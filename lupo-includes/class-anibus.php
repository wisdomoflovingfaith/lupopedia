<?php

/**
 * ANIBUS — Custodial Intelligence for Lupopedia
 *
 * FUNCTION LIST
 * -------------------------------------------------------------------------
 * __construct($db)
 *      Initializes ANIBUS with a database connection.
 *
 * logOrphan($table, $column, $missingId)
 *      Records an orphaned reference into anibus_orphans.
 *
 * resolveOrphan($orphanId, $newParentId, $note)
 *      Marks an orphan as resolved and stores a resolution note.
 *
 * logRedirect($table, $fromId, $toId, $reason)
 *      Creates a semantic redirect entry for lineage preservation.
 *
 * logEvent($eventType, $details)
 *      Writes a general ANIBUS event into anibus_events.
 *
 * detectOrphansInTable($table, $column, $idList)
 *      Scans a table for missing references and logs orphans.
 *
 * repairOrphansForTable($table, $column, $defaultParentId)
 *      Attempts to repair all orphans in a table by reassigning them.
 *
 * getOrphans($limit = 100)
 *      Returns a list of unresolved orphans for inspection or repair.
 *
 * getRedirectTarget($table, $id)
 *      Returns the redirect target for a given record, if any.
 *
 * applyRedirectIfExists($table, $id)
 *      Returns the correct ID after applying semantic redirects.
 *
 * -------------------------------------------------------------------------
 * NOTE:
 * ANIBUS NEVER deletes data.
 * ANIBUS NEVER enforces foreign keys.
 * ANIBUS ONLY logs, reassigns, redirects, and preserves lineage.
 * -------------------------------------------------------------------------
 */

class ANIBUS
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // -------------------------------------------------------------
    // ORPHAN LOGGING
    // -------------------------------------------------------------
    public function logOrphan($table, $column, $missingId)
    {
        $now = gmdate('YmdHis');

        $stmt = $this->db->prepare("
            INSERT INTO anibus_orphans (table_name, column_name, missing_id, detected_ymdhis)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$table, $column, $missingId, $now]);
    }

    public function resolveOrphan($orphanId, $newParentId, $note)
    {
        $now = gmdate('YmdHis');

        $stmt = $this->db->prepare("
            UPDATE anibus_orphans
            SET resolved_ymdhis = ?, resolution_note = ?
            WHERE id = ?
        ");
        $stmt->execute([$now, $note, $orphanId]);
    }

    // -------------------------------------------------------------
    // REDIRECTS
    // -------------------------------------------------------------
    public function logRedirect($table, $fromId, $toId, $reason)
    {
        $now = gmdate('YmdHis');

        $stmt = $this->db->prepare("
            INSERT INTO anibus_redirects (from_id, to_id, table_name, reason, created_ymdhis)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$fromId, $toId, $table, $reason, $now]);
    }

    public function getRedirectTarget($table, $id)
    {
        $stmt = $this->db->prepare("
            SELECT to_id FROM anibus_redirects
            WHERE table_name = ? AND from_id = ?
            ORDER BY id DESC LIMIT 1
        ");
        $stmt->execute([$table, $id]);

        $row = $stmt->fetch();
        return $row ? $row['to_id'] : $id;
    }

    public function applyRedirectIfExists($table, $id)
    {
        return $this->getRedirectTarget($table, $id);
    }

    // -------------------------------------------------------------
    // EVENTS
    // -------------------------------------------------------------
    public function logEvent($eventType, $details)
    {
        $now = gmdate('YmdHis');

        $stmt = $this->db->prepare("
            INSERT INTO anibus_events (event_type, details, created_ymdhis)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$eventType, $details, $now]);
    }

    // -------------------------------------------------------------
    // ORPHAN DETECTION & REPAIR
    // -------------------------------------------------------------
    public function detectOrphansInTable($table, $column, $idList)
    {
        foreach ($idList as $id) {
            $stmt = $this->db->prepare("SELECT COUNT(*) AS c FROM {$table} WHERE {$column} = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch();

            if ($row['c'] == 0) {
                $this->logOrphan($table, $column, $id);
            }
        }
    }

    public function repairOrphansForTable($table, $column, $defaultParentId)
    {
        $orphans = $this->getOrphans();

        foreach ($orphans as $orphan) {
            if ($orphan['table_name'] === $table && $orphan['column_name'] === $column) {

                // Reassign the orphaned record
                $stmt = $this->db->prepare("
                    UPDATE {$table}
                    SET {$column} = ?
                    WHERE {$column} = ?
                ");
                $stmt->execute([$defaultParentId, $orphan['missing_id']]);

                // Log redirect
                $this->logRedirect($table, $orphan['missing_id'], $defaultParentId, "orphan_repair");

                // Mark orphan resolved
                $this->resolveOrphan($orphan['id'], $defaultParentId, "Reassigned by ANIBUS");
            }
        }
    }

    public function getOrphans($limit = 100)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM anibus_orphans
            WHERE resolved_ymdhis IS NULL
            ORDER BY id ASC
            LIMIT ?
        ");
        $stmt->bindValue(1, (int)$limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}

?>