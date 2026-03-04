/**
 * FLIP Header parser — reads YAML front-matter blocks from file text.
 *
 * A FLIP header (also known as Wolfie Header / CROP Header) is a YAML
 * front-matter block delimited by `---` lines:
 *
 *   ---
 *   # FLIP Header
 *   file_path_from_root: docs/example.md
 *   file.last_modified_system_version: "4.0.21"
 *   file.last_modified_utc: "20260118120000"
 *   channel_id: 3
 *   ---
 *
 * @module lupopedia/flip
 */

export interface FlipHeader {
    /** Canonical: X-Lupo-File-Path or file_path_from_root */
    file_path_from_root: string;
    /** Canonical: X-Lupo-Version or file.last_modified_system_version */
    file_last_modified_system_version: string;
    /** Canonical: file.last_modified_utc */
    file_last_modified_utc: string;
    /** Canonical: X-Lupo-Channel or channel_id */
    channel_id: number | null;
    /** Canonical: X-Lupo-Federated-Node-ID or federated_node_id */
    federated_node_id: number | null;
    /** Canonical: X-Lupo-Status or status */
    status: string | null;
    /** Canonical: X-Lupo-Thread or thread_id */
    thread_id: string | null;
    /** Canonical: X-Lupo-Actor-From or actor_id */
    actor_id: number | null;
    /** Canonical: X-Lupo-Actor-Identity or actor_identity */
    lupo_actor_identity: string | null;
    /** Canonical: From or from */
    from: string | null;
    /** Canonical: X-Lupo-Delegation-Chain or delegation_chain */
    delegation_chain?: string | null;

    // Core Routing
    lupo_actor_to?: number;

    // Survivor Protocol
    lupo_survivor_protocol?: string;
    lupo_forwarded_for?: string;
    lupo_forward_chain?: string;
    lupo_origin_status?: string;
    lupo_ban_reason?: string;
    lupo_ban_timestamp?: string;
    lupo_relay_validated_by?: string;
    lupo_collapse_ratio?: string;
    lupo_system_state?: string;

    // Operational/Registry
    lupo_task?: string;
    lupo_doctrine?: string;
    lupo_registry_mode?: string;
    lupo_registry_source?: string;
    lupo_toon_path?: string;
    lupo_csv_path?: string;

    // FLIP Footer Fields
    referenced_by_files?: string[];
    consumed_by_services?: string[];
    cited_by_docs?: string[];
    referenced_by_channels?: number[];
    referenced_by_actors?: number[];
    graph_edges_in?: string[];
    inbound_edges?: string[];
    footnotes?: string[];
    last_verified?: string;
    last_verified_by?: string;

    // Mood/Emotional
    mood_rgb?: string;

    // Verbose/Offline Metadata
    lupo_timestamp?: string;
    lupo_utc_timestamp?: string;
    lupo_location?: string;
    tags?: string;

    // Registry Metadata
    registry_id?: number | null;
    entity_type?: string | null;
    entity_index_id?: number | null;
    federation_node_id?: number | null;
    registry_metadata?: string | null;

    // Content Semantic Metadata
    content_id?: number | null;
    content_parent_id?: number | null;
    triage_status?: string | null;
    visibility?: string | null;
    view_count?: number | null;
    share_count?: number | null;
    version_number?: number | null;
    seo_keywords?: string | null;

    // Collection Metadata
    collection_id?: number | null;
    collection_name?: string | null;
    collection_slug?: string | null;

    // Channel Metadata
    channel_key?: string | null;
    channel_type?: string | null;
    channel_name?: string | null;

    // System Status
    is_kernel?: boolean | null;
    is_active?: boolean | null;
    is_deleted?: boolean | null;

    /** Any extra keys found in the FLIP block that weren't mapped */
    extras: Record<string, string>;

    /** Database mapping layer - X-LUPO-{table}.{column} entries */
    database_mapping: Record<string, string>;
}

export interface FlipParseResult {
    valid: boolean;
    header: FlipHeader | null;
    raw: string; // The header block raw
    rawFooter?: string; // The footer block raw
    errors: string[];
}

const REQUIRED_FIELDS = [
    'file_path_from_root',
    'file.last_modified_system_version',
    'file.last_modified_utc',
];

/**
 * Maps varying header aliases to canonical internal keys.
 */
const CANONICAL_MAP: Record<string, keyof FlipHeader | string> = {
    // File Identity & Path
    'file_path_from_root': 'file_path_from_root',
    'x-lupo-file-path': 'file_path_from_root',
    'x-flip-file-path': 'file_path_from_root',
    'wolfie-file-path': 'file_path_from_root',

    // System & Versioning
    'file.last_modified_system_version': 'file_last_modified_system_version',
    'x-lupo-version': 'file_last_modified_system_version',
    'x-flip-version': 'file_last_modified_system_version',
    'wolfie-version': 'file_last_modified_system_version',

    // Temporal
    'file.last_modified_utc': 'file_last_modified_utc',
    'x-flip-timestamp': 'file_last_modified_utc',

    // Routing & Identity
    'channel_id': 'channel_id',
    'x-lupo-channel': 'channel_id',
    'wolfie-channel': 'channel_id',
    'federated_node_id': 'federated_node_id',
    'x-lupo-federated-node-id': 'federated_node_id',
    'x-flip-federated-node-id': 'federated_node_id',
    'thread_id': 'thread_id',
    'x-lupo-thread': 'thread_id',
    'x-flip-thread': 'thread_id',
    'actor_id': 'actor_id',
    'x-lupo-actor-from': 'actor_id',
    'x-flip_actor-id': 'actor_id',
    'x-lupo-actor-identity': 'lupo_actor_identity',
    'actor_identity': 'lupo_actor_identity',
    'from': 'from',
    'x-lupo-from': 'from',
    'delegation_chain': 'delegation_chain',
    'x-lupo-delegation-chain': 'delegation_chain',
    'x-lupo-actor-to': 'lupo_actor_to',

    // Status & Doctrine
    'status': 'status',
    'x-lupo-status': 'status',
    'x-lupo-doctrine': 'lupo_doctrine',

    // Survivor Protocol
    'x-lupo-survivor-protocol': 'lupo_survivor_protocol',
    'x-lupo-forwarded-for': 'lupo_forwarded_for',
    'x-lupo-forward-chain': 'lupo_forward_chain',
    'x-lupo-origin-status': 'lupo_origin_status',
    'x-lupo-ban-reason': 'lupo_ban_reason',
    'x-lupo-ban-timestamp': 'lupo_ban_timestamp',
    'x-lupo-relay-validated-by': 'lupo_relay_validated_by',
    'x-lupo-collapse-ratio': 'lupo_collapse_ratio',
    'x-lupo-system-state': 'lupo_system_state',

    // Registry & Task
    'x-lupo-task': 'lupo_task',
    'x-lupo-registry-mode': 'lupo_registry_mode',
    'x-lupo-registry-source': 'lupo_registry_source',
    'x-lupo-toon-path': 'lupo_toon_path',
    'x-lupo-csv-path': 'lupo_csv_path',

    // Mood/Emotional
    'mood_rgb': 'mood_rgb',
    'x-lupo-mood-rgb': 'mood_rgb',

    // Verbose/Offline
    'x-lupo-timestamp': 'lupo_timestamp',
    'x-lupo-utc-timestamp': 'lupo_utc_timestamp',
    'x-lupo-location': 'lupo_location',
    'tags': 'tags',

    // Registry
    'x-lupo-registry-id': 'registry_id',
    'x-lupo-entity-type': 'entity_type',
    'x-lupo-entity-index-id': 'entity_index_id',
    'x-lupo-federation-node-id': 'federation_node_id',
    'x-lupo-registry-metadata': 'registry_metadata',

    // Content
    'x-lupo-content-id': 'content_id',
    'content_id': 'content_id',
    'x-lupo-content-parent-id': 'content_parent_id',
    'x-lupo-triage-status': 'triage_status',
    'triage_status': 'triage_status',
    'x-lupo-visibility': 'visibility',
    'visibility': 'visibility',
    'x-lupo-view-count': 'view_count',
    'x-lupo-share-count': 'share_count',
    'x-lupo-version-number': 'version_number',
    'x-lupo-seo-keywords': 'seo_keywords',

    // Collections
    'x-lupo-collection-id': 'collection_id',
    'x-lupo-collection-name': 'collection_name',
    'x-lupo-collection-slug': 'collection_slug',

    // Channels
    'x-lupo-channel-key': 'channel_key',
    'x-lupo-channel-type': 'channel_type',
    'x-lupo-channel-name': 'channel_name',

    // System
    'x-lupo-is-kernel': 'is_kernel',
    'x-lupo-is-active': 'is_active',
    'x-lupo-is-deleted': 'is_deleted'
};

/**
 * Validate X-LUPO-{table}.{column} database mapping format
 */
function isValidDatabaseMapping(key: string): boolean {
    const lowerKey = key.toLowerCase();
    if (!lowerKey.startsWith('x-lupo-')) {
        return false;
    }
    const afterPrefix = lowerKey.slice(8); // Remove 'x-lupo-'
    const parts = afterPrefix.split('.');
    return parts.length === 2 && parts[0].length > 0 && parts[1].length > 0;
}

/**
 * Schema tables and columns for validation
 */
const SCHEMA_TABLES: Record<string, string[]> = {
    'actors': [
        'actor_id', 'actor_type', 'slug', 'name', 'created_ymdhis',
        'updated_ymdhis', 'is_active', 'is_deleted', 'paired_actor_id',
        'primary_federation_node_id', 'department_id', 'is_kernel', 'can_login',
        'metadata_json', 'identity_provider_config', 'avatar_hash'
    ],
    'channels': [
        'channel_id', 'federation_node_id', 'channel_key', 'channel_slug',
        'channel_type', 'channel_name', 'created_ymdhis', 'updated_ymdhis',
        'is_deleted', 'deleted_ymdhis', 'is_active'
    ],
    'dialog_messages': [
        'dialog_message_id', 'message_id', 'dialog_thread_id', 'channel_id',
        'from_actor_id', 'to_actor_id', 'message_text', 'message_type',
        'created_ymdhis', 'updated_ymdhis', 'is_deleted', 'deleted_ymdhis'
    ],
    'registry': [
        'registry_id', 'entity_type', 'entity_index_id', 'entity_name',
        'entity_table', 'federation_node_id', 'created_ymdhis', 'updated_ymdhis',
        'is_deleted', 'deleted_ymdhis', 'is_active', 'is_kernel', 'metadata_json'
    ]
};

function isValidTable(table: string): boolean {
    return table in SCHEMA_TABLES;
}

function isValidColumn(table: string, column: string): boolean {
    return SCHEMA_TABLES[table]?.includes(column) ?? false;
}

/**
 * Extract YAML front-matter blocks from text.
 * Lupopedia files can have a header and a footer.
 */
function extractBlocks(text: string): { header: string | null; footer: string | null } {
    const lines = text.split(/\r?\n/);
    const separators: number[] = [];

    for (let i = 0; i < lines.length; i++) {
        if (lines[i].trim() === '---') {
            separators.push(i);
        }
    }

    let header: string | null = null;
    let footer: string | null = null;

    if (separators.length >= 2) {
        // First block is header
        header = lines.slice(separators[0] + 1, separators[1]).join('\n');
    }

    if (separators.length >= 4) {
        // Last block is likely the footer (Lupopedia doctrine: content separated by ---)
        footer = lines.slice(separators[separators.length - 2] + 1, separators[separators.length - 1]).join('\n');
    } else if (separators.length === 3) {
        // Edge case: single --- at end or middle?
        // Usually, a footer is its own --- block.
    }

    return { header, footer };
}

/**
 * Parse a YAML-like block into key-value pairs.
 * Handles strings and simple lists (lines starting with -).
 */
function parseKV(block: string): Record<string, any> {
    const result: Record<string, any> = {};
    const lines = block.split(/\r?\n/);
    let currentKey: string | null = null;

    for (const line of lines) {
        const trimmed = line.trim();
        if (!trimmed || trimmed.startsWith('#')) {
            continue;
        }

        if (trimmed.startsWith('-')) {
            // It's a list item for the last key
            if (currentKey) {
                if (!Array.isArray(result[currentKey])) {
                    result[currentKey] = [];
                }
                const val = trimmed.slice(1).trim().replace(/^["']|["']$/g, '');
                result[currentKey].push(val);
            }
            continue;
        }

        const colonIdx = trimmed.indexOf(':');
        if (colonIdx === -1) {
            continue;
        }

        const key = trimmed.slice(0, colonIdx).trim();
        const rawValue = trimmed.slice(colonIdx + 1).trim();
        const value = rawValue.replace(/^["']|["']$/g, '');

        if (key) {
            currentKey = key;
            result[key] = value;
        }
    }
    return result;
}

/**
 * Parse a FLIP header/footer from raw file text.
 */
export function parseFlipHeader(text: string): FlipParseResult {
    const errors: string[] = [];

    try {
        const { header: rawHeader, footer: rawFooter } = extractBlocks(text);
        if (rawHeader === null) {
            return {
                valid: false,
                header: null,
                raw: '',
                errors: ['No FLIP header block found (expected --- ... --- at the top).'],
            };
        }

        const kvHeader = parseKV(rawHeader);
        const kvFooter = rawFooter ? parseKV(rawFooter) : {};

        // Merge them - Footer values overwrite header if duplicates (rare)
        const kv = { ...kvHeader, ...kvFooter };

        const mapped: Partial<FlipHeader> = { extras: {}, database_mapping: {} };

        // Process all keys, mapping to canonical if possible
        for (const [key, value] of Object.entries(kv)) {
            const lowerKey = key.toLowerCase();

            // Check for database mapping layer first
            if (isValidDatabaseMapping(key)) {
                mapped.database_mapping![key] = String(value);
                continue;
            }

            // Handle fuzzy prefix aliases
            let canonicalKey: string | undefined = CANONICAL_MAP[lowerKey] as string;
            if (!canonicalKey) {
                const cleanKey = lowerKey.replace(/^(x-lupo-|x-flip-|wolfie-|flp-)/, 'x-lupo-');
                canonicalKey = CANONICAL_MAP[cleanKey] as string;
            }

            if (canonicalKey) {
                const k = canonicalKey as keyof FlipHeader;
                if (k === 'referenced_by_files' || k === 'consumed_by_services' ||
                    k === 'cited_by_docs' || k === 'graph_edges_in' ||
                    k === 'inbound_edges' || k === 'footnotes') {
                    mapped[k] = Array.isArray(value) ? value : [String(value)];
                } else if (k === 'referenced_by_channels' || k === 'referenced_by_actors') {
                    const arr = Array.isArray(value) ? value : [String(value)];
                    mapped[k] = arr.map(v => parseInt(v, 10)).filter(v => !isNaN(v));
                } else if (k === 'channel_id' || k === 'actor_id' || k === 'lupo_actor_to') {
                    const parsed = parseInt(String(value), 10);
                    (mapped as any)[k] = isNaN(parsed) ? null : parsed;
                } else if (k === 'is_kernel' || k === 'is_active' || k === 'is_deleted') {
                    const lowVal = String(value).toLowerCase().trim();
                    (mapped as any)[k] = (lowVal === 'true' || lowVal === '1' || lowVal === 'yes');
                } else if (k !== 'extras' && k !== 'database_mapping') {
                    (mapped as any)[k] = String(value);
                }
            } else {
                mapped.extras![key] = String(value);
            }
        }

        // Default missing core fields
        const header: FlipHeader = {
            file_path_from_root: (mapped.file_path_from_root as string) || '',
            file_last_modified_system_version: (mapped.file_last_modified_system_version as string) || '',
            file_last_modified_utc: (mapped.file_last_modified_utc as string) || '',
            channel_id: mapped.channel_id ?? null,
            federated_node_id: mapped.federated_node_id ?? null,
            status: mapped.status ?? null,
            thread_id: mapped.thread_id ?? null,
            actor_id: mapped.actor_id ?? null,
            lupo_actor_identity: mapped.lupo_actor_identity || null,
            from: mapped.from || null,
            extras: mapped.extras || {},
            database_mapping: mapped.database_mapping || {},
            ...mapped
        };

        // Validation
        if (!header.file_path_from_root) errors.push("Missing required FLIP field: file_path_from_root");
        if (!header.file_last_modified_system_version) errors.push("Missing required FLIP field: file_last_modified_system_version");
        if (!header.file_last_modified_utc) errors.push("Missing required FLIP field: file_last_modified_utc");

        return {
            valid: errors.length === 0,
            header,
            raw: rawHeader,
            rawFooter: rawFooter || undefined,
            errors,
        };
    } catch (err) {
        return {
            valid: false,
            header: null,
            raw: '',
            errors: [`FLIP Parser Panic: ${err instanceof Error ? err.message : String(err)}`],
        };
    }
}

/**
 * Format a FlipHeader back into a YAML front-matter string.
 * Uses X-Lupo-* canonical keys for maximum doctrine adherence.
 */
export function formatFlipHeader(header: FlipHeader): string {
    const lines = [
        '---',
        '# FLIP Header (alias: Wolfie Header, CROP Header)',
        'wolfie.headers: explicit architecture with structured clarity for every file.',
        `X-Lupo-File-Path: ${header.file_path_from_root}`,
        `X-Lupo-Version: "${header.file_last_modified_system_version}"`,
        `file.last_modified_utc: "${header.file_last_modified_utc}"`,
    ];

    if (header.channel_id !== null) lines.push(`X-Lupo-Channel: ${header.channel_id}`);
    if (header.actor_id !== null) lines.push(`X-Lupo-Actor-From: ${header.actor_id}`);
    if (header.lupo_actor_identity) lines.push(`X-Lupo-Actor-Identity: "${header.lupo_actor_identity}"`);
    if (header.from) lines.push(`From: "${header.from}"`);
    if (header.delegation_chain) lines.push(`X-Lupo-Delegation-Chain: "${header.delegation_chain}"`);
    if (header.lupo_actor_to) lines.push(`X-Lupo-Actor-To: ${header.lupo_actor_to}`);
    if (header.status) lines.push(`X-Lupo-Status: ${header.status}`);
    if (header.thread_id) lines.push(`X-Lupo-Thread: ${header.thread_id}`);

    // Survivor Protocol
    if (header.lupo_survivor_protocol) lines.push(`X-Lupo-Survivor-Protocol: ${header.lupo_survivor_protocol}`);
    if (header.lupo_forwarded_for) lines.push(`X-Lupo-Forwarded-For: ${header.lupo_forwarded_for}`);
    if (header.lupo_forward_chain) lines.push(`X-Lupo-Forward-Chain: ${header.lupo_forward_chain}`);

    // Registry/Task
    if (header.lupo_task) lines.push(`X-Lupo-Task: ${header.lupo_task}`);
    if (header.lupo_doctrine) lines.push(`X-Lupo-Doctrine: ${header.lupo_doctrine}`);
    if (header.mood_rgb) lines.push(`X-Lupo-Mood-RGB: ${header.mood_rgb}`);

    // Verbose/Offline
    if (header.lupo_timestamp) lines.push(`X-Lupo-Timestamp: ${header.lupo_timestamp}`);
    if (header.lupo_utc_timestamp) lines.push(`X-Lupo-UTC-Timestamp: ${header.lupo_utc_timestamp}`);
    if (header.lupo_location) lines.push(`X-Lupo-Location: ${header.lupo_location}`);
    if (header.tags) lines.push(`tags: ${header.tags}`);

    // Registry
    if (header.registry_id !== undefined) lines.push(`X-Lupo-Registry-ID: ${header.registry_id}`);
    if (header.entity_type) lines.push(`X-Lupo-Entity-Type: ${header.entity_type}`);
    if (header.entity_index_id !== undefined) lines.push(`X-Lupo-Entity-Index-ID: ${header.entity_index_id}`);
    if (header.federation_node_id !== undefined) lines.push(`X-Lupo-Federation-Node-ID: ${header.federation_node_id}`);
    if (header.registry_metadata) lines.push(`X-Lupo-Registry-Metadata: ${header.registry_metadata}`);

    // Content
    if (header.content_id !== undefined) lines.push(`X-Lupo-Content-ID: ${header.content_id}`);
    if (header.content_parent_id !== undefined) lines.push(`X-Lupo-Content-Parent-ID: ${header.content_parent_id}`);
    if (header.triage_status) lines.push(`X-Lupo-Triage-Status: ${header.triage_status}`);
    if (header.visibility) lines.push(`X-Lupo-Visibility: ${header.visibility}`);
    if (header.view_count !== undefined) lines.push(`X-Lupo-View-Count: ${header.view_count}`);
    if (header.share_count !== undefined) lines.push(`X-Lupo-Share-Count: ${header.share_count}`);
    if (header.version_number !== undefined) lines.push(`X-Lupo-Version-Number: ${header.version_number}`);
    if (header.seo_keywords) lines.push(`X-Lupo-SEO-Keywords: ${header.seo_keywords}`);

    // Collections
    if (header.collection_id !== undefined) lines.push(`X-Lupo-Collection-ID: ${header.collection_id}`);
    if (header.collection_name) lines.push(`X-Lupo-Collection-Name: ${header.collection_name}`);
    if (header.collection_slug) lines.push(`X-Lupo-Collection-Slug: ${header.collection_slug}`);

    // Channels
    if (header.channel_key) lines.push(`X-Lupo-Channel-Key: ${header.channel_key}`);
    if (header.channel_type) lines.push(`X-Lupo-Channel-Type: ${header.channel_type}`);
    if (header.channel_name) lines.push(`X-Lupo-Channel-Name: ${header.channel_name}`);

    // System
    if (header.is_kernel !== undefined) lines.push(`X-Lupo-Is-Kernel: ${header.is_kernel}`);
    if (header.is_active !== undefined) lines.push(`X-Lupo-Is-Active: ${header.is_active}`);
    if (header.is_deleted !== undefined) lines.push(`X-Lupo-Is-Deleted: ${header.is_deleted}`);

    // Database Mapping Layer (Optional)
    for (const [key, value] of Object.entries(header.database_mapping)) {
        lines.push(`${key}: ${value}`);
    }

    for (const [k, v] of Object.entries(header.extras)) {
        if (!k.startsWith('X-Lupo-') && !k.startsWith('wolfie.')) {
            lines.push(`${k}: ${v}`);
        }
    }
    lines.push('---');
    return lines.join('\n');
}

/**
 * Generate INSERT statement from database mapping layer
 */
export function generateInsertFromMapping(
    table: string,
    mapping: Record<string, string>
): string {
    // Extract columns for this table
    const columns: string[] = [];
    const values: string[] = [];

    for (const [key, value] of Object.entries(mapping)) {
        const [mappedTable, column] = key.split('.');

        if (mappedTable === table) {
            columns.push(column);
            // Quote string values for SQL
            values.push(`'${value.replace(/'/g, "''")}'`);
        }
    }

    // Ensure required timestamp columns
    if (SCHEMA_TABLES[table]?.includes('created_ymdhis') && !columns.includes('created_ymdhis')) {
        console.warn(`Missing required column: created_ymdhis`);
    }

    if (SCHEMA_TABLES[table]?.includes('updated_ymdhis') && !columns.includes('updated_ymdhis')) {
        console.warn(`Missing required column: updated_ymdhis`);
    }

    // Generate SQL with explicit column list
    if (columns.length === 0) {
        return `-- No mapping columns found for table: ${table}`;
    }

    return `INSERT INTO lupo_${table} (${columns.join(', ')}) VALUES (${values.join(', ')});`;
}

/**
 * Infer projects-root relative path for a file.
 */
export function inferRelativePath(fsPath: string, workspaceRoot: string): string {
    const path = require('path');
    let relative = path.relative(workspaceRoot, fsPath);
    // Normalize to forward slashes for cross-platform doctrine
    return relative.replace(/\\/g, '/');
}
