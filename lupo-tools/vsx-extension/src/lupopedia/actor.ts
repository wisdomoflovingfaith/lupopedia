/**
 * Actor registration and lookup via Lupopedia registry API.
 *
 * Endpoints used:
 *   POST /registry/actors/register
 *   GET  /registry/actors/lookup?name=&type=
 *
 * lookupKnownActors() does the startup batch lookup for
 * Microsoft Copilot, DeepSeek LEXA, and DeepSeek LILITH.
 * Their actor_ids are NEVER hardcoded — always fetched from the server.
 *
 * @module lupopedia/actor
 */

import { lupoGet, lupoPost } from './client';
import { ActorIdentity, KNOWN_EXTERNAL_ACTORS, saveIdentity, mergeActorCache } from './identity';
import * as fs from 'fs';
import * as path from 'path';
import * as vscode from 'vscode';

export interface RegisterRequest {
    actor_name: string;
    actor_type: string;
    /** Optional metadata blob to store alongside the actor. */
    meta?: Record<string, unknown>;
}

export interface RegisterResponse {
    actor_id: number;
    actor_name: string;
    actor_type: string;
    registered_at?: string;
    [key: string]: unknown;
}

export interface LookupResponse {
    actors: Array<{
        actor_id: number;
        actor_name: string;
        actor_type: string;
        [key: string]: unknown;
    }>;
}

/**
 * Register this IDE as an actor in the Lupopedia registry.
 * Saves the returned identity to globalState.
 *
 * @returns The full actor identity as returned by the server.
 */
export async function registerActor(
    baseUrl: string,
    actorName: string,
    actorType: string
): Promise<ActorIdentity> {
    const payload: RegisterRequest = {
        actor_name: actorName,
        actor_type: actorType,
        meta: {
            source: 'lupopedia-vsx-extension',
            version: '0.1.0',
        },
    };

    const res = await lupoPost<RegisterResponse>(
        baseUrl,
        '/registry/actors/register',
        payload
    );

    if (!res.ok) {
        throw new Error(
            `Registration failed (HTTP ${res.status}): ${JSON.stringify(res.data)}`
        );
    }

    const identity: ActorIdentity = {
        actor_id: res.data.actor_id,
        actor_name: res.data.actor_name,
        actor_type: res.data.actor_type,
    };

    await saveIdentity(identity);
    return identity;
}

/**
 * Look up actors by name and/or type.
 * Returns null if none found or on error.
 */
export async function lookupActor(
    baseUrl: string,
    actorName: string,
    actorType?: string
): Promise<ActorIdentity | null> {
    const params = new URLSearchParams({ name: actorName });
    if (actorType) {
        params.set('type', actorType);
    }

    const res = await lupoGet<LookupResponse>(
        baseUrl,
        `/registry/actors/lookup?${params.toString()}`
    );

    if (!res.ok || !res.data.actors || res.data.actors.length === 0) {
        return null;
    }

    const first = res.data.actors[0];
    return {
        actor_id: first.actor_id,
        actor_name: first.actor_name,
        actor_type: first.actor_type,
    };
}

// ─── Batch / external actor helpers ──────────────────────────────────────────

/**
 * Look up all registered actors of a given type.
 * Returns as many as the server returns (no client-side limit).
 */
export async function lookupActorsByType(
    baseUrl: string,
    actorType: string
): Promise<ActorIdentity[]> {
    const res = await lupoGet<LookupResponse>(
        baseUrl,
        `/registry/actors/lookup?type=${encodeURIComponent(actorType)}`
    );
    if (!res.ok || !res.data.actors) {
        return [];
    }
    return res.data.actors.map((a) => ({
        actor_id: a.actor_id,
        actor_name: a.actor_name,
        actor_type: a.actor_type,
    }));
}

/**
 * Startup batch lookup for all known external actors
 * (Microsoft Copilot, DeepSeek LEXA, DeepSeek LILITH).
 *
 * - Looks each one up individually by name + type.
 * - Silently skips actors not yet seeded on the server.
 * - Merges results into the persistent actor cache.
 * - Returns the resolved identities (may be fewer than requested).
 */
export async function lookupKnownActors(
    baseUrl: string,
    mode: 'remote' | 'local' | 'offline' | 'auto' = 'auto'
): Promise<ActorIdentity[]> {
    // Tier 3: Offline mode - use TOON files directly
    if (mode === 'offline') {
        return lookupKnownActorsLocal();
    }

    // Tier 2: Local mode - use localhost API
    if (mode === 'local') {
        try {
            return await lookupKnownActorsApi('http://localhost/lupopedia');
        } catch {
            // Localhost failed, fall through to TOON files
            return lookupKnownActorsLocal();
        }
    }

    // Tier 1: Remote mode - use production API
    if (mode === 'remote') {
        try {
            return await lookupKnownActorsApi('https://lupopedia.com/lupopedia');
        } catch {
            // Production failed, fall through to TOON files
            return lookupKnownActorsLocal();
        }
    }

    // Auto mode: Try all tiers in order
    try {
        // Try production first
        return await lookupKnownActorsApi('https://lupopedia.com/lupopedia');
    } catch {
        try {
            // Production failed, try localhost
            return await lookupKnownActorsApi('http://localhost/lupopedia');
        } catch {
            // Both failed, use TOON files
            return lookupKnownActorsLocal();
        }
    }
}

async function lookupKnownActorsApi(apiBaseUrl: string): Promise<ActorIdentity[]> {
    const resolved: ActorIdentity[] = [];

    for (const { name, type } of KNOWN_EXTERNAL_ACTORS) {
        try {
            const identity = await lookupActor(apiBaseUrl, name, type);
            if (identity) {
                resolved.push(identity);
            }
        } catch {
            // Server may not have this actor seeded yet — skip silently
        }
    }

    if (resolved.length > 0) {
        await mergeActorCache(resolved);
    }

    return resolved;
}

import { parseFlipHeader } from './flip';

// ... (previous interfaces)

interface ToonAgent {
    agent_id?: number;
    agent_key?: string;
    agent_name?: string;
    archetype?: string;
    [key: string]: unknown;
}

async function extractAgentsFromInventoryMD(workspaceRoot: string): Promise<ActorIdentity[]> {
    const inventoryPath = path.join(workspaceRoot, 'docs', 'AGENT_INVENTORY.md');
    if (!fs.existsSync(inventoryPath)) { return []; }

    const raw = fs.readFileSync(inventoryPath, 'utf-8');
    const agents: ActorIdentity[] = [];

    // Simple regex for table rows: | 1001 | KIRO | ... |
    const rowRegex = /^\|\s*(\d+)\s*\|\s*([^|]+)\s*\|\s*([^|]+)\s*\|/gm;
    let match;
    while ((match = rowRegex.exec(raw)) !== null) {
        const id = parseInt(match[1], 10);
        const name = match[2].trim();
        const type = match[3].trim();
        if (!isNaN(id) && name && !name.toLowerCase().includes('actor')) {
            agents.push({ actor_id: id, actor_name: name, actor_type: type });
        }
    }
    return agents;
}

async function lookupKnownActorsLocal(): Promise<ActorIdentity[]> {
    const folders = vscode.workspace.workspaceFolders;
    if (!folders || folders.length === 0) { return []; }
    const root = folders[0].uri.fsPath;

    const resolved: ActorIdentity[] = [];

    // 1. Try AGENT_INVENTORY.md (New Doctrine)
    try {
        const mdAgents = await extractAgentsFromInventoryMD(root);
        resolved.push(...mdAgents);
    } catch { }

    // 2. Try TOON file (Legacy Fallback)
    const toonPath = path.join(root, 'docs', 'toons', 'lupo_agents.toon.json');
    if (fs.existsSync(toonPath)) {
        try {
            const raw = fs.readFileSync(toonPath, 'utf-8');
            const parsed = JSON.parse(raw) as { data?: ToonAgent[] };
            if (parsed.data) {
                for (const agent of parsed.data) {
                    if (agent.agent_name && agent.agent_id && !resolved.some(a => a.actor_id === agent.agent_id)) {
                        resolved.push({
                            actor_id: agent.agent_id,
                            actor_name: agent.agent_name,
                            actor_type: agent.archetype || 'system_tool',
                        });
                    }
                }
            }
        } catch { }
    }

    if (resolved.length > 0) {
        await mergeActorCache(resolved);
    }
    return resolved;
}
