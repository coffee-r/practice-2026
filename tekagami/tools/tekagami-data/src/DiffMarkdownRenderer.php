<?php

namespace TekagamiData\Report;

/**
 * DiffEngine::diff() の出力を Markdown に変換する。
 *
 * 変化のないセクションは省略する。
 */
class DiffMarkdownRenderer
{
    /**
     * @param array $diff  DiffEngine::diff() の戻り値
     * @return string
     */
    public function render(array $diff)
    {
        $lines = [];
        $lines[] = '# tekagami diff report';
        $lines[] = '';

        $legacyAt = isset($diff['legacy_generated_at']) ? $diff['legacy_generated_at'] : '-';
        $targetAt = isset($diff['target_generated_at']) ? $diff['target_generated_at'] : '-';
        $legacyN  = isset($diff['legacy_trace_count']) ? $diff['legacy_trace_count'] : 0;
        $targetN  = isset($diff['target_trace_count']) ? $diff['target_trace_count'] : 0;
        $lines[] = '- legacy: `' . $legacyAt . '` (' . $legacyN . ' traces)';
        $lines[] = '- target: `' . $targetAt . '` (' . $targetN . ' traces)';
        $lines[] = '';

        $eps = isset($diff['entrypoints']) && is_array($diff['entrypoints'])
            ? $diff['entrypoints'] : [];
        $added   = isset($eps['added'])   && is_array($eps['added'])   ? $eps['added']   : [];
        $removed = isset($eps['removed']) && is_array($eps['removed']) ? $eps['removed'] : [];
        $changed = isset($eps['changed']) && is_array($eps['changed']) ? $eps['changed'] : [];

        // entrypoint summary
        if (!empty($added) || !empty($removed)) {
            $lines[] = '## Entrypoints';
            $lines[] = '';
            if (!empty($added)) {
                $lines[] = '**Added (' . count($added) . '):** ' . implode(', ', array_map(function ($k) {
                    return '`' . $k . '`';
                }, $added));
            }
            if (!empty($removed)) {
                $lines[] = '**Removed (' . count($removed) . '):** ' . implode(', ', array_map(function ($k) {
                    return '`' . $k . '`';
                }, $removed));
            }
            $lines[] = '';
        }

        // per-entrypoint changes
        foreach ($changed as $epDiff) {
            $key = isset($epDiff['entrypoint_key']) ? $epDiff['entrypoint_key'] : '-';
            $lines[] = '## ' . $key;
            $lines[] = '';

            // status codes
            $scDiff = isset($epDiff['status_codes']) && is_array($epDiff['status_codes'])
                ? $epDiff['status_codes'] : [];
            $scSection = $this->renderStatusCodesDiff($scDiff);
            if (!empty($scSection)) {
                $lines[] = '### Status Codes';
                $lines[] = '';
                foreach ($scSection as $l) {
                    $lines[] = $l;
                }
                $lines[] = '';
            }

            // patterns
            $patsDiff = isset($epDiff['patterns']) && is_array($epDiff['patterns'])
                ? $epDiff['patterns'] : [];
            $pAdded   = isset($patsDiff['added'])   && is_array($patsDiff['added'])   ? $patsDiff['added']   : [];
            $pRemoved = isset($patsDiff['removed']) && is_array($patsDiff['removed']) ? $patsDiff['removed'] : [];
            $pChanged = isset($patsDiff['changed']) && is_array($patsDiff['changed']) ? $patsDiff['changed'] : [];

            if (!empty($pAdded)) {
                $lines[] = '### Patterns: Added (' . count($pAdded) . ')';
                $lines[] = '';
                foreach ($pAdded as $p) {
                    foreach ($this->renderPatternSummary($p) as $l) {
                        $lines[] = $l;
                    }
                }
                $lines[] = '';
            }
            if (!empty($pRemoved)) {
                $lines[] = '### Patterns: Removed (' . count($pRemoved) . ')';
                $lines[] = '';
                foreach ($pRemoved as $p) {
                    foreach ($this->renderPatternSummary($p) as $l) {
                        $lines[] = $l;
                    }
                }
                $lines[] = '';
            }
            if (!empty($pChanged)) {
                $lines[] = '### Patterns: Changed (' . count($pChanged) . ')';
                $lines[] = '';
                foreach ($pChanged as $ch) {
                    foreach ($this->renderPatternChange($ch) as $l) {
                        $lines[] = $l;
                    }
                }
                $lines[] = '';
            }
        }

        // meaning-near-matches
        $mnm = isset($diff['meaning_near_matches']) && is_array($diff['meaning_near_matches'])
            ? $diff['meaning_near_matches'] : [];
        if (!empty($mnm)) {
            $lines[] = '## Meaning-Near-Matches (同一 fp・異 SQL)';
            $lines[] = '';
            $lines[] = '> layer-B fp が一致するが SQL 文字列が異なる候補。等価保証ではなく意味近似一致候補です。';
            $lines[] = '';
            $lines[] = '| fp | legacy SQL | target SQL |';
            $lines[] = '|---|---|---|';
            foreach ($mnm as $m) {
                $fp    = isset($m['fp']) ? $m['fp'] : '';
                $lSqls = isset($m['legacy_sqls']) && is_array($m['legacy_sqls']) ? $m['legacy_sqls'] : [];
                $tSqls = isset($m['target_sqls']) && is_array($m['target_sqls']) ? $m['target_sqls'] : [];
                $lines[] = '| `' . $this->cell($fp) . '` | `' . $this->cell(implode(' / ', $lSqls)) . '` | `' . $this->cell(implode(' / ', $tSqls)) . '` |';
            }
            $lines[] = '';
        }

        return implode("\n", $lines) . "\n";
    }

    // -------------------------------------------------------------------------

    /**
     * @param array $scDiff  {added, removed, changed}
     * @return string[]
     */
    private function renderStatusCodesDiff(array $scDiff)
    {
        $added   = isset($scDiff['added'])   && is_array($scDiff['added'])   ? $scDiff['added']   : [];
        $removed = isset($scDiff['removed']) && is_array($scDiff['removed']) ? $scDiff['removed'] : [];
        $changed = isset($scDiff['changed']) && is_array($scDiff['changed']) ? $scDiff['changed'] : [];

        if (empty($added) && empty($removed) && empty($changed)) {
            return [];
        }

        $lines = [];
        $lines[] = '| Code | Legacy | Target | Delta |';
        $lines[] = '|---|---:|---:|---:|';

        foreach ($removed as $r) {
            $lines[] = '| ' . $r['code'] . ' | ' . $r['legacy_count'] . ' | - | removed |';
        }
        foreach ($added as $a) {
            $lines[] = '| ' . $a['code'] . ' | - | ' . $a['target_count'] . ' | added |';
        }
        foreach ($changed as $c) {
            $delta = $c['target_count'] - $c['legacy_count'];
            $lines[] = '| ' . $c['code'] . ' | ' . $c['legacy_count'] . ' | ' . $c['target_count'] . ' | ' . ($delta >= 0 ? '+' . $delta : $delta) . ' |';
        }

        return $lines;
    }

    /**
     * @param array $p  pattern summary
     * @return string[]
     */
    private function renderPatternSummary(array $p)
    {
        $lines = [];
        $sig    = isset($p['signature']) ? $p['signature'] : '';
        $stats  = isset($p['statuses']) && is_array($p['statuses']) ? implode(', ', $p['statuses']) : '';
        $custom = isset($p['custom_events']) && is_array($p['custom_events']) ? $p['custom_events'] : [];
        $effects = isset($p['effects']) && is_array($p['effects']) ? $p['effects'] : [];

        $lines[] = '- sig: `' . $sig . '`';
        if ($stats !== '') {
            $lines[] = '  - statuses: `' . $stats . '`';
        }
        foreach ($effects as $e) {
            $lines[] = '  - effect: ' . (isset($e['op']) ? $e['op'] : '') . ' ' . (isset($e['table']) ? $e['table'] : '') . ' ×' . (isset($e['count']) ? $e['count'] : 0);
        }
        foreach ($custom as $label) {
            $lines[] = '  - custom: `' . $label . '`';
        }
        return $lines;
    }

    /**
     * @param array $ch  pattern change
     * @return string[]
     */
    private function renderPatternChange(array $ch)
    {
        $lines = [];
        $lSig = isset($ch['legacy_signature']) ? $ch['legacy_signature'] : '';
        $tSig = isset($ch['target_signature']) ? $ch['target_signature'] : '';

        if ($lSig === $tSig) {
            $lines[] = '**Pattern** `' . $lSig . '`';
        } else {
            $lines[] = '**Pattern** (semantic key match)';
            $lines[] = '- legacy sig: `' . $lSig . '`';
            $lines[] = '- target sig: `' . $tSig . '`';
        }

        // statuses
        $statuses = isset($ch['statuses']) && is_array($ch['statuses']) ? $ch['statuses'] : [];
        $sAdded   = isset($statuses['added'])   && is_array($statuses['added'])   ? $statuses['added']   : [];
        $sRemoved = isset($statuses['removed']) && is_array($statuses['removed']) ? $statuses['removed'] : [];
        foreach ($sAdded   as $s) { $lines[] = '- status added: `' . $s . '`'; }
        foreach ($sRemoved as $s) { $lines[] = '- status removed: `' . $s . '`'; }

        // effects
        $effectsDiff = isset($ch['effects']) && is_array($ch['effects']) ? $ch['effects'] : [];
        foreach (isset($effectsDiff['added'])   ? $effectsDiff['added']   : [] as $e) {
            $lines[] = '- effect added: ' . (isset($e['op']) ? $e['op'] : '') . ' ' . (isset($e['table']) ? $e['table'] : '');
        }
        foreach (isset($effectsDiff['removed']) ? $effectsDiff['removed'] : [] as $e) {
            $lines[] = '- effect removed: ' . (isset($e['op']) ? $e['op'] : '') . ' ' . (isset($e['table']) ? $e['table'] : '');
        }
        foreach (isset($effectsDiff['changed']) ? $effectsDiff['changed'] : [] as $e) {
            $lines[] = '- effect changed: ' . $e['op'] . ' ' . $e['table'] . ' legacy=' . $e['legacy_count'] . ' target=' . $e['target_count'];
        }

        // custom events
        $customDiff = isset($ch['custom_events']) && is_array($ch['custom_events']) ? $ch['custom_events'] : [];
        foreach (isset($customDiff['added'])   ? $customDiff['added']   : [] as $label) {
            $lines[] = '- custom event added: `' . $label . '`';
        }
        foreach (isset($customDiff['removed']) ? $customDiff['removed'] : [] as $label) {
            $lines[] = '- custom event removed: `' . $label . '`';
        }

        // sql_flow fps (通常は空)
        $fpsDiff = isset($ch['sql_flow_fps']) && is_array($ch['sql_flow_fps']) ? $ch['sql_flow_fps'] : [];
        foreach (isset($fpsDiff['added'])   ? $fpsDiff['added']   : [] as $fp) {
            $lines[] = '- fp added: `' . $fp . '`';
        }
        foreach (isset($fpsDiff['removed']) ? $fpsDiff['removed'] : [] as $fp) {
            $lines[] = '- fp removed: `' . $fp . '`';
        }

        return $lines;
    }

    /**
     * Markdown テーブルセル内のパイプをエスケープする。
     *
     * @param string $value
     * @return string
     */
    private function cell($value)
    {
        return str_replace(['|', '`'], ['&#124;', "'"], (string) $value);
    }
}
