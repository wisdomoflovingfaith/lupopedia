import { Relation, MetadataIndex } from '../parser/types';

export interface MetadataIndexCachedEntry extends MetadataIndex {
    lastUpdated: number;
}

export class MetadataIndexCache {
    private cache: Map<string, MetadataIndexCachedEntry> = new Map();
    private readonly MAX_SIZE = 5000; // LRU limit

    /**
     * Store or update an entry in the cache
     */
    public set(filePath: string, entry: MetadataIndexCachedEntry): void {
        if (this.cache.size >= this.MAX_SIZE) {
            // Primitive LRU: Delete the first entry (oldest added)
            const firstKey = this.cache.keys().next().value;
            if (firstKey) this.cache.delete(firstKey);
        }
        this.cache.set(filePath, entry);
    }

    /**
     * Get an entry from the cache
     */
    public get(filePath: string): MetadataIndexCachedEntry | undefined {
        const entry = this.cache.get(filePath);
        if (entry) {
            // Refresh LRU position
            this.cache.delete(filePath);
            this.cache.set(filePath, entry);
        }
        return entry;
    }

    /**
     * Remove an entry from the cache
     */
    public delete(filePath: string): void {
        this.cache.delete(filePath);
    }

    /**
     * Clear the cache
     */
    public clear(): void {
        this.cache.clear();
    }

    /**
     * Check if a file is in the cache
     */
    public has(filePath: string): boolean {
        return this.cache.has(filePath);
    }

    /**
     * Get the current size of the cache
     */
    public size(): number {
        return this.cache.size;
    }

    /**
     * Bulk query by human principal
     */
    public findByPrincipal(humanId: number): string[] {
        const results: string[] = [];
        for (const [path, entry] of this.cache.entries()) {
            if (entry.principalId === humanId) {
                results.push(path);
            }
        }
        return results;
    }
}
