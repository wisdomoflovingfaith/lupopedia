// src/lupopedia/flip/parser/FooterParser.ts

import { FlipFooterV2, FlipFooterV3, Edge } from './types';
import { YamlExtractor } from './YamlExtractor';

export class FooterParser {
    private extractor = new YamlExtractor();

    /**
     * Extract footer from file content
     * Looks for last --- in file
     */
    public extractFooter(content: string): FlipFooterV2 | FlipFooterV3 | null {
        const block = this.extractor.extractFooterBlock(content);
        if (!block) return null;

        try {
            const data = this.extractor.parseYaml(block);
            if (this.validateFooter(data)) {
                return data;
            }
        } catch (e) {
            console.error('Error parsing FLIP footer:', e);
        }
        return null;
    }

    /**
     * Validate footer against v2 or v3 schema
     */
    public validateFooter(footer: any): footer is FlipFooterV2 | FlipFooterV3 {
        if (!footer) return false;

        // v3 check
        if (footer.relations) {
            return true; // Simplified for v3 draft
        }

        // v2 check
        if (footer.flip && footer.flip.footer) {
            const f = footer.flip.footer;
            return !!(f.version && f.last_verified);
        }

        return false;
    }

    /**
     * Extract all edges from footer
     */
    public extractEdges(footer: FlipFooterV2 | FlipFooterV3): Edge[] {
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
        const f = footer.flip.footer;

        // Process inbound_edges
        if (footer.flip.footer.inbound_edges) {
            for (const edge of footer.flip.footer.inbound_edges) {
                edges.push({
                    type: edge,
                    direction: 'inbound',
                    source: 'current_file' // Context needed to resolve this to actual path
                });
            }
        }

        // Process semantic_relationships
        if (footer.flip.footer.semantic_relationships) {
            for (const rel of footer.flip.footer.semantic_relationships) {
                edges.push({
                    type: rel,
                    direction: 'bidirectional',
                    metadata: { kind: 'semantic_relationship' }
                });
            }
        }

        // Process graph_edges_in
        if (footer.flip.footer.graph_edges_in) {
            for (const edgeStr of footer.flip.footer.graph_edges_in) {
                // Format: "source -> target" or "source"
                const parts = edgeStr.split('->').map(p => p.trim());
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
    public extractReferencedActors(footer: FlipFooterV2): number[] {
        if (!footer.flip.footer.referenced_by_actors) return [];
        return footer.flip.footer.referenced_by_actors.map(a => Number(a));
    }

    /**
     * Extract referenced files for relationship mapping
     */
    public extractReferencedFiles(footer: FlipFooterV2): string[] {
        return footer.flip.footer.referenced_by_files || [];
    }
}
