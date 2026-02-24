// src/lupopedia/commands/ScanWorkspace.ts

import * as vscode from 'vscode';
import { HeaderParser } from '../flip/parser/HeaderParser';
import { FooterParser } from '../flip/parser/FooterParser';
import { HashGenerator } from '../flip/storage/HashGenerator';
import { ArtifactIndex } from '../flip/storage/ArtifactIndex';
import { EdgeMapper } from '../flip/edge/EdgeMapper';

export async function scanWorkspace(context: vscode.ExtensionContext, artifactIndex: ArtifactIndex) {
    const headerParser = new HeaderParser();
    const footerParser = new FooterParser();
    const hashGenerator = new HashGenerator();
    const edgeMapper = new EdgeMapper();

    const files = await vscode.workspace.findFiles('**/*.md', '**/node_modules/**');
    let filesScanned = 0;
    let artifactsUpdated = 0;

    const timestamp = new Date().toISOString().slice(0, 10).replace(/-/g, '');

    for (const file of files) {
        try {
            const contentBuffer = await vscode.workspace.fs.readFile(file);
            const content = new TextDecoder().decode(contentBuffer);

            const header = headerParser.extractHeader(content);
            if (!header) continue;

            const hash = await hashGenerator.generateHash(content);
            const stored = await artifactIndex.findByPath(file.fsPath);

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
                    edgeMapper.processFooter(file.fsPath, footer);
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
