/**
 * Channel messaging — send and receive messages in Lupopedia channels.
 *
 * Endpoints used:
 *   POST /channels/{id}/messages
 *   GET  /channels/{id}/messages
 *
 * @module lupopedia/channels
 */

import * as fs from 'fs';
import * as path from 'path';
import * as vscode from 'vscode';
import { lupoGet, lupoPost } from './client';
import { ActorIdentity } from './identity';

export type CommMode = 'remote' | 'local' | 'offline' | 'auto';

export interface ChannelMessage {
    message_id?: number;
    actor_id: number;
    actor_name?: string;
    actor_type?: string;
    channel_id: number;
    body: string;
    created_at?: string;
    meta?: Record<string, unknown>;
}
// ... (rest of interfaces)
export interface SendMessageRequest {
    actor_id: number;
    actor_name: string;
    actor_type: string;
    body: string;
    delegation_chain: string;
    meta?: Record<string, unknown>;
}

export interface SendMessageResponse {
    message_id: number;
    channel_id: number;
    accepted: boolean;
    [key: string]: unknown;
}

export interface GetMessagesResponse {
    channel_id: number;
    messages: ChannelMessage[];
    [key: string]: unknown;
}

/**
 * Send a message to a Lupopedia channel.
 */
export async function sendMessage(
    baseUrl: string,
    channelId: number,
    body: string,
    identity: ActorIdentity,
    meta?: Record<string, unknown>,
    mode: CommMode = 'auto'
): Promise<SendMessageResponse> {
    // Tier 3: Offline mode - use local files directly
    if (mode === 'offline') {
        return sendMessageLocal(channelId, body, identity);
    }

    // Tier 2: Local mode - use localhost API
    if (mode === 'local') {
        try {
            return await sendMessageApi('http://localhost/lupopedia', channelId, body, identity, meta);
        } catch {
            // Localhost failed, fall through to offline mode
            return sendMessageLocal(channelId, body, identity);
        }
    }

    // Tier 1: Remote mode - use production API
    if (mode === 'remote') {
        try {
            return await sendMessageApi('https://lupopedia.com/lupopedia', channelId, body, identity, meta);
        } catch {
            // Production failed, fall through to offline mode
            return sendMessageLocal(channelId, body, identity);
        }
    }

    // Auto mode: Try all tiers in order
    try {
        // Try production first
        return await sendMessageApi('https://lupopedia.com/lupopedia', channelId, body, identity, meta);
    } catch {
        try {
            // Production failed, try localhost
            return await sendMessageApi('http://localhost/lupopedia', channelId, body, identity, meta);
        } catch {
            // Both failed, use local files
            return sendMessageLocal(channelId, body, identity);
        }
    }
}

async function sendMessageApi(
    apiBaseUrl: string,
    channelId: number,
    body: string,
    identity: ActorIdentity,
    meta?: Record<string, unknown>
): Promise<SendMessageResponse> {
    const payload: SendMessageRequest = {
        actor_id: identity.actor_id,
        actor_name: identity.actor_name,
        actor_type: identity.actor_type,
        body,
        delegation_chain: identity.delegation_chain || `${identity.actor_id}:10000`,
        ...(meta ? { meta } : {}),
    };

    const res = await lupoPost<SendMessageResponse>(
        apiBaseUrl,
        `/channels/${channelId}/messages`,
        payload
    );

    if (!res.ok) {
        throw new Error(
            `Send message failed (HTTP ${res.status}): ${JSON.stringify(res.data)}`
        );
    }

    return res.data;
}

/**
 * Retrieve messages from a Lupopedia channel.
 */
export async function getMessages(
    baseUrl: string,
    channelId: number,
    since?: string,
    mode: CommMode = 'auto'
): Promise<ChannelMessage[]> {
    // Tier 3: Offline mode - use local files directly
    if (mode === 'offline') {
        return getMessagesLocal(channelId, since);
    }

    // Tier 2: Local mode - use localhost API
    if (mode === 'local') {
        try {
            return await getMessagesApi('http://localhost/lupopedia', channelId, since);
        } catch {
            // Localhost failed, fall through to offline mode
            return getMessagesLocal(channelId, since);
        }
    }

    // Tier 1: Remote mode - use production API
    if (mode === 'remote') {
        try {
            return await getMessagesApi('https://lupopedia.com/lupopedia', channelId, since);
        } catch {
            // Production failed, fall through to offline mode
            return getMessagesLocal(channelId, since);
        }
    }

    // Auto mode: Try all tiers in order
    try {
        // Try production first
        return await getMessagesApi('https://lupopedia.com/lupopedia', channelId, since);
    } catch {
        try {
            // Production failed, try localhost
            return await getMessagesApi('http://localhost/lupopedia', channelId, since);
        } catch {
            // Both failed, use local files
            return getMessagesLocal(channelId, since);
        }
    }
}

async function getMessagesApi(
    apiBaseUrl: string,
    channelId: number,
    since?: string
): Promise<ChannelMessage[]> {
    const params = since ? `?since=${encodeURIComponent(since)}` : '';
    const res = await lupoGet<GetMessagesResponse>(
        apiBaseUrl,
        `/channels/${channelId}/messages${params}`
    );

    if (!res.ok) {
        throw new Error(
            `Get messages failed (HTTP ${res.status}): ${JSON.stringify(res.data)}`
        );
    }

    return res.data.messages ?? [];
}

/**
 * Join a channel by sending a system join message.
 * This signals to other actors that this IDE is active in the channel.
 */
export async function joinChannel(
    baseUrl: string,
    channelId: number,
    identity: ActorIdentity,
    mode: CommMode = 'auto'
): Promise<void> {
    await sendMessage(
        baseUrl,
        channelId,
        `[JOIN] ${identity.actor_name} joined channel ${channelId}.`,
        identity,
        { event: 'join', actor_type: identity.actor_type },
        mode
    );
}

// ... (previous imports)
import { parseLupopediaHeader } from './headers';

export async function discoverChannelsLocal(): Promise<number[]> {
    const folders = vscode.workspace.workspaceFolders;
    if (!folders || folders.length === 0) { return []; }
    const root = folders[0].uri.fsPath;

    const channels = new Set<number>();

    // Discover from messages/ directory
    const msgDir = path.join(root, 'messages');
    if (fs.existsSync(msgDir)) {
        const files = fs.readdirSync(msgDir);
        for (const file of files) {
            const match = /channel_(\d+)\.md/.exec(file);
            if (match) {
                channels.add(parseInt(match[1], 10));
            }
        }
    }

    // Discover from docs/channels/ directory
    const docsChanDir = path.join(root, 'docs', 'channels');
    if (fs.existsSync(docsChanDir)) {
        const subdirs = fs.readdirSync(docsChanDir);
        for (const dir of subdirs) {
            const id = parseInt(dir, 10);
            if (!isNaN(id)) {
                channels.add(id);
            }
        }
    }

    return Array.from(channels);
}

function localChannelPath(channelId: number): string | null {
    const folders = vscode.workspace.workspaceFolders;
    if (!folders || folders.length === 0) { return null; }
    const root = folders[0].uri.fsPath;

    // Check paths in order of doctrine priority
    const paths = [
        path.join(root, 'messages', `channel_${channelId}.md`),
        path.join(root, 'docs', 'channels', String(channelId), 'index.md'),
        path.join(root, 'channels', String(channelId), 'thread.md'),
    ];

    for (const p of paths) {
        if (fs.existsSync(p)) { return p; }
    }

    return paths[0]; // Default for new files
}

function ensureLocalFile(filePath: string): void {
    const dir = path.dirname(filePath);
    if (!fs.existsSync(dir)) {
        fs.mkdirSync(dir, { recursive: true });
    }
    if (!fs.existsSync(filePath)) {
        fs.writeFileSync(filePath, `# Channel Local Fallback Log\n\n`, 'utf-8');
    }
}

async function sendMessageLocal(
    channelId: number,
    body: string,
    identity: ActorIdentity
): Promise<SendMessageResponse> {
    const filePath = localChannelPath(channelId);
    if (!filePath) {
        throw new Error('No workspace folder open — cannot use local mode.');
    }
    ensureLocalFile(filePath);

    const ts = Date.now();
    const iso = new Date().toISOString();
    const human = iso.replace('T', ' ').replace(/\.\d+Z$/, ' UTC');

    const delegation = identity.delegation_chain || `${identity.actor_id}:10000`;
    const block = [
        `<!-- message_id: ${ts} | actor_id: ${identity.actor_id} | delegation_chain: ${delegation} | created_at: ${iso} -->`,
        `### ${identity.actor_name} (#${identity.actor_id}) - ${human}`,
        body,
        '',
        '---',
        '',
    ].join('\n');

    fs.appendFileSync(filePath, block, 'utf-8');

    return {
        message_id: ts,
        channel_id: channelId,
        accepted: true,
    };
}

const LOCAL_MSG_RE = /^<!--\s*message_id:\s*(\d+)\s*\|\s*actor_id:\s*(\d+)\s*\|\s*created_at:\s*(.+?)\s*-->$/;

async function getMessagesLocal(
    channelId: number,
    since?: string
): Promise<ChannelMessage[]> {
    const filePath = localChannelPath(channelId);
    if (!filePath || !fs.existsSync(filePath)) {
        return [];
    }

    const raw = fs.readFileSync(filePath, 'utf-8');
    const blocks = raw.split(/^---$/m);
    const messages: ChannelMessage[] = [];

    for (const block of blocks) {
        const lines = block.trim().split('\n');
        if (lines.length < 2) { continue; }

        const metaMatch = LOCAL_MSG_RE.exec(lines[0].trim());
        if (!metaMatch) { continue; }

        const msgId = parseInt(metaMatch[1], 10);
        const actorId = parseInt(metaMatch[2], 10);
        const createdAt = metaMatch[3].trim();
        const bodyLines = lines.slice(2); // skip meta comment + header
        const body = bodyLines.join('\n').trim();

        if (since) {
            // Compare ISO timestamps or numeric
            const sinceTs = /^\d{14}$/.test(since)
                ? new Date(
                    since.slice(0, 4) + '-' + since.slice(4, 6) + '-' + since.slice(6, 8) +
                    'T' + since.slice(8, 10) + ':' + since.slice(10, 12) + ':' + since.slice(12, 14) + 'Z'
                ).getTime()
                : new Date(since).getTime();
            if (msgId <= sinceTs) { continue; }
        }

        messages.push({
            message_id: msgId,
            actor_id: actorId,
            channel_id: channelId,
            body,
            created_at: createdAt,
        });
    }

    return messages;
}
