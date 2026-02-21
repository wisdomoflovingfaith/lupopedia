/**
 * Channel Viewer Webview — displays channel 42 messages with:
 *   • Actor roster sidebar (self + all external actors from registry)
 *   • Per-actor colour-coded message attribution
 *   • Live 5-second polling for new messages
 *   • Compose box to send replies
 *
 * @module webviews/channelViewer
 */

import * as vscode from 'vscode';
import { ChannelMessage, sendMessage } from '../lupopedia/channels';
import { ActorIdentity, actorColor } from '../lupopedia/identity';

// ─── Nonce helper ─────────────────────────────────────────────────────────────

function getNonce(): string {
    let text = '';
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    for (let i = 0; i < 32; i++) {
        text += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    return text;
}

// ─── Panel ────────────────────────────────────────────────────────────────────

export class ChannelViewerPanel {
    public static currentPanel: ChannelViewerPanel | undefined;
    private static readonly _viewType = 'lupopediaChannel';

    private readonly _panel: vscode.WebviewPanel;
    private readonly _extensionUri: vscode.Uri;
    private _disposables: vscode.Disposable[] = [];
    private _pollTimer: ReturnType<typeof setInterval> | undefined;

    private _channelId: number;
    private _baseUrl: string;
    private _self: ActorIdentity;
    private _roster: ActorIdentity[];
    private _messages: ChannelMessage[];
    private _lastTimestamp: string | undefined;

    // ─── Factory ───────────────────────────────────────────────────────────────

    public static createOrShow(
        extensionUri: vscode.Uri,
        channelId: number,
        initialMessages: ChannelMessage[],
        baseUrl: string,
        self: ActorIdentity,
        roster: ActorIdentity[]
    ): void {
        const column = vscode.window.activeTextEditor
            ? vscode.ViewColumn.Beside
            : vscode.ViewColumn.One;

        if (ChannelViewerPanel.currentPanel) {
            ChannelViewerPanel.currentPanel._panel.reveal(column);
            ChannelViewerPanel.currentPanel._update(initialMessages);
            return;
        }

        const panel = vscode.window.createWebviewPanel(
            ChannelViewerPanel._viewType,
            `Lupopedia ─ Channel ${channelId}`,
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
            self,
            roster
        );
    }

    // ─── Constructor ───────────────────────────────────────────────────────────

    private constructor(
        panel: vscode.WebviewPanel,
        extensionUri: vscode.Uri,
        channelId: number,
        messages: ChannelMessage[],
        baseUrl: string,
        self: ActorIdentity,
        roster: ActorIdentity[]
    ) {
        this._panel = panel;
        this._extensionUri = extensionUri;
        this._channelId = channelId;
        this._messages = messages;
        this._baseUrl = baseUrl;
        this._self = self;
        this._roster = roster;

        // Track last message timestamp for incremental fetches
        if (messages.length > 0) {
            this._lastTimestamp = messages[messages.length - 1].created_at;
        }

        this._update(this._messages);
        this._startPolling();

        // Handle messages from webview JS
        this._panel.webview.onDidReceiveMessage(
            async (msg: { type: string; text?: string }) => {
                if (msg.type === 'send' && msg.text?.trim()) {
                    try {
                        await sendMessage(this._baseUrl, this._channelId, msg.text.trim(), this._self);
                    } catch {
                        vscode.window.showErrorMessage('Lupopedia: Failed to send message.');
                    }
                }
            },
            null,
            this._disposables
        );

        this._panel.onDidDispose(() => this.dispose(), null, this._disposables);
    }

    // ─── Polling ───────────────────────────────────────────────────────────────

    private _startPolling(): void {
        this._pollTimer = setInterval(() => void this._poll(), 5000);
    }

    private async _poll(): Promise<void> {
        try {
            const { getMessages } = await import('../lupopedia/channels');
            const newMessages = await getMessages(
                this._baseUrl,
                this._channelId,
                this._lastTimestamp
            );
            if (newMessages.length > 0) {
                this._messages = this._messages.concat(newMessages);
                const last = newMessages[newMessages.length - 1].created_at;
                if (last) { this._lastTimestamp = last; }
                this._update(this._messages);
            }
        } catch {
            // Silently ignore poll errors
        }
    }

    // ─── Render ────────────────────────────────────────────────────────────────

    private _update(messages: ChannelMessage[]): void {
        if (!this._panel.visible) { return; }
        this._panel.webview.html = this._getHtml(messages);
    }

    private _getHtml(messages: ChannelMessage[]): string {
        const nonce = getNonce();

        // ── Actor roster HTML ──────────────────────────────────────────────────
        const rosterHtml = this._roster
            .map((a) => {
                const color = actorColor(a.actor_name);
                const isSelf = a.actor_id === this._self.actor_id;
                return /* html */ `
          <div class="actor-chip ${isSelf ? 'self' : ''}" style="--actor-col:${color}" title="actor_id: ${a.actor_id} | ${a.actor_type}">
            <span class="dot"></span>
            <span class="name">${esc(a.actor_name)}</span>
            <span class="id">#${a.actor_id}</span>
          </div>`;
            })
            .join('\n');

        // ── Messages HTML ──────────────────────────────────────────────────────
        const msgsHtml = messages.length === 0
            ? `<p class="empty">No messages yet. Be the first to write!</p>`
            : messages
                .map((m) => {
                    const name = m.actor_name ?? `Actor #${m.actor_id}`;
                    const color = actorColor(name);
                    const isSelf = m.actor_id === this._self.actor_id;
                    const ts = m.created_at
                        ? new Date(m.created_at).toLocaleTimeString()
                        : '';
                    return /* html */ `
              <div class="message ${isSelf ? 'mine' : 'theirs'}">
                <div class="msg-meta" style="--actor-col:${color}">
                  <span class="dot"></span>
                  <strong>${esc(name)}</strong>
                  <span class="ts">${ts}</span>
                </div>
                <div class="msg-body">${esc(m.body ?? '')}</div>
              </div>`;
                })
                .join('\n');

        return /* html */ `<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Security-Policy"
        content="default-src 'none'; style-src 'nonce-${nonce}'; script-src 'nonce-${nonce}';">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Lupopedia Channel ${this._channelId}</title>
  <style nonce="${nonce}">
    :root {
      --bg:          var(--vscode-editor-background);
      --fg:          var(--vscode-editor-foreground);
      --border:      var(--vscode-panel-border);
      --input-bg:    var(--vscode-input-background);
      --input-fg:    var(--vscode-input-foreground);
      --btn-bg:      var(--vscode-button-background);
      --btn-fg:      var(--vscode-button-foreground);
      --meta-fg:     var(--vscode-descriptionForeground);
      --sidebar-w:   200px;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      display: flex; height: 100vh; overflow: hidden;
      font-family: var(--vscode-font-family);
      background: var(--bg); color: var(--fg);
    }

    /* ── Actor Roster Sidebar ─────────────────── */
    .roster {
      width: var(--sidebar-w); min-width: 160px;
      border-right: 1px solid var(--border);
      padding: 12px 10px; overflow-y: auto;
      flex-shrink: 0;
    }
    .roster h3 {
      font-size: 0.7rem; text-transform: uppercase;
      letter-spacing: .08em; color: var(--meta-fg);
      margin-bottom: 10px;
    }
    .actor-chip {
      display: flex; align-items: center; gap: 6px;
      padding: 6px 8px; border-radius: 6px;
      margin-bottom: 6px;
      border: 1px solid color-mix(in srgb, var(--actor-col) 40%, transparent);
      background: color-mix(in srgb, var(--actor-col) 10%, transparent);
    }
    .actor-chip.self { outline: 1px solid var(--actor-col); }
    .dot {
      width: 8px; height: 8px; border-radius: 50%;
      background: var(--actor-col); flex-shrink: 0;
    }
    .actor-chip .name { font-size: 0.78rem; flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .actor-chip .id   { font-size: 0.7rem; color: var(--meta-fg); }

    /* ── Main column ─────────────────────────── */
    .main {
      flex: 1; display: flex; flex-direction: column; overflow: hidden;
    }
    .header {
      padding: 10px 14px; border-bottom: 1px solid var(--border);
      font-size: 0.85rem; color: var(--meta-fg);
    }
    .messages {
      flex: 1; overflow-y: auto; padding: 12px 14px;
      display: flex; flex-direction: column; gap: 10px;
    }
    .empty { color: var(--meta-fg); font-size: 0.85rem; text-align: center; margin-top: 40px; }
    .message { max-width: 90%; }
    .message.mine  { align-self: flex-end; }
    .message.theirs { align-self: flex-start; }
    .msg-meta {
      display: flex; align-items: center; gap: 5px;
      font-size: 0.72rem; color: var(--meta-fg); margin-bottom: 3px;
    }
    .msg-meta .dot { background: var(--actor-col); }
    .msg-meta strong { color: var(--actor-col); }
    .msg-meta .ts { margin-left: auto; font-size: 0.68rem; }
    .msg-body {
      padding: 7px 10px; border-radius: 5px;
      background: var(--input-bg);
      font-size: 0.85rem; line-height: 1.45;
      white-space: pre-wrap; word-break: break-word;
    }
    .mine .msg-body { background: color-mix(in srgb, #7c6af7 15%, var(--input-bg)); }

    /* ── Compose ───────────────────────────────  */
    .compose {
      display: flex; gap: 8px; padding: 10px 14px;
      border-top: 1px solid var(--border);
    }
    textarea {
      flex: 1; resize: none; height: 54px;
      background: var(--input-bg); color: var(--input-fg);
      border: 1px solid var(--border);
      border-radius: 4px; padding: 8px;
      font-family: inherit; font-size: 0.85rem;
    }
    button {
      background: var(--btn-bg); color: var(--btn-fg);
      border: none; padding: 0 18px; border-radius: 4px;
      cursor: pointer; font-family: inherit; font-size: 0.85rem;
      align-self: stretch;
    }
    button:hover { opacity: 0.85; }
  </style>
</head>
<body>

  <!-- Actor Roster Sidebar -->
  <aside class="roster">
    <h3>Actors in channel ${this._channelId}</h3>
    ${rosterHtml}
  </aside>

  <!-- Main Chat Column -->
  <div class="main">
    <div class="header">
      Channel <strong>${this._channelId}</strong> &nbsp;·&nbsp;
      ${messages.length} message${messages.length !== 1 ? 's' : ''}
      &nbsp;·&nbsp; auto-refreshing every 5 s
    </div>
    <div class="messages" id="msgs">
      ${msgsHtml}
    </div>
    <div class="compose">
      <textarea id="txt" placeholder="Message as ${esc(this._self.actor_name)} (#${this._self.actor_id})…"></textarea>
      <button id="btn">Send</button>
    </div>
  </div>

  <script nonce="${nonce}">
    const vscode = acquireVsCodeApi();
    const btn = document.getElementById('btn');
    const txt = document.getElementById('txt');
    const msgs = document.getElementById('msgs');

    // Scroll to bottom on load
    msgs.scrollTop = msgs.scrollHeight;

    btn.addEventListener('click', () => {
      const text = txt.value.trim();
      if (!text) return;
      vscode.postMessage({ type: 'send', text });
      txt.value = '';
    });

    txt.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        btn.click();
      }
    });
  </script>
</body>
</html>`;
    }

    // ─── Lifecycle ─────────────────────────────────────────────────────────────

    public dispose(): void {
        if (this._pollTimer !== undefined) {
            clearInterval(this._pollTimer);
        }
        ChannelViewerPanel.currentPanel = undefined;
        this._panel.dispose();
        while (this._disposables.length) {
            const d = this._disposables.pop();
            if (d) { d.dispose(); }
        }
    }
}

// ─── HTML escape helper (shared by roster + messages) ─────────────────────────

function esc(str: string): string {
    return str
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}
