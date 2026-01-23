<?php
/**
 * Pack Registry
 *
 * Central registry for Pack Architecture agents and their capabilities.
 * Prepares system for Pack Architecture activation in 4.1.0.
 *
 * @package Lupopedia
 * @version 4.0.107
 * @author Captain Wolfie
 */

namespace Lupopedia\Pack;

/**
 * PackRegistry
 *
 * Manages agent registration and capability tracking for Pack Architecture.
 */
class PackRegistry
{
    /** @var array Registered agents */
    private static $agents = [];

    /**
     * Register an agent with the Pack
     *
     * @param string $id Agent identifier
     * @param string $class Agent class name
     * @param array $capabilities Agent capabilities
     * @return bool Success status
     */
    public function registerAgent(string $id, string $class, array $capabilities = []): bool
    {
        try {
            // Validate required fields
            if (empty($id)) {
                error_log("PackRegistry::registerAgent() error: Agent ID is required");
                return false;
            }

            if (empty($class)) {
                error_log("PackRegistry::registerAgent() error: Agent class is required");
                return false;
            }

            // Prevent duplicate registrations
            if (isset(self::$agents[$id])) {
                error_log("PackRegistry::registerAgent() warning: Agent '{$id}' already registered. Skipping duplicate registration.");
                return false;
            }

            self::$agents[$id] = [
                'id' => $id,
                'class' => $class,
                'capabilities' => $capabilities,
                'registered_at' => gmdate('YmdHis'),
            ];

            return true;
        } catch (\Exception $e) {
            error_log("PackRegistry::registerAgent() error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * List all registered agents
     *
     * @return array Array of registered agents
     */
    public function listAgents(): array
    {
        return self::$agents;
    }

    /**
     * Get agent information by ID
     *
     * @param string $id Agent identifier
     * @return array|null Agent information or null if not found
     */
    public function getAgent(string $id): ?array
    {
        return self::$agents[$id] ?? null;
    }

    /**
     * Add capability to an agent
     *
     * @param string $id Agent identifier
     * @param string $capability Capability to add
     * @return bool Success status
     */
    public function addCapability(string $id, string $capability): bool
    {
        try {
            if (empty($id)) {
                error_log("PackRegistry::addCapability() error: Agent ID is required");
                return false;
            }

            if (empty($capability)) {
                error_log("PackRegistry::addCapability() error: Capability is required");
                return false;
            }

            if (!isset(self::$agents[$id])) {
                error_log("PackRegistry::addCapability() error: Agent '{$id}' not found");
                return false;
            }

            // Append capability if not already present
            if (!in_array($capability, self::$agents[$id]['capabilities'], true)) {
                self::$agents[$id]['capabilities'][] = $capability;
            }

            return true;
        } catch (\Exception $e) {
            error_log("PackRegistry::addCapability() error: " . $e->getMessage());
            return false;
        }
    }
}
