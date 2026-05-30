import { ArtifactIndex, ArtifactRecord } from '../storage/ArtifactIndex';
import * as path from 'path';

export interface QueryResult {
    matched: ArtifactRecord[];
    query: string;
    executionTimeMs: number;
}

export class HeaderQueryEngine {
    private index: ArtifactIndex;

    constructor(index: ArtifactIndex) {
        this.index = index;
    }

    /**
     * Parse and execute a Header Query DSL statement.
     * Examples:
     * - relations inbound from QUICKSTART.md
     * - collections containing onboarding
     * - actors modifying docs/example.md
     * - type:directive collection:v4.0.37
     */
    public async query(dsl: string): Promise<QueryResult> {
        const start = Date.now();
        const tokens = dsl.split(/\s+/);
        let results: ArtifactRecord[] = [];

        if (tokens[0] === 'relations') {
            const direction = tokens[1]; // inbound/outbound
            const target = tokens[tokens.length - 1]; // filename or path
            const records = await this.index.findRecent('20000101');

            if (direction === 'inbound') {
                results = records.filter(r => {
                    if (!r.footerJson) return false;
                    const footer = JSON.parse(r.footerJson);
                    // v2 or v3 check
                    const edges = footer.lupopedia?.footer?.inbound_edges || footer.relations?.inbound || [];
                    return JSON.stringify(edges).includes(target);
                });
            }
        }
        else if (tokens[0] === 'collections') {
            const colId = tokens[tokens.length - 1];
            const records = await this.index.findRecent('20000101');
            results = records.filter(r => r.collectionId === colId);
        }
        else if (tokens[0] === 'actors') {
            const target = tokens[tokens.length - 1];
            const record = await this.index.findByPath(target);
            if (record) {
                // Return records modified by the same actor
                const all = await this.index.findRecent('20000101');
                results = all.filter(r => r.actorId === record.actorId);
            }
        }
        else if (tokens[0] === 'compliance') {
            const status = tokens[1]; // outdated/missing
            const all = await this.index.findRecent('20000101');
            if (status === 'missing') {
                results = all.filter(r => !r.headerJson);
            } else if (status === 'outdated') {
                results = all.filter(r => r.version && this.compareVersions(r.version, '4.0.40') < 0);
            }
        }
        else {
            // Default keyword/filter search
            const all = await this.index.findRecent('20000101');
            results = all.filter(r =>
                r.id.toLowerCase().includes(dsl.toLowerCase()) ||
                r.artifactType?.toLowerCase() === dsl.toLowerCase() ||
                r.collectionId?.toLowerCase() === dsl.toLowerCase()
            );
        }

        return {
            matched: results,
            query: dsl,
            executionTimeMs: Date.now() - start
        };
    }

    private compareVersions(v1: string, v2: string): number {
        const parts1 = v1.split('.').map(Number);
        const parts2 = v2.split('.').map(Number);
        for (let i = 0; i < Math.max(parts1.length, parts2.length); i++) {
            const p1 = parts1[i] || 0;
            const p2 = parts2[i] || 0;
            if (p1 > p2) return 1;
            if (p1 < p2) return -1;
        }
        return 0;
    }
}
