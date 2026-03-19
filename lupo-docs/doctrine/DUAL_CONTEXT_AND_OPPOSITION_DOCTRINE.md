---
lupopedia.headers:
  lupopedia.version: "4.0.82"
  lupopedia.schema: "doctrine"
  system_version: "4.0.82"
  file_path_from_root: "lupo-docs/doctrine/DUAL_CONTEXT_AND_OPPOSITION_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupo-docs/doctrine/DUAL_CONTEXT_AND_OPPOSITION_DOCTRINE"
  last_modified_utc: "20260319"
  project_id: 0
  project_slug: "lupopedia-core"
  channel_id: 51
  thread_id: 1039
  task_id: "task_interpretation_opposition_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "doctrine"
  artifact_kind: "interpretation_extension"
  purpose: "Define three-part interpretation model with identity, context, and opposition fields"
  tags: ["wolfie", "doctrine", "interpretation", "whoareyou", "whoami", "whoopposesyou", "adversarial_lens"]
lupopedia.footer:
  version: "4.0.82"
  last_verified: "20260319"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Implement validator rules"
    - "Update documentation templates"
    - "Audit for identity drift"
---

# 🎭 DUAL CONTEXT AND OPPOSITION DOCTRINE

## 🎯 PURPOSE

Extend the canonical interpretation model to include adversarial/heterodox perspective while maintaining absolute actor identity integrity.

---

## 📋 THREE-PART INTERPRETATION MODEL

### Canonical Structure
```yaml
lupopedia.interpretation:
  whoareyou: "canonical_actor_name"
  whoami: "execution_context"
  whoopposesyou: "adversarial_lens_actor"
```

#### Header Key Canonicalization
All interpretation keys MUST follow:
- `whoami` → lowercase
- `whoareyou` → lowercase  
- `whoopposesyou` → lowercase

Uppercase variants (WHOAMI, WHOAREYOU, WHOOPPOSESYOU) are FORBIDDEN in stored artifacts.
Validators MUST reject mixed or uppercase forms.

### Field Definitions

#### WHOAREYOU (Identity)
- **Purpose**: Canonical semantic identity
- **Requirement**: Must resolve to registered actor
- **Immutability**: Never changes regardless of context
- **Authority**: Source of all authorization

#### WHOAMI (Context)
- **Purpose**: Execution surface / facet / runtime context
- **Flexibility**: May be IDE, faucet, human UI, REST, etc.
- **Separation**: Never replaces or overrides identity
- **Scope**: Where and how the artifact is produced

#### WHOOPPOSESYOU (Opposition)
- **Purpose**: Adversarial or heterodox counter-lens
- **Nature**: Relationship field, not identity
- **Requirement**: Must resolve to canonical actor if present
- **Function**: Critical perspective for interpretation

---

## 🔒 IDENTITY INTEGRITY CONSTRAINTS

### Non-Negotiable Rules
1. **WHOAREYOU is immutable**: Canonical identity never changes
2. **WHOAMI never replaces identity**: Execution context is separate
3. **WHOOPPOSESYOU is relationship-only**: Not identity-bearing
4. **No WHOWASI**: Temporal identity drift explicitly rejected
5. **State never creates actors**: Mutable state vs immutable identity

### Forbidden Patterns
- ❌ `WHOWASI` as identity replacement
- ❌ `WHOAMI` used as canonical actor identity
- ❌ `WHOOPPOSESYOU` replacing `WHOAREYOU`
- ❌ Opposition field using non-canonical variant actors
- ❌ State-based actor creation (`lilith_banned`, `wolfie_test`)

---

## 🎯 FIELD MEANINGS AND USAGE

### WHOAREYOU: Canonical Identity
```yaml
whoareyou: "wolfie"
# Resolves to actor_id 1, canonical orchestrator
```

**Interpretation**: The canonical actor speaking with full authority.

### WHOAMI: Execution Context
```yaml
whoami: "windsurf"
# IDE faucet executing wolfie's authority
```

**Interpretation**: The execution surface producing the artifact.

### WHOOPPOSESYOU: Adversarial Lens
```yaml
whoopposesyou: "lilith"
# Apply heterodox analysis to this artifact
```

**Interpretation**: The critical perspective that should be applied when reading.

#### Default Opposition Rule
> **If whoopposesyou is omitted, and the artifact is doctrinal, architectural, or system-critical, the default adversarial lens is `lilith`.**

This is a **relationship default**, not identity assignment.

#### Opposition Resolution Rule (Non-Persistent)
If whoopposesyou is omitted:
- The system MUST resolve it to "lilith" for doctrinal/system-critical artifacts
- This resolution MUST NOT modify the artifact
- The resolved value exists only at:
  - validation time
  - interpretation time  
  - execution time

Artifacts must remain unchanged on disk.

---

## 📊 CANONICAL EXAMPLES

### Valid Configurations

#### Standard Three-Part Model
```yaml
lupopedia.interpretation:
  whoareyou: "wolfie"
  whoami: "windsurf"
  whoopposesyou: "lilith"
```

#### Different Context, Same Identity
```yaml
lupopedia.interpretation:
  whoareyou: "thoth"
  whoami: "human_web"
  whoopposesyou: "lilith"
```

#### Default Opposition (Implicit)
```yaml
lupopedia.interpretation:
  whoareyou: "wolfie"
  whoami: "cursor"
# Implicit whoopposesyou: "lilith" for doctrinal artifacts
```

#### No Opposition Field
```yaml
lupopedia.interpretation:
  whoareyou: "wolfie"
  whoami: "cursor"
```

### Invalid Configurations

#### Identity Drift Attempt
```yaml
lupopedia.interpretation:
  whoareyou: "wolfie"
  whoami: "wolfie"
  whowasi: "lilith"
# ❌ whowasi is rejected
```

#### Variant Actor Attempt
```yaml
lupopedia.interpretation:
  whoareyou: "wolfie_test"
  whoami: "cursor"
  whoopposesyou: "lilith_banned"
# ❌ Variant actors rejected
```

#### Context as Identity
```yaml
lupopedia.interpretation:
  whoareyou: "cursor"
  whoami: "wolfie"
  whoopposesyou: "lilith"
# ❌ whoami cannot replace whoareyou
```

---

## 🎯 CANONICAL OPPOSITION DEFAULTS

### Default Adversarial Lens
For **doctrinal, architectural, or system-critical artifacts**:
- **Default WHOOPPOSESYOU**: `lilith`
- **Application**: Implicit adversarial review
- **Scope**: System-level and doctrinal artifacts

### Actors with Default Opposition
✅ **Wolfie** → Lilith (orchestrator → heterodox validator)  
✅ **Thoth** → Lilith (knowledge → adversarial analysis)  
✅ **Hephaestus** → Lilith (validator → heterodox testing)  
✅ **Athena** → Lilith (strategy → critical review)  
✅ **All canonical actors** → Lilith (unless explicitly overridden)

### Actors Without Default Opposition
❌ **Lilith** → Cannot oppose herself  
❌ **System (actor_id 0)** → Runtime context, not an actor  
❌ **Human facets** → Facets, not canonical actors  
❌ **IDE faucets** → Execution surfaces, not oppositional actors

### Override Capability
Any artifact may explicitly specify a different adversarial lens:
```yaml
lupopedia.interpretation:
  whoareyou: "wolfie"
  whoami: "windsurf"
  whoopposesyou: "janus"  # Explicit override of default
```

---

## 🔧 VALIDATION REQUIREMENTS

### HEPHAESTUS Validator Rules (Channel 7)

#### WHOAMI Isolation Constraint
WHOAMI must NEVER contain:
- actor_name
- actor_id
- identity authority fields

WHOAMI is strictly execution context.
Violation = identity/context collapse.

#### Opposition Integrity Constraint
whoopposesyou MUST NOT equal whoareyou.actor_name.
Self-opposition is invalid and must be rejected by validators.

#### Validator Severity Levels
**Blocking Errors:**
- missing whoareyou
- missing whoami
- whoareyou not matching registry
- variant actor names detected
- whoopposesyou equals whoareyou
- invalid casing of interpretation keys

**Warnings:**
- missing whoopposesyou
- missing optional whoami subfields

#### Required Fields
- ✅ `whoareyou` required on canonical artifacts
- ✅ `whoami` required on canonical artifacts
- ⚠️ `whoopposesyou` optional but must be canonical if present
- 🔧 **Default Application**: Implicit `lilith` for doctrinal artifacts if omitted

#### Identity Validation
- ✅ `whoareyou` must resolve to registered actor
- ✅ `whoopposesyou` must resolve to registered actor if present
- ❌ Reject non-canonical variant actors
- ❌ Reject whowasi field

#### Separation Validation
- ✅ `whoami` cannot be used as canonical actor identity
- ✅ `whoopposesyou` cannot replace `whoareyou`
- ✅ Context cannot override identity
- ✅ Opposition cannot become identity

#### Doctrine Compliance
- ⚠️ Warn if interpretation contradicts ACTOR_STATE_DOCTRINE
- ⚠️ Warn if interpretation contradicts ACTOR_FACET_SEPARATION_DOCTRINE
- ❌ Error if identity drift detected

#### Architectical Boundary Enforcement
- ✅ Doctrine defines interpretation contract
- ✅ Session data instantiates contract
- ✅ Validators enforce contract compliance
- ❌ Reject mixing of governance and instantiation

---

## 📚 DOCUMENTATION REQUIREMENTS

### THOTH Documentation Assignment (Channel 11)

#### Template Updates
- Update all artifact header templates
- Include three-part interpretation examples
- Show valid vs invalid configurations

#### Onboarding Updates
- Explain identity vs context vs opposition
- Provide Wolfie/Lilith example scenarios
- Document field resolution process

#### External AI Guidance
- Clarify interpretation layer for external agents
- Define proper field usage patterns
- Specify canonical resolution requirements

#### Example Documentation
```yaml
# Wolfie directing from Windsurf with Lilith's critical lens
lupopedia.interpretation:
  WHOAREYOU: "wolfie"
  WHOAMI: "windsurf"
  WHOOPPOSESYOU: "lilith"

# Thoth analyzing from web interface with adversarial review
lupopedia.interpretation:
  WHOAREYOU: "thoth"
  WHOAMI: "human_web"
  WHOOPPOSESYOU: "lilith"
```

---

## 🔍 AUDIT REQUIREMENTS

### LILITH Audit Assignment (Channel 66)

#### Identity Drift Testing
- Test for hidden identity mutations
- Verify opposition field doesn't create implicit variants
- Confirm relationship-only nature of WHOOPPOSESYOU

#### Pattern Analysis
- Identify potential misuse patterns
- Test edge cases for identity confusion
- Validate separation integrity

#### Compliance Verification
- Audit existing artifacts for compliance
- Test validator rule effectiveness
- Recommend additional safeguards if needed

---

## 🎯 INTERPRETATION PROCESS

### Resolution Order
1. **System Header Validation**: Validate lupopedia.headers
2. **Interpretation Resolution**: Resolve WHOAREYOU, WHOAMI, WHOOPPOSESYOU
3. **Identity Verification**: Confirm canonical identities
4. **Context Application**: Apply execution context
5. **Opposition Lens**: Apply adversarial perspective
6. **Content Processing**: Process with full interpretation

### Error Handling
- **Missing WHOAREYOU**: Reject as non-canonical
- **Invalid WHOOPPOSESYOU**: Reject with specific error
- **Context Identity Confusion**: Block with diagnostic
- **Variant Actor Detection**: Automatic rejection

---

## 📋 IMPLEMENTATION STATUS

### Completed
- ✅ Doctrine file created and canonical
- ✅ WHOOPPOSESYOU ratified as field name
- ✅ Three-part model defined
- ✅ Identity constraints established

### In Progress
- ⏳ HEPHAESTUS validator rules (Channel 7)
- ⏳ THOTH documentation updates (Channel 11)
- ⏳ LILITH audit procedures (Channel 66)

### Future
- 📋 Migration of existing artifacts
- 📋 Template updates across system
- 📋 Training for all agents

---

## 🔒 NON-NEGOTIABLE REQUIREMENTS

### Identity Protection
- **WHOAREYOU never changes**: Canonical identity is permanent
- **No variant actors**: WHOWASI and *_banned patterns rejected
- **State vs identity separation**: Mutable state, immutable identity

### Context Integrity
- **WHOAMI never replaces identity**: Execution context is separate
- **Facet vs actor distinction**: Facets execute, actors authorize
- **No context-based identity mutations**

### Opposition Semantics
- **WHOOPPOSESYOU is relationship**: Not identity-bearing
- **Canonical resolution required**: Must resolve to registered actor
- **No adversarial identity creation**: Opposition stays as perspective

---

## 🐺 FINAL UPGRADED INTERPRETATION BLOCK (CLEAN CANONICAL)

```yaml
lupopedia.interpretation:
  whoami:
    facet: "cursor"
    runtime_context: "system"
    channel_id: 51
    thread_id: 1037
    session_mode: "development"
    project_id: 0
    project_slug: "lupopedia-core"
  whoareyou:
    actor_id: 1
    actor_name: "wolfie"
    identity_source: "canonical_registry"
    state: "active"
    authority_level: "canonical_orchestrator"
  whoopposesyou: "lilith"
```

---

## 🧠 WHAT YOU'VE ACTUALLY BUILT (IMPORTANT PERSPECTIVE)

You now have a 3-layer deterministic identity model:

**Layer 1 — System**
- headers
- routing
- versioning

**Layer 2 — Interpretation**
- identity
- execution
- opposition

**Layer 3 — Content**
- meaning

---

## 🐺 FINAL VERDICT

After these fixes:

This becomes a stable, enforceable, multi-agent identity contract

- No drift
- No ambiguity
- No "AI interpretation variance"

---

## 🏁 FINAL DOCTRINE STATEMENT

> **The three-part interpretation model enables structured opposition while maintaining absolute actor identity integrity.**

### System Capability
- **Who speaks**: Canonical identity (whoareyou)
- **From where**: Execution context (whoami)
- **Against what lens**: Adversarial perspective (whoopposesyou)

### Architectural Impact
- **Deterministic interpretation**: Every artifact has clear context
- **Identity protection**: No drift or mutation possible
- **Structured opposition**: Critical thinking built into system
- **Semantic clarity**: Unambiguous field meanings

---

## 🐺 FINAL LINE

This is the moment Lupopedia stops being "a system"

and becomes:

**a governed semantic runtime**

---

*This doctrine extends interpretation capabilities while protecting the foundational actor identity system.*
