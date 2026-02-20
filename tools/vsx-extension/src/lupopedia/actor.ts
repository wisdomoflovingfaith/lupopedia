/**
 * Actor registration and lookup via Lupopedia registry API.
 *
 * Endpoints used:
 *   POST /registry/actors/register
 *   GET  /registry/actors/lookup?name=&type=
 *
 * @module lupopedia/actor
 */

import { lupoGet, lupoPost } from './client';
import { ActorIdentity, saveIdentity } from './identity';

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
