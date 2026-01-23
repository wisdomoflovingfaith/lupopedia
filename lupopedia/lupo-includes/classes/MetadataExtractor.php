<?php
/**
 * MetadataExtractor - Big Rock 2: Dialog Channel Migration
 * 
 * Extracts and preserves metadata from .md logs for dialog channel migration.
 * This component systematically processes historical markdown files to extract
 * structured metadata for dialog-based navigation and cross-reference intelligence.
 * 
 * @version 4.0.66
 * @author GLOBAL_CURRENT_AUTHORS
 */

class MetadataExtractor {
    
    private $metadataCache = [];
    private $crossReferenceMap = [];
    private $emotionalGeometryMap = [];
    
    /**
     * Extract metadata from all historical .md files
     */
    public function extractAllMetadata(): array {
        $historyDir = dirname(__DIR__, 2) . '/docs/history';
        $metadata = [];
        
        // Process all historical markdown files
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($historyDir)
        );
        
        foreach ($iterator as $file) {
            if ($file->getExtension() === 'md' && $file->getFilename() !== 'README.md') {
                $filePath = $file->getPathname();
                $relativePath = str_replace(dirname(__DIR__, 2) . '/', '', $filePath);
                
                $fileMetadata = $this->extractFileMetadata($filePath, $relativePath);
                if ($fileMetadata) {
                    $metadata[$relativePath] = $fileMetadata;
                }
            }
        }
        
        // Build cross-reference and emotional geometry maps
        $this->buildCrossReferenceMap($metadata);
        $this->buildEmotionalGeometryMap($metadata);
        
        return $metadata;
    }
    
    /**
     * Extract metadata from a single markdown file
     */
    public function extractFileMetadata(string $filePath, string $relativePath): ?array {
        if (!file_exists($filePath)) {
            return null;
        }
        
        $content = file_get_contents($filePath);
        if (!$content) {
            return null;
        }
        
        // Extract YAML frontmatter
        $frontmatter = $this->extractYAMLFrontmatter($content);
        if (!$frontmatter) {
            return null;
        }
        
        // Extract content metadata
        $contentMetadata = $this->extractContentMetadata($content);
        
        // Extract emotional geometry
        $emotionalGeometry = $this->extractEmotionalGeometry($content);
        
        // Extract cross-references
        $crossReferences = $this->extractCrossReferences($content);
        
        // Determine era and sensitivity
        $era = $this->determineEra($relativePath);
        $sensitivity = $this->determineSensitivity($relativePath, $content);
        
        return [
            'file_path' => $relativePath,
            'frontmatter' => $frontmatter,
            'content' => $contentMetadata,
            'emotional_geometry' => $emotionalGeometry,
            'cross_references' => $crossReferences,
            'era' => $era,
            'sensitivity' => $sensitivity,
            'extracted_at' => date('YmdHis'),
            'file_size' => filesize($filePath),
            'word_count' => str_word_count(strip_tags($content))
        ];
    }
    
    /**
     * Extract YAML frontmatter from markdown content
     */
    private function extractYAMLFrontmatter(string $content): ?array {
        // Look for YAML frontmatter between --- markers
        if (preg_match('/^---\s*\n(.*?)\n---\s*\n/s', $content, $matches)) {
            $yaml = $matches[1];
            
            // Parse YAML (simple parsing for key: value pairs)
            $frontmatter = [];
            $lines = explode("\n", $yaml);
            
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line) || strpos($line, '#') === 0) {
                    continue;
                }
                
                if (strpos($line, ':') !== false) {
                    list($key, $value) = explode(':', $line, 2);
                    $key = trim($key);
                    $value = trim($value);
                    
                    // Handle different value types
                    if (strpos($value, '[') === 0 && strpos($value, ']') === strlen($value) - 1) {
                        // Array value
                        $value = trim($value, '[]');
                        $items = array_map('trim', explode(',', $value));
                        $frontmatter[$key] = $items;
                    } elseif (in_array($value, ['true', 'false'])) {
                        // Boolean value
                        $frontmatter[$key] = $value === 'true';
                    } elseif (is_numeric($value)) {
                        // Numeric value
                        $frontmatter[$key] = is_float($value) ? (float)$value : (int)$value;
                    } else {
                        // String value
                        $frontmatter[$key] = trim($value, '"\'');
                    }
                }
            }
            
            return $frontmatter;
        }
        
        return null;
    }
    
    /**
     * Extract content metadata from markdown content
     */
    private function extractContentMetadata(string $content): array {
        $metadata = [];
        
        // Extract title from first # heading
        if (preg_match('/^#\s+(.+)$/m', $content, $matches)) {
            $metadata['title'] = trim($matches[1]);
        }
        
        // Extract major sections
        if (preg_match_all('/^##\s+(.+)$/m', $content, $matches)) {
            $metadata['sections'] = $matches[1];
        }
        
        // Extract key events/bullet points
        if (preg_match_all('/^- \*\*(.+?)\*\*:\s*(.+)$/m', $content, $matches)) {
            $metadata['key_events'] = array_map(function($category, $description) {
                return [
                    'category' => trim($category),
                    'description' => trim($description)
                ];
            }, $matches[1], $matches[2]);
        }
        
        // Extract technical achievements
        if (preg_match_all('/### 🏗️ (.+)$/m', $content, $matches)) {
            $metadata['technical_categories'] = $matches[1];
        }
        
        // Extract years mentioned in content
        if (preg_match_all('/\b(19|20)\d{2}\b/', $content, $matches)) {
            $metadata['years_mentioned'] = array_unique($matches[0]);
        }
        
        return $metadata;
    }
    
    /**
     * Extract emotional geometry from content
     */
    private function extractEmotionalGeometry(string $content): array {
        $emotionalGeometry = [];
        
        // Look for Emotional Geometry section
        if (preg_match('/## Emotional Geometry\s*\n(.*?)\n##/s', $content, $matches)) {
            $section = $matches[1];
            
            // Extract axes
            if (preg_match_all('/### 🎨 (.+?)\s*\n- (.+)$/m', $section, $matches)) {
                $emotionalGeometry['creative_axis'] = [
                    'title' => trim($matches[1][0] ?? ''),
                    'items' => array_map('trim', $matches[2])
                ];
            }
            
            if (preg_match_all('/### 📈 (.+?)\s*\n- (.+)$/m', $section, $matches)) {
                $emotionalGeometry['growth_axis'] = [
                    'title' => trim($matches[1][0] ?? ''),
                    'items' => array_map('trim', $matches[2])
                ];
            }
            
            if (preg_match_all('/### 🏗️ (.+?)\s*\n- (.+)$/m', $section, $matches)) {
                $emotionalGeometry['foundation_axis'] = [
                    'title' => trim($matches[1][0] ?? ''),
                    'items' => array_map('trim', $matches[2])
                ];
            }
        }
        
        return $emotionalGeometry;
    }
    
    /**
     * Extract cross-references from content
     */
    private function extractCrossReferences(string $content): array {
        $crossReferences = [];
        
        // Extract markdown links
        if (preg_match_all('/\[(.+?)\]\((.+?)\)/', $content, $matches)) {
            foreach ($matches[1] as $i => $text) {
                $url = $matches[2][$i];
                if (strpos($url, '.md') !== false) {
                    $crossReferences[] = [
                        'text' => trim($text),
                        'target' => $url,
                        'type' => 'internal_link'
                    ];
                }
            }
        }
        
        // Extract Related Years section
        if (preg_match('/### 🔗 Related Years\s*\n(.*?)\n###/s', $content, $matches)) {
            $section = $matches[1];
            if (preg_match_all('/- \*\*(\d+\.md)\*\*:\s*(.+)$/m', $section, $yearMatches)) {
                foreach ($yearMatches[1] as $i => $year) {
                    $crossReferences[] = [
                        'text' => trim($yearMatches[2][$i]),
                        'target' => $year,
                        'type' => 'related_year'
                    ];
                }
            }
        }
        
        return $crossReferences;
    }
    
    /**
     * Determine historical era from file path
     */
    private function determineEra(string $relativePath): string {
        if (strpos($relativePath, '1996-2013') !== false) {
            return 'active_development';
        } elseif (strpos($relativePath, '2014-2025') !== false) {
            return 'hiatus';
        } elseif (strpos($relativePath, 'future') !== false) {
            return 'resurgence';
        }
        
        return 'unknown';
    }
    
    /**
     * Determine content sensitivity
     */
    private function determineSensitivity(string $relativePath, string $content): array {
        $sensitivity = [
            'level' => 'low',
            'topics' => [],
            'handling_required' => false
        ];
        
        // Check for hiatus period (high sensitivity)
        if ($this->determineEra($relativePath) === 'hiatus') {
            $sensitivity['level'] = 'high';
            $sensitivity['topics'][] = 'personal_tragedy';
            $sensitivity['topics'][] = 'hiatus';
            $sensitivity['handling_required'] = true;
        }
        
        // Check for sensitive keywords
        $sensitiveKeywords = ['tragedy', 'loss', 'grief', 'absence', 'hiatus', 'personal'];
        foreach ($sensitiveKeywords as $keyword) {
            if (stripos($content, $keyword) !== false) {
                $sensitivity['level'] = 'medium';
                $sensitivity['topics'][] = $keyword;
                $sensitivity['handling_required'] = true;
            }
        }
        
        return $sensitivity;
    }
    
    /**
     * Build cross-reference map for intelligent suggestions
     */
    private function buildCrossReferenceMap(array $metadata): void {
        $this->crossReferenceMap = [];
        
        foreach ($metadata as $filePath => $data) {
            $year = $this->extractYearFromPath($filePath);
            if ($year) {
                $this->crossReferenceMap[$year] = [
                    'file_path' => $filePath,
                    'title' => $data['content']['title'] ?? '',
                    'era' => $data['era'],
                    'cross_references' => $data['cross_references'],
                    'key_events' => $data['content']['key_events'] ?? [],
                    'sensitivity' => $data['sensitivity']
                ];
            }
        }
        
        ksort($this->crossReferenceMap);
    }
    
    /**
     * Build emotional geometry map for context-aware responses
     */
    private function buildEmotionalGeometryMap(array $metadata): void {
        $this->emotionalGeometryMap = [];
        
        foreach ($metadata as $filePath => $data) {
            $year = $this->extractYearFromPath($filePath);
            if ($year && !empty($data['emotional_geometry'])) {
                $this->emotionalGeometryMap[$year] = [
                    'era' => $data['era'],
                    'emotional_geometry' => $data['emotional_geometry'],
                    'sensitivity' => $data['sensitivity']
                ];
            }
        }
        
        ksort($this->emotionalGeometryMap);
    }
    
    /**
     * Extract year from file path
     */
    private function extractYearFromPath(string $filePath): ?string {
        if (preg_match('/(\d{4})\.md$/', $filePath, $matches)) {
            return $matches[1];
        }
        return null;
    }
    
    /**
     * Get cross-reference suggestions for a given year
     */
    public function getCrossReferenceSuggestions(string $year): array {
        $suggestions = [];
        
        if (isset($this->crossReferenceMap[$year])) {
            $data = $this->crossReferenceMap[$year];
            
            // Add forward references
            $currentYear = (int)$year;
            for ($i = $currentYear + 1; $i <= $currentYear + 3; $i++) {
                $nextYear = (string)$i;
                if (isset($this->crossReferenceMap[$nextYear])) {
                    $suggestions['forward'][] = [
                        'year' => $nextYear,
                        'title' => $this->crossReferenceMap[$nextYear]['title'],
                        'reason' => 'subsequent_development'
                    ];
                }
            }
            
            // Add backward references
            for ($i = $currentYear - 1; $i >= $currentYear - 3; $i--) {
                $prevYear = (string)$i;
                if (isset($this->crossReferenceMap[$prevYear])) {
                    $suggestions['backward'][] = [
                        'year' => $prevYear,
                        'title' => $this->crossReferenceMap[$prevYear]['title'],
                        'reason' => 'foundational_development'
                    ];
                }
            }
            
            // Add era-based suggestions
            $era = $data['era'];
            foreach ($this->crossReferenceMap as $refYear => $refData) {
                if ($refData['era'] === $era && $refYear !== $year) {
                    $suggestions['era_related'][] = [
                        'year' => $refYear,
                        'title' => $refData['title'],
                        'reason' => 'same_era_development'
                    ];
                }
            }
        }
        
        return $suggestions;
    }
    
    /**
     * Get emotional context for a given year
     */
    public function getEmotionalContext(string $year): array {
        if (isset($this->emotionalGeometryMap[$year])) {
            return $this->emotionalGeometryMap[$year];
        }
        
        return [
            'era' => 'unknown',
            'emotional_geometry' => [],
            'sensitivity' => ['level' => 'low', 'topics' => [], 'handling_required' => false]
        ];
    }
    
    /**
     * Export metadata for dialog system integration
     */
    public function exportForDialogSystem(): array {
        return [
            'cross_reference_map' => $this->crossReferenceMap,
            'emotional_geometry_map' => $this->emotionalGeometryMap,
            'total_files_processed' => count($this->metadataCache),
            'eras_covered' => ['active_development', 'hiatus', 'resurgence'],
            'sensitive_periods' => array_filter($this->emotionalGeometryMap, function($data) {
                return $data['sensitivity']['handling_required'];
            }),
            'exported_at' => date('YmdHis')
        ];
    }
}
