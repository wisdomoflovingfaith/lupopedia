<?php

class ReactActionValidator
{
    protected PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function agentCanCallReact(int $agentId, int $domainId): bool
    {
        $sql = "
            SELECT 1
            FROM agent_capabilities
            WHERE agent_id = :agent_id
              AND domain_id = :domain_id
              AND capability_key = 'call_react_actions'
              AND is_deleted = 0
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':agent_id'  => $agentId,
            ':domain_id' => $domainId
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public function validate(ReactActionRequest $request): bool
    {
        return $this->agentCanCallReact($request->agentId, $request->domainId);
    }
}
?>