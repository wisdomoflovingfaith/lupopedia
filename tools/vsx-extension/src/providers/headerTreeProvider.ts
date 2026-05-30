/**
 * LUPOPEDIA HEADER TreeView Provider — hierarchical navigation of doctrine files by LUPOPEDIA Headers
 *
 * Scans docs/ directories, parses LUPOPEDIA Headers, and displays files grouped by:
 * - Status (Active/Proposed/Deprecated)
 * - Channel ID
 * - Thread links
 *
 * @module providers/headerTreeProvider
 */

import * as vscode from 'vscode';
import * as fs from 'fs';
import * as path from 'path';
import { parseLupopediaHeader, LupopediaHeader } from '../lupopedia/headers';

export interface HeaderFileInfo {
    /** Absolute filesystem path */
    fsPath: string;
    /** Relative path from workspace root */
    relativePath: string;
    /** Parsed LUPOPEDIA Header (null if parse failed) */
    header: LupopediaHeader | null;
    /** Parse errors */
    errors: string[];
}

type GroupBy = 'status' | 'channel' | 'node' | 'flat' | 'activity';

interface AgentAction {
    actor_id: number;
    actor_name: string;
    action: string;
    timestamp: string;
    thread_id: string;
}

export class HeaderTreeDataProvider implements vscode.TreeDataProvider<HeaderTreeItem> {
    private _onDidChangeTreeData = new vscode.EventEmitter<HeaderTreeItem | undefined | null>();
    readonly onDidChangeTreeData = this._onDidChangeTreeData.event;

    private files: HeaderFileInfo[] = [];
    private actions: AgentAction[] = [];
    private groupBy: GroupBy = 'status';
    private headerCache = new Map<string, { header: LupopediaHeader | null, mtime: number }>();
    private channelRegistry: Record<string, any> = {};

    constructor(private workspaceRoot: string) {
        this.loadChannelRegistry();
        this.refresh();
    }

    refresh(): void {
        this.files = this.scanWorkspace();
        this.actions = this.loadActions();
        this._onDidChangeTreeData.fire(undefined);
    }

    /** Invalidate cache for a specific file */
    invalidate(fsPath: string): void {
        this.headerCache.delete(fsPath);
    }

    setGroupBy(mode: GroupBy): void {
        this.groupBy = mode;
        this._onDidChangeTreeData.fire(undefined);
    }

    getTreeItem(element: HeaderTreeItem): vscode.TreeItem {
        return element;
    }

    getChildren(element?: HeaderTreeItem): Thenable<HeaderTreeItem[]> {
        if (!element) {
            // Root level: groups or flat list
            const roots = this.getRootItems();
            // Always prepend Activity group if not in flat mode
            if (this.groupBy !== 'flat') {
                const activityGroup = this.createActivityGroup();
                if (activityGroup) {
                    return Promise.resolve([activityGroup, ...roots]);
                }
            }
            return Promise.resolve(roots);
        }
        // Children of a group
        if (element.type === 'group') {
            return Promise.resolve(element.children || []);
        }
        return Promise.resolve([]);
    }

    private getRootItems(): HeaderTreeItem[] {
        if (this.groupBy === 'flat') {
            return this.files.map(f => this.createFileItem(f));
        }
        if (this.groupBy === 'status') {
            return this.groupByStatus();
        }
        if (this.groupBy === 'channel') {
            return this.groupByChannel();
        }
        if (this.groupBy === 'node') {
            return this.groupByNode();
        }
        if (this.groupBy === 'activity') {
            return this.files.map(f => this.createFileItem(f)); // Fallback
        }
        return this.groupByActor();
    }

    private groupByStatus(): HeaderTreeItem[] {
        const groups = new Map<string, HeaderFileInfo[]>();
        for (const file of this.files) {
            const status = file.header?.status || 'Unknown';
            if (!groups.has(status)) {
                groups.set(status, []);
            }
            groups.get(status)!.push(file);
        }

        const order = ['Active', 'Proposed', 'Deprecated', 'Unknown'];
        const items: HeaderTreeItem[] = [];
        for (const status of order) {
            if (groups.has(status)) {
                const files = groups.get(status)!;
                items.push(this.createGroupItem(status, files));
            }
        }
        // Add any other statuses
        for (const [status, files] of groups.entries()) {
            if (!order.includes(status)) {
                items.push(this.createGroupItem(status, files));
            }
        }
        return items;
    }

    private groupByChannel(): HeaderTreeItem[] {
        const groups = new Map<string, HeaderFileInfo[]>();
        for (const file of this.files) {
            const channelId = file.header?.channel_id ?? null;
            let key = 'No Channel';
            if (channelId !== null) {
                const reg = this.channelRegistry[String(channelId)];
                key = reg ? `Channel ${channelId}: ${reg.name}` : `Channel ${channelId} (Unregistered)`;
            }
            if (!groups.has(key)) {
                groups.set(key, []);
            }
            groups.get(key)!.push(file);
        }

        const items: HeaderTreeItem[] = [];
        for (const [label, files] of Array.from(groups.entries()).sort()) {
            items.push(this.createGroupItem(label, files));
        }
        return items;
    }

    private groupByNode(): HeaderTreeItem[] {
        const groups = new Map<string, HeaderFileInfo[]>();
        for (const file of this.files) {
            const nodeId = file.header?.federated_node_id ?? null;
            const key = nodeId !== null ? `Node ${nodeId}` : 'Local Node (1)';
            if (!groups.has(key)) {
                groups.set(key, []);
            }
            groups.get(key)!.push(file);
        }

        const items: HeaderTreeItem[] = [];
        for (const [label, files] of Array.from(groups.entries()).sort()) {
            items.push(this.createGroupItem(label, files));
        }
        return items;
    }

    private groupByActor(): HeaderTreeItem[] {
        const groups = new Map<string, HeaderFileInfo[]>();
        for (const file of this.files) {
            const h = file.header;
            let key = 'No Actor';
            if (h) {
                if (h.actor_id !== null) key = `Actor ${h.actor_id}`;
                else if (h.lupo_actor_identity) key = h.lupo_actor_identity;
                else if (h.from) key = h.from;
            }
            if (!groups.has(key)) {
                groups.set(key, []);
            }
            groups.get(key)!.push(file);
        }

        const items: HeaderTreeItem[] = [];
        for (const [label, files] of Array.from(groups.entries()).sort()) {
            items.push(this.createGroupItem(label, files));
        }
        return items;
    }

    private createGroupItem(label: string, files: HeaderFileInfo[]): HeaderTreeItem {
        const item = new HeaderTreeItem(
            label,
            vscode.TreeItemCollapsibleState.Collapsed,
            'group'
        );
        item.children = files.map(f => this.createFileItem(f));
        item.description = `${files.length} file${files.length !== 1 ? 's' : ''}`;
        item.iconPath = new vscode.ThemeIcon('folder');
        return item;
    }

    private createActivityGroup(): HeaderTreeItem | null {
        if (this.actions.length === 0) {
            return null;
        }

        const item = new HeaderTreeItem(
            'Channel 42 Activity',
            vscode.TreeItemCollapsibleState.Expanded,
            'group'
        );

        // Sort by timestamp descending
        const sortedActions = [...this.actions].sort((a, b) => b.timestamp.localeCompare(a.timestamp));

        item.children = sortedActions.map(action => {
            const label = `${action.actor_name}: ${action.action}`;
            const sub = new HeaderTreeItem(label, vscode.TreeItemCollapsibleState.None, 'group');
            sub.description = this.formatTimestamp(action.timestamp);
            sub.tooltip = `Actor ID: ${action.actor_id}\nThread: ${action.thread_id}`;
            sub.iconPath = new vscode.ThemeIcon('pulse');
            return sub;
        });

        item.iconPath = new vscode.ThemeIcon('history');
        item.contextValue = 'activityGroup';
        return item;
    }

    private createFileItem(file: HeaderFileInfo): HeaderTreeItem {
        const basename = path.basename(file.relativePath);
        const item = new HeaderTreeItem(
            basename,
            vscode.TreeItemCollapsibleState.None,
            'file'
        );
        item.resourceUri = vscode.Uri.file(file.fsPath);
        item.command = {
            command: 'lupopedia.openHeaderFile',
            title: 'Open Lupopedia Header file',
            arguments: [file.fsPath],
        };

        // Tooltip with LUPOPEDIA HEADER metadata
        const tooltip = this.buildTooltip(file);
        item.tooltip = tooltip;

        // Description shows version or status
        if (file.header) {
            const version = file.header.file_last_modified_system_version;
            const status = file.header.status || '';
            item.description = status ? `${version} (${status})` : version;
        } else {
            item.description = '⚠️ No LUPOPEDIA Header';
        }

        // Icon based on status or missing headers
        const isMissingPath = !file.header?.file_path_from_root;
        const isMissingAttribution = file.header && !file.header.actor_id && !file.header.lupo_actor_identity && !file.header.from;

        if (isMissingPath) {
            item.iconPath = new vscode.ThemeIcon('error', new vscode.ThemeColor('problemsErrorIcon.foreground'));
            item.description = '❌ Missing Root Path';
        } else if (isMissingAttribution) {
            item.iconPath = new vscode.ThemeIcon('warning', new vscode.ThemeColor('problemsWarningIcon.foreground'));
            item.description = '⚠️ Missing Attribution';
        } else if (file.header?.status === 'Deprecated') {
            item.iconPath = new vscode.ThemeIcon('warning', new vscode.ThemeColor('problemsWarningIcon.foreground'));
        } else if (file.header?.status === 'Active') {
            item.iconPath = new vscode.ThemeIcon('check', new vscode.ThemeColor('testing.iconPassed'));
        } else if (file.header?.status === 'Proposed') {
            item.iconPath = new vscode.ThemeIcon('lightbulb');
        } else {
            // Attribution-based icons
            if (file.header?.actor_id) {
                item.iconPath = new vscode.ThemeIcon('database');
            } else if (file.header?.lupo_actor_identity) {
                item.iconPath = new vscode.ThemeIcon('verified');
            } else if (file.header?.from) {
                item.iconPath = new vscode.ThemeIcon('account');
            } else {
                item.iconPath = vscode.ThemeIcon.File;
            }
        }

        return item;
    }

    private buildTooltip(file: HeaderFileInfo): vscode.MarkdownString {
        const md = new vscode.MarkdownString();
        md.isTrusted = true;
        md.appendMarkdown(`**${file.relativePath}**\n\n`);

        if (!file.header) {
            md.appendMarkdown('⚠️ **No LUPOPEDIA Header found**\n\n');
            if (file.errors.length > 0) {
                md.appendMarkdown('**Errors:**\n');
                for (const err of file.errors) {
                    md.appendMarkdown(`- ${err}\n`);
                }
            }
            return md;
        }

        const h = file.header;

        // Identity & Core
        md.appendMarkdown(`| Channel | Actor | Version |\n`);
        md.appendMarkdown(`| :--- | :--- | :--- |\n`);

        const actorLabel = h.actor_id
            ? `ID ${h.actor_id}`
            : (h.lupo_actor_identity || h.from || 'N/A');

        md.appendMarkdown(`| ${h.channel_id ?? 'N/A'} | ${actorLabel} | ${h.file_last_modified_system_version} |\n\n`);

        if (h.lupo_actor_identity && h.actor_id) md.appendMarkdown(`**Identity:** ${h.lupo_actor_identity}\n\n`);
        if (h.from) md.appendMarkdown(`**From:** ${h.from}\n\n`);

        if (h.status) md.appendMarkdown(`**Status:** ${h.status}\n\n`);
        if (h.thread_id) md.appendMarkdown(`**Thread:** ${h.thread_id}\n\n`);
        if (h.lupo_actor_to) md.appendMarkdown(`**To Actor:** ${h.lupo_actor_to}\n\n`);

        // Survivor Protocol
        if (h.lupo_survivor_protocol === 'active') {
            md.appendMarkdown(`--- \n`);
            md.appendMarkdown(`🛡️ **Survivor Protocol Active**\n\n`);
            if (h.lupo_collapse_ratio) md.appendMarkdown(`- **Collapse Ratio:** ${h.lupo_collapse_ratio}\n`);
            if (h.lupo_forward_chain) md.appendMarkdown(`- **Chain:** \`${h.lupo_forward_chain}\`\n`);
        }

        // Operational/Registry
        if (h.lupo_task || h.lupo_doctrine) {
            md.appendMarkdown(`--- \n`);
            if (h.lupo_task) md.appendMarkdown(`**Task:** ${h.lupo_task}\n\n`);
            if (h.lupo_doctrine) md.appendMarkdown(`**Doctrine:** \`${h.lupo_doctrine}\`\n\n`);
        }

        md.appendMarkdown(`--- \n`);
        md.appendMarkdown(`*Modified: ${this.formatTimestamp(h.file_last_modified_utc)}*\n`);

        return md;
    }

    private formatTimestamp(utc: string): string {
        // YYYYMMDDHHMMSS -> YYYY-MM-DD HH:MM:SS
        if (utc.length !== 14) {
            return utc;
        }
        return `${utc.slice(0, 4)}-${utc.slice(4, 6)}-${utc.slice(6, 8)} ${utc.slice(8, 10)}:${utc.slice(10, 12)}:${utc.slice(12, 14)}`;
    }

    scanWorkspace(): HeaderFileInfo[] {
        const results: HeaderFileInfo[] = [];
        if (!this.workspaceRoot) {
            return results;
        }

        const scanDirs = [
            path.join(this.workspaceRoot, 'channels'),
            path.join(this.workspaceRoot, 'artifacts'),
            path.join(this.workspaceRoot, 'docs')
        ];

        const scan = (dir: string) => {
            try {
                if (!fs.existsSync(dir)) return;
                const entries = fs.readdirSync(dir, { withFileTypes: true });
                for (const entry of entries) {
                    const fullPath = path.join(dir, entry.name);
                    if (entry.isDirectory()) {
                        scan(fullPath);
                    } else if (entry.isFile() && entry.name.endsWith('.md')) {
                        const relativePath = path.relative(this.workspaceRoot, fullPath);
                        const stats = fs.statSync(fullPath);
                        const mtime = stats.mtimeMs;

                        const cached = this.headerCache.get(fullPath);
                        if (cached && cached.mtime === mtime) {
                            results.push({
                                fsPath: fullPath,
                                relativePath: relativePath.replace(/\\/g, '/'),
                                header: cached.header,
                                errors: []
                            });
                            continue;
                        }

                        try {
                            const content = fs.readFileSync(fullPath, 'utf8');
                            const result = parseLupopediaHeader(content);

                            this.headerCache.set(fullPath, {
                                header: result.header,
                                mtime: mtime
                            });

                            results.push({
                                fsPath: fullPath,
                                relativePath: relativePath.replace(/\\/g, '/'),
                                header: result.header,
                                errors: result.errors,
                            });
                        } catch (err) {
                            results.push({
                                fsPath: fullPath,
                                relativePath: relativePath.replace(/\\/g, '/'),
                                header: null,
                                errors: [`Failed to read file: ${err instanceof Error ? err.message : String(err)}`],
                            });
                        }
                    }
                }
            } catch (err) {
                console.error(`Error scanning directory ${dir}:`, err);
            }
        };

        for (const d of scanDirs) {
            scan(d);
        }
        return results;
    }

    private loadActions(): AgentAction[] {
        const logPath = path.join(this.workspaceRoot, 'docs', 'channel42_log.json');
        if (!fs.existsSync(logPath)) {
            return [];
        }
        try {
            const raw = fs.readFileSync(logPath, 'utf-8');
            const data = JSON.parse(raw);
            return data.actions || [];
        } catch (err) {
            console.error('Failed to load actions:', err);
            return [];
        }
    }

    private loadChannelRegistry() {
        const registryPath = path.join(this.workspaceRoot, 'channels', 'registry.json');
        if (fs.existsSync(registryPath)) {
            try {
                const raw = fs.readFileSync(registryPath, 'utf-8');
                this.channelRegistry = JSON.parse(raw);
            } catch (e) {
                console.error('Failed to load channel registry:', e);
            }
        }
    }

    /**
     * Search for Lupopedia Header files by keyword (in path or LUPOPEDIA HEADER fields)
     */
    search(query: string): HeaderFileInfo[] {
        const lowerQuery = query.toLowerCase();
        return this.files.filter(f => {
            if (f.relativePath.toLowerCase().includes(lowerQuery)) {
                return true;
            }
            if (!f.header) {
                return false;
            }
            const h = f.header;
            if (h.file_path_from_root.toLowerCase().includes(lowerQuery)) {
                return true;
            }
            for (const [k, v] of Object.entries(h.extras)) {
                if (v.toLowerCase().includes(lowerQuery)) {
                    return true;
                }
            }
            return false;
        });
    }

    /**
     * Get files by status
     */
    getByStatus(status: string): HeaderFileInfo[] {
        return this.files.filter(f => f.header?.extras?.status === status);
    }

    /**
     * Get files by channel ID
     */
    getByChannel(channelId: number): HeaderFileInfo[] {
        return this.files.filter(f => f.header?.channel_id === channelId);
    }

    /**
     * Get files linked to a thread
     */
    getByThread(threadId: string): HeaderFileInfo[] {
        return this.files.filter(f => f.header?.extras?.thread_id === threadId);
    }

    /**
     * Get all files (for export/reporting)
     */
    getAllFiles(): HeaderFileInfo[] {
        return this.files;
    }
}

export class HeaderTreeItem extends vscode.TreeItem {
    children?: HeaderTreeItem[];

    constructor(
        public readonly label: string,
        public readonly collapsibleState: vscode.TreeItemCollapsibleState,
        public readonly type: 'group' | 'file'
    ) {
        super(label, collapsibleState);
    }
}
