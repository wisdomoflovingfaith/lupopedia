<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: functions
  when_updated: "20260406013447"
  file_path_from_root: "includes/functions/ai_activation.php"
  web_path: "http://www.lupopedia.com/lupopedia/includes/functions/ai_activation.php"
  questions_toon: null
  federation_node_id: 0
  channel_id: 42
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "functions"
  artifact_kind: "ai_activation"
  purpose: "Thin entry — lupo_random_bytes for installers/CLI; canonical isActorAIRunning/ensureActorActive live in ai_agent_integration.php"
  tags: ["ai", "activation", "functions", "delegation"]
---
*/

/**
 * PHP 5.3-safe random bytes.
 */
if (!function_exists('lupo_random_bytes')) {
    function lupo_random_bytes($length)
    {
        if (function_exists('random_bytes')) {
            return random_bytes($length);
        }
        if (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($length);
            return $bytes !== false ? $bytes : lupo_random_bytes_fallback($length);
        }
        return lupo_random_bytes_fallback($length);
    }
}

if (!function_exists('lupo_random_bytes_fallback')) {
    function lupo_random_bytes_fallback($length)
    {
        $bytes = '';
        for ($i = 0; $i < $length; $i++) {
            $bytes .= chr(mt_rand(0, 255));
        }
        return $bytes;
    }
}

require_once __DIR__ . '/ai_agent_integration.php';
