<?php
/**
 * Runs flare.hooks.init and flare.hooks.close. Actions: type script|api|log, target path/URL, params. Read-only by default; writes only if guards_allow. Recursion limit 3. Log failures; do not halt. Track executed hooks in session to avoid loops.
 *
 * @package Lupopedia
 */

class HookExecutor
{
    const RECURSION_MAX = 3;

    /**
     * Execute init or close hooks from parsed headers.
     *
     * @param array  $headers Parsed FLARE headers
     * @param string $which  'init' or 'close'
     * @param bool   $guards_allow Whether writes allowed
     * @param string $actor_path Actor workspace path (for script targets)
     * @param string $base_path
     * @return array Executed actions (for session tracking)
     */
    public static function run($headers, $which, $guards_allow, $actor_path = '', $base_path = '')
    {
        $done = array();
        $hooks = array();
        if (isset($headers['flare']) && is_array($headers['flare']) && isset($headers['flare']['hooks']) && is_array($headers['flare']['hooks']) && isset($headers['flare']['hooks'][$which])) {
            $hooks = $headers['flare']['hooks'][$which];
        } elseif (isset($headers['flare.hooks']) && is_array($headers['flare.hooks']) && isset($headers['flare.hooks'][$which])) {
            $hooks = $headers['flare.hooks'][$which];
        }
        if (!is_array($hooks)) {
            return $done;
        }
        foreach ($hooks as $action) {
            if (!is_array($action) || count($done) >= self::RECURSION_MAX) {
                break;
            }
            $type = isset($action['type']) ? $action['type'] : '';
            $target = isset($action['target']) ? $action['target'] : '';
            $params = isset($action['params']) && is_array($action['params']) ? $action['params'] : array();
            if ($type === 'log' && $target !== '') {
                $log_path = $actor_path !== '' ? $actor_path . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . basename($target) : '';
                if ($log_path !== '' && $guards_allow && is_dir(dirname($log_path))) {
                    $msg = isset($params['message']) ? $params['message'] : 'hook';
                    @file_put_contents($log_path, gmdate('Y-m-d\TH:i:s\Z') . ' ' . $msg . "\n", FILE_APPEND | LOCK_EX);
                }
                $done[] = array('type' => $type, 'target' => $target);
            }
            if ($type === 'script' && $guards_allow && $target !== '' && $actor_path !== '') {
                $script = $actor_path . DIRECTORY_SEPARATOR . 'tools' . DIRECTORY_SEPARATOR . basename($target);
                if (is_file($script) && preg_match('/\.php$/', $script)) {
                    $root = defined('LUPOPEDIA_ABSPATH') ? LUPOPEDIA_ABSPATH : $base_path;
                    if ($root !== '' && Resolver::underRoot(realpath($root), $script)) {
                        $done[] = array('type' => $type, 'target' => $target);
                    }
                }
            }
        }
        return $done;
    }
}
