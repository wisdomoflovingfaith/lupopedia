// src/lupopedia/flip/logic/RepairService.ts

import * as vscode from 'vscode';
import { YamlExtractor } from '../parser/YamlExtractor';
import { DelegationEngine } from './DelegationEngine';

export class RepairService {
    private extractor = new YamlExtractor();

    /**
     * Normalize the metadata block (fix formatting, remove legacy fields)
     */
    public async normalizeMetadata(document: vscode.TextDocument): Promise<boolean> {
        const text = document.getText();
        const headerBlock = this.extractor.extractHeaderBlock(text);
        if (!headerBlock) return false;

        let data = this.extractor.parseMetadata(headerBlock);
        if (!data) return false;

        // Legacy cleanup: Replace x_lupo_forwarded with delegation_chain
        if (data.wolfie && data.wolfie.headers) {
            if (data.wolfie.headers.x_lupo_forwarded && !data.wolfie.headers.delegation_chain) {
                data.wolfie.headers.delegation_chain = data.wolfie.headers.x_lupo_forwarded;
                delete data.wolfie.headers.x_lupo_forwarded;
            }
        } else if (data.identity) {
            // v3 - already uses delegation_chain ideally
        }

        const normalized = JSON.stringify(data, null, 2);
        return this.replaceBlock(document, headerBlock, normalized);
    }

    /**
     * Repair the delegation chain
     */
    public async repairDelegationChain(document: vscode.TextDocument, actorId: number): Promise<boolean> {
        const text = document.getText();
        const headerBlock = this.extractor.extractHeaderBlock(text);
        if (!headerBlock) return false;

        let data = this.extractor.parseMetadata(headerBlock);
        if (!data) return false;

        let chain = '';
        if (data.wolfie && data.wolfie.headers) {
            chain = data.wolfie.headers.delegation_chain || '';
            const repaired = DelegationEngine.autoRepair(chain, actorId);
            data.wolfie.headers.delegation_chain = repaired;
            data.wolfie.headers.actor_id = actorId;
        } else if (data.identity) {
            chain = data.identity.delegation_chain || '';
            const repaired = DelegationEngine.autoRepair(chain, actorId);
            data.identity.delegation_chain = repaired;
            data.identity.execution_agent = actorId;
        }

        const normalized = JSON.stringify(data, null, 2);
        return this.replaceBlock(document, headerBlock, normalized);
    }

    private async replaceBlock(document: vscode.TextDocument, oldBlock: string, newBlock: string): Promise<boolean> {
        const edit = new vscode.WorkspaceEdit();
        const fullText = document.getText();

        // Find the range of the old block
        const startIdx = fullText.indexOf(oldBlock);
        if (startIdx === -1) return false;

        const startPos = document.positionAt(startIdx);
        const endPos = document.positionAt(startIdx + oldBlock.length);

        edit.replace(document.uri, new vscode.Range(startPos, endPos), newBlock);
        return vscode.workspace.applyEdit(edit);
    }
}
