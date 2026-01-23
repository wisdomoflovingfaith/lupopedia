<?php
/**
 * Tab Mapping Validator
 * 
 * Validates that content items have exactly one active tab mapping.
 * Detects duplicate mappings and orphaned content.
 * 
 * @package Lupopedia
 * @version 4.0.23
 * @author Captain Wolfie
 */

require_once(__DIR__ . '/../lupo-includes/header.php');

class TabMappingValidator {
    private $db;
    private $issues = [];
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    /**
     * Run all validation checks
     * 
     * @return array Validation results
     */
    public function runAll(): array {
        $this->checkDuplicateMappings();
        $this->checkOrphanedContent();
        $this->checkMissingMappings();
        
        return [
            'total_issues' => count($this->issues),
            'issues' => $this->issues,
            'status' => empty($this->issues) ? 'PASS' : 'FAIL'
        ];
    }
    
    /**
     * Check for duplicate active mappings (same content_id, multiple active tabs)
     */
    private function checkDuplicateMappings() {
        $sql = "
            SELECT 
                ctm.item_id AS content_id,
                c.slug AS content_slug,
                COUNT(*) AS mapping_count,
                GROUP_CONCAT(ct.slug ORDER BY ct.slug SEPARATOR ', ') AS tab_slugs,
                GROUP_CONCAT(ctm.collection_tab_id ORDER BY ctm.collection_tab_id SEPARATOR ', ') AS tab_ids
            FROM lupo_collection_tab_map ctm
            JOIN lupo_contents c ON ctm.item_id = c.content_id
            JOIN lupo_collection_tabs ct ON ctm.collection_tab_id = ct.collection_tab_id
            WHERE ctm.item_type = 'content'
              AND ctm.is_deleted = 0
            GROUP BY ctm.item_id
            HAVING COUNT(*) > 1
            ORDER BY mapping_count DESC, ctm.item_id
        ";
        
        $stmt = $this->db->query($sql);
        $duplicates = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($duplicates as $dup) {
            $this->issues[] = [
                'type' => 'duplicate_mapping',
                'severity' => 'error',
                'content_id' => $dup['content_id'],
                'content_slug' => $dup['content_slug'],
                'mapping_count' => $dup['mapping_count'],
                'tab_slugs' => $dup['tab_slugs'],
                'tab_ids' => $dup['tab_ids'],
                'message' => "Content '{$dup['content_slug']}' (ID: {$dup['content_id']}) has {$dup['mapping_count']} active tab mappings: {$dup['tab_slugs']}"
            ];
        }
    }
    
    /**
     * Check for orphaned content (mapped to non-existent tabs)
     */
    private function checkOrphanedContent() {
        $sql = "
            SELECT 
                ctm.collection_tab_map_id,
                ctm.item_id AS content_id,
                c.slug AS content_slug,
                ctm.collection_tab_id,
                ct.slug AS tab_slug
            FROM lupo_collection_tab_map ctm
            JOIN lupo_contents c ON ctm.item_id = c.content_id
            LEFT JOIN lupo_collection_tabs ct ON ctm.collection_tab_id = ct.collection_tab_id
            WHERE ctm.item_type = 'content'
              AND ctm.is_deleted = 0
              AND ct.collection_tab_id IS NULL
            ORDER BY ctm.item_id
        ";
        
        $stmt = $this->db->query($sql);
        $orphans = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($orphans as $orphan) {
            $this->issues[] = [
                'type' => 'orphaned_mapping',
                'severity' => 'error',
                'content_id' => $orphan['content_id'],
                'content_slug' => $orphan['content_slug'],
                'tab_id' => $orphan['collection_tab_id'],
                'message' => "Content '{$orphan['content_slug']}' (ID: {$orphan['content_id']}) mapped to non-existent tab ID: {$orphan['collection_tab_id']}"
            ];
        }
    }
    
    /**
     * Check for content items that should have mappings but don't
     * (Optional: only checks TOON-related content for now)
     */
    private function checkMissingMappings() {
        $sql = "
            SELECT 
                c.content_id,
                c.slug AS content_slug
            FROM lupo_contents c
            WHERE c.slug LIKE '%toon%'
              AND c.is_deleted = 0
              AND NOT EXISTS (
                  SELECT 1 FROM lupo_collection_tab_map ctm
                  WHERE ctm.item_id = c.content_id
                    AND ctm.item_type = 'content'
                    AND ctm.is_deleted = 0
              )
            ORDER BY c.content_id
        ";
        
        $stmt = $this->db->query($sql);
        $missing = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($missing as $miss) {
            $this->issues[] = [
                'type' => 'missing_mapping',
                'severity' => 'warning',
                'content_id' => $miss['content_id'],
                'content_slug' => $miss['content_slug'],
                'message' => "TOON-related content '{$miss['content_slug']}' (ID: {$miss['content_id']}) has no active tab mapping"
            ];
        }
    }
    
    /**
     * Get summary statistics
     */
    public function getStats(): array {
        $sql = "
            SELECT 
                COUNT(DISTINCT ctm.item_id) AS total_mapped_content,
                COUNT(*) AS total_active_mappings,
                COUNT(DISTINCT ctm.collection_tab_id) AS tabs_with_content
            FROM lupo_collection_tab_map ctm
            WHERE ctm.item_type = 'content'
              AND ctm.is_deleted = 0
        ";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// Main execution
try {
    $db = lupo_get_db();
    $validator = new TabMappingValidator($db);
    
    echo "🔍 Tab Mapping Validation\n";
    echo str_repeat("=", 60) . "\n\n";
    
    $results = $validator->runAll();
    $stats = $validator->getStats();
    
    echo "📊 Statistics:\n";
    echo "  Total mapped content items: {$stats['total_mapped_content']}\n";
    echo "  Total active mappings: {$stats['total_active_mappings']}\n";
    echo "  Tabs with content: {$stats['tabs_with_content']}\n\n";
    
    echo "🔍 Validation Results:\n";
    echo "  Status: {$results['status']}\n";
    echo "  Issues found: {$results['total_issues']}\n\n";
    
    if (!empty($results['issues'])) {
        echo "❌ Issues:\n\n";
        
        $byType = [];
        foreach ($results['issues'] as $issue) {
            $byType[$issue['type']][] = $issue;
        }
        
        foreach ($byType as $type => $issues) {
            echo "  {$type}:\n";
            foreach ($issues as $issue) {
                echo "    - {$issue['message']}\n";
            }
            echo "\n";
        }
        
        exit(1);
    } else {
        echo "✅ All checks passed. No duplicate mappings or orphaned content found.\n";
        exit(0);
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
