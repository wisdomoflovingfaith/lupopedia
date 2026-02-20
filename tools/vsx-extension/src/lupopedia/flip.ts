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
    /** Path relative to repo root, e.g. "docs/example.md" */
    file_path_from_root: string;
    /** System version string when the file was last modified */
    file_last_modified_system_version: string;
    /** UTC timestamp string (14 digits: YYYYMMDDHHmmss) */
    file_last_modified_utc: string;
    /** Channel ID this file belongs to */
    channel_id: number | null;
    /** Any extra keys found in the FLIP block */
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

    for (const field of REQUIRED_FIELDS) {
        if (!kv[field] || kv[field].trim() === '') {
            errors.push(`Missing required FLIP field: ${field}`);
        }
    }

    // Validate UTC timestamp format: exactly 14 digits
    const utc = kv['file.last_modified_utc'] ?? '';
    if (utc && !/^\d{14}$/.test(utc)) {
        errors.push(
            `file.last_modified_utc must be exactly 14 digits (YYYYMMDDHHmmss), got: "${utc}"`
        );
    }

    // Parse channel_id
    let channelId: number | null = null;
    if (kv['channel_id']) {
        const parsed = parseInt(kv['channel_id'], 10);
        channelId = isNaN(parsed) ? null : parsed;
    }

    const extras: Record<string, string> = {};
    const knownFields = new Set([
        'file_path_from_root',
        'file.last_modified_system_version',
        'file.last_modified_utc',
        'channel_id',
        'wolfie.headers',
    ]);
    for (const [k, v] of Object.entries(kv)) {
        if (!knownFields.has(k)) {
            extras[k] = v;
        }
    }

    const header: FlipHeader = {
        file_path_from_root: kv['file_path_from_root'] ?? '',
        file_last_modified_system_version: kv['file.last_modified_system_version'] ?? '',
        file_last_modified_utc: utc,
        channel_id: channelId,
        extras,
    };

    return {
        valid: errors.length === 0,
        header,
        raw,
        errors,
    };
}

/**
 * Format a FlipHeader back into a YAML front-matter string.
 */
export function formatFlipHeader(header: FlipHeader): string {
    const lines = [
        '---',
        '# FLIP Header (alias: Wolfie Header, CROP Header)',
        'wolfie.headers: explicit architecture with structured clarity for every file.',
        `file_path_from_root: ${header.file_path_from_root}`,
        `file.last_modified_system_version: "${header.file_last_modified_system_version}"`,
        `file.last_modified_utc: "${header.file_last_modified_utc}"`,
        header.channel_id !== null
            ? `channel_id: ${header.channel_id}`
            : '# channel_id: unresolved',
    ];
    for (const [k, v] of Object.entries(header.extras)) {
        lines.push(`${k}: ${v}`);
    }
    lines.push('---');
    return lines.join('\n');
}
