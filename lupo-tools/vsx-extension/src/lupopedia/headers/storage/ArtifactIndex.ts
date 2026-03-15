// src/lupopedia/headers/storage/ArtifactIndex.ts

import * as vscode from 'vscode';
import { HeaderArtifact, AgentPresence } from '../parser/types';

export interface ArtifactRecord {
    id: string;  // file path as key
    fileHash: string;
    headerJson: string;
    footerJson: string;
    indexedAt: string;
    lastModified: string;
    channelId?: number;
    federatedNodeId?: number;
    actorId?: number;
    agentKey?: string;
    version: string;
    artifactType?: string;
    artifactKind?: string;
    collectionId?: string;
    delegationChain?: string;
}

/**
 * Robust artifact index.
 * Uses VS Code's Memento (workspaceState) for persistence, 
 * simulating the IndexedDB behavior requested in the directive.
 */
export class ArtifactIndex {
    private context: vscode.ExtensionContext;
    private readonly STORAGE_KEY = 'lupopedia.artifacts.v2';

    constructor(context: vscode.ExtensionContext) {
        this.context = context;
    }

    /**
     * Initialize storage
     */
    public async initialize(): Promise<void> {
        // No special setup needed for Memento
        return Promise.resolve();
    }

    /**
     * Store or update artifact
     */
    public async storeArtifact(artifact: HeaderArtifact): Promise<void> {
        const artifacts = this.getAllRecords();

        let lastModified = '';
        let systemVersion = '';
        let channelId: number | undefined;
        let federatedNodeId: number | undefined;
        let actorId: number | undefined;
        let agentKey: string | undefined;
        let artifactType: string | undefined;
        let artifactKind: string | undefined;
        let collectionId: string | undefined;
        let delegationChain: string | undefined;

        if ('identity' in artifact.header) {
            // v3 support
            const h = artifact.header;
            lastModified = new Date().toISOString().slice(0, 10).replace(/-/g, '');
            systemVersion = h.identity.system_version;
            channelId = h.identity.channel_id;
            federatedNodeId = h.identity.federated_node_id;
            actorId = h.identity.execution_agent;
            agentKey = h.identity.agent_slug;
            artifactType = h.classification.artifact_type;
            artifactKind = h.classification.artifact_kind;
            delegationChain = h.identity.delegation_chain;
        } else {
            // v2 support
            const h = artifact.header.lupopedia.headers;
            lastModified = h.last_modified;
            systemVersion = h.system_version;
            channelId = h.channel_id;
            federatedNodeId = h.federated_node_id;
            actorId = artifact.header.lupo?.agent?.tracking?.actor_id || h.actor_id;
            agentKey = artifact.header.lupo?.agent?.tracking?.agent_key;
            artifactType = h.artifact_type;
            artifactKind = h.artifact_kind;
            collectionId = h.collection_id;
            delegationChain = h.delegation_chain || h.x_lupo_forwarded;
        }

        const record: ArtifactRecord = {
            id: artifact.filePath,
            fileHash: artifact.fileHash,
            headerJson: JSON.stringify(artifact.header),
            footerJson: artifact.footer ? JSON.stringify(artifact.footer) : '',
            indexedAt: artifact.indexedAt,
            lastModified,
            version: systemVersion,
            channelId,
            federatedNodeId,
            actorId,
            agentKey,
            artifactType,
            artifactKind,
            collectionId,
            delegationChain
        };

        artifacts[record.id] = record;
        await this.context.workspaceState.update(this.STORAGE_KEY, artifacts);
    }

    /**
     * Find artifact by file path
     */
    public async findByPath(filePath: string): Promise<ArtifactRecord | null> {
        const artifacts = this.getAllRecords();
        return artifacts[filePath] || null;
    }

    /**
     * Find all artifacts by actor
     */
    public async findByActor(actorId: number): Promise<ArtifactRecord[]> {
        return Object.values(this.getAllRecords()).filter(r => r.actorId === actorId);
    }

    /**
     * Find all artifacts by channel
     */
    public async findByChannel(channelId: number): Promise<ArtifactRecord[]> {
        return Object.values(this.getAllRecords()).filter(r => r.channelId === channelId);
    }

    /**
     * Find artifacts by human principal in the delegation chain
     */
    public async findByHuman(humanId: number): Promise<ArtifactRecord[]> {
        const target = humanId.toString();
        return Object.values(this.getAllRecords()).filter(r =>
            r.delegationChain && r.delegationChain.split(':').includes(target)
        );
    }

    /**
     * Find artifacts modified after date (YYYYMMDD)
     */
    public async findRecent(since: string): Promise<ArtifactRecord[]> {
        return Object.values(this.getAllRecords()).filter(r => r.lastModified >= since);
    }

    /**
     * Get all unique agents from index
     */
    public async getAllAgents(): Promise<AgentPresence[]> {
        const records = Object.values(this.getAllRecords());
        const agentsMap = new Map<string, AgentPresence>();

        for (const r of records) {
            if (!r.agentKey || !r.actorId) continue;

            const key = `${r.agentKey}-${r.actorId}`;
            let presence = agentsMap.get(key);

            if (!presence) {
                presence = {
                    agentKey: r.agentKey,
                    actorId: r.actorId,
                    lastSeen: r.lastModified,
                    filesModified: [],
                    channels: []
                };
                agentsMap.set(key, presence);
            }

            if (r.lastModified > presence.lastSeen) {
                presence.lastSeen = r.lastModified;
            }

            if (!presence.filesModified.includes(r.id)) {
                presence.filesModified.push(r.id);
            }

            if (r.channelId && !presence.channels.includes(r.channelId)) {
                presence.channels.push(r.channelId);
            }
        }

        return Array.from(agentsMap.values());
    }

    private getAllRecords(): Record<string, ArtifactRecord> {
        return this.context.workspaceState.get<Record<string, ArtifactRecord>>(this.STORAGE_KEY) || {};
    }
}
