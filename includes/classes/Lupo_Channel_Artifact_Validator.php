<?php
/**
 * Canonical channel artifact filename and thread-id validation.
 *
 * Filename: YYYYMMDD_HHIISS_actor_purpose.md (UTC)
 * - actor: lowercase slug [a-z][a-z0-9]*
 * - purpose: lowercase hyphenated [a-z][a-z0-9-]*
 *
 * @package Lupopedia
 * @since   4.0.80
 */
class Lupo_Channel_Artifact_Validator
{
    const FILENAME_PATTERN = '/^[0-9]{8}_[0-9]{6}_[a-z][a-z0-9]*_[a-z][a-z0-9_-]+\.md$/';

    /**
     * Thread IDs must be strict positive integers (dialog_thread_id), not version strings.
     *
     * @param mixed $value Raw input (never cast float-like strings through (int) first)
     * @return bool
     */
    public static function isValidDialogThreadId($value)
    {
        if ($value === null || $value === '') {
            return false;
        }
        if (is_int($value)) {
            return $value > 0;
        }
        $s = trim((string) $value);
        if ($s === '' || $s[0] === '0') {
            return false;
        }
        if (!preg_match('/^[1-9][0-9]{0,17}$/', $s)) {
            return false;
        }
        return true;
    }

    /**
     * @param string $filename basename only
     * @return bool
     */
    public static function isValidCanonicalFilename($filename)
    {
        return is_string($filename) && preg_match(self::FILENAME_PATTERN, $filename) === 1;
    }

    /**
     * @param string $actor_slug already normalized
     * @param string $purpose_segment e.g. channel-system-review or broadcast-m99
     * @return string
     */
    public static function buildCanonicalFilename($dateYmd, $timeHis, $actor_slug, $purpose_segment)
    {
        $actor = strtolower(preg_replace('/[^a-z0-9]/', '', (string) $actor_slug));
        if ($actor === '' || !preg_match('/^[a-z]/', $actor)) {
            $actor = 'actor';
        }
        $purpose = strtolower(preg_replace('/[^a-z0-9_-]/', '-', (string) $purpose_segment));
        $purpose = trim($purpose, '-');
        if ($purpose === '' || !preg_match('/^[a-z]/', $purpose)) {
            $purpose = 'artifact';
        }
        return $dateYmd . '_' . $timeHis . '_' . $actor . '_' . $purpose . '.md';
    }

    /**
     * @param object $db
     * @param string $tablePrefix
     * @param int    $channel_id
     * @param int    $thread_id
     * @return bool
     */
    public static function threadExistsInChannel($db, $tablePrefix, $channel_id, $thread_id)
    {
        $t = $tablePrefix . 'dialog_threads';
        $stmt = $db->prepare("SELECT 1 FROM {$t} WHERE dialog_thread_id = :tid AND channel_id = :cid AND is_deleted = 0 LIMIT 1");
        $stmt->execute(array(':tid' => (int) $thread_id, ':cid' => (int) $channel_id));
        return $stmt->fetch() !== false;
    }

    /**
     * @param object $db
     * @param string $tablePrefix
     * @param int    $actor_id
     * @return string slug for filename segment
     */
    public static function resolveActorSlug($db, $tablePrefix, $actor_id)
    {
        $t = $tablePrefix . 'actors';
        $stmt = $db->prepare("SELECT slug, name FROM {$t} WHERE actor_id = :id AND is_deleted = 0 LIMIT 1");
        $stmt->execute(array(':id' => (int) $actor_id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return 'actor' . (int) $actor_id;
        }
        $slug = isset($row['slug']) ? trim((string) $row['slug']) : '';
        if ($slug !== '') {
            return strtolower(preg_replace('/[^a-z0-9]/', '', $slug));
        }
        $name = isset($row['name']) ? $row['name'] : 'actor';
        $s = strtolower(preg_replace('/[^a-z0-9]/', '', (string) $name));
        return $s !== '' ? $s : 'actor';
    }

    /**
     * Thread posts with message_type or meta.artifact_kind "review" must carry substantive body
     * (LILITH channel contract — non-empty artifact beyond YAML header).
     *
     * @param string      $message_text   Raw post body (markdown)
     * @param string      $message_type   e.g. review, thread, text
     * @param string|null $metadata_json  JSON string from API meta
     * @return string|null error message, or null if OK / not a review post
     */
    public static function validateThreadReviewBody($message_text, $message_type, $metadata_json)
    {
        $mt = strtolower(trim((string) $message_type));
        $enforce = ($mt === 'review');
        if (!$enforce && $metadata_json !== null && $metadata_json !== '') {
            $dec = json_decode($metadata_json, true);
            if (is_array($dec) && isset($dec['artifact_kind']) && strtolower(trim((string) $dec['artifact_kind'])) === 'review') {
                $enforce = true;
            }
        }
        if (!$enforce) {
            return null;
        }
        $t = self::substantiveBodyAfterOptionalYaml((string) $message_text);
        if (strlen($t) < 500) {
            return 'Thread review posts require substantive body (after YAML frontmatter if present) at least 500 characters.';
        }
        $n = preg_match_all('/^##\s+/m', $t);
        if ($n < 3) {
            return 'Thread review posts require at least 3 markdown headings at ## level in the body.';
        }
        return null;
    }

    /**
     * Thread posts with message_type or meta.artifact_kind help_response (and similar) need real content.
     * Per LILITH help_response contract: # title, 3+ ## sections, 200+ chars in body.
     *
     * @param string      $message_text   Raw post body (markdown, may include YAML)
     * @param string      $message_type   e.g. help_response, response
     * @param string|null $metadata_json  JSON string from API meta
     * @return string|null error message, or null if OK / not a help_response-class post
     */
    public static function validateThreadHelpResponseBody($message_text, $message_type, $metadata_json)
    {
        $mt = strtolower(trim((string) $message_type));
        $enforce = ($mt === 'help_response');
        if (!$enforce && $metadata_json !== null && $metadata_json !== '') {
            $dec = json_decode($metadata_json, true);
            if (is_array($dec) && isset($dec['artifact_kind']) && strtolower(trim((string) $dec['artifact_kind'])) === 'help_response') {
                $enforce = true;
            }
        }
        if (!$enforce) {
            return null;
        }
        $t = self::substantiveBodyAfterOptionalYaml((string) $message_text);
        $plain = trim(strip_tags($t));
        if ($plain === '' || strlen($t) < 200) {
            return 'help_response thread posts require substantive body at least 200 characters after frontmatter.';
        }
        if (substr_count($t, '#') < 3) {
            return 'help_response posts require markdown structure (at least 3 # characters, e.g. title + sections).';
        }
        if (preg_match('/^#\s+[^\r\n]+/m', $t) !== 1) {
            return 'help_response posts require at least one top-level # heading in the body.';
        }
        $n = preg_match_all('/^##\s+/m', $t);
        if ($n < 3) {
            return 'help_response posts require at least 3 markdown headings at ## level.';
        }
        return null;
    }

    /**
     * Combined thread body gate: review (stricter) then help_response.
     *
     * @param string      $message_text
     * @param string      $message_type
     * @param string|null $metadata_json
     * @return string|null
     */
    public static function validateThreadPostBody($message_text, $message_type, $metadata_json)
    {
        $e = self::validateThreadReviewBody($message_text, $message_type, $metadata_json);
        if ($e !== null) {
            return $e;
        }
        return self::validateThreadHelpResponseBody($message_text, $message_type, $metadata_json);
    }

    /**
     * Markdown body after optional LUPOPEDIA YAML frontmatter (first --- ... ---).
     *
     * @param string $message_text
     * @return string
     */
    public static function substantiveBodyAfterOptionalYaml($message_text)
    {
        $t = trim((string) $message_text);
        if (strlen($t) < 4 || substr($t, 0, 3) !== '---') {
            return $t;
        }
        $second = strpos($t, "\n---", 3);
        if ($second === false) {
            return $t;
        }
        return trim(substr($t, $second + 4));
    }
}
