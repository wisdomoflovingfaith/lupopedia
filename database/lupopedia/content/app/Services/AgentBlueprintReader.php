<?php

namespace App\Services;

class AgentBlueprintReader
{
    public function getAgentRole($agentKey)
    {
        $config = $this->readAgentConfig($agentKey);
        if (is_array($config) && isset($config['agent_role']) && is_string($config['agent_role']) && $config['agent_role'] !== '') {
            return $config['agent_role'];
        }
        return 'watcher';
    }

    public function getBlueprintPath($agentKey)
    {
        $path = $this->resolveAgentConfigPath($agentKey);
        if ($path === null) {
            return null;
        }
        return str_replace('\\', '/', dirname($path)) . '/';
    }

    protected function readAgentConfig($agentKey)
    {
        $path = $this->resolveAgentConfigPath($agentKey);
        if ($path === null) {
            return null;
        }
        $raw = @file_get_contents($path);
        if ($raw === false) {
            return null;
        }
        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : null;
    }

    protected function resolveAgentConfigPath($agentKey)
    {
        if (!is_string($agentKey) || trim($agentKey) === '') {
            return null;
        }

        $agentKey = trim($agentKey);
        $base = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : '';
        if ($base === '' && function_exists('getcwd')) {
            $base = getcwd();
        }
        if ($base === '') {
            return null;
        }

        $candidates = array(
            rtrim($base, '/\\') . DIRECTORY_SEPARATOR . 'lupo-agents' . DIRECTORY_SEPARATOR . $agentKey . DIRECTORY_SEPARATOR . 'agent.json',
            rtrim($base, '/\\') . DIRECTORY_SEPARATOR . 'archive' . DIRECTORY_SEPARATOR . 'lupo-agents' . DIRECTORY_SEPARATOR . $agentKey . DIRECTORY_SEPARATOR . 'agent.json'
        );

        foreach ($candidates as $path) {
            if (is_file($path) && is_readable($path)) {
                return $path;
            }
        }

        return null;
    }
}
