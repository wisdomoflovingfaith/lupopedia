// src/lupopedia/headers/concurrency/ThreadLock.ts

import * as vscode from 'vscode';
import * as fs from 'fs';
import * as path from 'path';

export interface ThreadLockData {
    thread_id: string;
    file_path: string;
    locked_by: number;
    lock_acquired: string;  // YYYYMMDD
    lock_expires?: string;  // YYYYMMDD-Rev
    lock_purpose: string;
    regions?: string[];     // ['header', 'footer', 'identity', etc.]
    agents_waiting?: number[];
    conflict_resolution?: string;
}

export class ThreadLockManager {
    /**
     * Attempts to acquire a lock on a file.
     * In the Lupopedia Semantic OS, a lock is a metadata block 
     * typically broadcasted to Channel 42 or stored in a shared registry.
     * 
     * For the initial implementation, we will use a shared lock directory.
     */
    public async acquireLock(
        filePath: string,
        actorId: number,
        threadId: string,
        purpose: string,
        regions: string[] = ['full']
    ): Promise<boolean> {
        const lockPath = this.getLockPath(filePath);
        const lockDir = path.dirname(lockPath);

        if (!fs.existsSync(lockDir)) {
            fs.mkdirSync(lockDir, { recursive: true });
        }

        if (fs.existsSync(lockPath)) {
            const existingLock = JSON.parse(fs.readFileSync(lockPath, 'utf-8')) as ThreadLockData;

            if (existingLock.locked_by !== actorId) {
                // Someone else has a lock. Check if it's a 'full' lock or if any requested regions are occupied.
                const existingRegions = existingLock.regions || ['full'];

                if (existingRegions.includes('full') || regions.includes('full')) {
                    return false;
                }

                // Check for intersection of regions
                const conflict = regions.some(r => existingRegions.includes(r));
                if (conflict) {
                    return false;
                }
            }
        }

        const timestamp = new Date().toISOString().slice(0, 10).replace(/-/g, '');
        const lockData: ThreadLockData = {
            thread_id: threadId,
            file_path: filePath,
            locked_by: actorId,
            lock_acquired: timestamp,
            lock_purpose: purpose,
            regions: regions,
            agents_waiting: []
        };

        fs.writeFileSync(lockPath, JSON.stringify(lockData, null, 2), 'utf-8');
        return true;
    }

    /**
     * Releases a lock on a file.
     */
    public async releaseLock(filePath: string, actorId: number): Promise<boolean> {
        const lockPath = this.getLockPath(filePath);
        if (!fs.existsSync(lockPath)) return true;

        const existingLock = JSON.parse(fs.readFileSync(lockPath, 'utf-8')) as ThreadLockData;
        if (existingLock.locked_by === actorId) {
            fs.unlinkSync(lockPath);
            return true;
        }

        return false;
    }

    /**
     * Checks if a file is currently locked.
     */
    public getLockInfo(filePath: string): ThreadLockData | null {
        const lockPath = this.getLockPath(filePath);
        if (!fs.existsSync(lockPath)) return null;

        try {
            return JSON.parse(fs.readFileSync(lockPath, 'utf-8'));
        } catch (e) {
            return null;
        }
    }

    private getLockPath(filePath: string): string {
        const workspaceRoot = vscode.workspace.workspaceFolders?.[0].uri.fsPath || '';
        const relativePath = path.relative(workspaceRoot, filePath);
        // Use a hash or encoded path to avoid filesystem issues with deep paths
        const safePath = Buffer.from(relativePath).toString('hex').slice(0, 64);
        return path.join(workspaceRoot, '.lupo', 'locks', `${safePath}.lock.json`);
    }
}
