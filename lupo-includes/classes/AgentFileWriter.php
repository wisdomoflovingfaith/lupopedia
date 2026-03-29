<?php
/**
 * AgentFileWriter — guard for filesystem writes from PHP / LLM agent pipelines (4.0.89+).
 *
 * Policy: see lupo-docs/versions/4.0.89/TODO.md H9 and lupo-docs/ORGANIZATION.md §2.2.
 * Applies to PHP-agent (and similarly guarded) writes only — IDE agents (Cursor, Windsurf, Kiro) have
 * full repo access and do not use this class for normal development edits.
 *
 * - CONTEXT_AGENT: allowed directories + extensions + content pattern scan (use for automated agents).
 * - CONTEXT_OPERATOR: same directory + extension checks; skips content pattern scan (human/CI import --write-back).
 *
 * All new PHP-side writes that should respect policy should use this class; do not bypass with raw file_put_contents
 * in agent-facing code paths.
 *
 * PHP 5.6+ compatible.
 *
 * @package Lupopedia
 */

class LupoAgentFileWriterException extends Exception
{
}

class AgentFileWriter
{
    /** @var string Agent/automated writes: full policy including content scan. */
    const CONTEXT_AGENT = 'agent';

    /** @var string Human or trusted CI: path + extension only (docs may mention <script> in examples). */
    const CONTEXT_OPERATOR = 'operator';

    /** @var array Allowed path prefixes relative to repository root (forward slashes). */
    private static $allowedDirPrefixes = array(
        'lupo-rules/',
        'lupo-docs/',
        'lupo-channels/',
        'lupo-content/',
    );

    /** @var array Lowercase extensions allowed for writes. */
    private static $allowedExtensions = array(
        'md',
        'txt',
        'yaml',
        'yml',
        'json',
        'csv',
        'xml',
    );

    /** @var array PCRE patterns; applied only for CONTEXT_AGENT. */
    private static $forbiddenContentPatterns = array(
        '/<\?php/i',
        '/<script[^>]*>/i',
        '/javascript:/i',
        '/on\w+\s*=/i',
        '/<iframe/i',
        '/<object/i',
        '/<embed/i',
        '/<link[^>]*rel\s*=\s*["\']stylesheet["\'][^>]*>/i',
    );

    /**
     * Resolve repository root (trailing slash stripped, forward slashes internally for comparisons).
     *
     * @param string|null $repoRoot
     * @return string
     * @throws LupoAgentFileWriterException
     */
    public static function resolveRepoRoot($repoRoot)
    {
        if ($repoRoot !== null && $repoRoot !== '') {
            $real = realpath($repoRoot);
            if ($real === false || !is_dir($real)) {
                throw new LupoAgentFileWriterException('Invalid repository root: ' . $repoRoot);
            }
            return rtrim(str_replace('\\', '/', $real), '/');
        }
        if (defined('LUPOPEDIA_PATH') && LUPOPEDIA_PATH !== '') {
            $real = realpath(LUPOPEDIA_PATH);
            if ($real === false || !is_dir($real)) {
                throw new LupoAgentFileWriterException('LUPOPEDIA_PATH is not a valid directory.');
            }
            return rtrim(str_replace('\\', '/', $real), '/');
        }
        $fallback = dirname(dirname(__DIR__));
        $real = realpath($fallback);
        if ($real === false || !is_dir($real)) {
            throw new LupoAgentFileWriterException('Could not resolve repository root; pass $repoRoot or define LUPOPEDIA_PATH.');
        }
        return rtrim(str_replace('\\', '/', $real), '/');
    }

    /**
     * Resolve to an absolute filesystem path whose parent directory is inside the repo (realpath-checked).
     *
     * @param string $path Absolute or repo-relative path
     * @param string $repoRootNorm From resolveRepoRoot() (forward slashes OK)
     * @return string Absolute path with DIRECTORY_SEPARATOR for disk ops
     * @throws LupoAgentFileWriterException
     */
    public static function resolveTargetPath($path, $repoRootNorm)
    {
        $rootReal = realpath(str_replace('/', DIRECTORY_SEPARATOR, $repoRootNorm));
        if ($rootReal === false) {
            throw new LupoAgentFileWriterException('Bad repository root.');
        }
        $path = str_replace('\\', '/', $path);
        if ($path === '') {
            throw new LupoAgentFileWriterException('Empty path.');
        }
        $isWinAbs = (strlen($path) >= 2 && ctype_alpha($path[0]) && $path[1] === ':');
        $isUnixAbs = (strlen($path) >= 1 && $path[0] === '/');
        if (!$isWinAbs && !$isUnixAbs) {
            $combined = $rootReal . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, ltrim($path, '/'));
        } else {
            $combined = str_replace('/', DIRECTORY_SEPARATOR, $path);
        }
        $dir = dirname($combined);
        $dirReal = realpath($dir);
        if ($dirReal === false) {
            throw new LupoAgentFileWriterException('Target directory does not exist or is not reachable: ' . $dir);
        }
        $rootFs = strtolower(str_replace('\\', '/', $rootReal));
        $dirFs = strtolower(str_replace('\\', '/', $dirReal));
        if ($dirFs !== $rootFs && strpos($dirFs, $rootFs . '/') !== 0) {
            throw new LupoAgentFileWriterException('Path outside repository root.');
        }
        $basename = basename($combined);
        if ($basename === '' || $basename === '.' || $basename === '..') {
            throw new LupoAgentFileWriterException('Invalid file name.');
        }
        return $dirReal . DIRECTORY_SEPARATOR . $basename;
    }

    /**
     * Repo-relative path with forward slashes; empty if not under root.
     *
     * @param string $absoluteNorm
     * @param string $repoRootNorm
     * @return string
     */
    public static function pathRelativeToRepo($absoluteNorm, $repoRootNorm)
    {
        $a = rtrim(str_replace('\\', '/', $absoluteNorm), '/');
        $r = rtrim($repoRootNorm, '/');
        $prefix = $r . '/';
        if ($a === $r) {
            return '';
        }
        if (strpos($a, $prefix) !== 0) {
            return '';
        }
        return substr($a, strlen($prefix));
    }

    /**
     * @param string $rel Forward-slash repo-relative path
     * @return bool
     */
    public static function isAllowedDirectoryPrefix($rel)
    {
        $rel = ltrim(str_replace('\\', '/', $rel), '/');
        foreach (self::$allowedDirPrefixes as $prefix) {
            if ($rel === rtrim($prefix, '/') || strpos($rel, $prefix) === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $ext Lowercase, no dot
     * @return bool
     */
    public static function isAllowedExtension($ext)
    {
        return in_array(strtolower($ext), self::$allowedExtensions, true);
    }

    /**
     * @param string $content
     * @param string $context self::CONTEXT_AGENT or self::CONTEXT_OPERATOR
     * @throws LupoAgentFileWriterException
     */
    public static function assertContentPatterns($content, $context)
    {
        if ($content === null) {
            $content = '';
        }
        if ($context !== self::CONTEXT_AGENT) {
            return;
        }
        foreach (self::$forbiddenContentPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                throw new LupoAgentFileWriterException('File content matches a forbidden pattern (agent context).');
            }
        }
    }

    /**
     * Validate path (directory + extension under repo). Does not write.
     *
     * @param string $path
     * @param string|null $repoRoot
     * @throws LupoAgentFileWriterException
     */
    public static function assertPathAllowed($path, $repoRoot = null)
    {
        $root = self::resolveRepoRoot($repoRoot);
        $abs = self::resolveTargetPath($path, $root);
        $rootReal = realpath(str_replace('/', DIRECTORY_SEPARATOR, $root));
        if ($rootReal === false) {
            throw new LupoAgentFileWriterException('Bad repository root.');
        }
        $absNorm = str_replace('\\', '/', $abs);
        $rootNorm = str_replace('\\', '/', $rootReal);
        $rel = self::pathRelativeToRepo($absNorm, $rootNorm);
        if ($rel === '') {
            throw new LupoAgentFileWriterException('Writes to repository root are not allowed for PHP agent policy.');
        }
        if (!self::isAllowedDirectoryPrefix($rel)) {
            throw new LupoAgentFileWriterException('Path is not under an allowed directory prefix (lupo-rules, lupo-docs, lupo-channels, lupo-content): ' . $rel);
        }
        $ext = pathinfo($rel, PATHINFO_EXTENSION);
        if ($ext === '' || $ext === null) {
            throw new LupoAgentFileWriterException('File has no extension; only documented safe extensions are allowed.');
        }
        if (!self::isAllowedExtension($ext)) {
            throw new LupoAgentFileWriterException('Forbidden file extension: .' . $ext);
        }
    }

    /**
     * Write file with policy enforcement.
     *
     * @param string $path
     * @param string $content
     * @param string|null $repoRoot
     * @param string $context self::CONTEXT_AGENT or self::CONTEXT_OPERATOR
     * @param int|string|null $actorId Optional audit field
     * @return int|false Bytes written from file_put_contents
     * @throws LupoAgentFileWriterException
     */
    public static function writeFile($path, $content, $repoRoot = null, $context = null, $actorId = null)
    {
        if ($context === null) {
            $context = self::CONTEXT_AGENT;
        }
        self::assertPathAllowed($path, $repoRoot);
        self::assertContentPatterns($content, $context);
        $root = self::resolveRepoRoot($repoRoot);
        $abs = self::resolveTargetPath($path, $root);
        $dir = dirname($abs);
        if (!is_dir($dir)) {
            throw new LupoAgentFileWriterException('Target directory does not exist: ' . $dir);
        }
        $rootReal = realpath(str_replace('/', DIRECTORY_SEPARATOR, $root));
        $absNorm = str_replace('\\', '/', $abs);
        $rootNorm = $rootReal !== false ? str_replace('\\', '/', $rootReal) : $root;
        $rel = self::pathRelativeToRepo($absNorm, $rootNorm);
        $written = @file_put_contents($abs, $content);
        if ($written === false) {
            throw new LupoAgentFileWriterException('file_put_contents failed: ' . $abs);
        }
        self::auditLog($rel, (int) $written, $context, $actorId);
        return $written;
    }

    /**
     * @param string $relPath Repo-relative forward-slash path
     * @param int $byteLen
     * @param string $context
     * @param int|string|null $actorId
     */
    private static function auditLog($relPath, $byteLen, $context, $actorId)
    {
        $base = null;
        if (defined('LUPOPEDIA_PATH') && LUPOPEDIA_PATH !== '') {
            $base = LUPOPEDIA_PATH;
        } else {
            $base = dirname(dirname(__DIR__));
        }
        $logDir = rtrim($base, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'lupo-logs';
        if (!is_dir($logDir) || !is_writable($logDir)) {
            return;
        }
        $line = gmdate('Y-m-d H:i:s') . "\t" . $context . "\t" . (string) $actorId . "\t" . $byteLen . "\t" . str_replace("\t", ' ', $relPath) . "\n";
        @file_put_contents($logDir . DIRECTORY_SEPARATOR . 'php_agent_filesystem_writes.log', $line, FILE_APPEND | LOCK_EX);
    }
}
