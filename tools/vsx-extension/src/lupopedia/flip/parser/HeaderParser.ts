// src/lupopedia/flip/parser/HeaderParser.ts

import { FlipHeaderV2, FlipHeaderV3, IndexFields } from './types';
import { YamlExtractor } from './YamlExtractor';

export class HeaderParser {
    private extractor = new YamlExtractor();

    /**
     * Extract header from file content
     * Returns null if no valid header found
     */
    public extractHeader(content: string): FlipHeaderV2 | FlipHeaderV3 | null {
        const block = this.extractor.extractHeaderBlock(content);
        if (!block) return null;

        try {
            const data = this.extractor.parseMetadata(block);
            if (this.validateHeader(data)) {
                return data;
            }
        } catch (e) {
            console.error('Error parsing FLIP header:', e);
        }
        return null;
    }

    /**
     * Validate header against v2 or v3 schema
     */
    public validateHeader(header: any): header is FlipHeaderV2 | FlipHeaderV3 {
        if (!header) return false;

        // Check for v3 structure
        if (header.identity && header.classification) {
            const basicValid = (
                header.identity.execution_agent !== undefined &&
                header.classification.artifact_kind !== undefined
            );
            if (basicValid && header.identity.delegation_chain) {
                return this.validateDelegationChain(header.identity.delegation_chain).valid;
            }
            return basicValid;
        }

        // Check for v2 structure
        if (header.wolfie && header.wolfie.headers) {
            const h = header.wolfie.headers;
            const basicValid = !!(h.file_path_from_root && h.system_version && h.last_modified);

            if (basicValid && h.delegation_chain) {
                return this.validateDelegationChain(h.delegation_chain).valid;
            }
            return basicValid;
        }

        return false;
    }

    /**
     * Validate the delegation chain for accountability
     * Format: "actor1:actor2:human"
     */
    public validateDelegationChain(chain: string): { valid: boolean; error?: string } {
        if (!chain) return { valid: true };

        const actors = chain.split(':').filter(s => s.trim() !== '').map(id => parseInt(id.trim()));

        if (actors.length === 0) return { valid: false, error: 'Empty delegation chain' };
        if (actors.some(isNaN)) {
            return { valid: false, error: 'Delegation chain contains invalid actor IDs' };
        }

        // Rule: All but the last actor must be agents (< 10000)
        for (let i = 0; i < actors.length - 1; i++) {
            if (actors[i] >= 10000) {
                return { valid: false, error: `Intermediate actor at position ${i} must be an agent (ID < 10000)` };
            }
        }

        // Rule: Final authority must be human (>= 10000)
        if (actors[actors.length - 1] < 10000) {
            return { valid: false, error: 'Final authority in delegation chain must be a human (ID >= 10000)' };
        }

        return { valid: true };
    }

    /**
     * Extract specific fields for indexing
     */
    public extractIndexFields(header: FlipHeaderV2 | FlipHeaderV3): IndexFields {
        if ('identity' in header) {
            // v3 Mapping
            return {
                filePath: '', // Context needed as v3 identity might not have it explicitly
                version: header.identity.system_version,
                channelId: header.identity.channel_id,
                actorId: header.identity.execution_agent,
                agentKey: header.identity.agent_slug,
                lastModified: new Date().toISOString().slice(0, 10).replace(/-/g, ''), // Defaulting to now
                artifactType: header.classification.artifact_type,
                artifactKind: header.classification.artifact_kind
            };
        }

        // v2 Mapping
        return {
            filePath: header.wolfie.headers.file_path_from_root,
            version: header.wolfie.headers.system_version,
            channelId: header.wolfie.headers.channel_id,
            actorId: header.lupo?.agent?.tracking?.actor_id || header.wolfie.headers.actor_id,
            agentKey: header.lupo?.agent?.tracking?.agent_key,
            lastModified: header.wolfie.headers.last_modified,
            artifactType: header.wolfie.headers.artifact_type,
            artifactKind: header.wolfie.headers.artifact_kind,
            collectionId: header.wolfie.headers.collection_id
        };
    }
}
