/**
 * Channel Viewer Webview Panel
 *
 * Displays a live thread of messages from a Lupopedia channel.
 * Polls GET /channels/{id}/messages every 5 seconds.
 *
 * @module webviews/channelViewer
 */

import * as vscode from 'vscode';
import { ChannelMessage, getMessages, sendMessage } from '../lupopedia/channels';
import { ActorIdentity } from '../lupopedia/identity';

export class ChannelViewerPanel {
    public static currentPanel: ChannelViewerPanel | undefined;
    private static readonly _viewType = 'lupopediaChannel';

    private readonly _panel: vscode.WebviewPanel;
    private readonly _extensionUri: vscode.Uri;
    private _disposables: vscode.Disposable[] = [];
    private _pollTimer: NodeJS.Timeout | undefined;

    private _channelId: number;
    private _baseUrl: string;
    private _identity: ActorIdentity;
    private _messages: ChannelMessage[];
    private _lastTimestamp: string | undefined;

    public static createOrShow(
        extensionUri: vscode.Uri,
        channelId: number,
        initialMessages: ChannelMessage[],
        baseUrl: string,
        identity: ActorIdentity
    ): void {
        const column = vscode.ViewColumn.Beside;

        if (ChannelViewerPanel.currentPanel) {
            ChannelViewerPanel.currentPanel._panel.reveal(column);
            ChannelViewerPanel.currentPanel._update(initialMessages);
            return;
        }

        const panel = vscode.window.createWebviewPanel(
            ChannelViewerPanel._viewType,
            `Lupopedia: Channel ${channelId}`,
            column,
            {
                enableScripts: true,
                localResourceRoots: [vscode.Uri.joinPath(extensionUri, 'media')],
            }
        );

        ChannelViewerPanel.currentPanel = new ChannelViewerPanel(
            panel,
            extensionUri,
            channelId,
            initialMessages,
            baseUrl,
            identity
        );
    }

    private constructor(
        panel: vscode.WebviewPanel,
        extensionUri: vscode.Uri,
        channelId: number,
        initialMessages: ChannelMessage[],
        baseUrl: string,
        identity: ActorIdentity
    ) {
        this._panel = panel;
        this._extensionUri = extensionUri;
        this._channelId = channelId;
        this._messages = initialMessages;
        this._baseUrl = baseUrl;
        this._identity = identity;

        this._update(initialMessages);

        this._panel.onDidDispose(() => this._dispose(), null, this._disposables);

        // Handle messages from the webview (e.g. user sends a message)
        this._panel.webview.onDidReceiveMessage(
            async (message: { command: string; text?: string }) => {
                if (message.command === 'sendMessage' && message.text) {
                    try {
                        await sendMessage(this._baseUrl, this._channelId, message.text, this._identity);
                        await this._poll(); // Immediately refresh
                    } catch (err) {
                        vscode.window.showErrorMessage(`Lupopedia: Send failed — ${String(err)}`);
                    }
                }
            },
            null,
            this._disposables
        );

        // Start 5-second polling
        this._pollTimer = setInterval(() => this._poll(), 5000);
    }

    private async _poll(): Promise<void> {
        try {
            const newMessages = await getMessages(
                this._baseUrl,
                this._channelId,
                this._lastTimestamp
            );
            if (newMessages.length > 0) {
                this._messages = this._messages.concat(newMessages);
                // Track last timestamp for incremental fetches
                const created = newMessages[newMessages.length - 1].created_at;
                if (created) {
                    this._lastTimestamp = created;
                }
                this._update(this._messages);
            }
        } catch {
            // Silently ignore poll errors (server may be transiently unavailable)
        }
    }

    private _update(messages: ChannelMessage[]): void {
        this._panel.title = `Lupopedia: Channel ${this._channelId}`;
        this._panel.webview.html = this._getHtml(messages);
    }

    private _getHtml(messages: ChannelMessage[]): string {
        const nonce = getNonce();

        const messageItems = messages
            .map(
                (m) => `
        <div class="message">
          <div class="meta">
            <span class="actor">${esc(m.actor_name ?? `Actor #${m.actor_id}`)}</span>
            <span class="type">${esc(m.actor_type ?? '')}</span>
            <span class="ts">${esc(m.created_at ?? '')}</span>
          </div>
          <div class="body">${esc(m.body)}</div>
        </div>`
            )
            .join('');

        return /* html */ `<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Security-Policy" content="default-src 'none'; style-src 'nonce-${nonce}'; script-src 'nonce-${nonce}';">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lupopedia Channel ${this._channelId}</title>
  <style nonce="${nonce}">
    :root {
      --bg: var(--vscode-editor-background);
      --fg: var(--vscode-editor-foreground);
      --border: var(--vscode-panel-border);
      --input-bg: var(--vscode-input-background);
      --input-fg: var(--vscode-input-foreground);
      --btn-bg: var(--vscode-button-background);
      --btn-fg: var(--vscode-button-foreground);
      --meta-fg: var(--vscode-descriptionForeground);
    }
    body { font-family: var(--vscode-font-family); background: var(--bg); color: var(--fg); margin: 0; padding: 0; display: flex; flex-direction: column; height: 100vh; }
    h1 { font-size: 1rem; padding: 10px 16px; margin: 0; border-bottom: 1px solid var(--border); }
    #thread { flex: 1; overflow-y: auto; padding: 12px 16px; display: flex; flex-direction: column; gap: 10px; }
    .message { border-left: 3px solid var(--btn-bg); padding: 8px 10px; border-radius: 3px; background: rgba(255,255,255,0.03); }
    .meta { font-size: 0.78rem; color: var(--meta-fg); margin-bottom: 4px; display: flex; gap: 8px; }
    .actor { font-weight: 600; color: var(--btn-bg); }
    .body { font-size: 0.9rem; white-space: pre-wrap; word-break: break-word; }
    #compose { display: flex; gap: 8px; padding: 10px 16px; border-top: 1px solid var(--border); }
    #msg-input { flex: 1; background: var(--input-bg); color: var(--input-fg); border: 1px solid var(--border); padding: 6px 8px; border-radius: 3px; font-family: inherit; font-size: 0.9rem; resize: none; }
    #send-btn { background: var(--btn-bg); color: var(--btn-fg); border: none; padding: 6px 14px; border-radius: 3px; cursor: pointer; font-family: inherit; }
    #send-btn:hover { opacity: 0.85; }
    .empty { color: var(--meta-fg); font-style: italic; text-align: center; margin-top: 40px; }
  </style>
</head>
<body>
  <h1>📡 Channel ${this._channelId} — Live Thread</h1>
  <div id="thread">
    ${messageItems || '<p class="empty">No messages yet. Be the first to say hello!</p>'}
  </div>
  <div id="compose">
    <textarea id="msg-input" rows="2" placeholder="Type a message…"></textarea>
    <button id="send-btn">Send</button>
  </div>
  <script nonce="${nonce}">
    const vscode = acquireVsCodeApi();
    const btn = document.getElementById('send-btn');
    const input = document.getElementById('msg-input');
    const thread = document.getElementById('thread');

    function scrollBottom() { thread.scrollTop = thread.scrollHeight; }
    scrollBottom();

    btn.addEventListener('click', () => {
      const text = input.value.trim();
      if (!text) return;
      vscode.postMessage({ command: 'sendMessage', text });
      input.value = '';
    });
    input.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); btn.click(); }
    });
  </script>
</body>
</html>`;
    }

    private _dispose(): void {
        ChannelViewerPanel.currentPanel = undefined;
        if (this._pollTimer) {
            clearInterval(this._pollTimer);
        }
        this._panel.dispose();
        while (this._disposables.length) {
            const d = this._disposables.pop();
            if (d) { d.dispose(); }
        }
    }
}

function getNonce(): string {
    let text = '';
    const possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    for (let i = 0; i < 32; i++) {
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }
    return text;
}

function esc(str: string): string {
    return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');
}
