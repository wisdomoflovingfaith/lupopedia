<?php
/**
 * @wolfie.headers {
 *   file_path_from_root: "install/InstallWizardMdImporter.php",
 *   system_version: "4.0.45",
 *   channel_id: 42,
 *   mood_rgb: "4B0082",
 *   purpose: "MD file import functionality for Lupopedia install wizard",
 *   last_modified_utc: "20260225",
 *   delegation_chain: "1004:10000",
 *   actor_id: 1004,
 *   lupo_agent: "warp",
 *   artifact_type: "installer_component",
 *   artifact_kind: "md_importer",
 *   traits: ["md_import", "fallback_communication", "4.0.45", "critical"],
 *   hashtags: ["#installer", "#md_import", "#fallback", "#communication", "#critical"],
 *   engagement: { likes: 0, shares: 0, views: 0, last_interaction_utc: "20260225" },
 *   graph_stats: { inbound_count: 2, outbound_count: 3, centrality_score: 0.85 }
 * }
 * 
 * @flip.footer {
 *   inbound_edges: [
 *     { from: "install.php", type: "uses", weight: 1.0, hashtag: "#installer" },
 *     { from: "channels/0/broadcasts/", type: "imports", weight: 1.0, hashtag: "#md_files" }
 *   ],
 *   outbound_edges: [
 *     { to: "lupo_dialog_messages", type: "inserts", weight: 1.0, hashtag: "#database" },
 *     { to: "lupo_actors", type: "creates", weight: 0.8, hashtag: "#actors" }
 *   ],
 *   referenced_by_actors: [1004, 10000],
 *   references: {
 *     by_files: ["install.php", "channels/0/broadcasts/"],
 *     by_actors: [1004, 10000]
 *   },
 *   semantic_tags: ["md_import", "fallback_communication", "installer_component"],
 *   enrichment: { llm_inferred_edges: [], federated_metrics: {} },
 *   version: "4.0.45",
 *   last_verified_utc: "20260225",
 *   last_verified_by: "warp"
 * }
 */

/**
 * Install Wizard MD File Importer
 * 
 * Imports MD files from channels/ directories ONLY (not global MD files).
 * Handles standard naming format: [YYYYMMDDHHIISS]_[FROM_ACTOR_ID]_[TO_ACTOR_ID]_[CHANNEL_ID]_[TITLE].md
 * STRICT VALIDATION: All actors/channels must exist, delegation_chain must be "10000:1000"
 * NO auto-creation of actors - hard error if missing required entities.
 * Marks messages as read by all IDE agents.
 */

class InstallWizardMdImporter
{
    const CHANNELS_PATH = 'lupo-channels';

    /**
     * Import all MD files from channels directories only
     */
    public static function importAllMdFiles($pdo, &$log, $table_prefix = 'lupo_')
    {
        $channelsPath = LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . self::CHANNELS_PATH;

        if (!is_dir($channelsPath)) {
            $log[] = InstallWizardLogger::logEntry('skip', 'Channels directory not found: ' . self::CHANNELS_PATH);
            return 0;
        }

        // Find all MD files in channels/**/**/*.md (recursive)
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($channelsPath, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        $files = array();
        $pattern = '/^(\d{14})_(\d+)_(\d+)_(\d+)_(.+)$/';
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'md') {
                $base = basename($file->getPathname(), '.md');
                if (preg_match($pattern, $base)) {
                    $files[] = $file->getPathname();
                }
            }
        }

        if (empty($files)) {
            $log[] = InstallWizardLogger::logEntry('skip', 'No MD files found in channels directories (format: YYYYMMDDHHIISS_from_to_channel_title.md)');
            return 0;
        }

        // Pre-validate all actors and channels exist before importing any files
        $validationResult = self::preValidateActorsAndChannels($pdo, $files, $log, $table_prefix);
        if (!$validationResult['valid']) {
            $log[] = InstallWizardLogger::logEntry('error', 'Pre-validation failed: ' . $validationResult['error']);
            return 0;
        }

        $importedCount = 0;
        $validationErrors = array();

        foreach ($files as $filePath) {
            $result = self::importSingleMdFile($pdo, $filePath, $log, $table_prefix);

            if ($result['success']) {
                $importedCount++;
                $log[] = InstallWizardLogger::logEntry('ok', 'Imported MD message: ' . $result['filename']);
            } else {
                $validationErrors[] = $result['error'];
                $log[] = InstallWizardLogger::logEntry('error', 'Failed to import ' . basename($filePath) . ': ' . $result['error']);
            }
        }

        // If any validation errors occurred, halt the entire batch
        if (!empty($validationErrors)) {
            $log[] = InstallWizardLogger::logEntry('error', 'Import halted due to validation errors. Fix all issues before retrying.');
            return 0;
        }

        // Mark messages as read by all IDE agents
        if ($importedCount > 0) {
            self::markMessagesReadByIdeAgents($pdo, $log, $table_prefix);
        }

        $log[] = InstallWizardLogger::logEntry('ok', 'MD import complete: ' . $importedCount . ' messages imported from ' . count($files) . ' channel files');

        return $importedCount;
    }

    /**
     * Pre-validate all actors and channels exist before importing
     */
    private static function preValidateActorsAndChannels($pdo, $files, &$log, $table_prefix)
    {
        $requiredActors = array();
        $requiredChannels = array();

        // Collect all required actors and channels from filenames
        foreach ($files as $filePath) {
            $fileName = basename($filePath, '.md');

            if (!preg_match('/^(\d{14})_(\d+)_(\d+)_(\d+)_(.+)$/', $fileName, $matches)) {
                return array('valid' => false, 'error' => 'Invalid filename format: ' . $fileName);
            }

            $fromActorId = (int) $matches[2];
            $toActorId = (int) $matches[3];
            $channelId = (int) $matches[4];

            $requiredActors[$fromActorId] = true;
            $requiredActors[$toActorId] = true;
            $requiredChannels[$channelId] = true;
        }

        // Validate all required actors exist
        $actorsTable = $table_prefix . 'actors';
        $actorIds = array_keys($requiredActors);
        $placeholders = str_repeat('?,', count($actorIds) - 1) . '?';

        $stmt = $pdo->prepare("SELECT actor_id FROM {$actorsTable} WHERE actor_id IN ({$placeholders}) AND is_deleted = 0");
        $stmt->execute($actorIds);
        $existingActors = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

        $missingActors = array_diff($actorIds, $existingActors);
        if (!empty($missingActors)) {
            return array('valid' => false, 'error' => 'Missing actors: ' . implode(', ', $missingActors));
        }

        // Validate all required channels exist
        $channelsTable = $table_prefix . 'channels';
        $channelIds = array_keys($requiredChannels);
        $placeholders = str_repeat('?,', count($channelIds) - 1) . '?';

        $stmt = $pdo->prepare("SELECT channel_id FROM {$channelsTable} WHERE channel_id IN ({$placeholders}) AND is_deleted = 0");
        $stmt->execute($channelIds);
        $existingChannels = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

        $missingChannels = array_diff($channelIds, $existingChannels);
        if (!empty($missingChannels)) {
            return array('valid' => false, 'error' => 'Missing channels: ' . implode(', ', $missingChannels));
        }

        return array('valid' => true, 'error' => null);
    }

    /**
     * Import a single MD file with strict validation
     */
    private static function importSingleMdFile($pdo, $filePath, &$log, $table_prefix)
    {
        $fileName = basename($filePath, '.md');

        // Parse filename format: [YYYYMMDDHHIISS]_[FROM_ACTOR_ID]_[TO_ACTOR_ID]_[CHANNEL_ID]_[TITLE]
        if (!preg_match('/^(\d{14})_(\d+)_(\d+)_(\d+)_(.+)$/', $fileName, $matches)) {
            return array('success' => false, 'error' => 'Invalid filename format', 'filename' => $fileName);
        }

        $timestamp = $matches[1];
        $fromActorId = (int) $matches[2];
        $toActorId = (int) $matches[3];
        $channelId = (int) $matches[4];
        $title = $matches[5];

        // Read file content
        $content = file_get_contents($filePath);
        if ($content === false) {
            return array('success' => false, 'error' => 'Could not read file', 'filename' => $fileName);
        }

        // Parse FLIP header with validation
        $flipData = self::parseFlipHeaderWithValidation($content, $fromActorId, $toActorId);
        if (!$flipData['valid']) {
            return array('success' => false, 'error' => $flipData['error'], 'filename' => $fileName);
        }

        $messageContent = $flipData['content'];
        $metadata = isset($flipData['header']) ? json_encode($flipData['header']) : null;

        // Insert message
        $messageInserted = self::insertMessage($pdo, $fromActorId, $toActorId, $channelId, $title, $messageContent, $timestamp, $metadata, $log, $table_prefix);

        if ($messageInserted) {
            return array('success' => true, 'filename' => $fileName, 'error' => null);
        } else {
            return array('success' => false, 'error' => 'Failed to insert message', 'filename' => $fileName);
        }
    }

    /**
     * Parse FLIP header with strict validation for delegation_chain
     */
    private static function parseFlipHeaderWithValidation($content, $expectedFromActorId, $expectedToActorId)
    {
        $result = array('content' => $content, 'header' => null, 'valid' => true, 'error' => null);

        // Check for YAML front matter
        if (preg_match('/^---\s*\n(.*?)\n---\s*\n(.*)$/s', $content, $matches)) {
            try {
                $yaml = $matches[1];
                $result['content'] = $matches[2];

                // Simple YAML parsing for basic key-value pairs
                $header = array();
                $lines = explode("\n", $yaml);
                foreach ($lines as $line) {
                    if (strpos($line, ':') !== false) {
                        list($key, $value) = explode(':', $line, 2);
                        $key = trim($key);
                        $value = trim($value);
                        if ($key && $value) {
                            $header[$key] = $value;
                        }
                    }
                }

                // Strict validation: delegation_chain must be "10000:1000"
                if (!isset($header['delegation_chain']) || $header['delegation_chain'] !== '10000:1000') {
                    $result['valid'] = false;
                    $result['error'] = 'Missing or invalid delegation_chain. Must be "10000:1000"';
                    return $result;
                }

                // Validate from_actor_id and to_actor_id in header if present
                if (isset($header['from_actor_id']) && (int) $header['from_actor_id'] !== $expectedFromActorId) {
                    $result['valid'] = false;
                    $result['error'] = 'Header from_actor_id does not match filename';
                    return $result;
                }

                if (isset($header['to_actor_id']) && (int) $header['to_actor_id'] !== $expectedToActorId) {
                    $result['valid'] = false;
                    $result['error'] = 'Header to_actor_id does not match filename';
                    return $result;
                }

                if (!empty($header)) {
                    $result['header'] = $header;
                }

            } catch (Exception $e) {
                $result['valid'] = false;
                $result['error'] = 'Failed to parse YAML header';
                return $result;
            }
        } else {
            // No header found - this is a validation error for channel files
            $result['valid'] = false;
            $result['error'] = 'Missing YAML header with delegation_chain';
            return $result;
        }

        return $result;
    }

    /**
     * Insert message into database
     */
    private static function insertMessage($pdo, $fromActorId, $toActorId, $channelId, $title, $content, $timestamp, $metadata, &$log, $table_prefix)
    {
        $messagesTable = $table_prefix . 'messages';
        $now = gmdate('YmdHis');

        try {
            $stmt = $pdo->prepare("INSERT INTO {$messagesTable} (from_actor_id, to_actor_id, channel_id, message_type, subject, content, metadata_json, created_ymdhis, updated_ymdhis, is_deleted, is_read) VALUES (:from, :to, :channel, 'md', :subject, :content, :metadata, :created, :updated, 0, 0)");

            return $stmt->execute(array(
                ':from' => $fromActorId,
                ':to' => $toActorId,
                ':channel' => $channelId,
                ':subject' => $title,
                ':content' => $content,
                ':metadata' => $metadata,
                ':created' => $timestamp,
                ':updated' => $now
            ));

        } catch (PDOException $e) {
            $log[] = InstallWizardLogger::logEntry('error', 'Failed to insert message: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Mark messages as read by all IDE agents (actors 1000-1004)
     */
    private static function markMessagesReadByIdeAgents($pdo, &$log, $table_prefix)
    {
        $ideAgentIds = array(1000, 1001, 1002, 1003, 1004); // Kiro, Windsurf, Cursor, Cascade, Warp
        $messagesReadTable = $table_prefix . 'messages_read';
        $messagesTable = $table_prefix . 'messages';

        foreach ($ideAgentIds as $actorId) {
            try {
                // Mark all unread messages as read by this IDE agent
                $stmt = $pdo->prepare("INSERT INTO {$messagesReadTable} (message_id, actor_id, read_ymdhis) SELECT m.message_id, :actor_id, :now FROM {$messagesTable} m LEFT JOIN {$messagesReadTable} mr ON m.message_id = mr.message_id AND mr.actor_id = :actor_id WHERE mr.message_id IS NULL AND m.is_deleted = 0");

                $stmt->execute(array(
                    ':actor_id' => $actorId,
                    ':now' => gmdate('YmdHis')
                ));

                $markedCount = $stmt->rowCount();
                if ($markedCount > 0) {
                    $log[] = InstallWizardLogger::logEntry('ok', 'Marked ' . $markedCount . ' messages as read by IDE agent ' . $actorId);
                }

            } catch (PDOException $e) {
                $log[] = InstallWizardLogger::logEntry('warn', 'Could not mark messages as read for IDE agent ' . $actorId . ': ' . $e->getMessage());
            }
        }
    }
}
