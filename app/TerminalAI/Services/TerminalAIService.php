<?php
/**
 * Terminal AI Service
 * 
 * Orchestrates Terminal AI agent execution and routing.
 * 
 * @package App\TerminalAI\Services
 * @version 4.0.101
 * @author Captain Wolfie
 */
namespace App\TerminalAI\Services;

use App\TerminalAI\Agents\TerminalAI_001;

class TerminalAIService
{
    public function execute(string $command): string
    {
        $agent = new TerminalAI_001();
        return $agent->handle($command);
    }

    public function utc(): string
    {
        $agent = new \App\TerminalAI\Agents\TerminalAI_005();
        return $agent->handle('what_is_current_utc_time_yyyymmddhhiiss');
    }
}
