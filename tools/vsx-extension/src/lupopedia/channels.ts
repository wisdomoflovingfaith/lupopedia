/**
 * Channel messaging — send and receive messages in Lupopedia channels.
 *
 * Endpoints used:
 *   POST /channels/{id}/messages
 *   GET  /channels/{id}/messages
 *
 * @module lupopedia/channels
 */

import { lupoGet, lupoPost } from './client';
import { ActorIdentity } from './identity';

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

export interface SendMessageRequest {
    actor_id: number;
    actor_name: string;
    actor_type: string;
    body: string;
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
 * The actor identity is automatically included in the payload.
 */
export async function sendMessage(
    baseUrl: string,
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
        ...(meta ? { meta } : {}),
    };

    const res = await lupoPost<SendMessageResponse>(
        baseUrl,
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
 *
 * @param since - Optional UTC timestamp (YYYYMMDDHHmmss) to only fetch
 *                messages after a given point.
 */
export async function getMessages(
    baseUrl: string,
    channelId: number,
    since?: string
): Promise<ChannelMessage[]> {
    const params = since ? `?since=${encodeURIComponent(since)}` : '';
    const res = await lupoGet<GetMessagesResponse>(
        baseUrl,
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
    identity: ActorIdentity
): Promise<void> {
    await sendMessage(
        baseUrl,
        channelId,
        `[JOIN] ${identity.actor_name} joined channel ${channelId}.`,
        identity,
        { event: 'join', actor_type: identity.actor_type }
    );
}
