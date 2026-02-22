/**
 * Lupopedia VS Code Extension — Entry Point
 *
 * Registers all commands, initialises identity storage, and manages
 * the status bar actor indicator.
 *
 * @module extension
 */

import * as vscode from 'vscode';

import { initIdentityStorage, loadIdentity, ActorIdentity, buildActorRoster } from './lupopedia/identity';
import { registerActor, lookupActor, lookupKnownActors } from './lupopedia/actor';
import { sendMessage, getMessages, joinChannel, CommMode } from './lupopedia/channels';
import { explainFile, getRelatedAtoms } from './lupopedia/semantic';
import { parseFlipHeader, formatFlipHeader, inferRelativePath } from './lupopedia/flip';
import { ChannelViewerPanel } from './webviews/channelViewer';
import { SemanticViewerPanel } from './webviews/semanticViewer';
import { FlipEditorPanel } from './webviews/flipEditor';
import { FlipTreeDataProvider } from './providers/flipTreeProvider';
import * as fs from 'fs';
import * as path from 'path';

// ─── Helpers ─────────────────────────────────────────────────────────────────

function getConfig(): {
    baseUrl: string;
    defaultChannelId: number;
    actorName: string;
    actorType: string;
    communicationMode: 'remote' | 'local' | 'offline' | 'auto';
} {
    const cfg = vscode.workspace.getConfiguration('lupopedia');
    return {
        baseUrl: cfg.get<string>('baseUrl', 'https://lupopedia.com/lupopedia'),
        defaultChannelId: cfg.get<number>('defaultChannelId', 42),
        actorName: cfg.get<string>('actorName', 'Antigravity IDE'),
        actorType: cfg.get<string>('actorType', 'system_tool'),
        communicationMode: cfg.get<'remote' | 'local' | 'offline' | 'auto'>('communicationMode', 'auto'),
    };
}

function requireIdentity(
    statusBar: vscode.StatusBarItem
): ActorIdentity | null {
    const identity = loadIdentity();
    if (!identity) {
        vscode.window
            .showWarningMessage(
                'Lupopedia: This IDE is not registered. Run "Lupopedia: Register IDE" first.',
                'Register Now'
            )
            .then((choice: string | undefined) => {
                if (choice === 'Register Now') {
                    vscode.commands.executeCommand('lupopedia.registerIde');
                }
            });
        return null;
    }
    return identity;
}

function updateStatusBar(
    statusBar: vscode.StatusBarItem,
    identity: ActorIdentity | null
): void {
    if (identity) {
        statusBar.text = `$(organization) Lupopedia: ${identity.actor_name}`;
        statusBar.tooltip = `Actor ID: ${identity.actor_id} | Type: ${identity.actor_type}`;
        statusBar.color = undefined;
    } else {
        statusBar.text = `$(warning) Lupopedia: (unregistered)`;
        statusBar.tooltip = 'Click to register this IDE with Lupopedia';
        statusBar.command = 'lupopedia.registerIde';
    }
    statusBar.show();
}

// ─── Activate ─────────────────────────────────────────────────────────────────

export async function activate(ctx: vscode.ExtensionContext): Promise<void> {
    // Initialise identity storage
    initIdentityStorage(ctx);

    // Status bar item
    const statusBar = vscode.window.createStatusBarItem(
        vscode.StatusBarAlignment.Right,
        100
    );
    ctx.subscriptions.push(statusBar);

    // ── Tree View: Doctrine / docs ──────────────────────────────────────────
    const rootPath =
        vscode.workspace.workspaceFolders && vscode.workspace.workspaceFolders.length > 0
            ? vscode.workspace.workspaceFolders[0].uri.fsPath
            : '';

    const flipTreeProvider = new FlipTreeDataProvider(rootPath);
    vscode.window.registerTreeDataProvider('lupopedia.doctrine', flipTreeProvider);

    // ── Command: Open FLIP File ─────────────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.openFlipFile', (fsPath: string) => {
            vscode.workspace.openTextDocument(fsPath).then(doc => {
                vscode.window.showTextDocument(doc);
            });
        })
    );

    // ── Command: Refresh Doctrine View ──────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.refreshDoctrine', () => {
            flipTreeProvider.refresh();
        })
    );

    // ── Command: Log Agent Action ───────────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.logAction', async () => {
            const identity = requireIdentity(statusBar);
            if (!identity) { return; }

            const action = await vscode.window.showInputBox({
                prompt: 'Describe the action you completed',
                placeHolder: 'e.g. Fixed schema mismatches in registry'
            });
            if (!action) { return; }

            const threadId = await vscode.window.showInputBox({
                prompt: 'Thread ID (optional)',
                value: '1001'
            });

            // Log format: YYYYMMDDHHmmss
            const now = new Date();
            const timestamp = now.toISOString().replace(/[-:T]/g, '').slice(0, 14);

            const editor = vscode.window.activeTextEditor;
            let actorDisplayName = identity.actor_name;
            let actorIdToLog = identity.actor_id;

            if (editor && editor.document.languageId === 'markdown') {
                const result = parseFlipHeader(editor.document.getText());
                if (result.header) {
                    if (result.header.actor_id) {
                        actorIdToLog = result.header.actor_id;
                    }
                    if (result.header.lupo_actor_identity || result.header.from) {
                        actorDisplayName = result.header.lupo_actor_identity || result.header.from || identity.actor_name;
                    }
                }
            }

            const logEntry = {
                actor_id: actorIdToLog,
                actor_name: actorDisplayName,
                action: action,
                timestamp: timestamp,
                thread_id: threadId || 'unassigned'
            };

            const logPath = path.join(rootPath, 'docs', 'channel42_log.json');
            try {
                let logData: { actions: any[] } = { actions: [] };
                if (fs.existsSync(logPath)) {
                    const raw = fs.readFileSync(logPath, 'utf-8');
                    logData = JSON.parse(raw);
                }
                logData.actions.push(logEntry);

                // Ensure docs dir exists
                const docsDir = path.dirname(logPath);
                if (!fs.existsSync(docsDir)) {
                    fs.mkdirSync(docsDir, { recursive: true });
                }

                fs.writeFileSync(logPath, JSON.stringify(logData, null, 2), 'utf-8');
                vscode.window.showInformationMessage(`Lupopedia: Action logged to channel42_log.json`);
                flipTreeProvider.refresh();
            } catch (err: unknown) {
                vscode.window.showErrorMessage(`Failed to log action: ${err instanceof Error ? err.message : String(err)}`);
            }
        })
    );

    // ── Command: Validate FLIP Header ───────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.validateFlipHeader', () => {
            const editor = vscode.window.activeTextEditor;
            if (!editor || editor.document.languageId !== 'markdown') {
                vscode.window.showWarningMessage('Please open a Markdown file to validate its FLIP header.');
                return;
            }

            const text = editor.document.getText();
            const result = parseFlipHeader(text);

            if (result.valid && result.header) {
                const h = result.header;
                vscode.window.showInformationMessage(
                    `✅ FLIP Header Valid: ${h.file_path_from_root} (v${h.file_last_modified_system_version})`
                );
            } else {
                const msg = result.errors.join(' | ');
                vscode.window.showErrorMessage(`❌ FLIP Header Invalid: ${msg}`);
            }
        })
    );

    // ── Global Document Watcher: FLIP Enforcement ────────────────────────────
    ctx.subscriptions.push(
        vscode.workspace.onWillSaveTextDocument(async (event) => {
            if (event.document.languageId !== 'markdown') { return; }
            if (!rootPath) { return; }

            const fsPath = event.document.uri.fsPath;
            if (!fsPath.includes(`${path.sep}docs${path.sep}`)) { return; }

            const text = event.document.getText();
            const result = parseFlipHeader(text);
            const expectedPath = inferRelativePath(fsPath, rootPath);

            // If header exists, enforce Trinity and Path
            if (result.header) {
                let needsUpdate = false;
                const updatedHeader = { ...result.header };

                // 1. Enforce Path
                if (updatedHeader.file_path_from_root !== expectedPath) {
                    updatedHeader.file_path_from_root = expectedPath;
                    needsUpdate = true;
                }

                // 2. Enforce Actor Trinity (Lupopedia 4.0.27)
                const hasTrinity = updatedHeader.actor_id !== null || updatedHeader.lupo_actor_identity || updatedHeader.from;
                if (!hasTrinity) {
                    // Inject "From:" based on config or identity
                    const config = getConfig();
                    let fromValue = config.actorName;

                    if (!fromValue) {
                        fromValue = await vscode.window.showInputBox({
                            prompt: 'Missing Actor Attribution: Enter your name or identifier (e.g., @username or email)',
                            placeHolder: 'captain@lupopedia.com'
                        }) || '';
                    }

                    if (fromValue) {
                        updatedHeader.from = fromValue;
                        needsUpdate = true;
                    }
                }

                const config = getConfig();
                const forceVerbose = config.communicationMode === 'offline' || config.communicationMode === 'auto';

                if (needsUpdate || forceVerbose) {
                    // Update temporal headers for doctrine compliance
                    const now = new Date();
                    const timestamp = now.toISOString().replace(/[-:T]/g, '').slice(0, 14);
                    const isoTimestamp = now.toISOString();

                    updatedHeader.file_last_modified_utc = timestamp;
                    updatedHeader.file_last_modified_system_version = '4.0.27'; // Doctrine 4.0.27 Enforcement

                    if (forceVerbose) {
                        updatedHeader.lupo_timestamp = timestamp;
                        updatedHeader.lupo_utc_timestamp = isoTimestamp;
                        updatedHeader.lupo_registry_mode = 'offline-fallback';
                        updatedHeader.lupo_registry_source = 'local-headers';
                        updatedHeader.lupo_location = 'Sioux Falls, South Dakota, US'; // Default for the Captain's location doctrine
                        updatedHeader.tags = '["flip", "doctrine", "offline"]';
                        updatedHeader.mood_rgb = 'D2BEFA';

                        // New Phase 10: Semantic Database Parity
                        updatedHeader.registry_id = updatedHeader.registry_id ?? 9002035;
                        updatedHeader.entity_type = updatedHeader.entity_type ?? 'file';
                        updatedHeader.federation_node_id = updatedHeader.federation_node_id ?? 1;

                        updatedHeader.content_id = updatedHeader.content_id ?? 0;
                        updatedHeader.triage_status = updatedHeader.triage_status ?? 'untriaged';
                        updatedHeader.visibility = updatedHeader.visibility ?? 'public';
                        updatedHeader.version_number = updatedHeader.version_number ?? 1;

                        updatedHeader.collection_id = updatedHeader.collection_id ?? 42;
                        updatedHeader.collection_name = updatedHeader.collection_name ?? 'Lupopedia Development';

                        updatedHeader.channel_key = updatedHeader.channel_key ?? 'lupopedia-development';
                        updatedHeader.channel_type = updatedHeader.channel_type ?? 'chat_room';
                        updatedHeader.channel_name = updatedHeader.channel_name ?? 'Lupopedia Development';

                        updatedHeader.is_active = updatedHeader.is_active ?? true;
                        updatedHeader.is_kernel = updatedHeader.is_kernel ?? false;
                        updatedHeader.is_deleted = updatedHeader.is_deleted ?? false;

                        // Example Survivor Protocol (Mission 1)
                        if (!updatedHeader.lupo_survivor_protocol) {
                            updatedHeader.lupo_survivor_protocol = 'standby';
                            updatedHeader.lupo_origin_status = 'active';
                            updatedHeader.lupo_forwarded_for = '420';
                            updatedHeader.lupo_forward_chain = '420 -> 2035 -> 2';
                        }

                        if (!updatedHeader.lupo_actor_to) {
                            updatedHeader.lupo_actor_to = 2; // Captain
                        }
                    }

                    const newHeaderText = formatFlipHeader(updatedHeader);
                    const oldHeaderText = `---${result.raw}---`;

                    const edit = new vscode.WorkspaceEdit();
                    const fullText = event.document.getText();
                    const startIdx = fullText.indexOf(oldHeaderText);
                    if (startIdx !== -1) {
                        const startPos = event.document.positionAt(startIdx);
                        const endPos = event.document.positionAt(startIdx + oldHeaderText.length);
                        edit.replace(event.document.uri, new vscode.Range(startPos, endPos), newHeaderText);
                        await vscode.workspace.applyEdit(edit);

                        // Invalidate cache for this file
                        flipTreeProvider.invalidate(fsPath);

                        // Auto-log the update action
                        vscode.commands.executeCommand('lupopedia.internalLog', {
                            actor_id: updatedHeader.actor_id || 0,
                            actor_name: updatedHeader.lupo_actor_identity || updatedHeader.from || 'System (FLIP Auto-Update)',
                            action: `Enforced 4.0.27 Doctrine (Path/Trinity) for ${expectedPath}`,
                            thread_id: updatedHeader.thread_id || '1001'
                        });
                    }
                }
            }
        })
    );

    // ── Internal Helper: Silent Log ──────────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.internalLog', (entry: any) => {
            const logPath = path.join(rootPath, 'docs', 'channel42_log.json');
            try {
                let logData: { actions: any[] } = { actions: [] };
                if (fs.existsSync(logPath)) {
                    const raw = fs.readFileSync(logPath, 'utf-8');
                    logData = JSON.parse(raw);
                }
                const now = new Date();
                const timestamp = now.toISOString().replace(/[-:T]/g, '').slice(0, 14);

                logData.actions.push({
                    timestamp,
                    thread_id: 'unassigned',
                    ...entry
                });

                fs.writeFileSync(logPath, JSON.stringify(logData, null, 2), 'utf-8');
                flipTreeProvider.refresh();
            } catch (err) { }
        })
    );

    // ── Startup: look up self-identity ─────────────────────────────────────
    let identity = loadIdentity();
    if (!identity) {
        try {
            const { baseUrl, actorName, actorType } = getConfig();
            identity = await lookupActor(baseUrl, actorName, actorType);
        } catch {
            // Server may be offline at startup — that's fine
        }
    }
    updateStatusBar(statusBar, identity);

    // ── Startup: look up external actors (Copilot, LEXA, LILITH) ──────────
    // Runs in background — failures are silent so offline installs still work.
    try {
        const { baseUrl } = getConfig();
        await lookupKnownActors(baseUrl);
    } catch {
        // Server offline — cached ids will be used if available
    }

    // ── Command: Register IDE ─────────────────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.registerIde', async () => {
            const { baseUrl, actorName, actorType } = getConfig();
            try {
                const result = await vscode.window.withProgress(
                    {
                        location: vscode.ProgressLocation.Notification,
                        title: 'Registering with Lupopedia…',
                        cancellable: false,
                    },
                    async () => registerActor(baseUrl, actorName, actorType)
                );
                updateStatusBar(statusBar, result);
                vscode.window.showInformationMessage(
                    `Lupopedia: Registered as "${result.actor_name}" (actor_id: ${result.actor_id})`
                );
            } catch (err: unknown) {
                vscode.window.showErrorMessage(
                    `Lupopedia registration failed: ${err instanceof Error ? err.message : String(err)}`
                );
            }
        })
    );

    // ── Command: Join Channel ─────────────────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.joinChannel', async () => {
            const id = requireIdentity(statusBar);
            if (!id) {
                return;
            }
            const { baseUrl, defaultChannelId } = getConfig();
            const input = await vscode.window.showInputBox({
                prompt: 'Channel ID to join',
                value: String(defaultChannelId),
                validateInput: (v: string) =>
                    /^\d+$/.test(v.trim()) ? undefined : 'Must be a positive integer',
            });
            if (!input) {
                return;
            }
            const channelId = parseInt(input, 10);
            try {
                const { communicationMode } = getConfig();
                await joinChannel(baseUrl, channelId, id, communicationMode);
                vscode.window.showInformationMessage(
                    `Lupopedia: Joined channel ${channelId}`
                );
            } catch (err: unknown) {
                vscode.window.showErrorMessage(
                    `Join channel failed: ${err instanceof Error ? err.message : String(err)}`
                );
            }
        })
    );

    // ── Command: Send Message ─────────────────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.sendMessage', async () => {
            const id = requireIdentity(statusBar);
            if (!id) {
                return;
            }
            const { baseUrl, defaultChannelId } = getConfig();
            const body = await vscode.window.showInputBox({
                prompt: `Message to send to channel ${defaultChannelId}`,
                placeHolder: 'Type your message…',
            });
            if (!body) {
                return;
            }
            try {
                const { communicationMode } = getConfig();
                const result = await sendMessage(baseUrl, defaultChannelId, body, id, undefined, communicationMode);
                vscode.window.showInformationMessage(
                    `Lupopedia: Message sent (id: ${result.message_id})`
                );
            } catch (err: unknown) {
                vscode.window.showErrorMessage(
                    `Send message failed: ${err instanceof Error ? err.message : String(err)}`
                );
            }
        })
    );

    // ── Command: Show Channel Thread ──────────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.showChannelThread', async () => {
            const id = requireIdentity(statusBar);
            if (!id) {
                return;
            }
            const { baseUrl, defaultChannelId } = getConfig();

            // Load initial messages
            let messages: import('./lupopedia/channels').ChannelMessage[] = [];
            try {
                const { communicationMode } = getConfig();
                messages = await getMessages(baseUrl, defaultChannelId, undefined, communicationMode);
            } catch {
                // Show empty panel; it will retry on next poll
            }

            const roster = buildActorRoster(id);

            ChannelViewerPanel.createOrShow(
                ctx.extensionUri,
                defaultChannelId,
                messages,
                baseUrl,
                id,
                roster,
                getConfig().communicationMode
            );
        })
    );

    // ── Command: Explain This File ────────────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.explainThisFile', async () => {
            const id = requireIdentity(statusBar);
            if (!id) {
                return;
            }
            const { baseUrl } = getConfig();

            const editor = vscode.window.activeTextEditor;
            if (!editor) {
                vscode.window.showWarningMessage('Lupopedia: No active file to explain.');
                return;
            }

            const filePath = vscode.workspace.asRelativePath(editor.document.uri);
            const content = editor.document.getText();

            try {
                const result = await vscode.window.withProgress(
                    {
                        location: vscode.ProgressLocation.Notification,
                        title: 'Lupopedia: Generating semantic explanation…',
                        cancellable: false,
                    },
                    async () =>
                        explainFile(baseUrl, {
                            file_path: filePath,
                            content,
                            actor_id: id.actor_id,
                        })
                );
                SemanticViewerPanel.createOrShow(ctx.extensionUri, 'Explanation', result);
            } catch (err: unknown) {
                vscode.window.showErrorMessage(
                    `Explain failed: ${err instanceof Error ? err.message : String(err)}`
                );
            }
        })
    );

    // ── Command: Show Related Atoms ───────────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.showRelatedAtoms', async () => {
            const id = requireIdentity(statusBar);
            if (!id) {
                return;
            }
            const { baseUrl } = getConfig();

            const editor = vscode.window.activeTextEditor;
            if (!editor) {
                vscode.window.showWarningMessage('Lupopedia: No active file open.');
                return;
            }

            const filePath = vscode.workspace.asRelativePath(editor.document.uri);

            try {
                const atoms = await vscode.window.withProgress(
                    {
                        location: vscode.ProgressLocation.Notification,
                        title: 'Lupopedia: Finding related atoms…',
                        cancellable: false,
                    },
                    async () =>
                        getRelatedAtoms(baseUrl, {
                            file_path: filePath,
                            actor_id: id.actor_id,
                            limit: 20,
                        })
                );
                SemanticViewerPanel.createOrShow(ctx.extensionUri, 'Related Atoms', {
                    atoms,
                    file_path: filePath,
                });
            } catch (err: unknown) {
                vscode.window.showErrorMessage(
                    `Related atoms failed: ${err instanceof Error ? err.message : String(err)}`
                );
            }
        })
    );

    // ── Command: Validate FLIP Header ─────────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.validateFlipHeader', async () => {
            const editor = vscode.window.activeTextEditor;
            if (!editor) {
                vscode.window.showWarningMessage('Lupopedia: No active file to validate.');
                return;
            }

            const text = editor.document.getText();
            const result = parseFlipHeader(text);

            if (result.valid && result.header) {
                const formatted = formatFlipHeader(result.header);
                // Show the FLIP editor for valid headers
                FlipEditorPanel.createOrShow(
                    ctx.extensionUri,
                    result.header,
                    getConfig().baseUrl,
                    loadIdentity()
                );
                vscode.window.showInformationMessage(
                    `Lupopedia: FLIP header is valid! Channel: ${result.header.channel_id ?? 'unresolved'}`
                );
            } else {
                const errList = result.errors.join('\n• ');
                vscode.window.showErrorMessage(
                    `Lupopedia: FLIP header invalid:\n• ${errList}`
                );
            }
        })
    );

    // ── Command: Toggle Communication Mode ────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.toggleCommunicationMode', async () => {
            const cfg = vscode.workspace.getConfiguration('lupopedia');
            const current = cfg.get<string>('communicationMode', 'auto');
            const modes = ['remote', 'local', 'offline', 'auto'];
            const currentIndex = modes.indexOf(current);
            const next = modes[(currentIndex + 1) % modes.length];
            await cfg.update('communicationMode', next, vscode.ConfigurationTarget.Global);
            vscode.window.showInformationMessage(`Lupopedia: Communication mode switched to ${next.toUpperCase()}`);
        })
    );
}

// ─── Deactivate ───────────────────────────────────────────────────────────────

export function deactivate(): void {
    // Subscriptions auto-disposed by VS Code
}
