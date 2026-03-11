<?php

namespace Lupopedia\Dialog\Database;

use PDO;
use PDOException;

/**
 * Dialog Database Layer
 * 
 * Provides database connectivity and operations for the dialog system.
 * Follows Lupopedia doctrine: no foreign keys, no triggers, BIGINT timestamps.
 * 
 * @package Lupopedia\Dialog\Database
 * @version 3.0.46
 * @author Captain Wolfie
 */
class DialogDatabase
{
    private PDO $pdo;
    private string $tablePrefix;
    
    public function __construct(PDO $pdo, string $tablePrefix = 'lupo_')
    {
        $this->pdo = $pdo;
        $this->tablePrefix = $tablePrefix;
    }
    
    /**
     * Get full table name with prefix
     */
    private function getTableName(string $table): string
    {
        return $this->tablePrefix . $table;
    }
    
    /**
     * Create a new dialog thread
     */
    public function createThread(array $data): int
    {
        $sql = "INSERT INTO " . $this->getTableName('dialog_threads') . " (
            thread_key, 
            channel_key, 
            created_by_actor_id, 
            thread_title, 
            thread_description, 
            metadata_json, 
            status_flag, 
            created_ymdhis, 
            updated_ymdhis, 
            is_deleted, 
            deleted_ymdhis
        ) VALUES (
            :thread_key,
            :channel_key,
            :created_by_actor_id,
            :thread_title,
            :thread_description,
            :metadata_json,
            :status_flag,
            :created_ymdhis,
            :updated_ymdhis,
            :is_deleted,
            :deleted_ymdhis
        )";
        
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->execute([
            ':thread_key' => $data['thread_key'],
            ':channel_key' => $data['channel_key'],
            ':created_by_actor_id' => $data['created_by_actor_id'],
            ':thread_title' => $data['thread_title'],
            ':thread_description' => $data['thread_description'] ?? null,
            ':metadata_json' => $data['metadata_json'] ?? null,
            ':status_flag' => $data['status_flag'] ?? 1,
            ':created_ymdhis' => $data['created_ymdhis'],
            ':updated_ymdhis' => $data['updated_ymdhis'],
            ':is_deleted' => $data['is_deleted'] ?? 0,
            ':deleted_ymdhis' => $data['deleted_ymdhis'] ?? null
        ]);
        
        return (int)$this->pdo->lastInsertId();
    }
    
    /**
     * Create a new dialog message (canonical table: lupo_dialog_messages)
     * Required keys: dialog_message_id, dialog_thread_id, channel_id, from_actor_id, message_text, created_ymdhis, updated_ymdhis
     */
    public function createMessage(array $data): int
    {
        $sql = "INSERT INTO " . $this->getTableName('dialog_messages') . " (
            dialog_message_id,
            message_id,
            dialog_thread_id,
            channel_id,
            from_actor_id,
            to_actor_id,
            read_by_actor_id,
            read_by_actor_utc,
            message_text,
            message_type,
            created_ymdhis,
            updated_ymdhis,
            is_deleted
        ) VALUES (
            :dialog_message_id,
            :message_id,
            :dialog_thread_id,
            :channel_id,
            :from_actor_id,
            :to_actor_id,
            :read_by_actor_id,
            :read_by_actor_utc,
            :message_text,
            :message_type,
            :created_ymdhis,
            :updated_ymdhis,
            :is_deleted
        )";
        
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->execute([
            ':dialog_message_id' => $data['dialog_message_id'],
            ':message_id' => isset($data['message_id']) ? $data['message_id'] : 0,
            ':dialog_thread_id' => $data['dialog_thread_id'],
            ':channel_id' => $data['channel_id'],
            ':from_actor_id' => $data['from_actor_id'],
            ':to_actor_id' => isset($data['to_actor_id']) ? $data['to_actor_id'] : null,
            ':read_by_actor_id' => isset($data['read_by_actor_id']) ? $data['read_by_actor_id'] : 0,
            ':read_by_actor_utc' => isset($data['read_by_actor_utc']) ? $data['read_by_actor_utc'] : 0,
            ':message_text' => $data['message_text'],
            ':message_type' => isset($data['message_type']) ? $data['message_type'] : 'text',
            ':created_ymdhis' => $data['created_ymdhis'],
            ':updated_ymdhis' => $data['updated_ymdhis'],
            ':is_deleted' => isset($data['is_deleted']) ? $data['is_deleted'] : 0
        ]);
        
        return (int)$data['dialog_message_id'];
    }
    
    /**
     * Create a new message body
     */
    public function createMessageBody(array $data): int
    {
        $sql = "INSERT INTO " . $this->getTableName('dialog_message_bodies') . " (
            message_body_id,
            content_type,
            content_text,
            content_json,
            metadata_json,
            content_hash,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        ) VALUES (
            :message_body_id,
            :content_type,
            :content_text,
            :content_json,
            :metadata_json,
            :content_hash,
            :created_ymdhis,
            :updated_ymdhis,
            :is_deleted,
            :deleted_ymdhis
        )";
        
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->execute([
            ':message_body_id' => $data['message_body_id'],
            ':content_type' => $data['content_type'],
            ':content_text' => $data['content_text'] ?? null,
            ':content_json' => $data['content_json'] ?? null,
            ':metadata_json' => $data['metadata_json'] ?? null,
            ':content_hash' => $data['content_hash'],
            ':created_ymdhis' => $data['created_ymdhis'],
            ':updated_ymdhis' => $data['updated_ymdhis'],
            ':is_deleted' => $data['is_deleted'] ?? 0,
            ':deleted_ymdhis' => $data['deleted_ymdhis'] ?? null
        ]);
        
        return (int)$this->pdo->lastInsertId();
    }
    
    /**
     * Get thread by key
     */
    public function getThreadByKey(string $threadKey): ?array
    {
        $sql = "SELECT * FROM " . $this->getTableName('dialog_threads') . " 
                 WHERE thread_key = :thread_key AND is_deleted = 0";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':thread_key' => $threadKey]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    /**
     * Get messages for a thread (canonical table: lupo_dialog_messages)
     */
    public function getMessagesByThread(int $threadId, int $limit = 50, int $offset = 0): array
    {
        $sql = "SELECT dm.dialog_message_id, dm.dialog_thread_id, dm.channel_id, dm.from_actor_id, dm.to_actor_id,
                        dm.message_text, dm.message_type, dm.created_ymdhis, dm.updated_ymdhis,
                        fa.name as from_actor_name, ta.name as to_actor_name
                 FROM " . $this->getTableName('dialog_messages') . " dm
                 LEFT JOIN " . $this->getTableName('actors') . " fa ON dm.from_actor_id = fa.actor_id
                 LEFT JOIN " . $this->getTableName('actors') . " ta ON dm.to_actor_id = ta.actor_id
                 WHERE dm.dialog_thread_id = :dialog_thread_id AND dm.is_deleted = 0
                 ORDER BY dm.created_ymdhis ASC
                 LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':dialog_thread_id' => $threadId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get channel by key
     */
    public function getChannelByKey(string $channelKey): ?array
    {
        $sql = "SELECT * FROM " . $this->getTableName('channels') . " 
                 WHERE channel_key = :channel_key AND status_flag = 1";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':channel_key' => $channelKey]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    /**
     * Get actor by ID
     */
    public function getActorById(int $actorId): ?array
    {
        $sql = "SELECT * FROM " . $this->getTableName('actors') . " 
                 WHERE actor_id = :actor_id AND is_active = 1 AND is_deleted = 0";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':actor_id' => $actorId]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    /**
     * Update thread status (canonical: status column in lupo_dialog_threads)
     */
    public function updateThreadStatus(int $threadId, string $status): bool
    {
        $sql = "UPDATE " . $this->getTableName('dialog_threads') . " 
                 SET status = :status, updated_ymdhis = :updated_ymdhis
                 WHERE dialog_thread_id = :dialog_thread_id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array(
            ':dialog_thread_id' => $threadId,
            ':status' => $status,
            ':updated_ymdhis' => gmdate('YmdHis')
        ));
    }
    
    /**
     * Soft delete thread
     */
    public function softDeleteThread(int $threadId): bool
    {
        $sql = "UPDATE " . $this->getTableName('dialog_threads') . " 
                 SET is_deleted = 1, deleted_ymdhis = :deleted_ymdhis, updated_ymdhis = :updated_ymdhis
                 WHERE dialog_thread_id = :dialog_thread_id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array(
            ':dialog_thread_id' => $threadId,
            ':deleted_ymdhis' => gmdate('YmdHis'),
            ':updated_ymdhis' => gmdate('YmdHis')
        ));
    }
    
    /**
     * Get dialog statistics (canonical tables: lupo_dialog_threads, lupo_dialog_messages)
     */
    public function getDialogStatistics(): array
    {
        $sql = "SELECT 
                    COUNT(DISTINCT dt.dialog_thread_id) as total_threads,
                    COUNT(DISTINCT dm.dialog_message_id) as total_messages,
                    COUNT(DISTINCT dt.channel_id) as total_channels,
                    COUNT(DISTINCT dm.from_actor_id) as active_participants
                 FROM " . $this->getTableName('dialog_threads') . " dt
                 LEFT JOIN " . $this->getTableName('dialog_messages') . " dm 
                   ON dt.dialog_thread_id = dm.dialog_thread_id AND dm.is_deleted = 0
                 WHERE dt.is_deleted = 0";
        
        $stmt = $this->pdo->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: array();
    }
    
    /**
     * Execute raw query (for complex operations)
     */
    public function executeQuery(string $sql, array $params = []): array
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new \RuntimeException("Database query failed: " . $e->getMessage(), 0, $e);
        }
    }
    
    /**
     * Begin transaction
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }
    
    /**
     * Commit transaction
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }
    
    /**
     * Rollback transaction
     */
    public function rollback(): bool
    {
        return $this->pdo->rollBack();
    }
}
