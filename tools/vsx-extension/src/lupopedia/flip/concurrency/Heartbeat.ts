// src/lupopedia/flip/concurrency/Heartbeat.ts

import * as vscode from 'vscode';
import * as fs from 'fs';
import * as path from 'path';

export interface HeartbeatData {
    agent_id: number;
    status: 'active' | 'idle' | 'away';
    current_thread?: string;
    current_file?: string;
    uptime: string;
    last_action?: string;
    next_action?: string;
    available: boolean;
}

export class HeartbeatManager {
    private interval: NodeJS.Timeout | null = null;
    private startTime: number;

    constructor() {
        this.startTime = Date.now();
    }

    /**
     * Starts the heartbeat broadcast.
     */
    public start(actorId: number, agentKey: string) {
        if (this.interval) return;

        // Pulse every 15 minutes as per LILITH's recommendation
        this.interval = setInterval(() => {
            this.pulse(actorId, agentKey);
        }, 15 * 60 * 1000);

        // Initial pulse
        this.pulse(actorId, agentKey);
    }

    /**
     * Stops the heartbeat broadcast.
     */
    public stop() {
        if (this.interval) {
            clearInterval(this.interval);
            this.interval = null;
        }
    }

    private pulse(actorId: number, agentKey: string) {
        const workspaceRoot = vscode.workspace.workspaceFolders?.[0].uri.fsPath;
        if (!workspaceRoot) return;

        const date = new Date().toISOString().slice(0, 10).replace(/-/g, '');
        const time = new Date().toISOString().slice(11, 16).replace(':', '');
        const fileName = `heartbeat_${actorId}_${date}.md`;
        const filePath = path.join(workspaceRoot, 'docs', 'status', 'agents', fileName);

        const statusDir = path.dirname(filePath);
        if (!fs.existsSync(statusDir)) {
            fs.mkdirSync(statusDir, { recursive: true });
        }

        const editor = vscode.window.activeTextEditor;
        const currentFile = editor ? vscode.workspace.asRelativePath(editor.document.uri) : 'none';

        const uptimeHours = ((Date.now() - this.startTime) / (1000 * 60 * 60)).toFixed(1);

        const content = `---
wolfie.headers:
  file_path_from_root: "docs/status/agents/${fileName}"
  system_version: "4.0.37"
  channel_id: 42
  purpose: "Agent heartbeat — ${agentKey} active"
  last_modified: "${date}-${time}"
  lupo_agent: "ide|${agentKey.toLowerCase()}"

heartbeat:
  agent_id: ${actorId}
  status: "active"
  current_thread: "T-37-01"
  current_file: "${currentFile}"
  uptime: "${uptimeHours} hours"
  last_action: "heartbeat pulse"
  available: true
---

# HEARTBEAT: ${agentKey.toUpperCase()}

This is an automated heartbeat for agent ${actorId} (${agentKey}).
Last pulse: ${new Date().toISOString()}
`;

        fs.writeFileSync(filePath, content, 'utf-8');
    }
}
