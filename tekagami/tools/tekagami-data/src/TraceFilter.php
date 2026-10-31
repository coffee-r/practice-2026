<?php

namespace TekagamiData\Report;

/**
 * CLI の endpoint/path/method 条件で trace 配列を絞り込む。
 */
class TraceFilter
{
    /** @var string[] */
    private $entrypointPatterns;

    /** @var string[] */
    private $pathPatterns;

    /** @var string|null */
    private $method;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->entrypointPatterns = $this->stringList(isset($options['entrypoint']) ? $options['entrypoint'] : []);
        $this->pathPatterns       = $this->stringList(isset($options['path']) ? $options['path'] : []);
        $this->method             = isset($options['method']) && $options['method'] !== ''
            ? strtoupper((string)$options['method'])
            : null;
    }

    /**
     * @param array $traces
     * @return array
     */
    public function filter(array $traces)
    {
        if (!$this->hasFilters()) {
            return $traces;
        }

        $filtered = [];
        foreach ($traces as $trace) {
            if ($this->matches($trace)) {
                $filtered[] = $trace;
            }
        }

        return $filtered;
    }

    /**
     * @return bool
     */
    public function hasFilters()
    {
        return $this->method !== null
            || count($this->entrypointPatterns) > 0
            || count($this->pathPatterns) > 0;
    }

    /**
     * @return array
     */
    public function describe()
    {
        return [
            'entrypoint' => $this->entrypointPatterns,
            'path'       => $this->pathPatterns,
            'method'     => $this->method,
        ];
    }

    /**
     * @param array $trace
     * @return bool
     */
    private function matches(array $trace)
    {
        $http = isset($trace['http']) && is_array($trace['http']) ? $trace['http'] : [];
        $method = isset($http['method']) ? strtoupper((string)$http['method']) : 'UNKNOWN';

        if ($this->method !== null && $method !== $this->method) {
            return false;
        }

        if (count($this->entrypointPatterns) === 0 && count($this->pathPatterns) === 0) {
            return true;
        }

        $path = $this->preferredPath($http);
        $entrypoint = $method . ' ' . $path;

        foreach ($this->entrypointPatterns as $pattern) {
            if ($this->wildcardMatch($pattern, $entrypoint)) {
                return true;
            }
        }

        foreach ($this->pathPatterns as $pattern) {
            if ($this->wildcardMatch($pattern, $path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $http
     * @return string
     */
    private function preferredPath(array $http)
    {
        if (isset($http['path_pattern']) && $http['path_pattern'] !== null && $http['path_pattern'] !== '') {
            return (string)$http['path_pattern'];
        }
        if (isset($http['path']) && $http['path'] !== null && $http['path'] !== '') {
            return (string)$http['path'];
        }
        return 'unknown';
    }

    /**
     * @param string $pattern
     * @param string $value
     * @return bool
     */
    private function wildcardMatch($pattern, $value)
    {
        if (substr($pattern, -2) === '/*') {
            $base = substr($pattern, 0, -2);
            $regex = '/^' . preg_quote($base, '/') . '(?:\/.*)?$/i';
            return preg_match($regex, $value) === 1;
        }

        $regex = '/^' . str_replace('\\*', '.*', preg_quote($pattern, '/')) . '$/i';
        return preg_match($regex, $value) === 1;
    }

    /**
     * @param mixed $value
     * @return string[]
     */
    private function stringList($value)
    {
        if ($value === null) {
            return [];
        }
        if (!is_array($value)) {
            $value = [$value];
        }

        $result = [];
        foreach ($value as $item) {
            $item = trim((string)$item);
            if ($item !== '') {
                $result[] = $item;
            }
        }
        return array_values($result);
    }
}
