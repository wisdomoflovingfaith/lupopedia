/**
 * Actor identity storage — persists actor_id, actor_name, actor_type
 * across VS Code sessions using ExtensionContext.globalState.
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
 * Load stored actor identity. Returns null if not yet registered.
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
 * Persist actor identity to global state.
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
 * Clear stored actor identity (deregisters the IDE locally).
 */
export async function clearIdentity(): Promise<void> {
    if (!_ctx) {
        return;
    }
    await _ctx.globalState.update(KEY_ACTOR_ID, undefined);
    await _ctx.globalState.update(KEY_ACTOR_NAME, undefined);
    await _ctx.globalState.update(KEY_ACTOR_TYPE, undefined);
}
