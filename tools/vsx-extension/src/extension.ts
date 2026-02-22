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
import { parseFlipHeader, formatFlipHeader } from './lupopedia/flip';
import { ChannelViewerPanel } from './webviews/channelViewer';
import { SemanticViewerPanel } from './webviews/semanticViewer';
import { FlipEditorPanel } from './webviews/flipEditor';

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
