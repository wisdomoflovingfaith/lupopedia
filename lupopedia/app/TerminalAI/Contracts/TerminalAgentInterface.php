<?php

namespace App\TerminalAI\Contracts;

interface TerminalAgentInterface
{
    public function handle(string $input): string;
}
