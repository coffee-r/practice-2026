<?php

namespace TekagamiData\Report;

/**
 * SummaryBuilder の出力を Markdown へ変換する。
 */
class SummaryMarkdownRenderer
{
    /**
     * @param array $summary
     * @return string
     */
    public function render(array $summary)
    {
        $lines = [];
        $lines[] = '# tekagami endpoint summary';
        $lines[] = '';
        $lines[] = '- 生成日時: `' . $this->cell(isset($summary['generated_at']) ? $summary['generated_at'] : '-') . '`';
        $lines[] = '- 入力ファイル数: `' . $this->cell(isset($summary['input_file_count']) ? $summary['input_file_count'] : '-') . '`';
        $lines[] = '- 入力トレース数: `' . $this->cell(isset($summary['source_trace_count']) ? $summary['source_trace_count'] : '-') . '`';
        $lines[] = '- 対象トレース数: `' . $this->cell(isset($summary['trace_count']) ? $summary['trace_count'] : 0) . '`';
        $lines[] = '- 観測endpoint数: `' . $this->cell(isset($summary['observed_endpoint_count']) ? $summary['observed_endpoint_count'] : 0) . '`';
        $lines[] = '- 観測期間: `' . $this->cell(isset($summary['observed_started_at_min']) ? $summary['observed_started_at_min'] : '-') . '` ～ `' . $this->cell(isset($summary['observed_started_at_max']) ? $summary['observed_started_at_max'] : '-') . '`';
        $lines[] = '';
        $lines[] = '> group_hint は URL prefix 由来の機械的なまとまりであり、業務名ではありません。';
        $lines[] = '';

        $filter = isset($summary['filter']) && is_array($summary['filter']) ? $summary['filter'] : null;
        if ($filter !== null) {
            $lines[] = '## Filter';
            $lines[] = '';
            $lines[] = '- entrypoint: `' . $this->cell($this->listValue(isset($filter['entrypoint']) ? $filter['entrypoint'] : [])) . '`';
            $lines[] = '- path: `' . $this->cell($this->listValue(isset($filter['path']) ? $filter['path'] : [])) . '`';
            $lines[] = '- method: `' . $this->cell(isset($filter['method']) && $filter['method'] !== null ? $filter['method'] : '-') . '`';
            $lines[] = '';
        }

        $lines[] = '## Observed Endpoint Catalog';
        $lines[] = '';
        $lines[] = '| group_hint | endpoint | count | statuses | patterns | rare | custom_events | writes | errors | truncated |';
        $lines[] = '|---|---|---:|---|---:|---:|---:|---|---:|---:|';

        $catalog = isset($summary['observed_endpoint_catalog']) && is_array($summary['observed_endpoint_catalog'])
            ? $summary['observed_endpoint_catalog']
            : [];
        if (count($catalog) === 0) {
            $lines[] = '| - | - | 0 | - | 0 | 0 | 0 | no | 0 | 0 |';
        }
        foreach ($catalog as $row) {
            $lines[] = '| '
                . $this->cell(isset($row['group_hint']) ? $row['group_hint'] : '-') . ' | '
                . $this->cell(isset($row['entrypoint_key']) ? $row['entrypoint_key'] : '-') . ' | '
                . $this->cell(isset($row['observed_count']) ? $row['observed_count'] : 0) . ' | '
                . $this->cell($this->statusList(isset($row['status_codes']) ? $row['status_codes'] : [])) . ' | '
                . $this->cell(isset($row['pattern_count']) ? $row['pattern_count'] : 0) . ' | '
                . $this->cell(isset($row['rare_pattern_count']) ? $row['rare_pattern_count'] : 0) . ' | '
                . $this->cell(isset($row['custom_event_count']) ? $row['custom_event_count'] : 0) . ' | '
                . $this->cell(!empty($row['has_write_effects']) ? 'yes' : 'no') . ' | '
                . $this->cell(isset($row['error_count']) ? $row['error_count'] : 0) . ' | '
                . $this->cell(isset($row['truncated_count']) ? $row['truncated_count'] : 0) . ' |';
        }

        return implode("\n", $lines) . "\n";
    }

    /**
     * @param array $statuses
     * @return string
     */
    private function statusList(array $statuses)
    {
        if (count($statuses) === 0) {
            return '-';
        }
        $parts = [];
        foreach ($statuses as $status => $count) {
            $parts[] = $status . ':' . $count;
        }
        return implode(', ', $parts);
    }

    /**
     * @param array $items
     * @return string
     */
    private function listValue(array $items)
    {
        return count($items) > 0 ? implode(', ', $items) : '-';
    }

    /**
     * @param mixed $value
     * @return string
     */
    private function cell($value)
    {
        return str_replace('|', '\\|', (string)$value);
    }
}
