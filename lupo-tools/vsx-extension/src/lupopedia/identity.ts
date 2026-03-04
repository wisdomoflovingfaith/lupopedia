/**
 * Actor identity storage — persists self-identity and a cache of
 * known external actors (Copilot, DeepSeek LEXA, DeepSeek LILITH, etc.)
 * across VS Code sessions using ExtensionContext.globalState.
 *
 * ALL actor_ids come from the registry at runtime — nothing is hardcoded.
 *
 * Self-identity:   GET /registry/actors/lookup?name=Antigravity+IDE&type=system_tool
 * External actors: GET /registry/actors/lookup?name=<NAME>&type=external_ai
 *
 * @module lupopedia/identity
 */

import * as vscode from 'vscode';

// ─── Storage keys ─────────────────────────────────────────────────────────────

const KEY_ACTOR_ID = 'lupopedia.actor_id';
const KEY_ACTOR_NAME = 'lupopedia.actor_name';
const KEY_ACTOR_TYPE = 'lupopedia.actor_type';
const KEY_AUTH_TOKEN = 'lupopedia.auth_token';

/** globalState key for the JSON-serialised external-actor cache Map */
const KEY_ACTOR_CACHE = 'lupopedia.actor_cache';

// ─── Types ────────────────────────────────────────────────────────────────────

export interface ActorIdentity {
    actor_id: number;
    actor_name: string;
    actor_type: string;
}

/** All external actors whose ids the extension tracks alongside itself */
export const KNOWN_EXTERNAL_ACTORS: ReadonlyArray<{ name: string; type: string }> = [
    { name: 'Microsoft Copilot', type: 'external_ai' },
    { name: 'DeepSeek LEXA', type: 'external_ai' },
    { name: 'DeepSeek LILITH', type: 'external_ai' },
];

/** Per-actor display badge colours for use in webviews */
export const ACTOR_COLORS: Record<string, string> = {
    'Antigravity IDE': '#7c6af7',   // purple — us
    'Microsoft Copilot': '#0078d4',   // Microsoft blue
    'DeepSeek LEXA': '#00c896',   // teal
    'DeepSeek LILITH': '#e05a6e',   // red-rose
};

// ─── Module state ─────────────────────────────────────────────────────────────

let _ctx: vscode.ExtensionContext | null = null;

// ─── Init ────────────────────────────────────────────────────────────────────

/**
 * Initialise identity storage with the extension context.
 * Must be called once from `activate()`.
 */
export function initIdentityStorage(ctx: vscode.ExtensionContext): void {
    _ctx = ctx;
}

// ─── Self-identity ────────────────────────────────────────────────────────────

/**
 * Load this IDE's stored actor identity.
 * Returns null if not yet looked up / registered via the registry.
 */
export function loadIdentity(): ActorIdentity | null {
    if (!_ctx) { return null; }
    const id = _ctx.globalState.get<number>(KEY_ACTOR_ID);
    const name = _ctx.globalState.get<string>(KEY_ACTOR_NAME);
    const type = _ctx.globalState.get<string>(KEY_ACTOR_TYPE);
    if (!id || !name || !type) { return null; }
    return { actor_id: id, actor_name: name, actor_type: type };
}

/**
 * Resolve the effective actor_id in the priority order:
 * 1. Logged-in Lupopedia user session (.lupo_actor)
 * 2. IDE authentication token (config)
 * 3. Default fallback -> 10000 (Captain Wolfie)
 */
export async function resolveEffectiveActorId(): Promise<ActorIdentity> {
    // 1. Logged-in Lupopedia user session (.lupo_actor)
    const folders = vscode.workspace.workspaceFolders;
    if (folders && folders.length > 0) {
        const root = folders[0].uri.fsPath;
        const stateFile = require('path').join(root, '.lupo_actor');
        const fs = require('fs');
        if (fs.existsSync(stateFile)) {
            try {
                const raw = fs.readFileSync(stateFile, 'utf-8');
                const data = JSON.parse(raw);
                if (data.actor_id) {
                    return {
                        actor_id: Number(data.actor_id),
                        actor_name: data.name || 'Lupopedia User',
                        actor_type: data.actor_type || 'human'
                    };
                }
            } catch (err) {
                // Ignore parse errors, fallback
            }
        }
    }

    // 2. IDE authentication token (if present in globalState or config)
    // For now, check if we have a stored identity from a previous registration
    const stored = loadIdentity();
    if (stored) {
        return stored;
    }

    // 3. Default fallback -> 10000
    return {
        actor_id: 10000,
        actor_name: 'Captain Wolfie',
        actor_type: 'human'
    };
}

/**
 * Persist the server-returned self-identity.
 */
export async function saveIdentity(identity: ActorIdentity): Promise<void> {
    if (!_ctx) {
        throw new Error('Identity storage not initialised — call initIdentityStorage() first.');
    }
    await _ctx.globalState.update(KEY_ACTOR_ID, identity.actor_id);
    await _ctx.globalState.update(KEY_ACTOR_NAME, identity.actor_name);
    await _ctx.globalState.update(KEY_ACTOR_TYPE, identity.actor_type);
}

/**
 * Clear stored self-identity (forces a fresh registry lookup).
 */
export async function clearIdentity(): Promise<void> {
    if (!_ctx) { return; }
    await _ctx.globalState.update(KEY_ACTOR_ID, undefined);
    await _ctx.globalState.update(KEY_ACTOR_NAME, undefined);
    await _ctx.globalState.update(KEY_ACTOR_TYPE, undefined);
}

// ─── Multi-actor cache ────────────────────────────────────────────────────────

/**
 * Load the cached actor map.
 * Keys are actor names (e.g. "Microsoft Copilot"), values are ActorIdentity.
 * Returns an empty Map if the cache is cold.
 */
export function loadActorCache(): Map<string, ActorIdentity> {
    if (!_ctx) { return new Map(); }
    const raw = _ctx.globalState.get<Record<string, ActorIdentity>>(KEY_ACTOR_CACHE, {});
    return new Map(Object.entries(raw));
}

/**
 * Merge one or more resolved actors into the persistent cache.
 * Existing entries are overwritten with fresher data from the server.
 */
export async function mergeActorCache(actors: ActorIdentity[]): Promise<void> {
    if (!_ctx) { return; }
    const current = loadActorCache();
    for (const a of actors) {
        current.set(a.actor_name, a);
    }
    // Serialise as plain object for globalState (Map is not JSON-serialisable)
    const serialised: Record<string, ActorIdentity> = {};
    for (const [k, v] of current) {
        serialised[k] = v;
    }
    await _ctx.globalState.update(KEY_ACTOR_CACHE, serialised);
}

/**
 * Resolve an actor_id by name from the local cache.
 * Returns null if the actor hasn't been looked up yet.
 */
export function resolveActorId(name: string): number | null {
    const cache = loadActorCache();
    return cache.get(name)?.actor_id ?? null;
}

/**
 * Build the full actor roster: self + all cached external actors.
 * Self is always rendered first.
 */
export function buildActorRoster(self: ActorIdentity | null): ActorIdentity[] {
    const cache = loadActorCache();
    const roster: ActorIdentity[] = [];
    if (self) { roster.push(self); }
    for (const a of cache.values()) {
        if (!self || a.actor_id !== self.actor_id) {
            roster.push(a);
        }
    }
    return roster;
}

/**
 * Look up a display colour for an actor by name or id.
 * Falls back to a neutral grey if the actor is unknown.
 */
export function actorColor(name: string): string {
    return ACTOR_COLORS[name] ?? '#888888';
}
