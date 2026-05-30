<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.1.3"
#   file_path_from_root: "lupo-includes/classes/Utf8StructuredWrite.php"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-includes/classes/Utf8StructuredWrite.php"
#   status: "active"
#   when_updated: "20260418200841"
#   trust_tier: "canonical"
#   questions_toon: null
#   memory_toon: "lupo-memory/development/canonical/1026/04/utf8structuredwrite.toon"
#   atoms_toon: null
#   transcript_jsonl: "0/development/utf8structuredwrite"
#   artifact_type: implementation
#   artifact_kind: tool
#   channel_key: "development"
#   federation_node_id: 0
#   thread_id: ""
#   content_id: null
#   content_parent_id: 16
#   content_slug: "utf8structuredwrite"
#   default_collection_id: null
#   lupopedia.schema: implementation
#   title: "Utf8StructuredWrite structured UTF-8 write guard"
#   summary: "Forward-only UTF-8 guard for JSON, JSONL, and TOON-shaped text before filesystem write; known mojibake repair; invalid UTF-8 blocks write."
# ---------------------------------------------------------------------
/**
 * Forward-only UTF-8 guard for structured artifacts (JSON, JSONL, TOON-shaped text).
 *
 * Doctrine: normalize known UTF-8/cp1252 mojibake sequences before write; verify valid UTF-8;
 * block write and return failure if still invalid. Does not scan the repo or rewrite existing files.
 *
 * Policy: this class enforces UTF-8 byte safety and a tiny explicit mojibake repair table only.
 * It does not parse or validate JSON, JSONL, TOON, or other semantic structure; callers must
 * validate meaning (schema, line shape, channel rules) separately.
 *
 * Manual fix for isolated hand-edited files: copy through Windows Notepad (UTF-8) per operator runbook.
 */
final class Utf8StructuredWrite
{
    /**
     * Known bad byte runs (double-UTF-8 or common cp1252-misread artifacts) replaced in order.
     *
     * @return array<string,string>
     */
    private static function mojibakeReplacementPairs()
    {
        return array(
            // Broken: UTF-8 mojibake from smart double-quote (U+201D style misread). Target: ASCII " (U+0022).
            "\xC3\xA2\xE2\x82\xAC\xC2\x9D" => '"',
            // Broken: UTF-8 mojibake run often seen where ASCII hyphen-minus was intended. Target: '-'.
            "\xC3\xA2\xE2\x82\xAC\xE2\x80\x98" => '-',
            // Broken: UTF-8 mojibake from ellipsis (U+2026 style misread). Target: drop (empty string).
            "\xC3\xA2\xC5\x93\xE2\x80\xA6" => '',
        );
    }

    /**
     * Replace known mojibake substrings (UTF-8 byte runs).
     *
     * @param string $text
     * @return string
     */
    public static function normalizeKnownMojibake($text)
    {
        if (!is_string($text) || $text === '') {
            return is_string($text) ? $text : '';
        }
        $pairs = self::mojibakeReplacementPairs();
        return str_replace(array_keys($pairs), array_values($pairs), $text);
    }

    /**
     * True if PHP considers the string valid UTF-8.
     *
     * @param string $text
     * @return bool
     */
    public static function isValidUtf8($text)
    {
        if (!is_string($text)) {
            return false;
        }
        if ($text === '') {
            return true;
        }
        if (function_exists('mb_check_encoding')) {
            return (bool) mb_check_encoding($text, 'UTF-8');
        }
        $r = @preg_match('//u', $text);
        return $r === 1;
    }

    /**
     * Normalize mojibake, then strip invalid UTF-8 bytes if iconv is available.
     *
     * @param string $text
     * @return string
     */
    public static function coerceToUtf8($text)
    {
        if (!is_string($text)) {
            return '';
        }
        $text = self::normalizeKnownMojibake($text);
        if (!self::isValidUtf8($text) && function_exists('iconv')) {
            $fixed = @iconv('UTF-8', 'UTF-8//IGNORE', $text);
            if (is_string($fixed)) {
                $text = $fixed;
            }
        }
        return $text;
    }

    /**
     * Prepare one structured text blob (e.g. a JSONL line) for filesystem write.
     *
     * @param string $text Already-encoded JSON/JSONL line (no trailing newline required).
     * @param string $file_path Pass-through context for outer layers (e.g. target path for logging on failure).
     *        Intentionally unused inside this method; kept so callers like HermesService can pass a path
     *        without this class opening or touching the filesystem.
     * @return array { ok: bool, text: string, reason: string, changed: bool }
     */
    public static function prepareForFilesystemWrite($text, $file_path = '')
    {
        if (!is_string($text)) {
            return array(
                'ok' => false,
                'text' => '',
                'reason' => 'not_string',
                'changed' => false,
            );
        }
        $before = $text;
        $after = self::coerceToUtf8($text);
        $changed = ($after !== $before);
        if (!self::isValidUtf8($after)) {
            return array(
                'ok' => false,
                'text' => '',
                'reason' => 'invalid_utf8_after_coerce',
                'changed' => $changed,
            );
        }
        return array(
            'ok' => true,
            'text' => $after,
            'reason' => '',
            'changed' => $changed,
        );
    }
}
