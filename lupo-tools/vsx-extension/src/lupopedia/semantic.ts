/**
 * Semantic API integration.
 *
 * Endpoints used:
 *   POST /semantic/explain
 *   POST /semantic/related
 *   POST /semantic/paths
 *   POST /semantic/flip-header
 *
 * @module lupopedia/semantic
 */

import { lupoPost } from './client';

// ─── Explain ──────────────────────────────────────────────────────────────────

export interface ExplainRequest {
    /** Relative path from repo root (e.g. "docs/example.md") */
    file_path?: string;
    /** Raw file content to explain */
    content?: string;
    /** Additional context passed to the semantic engine */
    context?: string;
    actor_id?: number;
}

export interface ExplainResponse {
    explanation: string;
    channel_id?: number;
    tags?: string[];
    confidence?: number;
    [key: string]: unknown;
}

export async function explainFile(
    baseUrl: string,
    request: ExplainRequest
): Promise<ExplainResponse> {
    const res = await lupoPost<ExplainResponse>(baseUrl, '/semantic/explain', request);
    if (!res.ok) {
        throw new Error(`Explain failed (HTTP ${res.status}): ${JSON.stringify(res.data)}`);
    }
    return res.data;
}

// ─── Related Atoms ────────────────────────────────────────────────────────────

export interface RelatedRequest {
    file_path?: string;
    content_id?: number;
    limit?: number;
    actor_id?: number;
}

export interface RelatedAtom {
    content_id: number;
    title?: string;
    file_path?: string;
    score?: number;
    [key: string]: unknown;
}

export interface RelatedResponse {
    atoms: RelatedAtom[];
    [key: string]: unknown;
}

export async function getRelatedAtoms(
    baseUrl: string,
    request: RelatedRequest
): Promise<RelatedAtom[]> {
    const res = await lupoPost<RelatedResponse>(baseUrl, '/semantic/related', request);
    if (!res.ok) {
        throw new Error(`Related atoms failed (HTTP ${res.status}): ${JSON.stringify(res.data)}`);
    }
    return res.data.atoms ?? [];
}

// ─── Semantic Paths ───────────────────────────────────────────────────────────

export interface PathsRequest {
    source_id: number;
    target_id?: number;
    layer?: 'interaction' | 'extracted' | 'navigation' | 'ai';
    limit?: number;
}

export interface SemanticPath {
    path_id?: number;
    source_id: number;
    target_id: number;
    weight?: number;
    layer?: string;
    [key: string]: unknown;
}

export interface PathsResponse {
    paths: SemanticPath[];
    [key: string]: unknown;
}

export async function getSemanticPaths(
    baseUrl: string,
    request: PathsRequest
): Promise<SemanticPath[]> {
    const res = await lupoPost<PathsResponse>(baseUrl, '/semantic/paths', request);
    if (!res.ok) {
        throw new Error(`Semantic paths failed (HTTP ${res.status}): ${JSON.stringify(res.data)}`);
    }
    return res.data.paths ?? [];
}

// ─── FLIP Header via Semantic API ─────────────────────────────────────────────

export interface FlipHeaderSemanticRequest {
    file_path?: string;
    content_id?: number;
    url?: string;
}

export interface FlipHeaderSemanticResponse {
    header: string;
    resolved: boolean;
    channel_id?: number;
    [key: string]: unknown;
}

export async function getFlipHeaderFromServer(
    baseUrl: string,
    request: FlipHeaderSemanticRequest
): Promise<FlipHeaderSemanticResponse> {
    const res = await lupoPost<FlipHeaderSemanticResponse>(
        baseUrl,
        '/semantic/flip-header',
        request
    );
    if (!res.ok) {
        throw new Error(`FLIP header fetch failed (HTTP ${res.status}): ${JSON.stringify(res.data)}`);
    }
    return res.data;
}
