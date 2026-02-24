// src/lupopedia/flip/logic/DelegationEngine.ts

export class DelegationEngine {
    /**
     * Get the principal authority (the last ID in the chain)
     */
    public static getPrincipal(chain: string): number | null {
        const path = this.getDelegationPath(chain);
        return path.length > 0 ? path[path.length - 1] : null;
    }

    /**
     * Get the executor agent (the first ID in the chain)
     */
    public static getExecutor(chain: string): number | null {
        const path = this.getDelegationPath(chain);
        return path.length > 0 ? path[0] : null;
    }

    /**
     * Get the full delegation path as an array of IDs
     */
    public static getDelegationPath(chain: string): number[] {
        if (!chain) return [];
        return chain.split(':')
            .filter(s => s.trim() !== '')
            .map(id => parseInt(id.trim()))
            .filter(n => !isNaN(n));
    }

    /**
     * Validate a delegation chain against the v4.1 rules
     */
    public static validate(chain: string, actorId?: number): { valid: boolean; error?: string } {
        const path = this.getDelegationPath(chain);

        if (path.length < 2) {
            return { valid: false, error: 'Delegation chain must contain at least 2 IDs (Execution Agent : Human Authority)' };
        }

        // Rule: All intermediate actors must be agents (< 10000)
        for (let i = 0; i < path.length - 1; i++) {
            if (path[i] >= 10000) {
                return { valid: false, error: `Intermediate actor at position ${i} must be an agent (ID < 10000)` };
            }
        }

        // Rule: Final authority must be human (>= 10000)
        if (path[path.length - 1] < 10000) {
            return { valid: false, error: 'Final authority in delegation chain must be a human (ID >= 10000)' };
        }

        // Rule: If actorId is provided, it must match the first ID (the executor)
        if (actorId !== undefined && path[0] !== actorId) {
            return { valid: false, error: `Executor in chain (${path[0]}) does not match actor_id (${actorId})` };
        }

        return { valid: true };
    }

    /**
     * Attempt to repair common delegation chain errors
     */
    public static autoRepair(chain: string, currentActorId?: number): string {
        let path = this.getDelegationPath(chain);

        // If empty and we have an actor ID, start a chain
        if (path.length === 0 && currentActorId !== undefined) {
            return `${currentActorId}:10000`; // Default to Captain if unknown
        }

        if (path.length === 1) {
            // If only one, assume it's the agent and add Captain as authority
            if (path[0] < 10000) {
                return `${path[0]}:10000`;
            } else {
                // If only a human, add a generic agent if we have one, or move to end
                return `1001:${path[0]}`; // KIRO as default proxy
            }
        }

        // Ensure first ID matches actorId if provided
        if (currentActorId !== undefined && path[0] !== currentActorId) {
            // Prepend or replace? Let's prepend if path[0] is human, else replace
            if (path[0] >= 10000) {
                path = [currentActorId, ...path];
            } else {
                path[0] = currentActorId;
            }
        }

        // Final check on ranges
        const repaired = path.map((id, idx) => {
            if (idx === path.length - 1) {
                return id < 10000 ? 10000 : id; // Final must be human
            }
            return id >= 10000 ? 1001 : id; // Intermediates must be agents
        });

        return repaired.join(':');
    }
}
