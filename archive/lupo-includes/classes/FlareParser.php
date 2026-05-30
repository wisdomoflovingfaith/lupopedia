<?php
/**
 * FLARE frontmatter parser for Lupopedia. Supports depth-2 nesting, indentation-sensitive
 * sections, inline objects (e.g. { to: "...", type: "...", weight: 0.9 }), and arrays of objects.
 * On parse failure logs to lupo-actors/0/logs/parser_errors.log and returns empty headers.
 * PHP 5.3+ compatible.
 *
 * @package Lupopedia
 */

class FlareParser
{
    const MAX_DEPTH = 2;
    const PARSER_LOG = 'lupo-actors/0/logs/parser_errors.log';

    /**
     * Parse FLARE frontmatter from content. Returns array with 'headers' (nested assoc) and 'body'.
     *
     * @param string $content Full file content
     * @param string $base_path Optional base path for log (LUPOPEDIA_ABSPATH)
     * @return array array('headers' => array(), 'body' => string)
     */
    public static function parse($content, $base_path = '')
    {
        $data = array('headers' => array(), 'body' => '');
        $lines = explode("\n", $content);
        $in_header = false;
        $current_section = null;
        $current_depth = 0;
        $section_stack = array();
        $depth_stack = array();

        foreach ($lines as $i => $line) {
            $trimmed = trim($line);
            if ($trimmed === '---') {
                $in_header = !$in_header;
                if (!$in_header) {
                    $data['body'] = implode("\n", array_slice($lines, $i + 1));
                }
                continue;
            }
            if (!$in_header) {
                continue;
            }

            $indent = strlen($line) - strlen(ltrim($line));

            while (count($depth_stack) > 0 && $indent <= $depth_stack[count($depth_stack) - 1]) {
                array_pop($section_stack);
                array_pop($depth_stack);
            }

            if (preg_match('/^([a-z._]+):\s*(.*)$/s', $trimmed, $m)) {
                $key = $m[1];
                $val = trim(isset($m[2]) ? $m[2] : '');
                $ref =& $data['headers'];
                foreach ($section_stack as $parent) {
                    if (!isset($ref[$parent]) || !is_array($ref[$parent])) {
                        $ref[$parent] = array();
                    }
                    $ref =& $ref[$parent];
                }
                if (count($section_stack) >= self::MAX_DEPTH) {
                    $ref[$key] = $val;
                } else {
                    if ($val === '' || $val === '[]' || preg_match('/^\s*\[/', $val)) {
                        $ref[$key] = array();
                        $section_stack[] = $key;
                        $depth_stack[] = $indent;
                        $current_section = $key;
                        $current_depth = $indent;
                    } else {
                        $ref[$key] = self::cleanValue($val);
                    }
                }
                continue;
            }

            if (preg_match('/^-\s*\{?(.*)\}?\s*$/s', $trimmed, $m)) {
                $inner = isset($m[1]) ? trim($m[1], " \t\n\r{}") : '';
                $obj = self::parseInlineObject($inner);
                if (count($section_stack) > 0) {
                    $ref =& $data['headers'];
                    $n = count($section_stack);
                    for ($j = 0; $j < $n - 1; $j++) {
                        if (!isset($ref[$section_stack[$j]]) || !is_array($ref[$section_stack[$j]])) {
                            $ref[$section_stack[$j]] = array();
                        }
                        $ref =& $ref[$section_stack[$j]];
                    }
                    $last_key = $section_stack[$n - 1];
                    if (!isset($ref[$last_key]) || !is_array($ref[$last_key])) {
                        $ref[$last_key] = array();
                    }
                    $ref[$last_key][] = $obj;
                }
                continue;
            }

            if ($trimmed !== '' && count($section_stack) > 0) {
                $ref =& $data['headers'];
                foreach ($section_stack as $parent) {
                    $ref =& $ref[$parent];
                }
                if (preg_match('/^([a-z._]+):\s*(.*)$/s', $trimmed, $m2)) {
                    $ref[$m2[1]] = self::cleanValue(trim(isset($m2[2]) ? $m2[2] : ''));
                }
            }
        }

        return $data;
    }

    /**
     * Parse inline object string " to: \"x\", type: \"y\", weight: 0.9 " into assoc array.
     *
     * @param string $str
     * @return array
     */
    public static function parseInlineObject($str)
    {
        $out = array();
        $pairs = preg_split('/,\s*(?=(?:[^\"]*\"[^\"]*\")*[^\"]*$)/', $str);
        foreach ($pairs as $pair) {
            $pair = trim($pair);
            if (strpos($pair, ':') === false) {
                continue;
            }
            list($k, $v) = explode(':', $pair, 2);
            $k = trim($k);
            $v = self::cleanValue(trim($v));
            $out[$k] = $v;
        }
        return $out;
    }

    /**
     * Clean scalar: strip quotes, cast numbers.
     *
     * @param string $v
     * @return string|int|float
     */
    public static function cleanValue($v)
    {
        $v = trim($v);
        if (preg_match('/^["\'](.*)["\']$/s', $v, $m)) {
            return $m[1];
        }
        if (is_numeric($v)) {
            return strpos($v, '.') !== false ? (float) $v : (int) $v;
        }
        return $v;
    }

    /**
     * Log parse error to lupo-actors/0/logs/parser_errors.log.
     *
     * @param string $message
     * @param string $context Optional path or context
     * @param string $base_path LUPOPEDIA_ABSPATH or similar
     */
    public static function logError($message, $context = '', $base_path = '')
    {
        $log_dir = $base_path !== '' ? rtrim($base_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'lupo-actors' . DIRECTORY_SEPARATOR . '0' . DIRECTORY_SEPARATOR . 'logs' : '';
        if ($log_dir === '' || !is_dir($log_dir)) {
            return;
        }
        $log_file = $log_dir . DIRECTORY_SEPARATOR . 'parser_errors.log';
        $line = gmdate('Y-m-d\TH:i:s\Z') . ' ' . $message . ($context !== '' ? ' ' . $context : '') . "\n";
        @file_put_contents($log_file, $line, FILE_APPEND | LOCK_EX);
    }

    /**
     * Parse and return headers; on exception log and return empty headers + full body.
     *
     * @param string $content
     * @param string $base_path
     * @return array
     */
    public static function parseSafe($content, $base_path = '')
    {
        try {
            $parsed = self::parse($content, $base_path);
            if (!is_array($parsed) || !isset($parsed['headers'])) {
                self::logError('FlareParser::parse returned invalid structure', '', $base_path);
                return array('headers' => array(), 'body' => $content);
            }
            return $parsed;
        } catch (Exception $e) {
            self::logError('FlareParser exception: ' . $e->getMessage(), $e->getFile() . ':' . $e->getLine(), $base_path);
            return array('headers' => array(), 'body' => $content);
        }
    }
}
