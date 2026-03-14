# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\specs\FLIP_DATABASE_MAPPING_LAYER_4.0.28.md"
  file_hash: "5493e770ec420498f356dec2168ff058921d744314efb6b9d4c5223c57cf621f"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\specs\FLIP_DATABASE_MAPPING_LAYER_4.0.28.md"
  file_hash: "b500a85b379c670505a043ea9b7ee3480d57dbba15c4ffcee959c1cee67abf2f"
  file_path_from_root: "lupo-docs\specs\FLIP_DATABASE_MAPPING_LAYER_4.0.28.md"
  file_hash: "fdf2752cf4fd3fc078487597afa8bb2a039ab8351c4f31f1f9853e3fcb51a86e"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for FLIP_DATABASE_MAPPING_LAYER_4.0.28.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "specs", "flip_database_mapping_layer_4028md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
X-Lupo-File-Path: lupo-docs/specs/FLIP_DATABASE_MAPPING_LAYER_4.0.28.md
X-Lupo-Version: "4.0.27"
X-Lupo-UTC-Timestamp: "20260222194455"
X-Lupo-Channel: 42   # ANUBIS adoption channel
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool"
X-Lupo-Location: "Sioux Falls, South Dakota, US"
tags: ["lost", "orphan", "doctrine"]
mood_rgb: "FFDAB9"
atoms:
  recovery_event: true
web:
  canonical: /specs/FLIP_DATABASE_MAPPING_LAYER_4.0.28
  aliases:
    - /docs/FLIP_DATABASE_MAPPING_LAYER_4.0.28
    - /qa/FLIP+DATABASE+MAPPING+LAYER+4.0.28
  slug: FLIP_DATABASE_MAPPING_LAYER_4.0.28
  slug_encoding: underscore
  base_path: /specs
  url_pattern: "/{base}/{slug}"
---

# FLIP Database Mapping Layer Specification 4.0.28
**X-Lupo-Actor-ID**: 2039  
**X-Lupo-Actor-Identity**: Warp IDE  
**Date**: 2026-02-22  
**Status**: SPECIFICATION - Awaiting Windsurf IDE (2040) Implementation  
**Version**: 4.0.28

## OVERVIEW

This specification defines an **optional database mapping layer** for FLIP headers using the syntax:

```
X-LUPO-{table}.{column}: <value>
```

This layer is **NOT** a replacement for semantic FLIP headers. It is an **optional, namespaced extension** that allows files to explicitly reference database columns when needed for advanced tooling, migrations, and schema-aware operations.

## DOCTRINE PRINCIPLES

### 1. SEMANTIC-FIRST REMAINS UNCHANGED

The existing semantic FLIP headers remain the primary method for file attribution:

```
X-Lupo-Actor-ID: 2038
X-Lupo-Channel-ID: 42
X-Lupo-Thread-ID: 1001
X-Lupo-File-Path: lupo-docs/specs/example.md
X-Lupo-File-Last-Modified-System-Version: 4.0.27
X-Lupo-File-Last-Modified-UTC: 20260222120000
```

**These remain required and canonical.**

### 2. MAPPING LAYER IS OPTIONAL

The new mapping layer provides explicit column mapping:

```
X-LUPO-actors.actor_id: 2038
X-LUPO-actors.actor_type: ai
X-LUPO-actors.slug: deepseek-lilith
X-LUPO-actors.name: DeepSeek LILITH
X-LUPO-channels.channel_id: 42
X-LUPO-channels.channel_key: lupopedia-development
X-LUPO-dialog_messages.dialog_message_id: 2000
X-LUPO-dialog_messages.message_text: Example message
```

**These are optional metadata and must never be required for inference.**

### 3. CONSTRAINTS

The mapping layer:
- ✅ MUST use namespace prefix `X-LUPO-` (all caps)
- ✅ MUST use format `{table}.{column}` (lowercase table/column names)
- ✅ MUST treat values as opaque strings (no type inference)
- ✅ MUST validate table/column names against schema
- ❌ MUST NOT override semantic field values
- ❌ MUST NOT be required for file processing
- ❌ MUST NOT be used for schema guessing
- ❌ MUST NOT be used for write-back unless explicitly invoked

### 4. SQL DOCTRINE ENFORCEMENT

When mapping layer is present, SQL generation:
- ✅ MUST explicitly list columns in INSERT statements
- ✅ MUST include `created_ymdhis` and `updated_ymdhis` when table has them
- ✅ MUST use named column lists (never positional INSERTs)
- ✅ MUST validate column names against actual schema
- ❌ MUST NOT guess missing columns
- ❌ MUST NOT auto-fill columns not in mapping

## SYNTAX SPECIFICATION

### Valid Header Format

```
X-LUPO-{table}.{column}: {value}
```

**Components**:
- `X-LUPO-` - Fixed namespace prefix (required, all caps)
- `{table}` - Lowercase table name from schema (e.g., `actors`, `channels`, `dialog_messages`)
- `.` - Separator (required)
- `{column}` - Lowercase column name from schema (e.g., `actor_id`, `channel_key`, `created_ymdhis`)
- `{value}` - Opaque string value (no type inference, application validates)

### Examples

**Valid**:
```
X-LUPO-actors.actor_id: 2040
X-LUPO-channels.channel_key: windsurf-dev
X-LUPO-dialog_threads.created_ymdhis: 20260222120000
X-LUPO-contents.title: FLIP Mapping Layer Specification
```

**Invalid**:
```
X-Lupo-Actors.ActorID: 2040              ❌ Wrong case
X-LUPO-actor.id: 2040                    ❌ Wrong separator
X-LUPO-nonexistent_table.column: value   ❌ Invalid table
X-LUPO-actors.fake_column: value         ❌ Invalid column
```

## VALIDATION RULES

### Parser Must Validate

1. **Namespace Prefix**: Header starts with `X-LUPO-` (exact case)
2. **Format**: Contains exactly one `.` separator after prefix
3. **Table Name**: Matches a table in `install_new_lupopedia.sql`
4. **Column Name**: Matches a column in the specified table
5. **No Semantic Collision**: Does not conflict with semantic FLIP headers

### Error Handling

- **Invalid Format**: Log warning, ignore header
- **Unknown Table**: Log warning, ignore header
- **Unknown Column**: Log warning, ignore header
- **Type Mismatch**: Accept value as string, let application validate
- **Collision with Semantic**: Semantic header takes precedence, log warning

## IMPLEMENTATION TASKS (Windsurf IDE - Actor 2040)

### Task 1: Update FLIP Header Generator (`lupo-tools/vsx-extension/src/lupopedia/flip.ts`)

**Current Interface** (excerpt):
```typescript
interface FlipHeader {
    actor_id?: number;
    actor_identity?: string;
    channel_id?: number;
    thread_id?: number;
    file_path_from_root?: string;
    // ... existing semantic fields
}
```

**Add Mapping Layer**:
```typescript
interface FlipHeader {
    // Existing semantic fields
    actor_id?: number;
    actor_identity?: string;
    channel_id?: number;
    // ... other semantic fields
    
    // NEW: Optional database mapping layer
    database_mapping?: {
        [key: string]: string;  // Format: "table.column" -> "value"
    };
}
```

**Generator Function**:
```typescript
function generateMappingLayerHeaders(mapping: Record<string, string>): string {
    const lines: string[] = [];
    
    for (const [key, value] of Object.entries(mapping)) {
        // Validate format: table.column
        if (!/^[a-z_]+\.[a-z_]+$/.test(key)) {
            console.warn(`Invalid mapping key format: ${key}`);
            continue;
        }
        
        const [table, column] = key.split('.');
        
        // Validate against schema (implement schema validation)
        if (!isValidTable(table)) {
            console.warn(`Unknown table: ${table}`);
            continue;
        }
        
        if (!isValidColumn(table, column)) {
            console.warn(`Unknown column: ${table}.${column}`);
            continue;
        }
        
        // Generate header
        lines.push(`X-LUPO-${key}: ${value}`);
    }
    
    return lines.join('\n');
}
```

### Task 2: Update FLIP Header Parser

**Add Mapping Layer Parsing**:
```typescript
function parseFlipHeaders(content: string): FlipHeader {
    const header: FlipHeader = {};
    const mapping: Record<string, string> = {};
    const lines = content.split('\n');
    
    for (const line of lines) {
        // Existing semantic header parsing
        if (line.startsWith('X-Lupo-Actor-ID:')) {
            header.actor_id = parseInt(line.split(':')[1].trim());
            continue;
        }
        
        // NEW: Database mapping layer parsing
        if (line.startsWith('X-LUPO-')) {
            const match = line.match(/^X-LUPO-([a-z_]+)\.([a-z_]+):\s*(.+)$/);
            if (match) {
                const [, table, column, value] = match;
                const key = `${table}.${column}`;
                
                // Validate and store
                if (isValidTable(table) && isValidColumn(table, column)) {
                    mapping[key] = value.trim();
                } else {
                    console.warn(`Invalid mapping: ${key}`);
                }
            }
        }
    }
    
    if (Object.keys(mapping).length > 0) {
        header.database_mapping = mapping;
    }
    
    return header;
}
```

### Task 3: Schema Validation

**Implement Schema Validator**:
```typescript
// Load schema from install_new_lupopedia.sql
const SCHEMA_TABLES: Record<string, string[]> = {
    'actors': [
        'actor_id', 'actor_type', 'slug', 'name', 'created_ymdhis',
        'updated_ymdhis', 'is_active', 'is_deleted', 'paired_actor_id'
        // ... full column list
    ],
    'channels': [
        'channel_id', 'federation_node_id', 'channel_key', 'channel_slug',
        'channel_type', 'channel_name', 'created_ymdhis', 'updated_ymdhis'
        // ... full column list
    ],
    'dialog_messages': [
        'dialog_message_id', 'message_id', 'dialog_thread_id', 'channel_id',
        'from_actor_id', 'to_actor_id', 'message_text', 'message_type',
        'created_ymdhis', 'updated_ymdhis'
        // ... full column list
    ]
    // ... all tables
};

function isValidTable(table: string): boolean {
    return table in SCHEMA_TABLES;
}

function isValidColumn(table: string, column: string): boolean {
    return SCHEMA_TABLES[table]?.includes(column) ?? false;
}
```

### Task 4: SQL Generation with Mapping Layer

**Generate INSERT from Mapping**:
```typescript
function generateInsertFromMapping(
    table: string,
    mapping: Record<string, string>
): string {
    // Extract columns for this table
    const columns: string[] = [];
    const values: string[] = [];
    
    for (const [key, value] of Object.entries(mapping)) {
        const [mappedTable, column] = key.split('.');
        
        if (mappedTable === table) {
            columns.push(column);
            values.push(value);
        }
    }
    
    // Ensure required timestamp columns
    if (SCHEMA_TABLES[table].includes('created_ymdhis') && !columns.includes('created_ymdhis')) {
        console.warn(`Missing required column: created_ymdhis`);
    }
    
    if (SCHEMA_TABLES[table].includes('updated_ymdhis') && !columns.includes('updated_ymdhis')) {
        console.warn(`Missing required column: updated_ymdhis`);
    }
    
    // Generate SQL with explicit column list
    return `INSERT INTO lupo_${table} (${columns.join(', ')}) VALUES (${values.join(', ')});`;
}
```

### Task 5: Update Documentation

**Files to Update**:
1. `lupo-docs/specs/FLIP_HEADERS_VERBOSE_COMPLETE_4.0.27.md`
2. `lupo-docs/specs/UNIVERSAL_WOLFIE_HEADER_SPECIFICATION.md`
3. `lupo-docs/specs/COLLECTION_FLIP_HEADERS_USAGE.md`
4. `lupo-docs/specs/FLIP_HEADER_SPECIFICATION_4.0.23.md`

**Add Section** (template):
```markdown
## Database Mapping Layer (Optional)

### Overview
The `X-LUPO-{table}.{column}` namespace provides explicit mapping between
FLIP headers and database schema. This layer is **optional** and **must not**
replace semantic FLIP fields.

### When to Use
- Advanced migration tooling
- Schema-aware agents
- Explicit database write-back
- Debugging and validation

### When NOT to Use
- Standard file attribution (use semantic headers)
- Inference-based processing
- Schema-agnostic operations

### Syntax
```
X-LUPO-{table}.{column}: {value}
```

### Example
```markdown
X-Lupo-Actor-ID: 2040
X-Lupo-Channel-ID: 42
X-LUPO-actors.actor_type: system_tool
X-LUPO-actors.slug: windsurf-ide
X-LUPO-channels.channel_key: windsurf-dev
```

### Constraints
- Must use `X-LUPO-` prefix (all caps)
- Must validate table/column against schema
- Must not override semantic headers
- Must not be required for processing
```

### Task 6: VSX Extension Behavior

**Offline Mode**:
- Include mapping layer only if present in file
- Do NOT auto-generate unless explicitly requested
- Do NOT infer table/column names from semantic fields

**Online Mode**:
- Query database for actual values if needed
- Validate mapping against live schema
- Generate mapping layer only when requested

## USE CASES

### Use Case 1: Migration Tooling
```markdown
X-Lupo-Actor-ID: 2038
X-LUPO-actors.actor_id: 2038
X-LUPO-actors.actor_type: external_ai
X-LUPO-actors.slug: deepseek-lilith
X-LUPO-actors.created_ymdhis: 20260220000000
X-LUPO-actors.updated_ymdhis: 20260222120000
```

Migration tool can generate exact INSERT statement from mapping layer.

### Use Case 2: Schema Validation
```markdown
X-Lupo-Channel-ID: 666
X-LUPO-channels.channel_id: 666
X-LUPO-channels.channel_key: protocol-dev
X-LUPO-channels.federation_node_id: 1
```

Validator can verify semantic headers match database mapping.

### Use Case 3: Debugging
```markdown
X-Lupo-Thread-ID: 1001
X-LUPO-dialog_threads.dialog_thread_id: 1001
X-LUPO-dialog_threads.channel_id: 42
X-LUPO-dialog_threads.status: Open
```

Developers can see exact database state in file headers.

## TESTING REQUIREMENTS

### Test 1: Valid Mapping Parsing
```typescript
test('parses valid database mapping layer', () => {
    const content = `
X-Lupo-Actor-ID: 2040
X-LUPO-actors.actor_type: system_tool
X-LUPO-channels.channel_id: 42
`;
    const header = parseFlipHeaders(content);
    expect(header.actor_id).toBe(2040);
    expect(header.database_mapping).toEqual({
        'actors.actor_type': 'system_tool',
        'channels.channel_id': '42'
    });
});
```

### Test 2: Invalid Format Rejection
```typescript
test('rejects invalid mapping format', () => {
    const content = `
X-LUPO-InvalidFormat: value
X-LUPO-actors.fake_column: value
`;
    const header = parseFlipHeaders(content);
    expect(header.database_mapping).toBeUndefined();
});
```

### Test 3: Semantic Header Priority
```typescript
test('semantic headers take priority', () => {
    const content = `
X-Lupo-Actor-ID: 2040
X-LUPO-actors.actor_id: 9999
`;
    const header = parseFlipHeaders(content);
    expect(header.actor_id).toBe(2040);  // Semantic value wins
});
```

## WINDSURF IMPLEMENTATION CHECKLIST

- [ ] Update `FlipHeader` interface with `database_mapping` field
- [ ] Implement `generateMappingLayerHeaders()` function
- [ ] Update `parseFlipHeaders()` to recognize `X-LUPO-` prefix
- [ ] Implement schema validation (`isValidTable`, `isValidColumn`)
- [ ] Load schema from `install_new_lupopedia.sql` or JSON cache
- [ ] Implement SQL generation from mapping layer
- [ ] Add validation rules (format, table, column)
- [ ] Add error handling and warnings
- [ ] Update documentation (4 files)
- [ ] Add unit tests (parsing, validation, generation)
- [ ] Update VSX extension behavior (offline/online modes)
- [ ] Verify no doctrine violations (semantic-first preserved)

## COMPLETION CRITERIA

Implementation is complete when:
1. ✅ Parser recognizes and validates `X-LUPO-{table}.{column}` headers
2. ✅ Generator can create mapping layer from schema data
3. ✅ Validator enforces all constraints
4. ✅ SQL generation uses explicit column lists
5. ✅ Documentation updated in all 4 files
6. ✅ Tests cover valid/invalid cases
7. ✅ No semantic FLIP headers are affected
8. ✅ Offline mode works without mapping layer

---
**Status**: SPECIFICATION COMPLETE  
**Assigned To**: Windsurf IDE (actor_id 2040)  
**Created By**: Warp IDE (actor_id 2039)  
**Date**: 2026-02-22
