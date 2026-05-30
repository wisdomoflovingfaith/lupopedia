import * as vscode from 'vscode';
import { ArtifactIndex, ArtifactRecord } from './headers/storage/ArtifactIndex';
import { LupopediaHeaderV2 } from './headers/parser/types';

export interface CollectionInfo {
    id: string;
    title: string;
    description?: string;
    filePath: string;
    traits?: string[];
    rules?: string[];
}

export class CollectionManager {
    private index: ArtifactIndex;

    constructor(index: ArtifactIndex) {
        this.index = index;
    }

    /**
     * Get all collection definitions from the index
     */
    public async getAllCollections(): Promise<CollectionInfo[]> {
        const artifacts = await this.getArtifactsByType('collection');
        const results: CollectionInfo[] = [];

        for (const a of artifacts) {
            const header = JSON.parse(a.headerJson);

            if ('identity' in header) {
                // v3 support
                results.push({
                    id: header.classification.artifact_kind, // Mapping kind to ID for now if no specific ID
                    title: header.classification.artifact_kind,
                    traits: header.classification.traits,
                    filePath: a.id
                });
            } else if (header.lupopedia && header.lupopedia.headers) {
                // v2 support
                const h = header.lupopedia.headers;
                results.push({
                    id: h.collection_id || 'unknown',
                    title: h.collection_title || h.collection_id || 'Untitled Collection',
                    description: h.collection_description,
                    filePath: a.id
                });
            }
        }
        return results;
    }

    /**
     * Get artifacts belonging to a specific collection
     */
    public async getArtifactsInCollection(collectionId: string): Promise<ArtifactRecord[]> {
        const allRecords = await this.index.findRecent('20000101');
        return allRecords.filter(r => {
            if (r.collectionId === collectionId) return true;

            const header = JSON.parse(r.headerJson);
            // v2 multi-membership
            if (header.collections && header.collections.includes(collectionId)) {
                return true;
            }

            // v3 multi-membership (footer-based)
            if (r.footerJson) {
                const footer = JSON.parse(r.footerJson);
                if (footer.collections && footer.collections.some((c: any) => c.id === collectionId)) {
                    return true;
                }
            }

            return false;
        });
    }

    /**
     * Find a collection by ID
     */
    public async getCollectionById(collectionId: string): Promise<CollectionInfo | null> {
        const collections = await this.getAllCollections();
        return collections.find(c => c.id === collectionId) || null;
    }

    private async getArtifactsByType(type: string): Promise<ArtifactRecord[]> {
        const allRecords = await this.index.findRecent('20000101');
        return allRecords.filter(r => r.artifactType === type);
    }
}
