<?php

class AgentEmotionRepository
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function load(int $agentId, int $domainId): AgentEmotionState
    {
        $sql = "
            SELECT *
            FROM agent_moods
            WHERE agent_id = :agent_id
              AND domain_id = :domain_id
              AND is_deleted = 0
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':agent_id'  => $agentId,
            ':domain_id' => $domainId
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $state = new AgentEmotionState($agentId, $domainId);

        if ($row) {
            $state->moodKey     = $row['mood_key'];
            $state->moodValue   = $row['mood_value'];
            $state->lightR      = $row['light_r'];
            $state->lightG      = $row['light_g'];
            $state->lightB      = $row['light_b'];
            $state->properties  = json_decode($row['properties'] ?? "{}", true);
            $state->createdYmdhis = (int) $row['created_ymdhis'];
            $state->updatedYmdhis = (int) $row['updated_ymdhis'];
        }

        return $state;
    }

    public function save(AgentEmotionState $state): void
    {
        $sql = "
            INSERT INTO agent_moods
            (agent_id, domain_id, mood_key, mood_value, light_r, light_g, light_b, properties, created_ymdhis, updated_ymdhis)
            VALUES
            (:agent_id, :domain_id, :mood_key, :mood_value, :light_r, :light_g, :light_b, :properties, :created, :updated)
            ON DUPLICATE KEY UPDATE
                mood_key = VALUES(mood_key),
                mood_value = VALUES(mood_value),
                light_r = VALUES(light_r),
                light_g = VALUES(light_g),
                light_b = VALUES(light_b),
                properties = VALUES(properties),
                updated_ymdhis = VALUES(updated_ymdhis)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':agent_id'  => $state->agentId,
            ':domain_id' => $state->domainId,
            ':mood_key'  => $state->moodKey,
            ':mood_value'=> $state->moodValue,
            ':light_r'   => $state->lightR,
            ':light_g'   => $state->lightG,
            ':light_b'   => $state->lightB,
            ':properties'=> json_encode($state->properties),
            ':created'   => $state->createdYmdhis ?? (int) gmdate("YmdHis"),
            ':updated'   => (int) gmdate("YmdHis")
        ]);
    }
}

?>