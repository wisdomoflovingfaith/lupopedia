<?php

class AgentEmotionRuntime
{
    protected AgentEmotionRepository $repo;
    protected AgentEmotionInterpreter $interpreter;
    protected AgentEmotionGovernor $governor;

    public function __construct(
        AgentEmotionRepository $repo,
        AgentEmotionInterpreter $interpreter,
        AgentEmotionGovernor $governor
    ) {
        $this->repo = $repo;
        $this->interpreter = $interpreter;
        $this->governor = $governor;
    }

    public function updateEmotion(
        int $agentId,
        int $domainId,
        float $deltaR,
        float $deltaG,
        float $deltaB
    ): AgentEmotionState {

        $state = $this->repo->load($agentId, $domainId);

        // Update raw emotional axes
        $state->lightR = ($state->lightR ?? 0) + $deltaR;
        $state->lightG = ($state->lightG ?? 0) + $deltaG;
        $state->lightB = ($state->lightB ?? 0) + $deltaB;

        // Compute mood value (simple example)
        $state->moodValue = ($state->lightR + $state->lightG + $state->lightB) / 3;

        // Interpret mood
        $state->moodKey = $this->interpreter->interpret($state);

        // Regulate mood
        $state = $this->governor->regulate($state);

        // Save
        $this->repo->save($state);

        return $state;
    }
}

?>