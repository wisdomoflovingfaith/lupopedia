<?php
/**
 * wolfie.headers: explicit architecture with structured clarity for every file.
 * file.last_modified_system_version: 2026.3.7.6
 * file.channel: schema
 *
 * Lupopedia Migration Controller
 *
 * First-line controller for governance and schema changes. All AI-generated
 * migrations MUST be validated and executed only through this controller.
 *
 * Enforces:
 * - Immutable governance events (append-only for lupo_gov_*)
 * - NO-ADS: no ad-tech, tracking, or analytics schema
 * - Table ceiling: 222 tables max (target: 222)
 * - No foreign keys, no triggers
 *
 * @package App\Services\System
 * @see LIMITS.md
 * @see docs/doctrine/TABLE_COUNT_DOCTRINE.md
 */

namespace App\Services\System;

use PDO;
use PDOException;

class ValidationResult
{
    public bool $valid;
    /** @var string[] */
    public array $errors;

    public function __construct(bool $valid, array $errors = [])
    {
        $this->valid = $valid;
        $this->errors = $errors;
    }

    public function getMessage(): string
    {
        return implode('; ', $this->errors);
    }
}

class LupopediaMigrationController
{
    protected const MAX_TABLES = 222;
    protected const TARGET_TABLES = 222;
    protected const SQL_SNIPPET_LENGTH = 2000;
    protected const GOVERNANCE_PREFIX = 'lupo_gov_';

    /** NO-ADS: word-boundary patterns in identifiers/comments */
    protected const NO_ADS_WORDS = [
        'ad', 'ads', 'advert', 'campaign', 'pixel', 'impression', 'click',
        'crm', 'retarget', 'tracking'
    ];

    /** NO-ADS: ad/analytics provider phrases */
    protected const NO_ADS_PHRASES = [
        'google-analytics', 'doubleclick', 'facebook', 'tagmanager', 'pixel'
    ];

    /**
     * Validate migration SQL against governance, NO-ADS, FK, and trigger rules.
     * Does not check table ceiling (requires DB); executeMigration does that.
     */
    public function validateMigrationSql(string $sql): ValidationResult
    {
        $errors = [];

        // 1) NO-ADS
        if (!$this->enforceNoAds($sql)) {
            $errors[] = 'NO-ADS: forbidden ad/analytics/tracking pattern detected in table names, column names, or comments.';
        }

        // 2) Governance immutability: block UPDATE, DELETE, TRUNCATE, DROP, ALTER, REPLACE, ON DUPLICATE KEY UPDATE on lupo_gov_*
        $govTables = $this->extractGovernanceTablesFromMutatingStatements($sql);
        if (!empty($govTables)) {
            $errors[] = 'Governance tables are immutable and append-only. Mutating or altering lupo_gov_* is prohibited. Affected: ' . implode(', ', $govTables);
        }

        if ($this->hasGovernanceTableWithOnDuplicateKeyUpdate($sql)) {
            $errors[] = 'Governance tables are immutable and append-only. ON DUPLICATE KEY UPDATE on lupo_gov_* is prohibited.';
        }

        // 3) Foreign keys
        if (preg_match('/\bFOREIGN\s+KEY\b/i', $sql) || preg_match('/\bREFERENCES\s+[`"\w]/i', $sql)) {
            $errors[] = 'Foreign keys are prohibited.';
        }

        // 4) Triggers
        if (preg_match('/\bCREATE\s+TRIGGER\b/i', $sql) || preg_match('/\bDROP\s+TRIGGER\b/i', $sql)) {
            $errors[] = 'Triggers are prohibited.';
        }

        return new ValidationResult(empty($errors), $errors);
    }

    /**
     * Return false if SQL contains NO-ADS forbidden patterns (block).
     */
    public function enforceNoAds(string $sql): bool
    {
        $s = ' ' . $sql . ' ';

        foreach (self::NO_ADS_WORDS as $w) {
            if (preg_match('/\b' . preg_quote($w, '/') . '\b/i', $s)) {
                return false;
            }
        }

        foreach (self::NO_ADS_PHRASES as $p) {
            if (stripos($s, $p) !== false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Return true if current table count is under the ceiling (< 222), false if >= 222.
     */
    public function checkTableCeiling(PDO $pdo): bool
    {
        $stmt = $pdo->query(
            "SELECT COUNT(*) AS c FROM information_schema.tables " .
            "WHERE table_schema = DATABASE() AND table_type = 'BASE TABLE'"
        );
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = (int)($row['c'] ?? 0);
        return $count < self::MAX_TABLES;
    }

    public function isGovernanceTable(string $tableName): bool
    {
        $t = strtolower(trim($tableName, "`\"'"));
        return strpos($t, self::GOVERNANCE_PREFIX) === 0;
    }

    /**
     * Validate, enforce ceiling, run in a transaction, and log.
     *
     * @throws \RuntimeException on validation or ceiling failure
     * @throws PDOException on execution failure
     */
    public function executeMigration(PDO $pdo, string $sql): void
    {
        $snippet = mb_substr($sql, 0, self::SQL_SNIPPET_LENGTH);
        $ymdhis = (int)gmdate('YmdHis');

        $result = $this->validateMigrationSql($sql);
        if (!$result->valid) {
            $this->logMigration($pdo, $ymdhis, $snippet, 'blocked', $result->getMessage());
            throw new \RuntimeException('Migration validation failed: ' . $result->getMessage());
        }

        $current = $this->getTableCount($pdo);
        $createCount = $this->countCreateTableStatements($sql);
        if ($createCount > 0 && ($current >= self::MAX_TABLES || $current + $createCount > self::MAX_TABLES)) {
            $msg = "Table ceiling exceeded: current={$current}, would add {$createCount}, max=" . self::MAX_TABLES;
            $this->logMigration($pdo, $ymdhis, $snippet, 'blocked', $msg);
            throw new \RuntimeException($msg);
        }

        try {
            $pdo->beginTransaction();
            $this->runStatements($pdo, $sql);
            $pdo->commit();
            $this->logMigration($pdo, $ymdhis, $snippet, 'success', null);
        } catch (\Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $this->logMigration($pdo, $ymdhis, $snippet, 'blocked', $e->getMessage());
            throw $e;
        }
    }

    /**
     * Extract table names from UPDATE/DELETE/ALTER/TRUNCATE/DROP/REPLACE that target governance tables.
     *
     * @return string[]
     */
    protected function extractGovernanceTablesFromMutatingStatements(string $sql): array
    {
        $found = [];
        $patterns = [
            '/\bUPDATE\s+(?:IGNORE\s+)?[`"]?(\w+)[`"]?/i',
            '/\bDELETE\s+FROM\s+(?:IGNORE\s+)?[`"]?(\w+)[`"]?/i',
            '/\bALTER\s+TABLE\s+(?:IGNORE\s+)?[`"]?(\w+)[`"]?/i',
            '/\bTRUNCATE\s+(?:TABLE\s+)?[`"]?(\w+)[`"]?/i',
            '/\bDROP\s+TABLE\s+(?:IF\s+EXISTS\s+)?[`"]?(\w+)[`"]?/i',
            '/\bREPLACE\s+INTO\s+[`"]?(\w+)[`"]?/i',
        ];

        foreach ($patterns as $re) {
            if (preg_match_all($re, $sql, $m)) {
                foreach ($m[1] as $t) {
                    if ($this->isGovernanceTable($t)) {
                        $found[$t] = true;
                    }
                }
            }
        }

        return array_keys($found);
    }

    protected function hasGovernanceTableWithOnDuplicateKeyUpdate(string $sql): bool
    {
        if (!preg_match('/\bON\s+DUPLICATE\s+KEY\s+UPDATE\b/i', $sql)) {
            return false;
        }
        if (preg_match('/\bINSERT\s+INTO\s+[`"]?(\w+)[`"]?/i', $sql, $m) && $this->isGovernanceTable($m[1])) {
            return true;
        }
        return false;
    }

    protected function getTableCount(PDO $pdo): int
    {
        $stmt = $pdo->query(
            "SELECT COUNT(*) AS c FROM information_schema.tables " .
            "WHERE table_schema = DATABASE() AND table_type = 'BASE TABLE'"
        );
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['c'] ?? 0);
    }

    protected function countCreateTableStatements(string $sql): int
    {
        return preg_match_all('/\bCREATE\s+TABLE\s+(?:IF\s+NOT\s+EXISTS\s+)?/i', $sql);
    }

    /**
     * Run one or more statements. Splits on semicolon followed by newline; does not handle ; inside string literals.
     */
    protected function runStatements(PDO $pdo, string $sql): void
    {
        $parts = preg_split('/;\s*[\r\n]+/', trim($sql), -1, PREG_SPLIT_NO_EMPTY);
        foreach ($parts as $s) {
            $s = trim($s);
            if ($s === '') {
                continue;
            }
            if (substr($s, -1) !== ';') {
                $s .= ';';
            }
            $pdo->exec($s);
        }
    }

    protected function logMigration(PDO $pdo, int $ymdhis, string $sqlSnippet, string $status, ?string $reason): void
    {
        $stmt = $pdo->prepare(
            "INSERT INTO lupo_migration_log (executed_ymdhis, sql_snippet, status, reason) VALUES (?, ?, ?, ?)"
        );
        try {
            $stmt->execute([$ymdhis, $sqlSnippet, $status, $reason]);
        } catch (PDOException $e) {
            @trigger_error('LupopediaMigrationController: could not write to lupo_migration_log: ' . $e->getMessage(), E_USER_WARNING);
        }
    }
}
