// src/panels/DelegationPanel.ts

import * as vscode from 'vscode';
import { MetadataIndex } from '../lupopedia/headers/parser/types';
import { DelegationEngine } from '../lupopedia/headers/logic/DelegationEngine';

export class DelegationPanel {
    public static currentPanel: DelegationPanel | undefined;
    private readonly _panel: vscode.WebviewPanel;
    private _disposables: vscode.Disposable[] = [];

    private constructor(panel: vscode.WebviewPanel, extensionUri: vscode.Uri) {
        this._panel = panel;
        this._panel.onDidDispose(() => this.dispose(), null, this._disposables);
    }

    public static createOrShow(extensionUri: vscode.Uri, metadata: MetadataIndex, filePath: string) {
        const column = vscode.window.activeTextEditor ? vscode.window.activeTextEditor.viewColumn : undefined;

        if (DelegationPanel.currentPanel) {
            DelegationPanel.currentPanel._panel.reveal(column);
            DelegationPanel.currentPanel._update(metadata, filePath);
            return;
        }

        const panel = vscode.window.createWebviewPanel(
            'delegationInspector',
            'Delegation Inspector',
            column || vscode.ViewColumn.One,
            { enableScripts: true }
        );

        DelegationPanel.currentPanel = new DelegationPanel(panel, extensionUri);
        DelegationPanel.currentPanel._update(metadata, filePath);
    }

    private _update(metadata: MetadataIndex, filePath: string) {
        this._panel.webview.html = this._getHtmlForWebview(metadata, filePath);
    }

    public dispose() {
        DelegationPanel.currentPanel = undefined;
        this._panel.dispose();
        while (this._disposables.length) {
            const x = this._disposables.pop();
            if (x) x.dispose();
        }
    }

    private _getHtmlForWebview(metadata: MetadataIndex, filePath: string) {
        const validation = DelegationEngine.validate(metadata.delegationPath.join(':'), metadata.actorId);
        const chainHtml = metadata.delegationPath.map((id, idx) => {
            const isHuman = id >= 10000;
            const role = idx === 0 ? 'Executor' : (idx === metadata.delegationPath.length - 1 ? 'Principal' : 'Delegator');
            return `<div class="node ${isHuman ? 'human' : 'agent'}">
                <div class="role">${role}</div>
                <div class="id">${id}</div>
                <div class="type">${isHuman ? 'Human' : 'Agent'}</div>
            </div>`;
        }).join('<div class="arrow">→</div>');

        return `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; padding: 20px; color: #d4d4d4; background: #1e1e1e; }
        .chain { display: flex; align-items: center; gap: 10px; margin-bottom: 30px; overflow-x: auto; padding: 10px 0; }
        .node { padding: 10px; border-radius: 8px; border: 1px solid #444; min-width: 100px; text-align: center; }
        .node.agent { background: #2d4a63; border-color: #569cd6; }
        .node.human { background: #4a2d30; border-color: #f44747; }
        .role { font-size: 0.7rem; text-transform: uppercase; color: #888; margin-bottom: 5px; }
        .id { font-size: 1.2rem; font-weight: bold; }
        .type { font-size: 0.8rem; margin-top: 5px; opacity: 0.8; }
        .arrow { font-size: 1.5rem; color: #666; }
        .status { padding: 10px; border-radius: 4px; margin-top: 20px; }
        .status.valid { background: #2d4a3e; color: #75be95; border: 1px solid #75be95; }
        .status.invalid { background: #4a2d30; color: #f44747; border: 1px solid #f44747; }
        h2 { border-bottom: 1px solid #444; padding-bottom: 10px; }
    </style>
</head>
<body>
    <h2>Delegation Inspector</h2>
    <p>File: <code>${filePath}</code></p>
    
    <div class="chain">
        ${chainHtml}
    </div>

    <div class="status ${validation.valid ? 'valid' : 'invalid'}">
        <strong>Status:</strong> ${validation.valid ? 'Valid Chain' : `Invalid Chain: ${validation.error}`}
    </div>

    <div style="margin-top: 30px;">
        <h3>Metadata Summary</h3>
        <ul>
            <li><strong>Principal:</strong> ${metadata.principalId}</li>
            <li><strong>Current Executor:</strong> ${metadata.actorId}</li>
            <li><strong>Chain Depth:</strong> ${metadata.delegationPath.length}</li>
        </ul>
    </div>
</body>
</html>`;
    }
}
