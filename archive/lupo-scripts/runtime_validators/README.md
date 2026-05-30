---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "lupo-scripts/runtime_validators/README.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/runtime_validators/README.md"
  status: "active"
  when_updated: "20260420080000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/development/canonical/1026/04/runtime-validators-readme.toon"
  atoms_toon: "lupo-memory/atoms/lupopedia_global_constants.atom.toon"
  transcript_jsonl: "0/development/runtime-validators-readme"
  artifact_type: documentation
  artifact_kind: guide
  channel_key: "development"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  content_slug: "runtime-validators-readme"
  default_collection_id: null
  lupopedia.schema: documentation
  title: "Runtime Ledger Validators Guide"
  summary: "Complete validator suite documentation for Lupopedia Runtime Ledger integrity and compliance."
---

# Lupopedia Runtime Ledger Validators

Complete validator suite for the Lupopedia Runtime Ledger according to PRD 70: Actor Runtime Directory Structure and canonical runtime ledger schemas.

## Overview

The validator suite ensures runtime ledger integrity, coordination compliance, and memory integration. All validators are read-only and operate on the filesystem only - no database or registry modifications.

## Directory Structure Expected

```
lupo-runtime/
+-- channels.jsonl
+-- {channel_key}/
    +-- actors.jsonl
    +-- {actor_id}/
        +-- tasks.jsonl
        +-- interrupts.jsonl
        +-- dependencies.jsonl
        +-- install_state.json
```

## Validators

### 1. validate_runtime_structure.php

Validates directory layout according to PRD 70 §3.1.

**Purpose:**
- Ensures directory layout matches canonical structure
- Ensures required files exist for each active actor
- Ensures channel directories exist for all channels in channels.jsonl

**Usage:**
```bash
php validate_runtime_structure.php [runtime_path]
```

**Error Codes:**
- `STRUCTURE_ROOT_MISSING` - Runtime directory not found
- `STRUCTURE_MISSING_FILE` - Required file missing
- `STRUCTURE_NOT_READABLE` - File not readable
- `STRUCTURE_NON_ASCII` - Non-ASCII characters detected
- `STRUCTURE_JSON_INVALID` - Invalid JSON format
- `STRUCTURE_MISSING_FIELD` - Missing required field
- `STRUCTURE_INVALID_CHANNEL_KEY` - Invalid channel_key format
- `STRUCTURE_CHANNEL_MISSING` - Channel directory missing
- `STRUCTURE_ACTORS_MISSING` - actors.jsonl missing
- `STRUCTURE_ACTORS_NOT_READABLE` - actors.jsonl not readable
- `STRUCTURE_ACTOR_DIR_MISSING` - Actor directory missing
- `STRUCTURE_ACTOR_FILE_MISSING` - Required actor file missing
- `STRUCTURE_ACTOR_FILE_NOT_READABLE` - Actor file not readable
- `STRUCTURE_INSTALL_STATE_READ` - Cannot read install_state.json
- `STRUCTURE_INSTALL_STATE_NON_ASCII` - Non-ASCII in install_state.json
- `STRUCTURE_INSTALL_STATE_JSON_INVALID` - Invalid JSON in install_state.json

### 2. validate_runtime_jsonl_format.php

Validates JSONL correctness and format compliance.

**Purpose:**
- Validates JSONL correctness (one JSON object per line)
- Validates ASCII-only content
- Validates required fields per schema
- Validates no trailing commas, no trailing whitespace

**Usage:**
```bash
php validate_runtime_jsonl_format.php [runtime_path]
```

**Error Codes:**
- `JSONL_FILE_MISSING` - JSONL file missing
- `JSONL_NOT_READABLE` - JSONL file not readable
- `JSONL_READ_ERROR` - Cannot read JSONL file
- `JSONL_EMPTY_LINE` - Empty line in JSONL
- `JSONL_TRAILING_WHITESPACE` - Trailing whitespace on line
- `JSONL_NON_ASCII` - Non-ASCII character detected
- `JSONL_INVALID_JSON` - Invalid JSON on line
- `JSONL_MISSING_REQUIRED` - Missing required field
- `JSONL_MISSING_PRIMARY_KEY` - Missing primary key field
- `JSONL_EMPTY_PRIMARY_KEY` - Primary key field is empty
- `JSONL_INVALID_TIMESTAMP` - Invalid timestamp format
- `JSONL_INVALID_ACTOR_ID` - Invalid actor_id
- `JSONL_INVALID_CHANNEL_KEY` - Invalid channel_key

**Warnings:**
- `JSONL_EMPTY` - JSONL file is empty
- `JSONL_NO_FINAL_NEWLINE` - File does not end with newline
- `JSONL_EXTRA_NEWLINES` - File ends with multiple newlines

### 3. validate_runtime_schema.php

Validates each JSON object against its corresponding schema.

**Purpose:**
- Validates field types, required fields, and allowed values
- Validates timestamp formats
- Validates actor_id and channel_key consistency

**Usage:**
```bash
php validate_runtime_schema.php [runtime_path]
```

**Error Codes:**
- `SCHEMA_INSTALL_STATE_READ` - Cannot read install_state.json
- `SCHEMA_INSTALL_STATE_JSON` - Invalid JSON in install_state.json
- `SCHEMA_MISSING_REQUIRED` - Missing required field
- `SCHEMA_NULL_NOT_ALLOWED` - Field cannot be null
- `SCHEMA_MISSING_PRIMARY_KEY` - Missing primary key field
- `SCHEMA_EMPTY_PRIMARY_KEY` - Primary key field is empty
- `SCHEMA_TYPE_MISMATCH` - Field type mismatch
- `SCHEMA_INVALID_ACTOR_ID` - Invalid actor_id
- `SCHEMA_INVALID_CHANNEL_KEY` - Invalid channel_key
- `SCHEMA_INVALID_TASK_STATE` - Invalid task_state value
- `SCHEMA_INVALID_ACTOR_STATE` - Invalid actor_state value
- `SCHEMA_INVALID_CHANNEL_STATE` - Invalid channel_state value
- `SCHEMA_INVALID_TIMESTAMP` - Invalid timestamp format

**Warnings:**
- `SCHEMA_TIMESTAMP_YEAR` - Unusual timestamp year

### 4. validate_runtime_coordination.php

Enforces coordination rules and isolation.

**Purpose:**
- Enforces channel_key isolation (PRD 70 §4.1)
- Enforces task boundary rules (PRD 70 §4.2)
- Enforces append-only rules for .jsonl files
- Enforces valid handoff targets
- Enforces dependency resolution rules

**Usage:**
```bash
php validate_runtime_coordination.php [runtime_path]
```

**Error Codes:**
- `COORDINATION_CHANNEL_ISOLATION` - Task in wrong channel
- `COORDINATION_ACTOR_ISOLATION` - Task owned by wrong actor
- `COORDINATION_DEP_CHANNEL_CROSS` - Dependency crosses channels
- `COORDINATION_APPEND_ONLY` - Non-monotonic timestamp
- `COORDINATION_DUPLICATE_EVENT` - Duplicate event_id
- `COORDINATION_HANDOFF_NO_CHANNEL` - Handoff to non-existent channel
- `COORDINATION_HANDOFF_INVALID_ACTOR` - Handoff to non-existent actor
- `COORDINATION_DEP_MISSING` - Dependency on non-existent task

**Warnings:**
- `COORDINATION_TASK_TRANSITION` - Unusual task state transition
- `COORDINATION_HANDOFF_STATE` - Handoff from non-completed task
- `COORDINATION_DEP_UNRESOLVED` - Dependency on unresolved task

### 5. validate_runtime_memory_integration.php

Ensures memory graph integration compliance.

**Purpose:**
- Ensures task completion events map to memory nodes
- Ensures dependencies map to memory edges
- Ensures handoff events create continuity artifacts
- Ensures no inference (PRD 38 §11)

**Usage:**
```bash
php validate_runtime_memory_integration.php [runtime_path]
```

**Error Codes:**
- `MEMORY_INFERENCE_DETECTED` - Memory node contains inferred language
- `MEMORY_EDGE_INFERENCE` - Memory edge contains inferred language

**Warnings:**
- `MEMORY_TASK_NODE_MISSING` - Completed task has no memory node
- `MEMORY_DEPENDENCY_EDGE_MISSING` - Dependency has no memory edge
- `MEMORY_HANDOFF_CONTINUITY_MISSING` - Handoff has no continuity artifact

## Running All Validators

Run all validators in sequence:

```bash
#!/bin/bash
RUNTIME_PATH="${1:-../lupo-runtime}"

echo "Running Runtime Ledger Validators..."
echo "Runtime Path: $RUNTIME_PATH"
echo

# Run all validators
validators=(
    "validate_runtime_structure.php"
    "validate_runtime_jsonl_format.php"
    "validate_runtime_schema.php"
    "validate_runtime_coordination.php"
    "validate_runtime_memory_integration.php"
)

total_errors=0
total_warnings=0

for validator in "${validators[@]}"; do
    echo "Running $validator..."
    php "$validator" "$RUNTIME_PATH" > "result_${validator%.php}.json"
    
    # Extract error/warning counts
    errors=$(jq -r '.summary.error_count' "result_${validator%.php}.json")
    warnings=$(jq -r '.summary.warning_count' "result_${validator%.php}.json")
    status=$(jq -r '.status' "result_${validator%.php}.json")
    
    total_errors=$((total_errors + errors))
    total_warnings=$((total_warnings + warnings))
    
    echo "  Status: $status, Errors: $errors, Warnings: $warnings"
    
    if [ "$status" = "fail" ]; then
        echo "  Errors:"
        jq -r '.errors[] | "    - \(.code): \(.message)"' "result_${validator%.php}.json"
    fi
    
    if [ "$warnings" -gt 0 ]; then
        echo "  Warnings:"
        jq -r '.warnings[] | "    - \(.code): \(.message)"' "result_${validator%.php}.json"
    fi
    
    echo
done

echo "Validation Complete"
echo "Total Errors: $total_errors"
echo "Total Warnings: $total_warnings"

if [ "$total_errors" -gt 0 ]; then
    exit 1
else
    exit 0
fi
```

## Integration with LILITH Audit Pipeline

Validators produce machine-readable JSON output suitable for LILITH audit processing:

```json
{
  "validator": "validate_runtime_structure",
  "timestamp": "2026-04-20 11:00:00",
  "runtime_path": "/path/to/lupo-runtime",
  "status": "pass",
  "errors": [],
  "warnings": [],
  "summary": {
    "error_count": 0,
    "warning_count": 0
  }
}
```

### LILITH Integration Points

1. **Error Classification**: LILITH can classify errors by severity and type
2. **Compliance Reporting**: Generate compliance reports from validator results
3. **Trend Analysis**: Track validation results over time
4. **Automated Enforcement**: Trigger remediation workflows for critical violations

## Severity Levels

- **Error**: Critical violations that must be fixed
  - Structural problems
  - Schema violations
  - Coordination rule violations
  - Memory inference violations

- **Warning**: Non-critical issues that should be reviewed
  - Missing memory artifacts
  - Unusual state transitions
  - Format inconsistencies

## Schema Validation

Validators use canonical schemas from:
`lupo-docs/database/lupopedia/tables/runtime/`

Schemas are loaded automatically and used for:
- Field type validation
- Required field checking
- Primary key validation
- Timestamp format validation

## Dependencies

- PHP 8.0+
- JSON extension
- Filesystem access to runtime directory
- Read-only access to memory directory (for memory integration validator)

## Troubleshooting

### Common Issues

1. **Permission Denied**: Ensure runtime directory is readable
2. **Missing Schemas**: Verify schema files exist in expected location
3. **Memory Integration Fails**: Check memory directory path and permissions
4. **JSON Parse Errors**: Validate JSONL files manually for syntax issues

### Debug Mode

Add debug output by setting environment variable:
```bash
DEBUG=1 php validate_runtime_structure.php
```

### Performance Considerations

- Validators are optimized for large runtime directories
- Memory integration validator scans entire memory directory - may be slow
- Consider running validators individually for targeted validation

## Compliance

Validators enforce compliance with:
- PRD 70: Actor Runtime Directory Structure
- PRD 16: Header Doctrine (v4.1.3)
- PRD 38: Memory Unification Doctrine
- PRD 51: Memory Graph Authority Doctrine
- Canonical runtime ledger schemas

All validators are:
- Read-only (no modifications)
- Non-destructive
- ASCII-only compliant
- Database-neutral
