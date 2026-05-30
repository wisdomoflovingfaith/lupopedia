// src/lupopedia/commands/Initialize.ts

import * as vscode from 'vscode';
import { scanWorkspace } from './ScanWorkspace';
import { ArtifactIndex } from '../headers/storage/ArtifactIndex';

export async function initializeLupopedia(context: vscode.ExtensionContext, artifactIndex: ArtifactIndex) {
    // 1. Check if workspace is Lupopedia project
    if (!vscode.workspace.workspaceFolders) {
        return;
    }

    // 2. Initialize Artifact Index (if needed)
    await artifactIndex.initialize();

    // 3. Scan workspace for existing Lupopedia Header files
    await vscode.window.withProgress({
        location: vscode.ProgressLocation.Notification,
        title: "Initializing Lupopedia...",
        cancellable: false
    }, async (progress) => {
        progress.report({ message: "Scanning for LUPOPEDIA HEADER artifacts..." });
        const results = await scanWorkspace(context, artifactIndex);

        const agents = await artifactIndex.getAllAgents();

        vscode.window.showInformationMessage(
            `Lupopedia v4.0.37 initialized. ` +
            `Found ${results.filesScanned} LUPOPEDIA HEADER artifacts. ` +
            `${agents.length} agents active in workspace.`
        );
    });
}
