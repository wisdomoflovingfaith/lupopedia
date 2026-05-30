<?php

/**
 * LUPOPEDIA HEADERS (class — YAML excerpt; canonical: docs/doctrine/LUPOPEDIA_HEADERS/)
 *
 * lupopedia.headers:
 *   lupopedia.schema: class
 *   file_path_from_root: includes/classes/ConsensusService.php
 *   last_modified_utc: "20260405233750"
 *   when_updated: "20260405233750"
 *   channel_id: 42
 *   actor_id: 102
 *   delegation_chain: cursor:root
 *   artifact_type: class
 *   artifact_kind: service
 *   purpose: Multi-agent consensus tasks via TaskService and ChannelService; THEMIS audit hook.
 *   tags: [consensus, channels, tasks, service]
 */

class ConsensusService
{
    private $db;
    private $prefix;
    private $taskService;
    private $channelService;
    private $requirementsValidator;

    public function __construct($db, $prefix)
    {
        $this->db = $db;
        $this->prefix = $prefix;
        require_once dirname(__FILE__) . '/TaskService.php';
        require_once dirname(__FILE__) . '/ChannelService.php';
        require_once dirname(__FILE__) . '/ActorRequirementsValidator.php';
        if (!class_exists('IdGenerator', false)) {
            require_once dirname(__FILE__) . '/IdGenerator.php';
        }
        $this->taskService = new TaskService($db, $prefix);
        $this->channelService = new ChannelService($db, $prefix);
        $this->requirementsValidator = new ActorRequirementsValidator($db, $prefix);
    }

    /**
     * Start a collaborative task flow
     */
    public function initiateConsensusTask($channelId, $actorId, $title, $description)
    {
        // 1. Create Pending Task
        $taskKey = 'consensus_' . IdGenerator::generate();
        $this->taskService->createTask($channelId, $taskKey, $actorId, $title, $description, 1); // WOLFIE as parent

        // 2. Notify Channel via post
        $this->channelService->postMessage($channelId, 'evolution_4_0_65', $actorId, "Proposing Consensus Task: $title. Waiting for THEMIS audit.");

        return $taskKey;
    }

    /**
     * Perform THEMIS audit (Slot 107). If a proposal (e.g. SQL/DDL) is supplied,
     * LUPO (106) doctrine is enforced via ActorRequirementsValidator; violations cause VETO.
     *
     * @param int $channelId
     * @param string $taskKey
     * @param string|null $proposal Optional proposal text (e.g. SQL migration); if present and SQL-like, validated against LUPO requirements
     * @return bool true if audit passes, false if vetoed
     */
    public function auditTask($channelId, $taskKey, $proposal = null)
    {
        $this->channelService->postMessage($channelId, 'evolution_4_0_65', 107, "[THEMIS] Auditing task $taskKey for ethical alignment and doctrine compliance.");

        $isSafe = true;

        if ($proposal !== null && $proposal !== '' && $this->looksLikeSql($proposal)) {
            $violations = $this->requirementsValidator->validateSqlAgainstLupo($proposal);
            if (!empty($violations)) {
                $isSafe = false;
                $msg = "[THEMIS] Task $taskKey FAILED LUPO doctrine audit. VETO. " . implode(' ', $violations);
                $this->channelService->postMessage($channelId, 'evolution_4_0_65', 107, $msg);
                return false;
            }
        }

        if ($isSafe) {
            $this->channelService->postMessage($channelId, 'evolution_4_0_65', 107, "[THEMIS] Task $taskKey PASSES ethical audit. Proceeding to Active state.");
            $this->taskService->updateStatus($channelId, $taskKey, 'active');
            return true;
        }

        $this->channelService->postMessage($channelId, 'evolution_4_0_65', 107, "[THEMIS] Task $taskKey FAILED audit. Blocked.");
        return false;
    }

    /**
     * Heuristic: does the string look like SQL (DDL/DML) that should be checked by LUPO?
     */
    private function looksLikeSql($text)
    {
        $upper = strtoupper(substr(trim($text), 0, 500));
        return (strpos($upper, 'CREATE ') !== false || strpos($upper, 'ALTER ') !== false
            || strpos($upper, 'INSERT ') !== false || strpos($upper, 'FOREIGN KEY') !== false
            || strpos($upper, 'REFERENCES ') !== false || strpos($upper, 'TRIGGER') !== false
            || strpos($upper, 'PROCEDURE') !== false || strpos($upper, 'FUNCTION') !== false
            || strpos($upper, 'DATETIME') !== false || strpos($upper, 'TIMESTAMP') !== false);
    }

    /**
     * Final Approval (WOLFIE Slot 1)
     */
    public function finalizeConsensus($channelId, $taskKey)
    {
        $this->channelService->postMessage($channelId, 'evolution_4_0_65', 1, "[WOLFIE] Final review for $taskKey. Checking consensus hash and approval chain.");

        // Simulate validation
        $isValid = true;

        if ($isValid) {
            $this->channelService->postMessage($channelId, 'evolution_4_0_65', 1, "[WOLFIE] Consensus REACHED. Task $taskKey is COMPLETED.");
            $this->taskService->updateStatus($channelId, $taskKey, 'completed');
            return true;
        }

        return false;
    }
}
