<?php

class ThothTopic
{
    public $thoth_topic_id;
    public $question_id;
    public $title;
    public $summary;
    public $primary_context_id;

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
        $sql = "SELECT * FROM thoth_topics WHERE thoth_topic_id = ?";
        return $this->db->row($sql, [$id]);
    }

    public function insert()
    {
        $sql = "INSERT INTO thoth_topics
                (question_id, title, summary, primary_context_id,
                 created_ymdhis, updated_ymdhis, is_deleted, deleted_ymdhis)
                VALUES (?, ?, ?, ?, ?, ?, 0, NULL)";

        $this->db->query($sql, [
            $this->question_id,
            $this->title,
            $this->summary,
            $this->primary_context_id,
            $this->created_ymdhis,
            $this->updated_ymdhis
        ]);

        return $this->db->lastInsertId();
    }

    public function update()
    {
        $sql = "UPDATE thoth_topics
                SET question_id = ?, title = ?, summary = ?, primary_context_id = ?,
                    updated_ymdhis = ?
                WHERE thoth_topic_id = ?";

        return $this->db->query($sql, [
            $this->question_id,
            $this->title,
            $this->summary,
            $this->primary_context_id,
            $this->updated_ymdhis,
            $this->thoth_topic_id
        ]);
    }

    public function softDelete($timestamp)
    {
        $sql = "UPDATE thoth_topics
                SET is_deleted = 1, deleted_ymdhis = ?
                WHERE thoth_topic_id = ?";

        return $this->db->query($sql, [$timestamp, $this->thoth_topic_id]);
    }
}
?>