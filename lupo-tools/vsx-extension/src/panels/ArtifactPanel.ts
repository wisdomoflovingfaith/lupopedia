import * as vscode from 'vscode';
import { LupopediaHeaderV2, LupopediaHeaderV3, LupopediaFooterV2, LupopediaFooterV3 } from '../lupopedia/headers/parser/types';
import * as path from 'path';

export class ArtifactPanel {
    public static currentPanel: ArtifactPanel | undefined;
    private readonly _panel: vscode.WebviewPanel;
    private _disposables: vscode.Disposable[] = [];

    private constructor(panel: vscode.WebviewPanel, extensionUri: vscode.Uri) {
        this._panel = panel;
        this._panel.onDidDispose(() => this.dispose(), null, this._disposables);
        this._panel.webview.onDidReceiveMessage(
            message => {
                switch (message.command) {
                    case 'openLink':
                        if (message.text.startsWith('http')) {
                            vscode.env.openExternal(vscode.Uri.parse(message.text));
                        } else {
                            // Assume it's a file path or artifact ID
                            vscode.commands.executeCommand('lupopedia.showArtifactDetails', message.text);
                        }
                        return;
                }
            },
            null,
            this._disposables
        );
    }

    public static createOrShow(extensionUri: vscode.Uri, artifact: { filePath: string, header: LupopediaHeaderV2 | LupopediaHeaderV3, footer?: LupopediaFooterV2 | LupopediaFooterV3 }) {
        const column = vscode.window.activeTextEditor ? vscode.window.activeTextEditor.viewColumn : undefined;

        if (ArtifactPanel.currentPanel) {
            ArtifactPanel.currentPanel._panel.reveal(column);
            ArtifactPanel.currentPanel._update(artifact);
            return;
        }

        const title = 'identity' in artifact.header ? artifact.header.identity.agent_slug : (artifact.header as LupopediaHeaderV2).lupopedia.headers.file_path_from_root;

        const panel = vscode.window.createWebviewPanel(
            'artifactViewer',
            `Artifact: ${path.basename(title)}`,
            column || vscode.ViewColumn.One,
            { enableScripts: true }
        );

        ArtifactPanel.currentPanel = new ArtifactPanel(panel, extensionUri);
        ArtifactPanel.currentPanel._update(artifact);
    }

    private _update(artifact: { filePath: string, header: LupopediaHeaderV2 | LupopediaHeaderV3, footer?: LupopediaFooterV2 | LupopediaFooterV3 }) {
        const title = 'identity' in artifact.header ? artifact.header.identity.agent_slug : (artifact.header as LupopediaHeaderV2).lupopedia.headers.file_path_from_root;
        this._panel.title = `Artifact: ${path.basename(title)}`;
        this._panel.webview.html = this._getHtmlForWebview(artifact);
    }

    public dispose() {
        ArtifactPanel.currentPanel = undefined;
        this._panel.dispose();
        while (this._disposables.length) {
            const x = this._disposables.pop();
            if (x) x.dispose();
        }
    }

    private _getHtmlForWebview(artifact: { filePath: string, header: LupopediaHeaderV2 | LupopediaHeaderV3, footer?: LupopediaFooterV2 | LupopediaFooterV3 }) {
        let moodRgb = '666666';
        let title = artifact.filePath;
        let type = 'file';
        let kind = '';

        if ('identity' in artifact.header) {
            const h = artifact.header as LupopediaHeaderV3;
            moodRgb = '569cd6';
            title = h.identity.agent_slug;
            type = h.classification.artifact_type;
            kind = h.classification.artifact_kind;
        } else {
            const h = artifact.header as LupopediaHeaderV2;
            moodRgb = h.lupopedia.headers.mood_rgb || '666666';
            title = h.lupopedia.headers.file_path_from_root;
            type = h.lupopedia.headers.artifact_type || 'file';
            kind = h.lupopedia.headers.artifact_kind || '';
        }

        const renderV3Header = () => {
            const h = artifact.header as LupopediaHeaderV3;
            return `
            <div class="section">
                <div class="section-title">Identity Layer (v3)</div>
                <div class="grid">
                    <div class="label">Agent Slug:</div><div class="value">${h.identity.agent_slug}</div>
                    <div class="label">Delegation Path:</div><div class="value" style="color: #4fc1ff; font-weight: bold;">${(h.identity.delegation_chain || `${h.identity.execution_agent}:${h.identity.intent_authority}`).split(':').join(' → ')}</div>
                    <div class="label">Exec Agent:</div><div class="value">${h.identity.execution_agent}</div>
                    <div class="label">Intent Auth:</div><div class="value">${h.identity.intent_authority}</div>
                    <div class="label">Type:</div><div class="value">${h.identity.agent_type}</div>
                    <div class="label">System Version:</div><div class="value">${h.identity.system_version}</div>
                </div>
            </div>
            <div class="section">
                <div class="section-title">Classification Layer</div>
                <div class="grid">
                    <div class="label">Kind:</div><div class="value">${h.classification.artifact_kind}</div>
                    <div class="label">Type:</div><div class="value">${h.classification.artifact_type}</div>
                    <div class="label">Traits:</div><div class="value">${(h.classification.traits || []).join(', ') || 'None'}</div>
                </div>
            </div>`;
        };

        const renderV2Header = () => {
            const h = (artifact.header as LupopediaHeaderV2).lupopedia.headers;
            return `
            <div class="section">
                <div class="section-title">Core Identity (v2)</div>
                <div class="grid">
                    <div class="label">System Version:</div><div class="value">${h.system_version}</div>
                    <div class="label">Delegation Path:</div><div class="value" style="color: #4fc1ff; font-weight: bold;">${(h.delegation_chain || h.x_lupo_forwarded || 'No Chain').split(':').join(' → ')}</div>
                    <div class="label">Last Modified:</div><div class="value">${h.last_modified}</div>
                    <div class="label">Channel ID:</div><div class="value">${h.channel_id || 'N/A'}</div>
                    <div class="label">Actor ID:</div><div class="value">${h.actor_id || 'N/A'}</div>
                </div>
            </div>`;
        };

        const renderFooter = () => {
            if (!artifact.footer) return '';

            if ('relations' in artifact.footer) {
                const f = artifact.footer as LupopediaFooterV3;
                return `
                <div class="section">
                    <div class="section-title">Relations Layer (v3)</div>
                    <div class="grid">
                        <div class="label">Inbound:</div>
                        <div class="value">
                            ${f.relations.inbound ? `<ul>${f.relations.inbound.map(e => `<li class="link">${e.source} (${e.type})</li>`).join('')}</ul>` : 'None'}
                        </div>
                        <div class="label">Outbound:</div>
                        <div class="value">
                            ${f.relations.outbound ? `<ul>${f.relations.outbound.map(e => `<li class="link">${e.target} (${e.type})</li>`).join('')}</ul>` : 'None'}
                        </div>
                    </div>
                </div>
                ${f.collections ? `
                <div class="section">
                    <div class="section-title">Collections (Semantic)</div>
                    <ul>
                        ${f.collections.map(c => `<li><b>${c.id}</b>: [${(c.traits || []).join(', ')}]</li>`).join('')}
                    </ul>
                </div>` : ''}`;
            } else {
                const f = (artifact.footer as LupopediaFooterV2).lupopedia.footer;
                return `
                <div class="section">
                    <div class="section-title">Semantic Context (v2)</div>
                    <div class="grid">
                        <div class="label">Inbound Edges:</div>
                        <div class="value">
                            ${f.inbound_edges && f.inbound_edges.length > 0 ? `<ul>${f.inbound_edges.map(e => `<li>${e}</li>`).join('')}</ul>` : 'None'}
                        </div>
                    </div>
                </div>`;
            }
        };

        return `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artifact Details</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
        .header-bar { border-left: 5px solid #${moodRgb}; padding-left: 15px; margin-bottom: 25px; }
        h1 { margin: 0; color: #ffffff; font-size: 1.5rem; }
        .type-tag { display: inline-block; padding: 2px 8px; border-radius: 4px; background: #333; font-size: 0.8rem; margin-top: 5px; color: #${moodRgb}; border: 1px solid #${moodRgb}44; }
        .section { margin-bottom: 20px; background: #252526; padding: 15px; border-radius: 8px; border: 1px solid #333; }
        .section-title { font-size: 0.9rem; font-weight: bold; color: #569cd6; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px; }
        .grid { display: grid; grid-template-columns: 150px 1fr; gap: 8px; font-size: 0.9rem; }
        .label { color: #888; }
        .value { color: #ce9178; word-break: break-all; }
        .value.link { color: #4fc1ff; cursor: pointer; text-decoration: underline; }
        ul { margin: 0; padding-left: 20px; }
        li { margin-bottom: 4px; }
        .footer-info { font-size: 0.8rem; color: #666; margin-top: 30px; border-top: 1px solid #333; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header-bar">
        <h1>${path.basename(title)}</h1>
        <div class="type-tag">${type}</div>
        ${kind ? `<div class="type-tag" style="margin-left: 10px; border-color: #888;">kind: ${kind}</div>` : ''}
    </div>

    ${'identity' in artifact.header ? renderV3Header() : renderV2Header()}
    ${renderFooter()}

    <div class="footer-info">
        Full Path: ${artifact.filePath}
    </div>

    <script>
        const vscode = acquireVsCodeApi();
        document.querySelectorAll('.link').forEach(link => {
            link.addEventListener('click', () => {
                const text = link.innerText.split('(')[0].trim();
                vscode.postMessage({ command: 'openLink', text });
            });
        });
    </script>
</body>
</html>`;
    }
}
