<?php

class ThothClaimEvidence
{
    public $thoth_claim_evidence_id;
    public $thoth_claim_id;
    public $content_id;
    public $atom_id;
    public $evidence_type;
    public $weight;
    public $reference_locator;
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
        $sql = "SELECT * FROM thoth_claim_evidence WHERE thoth_claim_evidence_id = ?";
        return $this->db->row($sql, [$id]);
    }

    public function insert()
    {
        $sql = "INSERT INTO thoth_claim_evidence
                (thoth_claim_id, content_id, atom_id, evidence_type,
                 weight, reference_locator, notes,
                 created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 0, NULL)";

        $this->db->query($sql, [
            $this->thoth_claim_id,
            $this->content_id,
            $this->atom_id,
            $this->evidence_type,
            $this->weight,
            $this->reference_locator,
            $this->notes,
            $this->created_ymdhis,
            $this->updated_ymdhis
        ]);

        return $this->db->lastInsertId();
    }

    public function update()
    {
        $sql = "UPDATE thoth_claim_evidence
                SET thoth_claim_id = ?, content_id = ?, atom_id = ?,
                    evidence_type = ?, weight = ?, reference_locator = ?, notes = ?,
                    updated_ymdhis = ?
                WHERE thoth_claim_evidence_id = ?";

        return $this->db->query($sql, [
            $this->thoth_claim_id,
            $this->content_id,
            $this->atom_id,
            $this->evidence_type,
            $this->weight,
            $this->reference_locator,
            $this->notes,
            $this->updated_ymdhis,
            $this->thoth_claim_evidence_id
        ]);
    }

    public function softDelete($timestamp)
    {
        $sql = "UPDATE thoth_claim_evidence
                SET is_deleted = 1, deleted_ymdhis = ?
                WHERE thoth_claim_evidence_id = ?";

        return $this->db->query($sql, [$timestamp, $this->thoth_claim_evidence_id]);
    }
}
?>