/**
 * Actor identity storage — persists actor_id, actor_name, actor_type
 * across VS Code sessions using ExtensionContext.globalState.
 *
 * Identity is ALWAYS obtained from the registry at runtime — either by
 * lookup (GET /registry/actors/lookup) or by registration
 * (POST /registry/actors/register). No actor_id is ever hardcoded here;
 * that would defeat the purpose of the unified registry.
 *
 * @module lupopedia/identity
 */

import * as vscode from 'vscode';

const KEY_ACTOR_ID = 'lupopedia.actor_id';
const KEY_ACTOR_NAME = 'lupopedia.actor_name';
const KEY_ACTOR_TYPE = 'lupopedia.actor_type';

export interface ActorIdentity {
    actor_id: number;
    actor_name: string;
    actor_type: string;
}

let _ctx: vscode.ExtensionContext | null = null;

/**
 * Initialise identity storage with the extension context.
 * Must be called once from `activate()`.
 */
export function initIdentityStorage(ctx: vscode.ExtensionContext): void {
    _ctx = ctx;
}

/**
 * Load stored actor identity.
 * Returns null if the IDE has not yet been registered/looked up via the registry.
 * Callers should trigger 'lupopedia.registerIde' if this returns null.
 */
export function loadIdentity(): ActorIdentity | null {
    if (!_ctx) {
        return null;
    }
    const id = _ctx.globalState.get<number>(KEY_ACTOR_ID);
    const name = _ctx.globalState.get<string>(KEY_ACTOR_NAME);
    const type = _ctx.globalState.get<string>(KEY_ACTOR_TYPE);

    if (!id || !name || !type) {
        return null;
    }
    return { actor_id: id, actor_name: name, actor_type: type };
}

/**
 * Persist actor identity returned by the registry to global state.
 * Only call this with a server-confirmed actor_id.
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
 * Clear stored actor identity (forces a fresh registry lookup on next activate).
 */
export async function clearIdentity(): Promise<void> {
    if (!_ctx) {
        return;
    }
    await _ctx.globalState.update(KEY_ACTOR_ID, undefined);
    await _ctx.globalState.update(KEY_ACTOR_NAME, undefined);
    await _ctx.globalState.update(KEY_ACTOR_TYPE, undefined);
}
