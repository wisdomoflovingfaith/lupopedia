<?php
// SyncChannelsToDb.php: Idempotent migration of "Unfinished Business" from JSON/TOON to DB with dry-run and commit modes.
// Usage: php lupo-scripts/SyncChannelsToDb.php [--commit]

require_once __DIR__ . '/../lupo-includes/bootstrap.php';
require_once __DIR__ . '/../lupo-includes/classes/Lupo_Id_Generator.php';

class SyncChannelsToDb {
    private $db;
    private $jsonDir;
    private $dryRun;
    private $log = [];

    // Use Lupo_Id_Generator for deterministic IDs
    private function generateId($nodeId = 0) {
        return Lupo_Id_Generator::generate($nodeId);
    }

    // Use Lupo_Id_Generator for doctrinal timestamps
    private function nowYmdhis() {
        return Lupo_Id_Generator::getLupoTimestamp();
    }

    public function __construct($jsonDir, $dryRun = true) {
        $this->db = DatabaseFactory::getConnection();
        $this->jsonDir = $jsonDir;
        $this->dryRun = $dryRun;
    }

    public function run() {
        $channelsFile = $this->jsonDir . '/lupo_dialog_threads.json';
        $messagesFile = $this->jsonDir . '/lupo_dialog_messages.json';
        $contextsFile = $this->jsonDir . '/lupo_contexts.json';
        $edgesFile = $this->jsonDir . '/lupo_edges.json';
        if (!file_exists($channelsFile) || !file_exists($messagesFile)) {
            echo "Missing required JSON files.\n";
            exit(1);
        }
        $channels = json_decode(file_get_contents($channelsFile), true);
        $messages = json_decode(file_get_contents($messagesFile), true);
        $contexts = file_exists($contextsFile) ? json_decode(file_get_contents($contextsFile), true) : [];
        $edges = file_exists($edgesFile) ? json_decode(file_get_contents($edgesFile), true) : [];
        if (!is_array($channels) || !is_array($messages)) {
            echo "Invalid JSON structure.\n";
            exit(1);
        }
        $this->syncChannels($channels);
        $this->syncMessages($messages);
        if ($contexts) $this->syncContexts($contexts);
        if ($edges) $this->syncEdges($edges);
        $this->report();
    }

    private function syncChannels($channels) {
        foreach ($channels as $chan) {
            $exists = $this->db->fetchOne("SELECT COUNT(*) FROM ".LUPO_TABLE_PREFIX."channels WHERE channel_id = :id", ['id' => $chan['channel_id']]);
            if ($exists) {
                $this->log[] = "Channel {$chan['channel_id']} exists. Skipping.";
                continue;
            }
            if ($this->dryRun) {
                $this->log[] = "[DRY RUN] Would insert channel {$chan['channel_id']} ({$chan['title']})";
            } else {
                $this->db->insert(LUPO_TABLE_PREFIX.'channels', $chan);
                $this->log[] = "Inserted channel {$chan['channel_id']} ({$chan['title']})";
            }
        }
    }

    private function syncMessages($messages) {
        foreach ($messages as $msg) {
            $exists = $this->db->fetchOne("SELECT COUNT(*) FROM ".LUPO_TABLE_PREFIX."channel_content WHERE channel_content_id = :id", ['id' => $msg['channel_content_id']]);
            if ($exists) {
                $this->log[] = "Message {$msg['channel_content_id']} exists. Skipping.";
                continue;
            }
            if ($this->dryRun) {
                $this->log[] = "[DRY RUN] Would insert message {$msg['channel_content_id']} (channel {$msg['channel_id']})";
            } else {
                $this->db->insert(LUPO_TABLE_PREFIX.'channel_content', $msg);
                $this->log[] = "Inserted message {$msg['channel_content_id']} (channel {$msg['channel_id']})";
            }
        }
    }

    private function report() {
        foreach ($this->log as $line) {
            echo $line . "\n";
        }
    }

    // Sync lupo_contexts (doctrine-compliant, no FK, no auto-increment)
    private function syncContexts($contexts) {
        $nodeId = 1; // TODO: fetch from config or headers if needed
        foreach ($contexts as $ctx) {
            if (empty($ctx['context_id'])) $ctx['context_id'] = $this->generateId($nodeId);
            if (empty($ctx['created_ymdhis'])) $ctx['created_ymdhis'] = $this->nowYmdhis();
            // Check for required lineage
            if (empty($ctx['source_message_id'])) {
                $this->log[] = "[ERROR] Context missing source_message_id, skipping.";
                continue;
            }
            $exists = $this->db->fetchOne("SELECT COUNT(*) FROM ".LUPO_TABLE_PREFIX."contexts WHERE context_id = :id", ['id' => $ctx['context_id']]);
            if ($exists) {
                $this->log[] = "Context {$ctx['context_id']} exists. Skipping.";
                continue;
            }
            if ($this->dryRun) {
                $this->log[] = "[DRY RUN] Would insert context {$ctx['context_id']} (source_message_id {$ctx['source_message_id']})";
            } else {
                $this->db->insert(LUPO_TABLE_PREFIX.'contexts', $ctx);
                $this->log[] = "Inserted context {$ctx['context_id']} (source_message_id {$ctx['source_message_id']})";
            }
        }
    }

    // Sync lupo_edges (polymorphic edge system - replaces lupo_context_edges)
    private function syncEdges($edges) {
        $nodeId = 1; // TODO: fetch from config or headers if needed
        foreach ($edges as $edge) {
            if (empty($edge['edge_id'])) $edge['edge_id'] = $this->generateId($nodeId);
            if (empty($edge['created_ymdhis'])) $edge['created_ymdhis'] = $this->nowYmdhis();
            $exists = $this->db->fetchOne("SELECT COUNT(*) FROM ".LUPO_TABLE_PREFIX."edges WHERE edge_id = :id", ['id' => $edge['edge_id']]);
            if ($exists) {
                $this->log[] = "Edge {$edge['edge_id']} exists. Skipping.";
                continue;
            }
            if ($this->dryRun) {
                $this->log[] = "[DRY RUN] Would insert edge {$edge['edge_id']} ({$edge['left_object_type']} {$edge['left_object_id']} → {$edge['right_object_type']} {$edge['right_object_id']})";
            } else {
                $this->db->insert(LUPO_TABLE_PREFIX.'edges', $edge);
                $this->log[] = "Inserted edge {$edge['edge_id']} ({$edge['left_object_type']} {$edge['left_object_id']} → {$edge['right_object_type']} {$edge['right_object_id']})";
            }
        }
    }
}

// Entrypoint
$dryRun = true;
if (in_array('--commit', $argv)) {
    $dryRun = false;
}
$sync = new SyncChannelsToDb(__DIR__ . '/../lupo-database/lupopedia/json', $dryRun);
$sync->run();
