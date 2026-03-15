// src/lupopedia/commands/ScanWorkspace.ts

import * as vscode from 'vscode';
import { HeaderParser } from '../headers/parser/HeaderParser';
import { FooterParser } from '../headers/parser/FooterParser';
import { HashGenerator } from '../headers/storage/HashGenerator';
import { ArtifactIndex } from '../headers/storage/ArtifactIndex';
import { EdgeMapper } from '../headers/edge/EdgeMapper';

export async function scanWorkspace(context: vscode.ExtensionContext, artifactIndex: ArtifactIndex) {
    const headerParser = new HeaderParser();
    const footerParser = new FooterParser();
    const hashGenerator = new HashGenerator();
    const edgeMapper = new EdgeMapper();

    const files = await vscode.workspace.findFiles('{channels,artifacts}/**/*.md', '**/node_modules/**');
    let filesScanned = 0;
    let artifactsUpdated = 0;

    const timestamp = new Date().toISOString().slice(0, 10).replace(/-/g, '');

    for (const file of files) {
        try {
            const contentBuffer = await vscode.workspace.fs.readFile(file);
            const content = new TextDecoder().decode(contentBuffer);

            const header = headerParser.extractHeader(content);
            if (!header) {
                console.warn(`Missing or invalid LUPOPEDIA Header in ${file.fsPath}`);
                continue;
            }

            const hash = await hashGenerator.generateHash(content);
            const stored = await artifactIndex.findByPath(file.fsPath);

            // Extract folder mapping
            const relPath = vscode.workspace.asRelativePath(file);
            const pathParts = relPath.split('/');
            let folderId: number | undefined;
            let isChannel = false;
            let isArtifact = false;

            if (pathParts[0] === 'channels' && pathParts[1] && /^\d+$/.test(pathParts[1])) {
                folderId = parseInt(pathParts[1]);
                isChannel = true;
            } else if (pathParts[0] === 'artifacts' && pathParts[1] && /^\d+$/.test(pathParts[1])) {
                folderId = parseInt(pathParts[1]);
                isArtifact = true;
            }

            // Sync folder ID with header metadata if missing
            if (folderId !== undefined) {
                if ('identity' in header) {
                    if (isChannel && header.identity.channel_id === undefined) {
                        header.identity.channel_id = folderId;
                    }
                } else if (header.lupopedia?.headers) {
                    if (isChannel && header.lupopedia.headers.channel_id === undefined) {
                        header.lupopedia.headers.channel_id = folderId;
                    }
                }
            }

            if (!stored || stored.fileHash !== hash) {
                const footer = footerParser.extractFooter(content);

                await artifactIndex.storeArtifact({
                    filePath: file.fsPath,
                    fileHash: hash,
                    header: header,
                    footer: footer || undefined,
                    indexedAt: timestamp,
                    lastScanned: timestamp
                });

                if (footer) {
                    edgeMapper.processFooter(file.fsPath, footer as any);
                }
                artifactsUpdated++;
            }
            filesScanned++;
        } catch (e) {
            console.error(`Failed to scan file ${file.fsPath}:`, e);
        }
    }

    return {
        filesScanned,
        artifactsUpdated
    };
}
