// src/panels/SemanticMapPanel.ts

import * as vscode from 'vscode';
import { MetadataIndex } from '../lupopedia/flip/parser/types';

export class SemanticMapPanel {
    public static currentPanel: SemanticMapPanel | undefined;
    private readonly _panel: vscode.WebviewPanel;
    private _disposables: vscode.Disposable[] = [];

    private constructor(panel: vscode.WebviewPanel, extensionUri: vscode.Uri) {
        this._panel = panel;
        this._panel.onDidDispose(() => this.dispose(), null, this._disposables);
    }

    public static createOrShow(extensionUri: vscode.Uri, metadata: MetadataIndex, filePath: string) {
        const column = vscode.window.activeTextEditor ? vscode.window.activeTextEditor.viewColumn : undefined;

        if (SemanticMapPanel.currentPanel) {
            SemanticMapPanel.currentPanel._panel.reveal(column);
            SemanticMapPanel.currentPanel._update(metadata, filePath);
            return;
        }

        const panel = vscode.window.createWebviewPanel(
            'semanticMap',
            'Semantic Relationship Map',
            column || vscode.ViewColumn.One,
            { enableScripts: true }
        );

        SemanticMapPanel.currentPanel = new SemanticMapPanel(panel, extensionUri);
        SemanticMapPanel.currentPanel._update(metadata, filePath);
    }

    private _update(metadata: MetadataIndex, filePath: string) {
        this._panel.webview.html = this._getHtmlForWebview(metadata, filePath);
    }

    public dispose() {
        SemanticMapPanel.currentPanel = undefined;
        this._panel.dispose();
        while (this._disposables.length) {
            const x = this._disposables.pop();
            if (x) x.dispose();
        }
    }

    private _getHtmlForWebview(metadata: MetadataIndex, filePath: string) {
        const fileName = filePath.split(/[\\/]/).pop();

        // Generate Mermaid code string
        let mermaidCode = 'graph TD\n';
        mermaidCode += `  Self["${fileName}"]\n`;

        metadata.inbound.forEach(src => {
            mermaidCode += `  Source["${src.split(/[\\/]/).pop()}"] -->|inbound| Self\n`;
        });

        metadata.outbound.forEach(target => {
            mermaidCode += `  Self -->|outbound| Target["${target.split(/[\\/]/).pop()}"]\n`;
        });

        return `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; padding: 20px; color: #d4d4d4; background: #1e1e1e; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px; }
        .section { background: #252526; padding: 15px; border-radius: 8px; border: 1px solid #333; }
        .section-title { font-weight: bold; color: #569cd6; margin-bottom: 10px; text-transform: uppercase; font-size: 0.8rem; }
        ul { padding-left: 20px; margin: 0; }
        li { margin-bottom: 5px; color: #ce9178; }
        pre { background: #000; padding: 10px; border-radius: 4px; overflow-x: auto; color: #dcdcdc; }
    </style>
</head>
<body>
    <h2>Semantic Relationship Map</h2>
    <p>File: <code>${filePath}</code></p>
    
    <div class="grid">
        <div class="section">
            <div class="section-title">Inbound Edges</div>
            ${metadata.inbound.length > 0 ? `<ul>${metadata.inbound.map(i => `<li>${i}</li>`).join('')}</ul>` : '<p>No inbound edges found.</p>'}
        </div>
        <div class="section">
            <div class="section-title">Outbound Edges</div>
            ${metadata.outbound.length > 0 ? `<ul>${metadata.outbound.map(o => `<li>${o}</li>`).join('')}</ul>` : '<p>No outbound edges found.</p>'}
        </div>
    </div>

    <div style="margin-top: 30px;">
        <h3>Mermaid Graph Definition</h3>
        <pre>${mermaidCode}</pre>
        <p style="font-size: 0.8rem; color: #888;">Copy and paste this into a Mermaid-capable viewer or GitHub Issue.</p>
    </div>
</body>
</html>`;
    }
}
