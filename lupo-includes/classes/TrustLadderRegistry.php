<?php
/**
 * TrustLadderRegistry — Runtime access layer for lupo_trust_ladder_registry.
 *
 * Single source of truth for table-level trust-ladder metadata at runtime:
 *   - Entity archetype (parent, child, system)
 *   - Participation mode (full, generator_staging, seed_only, not_ladder)
 *   - Whether a table requires seed rows
 *   - Reference target (seed vs canonical)
 *   - Canonical lineage edge type and promotion target
 *
 * Fail-closed behavior (non-negotiable):
 *   - LUPOPEDIA_ENV=development: missing entries emit E_USER_WARNING + permissive fallback.
 *   - LUPOPEDIA_ENV=production (default): missing or deactivated entries throw TrustLadderException.
 *
 * Bootstrap safety:
 *   - getSelfMetadata() returns hard-coded metadata for lupo_trust_ladder_registry itself,
 *     allowing Phase 2 of the bootstrap sequence to complete before the table is queryable.
 *   - loadCacheIfNeeded() seeds the cache with the self-referential entry before any DB query.
 *
 * Doctrine source of truth: lupo-docs/doctrine/TRUST_LADDER_REGISTRY.md
 * Sync script: lupo-scripts/sync_trust_ladder_registry_to_db.py
 *
 * TODO(LEGACY-GAP): Cache invalidation after sync for long-running workers/daemons.
 *   Workers started before sync completes hold a stale cache until clearCache() is called
 *   or the process restarts. Mitigation: add a version counter to lupo_system_config and
 *   compare it on every N-th call to getTableMeta(). Not implemented — file as an issue.
 *
 * TODO(LEGACY-GAP): How should TrustLadderRegistry behave during unit tests (especially SQLite)?
 *   Current approach: test bootstrap calls injectTestCache() before any getTableMeta() call,
 *   bypassing the DB load. Alternatively, set LUPOPEDIA_ENV=development before tests run.
 *
 * TODO(LEGACY-GAP): Table prefix portability. The registry stores full table names
 *   (e.g. 'lupo_memory_nodes'). If the install prefix is not 'lupo_' (Softaculous custom),
 *   the registry rows must be updated accordingly. The sync script and this class both strip
 *   the configured LUPO_TABLE_PREFIX from lookup keys for resilience, but the stored names
 *   in the DB will still reflect the prefix at install time. This is a known gap.
 *
 * @package Lupopedia
 * @version 4.0.96
 */

if (!class_exists('TrustLadderException')) {
    /**
     * Thrown by TrustLadderRegistry when a table is unregistered, deactivated,
     * or a PK violates table-level archetype constraints in production mode.
     */
    class TrustLadderException extends RuntimeException {}
}

final class TrustLadderRegistry
{
    // ─────────────────────────────────────────────────────────────────────────
    // Constants
    // ─────────────────────────────────────────────────────────────────────────

    /** Valid archetype values. */
    const ARCHETYPES = array('parent', 'child', 'system');

    /** Valid participates values. */
    const PARTICIPATES = array('full', 'generator_staging', 'seed_only', 'not_ladder');

    /** Valid reference_target values. */
    const REFERENCE_TARGETS = array('seed', 'canonical');

    // ─────────────────────────────────────────────────────────────────────────
    // Internal state
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * In-process cache: table_name → metadata array.
     * Also indexed by bare table name (without prefix) for resilience.
     *
     * @var array<string, array>|null  null = not yet loaded
     */
    private static $cache = null;

    /**
     * Hard-coded metadata for the registry table itself.
     * Returned by getSelfMetadata() and used as the bootstrap seed entry
     * before the DB is queryable (Phase 1 → Phase 2 bootstrap transition).
     *
     * @var array<string, mixed>
     */
    private static $selfMeta = array(
        'registry_id'            => 1,
        'table_name'             => 'lupo_trust_ladder_registry',
        'archetype'              => 'system',
        'participates'           => 'seed_only',
        'seed_required'          => 1,
        'canonical_lineage_edge' => null,
        'promotion_target'       => null,
        'is_active'              => 1,
        'is_deleted'             => 0,
        'reference_target'       => 'seed',
        'canonical_id'           => null,
        'notes'                  => 'Bootstrap: registry table follows its own rules. Seed-only archetype.',
    );

    // ─────────────────────────────────────────────────────────────────────────
    // Cache management
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Clear the in-process cache. The next call to getTableMeta() triggers a fresh DB load.
     *
     * Call this after running sync_trust_ladder_registry_to_db.py in long-running contexts.
     *
     * TODO(LEGACY-GAP): Clearing the cache in one FPM worker does not affect other workers.
     *   A shared invalidation signal (e.g. APCu, shared memory, or a version column in
     *   lupo_system_config) is needed for full daemon/FPM invalidation.
     */
    public static function clearCache()
    {
        self::$cache = null;
    }

    /**
     * Inject a pre-built test cache. Unit tests only — never call in production.
     *
     * @param array<string, array> $cache Map of table_name → metadata row
     */
    public static function injectTestCache(array $cache)
    {
        self::$cache = $cache;
    }

    /**
     * Load the full registry from lupo_trust_ladder_registry into the static cache.
     *
     * Dev mode (LUPOPEDIA_ENV=development): DB failures emit E_USER_WARNING; cache remains
     *   at bootstrap-only state (permissive fallback for unregistered tables).
     * Prod mode (default): DB failure throws TrustLadderException (fail closed).
     *
     * TODO(LEGACY-GAP): Migration path for tables that exist but are not yet registered.
     *   In a first-deployment scenario, any table used before the sync script runs will cause
     *   production failures. Mitigation: run sync_trust_ladder_registry_to_db.py --dry-run
     *   first, resolve all drift, then deploy the PHP class.
     *
     * TODO(LEGACY-GAP): Should we add last_validated_ymdhis and alert if registry is stale
     *   beyond 24 hours? Not implemented. File as a follow-up issue if staleness is a risk.
     */
    private static function loadCacheIfNeeded()
    {
        if (self::$cache !== null) {
            return;
        }

        $isDev = self::isDevMode();

        // Bootstrap: seed cache with self-referential entry so registry-table lookups
        // never fail even before the DB is queryable.
        self::$cache = array(
            'lupo_trust_ladder_registry'    => self::$selfMeta,
            'trust_ladder_registry'         => self::$selfMeta,  // bare-name alias
        );

        // Attempt to load DatabaseFactory if config is available.
        if (!class_exists('DatabaseFactory', false) && defined('LUPOPEDIA_CONFIG_LOADED')) {
            $factoryFile = __DIR__ . '/DatabaseFactory.php';
            if (is_file($factoryFile)) {
                require_once $factoryFile;
            }
        }

        if (!class_exists('DatabaseFactory', false)) {
            // No DB available — acceptable during very early install bootstrap.
            // TODO(LEGACY-GAP): Should raise a warning in non-install contexts.
            return;
        }

        try {
            $db     = DatabaseFactory::getConnection();
            $prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
            $table  = $db->quoteIdentifier($prefix . 'trust_ladder_registry');

            $sql  = 'SELECT registry_id, table_name, archetype, participates,'
                  . '       seed_required, canonical_lineage_edge, promotion_target,'
                  . '       is_active, is_deleted, reference_target, canonical_id, notes'
                  . ' FROM ' . $table
                  . ' WHERE is_deleted = 0'
                  . ' ORDER BY registry_id ASC';

            $rows = $db->fetchAll($sql);
            if (!is_array($rows)) {
                $rows = array();
            }

            foreach ($rows as $row) {
                $fullName = (string) ($row['table_name'] ?? '');
                if ($fullName === '') {
                    continue;
                }
                $meta = self::normalizeRow($row);

                // Index by full name.
                self::$cache[$fullName] = $meta;

                // Also index by bare name (without prefix) for caller resilience.
                // Callers may pass 'memory_nodes' or 'lupo_memory_nodes' interchangeably.
                if (strpos($fullName, $prefix) === 0) {
                    $bare = substr($fullName, strlen($prefix));
                    if ($bare !== '' && !isset(self::$cache[$bare])) {
                        self::$cache[$bare] = $meta;
                    }
                }
            }
        } catch (TrustLadderException $e) {
            throw $e;
        } catch (Exception $e) {
            $msg = 'TrustLadderRegistry: failed to load from DB — ' . $e->getMessage()
                . '. Run lupo-scripts/sync_trust_ladder_registry_to_db.py to reconcile.';
            if ($isDev) {
                trigger_error($msg, E_USER_WARNING);
            } else {
                throw new TrustLadderException($msg, 0, $e);
            }
        }
    }

    /**
     * Normalize a raw DB row into a typed metadata array.
     *
     * @param array $row Raw DB row
     * @return array<string, mixed>
     */
    private static function normalizeRow(array $row)
    {
        return array(
            'registry_id'            => (int)    ($row['registry_id']            ?? 0),
            'table_name'             => (string) ($row['table_name']             ?? ''),
            'archetype'              => (string) ($row['archetype']              ?? ''),
            'participates'           => (string) ($row['participates']           ?? 'not_ladder'),
            'seed_required'          => (int)    ($row['seed_required']          ?? 0),
            'canonical_lineage_edge' => isset($row['canonical_lineage_edge']) && $row['canonical_lineage_edge'] !== null
                                            ? (string) $row['canonical_lineage_edge'] : null,
            'promotion_target'       => isset($row['promotion_target']) && $row['promotion_target'] !== null
                                            ? (string) $row['promotion_target'] : null,
            'is_active'              => (int)    ($row['is_active']              ?? 1),
            'is_deleted'             => (int)    ($row['is_deleted']             ?? 0),
            'reference_target'       => (string) ($row['reference_target']       ?? 'canonical'),
            'canonical_id'           => isset($row['canonical_id']) && $row['canonical_id'] !== null
                                            ? (string) $row['canonical_id'] : null,
            'notes'                  => isset($row['notes']) && $row['notes'] !== null
                                            ? (string) $row['notes'] : null,
        );
    }

    /**
     * True when running in development mode.
     *
     * @return bool
     */
    private static function isDevMode()
    {
        return defined('LUPOPEDIA_ENV') && strtolower(LUPOPEDIA_ENV) === 'development';
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Public API — metadata accessors
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Hard-coded metadata for the registry table itself (bootstrap-safe).
     * Use during Phase 1 of the bootstrap sequence before the DB is queryable.
     *
     * @return array<string, mixed>
     */
    public static function getSelfMetadata()
    {
        return self::$selfMeta;
    }

    /**
     * Return the full metadata row for a table.
     *
     * In development: unregistered or deactivated table raises E_USER_WARNING and
     *   returns a permissive not_ladder fallback so iterative development proceeds.
     * In production (default): throws TrustLadderException (fail closed).
     *
     * @param string $tableName Full table name ('lupo_memory_nodes') or bare ('memory_nodes')
     * @return array<string, mixed> Metadata row
     * @throws TrustLadderException In production when table is unregistered or deactivated
     */
    public static function getTableMeta($tableName)
    {
        self::loadCacheIfNeeded();

        $isDev = self::isDevMode();

        if (!isset(self::$cache[$tableName])) {
            $msg = "TrustLadderRegistry: table '{$tableName}' is not registered. "
                . "Add it to TRUST_LADDER_REGISTRY.md and run sync_trust_ladder_registry_to_db.py. "
                . 'TODO(LEGACY-GAP): Unregistered table detected at runtime — '
                . 'verify every ladder table has been classified before production deployment.';
            if ($isDev) {
                trigger_error($msg, E_USER_WARNING);
                return self::devFallback($tableName);
            }
            throw new TrustLadderException(
                "Table '{$tableName}' is not registered in trust ladder registry. "
                . "Add it to TRUST_LADDER_REGISTRY.md and run sync script."
            );
        }

        $meta = self::$cache[$tableName];

        if (!$meta['is_active'] || $meta['is_deleted']) {
            $msg = "TrustLadderRegistry: metadata for '{$tableName}' is deactivated or deleted.";
            if ($isDev) {
                trigger_error($msg, E_USER_WARNING);
                return $meta;
            }
            throw new TrustLadderException($msg);
        }

        return $meta;
    }

    /**
     * Return the archetype for a table ('parent' | 'child' | 'system').
     *
     * @param string $tableName
     * @return string
     */
    public static function getArchetype($tableName)
    {
        return self::getTableMeta($tableName)['archetype'];
    }

    /**
     * True if the table is a parent archetype.
     *
     * @param string $tableName
     * @return bool
     */
    public static function isParent($tableName)
    {
        return self::getTableMeta($tableName)['archetype'] === 'parent';
    }

    /**
     * True if the table is a child archetype.
     *
     * @param string $tableName
     * @return bool
     */
    public static function isChild($tableName)
    {
        return self::getTableMeta($tableName)['archetype'] === 'child';
    }

    /**
     * True if the table requires at least one seed row in its own table.
     *
     * @param string $tableName
     * @return bool
     */
    public static function requiresSeed($tableName)
    {
        return (bool) self::getTableMeta($tableName)['seed_required'];
    }

    /**
     * Application-level reference target for cross-table links involving this table.
     *
     * Lupopedia has NO database foreign key constraints (Database Neutral SQL Doctrine).
     * This value is used exclusively by APPLICATION CODE when it needs to decide which
     * ID to store when linking to a row in this table:
     *
     *   'seed'      → use the table's short seed ID (0–999,999)
     *                 e.g. actor_id = 116, channel_id = 42
     *   'canonical' → use the table's 18-digit canonical ID
     *                 e.g. memory_node_id = 102604081200001234
     *
     * Typical callers:
     *   - Creating edges in lupo_memory_edges (which actor_id to embed?)
     *   - Populating parent_id-style fields in PHP service classes
     *   - Any cross-table reference resolved at the application layer
     *
     * NOT for: database FOREIGN KEY declarations, ON DELETE/UPDATE rules,
     * or any engine-level referential integrity — there are none.
     *
     * @param string $tableName
     * @return string 'seed' | 'canonical'
     */
    public static function getReferenceTarget($tableName)
    {
        return self::getTableMeta($tableName)['reference_target'];
    }

    /**
     * Participation mode for the table.
     *
     * @param string $tableName
     * @return string 'full' | 'generator_staging' | 'seed_only' | 'not_ladder'
     */
    public static function getParticipates($tableName)
    {
        return self::getTableMeta($tableName)['participates'];
    }

    /**
     * Canonical lineage edge type used when promoting staging rows from this table.
     * Returns null when no lineage edge is defined (child or not_ladder tables).
     *
     * @param string $tableName
     * @return string|null Edge type label (e.g. 'canonical_instance_of')
     */
    public static function getCanonicalLineageEdge($tableName)
    {
        return self::getTableMeta($tableName)['canonical_lineage_edge'];
    }

    /**
     * Promotion target for staging → canonical conversion.
     * Returns 'canonical' for full-ladder parent tables, null otherwise.
     *
     * @param string $tableName
     * @return string|null
     */
    public static function getPromotionTarget($tableName)
    {
        return self::getTableMeta($tableName)['promotion_target'];
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Public API — PK validation
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Validate that a PK value is appropriate for the given table's participation mode.
     *
     * - not_ladder: always passes (no PK constraint from registry)
     * - seed_only:  PK must be in 0–999,999 (IdGenerator::isReservedSpace)
     * - full / generator_staging: PK must satisfy IdGenerator::validateTrustLadderPk
     *
     * @param string     $tableName Full or bare table name
     * @param int|string $pkValue   PK value to validate
     * @param bool       $throw     Throw TrustLadderException on failure (default false)
     * @return bool
     * @throws TrustLadderException If $throw is true and validation fails
     *
     * TODO(LEGACY-GAP): Verify every existing ladder table has been classified and that
     *   the classification matches historical usage patterns. Pay special attention to
     *   tables that may mix parent-style and child-style rows.
     */
    public static function validatePkForTable($tableName, $pkValue, $throw = false)
    {
        $meta         = self::getTableMeta($tableName);
        $participates = $meta['participates'];

        if ($participates === 'not_ladder') {
            return true;
        }

        if (!class_exists('IdGenerator', false)) {
            require_once __DIR__ . '/IdGenerator.php';
        }

        $pkStr = (string) $pkValue;

        if ($participates === 'seed_only') {
            if (!IdGenerator::isReservedSpace($pkStr)) {
                $msg = "TrustLadderRegistry: table '{$tableName}' (seed_only) rejected PK '{$pkStr}' — "
                    . 'PK must be in seed range 0–999,999.';
                if ($throw) {
                    throw new TrustLadderException($msg);
                }
                trigger_error($msg, E_USER_WARNING);
                return false;
            }
            return true;
        }

        // full or generator_staging: must be valid 18-digit trust-ladder PK.
        $context = $tableName . '.pk';
        $valid   = IdGenerator::validateTrustLadderPk($pkStr, $context, false);
        if (!$valid) {
            $msg = "TrustLadderRegistry: table '{$tableName}' (participates={$participates}) "
                . "rejected PK '{$pkStr}' — must be a valid trust-ladder PK.";
            if ($throw) {
                throw new TrustLadderException($msg);
            }
            return false;
        }

        return true;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Public API — invariant enforcement
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Assert that a table's archetype invariants are consistent.
     * Throws TrustLadderException if any invariant is violated.
     *
     * Invariants (from sync script and doctrine):
     *   parent  → seed_required = 1, reference_target = 'seed'
     *   child   → seed_required = 0, reference_target = 'canonical'
     *   system  → seed_required = 1
     *
     * @param string $tableName
     * @throws TrustLadderException
     */
    public static function assertInvariants($tableName)
    {
        $meta      = self::getTableMeta($tableName);
        $archetype = $meta['archetype'];
        $errors    = array();

        if ($archetype === 'parent') {
            if (!$meta['seed_required']) {
                $errors[] = "parent table '{$tableName}' must have seed_required = 1.";
            }
            if ($meta['reference_target'] !== 'seed') {
                $errors[] = "parent table '{$tableName}' must have reference_target = 'seed'.";
            }
        } elseif ($archetype === 'child') {
            if ($meta['seed_required']) {
                $errors[] = "child table '{$tableName}' must have seed_required = 0.";
            }
            if ($meta['reference_target'] !== 'canonical') {
                $errors[] = "child table '{$tableName}' must have reference_target = 'canonical'.";
            }
        } elseif ($archetype === 'system') {
            if (!$meta['seed_required']) {
                $errors[] = "system table '{$tableName}' must have seed_required = 1.";
            }
        } else {
            $errors[] = "unknown archetype '{$archetype}' for table '{$tableName}'.";
        }

        if (!empty($errors)) {
            throw new TrustLadderException(
                'TrustLadderRegistry invariant violation(s): ' . implode(' | ', $errors)
            );
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Permissive fallback metadata for development mode when a table is unregistered.
     *
     * @param string $tableName
     * @return array<string, mixed>
     */
    private static function devFallback($tableName)
    {
        return array(
            'registry_id'            => 0,
            'table_name'             => $tableName,
            'archetype'              => 'child',
            'participates'           => 'not_ladder',
            'seed_required'          => 0,
            'canonical_lineage_edge' => null,
            'promotion_target'       => null,
            'is_active'              => 1,
            'is_deleted'             => 0,
            'reference_target'       => 'canonical',
            'canonical_id'           => null,
            'notes'                  => 'TODO(LEGACY-GAP): Development fallback — not in registry. Register this table.',
        );
    }
}
