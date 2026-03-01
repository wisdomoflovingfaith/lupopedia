/**
 * Semantic Viewer Webview Panel
 *
 * Displays semantic API results (explanations, related atoms, paths)
 * in a structured, readable card layout.
 *
 * @module webviews/semanticViewer
 */

import * as vscode from 'vscode';

export class SemanticViewerPanel {
    public static currentPanel: SemanticViewerPanel | undefined;
    private static readonly _viewType = 'lupopediaSemantic';

    private readonly _panel: vscode.WebviewPanel;
    private readonly _extensionUri: vscode.Uri;
    private _disposables: vscode.Disposable[] = [];

    public static createOrShow(
        extensionUri: vscode.Uri,
        title: string,
        data: unknown
    ): void {
        const column = vscode.ViewColumn.Beside;
        if (SemanticViewerPanel.currentPanel) {
            SemanticViewerPanel.currentPanel._panel.reveal(column);
            SemanticViewerPanel.currentPanel._update(title, data);
            return;
        }
        const panel = vscode.window.createWebviewPanel(
            SemanticViewerPanel._viewType,
            `Lupopedia: ${title}`,
            column,
            { enableScripts: true }
        );
        SemanticViewerPanel.currentPanel = new SemanticViewerPanel(panel, extensionUri, title, data);
    }

    private constructor(
        panel: vscode.WebviewPanel,
        extensionUri: vscode.Uri,
        title: string,
        data: unknown
    ) {
        this._panel = panel;
        this._extensionUri = extensionUri;
        this._update(title, data);
        this._panel.onDidDispose(() => this._dispose(), null, this._disposables);
    }

    private _update(title: string, data: unknown): void {
        this._panel.title = `Lupopedia: ${title}`;
        this._panel.webview.html = this._getHtml(title, data);
    }

    private _getHtml(title: string, data: unknown): string {
        const nonce = getNonce();
        const json = JSON.stringify(data, null, 2);

        // Build human-readable cards based on data shape
        let cards = '';

        if (data && typeof data === 'object') {
            const d = data as Record<string, unknown>;

            if (typeof d['explanation'] === 'string') {
                cards += card('Explanation', `<p>${esc(d['explanation'])}</p>`);
            }
            if (Array.isArray(d['tags']) && d['tags'].length > 0) {
                cards += card(
                    'Tags',
                    (d['tags'] as string[]).map((t) => `<span class="tag">${esc(t)}</span>`).join('')
                );
            }
            if (typeof d['confidence'] === 'number') {
                const pct = Math.round((d['confidence'] as number) * 100);
                cards += card('Confidence', `<div class="bar-wrap"><div class="bar" style="width:${pct}%"></div><span>${pct}%</span></div>`);
            }
            if (Array.isArray(d['atoms'])) {
                const atoms = d['atoms'] as Array<Record<string, unknown>>;
                const rows = atoms
                    .map(
                        (a) =>
                            `<tr><td>${esc(String(a['content_id'] ?? ''))}</td><td>${esc(String(a['title'] ?? a['file_path'] ?? ''))}</td><td>${esc(String(Math.round(Number(a['score'] ?? 0) * 100) / 100))}</td></tr>`
                    )
                    .join('');
                cards += card(
                    'Related Atoms',
                    `<table><thead><tr><th>ID</th><th>Title / Path</th><th>Score</th></tr></thead><tbody>${rows}</tbody></table>`
                );
            }
            if (Array.isArray(d['paths'])) {
                const paths = d['paths'] as Array<Record<string, unknown>>;
                const rows = paths
                    .map(
                        (p) =>
                            `<tr><td>${esc(String(p['source_id'] ?? ''))}</td><td>${esc(String(p['target_id'] ?? ''))}</td><td>${esc(String(p['layer'] ?? ''))}</td><td>${esc(String(p['weight'] ?? ''))}</td></tr>`
                    )
                    .join('');
                cards += card(
                    'Semantic Paths',
                    `<table><thead><tr><th>Source</th><th>Target</th><th>Layer</th><th>Weight</th></tr></thead><tbody>${rows}</tbody></table>`
                );
            }
        }

        // Fallback: raw JSON
        if (!cards) {
            cards = card('Raw Response', `<pre>${esc(json)}</pre>`);
        }

        return /* html */ `<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Security-Policy" content="default-src 'none'; style-src 'nonce-${nonce}';">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lupopedia: ${esc(title)}</title>
  <style nonce="${nonce}">
    :root {
      --bg: var(--vscode-editor-background);
      --fg: var(--vscode-editor-foreground);
      --border: var(--vscode-panel-border);
      --card-bg: rgba(255,255,255,0.04);
      --btn-bg: var(--vscode-button-background);
      --meta-fg: var(--vscode-descriptionForeground);
    }
    body { font-family: var(--vscode-font-family); background: var(--bg); color: var(--fg); margin: 0; padding: 16px; }
    h1 { font-size: 1.1rem; margin: 0 0 16px; }
    .card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 5px; padding: 14px; margin-bottom: 14px; }
    .card h2 { font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.06em; color: var(--meta-fg); margin: 0 0 10px; }
    .tag { display: inline-block; background: var(--btn-bg); color: var(--vscode-button-foreground); border-radius: 3px; padding: 2px 7px; font-size: 0.78rem; margin: 2px 3px; }
    table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
    th, td { text-align: left; padding: 5px 8px; border-bottom: 1px solid var(--border); }
    th { color: var(--meta-fg); font-weight: 600; }
    .bar-wrap { display: flex; align-items: center; gap: 10px; }
    .bar { height: 8px; background: var(--btn-bg); border-radius: 4px; }
    pre { white-space: pre-wrap; word-break: break-word; font-size: 0.8rem; margin: 0; }
  </style>
</head>
<body>
  <h1>🔬 ${esc(title)}</h1>
  ${cards}
</body>
</html>`;
    }

    private _dispose(): void {
        SemanticViewerPanel.currentPanel = undefined;
        this._panel.dispose();
        while (this._disposables.length) {
            const d = this._disposables.pop();
            if (d) { d.dispose(); }
        }
    }
}

function card(heading: string, content: string): string {
    return `<div class="card"><h2>${heading}</h2>${content}</div>`;
}

function getNonce(): string {
    let text = '';
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    for (let i = 0; i < 32; i++) { text += chars[Math.floor(Math.random() * chars.length)]; }
    return text;
}

function esc(str: string): string {
    return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
