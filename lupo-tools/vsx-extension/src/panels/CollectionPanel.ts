import * as vscode from 'vscode';
import { CollectionInfo } from '../lupopedia/collections';
import { ArtifactRecord } from '../lupopedia/flip/storage/ArtifactIndex';

export class CollectionPanel {
    public static currentPanel: CollectionPanel | undefined;
    private readonly _panel: vscode.WebviewPanel;
    private _disposables: vscode.Disposable[] = [];

    private constructor(panel: vscode.WebviewPanel, extensionUri: vscode.Uri) {
        this._panel = panel;
        this._panel.onDidDispose(() => this.dispose(), null, this._disposables);
        this._panel.webview.onDidReceiveMessage(
            message => {
                switch (message.command) {
                    case 'openArtifact':
                        vscode.commands.executeCommand('lupopedia.showArtifactDetails', message.path);
                        return;
                }
            },
            null,
            this._disposables
        );
    }

    public static createOrShow(extensionUri: vscode.Uri, collection: CollectionInfo, artifacts: ArtifactRecord[]) {
        const column = vscode.window.activeTextEditor ? vscode.window.activeTextEditor.viewColumn : undefined;

        if (CollectionPanel.currentPanel) {
            CollectionPanel.currentPanel._panel.reveal(column);
            CollectionPanel.currentPanel._update(collection, artifacts);
            return;
        }

        const panel = vscode.window.createWebviewPanel(
            'collectionViewer',
            `Collection: ${collection.title}`,
            column || vscode.ViewColumn.One,
            { enableScripts: true }
        );

        CollectionPanel.currentPanel = new CollectionPanel(panel, extensionUri);
        CollectionPanel.currentPanel._update(collection, artifacts);
    }

    private _update(collection: CollectionInfo, artifacts: ArtifactRecord[]) {
        this._panel.title = `Collection: ${collection.title}`;
        this._panel.webview.html = this._getHtmlForWebview(collection, artifacts);
    }

    public dispose() {
        CollectionPanel.currentPanel = undefined;
        this._panel.dispose();
        while (this._disposables.length) {
            const x = this._disposables.pop();
            if (x) x.dispose();
        }
    }

    private _getHtmlForWebview(collection: CollectionInfo, artifacts: ArtifactRecord[]) {
        return `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collection Details</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; padding: 20px; background: #1e1e1e; color: #d4d4d4; }
        .header { margin-bottom: 25px; border-bottom: 1px solid #333; padding-bottom: 15px; }
        h1 { margin: 0; color: #ffffff; font-size: 1.5rem; }
        .description { color: #888; font-size: 0.9rem; margin-top: 5px; }
        .metadata { font-size: 0.8rem; color: #569cd6; margin-top: 5px; }
        .list { margin-top: 20px; }
        .item { background: #252526; padding: 12px; border-radius: 6px; margin-bottom: 10px; border: 1px solid #333; cursor: pointer; transition: background 0.2s; }
        .item:hover { background: #2d2d30; border-color: #007acc; }
        .item-title { font-weight: bold; color: #dcdcaa; margin-bottom: 3px; }
        .item-meta { font-size: 0.8rem; color: #666; display: flex; gap: 15px; }
        .badge { background: #333; padding: 1px 6px; border-radius: 3px; color: #aaa; }
    </style>
</head>
<body>
    <div class="header">
        <h1>${collection.title}</h1>
        <div class="metadata">ID: ${collection.id} | Defined in: ${collection.filePath}</div>
        ${collection.description ? `<div class="description">${collection.description}</div>` : ''}
    </div>

    <div class="list">
        <h3>Artifacts in this collection (${artifacts.length})</h3>
        ${artifacts.length === 0 ? '<p>No artifacts found in this collection.</p>' :
                artifacts.map(a => `
            <div class="item" onclick="openArtifact('${a.id.replace(/\\/g, '\\\\')}')">
                <div class="item-title">${a.id}</div>
                <div class="item-meta">
                    <span>Type: <span class="badge">${a.artifactType || 'file'}</span></span>
                    <span>Version: ${a.version}</span>
                    <span>Modified: ${a.lastModified}</span>
                </div>
            </div>
            `).join('')
            }
    </div>

    <script>
        const vscode = acquireVsCodeApi();
        function openArtifact(path) {
            vscode.postMessage({ command: 'openArtifact', path });
        }
    </script>
</body>
</html>`;
    }
}
