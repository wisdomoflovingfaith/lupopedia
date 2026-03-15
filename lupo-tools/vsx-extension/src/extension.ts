/**
 * Lupopedia VS Code Extension — Entry Point
 *
 * Registers all commands, initialises identity storage, and manages
 * the status bar actor indicator.
 *
 * @module extension
 */

import * as vscode from 'vscode';

import { initIdentityStorage, loadIdentity, ActorIdentity, buildActorRoster, resolveEffectiveActorId, findActorFaucet } from './lupopedia/identity';
import { registerActor, lookupActor, lookupKnownActors } from './lupopedia/actor';
import { sendMessage, getMessages, joinChannel, CommMode } from './lupopedia/channels';
import { explainFile, getRelatedAtoms } from './lupopedia/semantic';
import { parseLupopediaHeader, formatLupopediaHeader, inferRelativePath } from './lupopedia/headers';
import { ChannelViewerPanel } from './webviews/channelViewer';
import { SemanticViewerPanel } from './webviews/semanticViewer';
import { HeaderEditorPanel } from './webviews/headerEditor';
import { HeaderTreeDataProvider } from './providers/headerTreeProvider';
import * as fs from 'fs';
import * as path from 'path';

// LUPOPEDIA HEADER v2 Imports
import { ArtifactIndex } from './lupopedia/headers/storage/ArtifactIndex';
import { initializeLupopedia } from './lupopedia/commands/Initialize';
import { scanWorkspace } from './lupopedia/commands/ScanWorkspace';
import { showLupopediaStatus } from './lupopedia/commands/ShowStatus';
import { forceOfflineMode } from './lupopedia/commands/ForceOffline';
import { ComplianceProvider } from './providers/complianceProvider';

// Concurrency & Persistence
import { ThreadLockManager } from './lupopedia/headers/concurrency/ThreadLock';
import { HeartbeatManager } from './lupopedia/headers/concurrency/Heartbeat';

// Panels & Collections
import { ArtifactPanel } from './panels/ArtifactPanel';
import { CollectionPanel } from './panels/CollectionPanel';
import { CollectionManager } from './lupopedia/collections';

// Scaffolded modules 4.0.74 / 4.0.75
import { loadRules } from './rules/ruleLoader';
import { validateSql } from './schema/validator';
import { generateActorName } from './actor/nameGenerator';
import { openRegistryEditor } from './actor/registryEditor';
import { detectMode } from './offline/modeDetector';
import { viewTrust } from './federation/trustViewer';
import { viewLogs } from './logs/unifiedLogViewer';
import { viewHealth } from './health/snapshotDashboard';
import { HeaderParser } from './lupopedia/headers/parser/HeaderParser';
import { FooterParser } from './lupopedia/headers/parser/FooterParser';
import { HeaderArtifact } from './lupopedia/headers/parser/types';
import { HeaderQueryEngine } from './lupopedia/headers/query/QueryEngine';
import { SemanticEventBus } from './lupopedia/headers/concurrency/EventBus';

// v4.1 Services
import { MetadataService } from './lupopedia/headers/logic/MetadataService';
import { RepairService } from './lupopedia/headers/logic/RepairService';
import { DelegationPanel } from './panels/DelegationPanel';
import { SemanticMapPanel } from './panels/SemanticMapPanel';

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

async function requireIdentity(
    statusBar: vscode.StatusBarItem
): Promise<ActorIdentity | null> {
    const identity = await resolveEffectiveActorId();
    if (!identity) {
        // Fallback to internal lookup if resolution fails (rare)
        const stored = loadIdentity();
        if (stored) return stored;

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
    // Update status bar with resolved identity
    updateStatusBar(statusBar, identity);
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

    const headerTreeProvider = new HeaderTreeDataProvider(rootPath);
    vscode.window.registerTreeDataProvider('lupopedia.doctrine', headerTreeProvider);

    // LUPOPEDIA HEADER v2 Components
    const artifactIndex = new ArtifactIndex(ctx);
    await artifactIndex.initialize();

    const lockManager = new ThreadLockManager();
    const heartbeatManager = new HeartbeatManager();
    const metadataService = new MetadataService();
    const repairService = new RepairService();

    // ── Version 4.0.40 Compliance Gate ────────────────────────────────────────
    new ComplianceProvider(ctx);

    // ── Version 4.0.75 Initialization ─────────────────────────────────────────
    loadRules();
    validateSql();
    detectMode();
    generateActorName('antigravity', 'ide');

    // ── Command: Initialize ──────────────────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.initialize', () =>
            initializeLupopedia(ctx, artifactIndex)
        )
    );

    // ── Command: Lock File ───────────────────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.acquireLock', async () => {
            const editor = vscode.window.activeTextEditor;
            if (!editor) return;

            const id = await resolveEffectiveActorId();
            if (!id) return;

            const success = await lockManager.acquireLock(
                editor.document.uri.fsPath,
                id.actor_id,
                'T-37-01',
                'development'
            );

            if (success) {
                vscode.window.showInformationMessage(`Lupopedia: File locked for editing.`);
            } else {
                vscode.window.showErrorMessage(`Lupopedia: Failed to acquire lock. File is busy.`);
            }
        })
    );

    // ── Command: Unlock File ─────────────────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.releaseLock', async () => {
            const editor = vscode.window.activeTextEditor;
            if (!editor) return;

            const id = await resolveEffectiveActorId();
            if (!id) return;

            const success = await lockManager.releaseLock(editor.document.uri.fsPath, id.actor_id);
            if (success) {
                vscode.window.showInformationMessage(`Lupopedia: File unlocked.`);
            }
        })
    );

    // ── Command: Scan Workspace ──────────────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.scan', async () => {
            await vscode.window.withProgress(
                { location: vscode.ProgressLocation.Notification },
                async (progress) => {
                    progress.report({ message: 'Scanning workspace for LUPOPEDIA HEADER artifacts...' });
                    const results = await scanWorkspace(ctx, artifactIndex);
                    vscode.window.showInformationMessage(
                        `Scan complete: ${results.filesScanned} files, ` +
                        `${results.artifactsUpdated} artifacts updated`
                    );
                }
            );
        })
    );

    // ── Command: Show Status ─────────────────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.status', () =>
            showLupopediaStatus(artifactIndex)
        )
    );

    // ── Command: Force Offline Mode ──────────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.forceOffline', forceOfflineMode)
    );

    // ── Command: Show Artifact Details ───────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.showArtifactDetails', async (artifactOrPath?: any) => {
            const hParser = new HeaderParser();
            const fParser = new FooterParser();

            let fsPath = '';
            if (typeof artifactOrPath === 'string') {
                fsPath = artifactOrPath;
            } else if (artifactOrPath && artifactOrPath.id) {
                fsPath = artifactOrPath.id;
            } else {
                const editor = vscode.window.activeTextEditor;
                if (!editor) return;
                fsPath = editor.document.uri.fsPath;
            }

            try {
                const record = await artifactIndex.findByPath(fsPath);
                if (record) {
                    ArtifactPanel.createOrShow(ctx.extensionUri, {
                        filePath: record.id,
                        header: JSON.parse(record.headerJson),
                        footer: record.footerJson ? JSON.parse(record.footerJson) : undefined
                    });
                } else {
                    // Try parsing live
                    const content = fs.readFileSync(fsPath, 'utf-8');
                    const header = hParser.extractHeader(content);
                    const footer = fParser.extractFooter(content);
                    if (header) {
                        ArtifactPanel.createOrShow(ctx.extensionUri, {
                            filePath: fsPath,
                            header,
                            footer: footer || undefined
                        });
                    }
                }
            } catch (err) {
                vscode.window.showErrorMessage(`Failed to show artifact details: ${err}`);
            }
        })
    );

    // ── Command: Inspect Delegation (v4.1) ──────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.inspectDelegation', async () => {
            const editor = vscode.window.activeTextEditor;
            if (!editor) return;

            const index = await metadataService.parseMetadataBlock(editor.document.uri);
            if (index) {
                DelegationPanel.createOrShow(ctx.extensionUri, index, editor.document.uri.fsPath);
            } else {
                vscode.window.showErrorMessage('Lupopedia: Failed to parse delegation data for this file.');
            }
        })
    );

    // ── Command: Show Semantic Map (v4.1) ───────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.showSemanticMap', async () => {
            const editor = vscode.window.activeTextEditor;
            if (!editor) return;

            const index = await metadataService.parseMetadataBlock(editor.document.uri);
            if (index) {
                SemanticMapPanel.createOrShow(ctx.extensionUri, index, editor.document.uri.fsPath);
            } else {
                vscode.window.showErrorMessage('Lupopedia: Failed to parse semantic map for this file.');
            }
        })
    );

    // ── Command: Normalize Metadata (v4.1) ──────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.normalizeMetadata', async () => {
            const editor = vscode.window.activeTextEditor;
            if (!editor) return;
            const success = await repairService.normalizeMetadata(editor.document);
            if (success) vscode.window.showInformationMessage('Lupopedia: Metadata normalized.');
        })
    );

    // ── Command: Repair Delegation Chain (v4.1) ────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.repairDelegationChain', async () => {
            const editor = vscode.window.activeTextEditor;
            if (!editor) return;
            const identity = await resolveEffectiveActorId();
            if (!identity) return;
            const success = await repairService.repairDelegationChain(editor.document, identity.actor_id);
            if (success) vscode.window.showInformationMessage('Lupopedia: Delegation chain repaired.');
        })
    );

    // ── Command: List Collections ─────────────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.listCollections', async () => {
            const colManager = new CollectionManager(artifactIndex);
            const collections = await colManager.getAllCollections();

            const items = collections.map(c => ({
                label: c.title,
                description: c.id,
                detail: c.description || c.filePath,
                collection: c
            }));

            const choice = await vscode.window.showQuickPick(items, {
                placeHolder: 'Select a collection to browse'
            });

            if (choice) {
                const artifacts = await colManager.getArtifactsInCollection(choice.collection.id);
                CollectionPanel.createOrShow(ctx.extensionUri, choice.collection, artifacts);
            }
        })
    );

    // ── Command: Search Artifacts ─────────────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.searchArtifacts', async () => {
            const query = await vscode.window.showInputBox({
                prompt: 'Search by type, collection, version, or keyword',
                placeHolder: 'e.g. type:directive, collection:v4.0.37'
            });
            if (!query) return;

            const records = await artifactIndex.findRecent('20000101');
            let results = records;

            // Simple parser for filters e.g. "type:directive"
            const filters = query.match(/(\w+):(\S+)/g);
            const keyword = query.replace(/(\w+):(\S+)/g, '').trim().toLowerCase();

            if (filters) {
                for (const f of filters) {
                    const [key, val] = f.split(':');
                    const v = val.toLowerCase();
                    if (key === 'type') results = results.filter(r => r.artifactType?.toLowerCase() === v);
                    if (key === 'kind') results = results.filter(r => r.artifactKind?.toLowerCase() === v);
                    if (key === 'collection') results = results.filter(r => r.collectionId?.toLowerCase() === v);
                    if (key === 'version') results = results.filter(r => r.version?.toLowerCase().includes(v));
                }
            }

            if (keyword) {
                results = results.filter(r =>
                    r.id.toLowerCase().includes(keyword) ||
                    r.headerJson.toLowerCase().includes(keyword)
                );
            }

            const items = results.map(r => ({
                label: path.basename(r.id),
                description: r.artifactType || 'file',
                detail: r.id,
                record: r
            }));

            const choice = await vscode.window.showQuickPick(items, {
                placeHolder: `Found ${results.length} artifacts`
            });

            if (choice) {
                vscode.commands.executeCommand('lupopedia.showArtifactDetails', choice.record.id);
            }
        })
    );

    // ── Command: Header Query (v3) ──────────────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.headerQuery', async () => {
            const queryEngine = new HeaderQueryEngine(artifactIndex);
            const dsl = await vscode.window.showInputBox({
                prompt: 'Enter Header Query DSL',
                placeHolder: 'e.g. relations inbound from QUICKSTART.md'
            });
            if (!dsl) return;

            try {
                const results = await queryEngine.query(dsl);
                if (results.matched.length === 0) {
                    vscode.window.showInformationMessage(`No matches found for: ${dsl}`);
                    return;
                }

                const items = results.matched.map(r => ({
                    label: path.basename(r.id),
                    description: r.artifactType || 'artifact',
                    detail: r.id,
                    record: r
                }));

                const choice = await vscode.window.showQuickPick(items, {
                    placeHolder: `Query found ${results.matched.length} results (${results.executionTimeMs}ms)`
                });

                if (choice) {
                    vscode.commands.executeCommand('lupopedia.showArtifactDetails', choice.record.id);
                }
            } catch (err) {
                vscode.window.showErrorMessage(`Query failed: ${err}`);
            }
        })
    );

    // ── Semantic Event Bus Setup ─────────────────────────────────────────────
    const bus = SemanticEventBus.getInstance();
    ctx.subscriptions.push(bus.onEvent(event => {
        vscode.window.setStatusBarMessage(`Semantic Event: ${event.type} on ${path.basename(event.file_path)}`, 3000);
    }));

    // Auto-initialize if workspace is Lupopedia project
    if (isLupopediaWorkspace()) {
        initializeLupopedia(ctx, artifactIndex);
    }

    // ── Command: Open Lupopedia Header file ─────────────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.openHeaderFile', (fsPath: string) => {
            vscode.workspace.openTextDocument(fsPath).then(doc => {
                vscode.window.showTextDocument(doc);
            });
        })
    );

    // ── Command: Refresh Doctrine View ──────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.refreshDoctrine', () => {
            headerTreeProvider.refresh();
        })
    );

    // ── Command: Log Agent Action ───────────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.logAction', async () => {
            const identity = await requireIdentity(statusBar);
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
                const result = parseLupopediaHeader(editor.document.getText());
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
                headerTreeProvider.refresh();
            } catch (err: unknown) {
                vscode.window.showErrorMessage(`Failed to log action: ${err instanceof Error ? err.message : String(err)}`);
            }
        })
    );

    // ── Command: Validate LUPOPEDIA Header ───────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.validateLupopediaHeader', () => {
            const editor = vscode.window.activeTextEditor;
            if (!editor || editor.document.languageId !== 'markdown') {
                vscode.window.showWarningMessage('Please open a Markdown file to validate its LUPOPEDIA Header.');
                return;
            }

            const text = editor.document.getText();
            const result = parseLupopediaHeader(text);

            if (result.valid && result.header) {
                const h = result.header;
                vscode.window.showInformationMessage(
                    `✅ LUPOPEDIA Header Valid: ${h.file_path_from_root} (v${h.file_last_modified_system_version})`
                );
            } else {
                const msg = result.errors.join(' | ');
                vscode.window.showErrorMessage(`❌ LUPOPEDIA Header Invalid: ${msg}`);
            }
        })
    );

    // ── Global Document Watcher: LUPOPEDIA HEADER Enforcement ────────────────────────────
    ctx.subscriptions.push(
        vscode.workspace.onWillSaveTextDocument(async (event) => {
            if (event.document.languageId !== 'markdown') { return; }
            if (!rootPath) { return; }

            const fsPath = event.document.uri.fsPath;
            if (!fsPath.includes(`${path.sep}docs${path.sep}`)) { return; }

            const text = event.document.getText();
            const result = parseLupopediaHeader(text);
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

                // 2. Enforce Actor Trinity and Delegation (Lupopedia 4.0.55+)
                const identity = await resolveEffectiveActorId();
                if (updatedHeader.actor_id !== identity.actor_id) {
                    updatedHeader.actor_id = identity.actor_id;
                    needsUpdate = true;
                }
                if (updatedHeader.delegation_chain !== identity.delegation_chain) {
                    updatedHeader.delegation_chain = identity.delegation_chain;
                    needsUpdate = true;
                }
                if (!updatedHeader.from) {
                    updatedHeader.from = identity.actor_name;
                    needsUpdate = true;
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
                        updatedHeader.tags = '["lupopedia", "doctrine", "offline"]';
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

                    const newHeaderText = formatLupopediaHeader(updatedHeader);
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
                        headerTreeProvider.invalidate(fsPath);

                        // Auto-log the update action
                        vscode.commands.executeCommand('lupopedia.internalLog', {
                            actor_id: updatedHeader.actor_id || 0,
                            actor_name: updatedHeader.lupo_actor_identity || updatedHeader.from || 'System (LUPOPEDIA HEADER Auto-Update)',
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
                headerTreeProvider.refresh();
            } catch (err) { }
        })
    );

    // ── Startup: Resolve effective identity ──────────────────────────────
    let identity = await resolveEffectiveActorId();
    updateStatusBar(statusBar, identity);
    if (identity) {
        // Resolve faucet if available
        const faucet = await findActorFaucet(identity.actor_id);
        if (faucet) {
            identity.faucet = faucet;
            ctx.globalState.update('lupopedia.current_faucet', faucet);
        }
        heartbeatManager.start(identity.actor_id, identity.actor_name);
    }

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
            const id = await requireIdentity(statusBar);
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
            const id = await requireIdentity(statusBar);
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
            const id = await requireIdentity(statusBar);
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
            const id = await requireIdentity(statusBar);
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
            const id = await requireIdentity(statusBar);
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

    // ── Command: Validate LUPOPEDIA Header ─────────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.validateLupopediaHeader', async () => {
            const editor = vscode.window.activeTextEditor;
            if (!editor) {
                vscode.window.showWarningMessage('Lupopedia: No active file to validate.');
                return;
            }

            const text = editor.document.getText();
            const result = parseLupopediaHeader(text);

            if (result.valid && result.header) {
                const formatted = formatLupopediaHeader(result.header);
                // Show the LUPOPEDIA HEADER editor for valid headers (identity = effective logged-in user)
                const identity = await resolveEffectiveActorId();
                HeaderEditorPanel.createOrShow(
                    ctx.extensionUri,
                    result.header,
                    getConfig().baseUrl,
                    identity
                );
                vscode.window.showInformationMessage(
                    `Lupopedia: LUPOPEDIA Header is valid! Channel: ${result.header.channel_id ?? 'unresolved'}`
                );
            } else {
                const errList = result.errors.join('\n• ');
                vscode.window.showErrorMessage(
                    `Lupopedia: LUPOPEDIA Header invalid:\n• ${errList}`
                );
            }
        })
    );

    // ── Command: Get VSX Extension Status ─────────────────────────────────────
    ctx.subscriptions.push(
        vscode.commands.registerCommand('lupopedia.getStatus', async () => {
            const { communicationMode } = getConfig();
            const identity = await resolveEffectiveActorId();
            const status = {
                vsx_extension_status: communicationMode === 'offline' ? 'md_only' : 'hybrid',
                actor_id: identity.actor_id,
                lupo_agent: 'cursor',
                timestamp: new Date().toISOString().replace(/[-:T]/g, '').slice(0, 8),
                capabilities: [
                    'md_registry_loading',
                    'md_channel_discovery',
                    'lupo_metadata_persistence'
                ]
            };
            return status;
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

            // Auto-report status change
            const reportPath = path.join(rootPath, 'docs', 'status', 'antigravity_vsx_extension_update_4_0_35.md');
            if (fs.existsSync(reportPath)) {
                let content = fs.readFileSync(reportPath, 'utf-8');
                content = content.replace(/MD-only mode status: .*/, `MD-only mode status: ${next === 'offline' ? 'ACTIVE' : 'INACTIVE'}`);
                fs.writeFileSync(reportPath, content, 'utf-8');
            }
        })
    );

    // ── Global Message Listener (Unified) ────────────────────────────────────
    ctx.subscriptions.push(
        vscode.window.onDidChangeActiveTextEditor(e => {
            // Optional: update panels on editor change
        })
    );

    // We need a way to capture messages from our new panels.
    // Since current implementation of Panels doesn't expose the webview easily in a global way,
    // we normally would add the listener in the Panel class or a factory.
    // For this implementation, I will add a simple static message handler registration.
}

// ─── Deactivate ───────────────────────────────────────────────────────────────

export function deactivate(): void {
    // Subscriptions auto-disposed by VS Code
}

function isLupopediaWorkspace(): boolean {
    // Check for lupopedia-specific markers
    // e.g., presence of docs/doctrine/ or channels/42/
    if (!vscode.workspace.workspaceFolders) return false;

    const root = vscode.workspace.workspaceFolders[0].uri.fsPath;
    return fs.existsSync(path.join(root, 'docs', 'doctrine')) ||
        fs.existsSync(path.join(root, 'channels', '42'));
}
