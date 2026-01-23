<?php

class SearchIndexer
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
       ENTRY POINT — PROCESS QUEUE
       ============================================================ */

    public function processQueue($limit = 50)
    {
        $rows = $this->fetchQueueBatch($limit);

        foreach ($rows as $row) {
            $result = $this->processSingleQueueRow($row);

            if ($result === true) {
                $this->markQueueProcessed($row['queue_id']);
            } else {
                $this->incrementQueueAttempts($row['queue_id']);
            }
        }
    }

    protected function fetchQueueBatch($limit)
    {
        $sql = "
            SELECT *
            FROM search_queue
            WHERE processed_at IS NULL
            ORDER BY queue_id ASC
            LIMIT :limit
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function processSingleQueueRow(array $row)
    {
        $type      = $row['source_type'];
        $id        = (int)$row['source_id'];
        $operation = $row['operation'];

        switch ($operation) {
            case 'index':
                return $this->indexItem($type, $id);

            case 'delete':
                return $this->deleteIndex($type, $id);

            default:
                return "Unknown operation: $operation";
        }
    }

    /* ============================================================
       INDEXING LOGIC
       ============================================================ */

    protected function indexItem($type, $id)
    {
        $data = $this->loadSourceData($type, $id);
        if (!$data) {
            return "Source not found";
        }

        $tokens = $this->tokenize($data['title'] . ' ' . $data['body']);

        return $this->upsertIndexRow($type, $id, $data['title'], $data['body'], $tokens);
    }

    protected function loadSourceData($type, $id)
    {
        switch ($type) {
            case 'content':
                return $this->loadContent($id);

            case 'atom':
                return $this->loadAtom($id);

            case 'collection':
                return $this->loadCollection($id);

            default:
                return null;
        }
    }

    protected function loadContent($contentId)
    {
        $sql = "
            SELECT title, body
            FROM content
            WHERE content_id = :id
              AND deleted_at IS NULL
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $contentId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return [
            'title' => $row['title'],
            'body'  => strip_tags($row['body']),
        ];
    }

    protected function loadAtom($atomId)
    {
        $sql = "
            SELECT label
            FROM atoms
            WHERE atom_id = :id
              AND deleted_at IS NULL
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $atomId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return [
            'title' => $row['label'],
            'body'  => '',
        ];
    }

    protected function loadCollection($collectionId)
    {
        $sql = "
            SELECT name, description
            FROM collections
            WHERE collection_id = :id
              AND deleted_at IS NULL
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $collectionId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        return [
            'title' => $row['name'],
            'body'  => strip_tags($row['description']),
        ];
    }

    /* ============================================================
       TOKENIZER
       ============================================================ */

    protected function tokenize($text)
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text);
        $text = preg_replace('/\s+/', ' ', $text);

        $words = explode(' ', trim($text));
        $words = array_filter($words, fn($w) => strlen($w) > 1);

        return implode(' ', $words);
    }

    /* ============================================================
       UPSERT INTO search_index
       ============================================================ */

    protected function upsertIndexRow($type, $id, $title, $body, $tokens)
    {
        $sql = "
            SELECT search_id
            FROM search_index
            WHERE source_type = :type
              AND source_id   = :id
              AND deleted_at IS NULL
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':type' => $type,
            ':id'   => $id,
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $this->updateIndexRow($row['search_id'], $title, $body, $tokens);
        }

        return $this->insertIndexRow($type, $id, $title, $body, $tokens);
    }

    protected function insertIndexRow($type, $id, $title, $body, $tokens)
    {
        $sql = "
            INSERT INTO search_index
            (domain_id, source_type, source_id, title, body, tokens, rank_score, updated_at)
            VALUES
            (:domain_id, :type, :id, :title, :body, :tokens, 1.0, :updated_at)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':domain_id' => $this->domainId,
            ':type'      => $type,
            ':id'        => $id,
            ':title'     => $title,
            ':body'      => $body,
            ':tokens'    => $tokens,
            ':updated_at'=> $this->now,
        ]);

        return true;
    }

    protected function updateIndexRow($searchId, $title, $body, $tokens)
    {
        $sql = "
            UPDATE search_index
            SET title = :title,
                body = :body,
                tokens = :tokens,
                updated_at = :updated_at
            WHERE search_id = :search_id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':title'     => $title,
            ':body'      => $body,
            ':tokens'    => $tokens,
            ':updated_at'=> $this->now,
            ':search_id' => $searchId,
        ]);

        return true;
    }

    /* ============================================================
       DELETE FROM INDEX
       ============================================================ */

    protected function deleteIndex($type, $id)
    {
        $sql = "
            UPDATE search_index
            SET deleted_at = :deleted_at
            WHERE source_type = :type
              AND source_id   = :id
              AND deleted_at IS NULL
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':deleted_at' => $this->now,
            ':type'       => $type,
            ':id'         => $id,
        ]);

        return true;
    }

    /* ============================================================
       QUEUE HELPERS
       ============================================================ */

    protected function markQueueProcessed($queueId)
    {
        $sql = "
            UPDATE search_queue
            SET processed_at = :processed_at
            WHERE queue_id = :queue_id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':processed_at' => $this->now,
            ':queue_id'     => $queueId,
        ]);
    }

    protected function incrementQueueAttempts($queueId)
    {
        $sql = "
            UPDATE search_queue
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

    /* ============================================================
       UTIL
       ============================================================ */

    protected function utcNowBigint()
    {
        return (int)gmdate('YmdHis');
    }
}
?>