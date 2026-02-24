// src/lupopedia/flip/storage/HashGenerator.ts

import * as crypto from 'crypto';

export class HashGenerator {
    /**
     * Generate SHA-256 hash of file content
     */
    public async generateHash(content: string): Promise<string> {
        return crypto.createHash('sha256').update(content).digest('hex');
    }

    /**
     * Verify file integrity
     */
    public async verifyHash(content: string, expectedHash: string): Promise<boolean> {
        const actualHash = await this.generateHash(content);
        return actualHash === expectedHash;
    }

    /**
     * Quick check if file needs re-indexing
     */
    public async needsReindexing(
        filePath: string,
        content: string,
        storedHash?: string
    ): Promise<boolean> {
        if (!storedHash) return true;

        const currentHash = await this.generateHash(content);
        return currentHash !== storedHash;
    }
}
