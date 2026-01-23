<?php

class ThothClaimScore
{
    public $thoth_claim_score_id;
    public $thoth_claim_id;
    public $actor_id;
    public $score_type;
    public $score_value;
    public $justification;

    public $created_ymdhis;
    public $updated_ymdhis;

    protected $db;

    public function __construct(PDO_DB $db)
    {
        $this->db = $db;
    }

    public function load($id)
    {
        $sql = "SELECT * FROM thoth_claim_scores WHERE thoth_claim_score_id = ?";
        return $this->db->row($sql, [$id]);
    }

    public function insert()
    {
        $sql = "INSERT INTO thoth_claim_scores
                (thoth_claim_id, actor_id, score_type, score_value, justification,
                 created_ymdhis, updated_ymdhis)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $this->db->query($sql, [
            $this->thoth_claim_id,
            $this->actor_id,
            $this->score_type,
            $this->score_value,
            $this->justification,
            $this->created_ymdhis,
            $this->updated_ymdhis
        ]);

        return $this->db->lastInsertId();
    }

    public function update()
    {
        $sql = "UPDATE thoth_claim_scores
                SET thoth_claim_id = ?, actor_id = ?, score_type = ?, score_value = ?,
                    justification = ?, updated_ymdhis = ?
                WHERE thoth_claim_score_id = ?";

        return $this->db->query($sql, [
            $this->thoth_claim_id,
            $this->actor_id,
            $this->score_type,
            $this->score_value,
            $this->justification,
            $this->updated_ymdhis,
            $this->thoth_claim_score_id
        ]);
    }
}
?>