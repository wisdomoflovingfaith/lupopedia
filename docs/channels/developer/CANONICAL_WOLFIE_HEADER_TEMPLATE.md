---
wolfie.headers.version: 4.2.3
dialog:
  speaker: WOLFIE
  target: @everyone
  message: "Created comprehensive documentation for canonical Wolfie header template with Cascade compatibility."
tags:
  categories: ["documentation", "specification", "wolfie-header"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "Canonical Wolfie Header Template Documentation"
  description: "Complete guide to using the canonical Wolfie header template for Cascade compatibility"
  version: "4.2.3"
  status: published
  author: "Captain Wolfie"
---

# Canonical Wolfie Header Template

## Overview

The canonical Wolfie Header Template provides a **fully deterministic**, **schema-agnostic**, and **Cascade-ready** header structure for all Lupopedia files. This template ensures file-sovereignty while maintaining compatibility with the Lupopedia 4.2.3 ecosystem.

## Template Location

- **Template File**: `/templates/canonical_wolfie_header_template.yaml`
- **Generator Script**: `/scripts/generate_canonical_header.php`
- **Documentation**: `/docs/CANONICAL_WOLFIE_HEADER_TEMPLATE.md`

## Key Characteristics

### 🧩 Cascade Compatibility Features

- **Fully Deterministic**: No interpretive or symbolic fields
- **Schema-Agnostic**: Works with any file type and structure
- **Dreaming-Safe**: No meta-layer artifacts that could be misinterpreted
- **Witness-Safe**: No fields that require contextual interpretation
- **Migration-Safe**: No schema mutations or dependencies
- **Doctrine-Aligned**: Complies with 4.2.3 governance rules

### 🏗️ Structural Components

#### 🐺 MANDATORY WOLFIE HEADER CORE
The irreducible structural metadata block that MUST be present in every file:

```yaml
---
wolfie.headers: explicit architecture with structured clarity for every file.

file.name: "<FILENAME>"
file.last_modified_system_version: <SYSTEM_VERSION>
file.last_modified_utc: <UTC_TIMESTAMP>
file.utc_day: <UTC_DAY>

author: GLOBAL_CURRENT_AUTHORS

system_context:
  schema_state: "Frozen"
  table_ceiling: 185
  governance_active:
    - "GOV-AD-PROHIBIT-001"
    - "GOV-LILITH-0001"
    - "GOV-INTEGRATION-0001"
    - "LABS-001"
    - "TABLE_COUNT_DOCTRINE"
    - "LIMITS_DOCTRINE"
  doctrine_mode: "File-Sovereignty"
---
```

#### Optional Extended Components (Cascade-compatible)
```yaml
# Optional Extended Components (Cascade-compatible)
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
  - UTC_TIMEKEEPER__CHANNEL_ID

temporal_edges:
  actor_identity: "Eric (Captain Wolfie)"
  actor_location: "Sioux Falls, South Dakota"
  system_context: "GOV + XML; append-only; LupopediaMigrationController; TOON; table ceiling 185; Block, Dreaming (GOV-LILITH-0001), Witness (GOV-INTEGRATION-0001); three-layer model"
  branch: "<BRANCH_NAME>"

dialog:
  speaker: WOLFIE
  target: "@Castcade"
  mood_RGB: "0044FF"
  message: "File initialized with canonical Wolfie Header. Structural metadata only."

tags:
  categories: ["documentation", "wolfie-header"]
  collections: ["core-docs"]
  channels: ["dev"]

file:
  name: "<FILENAME>"
  title: "<HUMAN_READABLE_TITLE>"
  description: "<SHORT_DESCRIPTION>"
  version: <SYSTEM_VERSION>
  status: active
  author: GLOBAL_CURRENT_AUTHORS
```

## Usage Instructions

### 1. Manual Template Usage

Copy the template from `/templates/canonical_wolfie_header_template.yaml` and replace placeholders:

- `<FILENAME>` - Actual filename
- `<SYSTEM_VERSION>` - Current Lupopedia version
- `<UTC_TIMESTAMP>` - Current UTC timestamp
- `<UTC_DAY>` - Current UTC date
- `<HUMAN_READABLE_TITLE>` - File title
- `<SHORT_DESCRIPTION>` - Brief description
- `<BRANCH_NAME>` - Git branch name (defaults to "main")

### 2. Programmatic Generation

Use the PHP generator script:

```php
<?php
require_once 'scripts/generate_canonical_header.php';

// Full canonical header with optional components
$params = [
    'filename' => 'my_document.md',
    'title' => 'My Document Title',
    'description' => 'A brief description of my document',
    'message' => 'Created new document with canonical header',
    'branch' => 'develop'  // Optional: defaults to 'main'
];

$header = CanonicalHeaderGenerator::generateHeader($params);
echo $header;

// Minimal mandatory core only
$coreHeader = CanonicalHeaderGenerator::generateHeader([
    'filename' => 'minimal_file.md',
    'core_only' => true
]);
echo $coreHeader;
?>
```

### 3. Core-Only Generation

For minimal compliance with the mandatory WOLFIE HEADER CORE:

```php
$coreHeader = CanonicalHeaderGenerator::generateHeader([
    'filename' => 'essential_file.md',
    'core_only' => true
]);
```

This generates only the mandatory core fields:
- `wolfie.headers`
- `file.name`, `file.last_modified_system_version`, `file.last_modified_utc`, `file.utc_day`
- `author: GLOBAL_CURRENT_AUTHORS`
- `system_context` with all required governance items

### 3. Command Line Usage

```bash
php scripts/generate_canonical_header.php
```

## Validation Rules

The template includes built-in validation for Cascade compatibility:

### Mandatory Core Fields
- `wolfie.headers:` - Header specification
- `file.name:` - Filename
- `file.last_modified_system_version:` - System version
- `file.last_modified_utc:` - UTC timestamp
- `file.utc_day:` - UTC date
- `author:` - File author (GLOBAL_CURRENT_AUTHORS)
- `system_context:` - System governance context
- `schema_state: "Frozen"` - Schema status
- `table_ceiling: 185` - Database table limit
- `governance_active:` - Active governance rules
- `doctrine_mode: "File-Sovereignty"` - File autonomy mode

### Required Governance Items
- `GOV-AD-PROHIBIT-001` - Advertisement prohibition
- `GOV-LILITH-0001` - LILITH governance
- `GOV-INTEGRATION-0001` - Integration governance
- `LABS-001` - Laboratory governance
- `TABLE_COUNT_DOCTRINE` - Table count doctrine
- `LIMITS_DOCTRINE` - Limits doctrine

### Optional Extended Fields
- `header_atoms:` - Symbolic references
- `temporal_edges:` - Actor and system context
  - `branch:` - Git branch name (defaults to "main")
- `dialog:` - Agent communication
- `tags:` - Classification metadata
- `file:` - Extended file metadata

### Prohibited Fields
- No interpretive `mood:` fields (except RGB values)
- No `emotion:` fields
- No `symbolic:` fields
- No mystical or esoteric content

## Integration Examples

### Markdown Files
```markdown
---
[canonical header here]
---

# Document Title

Content goes here...
```

### PHP Files
```php
<?php
/**
 * [canonical header here]
 */

// PHP code here
?>
```

### SQL Files
```sql
/*
 * [canonical header here]
 */

-- SQL code here
```

## Migration Guide

### From Legacy Headers
1. Extract existing metadata
2. Map to canonical structure
3. Replace interpretive fields with structural equivalents
4. Validate compliance

### Validation Checklist
- [ ] All required fields present
- [ ] No prohibited interpretive fields
- [ ] Proper atom references
- [ ] Correct governance flags
- [ ] Valid YAML syntax

## Troubleshooting

### Common Issues

**Issue**: Header not recognized by Cascade
**Solution**: Ensure header starts with `---` and ends with `---`

**Issue**: Atom resolution failing
**Solution**: Verify atom names exist in `/config/global_atoms.yaml`

**Issue**: Governance validation failing
**Solution**: Check all governance flags are properly set

### Debug Mode
Enable validation output:
```php
$validation = CanonicalHeaderGenerator::validateHeader($header);
print_r($validation);
```

## Version History

- **4.2.3** - Current version with full Cascade compatibility
- **4.2.0** - Added governance compliance fields
- **4.1.0** - Initial canonical template structure

## References

- [WOLFIE Headers Specification](/wolfie_headers.yaml)
- [Global Atoms Configuration](/config/global_atoms.yaml)
- [Governance Documentation](/docs/GOVERNANCE.md)
- [Cascade Integration Guide](/docs/CASCADE_INTEGRATION.md)

---

🧩 This template is **Cascade-ready** and maintains file-sovereignty while ensuring full compatibility with the Lupopedia 4.2.3 ecosystem.
