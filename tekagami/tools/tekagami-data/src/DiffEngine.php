<?php

namespace TekagamiData\Report;

/**
 * 2つの export.json を比較して差分を返す。
 *
 * 出力は決定論的（全配列ソート済み）。AI 要約なし。
 */
class DiffEngine
{
    /**
     * @param array $legacy  legacy export.json の decoded array
     * @param array $target  target export.json の decoded array
     * @return array
     */
    public function diff(array $legacy, array $target)
    {
        $legacyEps = $this->indexByKey($legacy);
        $targetEps = $this->indexByKey($target);

        $allKeys = array_unique(array_merge(array_keys($legacyEps), array_keys($targetEps)));
        sort($allKeys);

        $added   = [];
        $removed = [];
        $changed = [];

        foreach ($allKeys as $key) {
            $inLegacy = isset($legacyEps[$key]);
            $inTarget = isset($targetEps[$key]);

            if ($inLegacy && !$inTarget) {
                $removed[] = $key;
            } elseif (!$inLegacy && $inTarget) {
                $added[] = $key;
            } else {
                $epDiff = $this->diffEntrypoint($legacyEps[$key], $targetEps[$key]);
                if ($epDiff !== null) {
                    $changed[] = $epDiff;
                }
            }
        }

        $legacyFpMap = $this->buildFpToSqlMap($legacy);
        $targetFpMap = $this->buildFpToSqlMap($target);
        $meaning     = $this->findMeaningNearMatches($legacyFpMap, $targetFpMap);

        return [
            'legacy_generated_at' => isset($legacy['generated_at']) ? $legacy['generated_at'] : null,
            'target_generated_at' => isset($target['generated_at']) ? $target['generated_at'] : null,
            'legacy_trace_count'  => isset($legacy['trace_count']) ? (int) $legacy['trace_count'] : 0,
            'target_trace_count'  => isset($target['trace_count']) ? (int) $target['trace_count'] : 0,
            'entrypoints' => [
                'added'   => $added,
                'removed' => $removed,
                'changed' => $changed,
            ],
            'meaning_near_matches' => $meaning,
        ];
    }

    // -------------------------------------------------------------------------
    // Private: entrypoint diff
    // -------------------------------------------------------------------------

    /**
     * @param array $legacyEp
     * @param array $targetEp
     * @return array|null  null = no change
     */
    private function diffEntrypoint(array $legacyEp, array $targetEp)
    {
        $lCodes = isset($legacyEp['status_codes']) && is_array($legacyEp['status_codes'])
            ? $legacyEp['status_codes'] : [];
        $tCodes = isset($targetEp['status_codes']) && is_array($targetEp['status_codes'])
            ? $targetEp['status_codes'] : [];
        $statusCodesDiff = $this->diffStatusCodes($lCodes, $tCodes);

        $lPats = isset($legacyEp['patterns']) && is_array($legacyEp['patterns'])
            ? $legacyEp['patterns'] : [];
        $tPats = isset($targetEp['patterns']) && is_array($targetEp['patterns'])
            ? $targetEp['patterns'] : [];
        $patternsDiff = $this->diffPatterns($lPats, $tPats);

        $hasChange = !empty($statusCodesDiff['added'])
            || !empty($statusCodesDiff['removed'])
            || !empty($statusCodesDiff['changed'])
            || !empty($patternsDiff['added'])
            || !empty($patternsDiff['removed'])
            || !empty($patternsDiff['changed']);

        if (!$hasChange) {
            return null;
        }

        return [
            'entrypoint_key' => isset($legacyEp['entrypoint_key']) ? $legacyEp['entrypoint_key'] : '',
            'status_codes'   => $statusCodesDiff,
            'patterns'       => $patternsDiff,
        ];
    }

    // -------------------------------------------------------------------------
    // Private: pattern diff
    // -------------------------------------------------------------------------

    /**
     * @param array $legacyPatterns
     * @param array $targetPatterns
     * @return array  {added, removed, changed}
     */
    private function diffPatterns(array $legacyPatterns, array $targetPatterns)
    {
        $legacyByKey = $this->indexPatternsBySemanticKey($legacyPatterns);
        $targetByKey = $this->indexPatternsBySemanticKey($targetPatterns);

        $allKeys = array_unique(array_merge(array_keys($legacyByKey), array_keys($targetByKey)));
        sort($allKeys);

        $added   = [];
        $removed = [];
        $changed = [];

        foreach ($allKeys as $key) {
            $inL = isset($legacyByKey[$key]);
            $inT = isset($targetByKey[$key]);

            if ($inL && !$inT) {
                $removed[] = $this->patternSummary($legacyByKey[$key], $key);
            } elseif (!$inL && $inT) {
                $added[] = $this->patternSummary($targetByKey[$key], $key);
            } else {
                $ch = $this->diffPattern($legacyByKey[$key], $targetByKey[$key], $key);
                if ($ch !== null) {
                    $changed[] = $ch;
                }
            }
        }

        usort($added,   function ($a, $b) { return strcmp($a['signature'], $b['signature']); });
        usort($removed, function ($a, $b) { return strcmp($a['signature'], $b['signature']); });
        usort($changed, function ($a, $b) { return strcmp($a['semantic_key'], $b['semantic_key']); });

        return [
            'added'   => $added,
            'removed' => $removed,
            'changed' => $changed,
        ];
    }

    /**
     * @param array  $lp
     * @param array  $tp
     * @param string $semanticKey
     * @return array|null  null = no change
     */
    private function diffPattern(array $lp, array $tp, $semanticKey)
    {
        // statuses (as sorted string sets)
        $lStat = $this->statusStringSet($lp);
        $tStat = $this->statusStringSet($tp);
        $statusesAdded   = array_values(array_diff($tStat, $lStat));
        $statusesRemoved = array_values(array_diff($lStat, $tStat));

        // effects
        $effectsDiff = $this->diffEffects(
            isset($lp['effects']) && is_array($lp['effects']) ? $lp['effects'] : [],
            isset($tp['effects']) && is_array($tp['effects']) ? $tp['effects'] : []
        );

        // custom events
        $lCustom = self::parseCustomEvents(isset($lp['signature']) ? $lp['signature'] : '');
        $tCustom = self::parseCustomEvents(isset($tp['signature']) ? $tp['signature'] : '');
        $customAdded   = array_values(array_diff($tCustom, $lCustom));
        $customRemoved = array_values(array_diff($lCustom, $tCustom));

        // sql_flow fps (set diff — should usually be empty since semantic key matched)
        $lFpSet = $this->fpSet($lp);
        $tFpSet = $this->fpSet($tp);
        $fpsAdded   = array_values(array_diff($tFpSet, $lFpSet));
        $fpsRemoved = array_values(array_diff($lFpSet, $tFpSet));

        $hasChange = !empty($statusesAdded) || !empty($statusesRemoved)
            || !empty($effectsDiff['added']) || !empty($effectsDiff['removed']) || !empty($effectsDiff['changed'])
            || !empty($customAdded) || !empty($customRemoved)
            || !empty($fpsAdded) || !empty($fpsRemoved);

        if (!$hasChange) {
            return null;
        }

        return [
            'semantic_key'      => $semanticKey,
            'legacy_signature'  => isset($lp['signature']) ? $lp['signature'] : '',
            'target_signature'  => isset($tp['signature']) ? $tp['signature'] : '',
            'statuses'          => ['added' => $statusesAdded, 'removed' => $statusesRemoved],
            'effects'           => $effectsDiff,
            'custom_events'     => ['added' => $customAdded, 'removed' => $customRemoved],
            'sql_flow_fps'      => ['added' => $fpsAdded, 'removed' => $fpsRemoved],
        ];
    }

    // -------------------------------------------------------------------------
    // Private: semantic key
    // -------------------------------------------------------------------------

    /**
     * SQL fps + STATUS のみを使ったパターンのセマンティックキーを返す。
     * CUSTOM イベントは除外（別途差分比較するため）。
     * S-ID は参照しない（layer-B fp を使う）。
     *
     * @param array $pattern
     * @return string  JSON エンコードされたトークン列
     */
    private function buildSemanticKey(array $pattern)
    {
        $signature = isset($pattern['signature']) ? (string) $pattern['signature'] : '';
        $sqlFlow   = isset($pattern['sql_flow']) && is_array($pattern['sql_flow'])
            ? $pattern['sql_flow'] : [];

        $tokens = [];
        $sqlIdx = 0;

        foreach (explode(' -> ', $signature) as $part) {
            $part = trim($part);
            if ($part === '') {
                continue;
            }

            if (strpos($part, 'STATUS:') === 0) {
                $tokens[] = ['STATUS', substr($part, 7)];
                continue;
            }

            if (strpos($part, 'CUSTOM:') === 0 || strpos($part, 'TRUNCATED:') === 0) {
                // CUSTOM はセマンティックキーに含めない（変化として別途検出）
                // TRUNCATED はキャプチャ上限であり挙動差ではない
                continue;
            }

            // SQL token: "OP:TABLES:SID [xN]"
            $repeat = 1;
            $body   = $part;
            if (preg_match('/^(.*)\s+x(\d+)$/', $part, $m)) {
                $body   = $m[1];
                $repeat = (int) $m[2];
            }

            for ($i = 0; $i < $repeat; $i++) {
                if (isset($sqlFlow[$sqlIdx])) {
                    $step   = $sqlFlow[$sqlIdx];
                    $tables = isset($step['tables']) && is_array($step['tables'])
                        ? $step['tables'] : [];
                    sort($tables);
                    $tokens[] = [
                        'SQL',
                        isset($step['op']) ? $step['op'] : 'UNKNOWN',
                        $tables,
                        isset($step['fp']) ? $step['fp'] : null,
                    ];
                }
                $sqlIdx++;
            }
        }

        return json_encode($tokens, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * シグネチャ文字列から CUSTOM:label のラベル一覧を取得する（ソート済み・重複除去済み）。
     *
     * @param string $signature
     * @return string[]
     */
    private static function parseCustomEvents($signature)
    {
        $labels = [];
        foreach (explode(' -> ', $signature) as $part) {
            $part = trim($part);
            if (strpos($part, 'CUSTOM:') === 0) {
                $labels[] = substr($part, 7);
            }
        }
        sort($labels);
        return array_values(array_unique($labels));
    }

    // -------------------------------------------------------------------------
    // Private: status/effects diff
    // -------------------------------------------------------------------------

    /**
     * @param array $legacy  ['200' => 5, '404' => 1]
     * @param array $target  ['200' => 6]
     * @return array  {added, removed, changed}
     */
    private function diffStatusCodes(array $legacy, array $target)
    {
        $allCodes = array_unique(array_merge(array_keys($legacy), array_keys($target)));
        sort($allCodes);

        $added   = [];
        $removed = [];
        $changed = [];

        foreach ($allCodes as $code) {
            $inL = array_key_exists($code, $legacy);
            $inT = array_key_exists($code, $target);

            if ($inL && !$inT) {
                $removed[] = ['code' => (string) $code, 'legacy_count' => (int) $legacy[$code]];
            } elseif (!$inL && $inT) {
                $added[] = ['code' => (string) $code, 'target_count' => (int) $target[$code]];
            } elseif ((int) $legacy[$code] !== (int) $target[$code]) {
                $changed[] = [
                    'code'         => (string) $code,
                    'legacy_count' => (int) $legacy[$code],
                    'target_count' => (int) $target[$code],
                ];
            }
        }

        return ['added' => $added, 'removed' => $removed, 'changed' => $changed];
    }

    /**
     * @param array $legacy  effects[]
     * @param array $target  effects[]
     * @return array  {added, removed, changed}
     */
    private function diffEffects(array $legacy, array $target)
    {
        $indexFn = function (array $effects) {
            $map = [];
            foreach ($effects as $e) {
                $key = (isset($e['op']) ? $e['op'] : '') . '|' . (isset($e['table']) ? $e['table'] : '');
                $map[$key] = $e;
            }
            return $map;
        };

        $lMap    = $indexFn($legacy);
        $tMap    = $indexFn($target);
        $allKeys = array_unique(array_merge(array_keys($lMap), array_keys($tMap)));
        sort($allKeys);

        $added   = [];
        $removed = [];
        $changed = [];

        foreach ($allKeys as $key) {
            $inL = isset($lMap[$key]);
            $inT = isset($tMap[$key]);

            if ($inL && !$inT) {
                $removed[] = $lMap[$key];
            } elseif (!$inL && $inT) {
                $added[] = $tMap[$key];
            } elseif ((int) $lMap[$key]['count'] !== (int) $tMap[$key]['count']) {
                $changed[] = [
                    'op'           => $lMap[$key]['op'],
                    'table'        => $lMap[$key]['table'],
                    'legacy_count' => (int) $lMap[$key]['count'],
                    'target_count' => (int) $tMap[$key]['count'],
                ];
            }
        }

        return ['added' => $added, 'removed' => $removed, 'changed' => $changed];
    }

    // -------------------------------------------------------------------------
    // Private: meaning-near-match
    // -------------------------------------------------------------------------

    /**
     * fp → [distinct sql texts] マップを全エクスポートから生成する。
     *
     * @param array $export
     * @return array  ['fp_hash' => ['sql', ...], ...]
     */
    private function buildFpToSqlMap(array $export)
    {
        $map        = [];
        $dictionary = isset($export['sql_dictionary']) && is_array($export['sql_dictionary'])
            ? $export['sql_dictionary'] : [];
        $entrypoints = isset($export['observed_entrypoints']) && is_array($export['observed_entrypoints'])
            ? $export['observed_entrypoints'] : [];

        foreach ($entrypoints as $ep) {
            $patterns = isset($ep['patterns']) && is_array($ep['patterns']) ? $ep['patterns'] : [];
            foreach ($patterns as $pattern) {
                $flow = isset($pattern['sql_flow']) && is_array($pattern['sql_flow'])
                    ? $pattern['sql_flow'] : [];
                foreach ($flow as $step) {
                    $fp  = isset($step['fp']) ? $step['fp'] : null;
                    $sid = isset($step['s']) ? $step['s'] : null;
                    if ($fp === null || $sid === null) {
                        continue;
                    }
                    $sql = isset($dictionary[$sid]) ? $dictionary[$sid] : null;
                    if ($sql === null) {
                        continue;
                    }
                    if (!isset($map[$fp])) {
                        $map[$fp] = [];
                    }
                    if (!in_array($sql, $map[$fp], true)) {
                        $map[$fp][] = $sql;
                    }
                }
            }
        }

        return $map;
    }

    /**
     * @param array $legacyFpMap
     * @param array $targetFpMap
     * @return array  meaning-near-match 候補のリスト（fp 順ソート済み）
     */
    private function findMeaningNearMatches(array $legacyFpMap, array $targetFpMap)
    {
        $matches = [];

        foreach (array_intersect_key($legacyFpMap, $targetFpMap) as $fp => $_) {
            $lSqls = $legacyFpMap[$fp];
            $tSqls = $targetFpMap[$fp];
            sort($lSqls);
            sort($tSqls);
            if ($lSqls !== $tSqls) {
                $matches[] = [
                    'fp'          => $fp,
                    'legacy_sqls' => $lSqls,
                    'target_sqls' => $tSqls,
                ];
            }
        }

        usort($matches, function ($a, $b) { return strcmp($a['fp'], $b['fp']); });
        return $matches;
    }

    // -------------------------------------------------------------------------
    // Private: helpers
    // -------------------------------------------------------------------------

    /**
     * @param array $export
     * @return array  [entrypoint_key => ep]
     */
    private function indexByKey(array $export)
    {
        $map         = [];
        $entrypoints = isset($export['observed_entrypoints']) && is_array($export['observed_entrypoints'])
            ? $export['observed_entrypoints'] : [];
        foreach ($entrypoints as $ep) {
            $key = isset($ep['entrypoint_key']) ? $ep['entrypoint_key'] : '';
            if ($key !== '') {
                $map[$key] = $ep;
            }
        }
        return $map;
    }

    /**
     * @param array $patterns
     * @return array  [semantic_key => pattern]  重複時は最初のものを使用
     */
    private function indexPatternsBySemanticKey(array $patterns)
    {
        $map = [];
        foreach ($patterns as $p) {
            $key = $this->buildSemanticKey($p);
            if (!isset($map[$key])) {
                $map[$key] = $p;
            }
        }
        return $map;
    }

    /**
     * @param array  $pattern
     * @param string $semanticKey
     * @return array  {semantic_key, signature, statuses, effects, custom_events}
     */
    private function patternSummary(array $pattern, $semanticKey)
    {
        return [
            'semantic_key'  => $semanticKey,
            'signature'     => isset($pattern['signature']) ? $pattern['signature'] : '',
            'statuses'      => $this->statusStringSet($pattern),
            'effects'       => isset($pattern['effects']) && is_array($pattern['effects'])
                ? $pattern['effects'] : [],
            'custom_events' => self::parseCustomEvents(
                isset($pattern['signature']) ? $pattern['signature'] : ''
            ),
        ];
    }

    /**
     * @param array $pattern
     * @return string[]  ソート済み文字列ステータスセット
     */
    private function statusStringSet(array $pattern)
    {
        $statuses = isset($pattern['statuses']) && is_array($pattern['statuses'])
            ? $pattern['statuses'] : [];
        $set = array_unique(array_map('strval', $statuses));
        sort($set);
        return array_values($set);
    }

    /**
     * @param array $pattern
     * @return string[]  ソート済み fp セット（null 除外）
     */
    private function fpSet(array $pattern)
    {
        $flow = isset($pattern['sql_flow']) && is_array($pattern['sql_flow'])
            ? $pattern['sql_flow'] : [];
        $fps = [];
        foreach ($flow as $step) {
            if (isset($step['fp']) && $step['fp'] !== null) {
                $fps[] = $step['fp'];
            }
        }
        $set = array_values(array_unique($fps));
        sort($set);
        return $set;
    }
}
