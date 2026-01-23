<?php

class AgentEmotionInterpreter
{
    public function interpret(AgentEmotionState $state): string
    {
        if ($state->moodValue === null) {
            return "neutral";
        }

        if ($state->moodValue > 0.7) {
            return "intense";
        }

        if ($state->moodValue < -0.5) {
            return "shadowed";
        }

        return "calm";
    }
}


?>