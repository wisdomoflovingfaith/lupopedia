<?php

namespace App\Http\Controllers;

use App\TerminalAI\Services\TerminalAIService;

/**
 * Terminal AI Controller — plain PHP. No Laravel.
 * Input: array with 'command' key. Returns: array with 'output' key. Caller sends JSON.
 */
class TerminalAIController
{
    /**
     * Execute terminal command. $input = ['command' => string]. Returns ['output' => string].
     */
    public function execute(array $input): array
    {
        $service = new TerminalAIService();
        $command = $input['command'] ?? '';
        return [
            'output' => $service->execute($command),
        ];
    }
}
