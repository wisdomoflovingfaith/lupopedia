<?php

class ReactActionRequest
{
    public string $action;          // e.g. "openModal", "highlightNode", "navigateTo"
    public array $payload = [];     // arbitrary data for the action
    public int $agentId;            // which agent is making the request
    public int $domainId;           // domain context
    public int $timestamp;          // UTC YYYYMMDDHHMMSS

    public function __construct(int $agentId, int $domainId, string $action, array $payload = [])
    {
        $this->agentId   = $agentId;
        $this->domainId  = $domainId;
        $this->action    = $action;
        $this->payload   = $payload;
        $this->timestamp = (int) gmdate("YmdHis");
    }

    public function toArray(): array
    {
        return [
            'agent_id'  => $this->agentId,
            'domain_id' => $this->domainId,
            'action'    => $this->action,
            'payload'   => $this->payload,
            'timestamp' => $this->timestamp
        ];
    }
}
?>