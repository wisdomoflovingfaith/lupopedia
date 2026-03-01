/**
 * FLIP Header Editor Webview Panel
 *
 * Shows parsed FLIP header fields in an editable form.
 * User can edit values and click "Validate" to re-check locally,
 * or "Fetch from Server" to call POST /semantic/flip-header.
 *
 * @module webviews/flipEditor
 */

import * as vscode from 'vscode';
import { FlipHeader, formatFlipHeader } from '../lupopedia/flip';
import { getFlipHeaderFromServer } from '../lupopedia/semantic';
import { ActorIdentity } from '../lupopedia/identity';

export class FlipEditorPanel {
    public static currentPanel: FlipEditorPanel | undefined;
    private static readonly _viewType = 'lupopediaFlip';

    private readonly _panel: vscode.WebviewPanel;
    private readonly _extensionUri: vscode.Uri;
    private readonly _baseUrl: string;
    private readonly _identity: ActorIdentity | null;
    private _disposables: vscode.Disposable[] = [];

    public static createOrShow(
        extensionUri: vscode.Uri,
        header: FlipHeader,
        baseUrl: string,
        identity: ActorIdentity | null
    ): void {
        const column = vscode.ViewColumn.Beside;
        if (FlipEditorPanel.currentPanel) {
            FlipEditorPanel.currentPanel._panel.reveal(column);
            FlipEditorPanel.currentPanel._update(header);
            return;
        }
        const panel = vscode.window.createWebviewPanel(
            FlipEditorPanel._viewType,
            'Lupopedia: FLIP Header Editor',
            column,
            { enableScripts: true }
        );
        FlipEditorPanel.currentPanel = new FlipEditorPanel(panel, extensionUri, header, baseUrl, identity);
    }

    private constructor(
        panel: vscode.WebviewPanel,
        extensionUri: vscode.Uri,
        header: FlipHeader,
        baseUrl: string,
        identity: ActorIdentity | null
    ) {
        this._panel = panel;
        this._extensionUri = extensionUri;
        this._baseUrl = baseUrl;
        this._identity = identity;

        this._update(header);

        this._panel.webview.onDidReceiveMessage(
            async (msg: { command: string; header?: FlipHeader; filePath?: string }) => {
                if (msg.command === 'fetchFromServer' && msg.filePath) {
                    try {
                        const result = await getFlipHeaderFromServer(this._baseUrl, {
                            file_path: msg.filePath,
                        });
                        vscode.window.showInformationMessage(
                            `Lupopedia: Server FLIP header fetched. Channel: ${result.channel_id ?? 'unresolved'}`
                        );
                    } catch (err) {
                        vscode.window.showErrorMessage(`FLIP fetch failed: ${String(err)}`);
                    }
                }
                if (msg.command === 'copyToClipboard' && msg.header) {
                    const text = formatFlipHeader(msg.header);
                    await vscode.env.clipboard.writeText(text);
                    vscode.window.showInformationMessage('Lupopedia: FLIP header copied to clipboard.');
                }
            },
            null,
            this._disposables
        );

        this._panel.onDidDispose(() => this._dispose(), null, this._disposables);
    }

    private _update(header: FlipHeader): void {
        this._panel.webview.html = this._getHtml(header);
    }

    private _getHtml(header: FlipHeader): string {
        const nonce = getNonce();
        const formatted = esc(formatFlipHeader(header));

        return /* html */ `<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Security-Policy" content="default-src 'none'; style-src 'nonce-${nonce}'; script-src 'nonce-${nonce}';">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lupopedia FLIP Header Editor</title>
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
      --ok: #4caf50;
      --err: #f44336;
    }
    body { font-family: var(--vscode-font-family); background: var(--bg); color: var(--fg); margin: 0; padding: 16px; }
    h1 { font-size: 1.05rem; margin: 0 0 16px; }
    label { display: block; font-size: 0.8rem; color: var(--meta-fg); margin-bottom: 3px; margin-top: 12px; }
    input { width: 100%; box-sizing: border-box; background: var(--input-bg); color: var(--input-fg); border: 1px solid var(--border); padding: 6px 8px; border-radius: 3px; font-family: inherit; font-size: 0.88rem; }
    .row { display: flex; gap: 8px; margin-top: 16px; flex-wrap: wrap; }
    button { background: var(--btn-bg); color: var(--btn-fg); border: none; padding: 7px 14px; border-radius: 3px; cursor: pointer; font-family: inherit; font-size: 0.88rem; }
    button:hover { opacity: 0.85; }
    button.secondary { background: transparent; border: 1px solid var(--border); color: var(--fg); }
    #status { margin-top: 12px; font-size: 0.82rem; }
    #status.ok { color: var(--ok); }
    #status.err { color: var(--err); }
    pre#preview { font-size: 0.78rem; background: var(--input-bg); border: 1px solid var(--border); border-radius: 3px; padding: 10px; margin-top: 16px; white-space: pre-wrap; word-break: break-word; }
    .badge { display: inline-block; padding: 2px 7px; border-radius: 3px; font-size: 0.76rem; margin-left: 8px; }
    .badge.ok  { background: var(--ok);  color: #fff; }
    .badge.err { background: var(--err); color: #fff; }
  </style>
</head>
<body>
  <h1>📋 FLIP Header Editor
    <span id="valid-badge" class="badge ok">valid</span>
  </h1>

  <label for="f-path">file_path_from_root</label>
  <input id="f-path" type="text" value="${esc(header.file_path_from_root)}">

  <label for="f-ver">file.last_modified_system_version</label>
  <input id="f-ver" type="text" value="${esc(header.file_last_modified_system_version)}">

  <label for="f-utc">file.last_modified_utc <span style="color:var(--meta-fg)">(14 digits: YYYYMMDDHHmmss)</span></label>
  <input id="f-utc" type="text" value="${esc(header.file_last_modified_utc)}" maxlength="14">

  <label for="f-ch">channel_id</label>
  <input id="f-ch" type="number" value="${header.channel_id ?? ''}">

  <div class="row">
    <button id="btn-validate">✓ Validate</button>
    <button id="btn-copy">⎘ Copy to Clipboard</button>
    <button class="secondary" id="btn-fetch">↓ Fetch from Server</button>
  </div>

  <div id="status"></div>

  <pre id="preview">${formatted}</pre>

  <script nonce="${nonce}">
    const vscode = acquireVsCodeApi();

    function getHeader() {
      return {
        file_path_from_root: document.getElementById('f-path').value.trim(),
        file_last_modified_system_version: document.getElementById('f-ver').value.trim(),
        file_last_modified_utc: document.getElementById('f-utc').value.trim(),
        channel_id: parseInt(document.getElementById('f-ch').value) || null,
        extras: {}
      };
    }

    function updatePreview() {
      const h = getHeader();
      const lines = [
        '---',
        '# FLIP Header (alias: Wolfie Header, CROP Header)',
        'wolfie.headers: explicit architecture with structured clarity for every file.',
        'file_path_from_root: ' + h.file_path_from_root,
        'file.last_modified_system_version: "' + h.file_last_modified_system_version + '"',
        'file.last_modified_utc: "' + h.file_last_modified_utc + '"',
        h.channel_id ? 'channel_id: ' + h.channel_id : '# channel_id: unresolved',
        '---'
      ];
      document.getElementById('preview').textContent = lines.join('\\n');
    }

    ['f-path','f-ver','f-utc','f-ch'].forEach(id => {
      document.getElementById(id).addEventListener('input', updatePreview);
    });

    document.getElementById('btn-validate').addEventListener('click', () => {
      const h = getHeader();
      const errors = [];
      if (!h.file_path_from_root) errors.push('file_path_from_root is required');
      if (!h.file_last_modified_system_version) errors.push('file.last_modified_system_version is required');
      if (!h.file_last_modified_utc) errors.push('file.last_modified_utc is required');
      if (h.file_last_modified_utc && !/^\\d{14}$/.test(h.file_last_modified_utc)) {
        errors.push('file.last_modified_utc must be 14 digits');
      }
      const status = document.getElementById('status');
      const badge = document.getElementById('valid-badge');
      if (errors.length === 0) {
        status.className = 'ok';
        status.textContent = '✓ FLIP header is valid.';
        badge.className = 'badge ok';
        badge.textContent = 'valid';
      } else {
        status.className = 'err';
        status.textContent = '✗ ' + errors.join(' | ');
        badge.className = 'badge err';
        badge.textContent = 'invalid';
      }
    });

    document.getElementById('btn-copy').addEventListener('click', () => {
      vscode.postMessage({ command: 'copyToClipboard', header: getHeader() });
    });

    document.getElementById('btn-fetch').addEventListener('click', () => {
      const h = getHeader();
      vscode.postMessage({ command: 'fetchFromServer', filePath: h.file_path_from_root });
    });
  </script>
</body>
</html>`;
    }

    private _dispose(): void {
        FlipEditorPanel.currentPanel = undefined;
        this._panel.dispose();
        while (this._disposables.length) {
            const d = this._disposables.pop();
            if (d) { d.dispose(); }
        }
    }
}

function getNonce(): string {
    let text = '';
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    for (let i = 0; i < 32; i++) { text += chars[Math.floor(Math.random() * chars.length)]; }
    return text;
}

function esc(str: string): string {
    return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
}
