// src/lupopedia/headers/parser/HeaderParser.ts

import { LupopediaHeaderV2, LupopediaHeaderV3, IndexFields } from './types';
import { YamlExtractor } from './YamlExtractor';

export class HeaderParser {
    private extractor = new YamlExtractor();

    /**
     * Extract header from file content
     * Returns null if no valid header found
     */
    public extractHeader(content: string): LupopediaHeaderV2 | LupopediaHeaderV3 | null {
        const block = this.extractor.extractHeaderBlock(content);
        if (!block) return null;

        try {
            const data = this.extractor.parseMetadata(block);
            if (this.validateHeader(data)) {
                return data;
            }
        } catch (e) {
            console.error('Error parsing LUPOPEDIA Header:', e);
        }
        return null;
    }

    /**
     * Validate header against v2 or v3 schema
     */
    public validateHeader(header: any): header is LupopediaHeaderV2 | LupopediaHeaderV3 {
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
        if (header.lupopedia && header.lupopedia.headers) {
            const h = header.lupopedia.headers;
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
    public extractIndexFields(header: LupopediaHeaderV2 | LupopediaHeaderV3): IndexFields {
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
            filePath: header.lupopedia.headers.file_path_from_root,
            version: header.lupopedia.headers.system_version,
            channelId: header.lupopedia.headers.channel_id,
            actorId: header.lupo?.agent?.tracking?.actor_id || header.lupopedia.headers.actor_id,
            agentKey: header.lupo?.agent?.tracking?.agent_key,
            lastModified: header.lupopedia.headers.last_modified,
            artifactType: header.lupopedia.headers.artifact_type,
            artifactKind: header.lupopedia.headers.artifact_kind,
            collectionId: header.lupopedia.headers.collection_id
        };
    }

    /**
     * Check if the header satisfies the current system version requirement (v4.0.40 Gate)
     */
    public checkCompliance(header: LupopediaHeaderV2 | LupopediaHeaderV3): { compliant: boolean; error?: string; code?: string } {
        let version = '';
        if ('identity' in header) {
            version = header.identity.system_version;
        } else if (header.lupopedia?.headers) {
            version = header.lupopedia.headers.system_version;
        }

        if (!version) return { compliant: false, error: 'Missing system_version', code: 'HEADER_OUTDATED' };

        // v4.0.40 Gate
        const target = '4.0.40';
        if (this.compareVersions(version, target) < 0) {
            return {
                compliant: false,
                error: `System version ${version} is below the 4.0.40 compliance gate.`,
                code: 'HEADER_OUTDATED'
            };
        }

        return { compliant: true };
    }

    /**
     * Simple semantic version comparison
     */
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
