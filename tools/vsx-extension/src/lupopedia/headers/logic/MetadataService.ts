// src/lupopedia/headers/logic/MetadataService.ts

import * as vscode from 'vscode';
import { MetadataIndex, Relation, LupopediaHeaderV2, LupopediaHeaderV3, LupopediaFooterV2, LupopediaFooterV3 } from '../parser/types';
import { YamlExtractor } from '../parser/YamlExtractor';
import { DelegationEngine } from './DelegationEngine';
import { MetadataIndexCache } from '../storage/MetadataIndexCache';

export class MetadataService {
    private extractor = new YamlExtractor();
    private cache = new MetadataIndexCache();

    /**
     * Parse a file in isolation to build a MetadataIndex
     * Optimized for sub-5ms performance
     */
    public async parseMetadataBlock(uri: vscode.Uri): Promise<MetadataIndex | null> {
        // Check cache first
        const filePath = uri.fsPath;
        const cached = this.cache.get(filePath);
        if (cached) return cached;

        try {
            const document = await vscode.workspace.openTextDocument(uri);
            const text = document.getText();

            const headerBlock = this.extractor.extractHeaderBlock(text);
            const footerBlock = this.extractor.extractFooterBlock(text);

            if (!headerBlock) return null;

            const header = this.extractor.parseMetadata(headerBlock);
            const footer = footerBlock ? this.extractor.parseMetadata(footerBlock) : null;

            const index = this.buildIndex(header, footer);
            if (index) {
                this.cache.set(filePath, { ...index, lastUpdated: Date.now() });
            }
            return index;
        } catch (e) {
            console.error(`Failed to parse metadata for ${filePath}:`, e);
            return null;
        }
    }

    /**
     * Build the MetadataIndex interface from raw parsed blocks
     */
    private buildIndex(header: any, footer: any): MetadataIndex | null {
        let actorId = 0;
        let chain = '';
        let semanticKeys: string[] = [];
        let relations: Relation[] = [];
        let inbound: string[] = [];
        let outbound: string[] = [];
        let hashtags: string[] = [];
        let engagement: any = undefined;
        let graphStats: any = undefined;

        // Determine V2 or V3
        if (header.identity && header.classification) {
            // V3
            const h = header as LupopediaHeaderV3;
            actorId = h.identity.execution_agent;
            chain = h.identity.delegation_chain || `${h.identity.execution_agent}:${h.identity.intent_authority}`;
            semanticKeys = h.classification.traits || [];
            // V3 specific fields (draft)
        } else if (header.lupopedia && header.lupopedia.headers) {
            // V2
            const h = header as LupopediaHeaderV2;
            actorId = h.lupopedia.headers.actor_id || 0;
            chain = h.lupopedia.headers.delegation_chain || h.lupopedia.headers.x_lupo_forwarded || '';
            semanticKeys = (header.collections || []);

            // Living Registry fields
            hashtags = h.lupopedia.headers.hashtags || [];
            engagement = h.lupopedia.headers.engagement;
            graphStats = h.lupopedia.headers.graph_stats;
        } else {
            return null;
        }

        const delegationPath = DelegationEngine.getDelegationPath(chain);
        const principalId = DelegationEngine.getPrincipal(chain) || 0;

        // Process Footer relations
        if (footer) {
            if (footer.relations) {
                // V3 Relations
                const f = footer as LupopediaFooterV3;
                relations = [...(f.relations.outbound || []), ...(f.relations.inbound || [])].map(r => ({
                    type: r.type,
                    target: 'target' in r ? r.target : r.source,
                    metadata: r.metadata
                }));
                outbound = (f.relations.outbound || []).map(r => r.target);
                inbound = (f.relations.inbound || []).map(r => r.source);
            } else if (footer.lupopedia && footer.lupopedia.footer) {
                // V2 Footer
                const f = footer as LupopediaFooterV2;

                // Handle typed edges in V2 Hybrid
                if (Array.isArray(f.lupopedia.footer.inbound_edges)) {
                    inbound = f.lupopedia.footer.inbound_edges.map(e => typeof e === 'string' ? e : e.from);
                }
                if (Array.isArray(f.lupopedia.footer.outbound_edges)) {
                    outbound = f.lupopedia.footer.outbound_edges.map(e => e.to);
                } else if (Array.isArray(f.lupopedia.footer.graph_edges_in)) {
                    outbound = f.lupopedia.footer.graph_edges_in.map(e => typeof e === 'string' ? e : e.to);
                }

                // Map relations for graph view
                const edges = [
                    ...(f.lupopedia.footer.inbound_edges || []),
                    ...(f.lupopedia.footer.outbound_edges || []),
                    ...(f.lupopedia.footer.graph_edges_in || [])
                ];
                relations = edges.map(e => {
                    if (typeof e === 'string') return { type: 'references', target: e };
                    return {
                        type: e.type,
                        target: 'to' in e ? e.to : e.from,
                        metadata: { weight: e.weight, hashtag: e.hashtag }
                    };
                });
            }
        }

        return {
            actorId,
            principalId,
            delegationPath,
            semanticKeys,
            relations,
            inbound,
            outbound,
            hashtags,
            engagement,
            graphStats
        };
    }

    /**
     * Get relations for a file as a graph node
     */
    public async getRelations(uri: vscode.Uri): Promise<any> {
        const index = await this.parseMetadataBlock(uri);
        if (!index) return null;
        return {
            path: uri.fsPath,
            inbound: index.inbound,
            outbound: index.outbound,
            relations: index.relations
        };
    }

    /**
     * Clear the cache (Workspace revalidation)
     */
    public invalidateCache(): void {
        this.cache.clear();
    }
}
