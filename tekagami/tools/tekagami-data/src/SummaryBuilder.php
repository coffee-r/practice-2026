<?php

namespace TekagamiData\Report;

/**
 * 100 endpoint 級の入口地図を作る軽量サマリ。
 */
class SummaryBuilder
{
    /** @var string */
    private $valueMode;

    /**
     * @param string $valueMode
     */
    public function __construct($valueMode = 'normalized')
    {
        $this->valueMode = in_array($valueMode, ['normalized', 'tokenized'], true)
            ? $valueMode
            : 'normalized';
    }

    /**
     * @param array $traces
     * @param array $metadata
     * @return array
     */
    public function build(array $traces, array $metadata = [])
    {
        $aggregator = new Aggregator($this->valueMode);
        $report = $aggregator->aggregate($traces);
        $catalog = [];

        foreach ($report['observed_entrypoints'] as $ep) {
            $key = isset($ep['entrypoint_key']) ? $ep['entrypoint_key'] : '';
            $catalog[$key] = [
                'group_hint'         => $this->groupHint(isset($ep['path']) ? $ep['path'] : ''),
                'entrypoint_key'     => $key,
                'observed_count'     => isset($ep['observed_count']) ? $ep['observed_count'] : 0,
                'status_codes'       => isset($ep['status_codes']) ? $ep['status_codes'] : [],
                'pattern_count'      => isset($ep['patterns']) && is_array($ep['patterns']) ? count($ep['patterns']) : 0,
                'rare_pattern_count' => $this->rarePatternCount($ep),
                'custom_event_count' => 0,
                'has_write_effects'  => $this->hasWriteEffects($ep),
                'error_count'        => isset($ep['error_count']) ? $ep['error_count'] : 0,
                'truncated_count'    => 0,
            ];
        }

        $customLabels = [];
        foreach ($traces as $trace) {
            $http = isset($trace['http']) && is_array($trace['http']) ? $trace['http'] : [];
            $key = $aggregator->entryKey($http);
            if (!isset($catalog[$key])) {
                continue;
            }
            if (!isset($customLabels[$key])) {
                $customLabels[$key] = [];
            }
            foreach (isset($trace['timeline']) && is_array($trace['timeline']) ? $trace['timeline'] : [] as $event) {
                if (isset($event['type'], $event['label']) && $event['type'] === 'custom') {
                    $customLabels[$key][(string)$event['label']] = true;
                }
            }
            if ($this->isTruncatedTrace($trace)) {
                $catalog[$key]['truncated_count']++;
            }
        }

        foreach ($customLabels as $key => $labels) {
            if (isset($catalog[$key])) {
                $catalog[$key]['custom_event_count'] = count($labels);
            }
        }

        return [
            'generated_at'              => isset($report['generated_at']) ? $report['generated_at'] : date('c'),
            'trace_count'               => isset($report['trace_count']) ? $report['trace_count'] : count($traces),
            'observed_endpoint_count'   => count($catalog),
            'observed_started_at_min'   => isset($report['observed_started_at_min']) ? $report['observed_started_at_min'] : null,
            'observed_started_at_max'   => isset($report['observed_started_at_max']) ? $report['observed_started_at_max'] : null,
            'value_mode'                => $this->valueMode,
            'input_file_count'          => isset($metadata['input_file_count']) ? $metadata['input_file_count'] : null,
            'source_trace_count'        => isset($metadata['source_trace_count']) ? $metadata['source_trace_count'] : count($traces),
            'filter'                    => isset($metadata['filter']) ? $metadata['filter'] : null,
            'observed_endpoint_catalog' => array_values($catalog),
        ];
    }

    /**
     * @param array $ep
     * @return int
     */
    private function rarePatternCount(array $ep)
    {
        $count = 0;
        $patterns = isset($ep['patterns']) && is_array($ep['patterns']) ? $ep['patterns'] : [];
        foreach ($patterns as $pattern) {
            if (isset($pattern['count']) && (int)$pattern['count'] === 1) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * @param array $ep
     * @return bool
     */
    private function hasWriteEffects(array $ep)
    {
        $patterns = isset($ep['patterns']) && is_array($ep['patterns']) ? $ep['patterns'] : [];
        foreach ($patterns as $pattern) {
            $effects = isset($pattern['effects']) && is_array($pattern['effects']) ? $pattern['effects'] : [];
            if (count($effects) > 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array $trace
     * @return bool
     */
    private function isTruncatedTrace(array $trace)
    {
        $errors = isset($trace['errors']) && is_array($trace['errors']) ? $trace['errors'] : [];
        foreach ($errors as $error) {
            $message = isset($error['message']) ? (string)$error['message'] : '';
            if (strpos($message, 'timeline truncated:') !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * URL prefix 由来の機械的なまとまり。業務名ではない。
     *
     * @param string $path
     * @return string
     */
    private function groupHint($path)
    {
        $path = trim((string)$path);
        if ($path === '' || $path === 'unknown') {
            return 'unknown';
        }

        $parts = array_values(array_filter(explode('/', trim($path, '/')), function ($part) {
            return $part !== '';
        }));

        if (count($parts) === 0) {
            return '/';
        }
        if ($parts[0] === 'api' && count($parts) >= 2) {
            return '/api/' . $parts[1];
        }
        return '/' . $parts[0];
    }
}
