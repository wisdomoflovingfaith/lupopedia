// src/lupopedia/flip/parser/types.ts

/**
 * WOLFIE/FLIP v2 Header Structure
 */
export interface FlipHeaderV2 {
    wolfie: {
        headers: {
            file_path_from_root: string;
            system_version: string;
            channel_id?: number;
            mood_rgb?: string;
            purpose?: string;
            last_modified: string;  // YYYYMMDD
            x_lupo_forwarded?: string;
            delegation_chain?: string; // New in v4.1 - accountability path
            actor_id?: number;
            federated_node_id?: number;
            lupo_agent?: string;

            // New fields for v4.0.37
            artifact_type?: string;
            artifact_kind?: string;
            artifact_id?: string;
            link_target?: string;
            url?: string;
            collection_id?: string;
            collection_title?: string;
            collection_description?: string;

            // Living Registry fields (v4.0.39)
            hashtags?: string[];
            engagement?: {
                likes: number;
                shares: number;
                views: number;
                last_interaction_utc: string;
            };
            graph_stats?: {
                inbound_count: number;
                outbound_count: number;
                centrality_score: number;
            };
        };
    };
    lupo?: {
        agent?: {
            tracking?: {
                agent_key?: string;
                agent_type?: string;
                actor_id?: number;
                session_id?: string;
                timestamp?: string;
            };
        };
    };
    // Membership
    collections?: string[];
}

/**
 * FLIP v2 Footer Structure
 */
export interface FlipFooterV2 {
    flip: {
        footer: {
            referenced_by_files?: string[];
            referenced_by_channels?: number[];
            referenced_by_actors?: number[];
            consumed_by_services?: string[];
            cited_by_docs?: string[];
            graph_edges_in?: Array<string | { to: string, type: string, weight: number, hashtag?: string }>;
            inbound_edges?: Array<string | { from: string, type: string, weight: number, hashtag?: string }>;
            outbound_edges?: Array<{ to: string, type: string, weight: number, hashtag?: string }>;
            semantic_relationships?: Record<string, string[]>;
            footnotes?: string[];
            version: string;
            last_verified: string;  // YYYYMMDD
            last_verified_by?: string;
            verification_method?: string;
            version_history?: any[];
        };
    };
}

/**
 * FLIP v3 Header Structure (Draft)
 */
export interface FlipHeaderV3 {
    identity: {
        execution_agent: number;
        intent_authority: number;
        delegation_chain?: string; // New in v4.1 - accountability path
        agent_type: string;
        agent_slug: string;
        system_version: string;
        channel_id?: number;
        federated_node_id?: number;
    };
    classification: {
        artifact_kind: string;
        artifact_type: string;
        traits?: string[];
    };
}

/**
 * FLIP v3 Footer Structure (Draft)
 */
export interface FlipFooterV3 {
    relations: {
        inbound?: Array<{
            type: string;
            source: string;
            metadata?: any;
        }>;
        outbound?: Array<{
            type: string;
            target: string;
            metadata?: any;
        }>;
    };
    collections?: Array<{
        id: string;
        traits?: string[];
        rules?: string[];
    }>;
    history?: {
        version: string;
        last_verified: string;
        last_verified_by?: string;
        entries?: any[];
    };
}

/**
 * Combined Artifact Metadata
 */
export interface FlipArtifact {
    filePath: string;
    fileHash: string;
    header: FlipHeaderV2 | FlipHeaderV3;
    footer?: FlipFooterV2 | FlipFooterV3;
    indexedAt: string;  // YYYYMMDD
    lastScanned: string; // YYYYMMDD
}

/**
 * Summary fields for indexing
 */
export interface IndexFields {
    filePath: string;
    version: string;
    channelId?: number;
    federatedNodeId?: number;
    actorId?: number;
    agentKey?: string;
    lastModified: string;
    artifactType?: string;
    artifactKind?: string;
    collectionId?: string;
}

/**
 * Semantic Relationship Edge
 */
export interface Edge {
    type: string;
    direction: 'inbound' | 'outbound' | 'bidirectional';
    source?: string;
    target?: string;
    metadata?: any;
}

/**
 * v4.1 Metadata Index Interface
 */
export interface MetadataIndex {
    actorId: number;
    principalId: number;
    delegationPath: number[];
    semanticKeys: string[];
    relations: Relation[];
    inbound: string[];
    outbound: string[];

    // Living Registry fields (v4.0.39)
    hashtags?: string[];
    engagement?: {
        likes: number;
        shares: number;
        views: number;
        last_interaction_utc: string;
    };
    graphStats?: {
        inbound_count: number;
        outbound_count: number;
        centrality_score: number;
    };
}

/**
 * Semantic Relationship Node
 */
export interface Relation {
    type: string;
    target: string;
    metadata?: any;
}

/**
 * Agent presence and activity tracking
 */
export interface AgentPresence {
    agentKey: string;
    actorId: number;
    lastSeen: string;  // YYYYMMDD
    filesModified: string[];
    channels: number[];
}
