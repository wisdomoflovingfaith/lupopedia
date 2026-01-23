<?php

class FederationSyncEngine
{
    protected $db;
    protected $domainId;
    protected $now;

    public function __construct(PDO $db, $domainId)
    {
        $this->db       = $db;
        $this->domainId = (int)$domainId;
        $this->now      = $this->utcNowBigint();
    }

    /* ============================================================
       1. ENQUEUE CHANGES
       ============================================================ */

    public function enqueueEdgeChange($edgeId, $operation = 'update')
    {
        $edgeId    = (int)$edgeId;
        $operation = $this->normalizeOperation($operation);

        $nodes = $this->getActiveNodesForDomain();
        foreach ($nodes as $node) {
            $this->insertEdgeQueueRow($node['node_id'], $edgeId, $operation);
        }
    }

    public function enqueueContentChange($contentId, $operation = 'update')
    {
        $contentId = (int)$contentId;
        $operation = $this->normalizeOperation($operation);

        $nodes = $this->getActiveNodesForDomain();
        foreach ($nodes as $node) {
            $this->insertContentQueueRow($node['node_id'], $contentId, $operation);
        }
    }

    protected function normalizeOperation($operation)
    {
        $operation = strtolower(trim($operation));
        if (!in_array($operation, ['create','update','delete'], true)) {
            $operation = 'update';
        }
        return $operation;
    }

    protected function getActiveNodesForDomain()
    {
        $sql = "
            SELECT node_id, node_url, trust_level
            FROM nodes
            WHERE domain_id = :domain_id
              AND is_active = 1
              AND deleted_at IS NULL
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':domain_id' => $this->domainId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function insertEdgeQueueRow($nodeId, $edgeId, $operation)
    {
        $sql = "
            INSERT INTO node_edge_queue
            (node_id, edge_id, operation, attempts, created_at)
            VALUES
            (:node_id, :edge_id, :operation, 0, :created_at)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':node_id'    => $nodeId,
            ':edge_id'    => $edgeId,
            ':operation'  => $operation,
            ':created_at' => $this->now,
        ]);
    }

    protected function insertContentQueueRow($nodeId, $contentId, $operation)
    {
        $sql = "
            INSERT INTO node_content_queue
            (node_id, content_id, operation, attempts, created_at)
            VALUES
            (:node_id, :content_id, :operation, 0, :created_at)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':node_id'    => $nodeId,
            ':content_id' => $contentId,
            ':operation'  => $operation,
            ':created_at' => $this->now,
        ]);
    }

    /* ============================================================
       2. PROCESS OUTGOING EDGES
       ============================================================ */

    public function processOutgoingEdges($limit = 50)
    {
        $rows = $this->fetchEdgeQueueBatch($limit);

        foreach ($rows as $row) {
            $this->processSingleEdgeQueueRow($row);
        }
    }

    protected function fetchEdgeQueueBatch($limit)
    {
        $sql = "
            SELECT *
            FROM node_edge_queue
            WHERE sent_at IS NULL
            ORDER BY queue_id ASC
            LIMIT :limit
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function processSingleEdgeQueueRow(array $row)
    {
        $queueId   = (int)$row['queue_id'];
        $nodeId    = (int)$row['node_id'];
        $edgeId    = (int)$row['edge_id'];
        $operation = $row['operation'];

        $node  = $this->getNodeById($nodeId);
        if (!$node || $node['trust_level'] === 'none') {
            $this->markEdgeQueueAsFailed($queueId);
            return;
        }

        $edgePayload = $this->buildEdgePayload($edgeId, $operation);
        if ($edgePayload === null) {
            $this->markEdgeQueueAsFailed($queueId);
            return;
        }

        $payloadJson = json_encode($edgePayload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $signature   = $this->signPayload($payloadJson, $node);

        $syncId = $this->createSyncLog($nodeId, 'outgoing', 'edges', strlen($payloadJson));

        $success = $this->sendToRemoteNode($node['node_url'], '/federation/edges', [
            'payload'   => $payloadJson,
            'signature' => $signature,
        ]);

        if ($success) {
            $this->markEdgeQueueAsSent($queueId);
            $this->markSyncLogSuccess($syncId);
        } else {
            $this->incrementEdgeQueueAttempts($queueId);
            $this->markSyncLogError($syncId, 'HTTP send failed');
        }
    }

    protected function buildEdgePayload($edgeId, $operation)
    {
        $sql = "
            SELECT 
                e.edge_id,
                e.source_type,
                e.source_id,
                e.target_type,
                e.target_id,
                e.edge_type_id,
                e.weight,
                e.confidence,
                e.created_at,
                e.updated_at,
                e.deleted_at
            FROM edges e
            WHERE e.edge_id = :edge_id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':edge_id' => $edgeId]);
        $edge = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$edge && $operation !== 'delete') {
            return null;
        }

        return [
            'operation' => $operation,
            'edge'      => $edge,
        ];
    }

    protected function markEdgeQueueAsSent($queueId)
    {
        $sql = "
            UPDATE node_edge_queue
            SET sent_at = :sent_at,
                last_attempt_at = :last_attempt_at
            WHERE queue_id = :queue_id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':sent_at'        => $this->now,
            ':last_attempt_at'=> $this->now,
            ':queue_id'       => $queueId,
        ]);
    }

    protected function incrementEdgeQueueAttempts($queueId)
    {
        $sql = "
            UPDATE node_edge_queue
            SET attempts = attempts + 1,
                last_attempt_at = :last_attempt_at
            WHERE queue_id = :queue_id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':last_attempt_at'=> $this->now,
            ':queue_id'       => $queueId,
        ]);
    }

    protected function markEdgeQueueAsFailed($queueId)
    {
        // Optionally: mark as sent with no payload, or keep for manual review
        $this->incrementEdgeQueueAttempts($queueId);
    }

    /* ============================================================
       3. PROCESS OUTGOING CONTENT
       ============================================================ */

    public function processOutgoingContent($limit = 50)
    {
        $rows = $this->fetchContentQueueBatch($limit);

        foreach ($rows as $row) {
            $this->processSingleContentQueueRow($row);
        }
    }

    protected function fetchContentQueueBatch($limit)
    {
        $sql = "
            SELECT *
            FROM node_content_queue
            WHERE sent_at IS NULL
            ORDER BY queue_id ASC
            LIMIT :limit
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function processSingleContentQueueRow(array $row)
    {
        $queueId   = (int)$row['queue_id'];
        $nodeId    = (int)$row['node_id'];
        $contentId = (int)$row['content_id'];
        $operation = $row['operation'];

        $node  = $this->getNodeById($nodeId);
        if (!$node || $node['trust_level'] === 'none') {
            $this->markContentQueueAsFailed($queueId);
            return;
        }

        $contentPayload = $this->buildContentPayload($contentId, $operation);
        if ($contentPayload === null) {
            $this->markContentQueueAsFailed($queueId);
            return;
        }

        $payloadJson = json_encode($contentPayload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $signature   = $this->signPayload($payloadJson, $node);

        $syncId = $this->createSyncLog($nodeId, 'outgoing', 'content', strlen($payloadJson));

        $success = $this->sendToRemoteNode($node['node_url'], '/federation/content', [
            'payload'   => $payloadJson,
            'signature' => $signature,
        ]);

        if ($success) {
            $this->markContentQueueAsSent($queueId);
            $this->markSyncLogSuccess($syncId);
        } else {
            $this->incrementContentQueueAttempts($queueId);
            $this->markSyncLogError($syncId, 'HTTP send failed');
        }
    }

    protected function buildContentPayload($contentId, $operation)
    {
        $sql = "
            SELECT 
                c.content_id,
                c.domain_id,
                c.slug,
                c.title,
                c.body,
                c.created_at,
                c.updated_at,
                c.deleted_at
            FROM content c
            WHERE c.content_id = :content_id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':content_id' => $contentId]);
        $content = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$content && $operation !== 'delete') {
            return null;
        }

        return [
            'operation' => $operation,
            'content'   => $content,
        ];
    }

    protected function markContentQueueAsSent($queueId)
    {
        $sql = "
            UPDATE node_content_queue
            SET sent_at = :sent_at,
                last_attempt_at = :last_attempt_at
            WHERE queue_id = :queue_id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':sent_at'        => $this->now,
            ':last_attempt_at'=> $this->now,
            ':queue_id'       => $queueId,
        ]);
    }

    protected function incrementContentQueueAttempts($queueId)
    {
        $sql = "
            UPDATE node_content_queue
            SET attempts = attempts + 1,
                last_attempt_at = :last_attempt_at
            WHERE queue_id = :queue_id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':last_attempt_at'=> $this->now,
            ':queue_id'       => $queueId,
        ]);
    }

    protected function markContentQueueAsFailed($queueId)
    {
        $this->incrementContentQueueAttempts($queueId);
    }

    /* ============================================================
       4. INCOMING PAYLOAD HANDOFF
       ============================================================ */

    public function recordIncomingPayload($nodeId, $payloadType, $payloadJson, $signature)
    {
        $nodeId      = (int)$nodeId;
        $payloadType = strtolower($payloadType);

        if (!in_array($payloadType, ['atom','edge','content'], true)) {
            throw new InvalidArgumentException("Invalid payload_type: " . $payloadType);
        }

        $sql = "
            INSERT INTO node_inbox
            (node_id, payload_type, payload_json, signature, received_at)
            VALUES
            (:node_id, :payload_type, :payload_json, :signature, :received_at)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':node_id'      => $nodeId,
            ':payload_type' => $payloadType,
            ':payload_json' => $payloadJson,
            ':signature'    => $signature,
            ':received_at'  => $this->now,
        ]);
    }

    /* ============================================================
       5. NODE + LOG HELPERS
       ============================================================ */

    protected function getNodeById($nodeId)
    {
        $sql = "
            SELECT node_id, node_url, trust_level, shared_secret
            FROM nodes
            WHERE node_id = :node_id
              AND deleted_at IS NULL
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':node_id' => $nodeId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    protected function createSyncLog($nodeId, $direction, $type, $payloadSize)
    {
        $sql = "
            INSERT INTO node_sync_log
            (node_id, direction, sync_type, payload_size, status, started_at)
            VALUES
            (:node_id, :direction, :sync_type, :payload_size, 'success', :started_at)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':node_id'      => $nodeId,
            ':direction'    => $direction,
            ':sync_type'    => $type,
            ':payload_size' => (int)$payloadSize,
            ':started_at'   => $this->now,
        ]);

        return (int)$this->db->lastInsertId();
    }

    protected function markSyncLogSuccess($syncId)
    {
        $sql = "
            UPDATE node_sync_log
            SET status = 'success',
                finished_at = :finished_at
            WHERE sync_id = :sync_id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':finished_at' => $this->now,
            ':sync_id'     => $syncId,
        ]);
    }

    protected function markSyncLogError($syncId, $errorMessage)
    {
        $sql = "
            UPDATE node_sync_log
            SET status = 'error',
                error_message = :error_message,
                finished_at = :finished_at
            WHERE sync_id = :sync_id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':error_message'=> $errorMessage,
            ':finished_at'  => $this->now,
            ':sync_id'      => $syncId,
        ]);
    }

    /* ============================================================
       6. CRYPTO + HTTP HOOKS (STUBS)
       ============================================================ */

    protected function signPayload($payloadJson, array $node)
    {
        // For now: HMAC with shared_secret (later: proper key signing)
        $secret = $node['shared_secret'] ?? '';
        if ($secret === '') {
            return '';
        }

        return hash_hmac('sha256', $payloadJson, $secret);
    }

    protected function sendToRemoteNode($baseUrl, $path, array $data)
    {
        // Stub: you plug in curl/file_get_contents/Guzzle/etc.
        // Return true on success, false on failure.
        return false;
    }

    protected function utcNowBigint()
    {
        return (int)gmdate('YmdHis');
    }
}
?>