/**
 * Lupopedia Lite Client — Base HTTP wrapper
 *
 * All API calls go through this module. Uses Node's built-in http/https
 * so no external dependencies are required.
 *
 * @module lupopedia/client
 */

import * as https from 'https';
import * as http from 'http';
import * as url from 'url';

export interface LupoResponse<T = unknown> {
    status: number;
    ok: boolean;
    data: T;
}

/**
 * Perform an HTTP/HTTPS request against the Lupopedia API.
 */
export function lupoRequest<T = unknown>(
    baseUrl: string,
    path: string,
    method: 'GET' | 'POST',
    body?: unknown
): Promise<LupoResponse<T>> {
    return new Promise((resolve, reject) => {
        const fullUrl = `${baseUrl.replace(/\/$/, '')}${path}`;
        const parsed = url.parse(fullUrl);
        const isHttps = parsed.protocol === 'https:';
        const transport = isHttps ? https : http;

        const payload = body !== undefined ? JSON.stringify(body) : undefined;

        const options: http.RequestOptions = {
            hostname: parsed.hostname ?? 'localhost',
            port: parsed.port
                ? parseInt(parsed.port, 10)
                : isHttps
                    ? 443
                    : 80,
            path: parsed.path ?? '/',
            method,
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                ...(payload ? { 'Content-Length': Buffer.byteLength(payload) } : {}),
            },
        };

        const req = transport.request(options, (res) => {
            let raw = '';
            res.on('data', (chunk: string) => (raw += chunk));
            res.on('end', () => {
                let data: T;
                try {
                    data = JSON.parse(raw) as T;
                } catch {
                    data = raw as unknown as T;
                }
                resolve({
                    status: res.statusCode ?? 0,
                    ok: (res.statusCode ?? 0) >= 200 && (res.statusCode ?? 0) < 300,
                    data,
                });
            });
        });

        req.on('error', reject);

        if (payload) {
            req.write(payload);
        }
        req.end();
    });
}

/**
 * GET helper
 */
export function lupoGet<T = unknown>(
    baseUrl: string,
    path: string
): Promise<LupoResponse<T>> {
    return lupoRequest<T>(baseUrl, path, 'GET');
}

/**
 * POST helper
 */
export function lupoPost<T = unknown>(
    baseUrl: string,
    path: string,
    body: unknown
): Promise<LupoResponse<T>> {
    return lupoRequest<T>(baseUrl, path, 'POST', body);
}
