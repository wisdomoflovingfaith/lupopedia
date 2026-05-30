---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "docs/doctrine/METADATA_VALIDATION_SPECIFICATION.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/doctrine/METADATA_VALIDATION_SPECIFICATION.md"
  status: "active"
  when_updated: "20260420050000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/doctrine/canonical/1026/04/metadata-validation-specification.toon"
  atoms_toon: "memory/atoms/lupopedia_global_constants.atom.toon"
  transcript_jsonl: "0/doctrine/metadata-validation"
  artifact_type: prd
  artifact_kind: specification
  channel_key: "doctrine"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: null
  content_slug: "metadata-validation-specification"
  default_collection_id: null
  lupopedia.schema: prd
  title: "METADATA_VALIDATION_SPECIFICATION.md -- Prevention Rules"
  summary: "Technical specification for validating Lupopedia's three-layer metadata system and preventing cross-layer confusion."
---

# Metadata Validation Specification

## Purpose

This specification defines validation rules for Lupopedia's three-layer metadata system (headers, metadata, footers) to prevent cross-layer confusion and maintain semantic clarity.

## Validation Categories

### 1. Header Validation (Strict)
### 2. Metadata Validation (Positional)
### 3. Footer Validation (Structural)
### 4. Cross-Layer Validation (Integrity)

---

## 1. Header Validation Rules

### 1.1 Canonical Field Validation
```yaml
required_fields:
  - header_format_version
  - file_path_from_root
  - web_path
  - status
  - when_updated
  - trust_tier
  - questions_toon
  - memory_toon
  - atoms_toon
  - transcript_jsonl
  - artifact_type
  - artifact_kind
  - channel_key
  - federation_node_id
  - thread_id
  - content_id
  - content_parent_id
  - content_slug
  - default_collection_id
  - lupopedia.schema
  - title
  - summary
```

### 1.2 Forbidden Field Detection
```yaml
forbidden_fields:
  - orchestrator
  - next_action
  - next_actions
  - comments
  - notes
  - pending_edges
  - metadata
  - footer
  - footers
  - any_non_canonical_field
```

### 1.3 Field Value Validation
```yaml
validation_rules:
  header_format_version:
    pattern: "^4\\.1\\.3$"
    required: true
  when_updated:
    pattern: "^\\d{14}$"
    required: true
  trust_tier:
    enum: ["seed", "canonical", "staging", "archive"]
    required: true
  federation_node_id:
    type: integer
    min: 0
    required: true
```

### 1.4 Error Codes
- `HDR_FORBIDDEN_FIELD`: Non-canonical field in header
- `HDR_MISSING_REQUIRED`: Required field missing
- `HDR_INVALID_VALUE`: Field value violates pattern
- `HDR_WRONG_ORDER`: Fields not in canonical order
- `HDR_POLLUTION_DETECTED`: Operational fields in header

---

## 2. Metadata Validation Rules

### 2.1 Block Detection
```yaml
metadata_block_pattern: "^---\\s*\\nlupopedia\\.metadata:"
block_requirements:
  - Must start with YAML delimiter
  - Must have "lupopedia.metadata:" key
  - Can appear anywhere in file
  - Must not be in header block
```

### 2.2 Position Validation
```yaml
position_rules:
  - Must not be within header block
  - Must not be within footer block
  - Should be near content it describes
  - Preserve original positioning
```

### 2.3 Content Validation (Light)
```yaml
allowed_keys:
  - comments
  - notes
  - dialog
  - context
  - references
  - mood
  - tone
  - any_semantic_annotation

validation_level: "light"  # No strict structure
```

### 2.4 Error Codes
- `META_HEADER_CONTAMINATION`: Metadata in header block
- `META_FOOTER_CONTAMINATION`: Metadata in footer block
- `META_MALFORMED_BLOCK`: Invalid YAML structure
- `META_MISPLACED`: Metadata in wrong location

---

## 3. Footer Validation Rules

### 3.1 Block Detection
```yaml
footer_patterns:
  - "^---\\s*\\nlupopedia\\.footer:"
  - "^---\\s*\\nlupopedia\\.footers:"
```

### 3.2 Structure Validation
```yaml
single_footer_structure:
  pending_edges: array
  next_actions: array
  comments: array
  warnings: array
  generated_by: string

multiple_footer_structure:
  - type: string
    items: array
```

### 3.3 Content Validation (Flexible)
```yaml
validation_level: "flexible"
allowed_content:
  - Task lists
  - Agent notes
  - Pending references
  - Warnings and flags
  - Generation metadata
```

### 3.4 Error Codes
- `FOOTER_MALFORMED_BLOCK`: Invalid YAML structure
- `FOOTER_HEADER_CONTAMINATION`: Footer content in header
- `FOOTER_INVALID_STRUCTURE`: Unrecognizable structure

---

## 4. Cross-Layer Validation Rules

### 4.1 Separation Integrity
```yaml
separation_rules:
  - No overlapping content between layers
  - Each layer must have distinct purpose
  - No field duplication across layers
  - Clear boundaries between layers
```

### 4.2 Semantic Consistency
```yaml
consistency_checks:
  - Headers must match file identity
  - Metadata must relate to nearby content
  - Footer must contain operational content
  - No contradictory information across layers
```

### 4.3 Migration Detection
```yaml
migration_patterns:
  - orchestrator in header → should be in metadata
  - next_action in header → should be in footer
  - comments in header → should be in metadata/footer
  - metadata in header → should be separate block
```

### 4.4 Error Codes
- `CROSS_LAYER_POLLUTION`: Content in wrong layer
- `CROSS_LAYER_DUPLICATION`: Same field in multiple layers
- `CROSS_LAYER_CONTRADICTION`: Conflicting information
- `CROSS_LAYER_MIGRATION_NEEDED`: Field needs moving

---

## Implementation Specification

### 5.1 Validator Functions

#### Header Validator
```python
def validate_headers(headers_dict):
    """Strict validation of header fields"""
    errors = []
    
    # Check required fields
    for field in REQUIRED_FIELDS:
        if field not in headers_dict:
            errors.append(Error("HDR_MISSING_REQUIRED", field))
    
    # Check forbidden fields
    for field in headers_dict:
        if field in FORBIDDEN_FIELDS:
            errors.append(Error("HDR_FORBIDDEN_FIELD", field))
    
    # Validate field values
    for field, rules in FIELD_VALIDATION_RULES.items():
        if field in headers_dict:
            if not validate_value(headers_dict[field], rules):
                errors.append(Error("HDR_INVALID_VALUE", field))
    
    return errors
```

#### Metadata Validator
```python
def validate_metadata_blocks(file_content):
    """Positional validation of metadata blocks"""
    errors = []
    blocks = extract_metadata_blocks(file_content)
    
    for block in blocks:
        # Check position
        if is_in_header(block):
            errors.append(Error("META_HEADER_CONTAMINATION"))
        if is_in_footer(block):
            errors.append(Error("META_FOOTER_CONTAMINATION"))
        
        # Light structure validation
        if not is_valid_yaml(block):
            errors.append(Error("META_MALFORMED_BLOCK"))
    
    return errors
```

#### Footer Validator
```python
def validate_footer_blocks(file_content):
    """Structural validation of footer blocks"""
    errors = []
    blocks = extract_footer_blocks(file_content)
    
    for block in blocks:
        # Check contamination
        if is_in_header(block):
            errors.append(Error("FOOTER_HEADER_CONTAMINATION"))
        
        # Flexible structure validation
        if not has_valid_structure(block):
            errors.append(Error("FOOTER_INVALID_STRUCTURE"))
    
    return errors
```

#### Cross-Layer Validator
```python
def validate_cross_layer_integrity(file_content):
    """Integrity validation across all layers"""
    errors = []
    
    # Check for pollution
    if has_header_pollution(file_content):
        errors.append(Error("CROSS_LAYER_POLLUTION"))
    
    # Check for migration needs
    migration_fields = detect_migration_needs(file_content)
    for field in migration_fields:
        errors.append(Error("CROSS_LAYER_MIGRATION_NEEDED", field))
    
    return errors
```

### 5.2 Error Reporting

#### Error Format
```json
{
  "error_code": "HDR_FORBIDDEN_FIELD",
  "severity": "error",
  "layer": "header",
  "field": "orchestrator",
  "message": "Forbidden field 'orchestrator' found in header block",
  "suggestion": "Move to metadata block if contextual, or remove if not needed",
  "line": 15,
  "auto_fix": true
}
```

#### Severity Levels
- **error**: Must fix (violates doctrine)
- **warning**: Should fix (potential issue)
- **info**: Consider fixing (best practice)

### 5.3 Auto-Fix Capabilities

#### Header Pollution Fix
```python
def fix_header_pollution(file_content):
    """Move operational fields from header to appropriate layer"""
    fixes = []
    
    # Move orchestrator to metadata
    if "orchestrator" in headers:
        metadata_block = create_metadata_block({"orchestrator": headers["orchestrator"]})
        fixes.append(("move_to_metadata", "orchestrator"))
    
    # Move next_action to footer
    if "next_action" in headers:
        footer_block = create_footer_block({"next_actions": headers["next_action"]})
        fixes.append(("move_to_footer", "next_action"))
    
    return apply_fixes(file_content, fixes)
```

---

## 6. Testing Requirements

### 6.1 Unit Tests
- Header field validation
- Metadata block detection
- Footer structure validation
- Cross-layer integrity checks

### 6.2 Integration Tests
- Complete file validation
- Auto-fix functionality
- Error reporting accuracy
- Performance with large files

### 6.3 Regression Tests
- Known pollution patterns
- Migration scenarios
- Edge cases and boundary conditions

---

## 7. Performance Considerations

### 7.1 Optimization Targets
- Header validation: < 10ms per file
- Metadata detection: < 5ms per block
- Footer validation: < 5ms per block
- Cross-layer checks: < 15ms per file

### 7.2 Caching Strategy
- Cache field validation rules
- Cache regex patterns
- Cache error message templates

---

## 8. Maintenance

### 8.1 Rule Updates
- PRD 16 field changes
- New forbidden fields
- Additional validation patterns

### 8.2 Error Code Evolution
- Add new error codes as needed
- Maintain backward compatibility
- Document deprecation timeline

---

## Summary

This specification provides a comprehensive framework for validating Lupopedia's three-layer metadata system. By implementing these rules, agents and tools can maintain proper separation between identity, contextual, and operational metadata, ensuring system integrity and preventing confusion.

---
lupopedia.footer:
  pending_edges:
    - to: docs/prd/16_lupopedia_headers.md
      reason: "file created in session and must be linked to PRD"
  notes:
    - "When DB is online, this file's edges must be imported into polymorphic edge table."
---
