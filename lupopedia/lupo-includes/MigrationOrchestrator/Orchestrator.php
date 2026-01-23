<?php
/**
 * ======================================================================
 * WOLFIE HEADER
 * ======================================================================
 * wolfie.headers: explicit architecture with structured clarity for every file.
 * file.last_modified_system_version: 4.0.35
 * header_atoms:
 *   - GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *   - GLOBAL_CURRENT_AUTHORS
 * updated: 2026-01-15
 * author: GLOBAL_CURRENT_AUTHORS
 * architect: Captain Wolfie
 * dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Updated Orchestrator class for Migration Orchestrator state machine. Manages state transitions, coordinates migration execution, and enforces state machine rules. Implements 8-state machine: idle, preparing, validating_pre, migrating, validating_post, completing, rolling_back, failed (guardrail added 4.0.35)."
 *   mood: "00FF00"
 * tags:
 *   categories: ["migration", "orchestration", "state-machine"]
 *   collections: ["core-docs"]
 *   channels: ["dev"]
 * file:
 *   title: "Migration Orchestrator"
 *   description: "Main orchestrator class that manages migration state machine transitions and execution lifecycle"
 *   version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
 *   status: published
 *   author: GLOBAL_CURRENT_AUTHORS
 * ======================================================================
 */

namespace Lupopedia\MigrationOrchestrator;

use Lupopedia\MigrationOrchestrator\Models\Migration;
use Lupopedia\MigrationOrchestrator\State\StateInterface;
use Lupopedia\MigrationOrchestrator\State\IdleState;
use Lupopedia\MigrationOrchestrator\State\PreparingState;
use Lupopedia\MigrationOrchestrator\State\ValidatingPreState;
use Lupopedia\MigrationOrchestrator\State\MigratingState;
use Lupopedia\MigrationOrchestrator\State\ValidatingPostState;
use Lupopedia\MigrationOrchestrator\State\CompletingState;
use Lupopedia\MigrationOrchestrator\State\RollingBackState;
use Lupopedia\MigrationOrchestrator\State\FailedState;
use Lupopedia\MigrationOrchestrator\Exceptions\StateTransitionException;

/**
 * Orchestrator
 * 
 * Main orchestrator class that manages migration state machine transitions
 * and coordinates the migration execution lifecycle.
 * 
 * The orchestrator:
 * - Manages state transitions according to the 8-state machine (7 original + failed guardrail)
 * - Validates state transitions before executing them
 * - Coordinates with validators, loggers, and state recorders
 * - Provides access to migration, logger, and validator instances
 * - Handles automatic execution flow through all states
 * 
 * State Machine Flow:
 * idle → preparing → validating_pre → migrating → validating_post → completing
 *                      ↓                ↓              ↓
 *                   failed          rolling_back    rolling_back (on failure)
 *                      ↓                ↓
 *                   idle            failed (if rollback fails)
 * 
 * @package Lupopedia\MigrationOrchestrator
 * @version GLOBAL_CURRENT_LUPOPEDIA_VERSION
 */
class Orchestrator
{
    private Migration $migration;
    private StateInterface $currentState;
    private LoggerInterface $logger;
    private DoctrineValidator $doctrineValidator;
    private StateTransitionRecorder $stateTransitionRecorder;
    
    /**
     * Map state IDs to state classes
     * 
     * State IDs match the values in lupopedia_orchestration.lupo_migration_system_state table:
     * 1 = idle
     * 2 = preparing
     * 3 = validating_pre
     * 4 = migrating
     * 5 = validating_post
     * 6 = completing
     * 7 = rolling_back
     * 8 = failed (explicit failure state - guardrail added 4.0.35)
     */
    private const STATE_MAP = [
        1 => IdleState::class,
        2 => PreparingState::class,
        3 => ValidatingPreState::class,
        4 => MigratingState::class,
        5 => ValidatingPostState::class,
        6 => CompletingState::class,
        7 => RollingBackState::class,
        8 => FailedState::class,
    ];
    
    /**
     * Constructor
     * 
     * @param Migration $migration The migration to orchestrate
     * @param LoggerInterface $logger Logger for migration events
     * @param DoctrineValidator $doctrineValidator Validator for doctrine compliance
     * @param StateTransitionRecorder $stateTransitionRecorder Recorder for state transitions
     */
    public function __construct(
        Migration $migration,
        LoggerInterface $logger,
        DoctrineValidator $doctrineValidator,
        StateTransitionRecorder $stateTransitionRecorder
    ) {
        $this->migration = $migration;
        $this->logger = $logger;
        $this->doctrineValidator = $doctrineValidator;
        $this->stateTransitionRecorder = $stateTransitionRecorder;
        
        // Set initial state based on migration's current state
        $this->setStateFromId($migration->getStateId());
    }
    
    /**
     * Transition to a new state
     * 
     * Validates the transition, executes leave/enter hooks, and processes the new state.
     * Throws StateTransitionException if transition is invalid.
     * 
     * @param string $stateClass Fully qualified state class name
     * @return void
     * @throws StateTransitionException If transition is invalid
     */
    public function transitionTo(string $stateClass): void
    {
        // Validate state class exists and implements StateInterface
        if (!class_exists($stateClass) || !is_subclass_of($stateClass, StateInterface::class)) {
            throw new StateTransitionException(
                sprintf('Invalid state class: %s', $stateClass)
            );
        }
        
        /** @var StateInterface $newState */
        $newState = new $stateClass();
        
        // Check if we can leave current state
        if (!$this->currentState->canLeave($this->migration)) {
            throw new StateTransitionException(
                sprintf(
                    'Cannot leave state %s for migration %d',
                    $this->currentState::getName(),
                    $this->migration->getId()
                )
            );
        }
        
        // Check if transition is allowed
        $allowedTransitions = $this->currentState->getPossibleTransitions();
        if (!in_array($stateClass, $allowedTransitions)) {
            throw new StateTransitionException(
                sprintf(
                    'Invalid transition from %s to %s for migration %d. Allowed transitions: %s',
                    $this->currentState::getName(),
                    $newState::getName(),
                    $this->migration->getId(),
                    implode(', ', array_map(fn($c) => basename(str_replace('\\', '/', $c)), $allowedTransitions))
                )
            );
        }
        
        // Check if we can enter new state
        if (!$newState->canEnter($this->migration)) {
            throw new StateTransitionException(
                sprintf(
                    'Cannot enter state %s from state %s for migration %d',
                    $newState::getName(),
                    $this->currentState::getName(),
                    $this->migration->getId()
                )
            );
        }
        
        // Validate the new state
        $validationErrors = $newState->validate($this->migration);
        if (!empty($validationErrors)) {
            throw new StateTransitionException(
                sprintf(
                    'Validation failed for state %s: %s',
                    $newState::getName(),
                    implode(', ', $validationErrors)
                )
            );
        }
        
        // Record transition start
        $this->stateTransitionRecorder->recordTransitionStart(
            $this->migration->getId(),
            $this->currentState::getId(),
            $newState::getId()
        );
        
        // Leave current state
        $this->currentState->leave($this->migration, $this);
        
        // Enter new state
        $newState->enter($this->migration, $this);
        
        // Update current state
        $this->currentState = $newState;
        
        // Record transition completion
        $this->stateTransitionRecorder->recordTransitionComplete(
            $this->migration->getId(),
            $newState::getId()
        );
        
        // Process the new state
        $this->currentState->process($this->migration, $this);
    }
    
    /**
     * Get possible transitions from current state
     * 
     * @return array<string> Array of fully qualified state class names
     */
    public function getPossibleTransitions(): array
    {
        return $this->currentState->getPossibleTransitions();
    }
    
    /**
     * Execute the migration (transition through the full lifecycle)
     * 
     * Automatically transitions through all states in the normal flow:
     * idle → preparing → validating_pre → migrating → validating_post → completing
     * 
     * If any transition fails, automatically transitions to rolling_back state.
     * 
     * @return void
     * @throws StateTransitionException If any transition fails
     */
    public function execute(): void
    {
        $this->logger->info(sprintf(
            'Starting execution of migration %d',
            $this->migration->getId()
        ));
        
        // Follow the state transitions automatically
        $transitions = [
            PreparingState::class,
            ValidatingPreState::class,
            MigratingState::class,
            ValidatingPostState::class,
            CompletingState::class,
        ];
        
        foreach ($transitions as $stateClass) {
            try {
                $this->transitionTo($stateClass);
            } catch (StateTransitionException $e) {
                // If any transition fails, go to rolling_back state
                $this->logger->error(sprintf(
                    'Migration %d failed in state %s: %s. Transitioning to rolling_back.',
                    $this->migration->getId(),
                    $this->currentState::getName(),
                    $e->getMessage()
                ));
                
                try {
                    $this->transitionTo(RollingBackState::class);
                } catch (StateTransitionException $rollbackException) {
                    // If rollback transition also fails, log and rethrow original exception
                    $this->logger->error(sprintf(
                        'Failed to transition migration %d to rolling_back: %s',
                        $this->migration->getId(),
                        $rollbackException->getMessage()
                    ));
                }
                
                throw $e;
            }
        }
        
        $this->logger->info(sprintf(
            'Migration %d completed successfully',
            $this->migration->getId()
        ));
    }
    
    /**
     * Get current state
     * 
     * @return StateInterface Current state instance
     */
    public function getCurrentState(): StateInterface
    {
        return $this->currentState;
    }
    
    /**
     * Get migration
     * 
     * @return Migration Migration instance
     */
    public function getMigration(): Migration
    {
        return $this->migration;
    }
    
    /**
     * Get logger
     * 
     * @return LoggerInterface Logger instance
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
    
    /**
     * Get doctrine validator
     * 
     * @return DoctrineValidator Doctrine validator instance
     */
    public function getDoctrineValidator(): DoctrineValidator
    {
        return $this->doctrineValidator;
    }
    
    /**
     * Get state transition recorder
     * 
     * @return StateTransitionRecorder State transition recorder instance
     */
    public function getStateTransitionRecorder(): StateTransitionRecorder
    {
        return $this->stateTransitionRecorder;
    }
    
    /**
     * Set state from state ID
     * 
     * Internal method to initialize the current state from a database state ID.
     * 
     * @param int $stateId State ID from database
     * @return void
     * @throws \InvalidArgumentException If state ID is invalid
     */
    private function setStateFromId(int $stateId): void
    {
        if (!isset(self::STATE_MAP[$stateId])) {
            throw new \InvalidArgumentException(
                sprintf('Invalid state ID: %d', $stateId)
            );
        }
        
        $stateClass = self::STATE_MAP[$stateId];
        $this->currentState = new $stateClass();
    }
}
