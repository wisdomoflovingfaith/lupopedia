/**
 * Minimal TOON validator.
 *
 * A TOON block is a structured semantic descriptor used by Lupopedia as
 * the source of truth for schema and content relationships. This validator
 * checks that a TOON block has the minimal required fields and correct
 * UTC timestamp format (14-digit YYYYMMDDHHmmss per Lupopedia doctrine).
 *
 * This is intentionally minimal — full TOON logic lives server-side.
 *
 * @module lupopedia/toon
 */

export interface ToonValidationResult {
    valid: boolean;
    errors: string[];
    warnings: string[];
    fields: Record<string, string>;
}

/** Fields that every TOON block must contain */
const REQUIRED_TOON_FIELDS = [
    'toon_id',
    'toon_type',
    'toon_name',
    'created_utc',
];

/** Fields that should be 14-digit UTC timestamps */
const UTC_TIMESTAMP_FIELDS = ['created_utc', 'updated_utc', 'expires_utc'];

/** Valid toon_type values (extend as the doctrine evolves) */
const KNOWN_TOON_TYPES = [
    'content',
    'channel',
    'actor',
    'edge',
    'semantic_path',
    'dialog',
    'artifact',
];

/**
 * Validate a TOON block provided as a key-value record.
 * Input is expected to be pre-parsed from YAML or JSON.
 */
export function validateToon(
    fields: Record<string, string | number | null | undefined>
): ToonValidationResult {
    const errors: string[] = [];
    const warnings: string[] = [];
    const normalised: Record<string, string> = {};

    // Normalise all values to strings for consistent checking
    for (const [k, v] of Object.entries(fields)) {
        normalised[k] = v !== null && v !== undefined ? String(v) : '';
    }

    // Required field presence
    for (const field of REQUIRED_TOON_FIELDS) {
        if (!normalised[field] || normalised[field].trim() === '') {
            errors.push(`Missing required TOON field: ${field}`);
        }
    }

    // UTC timestamp format validation
    for (const field of UTC_TIMESTAMP_FIELDS) {
        const val = normalised[field];
        if (val && val.trim() !== '') {
            if (!/^\d{14}$/.test(val.trim())) {
                errors.push(
                    `Field "${field}" must be exactly 14 digits (YYYYMMDDHHmmss), got: "${val}"`
                );
            }
        }
    }

    // toon_type validation
    const toonType = (normalised['toon_type'] ?? '').trim().toLowerCase();
    if (toonType && !KNOWN_TOON_TYPES.includes(toonType)) {
        warnings.push(
            `Unknown toon_type: "${toonType}". Known types: ${KNOWN_TOON_TYPES.join(', ')}.`
        );
    }

    // toon_id should be numeric
    const toonId = normalised['toon_id'] ?? '';
    if (toonId && !/^\d+$/.test(toonId.trim())) {
        errors.push(`toon_id must be a positive integer, got: "${toonId}"`);
    }

    // Warn on missing optional but common fields
    const optionalCommon = ['channel_id', 'actor_id', 'is_deleted'];
    for (const f of optionalCommon) {
        if (!Object.prototype.hasOwnProperty.call(normalised, f)) {
            warnings.push(`Optional field "${f}" is absent from TOON block.`);
        }
    }

    return {
        valid: errors.length === 0,
        errors,
        warnings,
        fields: normalised,
    };
}

/**
 * Parse a simple flat YAML-like block string into a key-value record
 * and validate it as a TOON.
 */
export function validateToonFromYaml(yaml: string): ToonValidationResult {
    const fields: Record<string, string> = {};
    for (const line of yaml.split(/\r?\n/)) {
        const trimmed = line.trim();
        if (!trimmed || trimmed.startsWith('#')) {
            continue;
        }
        const colonIdx = trimmed.indexOf(':');
        if (colonIdx === -1) {
            continue;
        }
        const key = trimmed.slice(0, colonIdx).trim();
        const value = trimmed.slice(colonIdx + 1).trim().replace(/^["']|["']$/g, '');
        if (key) {
            fields[key] = value;
        }
    }
    return validateToon(fields);
}
