// src/lupopedia/flip/parser/YamlExtractor.ts

/**
 * Utility for extracting YAML/JSON5 frontmatter and backmatter blocks from text.
 * Optimized for O(1) or O(n) scan of header/footer only.
 */
export class YamlExtractor {
    /**
     * Extracts the first YAML/JSON5 block found at the top of the file (Header).
     */
    public extractHeaderBlock(text: string): string | null {
        // 1. Try standard YAML delimiter ---
        const head = text.slice(0, 8192); // Increased scan range
        const yamlMatch = head.match(/^---\r?\n([\s\S]*?)\r?\n---/);
        if (yamlMatch) return yamlMatch[1].trim();

        // 2. Try @wolfie.headers { ... } annotation (common in code comments)
        const annotatedMatch = head.match(/@wolfie\.headers\s*({[\s\S]*?})\s*(?:\*\/|\n)/);
        if (annotatedMatch) return annotatedMatch[1].trim();

        return null;
    }

    /**
     * Extracts the last YAML/JSON5 block found at the bottom of the file (Footer).
     */
    public extractFooterBlock(text: string): string | null {
        // 1. Try standard YAML delimiter ---
        const tail = text.slice(-8192);
        const yamlMatch = tail.match(/---\r?\n([\s\S]*?)\r?\n---\s*$/);
        if (yamlMatch) return yamlMatch[1].trim();

        // 2. Try @flip.footer { ... } annotation
        const annotatedMatch = tail.match(/@flip\.footer\s*({[\s\S]*?})\s*(?:\*\/|\n)/);
        if (annotatedMatch) return annotatedMatch[1].trim();

        return null;
    }

    /**
     * Robust parser for YAML-lite and JSON5-lite.
     */
    public parseMetadata(block: string): any {
        const trimmed = block.trim();

        // 1. Direct JSON5 check
        if (trimmed.startsWith('{')) {
            return this.parseJson5Lite(trimmed);
        }

        // 2. Wrap block as JSON5 if it looks like a sequence of key: { ... }
        // This handles the hybrid V2/V3 format KIRO often outputs
        if (trimmed.includes(': {') && trimmed.endsWith('}')) {
            try {
                // Pre-process: convert "key: {" to "key\": {" for JSON compatibility
                let jsonish = trimmed
                    .replace(/^([a-z0-9_\.]+):/gm, '"$1":')
                    .replace(/:\s*{/g, ': {')
                    .replace(/:\s*\[/g, ': [');

                // If it's still not a valid object, wrap it
                if (!jsonish.startsWith('{')) jsonish = '{' + jsonish + '}';

                const parsed = this.parseJson5Lite(jsonish);
                if (parsed) return parsed;
            } catch (e) { /* fallback to recursive */ }
        }

        const lines = trimmed.split(/\r?\n/);
        return this.parseRecursive(lines, 0).result;
    }

    /**
     * Parse JSON5-lite (supports comments and trailing commas)
     */
    private parseJson5Lite(text: string): any {
        try {
            // 1. Remove single-line comments //
            // 2. Remove multi-line comments /* */
            // 3. Remove trailing commas before } or ]
            const clean = text
                .replace(/\/\/.*$/gm, '')
                .replace(/\/\*[\s\S]*?\*\//g, '')
                .replace(/,(\s*[}\]])/g, '$1');

            return JSON.parse(clean);
        } catch (e) {
            console.error('Failed to parse as JSON5-lite:', e);
            return null;
        }
    }

    private parseRecursive(lines: string[], indent: number): { result: any, nextIdx: number } {
        const result: any = {};
        let i = 0;

        while (i < lines.length) {
            const line = lines[i];
            const trimmed = line.trim();
            if (!trimmed || trimmed.startsWith('#')) {
                i++;
                continue;
            }

            const currentIndent = line.search(/\S/);
            if (currentIndent < indent && currentIndent !== -1) {
                // We've moved back out of this object
                return { result, nextIdx: i };
            }

            if (trimmed.startsWith('-')) {
                // List item handling (simplified)
                // This logic is a bit complex for a recursive KV parser
                // For FLIP, let's assume lists are simple strings
                i++;
                continue;
            }

            const colonIdx = trimmed.indexOf(':');
            if (colonIdx === -1) {
                i++;
                continue;
            }

            const key = trimmed.slice(0, colonIdx).trim();
            let value = trimmed.slice(colonIdx + 1).trim();

            if (!value) {
                // Check if next lines are indented further
                if (i + 1 < lines.length) {
                    const nextLine = lines[i + 1];
                    const nextIndent = nextLine.search(/\S/);
                    if (nextIndent > currentIndent) {
                        // It's a nested object or list
                        if (nextLine.trim().startsWith('-')) {
                            // It's a list
                            const list = [];
                            let j = i + 1;
                            while (j < lines.length) {
                                const l = lines[j];
                                const ti = l.trim();
                                if (!ti) { j++; continue; }
                                const liIndent = l.search(/\S/);
                                if (liIndent <= currentIndent) break;
                                if (ti.startsWith('-')) {
                                    list.push(ti.slice(1).trim().replace(/^["']|["']$/g, ''));
                                }
                                j++;
                            }
                            result[key] = list;
                            i = j;
                            continue;
                        } else {
                            // It's a nested object
                            const nested = this.parseRecursive(lines.slice(i + 1), nextIndent);
                            result[key] = nested.result;
                            i += nested.nextIdx + 1;
                            continue;
                        }
                    }
                }
                result[key] = null;
            } else {
                result[key] = value.replace(/^["']|["']$/g, '');
            }
            i++;
        }

        return { result, nextIdx: i };
    }
}
