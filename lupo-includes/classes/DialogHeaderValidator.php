<?php
/**
 * DialogHeaderValidator — Validates required dialog headers (department_id, channel_id, thread_id, agent_name, actor_name).
 * Non-fatal: prints WARNING for missing headers and continues.
 *
 * @package Lupopedia
 * @version 4.0.59
 */

class DialogHeaderValidator
{
    /**
     * Required dialog headers that must be present for correct routing and identity.
     *
     * @var array
     */
    protected static $required = array('department_id', 'channel_id', 'thread_id', 'agent_name', 'actor_name');

    /**
     * Validate context has required dialog headers. Echo WARNING for each missing; do not crash.
     *
     * @param array $ctx Resolved context (actor_name, agent_name, department_id, channel_id, thread_id, ...)
     */
    public static function validate($ctx)
    {
        if (!is_array($ctx)) {
            return;
        }
        foreach (self::$required as $header) {
            $missing = false;
            if ($header === 'department_id') {
                $missing = !isset($ctx['department_id']);
            } elseif ($header === 'channel_id') {
                $missing = !isset($ctx['channel_id']);
            } elseif ($header === 'thread_id') {
                $missing = !isset($ctx['thread_id']);
            } elseif ($header === 'agent_name') {
                $missing = empty($ctx['agent_name']);
            } elseif ($header === 'actor_name') {
                $missing = empty($ctx['actor_name']);
            }
            if ($missing) {
                echo "WARNING: Missing required dialog header: " . $header . "\n";
            }
        }
    }
}
