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
 * @version 4.0.46
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
     * Create a new dialog message
     */
    public function createMessage(array $data): int
    {
        $sql = "INSERT INTO " . $this->getTableName('dialog_doctrine') . " (
            thread_id,
            message_key,
            from_actor_id,
            to_actor_id,
            message_type,
            message_body_id,
            metadata_json,
            status_flag,
            created_ymdhis,
            updated_ymdhis,
            is_deleted,
            deleted_ymdhis
        ) VALUES (
            :thread_id,
            :message_key,
            :from_actor_id,
            :to_actor_id,
            :message_type,
            :message_body_id,
            :metadata_json,
            :status_flag,
            :created_ymdhis,
            :updated_ymdhis,
            :is_deleted,
            :deleted_ymdhis
        )";
        
        $stmt = $this->pdo->prepare($sql);
        
        $stmt->execute([
            ':thread_id' => $data['thread_id'],
            ':message_key' => $data['message_key'],
            ':from_actor_id' => $data['from_actor_id'],
            ':to_actor_id' => $data['to_actor_id'] ?? null,
            ':message_type' => $data['message_type'],
            ':message_body_id' => $data['message_body_id'],
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
     * Get messages for a thread
     */
    public function getMessagesByThread(int $threadId, int $limit = 50, int $offset = 0): array
    {
        $sql = "SELECT dm.*, mb.content_text, mb.content_json, 
                        fa.name as from_actor_name, ta.name as to_actor_name
                 FROM " . $this->getTableName('dialog_doctrine') . " dm
                 LEFT JOIN " . $this->getTableName('dialog_message_bodies') . " mb 
                   ON dm.message_body_id = mb.message_body_id
                 LEFT JOIN " . $this->getTableName('actors') . " fa 
                   ON dm.from_actor_id = fa.actor_id
                 LEFT JOIN " . $this->getTableName('actors') . " ta 
                   ON dm.to_actor_id = ta.actor_id
                 WHERE dm.thread_id = :thread_id AND dm.is_deleted = 0
                 ORDER BY dm.created_ymdhis ASC
                 LIMIT :limit OFFSET :offset";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':thread_id' => $threadId,
            ':limit' => $limit,
            ':offset' => $offset
        ]);
        
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
     * Update thread status
     */
    public function updateThreadStatus(int $threadId, string $status): bool
    {
        $sql = "UPDATE " . $this->getTableName('dialog_threads') . " 
                 SET status_flag = :status_flag, 
                     updated_ymdhis = :updated_ymdhis
                 WHERE thread_id = :thread_id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':thread_id' => $threadId,
            ':status_flag' => $status,
            ':updated_ymdhis' => date('YmdHis')
        ]);
    }
    
    /**
     * Soft delete thread
     */
    public function softDeleteThread(int $threadId): bool
    {
        $sql = "UPDATE " . $this->getTableName('dialog_threads') . " 
                 SET is_deleted = 1, 
                     deleted_ymdhis = :deleted_ymdhis,
                     updated_ymdhis = :updated_ymdhis
                 WHERE thread_id = :thread_id";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':thread_id' => $threadId,
            ':deleted_ymdhis' => date('YmdHis'),
            ':updated_ymdhis' => date('YmdHis')
        ]);
    }
    
    /**
     * Get dialog statistics
     */
    public function getDialogStatistics(): array
    {
        $sql = "SELECT 
                    COUNT(DISTINCT dt.thread_id) as total_threads,
                    COUNT(DISTINCT dm.message_id) as total_messages,
                    COUNT(DISTINCT dt.channel_key) as total_channels,
                    COUNT(DISTINCT dm.from_actor_id) as active_participants
                 FROM " . $this->getTableName('dialog_threads') . " dt
                 LEFT JOIN " . $this->getTableName('dialog_doctrine') . " dm 
                   ON dt.thread_id = dm.thread_id
                 WHERE dt.is_deleted = 0 AND dm.is_deleted = 0";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
