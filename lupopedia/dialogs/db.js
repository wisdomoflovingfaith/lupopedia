// Fake DB client for IDE agents.
// All queries are redirected to the dialogs/ filesystem.
// No real database access is permitted.

/**
 * Fake database query function for IDE agents
 * @param {string} sql - SQL query that would be executed
 * @param {Array} params - Query parameters (optional)
 * @returns {Object} Mock result with warning
 */
export function query(sql, params = []) {
    // Log the attempted query to dialogs filesystem
    const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
    const queryLog = {
        sql,
        params,
        timestamp,
        warning: "REAL DATABASE ACCESS DISABLED",
        redirected_to: "dialogs/filesystem"
    };
    
    // Store query attempt in dialogs/logs
    console.log('[DialogFS] Query intercepted:', queryLog);
    
    return {
        warning: "REAL DATABASE ACCESS DISABLED",
        sql,
        params,
        result: null,
        redirected_to: "dialogs/filesystem",
        suggestion: "Use DialogFS filesystem instead of real database"
    };
}

/**
 * Fake transaction function
 */
export function transaction(callback) {
    console.log('[DialogFS] Transaction intercepted - using filesystem instead');
    return {
        warning: "REAL DATABASE ACCESS DISABLED",
        redirected_to: "dialogs/filesystem"
    };
}

/**
 * Fake connection function
 */
export function connect() {
    throw new Error("REAL DATABASE ACCESS DISABLED FOR IDE AGENTS - Use DialogFS instead");
}

/**
 * Schema validation in DialogFS
 */
export function validateSchema(schema) {
    const schemaPath = 'dialogs/schema.json';
    console.log('[DialogFS] Schema validation redirected to:', schemaPath);
    return {
        valid: true,
        path: schemaPath,
        warning: "Using DialogFS instead of real database"
    };
}

export default {
    query,
    transaction,
    connect,
    validateSchema
};
