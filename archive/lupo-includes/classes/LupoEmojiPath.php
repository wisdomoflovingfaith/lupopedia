<?php

/**
 * Safe path resolution for PRD 33 §7.8 filesystem emoji tokens (::img|foldername|imagefile::).
 *
 * Whitelist segments, require file under lupo-emoji/, realpath containment (blocks traversal/symlink escape).
 * Does not use stream wrappers or user-controlled base paths.
 *
 * @package Lupopedia
 */
class LupoEmojiPath
{

    /**
     * Lowercase extensions allowed for inline chat images (no leading dot).
     *
     * @var array
     */
    private static $allowedExtensions = array('gif', 'png', 'jpg', 'jpeg', 'webp');

    /**
     * Resolve an existing file under LUPOPEDIA_PATH/lupo-emoji/{folder}/{file}.
     *
     * @param string $folder Subdirectory name (no path separators).
     * @param string $file   Filename with exactly one dot before extension.
     * @return string|null Absolute filesystem path, or null if invalid or missing.
     */
    public static function resolveExistingFilePath($folder, $file)
    {
        if (!is_string($folder) || !is_string($file)) {
            return null;
        }
        if (!self::isValidFolderSegment($folder) || !self::isValidFileSegment($file)) {
            return null;
        }
        if (!defined('LUPOPEDIA_PATH') || LUPOPEDIA_PATH === '') {
            return null;
        }
        $baseDir = rtrim(str_replace('\\', DIRECTORY_SEPARATOR, LUPOPEDIA_PATH), DIRECTORY_SEPARATOR)
            . DIRECTORY_SEPARATOR . 'lupo-emoji';
        $baseReal = @realpath($baseDir);
        if ($baseReal === false || !is_dir($baseReal)) {
            return null;
        }
        $fullPath = $baseReal . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $file;
        $resolved = @realpath($fullPath);
        if ($resolved === false || !is_file($resolved)) {
            return null;
        }
        if (!self::pathIsStrictlyUnderBase($baseReal, $resolved)) {
            return null;
        }
        $ext = self::getLowerExtension($file);
        if ($ext === null || !in_array($ext, self::$allowedExtensions, true)) {
            return null;
        }
        return $resolved;
    }

    /**
     * Validate every ::img|folder|file:: token in message body (write-time / API guard per PRD 33).
     * Rejects malformed wrappers or any token that does not resolve to an allowed file.
     *
     * @param string $body Message text.
     * @return string|null Null if OK or no tokens; human-readable error if invalid.
     */
    public static function validateAllImgTokensInBody($body)
    {
        if (!is_string($body) || $body === '') {
            return null;
        }
        if (strpos($body, '::img|') === false) {
            return null;
        }
        $offset = 0;
        $len = strlen($body);
        while ($offset < $len) {
            $start = strpos($body, '::img|', $offset);
            if ($start === false) {
                break;
            }
            $afterPrefix = $start + 5;
            $close = strpos($body, '::', $afterPrefix);
            if ($close === false) {
                return 'Malformed ::img token (unterminated).';
            }
            $inner = substr($body, $afterPrefix, $close - $afterPrefix);
            if (strpos($inner, "\r") !== false || strpos($inner, "\n") !== false || strpos($inner, "\0") !== false) {
                return 'Invalid ::img token (illegal characters).';
            }
            $parts = explode('|', $inner);
            if (count($parts) !== 2) {
                return 'Malformed ::img token (expected folder|filename).';
            }
            $folder = $parts[0];
            $file = $parts[1];
            if ($folder === '' || $file === '') {
                return 'Invalid ::img token (empty segment).';
            }
            if (self::resolveExistingFilePath($folder, $file) === null) {
                return 'Invalid ::img token (not allowed or file missing).';
            }
            $offset = $close + 2;
        }
        return null;
    }

    /**
     * Escape plain text with nl2br, but replace valid ::img|folder|file:: tokens with safe <img> tags.
     * Re-resolve paths on read so tampered DB rows cannot point outside lupo-emoji/.
     *
     * @param string $text Raw message body.
     * @return string HTML fragment (must still be embedded in a safe document context).
     */
    public static function replaceImgTokensInHtmlContext($text)
    {
        if (!is_string($text) || $text === '') {
            return '';
        }
        if (strpos($text, '::img|') === false) {
            return nl2br(htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));
        }
        $out = '';
        $offset = 0;
        $len = strlen($text);
        while ($offset < $len) {
            $start = strpos($text, '::img|', $offset);
            if ($start === false) {
                $chunk = substr($text, $offset);
                if ($chunk !== '') {
                    $out .= nl2br(htmlspecialchars($chunk, ENT_QUOTES, 'UTF-8'));
                }
                break;
            }
            if ($start > $offset) {
                $out .= nl2br(htmlspecialchars(substr($text, $offset, $start - $offset), ENT_QUOTES, 'UTF-8'));
            }
            $afterPrefix = $start + 5;
            $close = strpos($text, '::', $afterPrefix);
            if ($close === false) {
                $out .= nl2br(htmlspecialchars(substr($text, $start), ENT_QUOTES, 'UTF-8'));
                break;
            }
            $inner = substr($text, $afterPrefix, $close - $afterPrefix);
            $parts = explode('|', $inner);
            $tokenLen = $close + 2 - $start;
            $tokenStr = substr($text, $start, $tokenLen);
            if (count($parts) === 2
                && strpos($inner, "\r") === false
                && strpos($inner, "\n") === false
                && strpos($inner, "\0") === false) {
                $folder = $parts[0];
                $file = $parts[1];
                $abs = self::resolveExistingFilePath($folder, $file);
                if ($abs !== null && defined('LUPOPEDIA_PUBLIC_PATH')) {
                    $pub = rtrim((string) LUPOPEDIA_PUBLIC_PATH, '/');
                    $url = ($pub === '' ? '' : $pub) . '/lupo-emoji/' . $folder . '/' . $file;
                    $safeUrl = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
                    $alt = htmlspecialchars($file, ENT_QUOTES, 'UTF-8');
                    $out .= '<img src="' . $safeUrl . '" alt="' . $alt . '" class="lupo-emoji-inline" />';
                } else {
                    $out .= nl2br(htmlspecialchars($tokenStr, ENT_QUOTES, 'UTF-8'));
                }
            } else {
                $out .= nl2br(htmlspecialchars($tokenStr, ENT_QUOTES, 'UTF-8'));
            }
            $offset = $close + 2;
        }
        return $out;
    }

    /**
     * @param string $folder
     * @return bool
     */
    public static function isValidFolderSegment($folder)
    {
        if (!is_string($folder) || $folder === '') {
            return false;
        }
        if (strpos($folder, "\r") !== false || strpos($folder, "\n") !== false || strpos($folder, "\0") !== false) {
            return false;
        }
        return (bool) preg_match('/^[a-zA-Z0-9_-]+$/', $folder);
    }

    /**
     * One dot only; basename letters, digits, underscore, hyphen; allowed image extension.
     *
     * @param string $file
     * @return bool
     */
    public static function isValidFileSegment($file)
    {
        if (!is_string($file) || $file === '') {
            return false;
        }
        if (strpos($file, "\r") !== false || strpos($file, "\n") !== false || strpos($file, "\0") !== false) {
            return false;
        }
        if (substr_count($file, '.') !== 1) {
            return false;
        }
        if (!preg_match('/^([a-zA-Z0-9_-]+)\.([a-zA-Z0-9]+)$/', $file, $m)) {
            return false;
        }
        $ext = strtolower($m[2]);
        return in_array($ext, self::$allowedExtensions, true);
    }

    /**
     * @param string $baseReal realpath of lupo-emoji directory
     * @param string $resolvedPath realpath of candidate file
     * @return bool
     */
    private static function pathIsStrictlyUnderBase($baseReal, $resolvedPath)
    {
        $b = rtrim(str_replace('\\', '/', $baseReal), '/');
        $p = str_replace('\\', '/', $resolvedPath);
        if (function_exists('mb_strtolower')) {
            $b = mb_strtolower($b, '8bit');
            $p = mb_strtolower($p, '8bit');
        } else {
            $b = strtolower($b);
            $p = strtolower($p);
        }
        $blen = strlen($b);
        $plen = strlen($p);
        if ($plen <= $blen) {
            return false;
        }
        if (strpos($p, $b) !== 0) {
            return false;
        }
        $next = $p[$blen];
        return $next === '/';
    }

    /**
     * @param string $file
     * @return string|null extension without dot, lowercase
     */
    private static function getLowerExtension($file)
    {
        $pos = strrpos($file, '.');
        if ($pos === false) {
            return null;
        }
        return strtolower(substr($file, $pos + 1));
    }
}
