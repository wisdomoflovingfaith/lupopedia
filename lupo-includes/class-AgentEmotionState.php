<?php

class AgentEmotionState
{
    public int $agentId;
    public int $domainId;

    public ?string $moodKey = null;   // e.g. "calm", "intensity", "shadow"
    public ?float $moodValue = null;  // interpreted mood value

    public ?float $lightR = null;     // raw emotional axes
    public ?float $lightG = null;
    public ?float $lightB = null;

    public array $properties = [];    // JSON metadata

    public int $updatedYmdhis;
    public int $createdYmdhis;

    public function __construct(int $agentId, int $domainId)
    {
        $this->agentId  = $agentId;
        $this->domainId = $domainId;
    }
}


?>