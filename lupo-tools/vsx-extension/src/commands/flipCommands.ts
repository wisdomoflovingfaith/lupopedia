/**
 * FLIP Commands — search, navigation, thread simulation, offline audit
 *
 * Integrates with FlipTreeDataProvider for comprehensive FLIP header management.
 *
 * @module commands/flipCommands
 */

import * as vscode from 'vscode';
import * as fs from 'fs';
import * as path from 'path';
import { FlipTreeDataProvider, FlipFileInfo } from '../providers/flipTreeProvider';
import { parseFlipHeader, formatFlipHeader, FlipHeader } from '../lupopedia/flip';

/**
 * Register all FLIP-related commands
 */
export function registerFlipCommands(
    context: vscode.ExtensionContext,
    flipProvider: FlipTreeDataProvider
): void {
    // ── Command: FLIP Search ──────────────────────────────────────────────
    context.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.searchFlip', async () => {
            const query = await vscode.window.showInputBox({
                prompt: 'Search FLIP files by keyword (in path or metadata)',
                placeHolder: 'e.g. "doctrine", "42", "Active"',
            });

            if (!query) {
                return;
            }

            const results = flipProvider.search(query);
            if (results.length === 0) {
                vscode.window.showInformationMessage(`No FLIP files found matching "${query}"`);
                return;
            }

            // Show quick pick with results
            const items = results.map(f => ({
                label: path.basename(f.relativePath),
                description: f.header?.file_last_modified_system_version || 'No header',
                detail: f.relativePath,
                file: f,
            }));

            const selected = await vscode.window.showQuickPick(items, {
                placeHolder: `${results.length} result(s) found for "${query}"`,
            });

            if (selected) {
                openFlipFile(selected.file.fsPath);
            }
        })
    );

    // ── Command: FLIP Filter by Status ────────────────────────────────────
    context.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.filterByStatus', async () => {
            const status = await vscode.window.showQuickPick(
                ['Active', 'Proposed', 'Deprecated', 'Unknown'],
                { placeHolder: 'Filter FLIP files by status' }
            );

            if (!status) {
                return;
            }

            const results = flipProvider.getByStatus(status);
            showFlipResults(results, `Status: ${status}`);
        })
    );

    // ── Command: FLIP Filter by Channel ───────────────────────────────────
    context.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.filterByChannel', async () => {
            const channelInput = await vscode.window.showInputBox({
                prompt: 'Enter channel ID',
                placeHolder: 'e.g. 42',
                validateInput: (v: string) =>
                    /^\d+$/.test(v.trim()) ? undefined : 'Must be a positive integer',
            });

            if (!channelInput) {
                return;
            }

            const channelId = parseInt(channelInput, 10);
            const results = flipProvider.getByChannel(channelId);
            showFlipResults(results, `Channel ${channelId}`);
        })
    );

    // ── Command: FLIP Filter by Thread ────────────────────────────────────
    context.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.filterByThread', async () => {
            const threadId = await vscode.window.showInputBox({
                prompt: 'Enter thread ID',
                placeHolder: 'e.g. 1001',
            });

            if (!threadId) {
                return;
            }

            const results = flipProvider.getByThread(threadId);
            showFlipResults(results, `Thread ${threadId}`);
        })
    );

    // ── Command: Open FLIP File ───────────────────────────────────────────
    context.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.openFlipFile', (fsPath: string) => {
            openFlipFile(fsPath);
        })
    );

    // ── Command: Refresh Doctrine View ────────────────────────────────────
    context.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.refreshDoctrine', () => {
            flipProvider.refresh();
            vscode.window.showInformationMessage('Lupopedia: Doctrine view refreshed');
        })
    );

    // ── Command: Show Thread Simulation ───────────────────────────────────
    context.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.showThreadSimulation', async () => {
            const editor = vscode.window.activeTextEditor;
            if (!editor) {
                vscode.window.showWarningMessage('Lupopedia: No active file');
                return;
            }

            const content = editor.document.getText();
            const parsed = parseFlipHeader(content);

            if (!parsed.valid || !parsed.header) {
                vscode.window.showErrorMessage('Lupopedia: Current file has no valid FLIP header');
                return;
            }

            const threadId = parsed.header.extras.thread_id;
            if (!threadId) {
                vscode.window.showInformationMessage('Lupopedia: This file is not linked to a thread');
                return;
            }

            // Find related files in thread
            const threadFiles = flipProvider.getByThread(threadId);
            if (threadFiles.length === 0) {
                vscode.window.showInformationMessage(`No other files found in thread ${threadId}`);
                return;
            }

            // Show thread simulation panel
            showThreadSimulation(threadId, threadFiles, context.extensionUri);
        })
    );

    // ── Command: Update FLIP Header Timestamp ─────────────────────────────
    context.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.updateFlipTimestamp', async () => {
            const editor = vscode.window.activeTextEditor;
            if (!editor) {
                vscode.window.showWarningMessage('Lupopedia: No active file');
                return;
            }

            const content = editor.document.getText();
            const parsed = parseFlipHeader(content);

            if (!parsed.valid || !parsed.header) {
                vscode.window.showErrorMessage('Lupopedia: No valid FLIP header to update');
                return;
            }

            // Update timestamp
            const now = new Date();
            const utc = formatUTC(now);
            parsed.header.file_last_modified_utc = utc;

            // Replace header in document
            const newHeader = formatFlipHeader(parsed.header);
            const lines = content.split('\n');
            let endIdx = -1;
            for (let i = 1; i < lines.length; i++) {
                if (lines[i].trim() === '---') {
                    endIdx = i;
                    break;
                }
            }

            if (endIdx !== -1) {
                const newContent = newHeader + '\n' + lines.slice(endIdx + 1).join('\n');
                const fullRange = new vscode.Range(
                    editor.document.positionAt(0),
                    editor.document.positionAt(content.length)
                );
                await editor.edit(editBuilder => {
                    editBuilder.replace(fullRange, newContent);
                });

                // Log to audit file
                logAuditEvent(context, {
                    event: 'flip_header_updated',
                    file: editor.document.uri.fsPath,
                    utc_timestamp: utc,
                });

                vscode.window.showInformationMessage(
                    `Lupopedia: FLIP header updated (${utc})`
                );
            }
        })
    );

    // ── Command: Group By Mode Toggle ──────────────────────────────────────
    context.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.toggleFlipGroupBy', async () => {
            const mode = await vscode.window.showQuickPick(
                ['Status', 'Channel', 'Flat List'],
                { placeHolder: 'Group FLIP files by...' }
            );

            if (!mode) {
                return;
            }

            const mapping = {
                'Status': 'status' as const,
                'Channel': 'channel' as const,
                'Flat List': 'flat' as const,
            };

            flipProvider.setGroupBy(mapping[mode as keyof typeof mapping]);
        })
    );

    // ── Command: Log Agent Action ──────────────────────────────────────────
    context.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.logAction', async () => {
            const action = await vscode.window.showInputBox({
                prompt: 'Describe action taken (for offline audit log)',
                placeHolder: 'e.g. "Updated schema mismatch documentation"',
            });

            if (!action) {
                return;
            }

            logAuditEvent(context, {
                event: 'agent_action',
                action,
                timestamp: formatUTC(new Date()),
            });

            vscode.window.showInformationMessage('Lupopedia: Action logged');
        })
    );
}

/**
 * Helper: Show FLIP search results in quick pick
 */
async function showFlipResults(results: FlipFileInfo[], title: string): Promise<void> {
    if (results.length === 0) {
        vscode.window.showInformationMessage(`Lupopedia: No files found for ${title}`);
        return;
    }

    const items = results.map(f => ({
        label: path.basename(f.relativePath),
        description: f.header?.file_last_modified_system_version || 'No header',
        detail: f.relativePath,
        file: f,
    }));

    const selected = await vscode.window.showQuickPick(items, {
        placeHolder: `${results.length} file(s) in ${title}`,
    });

    if (selected) {
        openFlipFile(selected.file.fsPath);
    }
}

/**
 * Helper: Open FLIP file in editor
 */
function openFlipFile(fsPath: string): void {
    vscode.workspace.openTextDocument(fsPath).then(doc => {
        vscode.window.showTextDocument(doc);
    });
}

/**
 * Helper: Format UTC timestamp (YYYYMMDDHHMMSS)
 */
function formatUTC(date: Date): string {
    const y = date.getUTCFullYear();
    const m = String(date.getUTCMonth() + 1).padStart(2, '0');
    const d = String(date.getUTCDate()).padStart(2, '0');
    const h = String(date.getUTCHours()).padStart(2, '0');
    const min = String(date.getUTCMinutes()).padStart(2, '0');
    const s = String(date.getUTCSeconds()).padStart(2, '0');
    return `${y}${m}${d}${h}${min}${s}`;
}

/**
 * Helper: Log event to offline audit file (lupo_anubis_log.json)
 */
function logAuditEvent(context: vscode.ExtensionContext, event: Record<string, unknown>): void {
    const logPath = path.join(context.globalStorageUri.fsPath, 'lupo_anubis_log.json');

    // Create directory if needed
    if (!fs.existsSync(context.globalStorageUri.fsPath)) {
        fs.mkdirSync(context.globalStorageUri.fsPath, { recursive: true });
    }

    let logs: Record<string, unknown>[] = [];
    if (fs.existsSync(logPath)) {
        try {
            const data = fs.readFileSync(logPath, 'utf-8');
            logs = JSON.parse(data);
        } catch {
            // Corrupted log, start fresh
        }
    }

    logs.push({
        ...event,
        timestamp: formatUTC(new Date()),
    });

    // Keep last 1000 entries
    if (logs.length > 1000) {
        logs = logs.slice(-1000);
    }

    fs.writeFileSync(logPath, JSON.stringify(logs, null, 2));
}

/**
 * Helper: Show thread simulation webview
 */
function showThreadSimulation(
    threadId: string,
    files: FlipFileInfo[],
    extensionUri: vscode.Uri
): void {
    const panel = vscode.window.createWebviewPanel(
        'lupopediaThreadSimulation',
        `Thread ${threadId} Simulation`,
        vscode.ViewColumn.Two,
        { enableScripts: true }
    );

    panel.webview.html = getThreadSimulationHTML(threadId, files);
}

/**
 * Helper: Generate HTML for thread simulation
 */
function getThreadSimulationHTML(threadId: string, files: FlipFileInfo[]): string {
    const fileList = files
        .map(f => {
            const header = f.header;
            const version = header?.file_last_modified_system_version || 'Unknown';
            const modified = header?.file_last_modified_utc || 'Unknown';
            const actorId = header?.extras?.actor_id || 'Unknown';
            const status = header?.extras?.status || 'Unknown';

            return `
                <div class="file-card" style="border: 1px solid #444; padding: 12px; margin: 8px 0; border-radius: 4px;">
                    <h3 style="margin: 0 0 8px 0;">${path.basename(f.relativePath)}</h3>
                    <p style="margin: 4px 0; font-size: 0.9em; color: #888;">
                        <strong>Path:</strong> ${f.relativePath}<br/>
                        <strong>Version:</strong> ${version}<br/>
                        <strong>Modified:</strong> ${formatReadableTimestamp(modified)}<br/>
                        <strong>Actor ID:</strong> ${actorId}<br/>
                        <strong>Status:</strong> <span style="color: ${getStatusColor(status)};">${status}</span>
                    </p>
                </div>
            `;
        })
        .join('');

    return `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Thread ${threadId} Simulation</title>
            <style>
                body { font-family: sans-serif; padding: 16px; background: #1e1e1e; color: #d4d4d4; }
                h1 { color: #569cd6; }
                .file-card { background: #252526; }
            </style>
        </head>
        <body>
            <h1>🧵 Thread ${threadId} Simulation</h1>
            <p>${files.length} file(s) linked to this thread:</p>
            ${fileList}
            <p style="margin-top: 24px; font-size: 0.85em; color: #888;">
                💡 <strong>Offline Mode:</strong> This is a simulated thread view based on FLIP headers.
                When the server is online, thread messages will be displayed here.
            </p>
        </body>
        </html>
    `;
}

function formatReadableTimestamp(utc: string): string {
    if (utc.length !== 14) {
        return utc;
    }
    return `${utc.slice(0, 4)}-${utc.slice(4, 6)}-${utc.slice(6, 8)} ${utc.slice(8, 10)}:${utc.slice(10, 12)}:${utc.slice(12, 14)} UTC`;
}

function getStatusColor(status: string): string {
    switch (status) {
        case 'Active':
            return '#4ec9b0';
        case 'Proposed':
            return '#dcdcaa';
        case 'Deprecated':
            return '#f48771';
        default:
            return '#888';
    }
}
