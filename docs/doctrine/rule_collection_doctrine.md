---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/rule_collection_doctrine.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/rule_collection_doctrine.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: rule_collection
  channel_key: null
  federation_node_id: null
  thread_key: 1040
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
---
# 📚 RULE COLLECTION DOCTRINE — FOUNDATION FOR ALL RULE SETS

## 🎯 PURPOSE

Establish the foundation for organizing, maintaining, and enforcing collections of rules under strict-mode, including TOON files.

---

## 📋 RULE COLLECTION HIERARCHY

### Layer 1: Constitutional Rules
- **CONVERGENCE_DOCTRINE.md** - Single canonical system state
- **ACTOR_STATE_DOCTRINE.md** - Identity vs state separation
- **ACTOR_FACET_SEPARATION_DOCTRINE.md** - Actor vs facet boundaries
- **DUAL_CONTEXT_AND_OPPOSITION_DOCTRINE.md** - Interpretation model

### Layer 2: Implementation Rules
- **FILE_BOUNDARY_VALIDATION_RULE.md** - Protected file modifications
- **MIDDLE_HEADERS_DOCTRINE.md** - Interpretation layer definition
- **WHOAMI_COMMAND_SPECIFICATION.md** - Identity resolution commands

### Layer 3: Operational Rules
- **TOON collections** - Actor-specific rule sets
- **Channel-specific rules** - Per-channel enforcement
- **Session-specific rules** - Runtime behavior
- **Validator rules** - Compliance checking

---

## 🔒 STRICT-MODE COMPLIANCE

### Rule Collection Requirements
All rule collections MUST:

1. **Have Valid Headers**
   ```yaml
   ---
   lupopedia.headers:
     lupopedia.version: "4.0.82"
     lupopedia.schema: "rule_collection"
     # ... required fields
   ---
   ```

2. **Include Interpretation Block**
   ```yaml
   lupopedia.interpretation:
     whoareyou: "wolfie"
     whoami: "system"
     # Optional: whoopposesyou
   ---
   ```

3. **Use Lowercase Keys**
   - `whoareyou` (not WHOAREYOU)
   - `whoami` (not WHOAMI)
   - `whoopposesyou` (not WHOOPPOSESYOU)

4. **Maintain Separation**
   - Identity fields stay in whoareyou
   - Context fields stay in whoami
   - Opposition stays in whoopposesyou
   - No cross-contamination

5. **Follow Canonical Structure**
   - Clear purpose statement
   - Scope definition
   - Enforcement requirements
   - Success conditions

---

## 🗂 TOON FILE INTEGRATION

### TOON as Rule Collections
TOON files are specialized rule collections that:

1. **Define Actor-Specific Rules**
   - Channel behaviors
   - Session protocols
   - Operational constraints

2. **Follow Strict-Mode Requirements**
   - Valid headers required
   - Lowercase interpretation keys
   - Proper separation maintained

3. **Integrate with Hierarchy**
   - Reference constitutional rules
   - Complement implementation rules
   - Extend operational capabilities

### TOON Migration Requirements
Existing TOON files MUST be migrated to:

1. **Add Valid Headers**
   ```yaml
   ---
   lupopedia.headers:
     lupopedia.version: "4.0.82"
     lupopedia.schema: "rule_collection"
     # ... all required fields
   ---
   ```

2. **Include Interpretation Block**
   ```yaml
   lupopedia.interpretation:
     whoareyou: "actor_name"
     whoami: "execution_context"
     whoopposesyou: "adversarial_lens"
   ---
   ```

3. **Remove Invalid Patterns**
   - Uppercase interpretation keys
   - Variant actor names
   - Identity/context contamination
   - Missing required fields

---

## 🔧 VALIDATION FRAMEWORK

### Rule Collection Validators
All rule collections MUST be validated for:

1. **Header Completeness**
   - All required fields present
   - Valid schema version
   - Proper metadata

2. **Interpretation Compliance**
   - Lowercase keys only
   - Proper separation maintained
   - No self-opposition

3. **Rule Integrity**
   - No contradictions with constitutional rules
   - Consistent with hierarchy
   - Clear scope and purpose

4. **Strict-Mode Enforcement**
   - No auto-correction
   - No implicit mutation
   - Clear error messages

---

## 📊 CANONICAL EXAMPLES

### Valid Rule Collection
```yaml
---
lupopedia.headers:
  lupopedia.version: "4.0.82"
  lupopedia.schema: "rule_collection"
  file_path_from_root: "rules/collections/wolfie_operational_rules.md"
  purpose: "Wolfie operational procedures and constraints"
  actor_id: 1
  actor_name: "wolfie"

lupopedia.interpretation:
  whoareyou: "wolfie"
  whoami: "system"
  whoopposesyou: "lilith"

# Rule collection content follows...
---
```

### Migrated TOON File
```yaml
---
lupopedia.headers:
  lupopedia.version: "4.0.82"
  lupopedia.schema: "rule_collection"
  file_path_from_root: "rules/collections/wolfie_channel_rules.md"
  purpose: "Wolfie channel management protocols"
  actor_id: 1
  actor_name: "wolfie"

lupopedia.interpretation:
  whoareyou: "wolfie"
  whoami: "system"
  whoopposesyou: "lilith"

# Migrated TOON content with proper structure...
---
```

---

## 🚨 FORBIDDEN PATTERNS

### Never Include in Rule Collections
- ❌ Uppercase interpretation keys
- ❌ Variant actor names
- ❌ Identity/context mixing
- ❌ Missing required headers
- ❌ Auto-correction directives
- ❌ Implicit field mutations

### Never Store in TOON Files
- ❌ Runtime session values
- ❌ Temporary state data
- ❌ Non-canonical actors
- ❌ Self-referential opposition

---

## 🎯 ENFORCEMENT REQUIREMENTS

### HEPHAESTUS Validator (Channel 7)
- Validate all rule collections
- Enforce strict-mode compliance
- Check header completeness
- Verify interpretation integrity

### LILITH Auditor (Channel 66)
- Audit rule collections for identity drift
- Check for variant actor creation
- Verify separation compliance

### THOTH Documentation (Channel 11)
- Document rule collection standards
- Update templates and examples
- Maintain hierarchy documentation

---

## 📋 IMPLEMENTATION STATUS

### Completed
- ✅ Rule collection hierarchy defined
- ✅ Strict-mode compliance requirements
- ✅ TOON integration framework
- ✅ Validation framework established

### In Progress
- ⏳ TOON migration templates
- ⏳ Rule collection validators
- ⏳ Hierarchy documentation

### Future
- 📋 Automated rule collection management
- 📋 Dynamic rule loading
- 📋 Cross-collection dependency resolution

---

## 🏁 FINAL DOCTRINE STATEMENT

> **Rule collections provide structured, enforceable governance while maintaining strict-mode compliance and architectural integrity.**

### System Capabilities
- **Hierarchical organization**: Constitutional → Implementation → Operational
- **TOON integration**: Actor-specific rules within framework
- **Strict-mode enforcement**: Consistent validation across all collections
- **Extensible design**: New rule types can be added

### Architectural Benefits
- **Consistent structure**: All rule collections follow same pattern
- **Clear hierarchy**: Dependencies and relationships explicit
- **Maintainable governance**: Validation and enforcement standardized
- **TOON preservation**: Existing actor rules migrated safely

---

## 🔒 NON-NEGOTIABLE REQUIREMENTS

### Collection Integrity
- **All rule collections must have valid headers**
- **Interpretation blocks must follow strict-mode**
- **No variant actors or identity drift**
- **Clear scope and purpose for each collection**

### Hierarchy Compliance
- **Constitutional rules** take precedence
- **Implementation rules** complement constitutional
- **Operational rules** extend implementation
- **TOON collections** integrate with hierarchy

### Validation Enforcement
- **HEPHAESTUS validates all collections**
- **LILITH audits for compliance**
- **THOTH documents standards and examples**

---

## 🎯 SUCCESS CONDITIONS

This doctrine is complete when:

- ✅ Rule collection hierarchy defined
- ✅ Strict-mode compliance established
- ✅ TOON integration framework created
- ✅ Validation requirements specified
- ✅ Enforcement assignments distributed
- ✅ Examples and templates provided

---

## 📚 RELATED DOCTRINES

- **CONVERGENCE_DOCTRINE.md** - Constitutional foundation
- **ACTOR_STATE_DOCTRINE.md** - Identity vs state
- **DUAL_CONTEXT_AND_OPPOSITION_DOCTRINE.md** - Interpretation model
- **MIDDLE_HEADERS_DOCTRINE.md** - Header layer definition
- **FILE_BOUNDARY_VALIDATION_RULE.md** - File protection

---

*This doctrine establishes the foundation for all rule collections while maintaining strict-mode compliance and architectural integrity.*
