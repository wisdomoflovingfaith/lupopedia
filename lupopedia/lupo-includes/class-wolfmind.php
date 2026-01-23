<?php

/**
 * WOLFMIND — Memory Curator for Lupopedia
 *
 * FUNCTION LIST
 * -------------------------------------------------------------------------
 * __construct($db)
 *      Initializes WOLFMIND with a database connection.
 *
 * storeMemoryEvent($actorId, $type, $content, $metadata = [])
 *      Stores a memory event in relational fallback storage.
 *
 * getRecentMemory($actorId, $limit = 50)
 *      Retrieves recent memory events for an actor.
 *
 * searchMemoryRelational($actorId, $keyword, $limit = 50)
 *      Performs a simple LIKE-based search (MySQL fallback).
 *
 * searchMemoryVector($actorId, $embedding, $limit = 20)
 *      Placeholder for vector search (Postgres/pgvector or cloud).
 *
 * generateEmbedding($text)
 *      Placeholder for generating embeddings via external AI.
 *
 * hasVectorSupport()
 *      Detects whether vector memory is available.
 *
 * storeEmbedding($memoryId, $embedding)
 *      Placeholder for storing vector embeddings.
 *
 * logMemoryEvent($eventType, $details)
 *      Logs internal WOLFMIND events (debugging, fallback notices).
 *
 * -------------------------------------------------------------------------
 * NOTE:
 * WOLFMIND MUST run on MySQL with no vector support.
 * Vector memory is OPTIONAL and detected at runtime.
 * No foreign keys are used anywhere.
 * -------------------------------------------------------------------------
 */
<?php

/**
 * WOLFMIND — Memory Curator for Lupopedia
 *
 * This version is fully compatible with the PDO_DB wrapper.
 */

class WOLFMIND
{
    protected $db;      // PDO_DB wrapper
    protected $pdo;     // actual PDO instance
    protected $config;
    protected $vectorSupported = null;

    public function __construct($db, $config = [])
    {
        $this->db     = $db;
        $this->pdo    = $db->getPdo();   // IMPORTANT FIX
        $this->config = $config;
    }

    // -------------------------------------------------------------
    // RELATIONAL MEMORY STORAGE
    // -------------------------------------------------------------
    public function storeMemoryEvent($actorId, $type, $content, $metadata = [])
    {
        $now      = gmdate('YmdHis');
        $metaJson = json_encode($metadata);

        $stmt = $this->pdo->prepare("
            INSERT INTO memory_events (actor_id, event_type, content, metadata_json, created_ymdhis)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$actorId, $type, $content, $metaJson, $now]);

        return $this->pdo->lastInsertId();
    }

    public function getRecentMemory($actorId, $limit = 50)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM memory_events
            WHERE actor_id = ?
            ORDER BY id DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, (int)$actorId, \PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$limit,   \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function searchMemoryRelational($actorId, $keyword, $limit = 50)
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM memory_events
            WHERE actor_id = ?
              AND content LIKE ?
            ORDER BY id DESC
            LIMIT ?
        ");
        $stmt->execute([$actorId, "%{$keyword}%", $limit]);

        return $stmt->fetchAll();
    }

    // -------------------------------------------------------------
    // CAPABILITY DETECTION
    // -------------------------------------------------------------
    public function getDbType()
    {
        if (isset($this->config['DB_TYPE'])) {
            return strtolower($this->config['DB_TYPE']);
        }
        if (defined('DB_TYPE')) {
            return strtolower(DB_TYPE);
        }
        return 'mysql';
    }

    public function hasVectorSupport()
    {
        if ($this->vectorSupported !== null) {
            return $this->vectorSupported;
        }

        if ($this->getDbType() !== 'postgres') {
            return $this->vectorSupported = false;
        }

        try {
            $stmt = $this->pdo->query("SELECT extname FROM pg_extension WHERE extname = 'vector'");
            $this->vectorSupported = $stmt->fetch() ? true : false;
        } catch (\Exception $e) {
            $this->vectorSupported = false;
        }

        return $this->vectorSupported;
    }

    // -------------------------------------------------------------
    // VECTOR MEMORY (OPTIONAL)
    // -------------------------------------------------------------
    public function searchMemoryVector($actorId, $embedding, $limit = 20)
    {
        if (!$this->hasVectorSupport()) {
            $this->logMemoryEvent("VECTOR_UNAVAILABLE", "Vector search attempted but not supported.");
            return [];
        }

        $this->logMemoryEvent("VECTOR_STUB", "Vector search stub called.");
        return [];
    }

    public function storeEmbedding($memoryId, $actorId, $embedding)
    {
        if (!$this->hasVectorSupport()) {
            $this->logMemoryEvent("VECTOR_UNAVAILABLE", "Embedding storage attempted but not supported.");
            return;
        }

        $this->logMemoryEvent("VECTOR_STUB", "Embedding storage stub called.");
    }

    public function generateEmbedding($text)
    {
        $this->logMemoryEvent("EMBEDDING_STUB", "Embedding generation stub called.");
        return [];
    }

    // -------------------------------------------------------------
    // INTERNAL LOGGING
    // -------------------------------------------------------------
    public function logMemoryEvent($eventType, $details)
    {
        $now = gmdate('YmdHis');

        $stmt = $this->pdo->prepare("
            INSERT INTO memory_debug_log (event_type, details, created_ymdhis)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$eventType, $details, $now]);
    }
}

?>