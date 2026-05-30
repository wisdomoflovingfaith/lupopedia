<?php
/**
 * DoctorService - System health and diagnostic logic.
 * 
 * @package Lupopedia
 * @version 4.0.62
 */

class DoctorService
{
    private $kernel;
    private $db;
    private $table_prefix;
    private $state_file;
    private $abspath;

    /**
     * @param ContextKernel $kernel
     * @param object|null $db PDO_DB or null
     * @param string $table_prefix Table prefix
     * @param string $state_file Path to .lupo_actor
     * @param string $abspath Project root
     */
    public function __construct($kernel, $db, $table_prefix, $state_file, $abspath)
    {
        $this->kernel = $kernel;
        $this->db = $db;
        $this->table_prefix = $table_prefix;
        $this->state_file = $state_file;
        $this->abspath = $abspath;
    }

    /**
     * Run full system health check.
     * 
     * @return array Array of health metrics
     */
    public function runHealthCheck()
    {
        $db_dir = defined('LUPO_DATABASE_DIR') ? LUPO_DATABASE_DIR : 'database';
        $session_md = $this->abspath . $db_dir . '/session.md';

        $results = array(
            'timestamp' => gmdate('Y-m-d H:i:s'),
            'database' => array('ok' => false, 'details' => ''),
            'session' => array('ok' => false, 'details' => ''),
            'registry' => array('ok' => false, 'details' => ''),
            'context' => array('ok' => false, 'issues' => array()),
            'files' => array('ok' => true, 'details' => '')
        );

        // Database check
        if ($this->db) {
            try {
                $this->db->fetchRow('SELECT 1');
                $results['database']['ok'] = true;
            } catch (Exception $e) {
                $results['database']['details'] = $e->getMessage();
            }
        } else {
            $results['database']['details'] = 'Not connected';
        }

        // Session check
        if (file_exists($session_md) && is_readable($session_md)) {
            $results['session']['ok'] = true;
        } else {
            $results['session']['details'] = 'Missing or unreadable';
        }

        // Registry check (both options)
        $registry_path_top = $this->abspath . $db_dir . '/lupopedia/actors/registry.json';
        $registry_path_id = $this->abspath . $db_dir . '/lupopedia/actors/actor_id/registry.json';
        $path = file_exists($registry_path_top) ? $registry_path_top : $registry_path_id;

        if (file_exists($path) && is_readable($path)) {
            $reg_json = json_decode(file_get_contents($path), true);
            if (is_array($reg_json) && (isset($reg_json['actors']) || isset($reg_json['schema']))) {
                $results['registry']['ok'] = true;
                $actors = isset($reg_json['actors']) ? $reg_json['actors'] : $reg_json;
                $results['registry']['details'] = count($actors) . ' actors';
            } else {
                $results['registry']['details'] = 'Invalid JSON format';
            }
        } else {
            $results['registry']['details'] = 'Missing or unreadable';
            $results['files']['ok'] = false;
        }

        // Context check
        $results['context']['issues'] = $this->validateContext();
        $results['context']['ok'] = empty($results['context']['issues']);

        return $results;
    }

    /**
     * Validate the identity stack and return list of issues.
     * 
     * @return array
     */
    public function validateContext()
    {
        $db_dir = defined('LUPO_DATABASE_DIR') ? LUPO_DATABASE_DIR : 'database';
        $session_md = $this->abspath . $db_dir . '/session.md';
        return $this->kernel->validate($this->db, $this->table_prefix, $session_md);
    }

    /**
     * Repair session.md if drift detected.
     * 
     * @return bool True if repaired or no repair needed
     */
    public function repairSessionFile()
    {
        $db_dir = defined('LUPO_DATABASE_DIR') ? LUPO_DATABASE_DIR : 'database';
        $session_md = $this->abspath . $db_dir . '/session.md';

        $ctx = $this->kernel->getContext();

        // Backup
        if (file_exists($session_md)) {
            @copy($session_md, $session_md . '.bak.' . gmdate('YmdHis'));
        }

        $content = "actor_name: " . (isset($ctx['actor_name']) ? $ctx['actor_name'] : 'system') . "\n";
        $content .= "actor_id: " . (isset($ctx['actor_id']) ? (int) $ctx['actor_id'] : 0) . "\n";
        $content .= "session_id: " . (isset($ctx['session_id']) ? $ctx['session_id'] : '') . "\n";
        $content .= "channel_id: " . (isset($ctx['channel_id']) ? (int) $ctx['channel_id'] : 0) . "\n";
        $content .= "federation_node_id: " . (isset($ctx['federation_node_id']) ? (int) $ctx['federation_node_id'] : 0) . "\n";
        $content .= "context_source: lupo_sessions\n";

        $dir = dirname($session_md);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        $success = file_put_contents($session_md, $content) !== false;
        if ($success) {
            $this->logDiagnostic("Repaired session.md for actor: " . (isset($ctx['actor_name']) ? $ctx['actor_name'] : 'unknown'));
        }
        return $success;
    }

    /**
     * @return string Full path to DOCTOR actor logs (actors/doctor/logs/)
     */
    public function getLogsDir()
    {
        $dir = $this->abspath . 'actors/doctor/logs/';
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        return $dir;
    }

    /**
     * @return string Full path to DOCTOR actor reports (actors/doctor/reports/)
     */
    public function getReportsDir()
    {
        $dir = $this->abspath . 'actors/doctor/reports/';
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        return $dir;
    }

    /**
     * Log diagnostic message to agent's log.
     * 
     * @param string $message
     */
    public function logDiagnostic($message)
    {
        $log_file = $this->getLogsDir() . 'diagnostics.log';
        $entry = "[" . gmdate('Y-m-d H:i:s') . "] " . $message . "\n";
        @file_put_contents($log_file, $entry, FILE_APPEND);
    }

    /**
     * Check all actors for workspace and namespace consistency.
     * 
     * @return array Issues found
     */
    public function checkActors()
    {
        $issues = array();
        if (!$this->db) {
            $issues[] = "Database not connected; cannot check actor table.";
            return $issues;
        }

        $t = $this->table_prefix . 'actors';
        try {
            $actors = $this->db->fetchAll("SELECT actor_id, actor_name, name, actor_type, workspace_path, php_namespace FROM {$t} WHERE is_deleted = 0");
            foreach ($actors as $a) {
                $name = $a['actor_name'] ?: ($a['name'] ?: 'ID:' . $a['actor_id']);

                // Check workspace
                if (empty($a['workspace_path'])) {
                    $issues[] = "Actor {$name} ({$a['actor_id']}) has no persistent workspace_path defined.";
                } else {
                    $full_path = $this->abspath . trim($a['workspace_path'], '/');
                    if (!is_dir($full_path)) {
                        $issues[] = "Actor {$name} workspace directory does not exist: {$a['workspace_path']}";
                    }
                }

                // Check namespace for agents
                if (($a['actor_type'] === 'agent' || $a['actor_type'] === 'ide_agent') && empty($a['php_namespace'])) {
                    $issues[] = "Actor {$name} is an agent but has no php_namespace defined.";
                }
            }
        } catch (Exception $e) {
            $issues[] = "Error querying actors table: " . $e->getMessage();
        }

        return $issues;
    }
}
