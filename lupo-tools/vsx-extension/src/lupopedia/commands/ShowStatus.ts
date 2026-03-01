// src/lupopedia/commands/ShowStatus.ts

import * as vscode from 'vscode';
import { ArtifactIndex } from '../flip/storage/ArtifactIndex';

export async function showLupopediaStatus(artifactIndex: ArtifactIndex) {
    const panel = vscode.window.createWebviewPanel(
        'lupopediaStatus',
        'Lupopedia Status',
        vscode.ViewColumn.One,
        { enableScripts: true }
    );

    const agents = await artifactIndex.getAllAgents();

    // Simple HTML content
    panel.webview.html = `
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: sans-serif; padding: 20px; background-color: #1e1e1e; color: #ccc; }
                h1 { color: #fff; border-bottom: 1px solid #444; padding-bottom: 10px; }
                .stat-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
                .stat-card { background: #252526; padding: 15px; border-radius: 5px; border: 1px solid #333; }
                .stat-value { font-size: 24px; font-weight: bold; color: #007acc; }
                .agent-list { list-style: none; padding: 0; }
                .agent-card { background: #252526; margin-bottom: 10px; padding: 10px; border-left: 4px solid #007acc; }
                .online { color: #4ec9b0; font-weight: bold; }
            </style>
        </head>
        <body>
            <h1>Lupopedia v4.0.37 Status</h1>
            
            <div class="stat-grid">
                <div class="stat-card">
                    <div class="stat-label">Agents Active</div>
                    <div class="stat-value">${agents.length}</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Registry Mode</div>
                    <div class="stat-value">v4.0.37-FLIPv2</div>
                </div>
            </div>

            <h2>🤖 Active Agents (Last 24h)</h2>
            <ul class="agent-list">
                ${agents.map(a => `
                    <li class="agent-card">
                        <b>${a.agentKey}</b> (ID: ${a.actorId})<br/>
                        Last activity: ${a.lastSeen}<br/>
                        <span class="online">● ACTIVE</span>
                    </li>
                `).join('')}
            </ul>
        </body>
        </html>
    `;
}
