<?php

class ConnectionsService
{
    protected $db;
    protected $domainId;

    public function __construct(PDO $db, $domainId)
    {
        $this->db       = $db;
        $this->domainId = (int)$domainId;
    }

    public function getConnectionsForContent($contentId)
    {
        $contentId = (int)$contentId;

        $atoms = $this->getConnectedAtoms($contentId);

        // Build hierarchical context
        $parents  = [];
        $children = [];
        $siblings = [];

        foreach ($atoms as $atom) {
            $atomId = (int)$atom['atom_id'];

            $parents  = array_merge($parents,  $this->getParentAtoms($atomId));
            $children = array_merge($children, $this->getChildAtoms($atomId));
            $siblings = array_merge($siblings, $this->getSiblingAtoms($atomId));
        }

        // Remove duplicates
        $parents  = $this->uniqueById($parents,  'atom_id');
        $children = $this->uniqueById($children, 'atom_id');
        $siblings = $this->uniqueById($siblings, 'atom_id');

        $relatedContent = $this->getRelatedContent($contentId);
        $edgeSummary    = $this->getEdgeTypeSummary($contentId);

        return [
            'atoms'            => $atoms,
            'parents'          => $parents,
            'children'         => $children,
            'siblings'         => $siblings,
            'related_content'  => $relatedContent,
            'edge_type_summary'=> $edgeSummary,
        ];
    }

    /* -------------------------------------------------------------
       1. DIRECT ATOM CONNECTIONS
       ------------------------------------------------------------- */

    protected function getConnectedAtoms($contentId)
    {
        global $table_prefix;
        
        // Fix 1: Use correct edge column names (left_object_type/left_object_id, right_object_type/right_object_id)
        // Fix 2: Use weight_score instead of weight
        // Fix 3: Use is_deleted = 0 instead of deleted_at IS NULL
        // Fix 4: Use atom_name instead of slug/label (alias for backward compatibility)
        // Fix 5: Use table prefix
        // Note: edge_type is varchar in edges table, not a foreign key - keeping edge_type for now
        $sql = "
            SELECT 
                e.edge_id,
                e.edge_type,
                e.weight_score AS weight,
                a.atom_id,
                a.atom_name AS atom_slug,
                a.atom_name AS atom_label
            FROM {$table_prefix}edges e
            JOIN {$table_prefix}atoms a ON a.atom_id = e.right_object_id
            WHERE e.left_object_type = 'content'
              AND e.left_object_id = :content_id
              AND e.right_object_type = 'atom'
              AND e.is_deleted = 0
            ORDER BY e.weight_score DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':content_id' => $contentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* -------------------------------------------------------------
       2. HIERARCHICAL CONTEXT (PARENTS / CHILDREN / SIBLINGS)
       ------------------------------------------------------------- */

    protected function getParentAtoms($atomId)
    {
        global $table_prefix;
        
        // Fix 1: Use correct edge column names (left_object_type/left_object_id, right_object_type/right_object_id)
        // Fix 2: Use weight_score instead of weight
        // Fix 3: Use is_deleted = 0 instead of deleted_at IS NULL
        // Fix 4: Use atom_name instead of slug/label (alias for backward compatibility)
        // Fix 5: Use table prefix
        // Fix 6: Use edge_type varchar directly instead of edge_type_id foreign key
        // Note: Parent relationship: left_object is parent atom, right_object is child atom
        $sql = "
            SELECT 
                parent.atom_id,
                parent.atom_name AS slug,
                parent.atom_name AS label,
                e.weight_score AS weight
            FROM {$table_prefix}edges e
            JOIN {$table_prefix}atoms parent ON parent.atom_id = e.left_object_id
            WHERE e.edge_type = 'parent_of'
              AND e.right_object_type = 'atom'
              AND e.right_object_id = :atom_id
              AND e.is_deleted = 0
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':atom_id' => $atomId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function getChildAtoms($atomId)
    {
        global $table_prefix;
        
        // Fix 1: Use correct edge column names (left_object_type/left_object_id, right_object_type/right_object_id)
        // Fix 2: Use weight_score instead of weight
        // Fix 3: Use is_deleted = 0 instead of deleted_at IS NULL
        // Fix 4: Use atom_name instead of slug/label (alias for backward compatibility)
        // Fix 5: Use table prefix
        // Fix 6: Use edge_type varchar directly instead of edge_type_id foreign key
        // Note: Child relationship: left_object is parent atom, right_object is child atom
        $sql = "
            SELECT 
                child.atom_id,
                child.atom_name AS slug,
                child.atom_name AS label,
                e.weight_score AS weight
            FROM {$table_prefix}edges e
            JOIN {$table_prefix}atoms child ON child.atom_id = e.right_object_id
            WHERE e.edge_type = 'parent_of'
              AND e.left_object_type = 'atom'
              AND e.left_object_id = :atom_id
              AND e.is_deleted = 0
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':atom_id' => $atomId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function getSiblingAtoms($atomId)
    {
        global $table_prefix;
        
        // Fix 1: Use correct edge column names (left_object_type/left_object_id, right_object_type/right_object_id)
        // Fix 2: Use weight_score instead of weight (not used here, but consistent)
        // Fix 3: Use is_deleted = 0 instead of deleted_at IS NULL
        // Fix 4: Use atom_name instead of slug/label (alias for backward compatibility)
        // Fix 5: Use table prefix
        // Fix 6: Use edge_type varchar directly instead of edge_type_id foreign key
        // Note: Sibling relationship: find atoms that share the same parent
        // e_parent: parent -> current atom (left_object is parent, right_object is current atom)
        // e_sibling: parent -> sibling atom (left_object is parent, right_object is sibling)
        $sql = "
            SELECT 
                sibling.atom_id,
                sibling.atom_name AS slug,
                sibling.atom_name AS label
            FROM {$table_prefix}edges e_parent
            JOIN {$table_prefix}edges e_sibling 
                ON e_sibling.left_object_id = e_parent.left_object_id
               AND e_sibling.left_object_type = 'atom'
               AND e_sibling.edge_type = 'parent_of'
            JOIN {$table_prefix}atoms sibling ON sibling.atom_id = e_sibling.right_object_id
            WHERE e_parent.edge_type = 'parent_of'
              AND e_parent.right_object_id = :atom_id
              AND sibling.atom_id != :atom_id
              AND e_parent.is_deleted = 0
              AND e_sibling.is_deleted = 0
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':atom_id' => $atomId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* -------------------------------------------------------------
       3. RELATED CONTENT (VIA SHARED ATOMS)
       ------------------------------------------------------------- */

    protected function getRelatedContent($contentId)
    {
        global $table_prefix;
        
        // Fix 1: Use correct edge column names (left_object_type/left_object_id, right_object_type/right_object_id)
        // Fix 2: Use weight_score instead of weight (alias for backward compatibility)
        // Fix 3: Use is_deleted = 0 instead of deleted_at IS NULL
        // Fix 4: Use atom_name instead of label (alias for backward compatibility)
        // Fix 5: Use table prefix
        // Fix 6: Remove edge_type_id lookups (not used in this query, but consistent)
        // Note: Related content relationship: find content that shares atoms with current content
        // e1: current content -> atom (left_object is content, right_object is atom)
        // e2: related content -> same atom (left_object is content, right_object is atom)
        // Join on e2.right_object_id = e1.right_object_id to find content sharing same atoms
        $sql = "
            SELECT 
                c.content_id,
                c.title,
                e2.weight_score AS weight,
                a.atom_id,
                a.atom_name AS via_atom
            FROM {$table_prefix}edges e1
            JOIN {$table_prefix}edges e2 
                ON e2.right_object_id = e1.right_object_id
               AND e2.right_object_type = 'atom'
               AND e2.left_object_type = 'content'
               AND e2.is_deleted = 0
            JOIN {$table_prefix}contents c ON c.content_id = e2.left_object_id
            JOIN {$table_prefix}atoms a ON a.atom_id = e1.right_object_id
            WHERE e1.left_object_type = 'content'
              AND e1.left_object_id = :content_id
              AND e1.right_object_type = 'atom'
              AND e1.is_deleted = 0
              AND e2.left_object_id != :content_id
            ORDER BY e2.weight_score DESC
            LIMIT 50
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':content_id' => $contentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* -------------------------------------------------------------
       4. EDGE TYPE SUMMARY
       ------------------------------------------------------------- */

    protected function getEdgeTypeSummary($contentId)
    {
        global $table_prefix;
        
        // Fix 1: Use correct edge column names (left_object_type/left_object_id, right_object_type/right_object_id)
        // Fix 2: Use is_deleted = 0 instead of deleted_at IS NULL
        // Fix 3: Use table prefix
        // Fix 4: Remove edge_type_id lookup - use edge_type varchar directly from edges table
        // Note: edge_type is stored as varchar in lupo_edges, no need to join edge_types table
        $sql = "
            SELECT 
                e.edge_type,
                COUNT(*) AS total
            FROM {$table_prefix}edges e
            WHERE e.left_object_type = 'content'
              AND e.left_object_id = :content_id
              AND e.is_deleted = 0
            GROUP BY e.edge_type
            ORDER BY total DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':content_id' => $contentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* -------------------------------------------------------------
       5. UTILITY HELPERS
       ------------------------------------------------------------- */

    protected function getEdgeTypeId($slug)
    {
        static $cache = [];

        if (isset($cache[$slug])) {
            return $cache[$slug];
        }

        $sql = "SELECT edge_type_id FROM edge_types WHERE slug = :slug LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':slug' => $slug]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new Exception("Missing edge_type: " . $slug);
        }

        return $cache[$slug] = (int)$row['edge_type_id'];
    }

    protected function uniqueById(array $rows, $key)
    {
        $seen = [];
        $out  = [];

        foreach ($rows as $row) {
            $id = $row[$key];
            if (!isset($seen[$id])) {
                $seen[$id] = true;
                $out[] = $row;
            }
        }

        return $out;
    }
}
?>