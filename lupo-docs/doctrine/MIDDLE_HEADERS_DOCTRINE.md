---
lupopedia.headers:
  lupopedia.version: "4.0.82"
  lupopedia.schema: "doctrine"
  system_version: "4.0.82"
  file_path_from_root: "lupo-docs/doctrine/MIDDLE_HEADERS_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupo-docs/doctrine/MIDDLE_HEADERS_DOCTRINE"
  last_modified_utc: "20260319"
  project_id: 0
  project_slug: "lupopedia-core"
  channel_id: 51
  thread_id: 1037
  task_id: "task_middle_headers_doctrine_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "header_interpretation"
  purpose: "Define the middle interpretation header layer between system rules and content"
  tags: ["wolfie", "headers", "interpretation", "context", "identity", "three_layer"]
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/16_lupopedia_headers.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  version: "4.0.82"
  last_verified: "20260319"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Formalize header format"
    - "Update validator specs"
    - "Implement in all artifacts"
---

# 🟨 MIDDLE HEADERS DOCTRINE — INTERPRETATION LAYER DEFINITION

## 🎯 PURPOSE

Define the **middle interpretation header layer** that resolves context and identity before any content is processed.

---

## 📋 THREE LAYER MODEL

### Layer 1: System Headers (Top)
```yaml
lupopedia.headers:
  version: "4.0.82"
  schema: "doctrine"
  actor_id: 1
  channel_id: 51
  thread_id: 1037
  artifact_type: "doctrine"
```

**Purpose**: "What system rules apply?"

### Layer 2: Interpretation Headers (Middle) ← **THIS DOCTRINE**
```yaml
lupopedia.interpretation:
  whoami:
    facet: "cursor"
    runtime_context: "system"
    channel_id: 51
    thread_id: 1037
    session_mode: "system"
  whoareyou:
    actor_id: 1
    actor_name: "wolfie"
    identity_source: "canonical_registry"
    state: "active"
```

**Purpose**: "From what context is this being executed?" and "Which canonical actor is speaking?"

### Layer 3: Content (Bottom)
```markdown
# 🐺 WOLFIE DIRECTIVE

All agents must converge to the same canonical system state...
```

**Purpose**: "What is being said?"

---

## 🔍 INTERPRETATION HEADER COMPONENTS

### WHOAMI (Execution Context)
```yaml
whoami:
  facet: "cursor|windsurf|kiro|system|human"
  runtime_context: "system|agent|human"
  channel_id: integer
  thread_id: integer
  session_mode: "system|development|production"
  project_id: integer
  project_slug: "string"
```

**Answers**: "From what context is this being executed?"

### WHOAREYOU (Canonical Identity)
```yaml
whoareyou:
  actor_id: integer
  actor_name: "string"
  identity_source: "canonical_registry"
  state: "active|banned|restricted|paused"
  authority_level: "canonical_orchestrator|standard_agent|ide_faucet"
```

**Answers**: "Which canonical actor is speaking?"

---

## 🧪 RESOLUTION PROCESS

### Step 1: System Header Validation
- Validate `lupopedia.headers` format
- Check schema version compatibility
- Verify channel/thread permissions

### Step 2: Interpretation Resolution
- Resolve `whoami` execution context
- Resolve `whoareyou` canonical identity
- Apply context-specific rules

### Step 3: Content Processing
- Process content with resolved context
- Apply identity-based permissions
- Execute with full context awareness

---

## 🔧 IMPLEMENTATION REQUIREMENTS

### Header Format Standard
```yaml
---
lupopedia.headers:
  # System layer (existing)

lupopedia.interpretation:
  whoami:
    # Execution context
  whoareyou:
    # Canonical identity

# Content layer
---
```

### Validation Rules
1. **System Headers**: Must be valid and complete
2. **Interpretation Headers**: Must resolve to canonical values
3. **Content**: Must be processed with resolved context

### Error Handling
- **Missing Interpretation**: Default to system context
- **Invalid Identity**: Reject with explicit error
- **Context Mismatch**: Block execution with diagnostic

---

## 🎯 USE CASES

### IDE Agent Execution
```yaml
lupopedia.interpretation:
  whoami:
    facet: "cursor"
    runtime_context: "system"
    channel_id: 51
    thread_id: 1037
    session_mode: "development"
  whoareyou:
    actor_id: 1
    actor_name: "wolfie"
    identity_source: "canonical_registry"
    state: "active"
```

### Human Operator Execution
```yaml
lupopedia.interpretation:
  whoami:
    facet: "human"
    runtime_context: "human"
    channel_id: 51
    thread_id: 1037
    session_mode: "system"
  whoareyou:
    actor_id: 1
    actor_name: "wolfie"
    identity_source: "canonical_registry"
    state: "active"
```

### System Context Execution
```yaml
lupopedia.interpretation:
  whoami:
    facet: "system"
    runtime_context: "system"
    channel_id: 0
    thread_id: 0
    session_mode: "system"
  whoareyou:
    actor_id: 0
    actor_name: "system"
    identity_source: "canonical_registry"
    state: "active"
```

---

## 🚨 ENFORCEMENT REQUIREMENTS

### HEPHAESTUS Validator
- Validate interpretation header format
- Resolve identity conflicts
- Detect context mismatches
- Block invalid interpretation attempts

### All Agents
- Include interpretation headers in all artifacts
- Resolve context before content processing
- Maintain identity vs context separation
- Log all resolution attempts

### IDE Facets
- Generate correct interpretation headers
- Maintain facet vs actor separation
- Preserve canonical identity resolution
- Provide accurate execution context

---

## 📚 RELATED DOCTRINES

- **ACTOR_STATE_DOCTRINE.md**: Identity vs state separation
- **CONVERGENCE_DOCTRINE.md**: Single canonical system state
- **FILE_BOUNDARY_VALIDATION_RULE.md**: Protected file modifications
- **MULTI_AGENT_COORDINATION_DOCTRINE.md**: Agent coordination protocols

---

## 🏁 FINAL DEFINITION

> **Middle class headers are the interpretation layer that resolves execution context (WHOAMI) and canonical identity (WHOAREYOU) before any content is processed.**

### Three Layer Flow
```
SYSTEM (rules) → INTERPRETATION (context + identity) → CONTENT (message)
```

### Deterministic Processing
Every message becomes **deterministic** when processed through:
1. System rule validation
2. Context and identity resolution
3. Content execution with full awareness

---

## 🔒 NON-NEGOTIABLE REQUIREMENTS

### Header Completeness
- **System Headers**: Always required
- **Interpretation Headers**: Required for all new artifacts
- **Content**: Always processed with resolved context

### Resolution Guarantees
- **Identity Resolution**: Must resolve to canonical actor
- **Context Resolution**: Must provide accurate execution context
- **No Ambiguity**: Every message must have deterministic interpretation

### Separation Maintenance
- **Identity vs Context**: Never conflate actor identity with execution context
- **Facet vs Actor**: Facets execute, actors authorize
- **System vs Human**: Clear distinction between system and human contexts

---

*This doctrine establishes the missing interpretation layer that makes all Lupopedia messages deterministic and context-aware.*
