// src/lupopedia/flip/edge/EdgeMapper.ts

import { FlipFooterV2 } from '../parser/types';

export interface SemanticEdge {
    id: string;
    sourcePath: string;
    targetPath?: string;
    edgeType: string;
    direction: 'inbound' | 'outbound' | 'bidirectional';
    metadata?: any;
    discovered: string;  // YYYYMMDD
}

export class EdgeMapper {
    private edges: Map<string, SemanticEdge> = new Map();

    /**
     * Process footer and extract all edges
     */
    public processFooter(filePath: string, footer: FlipFooterV2): SemanticEdge[] {
        const edges: SemanticEdge[] = [];

        // Process inbound_edges
        if (footer.flip.footer.inbound_edges) {
            for (const edge of footer.flip.footer.inbound_edges) {
                edges.push(this.createEdge(filePath, edge, 'inbound'));
            }
        }

        // Process graph_edges_in
        if (footer.flip.footer.graph_edges_in) {
            for (const edge of footer.flip.footer.graph_edges_in) {
                edges.push(this.createEdge(filePath, edge, 'graph'));
            }
        }

        // Store edges
        for (const edge of edges) {
            this.edges.set(edge.id, edge);
        }

        return edges;
    }

    /**
     * Find all edges related to a file
     */
    public findEdgesForFile(filePath: string): SemanticEdge[] {
        const result: SemanticEdge[] = [];
        for (const edge of this.edges.values()) {
            if (edge.sourcePath === filePath || edge.targetPath === filePath) {
                result.push(edge);
            }
        }
        return result;
    }

    /**
     * Build relationship graph structure
     */
    public buildGraph(): { nodes: any[], links: any[] } {
        const nodesSet = new Set<string>();
        const links: any[] = [];

        for (const edge of this.edges.values()) {
            nodesSet.add(edge.sourcePath);
            if (edge.targetPath) nodesSet.add(edge.targetPath);

            links.push({
                source: edge.sourcePath,
                target: edge.targetPath || 'graph-root',
                type: edge.edgeType
            });
        }

        const nodes = Array.from(nodesSet).map(path => ({ id: path }));
        return { nodes, links };
    }

    private createEdge(source: string, type: string, direction: string): SemanticEdge {
        const timestamp = new Date().toISOString().slice(0, 10).replace(/-/g, '');
        return {
            id: `${source}-${type}-${Date.now()}`,
            sourcePath: source,
            edgeType: type,
            direction: direction as any,
            discovered: timestamp
        };
    }
}
