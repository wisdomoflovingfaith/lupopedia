<?php
/**
 * Minimal runtime LLM abstraction for MVP actor responses.
 *
 * Uses runtime_actors.yaml and falls back to deterministic mock responses when no provider is configured.
 */
class LlmRuntimeService
{
    private $actors;

    public function __construct($config_path)
    {
        $this->actors = $this->loadActors($config_path);
    }

    public function hasRuntimeActor($actor_id)
    {
        return isset($this->actors[(int) $actor_id]);
    }

    public function getActorConfig($actor_id)
    {
        $actor_id = (int) $actor_id;
        return isset($this->actors[$actor_id]) ? $this->actors[$actor_id] : null;
    }

    public function generateResponse($actor_id, $context)
    {
        $actor = $this->getActorConfig($actor_id);
        if (!$actor) {
            throw new Exception('Runtime actor is not configured.');
        }

        return $this->buildMockResponse($actor, $context);
    }

    private function loadActors($config_path)
    {
        $actors = array();
        if (!$config_path || !is_file($config_path)) {
            return $actors;
        }

        if (function_exists('yaml_parse_file')) {
            $parsed = @yaml_parse_file($config_path);
            if (is_array($parsed) && isset($parsed['actors']) && is_array($parsed['actors'])) {
                foreach ($parsed['actors'] as $actor) {
                    if (is_array($actor) && isset($actor['actor_id'])) {
                        $actor['capabilities'] = isset($actor['capabilities']) && is_array($actor['capabilities']) ? $actor['capabilities'] : array();
                        $actors[(int) $actor['actor_id']] = $actor;
                    }
                }
                return $actors;
            }
        }

        $lines = file($config_path, FILE_IGNORE_NEW_LINES);
        if (!is_array($lines)) {
            return $actors;
        }

        $current = null;
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed === '' || $trimmed === 'actors:') {
                continue;
            }

            if (strpos($trimmed, '- ') === 0) {
                if (is_array($current) && isset($current['actor_id'])) {
                    if (!isset($current['capabilities']) || !is_array($current['capabilities'])) {
                        $current['capabilities'] = array();
                    }
                    $actors[(int) $current['actor_id']] = $current;
                }
                $current = array();
                $trimmed = substr($trimmed, 2);
                if ($trimmed !== '' && strpos($trimmed, ':') !== false) {
                    list($key, $value) = explode(':', $trimmed, 2);
                    $current[trim($key)] = $this->parseYamlScalar(trim($value));
                }
                continue;
            }

            if (is_array($current) && strpos($trimmed, ':') !== false) {
                list($key, $value) = explode(':', $trimmed, 2);
                $current[trim($key)] = $this->parseYamlScalar(trim($value));
            }
        }

        if (is_array($current) && isset($current['actor_id'])) {
            if (!isset($current['capabilities']) || !is_array($current['capabilities'])) {
                $current['capabilities'] = array();
            }
            $actors[(int) $current['actor_id']] = $current;
        }

        return $actors;
    }

    private function parseYamlScalar($value)
    {
        $value = trim($value);
        if ($value === '[]') {
            return array();
        }

        if ($value !== '' && $value[0] === '[' && substr($value, -1) === ']') {
            $inner = trim(substr($value, 1, -1));
            if ($inner === '') {
                return array();
            }
            $parts = explode(',', $inner);
            $result = array();
            foreach ($parts as $part) {
                $result[] = trim($part, " \t\n\r\0\x0B\"'");
            }
            return $result;
        }

        if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') || (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
            return substr($value, 1, -1);
        }

        if (preg_match('/^\d+$/', $value)) {
            return (int) $value;
        }

        return $value;
    }

    private function buildMockResponse($actor, $context)
    {
        $actor_name = isset($actor['name']) ? strtoupper($actor['name']) : 'RUNTIME';
        $messages = isset($context['messages']) && is_array($context['messages']) ? $context['messages'] : array();
        $latest = count($messages) > 0 ? $messages[count($messages) - 1] : array();
        $latest_text = isset($latest['message_text']) ? trim($latest['message_text']) : '';
        $role = isset($actor['role']) ? $actor['role'] : 'runtime';

        if ($latest_text === '') {
            return $actor_name . ' runtime reply: No message context was available.';
        }

        $short_text = preg_replace('/\s+/', ' ', $latest_text);
        if (strlen($short_text) > 180) {
            $short_text = substr($short_text, 0, 177) . '...';
        }

        return $actor_name . ' runtime reply (' . $role . '): Based on the last ' . count($messages) . ' messages, the immediate response is to address: ' . $short_text;
    }
}