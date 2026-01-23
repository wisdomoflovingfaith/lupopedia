<?php

class ThothClaim
{
    public $thoth_claim_id;
    public $thoth_topic_id;
    public $statement_text;
    public $context_id;
    public $is_common_view;
    public $is_thoth_preferred;
    public $notes;

    public $created_ymdhis;
    public $updated_ymdhis;
    public $is_deleted;
    public $deleted_ymdhis;

    protected $db;

    public function __construct(PDO_DB $db)
    {
        $this->db = $db;
    }

    public function load($id)
    {
        $sql = "SELECT * FROM thoth_claims WHERE thoth_claim_id = ?";
        return $this->db->row($sql, [$id]);
    }

    public function insert()
    {
        $sql = "INSERT INTO thoth_claims
                (thoth_topic_id, statement_text, context_id,
                 is_common_view, is_thoth_preferred, notes,
                 created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, NULL)";

        $this->db->query($sql, [
            $this->thoth_topic_id,
            $this->statement_text,
            $this->context_id,
            $this->is_common_view,
            $this->is_thoth_preferred,
            $this->notes,
            $this->created_ymdhis,
            $this->updated_ymdhis
        ]);

        return $this->db->lastInsertId();
    }

    public function update()
    {
        $sql = "UPDATE thoth_claims
                SET thoth_topic_id = ?, statement_text = ?, context_id = ?,
                    is_common_view = ?, is_thoth_preferred = ?, notes = ?,
                    updated_ymdhis = ?
                WHERE thoth_claim_id = ?";

        return $this->db->query($sql, [
            $this->thoth_topic_id,
            $this->statement_text,
            $this->context_id,
            $this->is_common_view,
            $this->is_thoth_preferred,
            $this->notes,
            $this->updated_ymdhis,
            $this->thoth_claim_id
        ]);
    }

    public function softDelete($timestamp)
    {
        $sql = "UPDATE thoth_claims
                SET is_deleted = 1, deleted_ymdhis = ?
                WHERE thoth_claim_id = ?";

        return $this->db->query($sql, [$timestamp, $this->thoth_claim_id]);
    }
}
?>