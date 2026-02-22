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
}

export interface FlipParseResult {
    valid: boolean;
    header: FlipHeader | null;
    raw: string;
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
 * Extract the first YAML front-matter block from text.
 * Returns null when no `--- ... ---` block is found at or near the top.
 */
function extractRawBlock(text: string): string | null {
    const lines = text.split(/\r?\n/);
    // Allow up to 3 lines of preamble (e.g. a comment) before opening ---
    let startIdx = -1;
    for (let i = 0; i < Math.min(5, lines.length); i++) {
        if (lines[i].trim() === '---') {
            startIdx = i;
            break;
        }
    }
    if (startIdx === -1) {
        return null;
    }
    let endIdx = -1;
    for (let i = startIdx + 1; i < lines.length; i++) {
        if (lines[i].trim() === '---') {
            endIdx = i;
            break;
        }
    }
    if (endIdx === -1) {
        return null;
    }
    return lines.slice(startIdx + 1, endIdx).join('\n');
}

/**
 * Parse a YAML-like block into key-value pairs.
 * Only handles simple `key: value` lines (not nested YAML).
 */
function parseKV(block: string): Record<string, string> {
    const result: Record<string, string> = {};
    for (const line of block.split(/\r?\n/)) {
        const trimmed = line.trim();
        if (!trimmed || trimmed.startsWith('#')) {
            continue;
        }
        const colonIdx = trimmed.indexOf(':');
        if (colonIdx === -1) {
            continue;
        }
        const key = trimmed.slice(0, colonIdx).trim();
        const rawValue = trimmed.slice(colonIdx + 1).trim();
        // Strip surrounding quotes
        const value = rawValue.replace(/^["']|["']$/g, '');
        if (key) {
            result[key] = value;
        }
    }
    return result;
}

/**
 * Parse a FLIP header from raw file text.
 */
export function parseFlipHeader(text: string): FlipParseResult {
    const errors: string[] = [];

    try {
        const raw = extractRawBlock(text);
        if (raw === null) {
            return {
                valid: false,
                header: null,
                raw: '',
                errors: ['No FLIP header block found (expected --- ... --- near the top of the file).'],
            };
        }

        const kv = parseKV(raw);
        const mapped: Partial<FlipHeader> = { extras: {} };

        // Process all keys, mapping to canonical if possible
        for (const [key, value] of Object.entries(kv)) {
            const lowerKey = key.toLowerCase();

            // Handle fuzzy prefix aliases if not direct match
            let canonicalKey: string | undefined = CANONICAL_MAP[lowerKey] as string;
            if (!canonicalKey) {
                const cleanKey = lowerKey.replace(/^(x-lupo-|x-flip-|wolfie-|flp-|superpositionally-)/, 'x-lupo-');
                canonicalKey = CANONICAL_MAP[cleanKey] as string;
            }

            if (canonicalKey) {
                const k = canonicalKey as keyof FlipHeader;
                if (k === 'channel_id' || k === 'actor_id' || k === 'lupo_actor_to' ||
                    k === 'registry_id' || k === 'entity_index_id' ||
                    k === 'federation_node_id' || k === 'content_id' || k === 'content_parent_id' ||
                    k === 'collection_id' || k === 'view_count' || k === 'share_count' || k === 'version_number') {
                    const cleanValue = value.replace(/[\'\"\s]/g, '').trim();
                    const parsed = parseInt(cleanValue, 10);
                    (mapped as any)[k] = isNaN(parsed) ? null : parsed;
                } else if (k === 'is_kernel' || k === 'is_active' || k === 'is_deleted') {
                    const lowVal = value.toLowerCase().trim();
                    (mapped as any)[k] = (lowVal === 'true' || lowVal === '1' || lowVal === 'yes');
                } else if (k !== 'extras') {
                    (mapped as any)[k] = value;
                }
            } else {
                mapped.extras![key] = value;
            }
        }

        // Default missing core fields to empty/null
        const header: FlipHeader = {
            file_path_from_root: (mapped.file_path_from_root as string) || '',
            file_last_modified_system_version: (mapped.file_last_modified_system_version as string) || '',
            file_last_modified_utc: (mapped.file_last_modified_utc as string) || '',
            channel_id: mapped.channel_id ?? null,
            status: mapped.status ?? null,
            thread_id: mapped.thread_id ?? null,
            actor_id: mapped.actor_id ?? null,
            lupo_actor_identity: mapped.lupo_actor_identity || null,
            from: mapped.from || null,
            extras: mapped.extras || {},
            ...mapped
        };

        // Validation
        if (!header.file_path_from_root) errors.push("Missing required FLIP field: file_path_from_root (or X-Lupo-File-Path)");
        if (!header.file_last_modified_system_version) errors.push("Missing required FLIP field: file.last_modified_system_version (or X-Lupo-Version)");
        if (!header.file_last_modified_utc) errors.push("Missing required FLIP field: file.last_modified_utc");

        // Actor Trinity Validation (Lupopedia 4.0.27)
        if (header.actor_id === null && !header.lupo_actor_identity && !header.from) {
            errors.push("Missing Actor Attribution (Lupopedia 4.0.27): Must have at least one of X-Lupo-Actor-ID, X-Lupo-Actor-Identity, or From:");
        }

        // Validate UTC timestamp format: exactly 14 digits
        if (header.file_last_modified_utc && !/^\d{14}$/.test(header.file_last_modified_utc)) {
            errors.push(`file.last_modified_utc must be exactly 14 digits (YYYYMMDDHHmmss), got: "${header.file_last_modified_utc}"`);
        }

        return {
            valid: errors.length === 0,
            header,
            raw,
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

    for (const [k, v] of Object.entries(header.extras)) {
        if (!k.startsWith('X-Lupo-') && !k.startsWith('wolfie.')) {
            lines.push(`${k}: ${v}`);
        }
    }
    lines.push('---');
    return lines.join('\n');
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
