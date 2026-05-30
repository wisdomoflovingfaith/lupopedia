<?php
/**
 * Path-based channel artifact validation (LILITH / thread substantive body).
 * Delegates rules to Lupo_Channel_Artifact_Validator::validateThreadPostBody.
 *
 * @package Lupopedia
 * @since   4.0.80
 */
class ChannelArtifactValidator
{
    /**
     * @param string $path absolute or repo-relative path to a thread .md file
     * @return string|null error message or null if OK / not applicable
     */
    public static function validateThreadArtifact($path)
    {
        if (!is_string($path) || $path === '' || !is_readable($path)) {
            return 'THREAD_PATH_UNREADABLE';
        }
        $raw = file_get_contents($path);
        if ($raw === false) {
            return 'THREAD_READ_FAILED';
        }
        $fm = '';
        if (strpos(trim($raw), '---') === 0) {
            $end = strpos($raw, "\n---", 3);
            if ($end !== false) {
                $fm = substr($raw, 3, $end - 3);
            }
        }
        $mt = 'thread';
        if (preg_match('/message_type:\s*["\']?([a-z0-9_-]+)/i', $fm, $m)) {
            $mt = strtolower(trim($m[1]));
        }
        $meta = array();
        if (preg_match('/artifact_kind:\s*["\']?([a-z0-9_-]+)/i', $fm, $ak)) {
            $meta['artifact_kind'] = strtolower(trim($ak[1]));
        }
        $metaJson = empty($meta) ? null : json_encode($meta);
        return Lupo_Channel_Artifact_Validator::validateThreadPostBody($raw, $mt, $metaJson);
    }

    /**
     * @param string $path path under lupo-channels/{id}/direct/{actor_id}/*.md
     * @return string|null
     */
    public static function validateReplyArtifact($path)
    {
        if (!is_string($path) || $path === '' || !is_readable($path)) {
            return 'DIRECT_PATH_UNREADABLE';
        }
        $raw = file_get_contents($path);
        if ($raw === false) {
            return 'DIRECT_READ_FAILED';
        }
        $fm = '';
        if (strpos(trim($raw), '---') === 0) {
            $end = strpos($raw, "\n---", 3);
            if ($end !== false) {
                $fm = substr($raw, 3, $end - 3);
            }
        }
        $mt = 'direct';
        if (preg_match('/message_type:\s*["\']?([a-z0-9_-]+)/i', $fm, $m)) {
            $mt = strtolower(trim($m[1]));
        }
        $meta = array();
        if (preg_match('/artifact_kind:\s*["\']?([a-z0-9_-]+)/i', $fm, $ak)) {
            $meta['artifact_kind'] = strtolower(trim($ak[1]));
        }
        $akind = isset($meta['artifact_kind']) ? $meta['artifact_kind'] : '';
        if ($akind !== 'review' && $akind !== 'help_response') {
            return null;
        }
        $metaJson = json_encode($meta);
        return Lupo_Channel_Artifact_Validator::validateThreadPostBody($raw, $mt, $metaJson);
    }
}
