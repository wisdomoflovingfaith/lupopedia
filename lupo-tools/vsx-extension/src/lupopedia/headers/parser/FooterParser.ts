// src/lupopedia/headers/parser/FooterParser.ts

import { LupopediaFooterV2, LupopediaFooterV3, Edge } from './types';
import { YamlExtractor } from './YamlExtractor';

export class FooterParser {
    private extractor = new YamlExtractor();

    /**
     * Extract footer from file content
     * Looks for last --- in file
     */
    public extractFooter(content: string): LupopediaFooterV2 | LupopediaFooterV3 | null {
        const block = this.extractor.extractFooterBlock(content);
        if (!block) return null;

        try {
            const data = (this.extractor as any).parseYaml ? (this.extractor as any).parseYaml(block) : JSON.parse(block);
            if (this.validateFooter(data)) {
                return data;
            }
        } catch (e) {
            console.error('Error parsing LUPOPEDIA HEADER footer:', e);
        }
        return null;
    }

    /**
     * Validate footer against v2 or v3 schema
     */
    public validateFooter(footer: any): footer is LupopediaFooterV2 | LupopediaFooterV3 {
        if (!footer) return false;

        // v3 check
        if (footer.relations) {
            return true; // Simplified for v3 draft
        }

        // v2 check
        if (footer.lupopedia && footer.lupopedia.footer) {
            const f = footer.lupopedia.footer;
            return !!(f.version && f.last_verified);
        }

        return false;
    }

    /**
     * Extract all edges from footer
     */
    public extractEdges(footer: LupopediaFooterV2 | LupopediaFooterV3): Edge[] {
        const edges: Edge[] = [];

        if ('relations' in footer) {
            // v3 Mapping
            if (footer.relations.inbound) {
                for (const edge of footer.relations.inbound) {
                    edges.push({
                        type: edge.type,
                        direction: 'inbound',
                        source: edge.source,
                        metadata: edge.metadata
                    });
                }
            }
            if (footer.relations.outbound) {
                for (const edge of footer.relations.outbound) {
                    edges.push({
                        type: edge.type,
                        direction: 'outbound',
                        target: edge.target,
                        metadata: edge.metadata
                    });
                }
            }
            return edges;
        }

        // v2 Mapping
        const f = footer.lupopedia.footer;

        // Process inbound_edges
        if (footer.lupopedia.footer.inbound_edges) {
            for (const edge of footer.lupopedia.footer.inbound_edges) {
                edges.push({
                    type: typeof edge === 'string' ? edge : (edge as any).type,
                    direction: 'inbound',
                    source: 'current_file' // Context needed to resolve this to actual path
                });
            }
        }

        // Process semantic_relationships
        if (footer.lupopedia.footer.semantic_relationships) {
            for (const rel of Object.keys(footer.lupopedia.footer.semantic_relationships)) {
                edges.push({
                    type: rel,
                    direction: 'bidirectional',
                    metadata: { kind: 'semantic_relationship' }
                });
            }
        }

        // Process graph_edges_in
        if (footer.lupopedia.footer.graph_edges_in) {
            for (const edgeObj of footer.lupopedia.footer.graph_edges_in) {
                const edgeStr = typeof edgeObj === 'string' ? edgeObj : (edgeObj as any).to || '';
                const parts = edgeStr.split('->').map((p: string) => p.trim());
                if (parts.length === 2) {
                    edges.push({
                        type: 'graph',
                        direction: 'inbound',
                        source: parts[0],
                        target: parts[1]
                    });
                } else {
                    edges.push({
                        type: 'graph',
                        direction: 'inbound',
                        metadata: edgeStr
                    });
                }
            }
        }

        return edges;
    }

    /**
     * Extract referenced actors for indexing
     */
    public extractReferencedActors(footer: LupopediaFooterV2): number[] {
        if (!footer.lupopedia.footer.referenced_by_actors) return [];
        return footer.lupopedia.footer.referenced_by_actors.map(a => Number(a));
    }

    /**
     * Extract referenced files for relationship mapping
     */
    public extractReferencedFiles(footer: LupopediaFooterV2): string[] {
        return footer.lupopedia.footer.referenced_by_files || [];
    }
}
