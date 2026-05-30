<?php
/**
 * ToonBridge.php — TOON ↔ JSON Bi-Directional Converter
 * 
 * Converts TOON (Token-Oriented Object Notation) files to JSON and back.
 * Used by the API to serve memory graph files to agents that don't read TOON.
 */

class ToonBridge {
    /**
     * Convert TOON file to JSON string
     * 
     * @param string $toonPath Absolute path to .toon file
     * @return string JSON string
     * @throws Exception if file not found or parse error
     */
    public function toonToJsonString($toonPath) {
        $data = $this->toonToArray($toonPath);
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
    /**
     * Convert TOON file to PHP array
     * 
     * @param string $toonPath Absolute path to .toon file
     * @return array Associative array representation
     */
    public function toonToArray($toonPath) {
        if (!file_exists($toonPath)) {
            throw new Exception("TOON file not found: {$toonPath}");
        }
        $content = file_get_contents($toonPath);
        return $this->parseToon($content);
    }
    /**
     * Parse TOON format into array
     * 
     * TOON format examples:
     * 
     * Simple YAML-style:
     *   id: 00_root_constitutional_system_requirements, type: prd, title: Root Constitutional
     *   
     *   edges:
     *     - to: lupo-docs/prd/02_channels_discussions.md, type: references
     *     - to: lupo-docs/prd/16_lupopedia_headers.md, type: references
     * 
     * JSON-style (legacy):
     *   {
     *     "id": "00_root_constitutional_system_requirements",
     *     "edges": {"outbound": [...]}
     *   }
     * 
     * @param string $content Raw TOON file content
     * @return array Parsed data
     */
    private function parseToon($content) {
        $lines = explode("\n", $content);
        $firstLine = trim($lines[0]);
        // Detect format: if starts with {, it's JSON-format TOON
        if (str_starts_with($firstLine, '{')) {
            return $this->parseJsonToon($content);
        }
        // Otherwise, parse YAML-style TOON
        return $this->parseYamlToon($lines);
    }
    /**
     * Parse JSON-format TOON (legacy or converted)
     */
    private function parseJsonToon($content) {
        $data = json_decode($content, true);
        if ($data === null) {
            throw new Exception("Invalid JSON TOON format");
        }
        return $data;
    }
    /**
     * Parse YAML-style TOON (canonical format)
     */
    private function parseYamlToon($lines) {
        $result = [];
        // Parse header line (comma-separated key: value pairs)
        $header = trim($lines[0]);
        $pairs = explode(',', $header);
        foreach ($pairs as $pair) {
            $pair = trim($pair);
            if (strpos($pair, ':') !== false) {
                list($key, $value) = explode(':', $pair, 2);
                $result[trim($key)] = trim($value);
            }
        }
        // Initialize edges
        $result['edges'] = ['outbound' => []];
        // Parse edges block
        $inEdges = false;
        for ($i = 1; $i < count($lines); $i++) {
            $line = trim($lines[$i]);
            if ($line === 'edges:') {
                $inEdges = true;
                continue;
            }
            if ($inEdges && preg_match('/^- to: (.+), type: (.+)$/', $line, $matches)) {
                $result['edges']['outbound'][] = [
                    'to' => $matches[1],
                    'type' => $matches[2]
                ];
            } elseif ($inEdges && !preg_match('/^\s/', $line)) {
                // Exit edges block when non-indented line appears
                $inEdges = false;
            }
        }
        // Add bridge metadata
        $result['_toon_bridge'] = [
            'source_format' => 'yaml',
            'converted_at' => gmdate('Y-m-d H:i:s'),
            'parser_version' => '1.0.0'
        ];
        return $result;
    }
    /**
     * Convert JSON string or array to TOON format
     * 
     * @param array|string $json JSON array or string
     * @return string TOON formatted string
     */
    public function jsonToToon($json) {
        if (is_string($json)) {
            $data = json_decode($json, true);
        } else {
            $data = $json;
        }
        if ($data === null) {
            throw new Exception("Invalid JSON input");
        }
        return $this->arrayToToon($data);
    }
    /**
     * Convert array to TOON format
     */
    private function arrayToToon($data) {
        $lines = [];
        // Build header line (id, type, title, status)
        $headerParts = [];
        foreach (['id', 'type', 'title', 'status'] as $key) {
            if (isset($data[$key])) {
                $headerParts[] = "{$key}: {$data[$key]}";
            }
        }
        $lines[] = implode(', ', $headerParts);
        $lines[] = '';
        // Add edges block
        $lines[] = 'edges:';
        if (isset($data['edges']['outbound']) && is_array($data['edges']['outbound'])) {
            foreach ($data['edges']['outbound'] as $edge) {
                $to = $edge['to'] ?? '';
                $type = $edge['type'] ?? 'references';
                $lines[] = "  - to: {$to}, type: {$type}";
            }
        }
        // Add provenance comment
        $lines[] = '';
        $lines[] = '# converted by toon_bridge.php at ' . gmdate('Y-m-d H:i:s');
        return implode("\n", $lines);
    }
    /**
     * Check if a path is within allowed memory directory
     * 
     * @param string $path Requested path
     * @param string $baseDir Base directory (LUPOPEDIA_PATH . '/lupo-memory')
     * @return bool True if safe
     */
    public static function isSafeMemoryPath($path, $baseDir) {
        // Prevent directory traversal
        $realPath = realpath($baseDir . '/' . ltrim($path, '/'));
        $realBase = realpath($baseDir);
        if ($realPath === false || $realBase === false) {
            return false;
        }
        return str_starts_with($realPath, $realBase);
    }
}
