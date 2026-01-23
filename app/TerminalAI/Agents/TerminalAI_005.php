<?php
/**
 * Terminal AI Agent 005 - UTC_TIMEKEEPER
 * 
 * Provides authoritative UTC timestamps in YYYYMMDDHHIISS format.
 * Kernel-level temporal authority for deterministic time queries.
 * 
 * @package App\TerminalAI\Agents
 * @version 4.0.101
 * @author Captain Wolfie
 * @see docs/doctrine/UTC_TIMEKEEPER_DOCTRINE.md
 */
namespace App\TerminalAI\Agents;

use App\TerminalAI\Contracts\TerminalAgentInterface;

class TerminalAI_005 implements TerminalAgentInterface
{
    public function handle(string $input): string
    {
        // Expected input: what_is_current_utc_time_yyyymmddhhiiss
        if (trim($input) !== 'what_is_current_utc_time_yyyymmddhhiiss') {
            return "error: invalid_command";
        }

        // Get server time and convert to UTC
        $utc = new \DateTime('now', new \DateTimeZone('UTC'));
        $formatted = $utc->format('YmdHis');

        return "current_utc_time_yyyymmddhhiiss: " . $formatted;
    }
}
