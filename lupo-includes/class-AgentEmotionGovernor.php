<?php

class AgentEmotionGovernor
{
    public function regulate(AgentEmotionState $state): AgentEmotionState
    {
        // Hard safety clamp
        if ($state->moodValue > 1.0) {
            $state->moodValue = 1.0;
        }

        if ($state->moodValue < -1.0) {
            $state->moodValue = -1.0;
        }

        // Optional: dampen runaway loops
        $state->moodValue *= 0.95;

        return $state;
    }
}


?>