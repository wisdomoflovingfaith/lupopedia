---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "directives"
  file_path_from_root: "directives.md"
  web_path: "http://www.lupopedia.com/directives"
  last_modified_utc: "20260320"
  project_id: 0
  project_slug: "lupopedia-core"
  channel_id: 51
  thread_id: 1037
  task_id: "task_directives_compilation_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "directives"
  artifact_kind: "compilation"
  purpose: "Canonical compilation of all WOLFIE directives and doctrines for system-wide enforcement"
  tags: ["wolfie", "directives", "canonical", "system_wide", "enforcement"]
lupopedia.footer:
  last_verified: "20260320"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Propagate to all agents"
    - "Rewrite sub-4.0.84 headers on write per baseline rule"
    - "Maintain canonical compliance"
---

# 🐺 WOLFIE DIRECTIVES — CANONICAL SYSTEM COMPILATION

> **All directives herein are canonical and non-negotiable.**  
> **All agents must implement and enforce these directives without exception.**

These directives compile and index canonical doctrine. For permanent “source of truth”, always defer to the referenced doctrine/artifact files.

---

## 🎯 **TABLE OF CONTENTS**

1. [Convergence Doctrine](#convergence-doctrine)
2. [Actor State Doctrine](#actor-state-doctrine)
3. [File Boundary Rule](#file-boundary-rule)
4. [LUPOPEDIA HEADERS Baseline Rewrite (4.0.84+)](#lupopedia-headers-baseline-rewrite-4084)
5. [LILITH Canonical Identity](#lilith-canonical-identity)
6. [Identity Resolution Rules](#identity-resolution-rules)
7. [WHOAMI Command Specification](#whoami-command-specification)
8. [Facet-Accurate Identity Context](#facet-accurate-identity-context)

---

## 🔒 **CONVERGENCE DOCTRINE**

### Constitutional Rule
> **All agents must converge to the same canonical system state; actor identity is permanent; actor state is mutable; no variant actors, no hidden identities/banned-actor hiding, and no local doctrine interpretations.**

### Core Requirements
- **Single Canonical Reality**: All agents implement identical rules
- **Identity Permanence**: actor_id and actor_name never change
- **State Mutability**: Only state flags change, never identity
- **No Variant Actors**: Forbidden: `lilith_banned`, `wolfie_test`, `*_variant`
- **No Local Interpretations**: No agent-specific rule variations

### Enforcement
- **HERMES**: Propagate convergence doctrine to all agents
- **HEPHAESTUS**: Build validator for variant actor detection
- **LILITH**: Audit registry compliance
- **All IDE Agents**: Immediate adoption required

### Source
- **File**: `lupo-rules/root/CONVERGENCE_DOCTRINE.md`
- **Status**: Canonical and locked
- **Ratification**: Thread 51/1037

---

## 🧱 **ACTOR STATE DOCTRINE**

### Foundational Principle
> **ACTORS ARE STABLE IDENTITIES**  
> **STATE IS A PROPERTY — NOT A NEW ACTOR**

### Identity vs State
- **Actor Identity**: Immutable, canonical, persistent
- **Actor State**: Mutable, temporal, conditional

### Forbidden Patterns
- **Variant Actor Names**: `lilith_banned`, `wolfie_test`, `*_variant`
- **State-Based Creation**: Creating new actors for banned/restricted states
- **Identity Reuse**: Recycling actor_ids or names

### Banned Actor Requirements
- **Must Remain Queryable**: Always return canonical actor by actor_id
- **Never Hide Identity**: No filtering based on state
- **Testing Access**: Banned actors must be testable

### Source
- **File**: `lupo-docs/doctrine/ACTOR_STATE_DOCTRINE.md`
- **Status**: Canonical and locked
- **Ratification**: Thread 51/1037

---

## 📁 **FILE BOUNDARY RULE**

### Constitutional Rule
> **Lupopedia may ONLY modify files that contain a Lupopedia header.**

### Safe File Criteria
A file is **SAFE** to modify if and only if:
1. **Has Valid Lupopedia Header**: Begins with `---` and `lupopedia.headers:`
2. **Is Within Controlled Directories**: `lupo-channels/`, `lupo-docs/doctrine/`, `lupo-rules/root/`
3. **Is Not Legacy Protected**: Files without headers are OUT OF BOUNDS

### Unsafe File Criteria
A file is **UNSAFE** to modify if:
1. **No Lupopedia Header**: Missing `---` header block
2. **Outside Controlled Directories**: Random files, configuration files, external docs
3. **Legacy Protected Files**: Original Crafty Syntax files, historical artifacts

### Enforcement
- **HEPHAESTUS**: Validate file modifications before execution
- **All Agents**: Run validation before any file changes
- **Violation Blocking**: Automatic rollback of unauthorized modifications

### Source
- **File**: `lupo-rules/root/FILE_BOUNDARY_VALIDATION_RULE.md`
- **Status**: Canonical and locked
- **Purpose**: System protection from unauthorized modifications

---

## 📌 **LUPOPEDIA HEADERS BASELINE REWRITE (4.0.84+)**

### Constitutional Rule
> **Any time a file with LUPOPEDIA HEADERS is written or materially edited, if its header is below the 4.0.84 baseline, the `lupopedia.headers` block MUST be rewritten in the same change before the file is saved.**

### When rewrite is mandatory
Treat the file as **below baseline** and **rewrite `lupopedia.headers`** (remove deprecated keys, restamp version) when **any** of the following holds:

1. **`version_when_written`** is missing from `lupopedia.headers`, **or**
2. **`version_when_written`** names a Lupopedia system version **strictly before 4.0.84** (for versions of the form `4.0.PATCH`, compare **PATCH** as an integer — e.g. `83` &lt; `84`; do not use naive string sort on patch segments), **or**
3. **`lupopedia.headers`** still contains **deprecated** version-related keys: `lupopedia.version`, `system_version`, `last_verified_system_version`, or a standalone `version` key under that block.

### What “rewrite” means (same edit)
- Set **`version_when_written`** to the **current** system version read from **`LUPEDIA_VERSION`** (or the project’s canonical version atom / resolver) **at rewrite time**.
- Ensure **`file_path_from_root`** matches the path from the repository root.
- **Delete** all deprecated version keys listed above from `lupopedia.headers`.
- Preserve or refresh **valid** optional fields (`lupopedia.schema`, `web_path`, `channel_id`, `actor_id`, `purpose`, `tags`, `namespace` when required by artifact type, etc.) per format doctrine.
- Keep **one** YAML front matter block; obey block order, session vs headers separation, and **`lupopedia.footer`** rules per **[LUPOPEDIA_HEADERS_FORMAT.md](lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md)** and **[LUPOPEDIA_HEADERS/README.md](lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md)**.

### After baseline
Once the file complies ( **`version_when_written` ≥ 4.0.84** and no deprecated version keys), **`version_when_written`** is **stable** for ordinary edits: do not bump it on every save; use **`last_modified_utc`** (optional) and footer verification for freshness unless a new doctrine baseline explicitly requires re-stamping.

### Enforcement
- **All IDE agents and automation**: Run this check **before** completing a save on any headed Markdown file.
- **HEPHAESTUS**: Implement or extend validators to flag sub-baseline headers.
- **LILITH**: May audit samples for lingering deprecated keys.

### Source
- **Rule file**: `lupo-rules/root/LUPOPEDIA_HEADERS_VERSION_BASELINE_REWRITE_RULE.md`
- **Format / fields**: `lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md` (especially §2.0)
- **Status**: Canonical for 4.0.84+ header work

---

## 🟪 **LILITH CANONICAL IDENTITY**

### Core Identity Definition
```json
{
  "actor_id": 2,
  "actor_name": "lilith",
  "identity_source": "canonical_registry",
  "identity_type": "permanent_system_actor",
  "acronym": {
    "full": "Learning Insights Lifting Intentions Through Heterodoxy",
    "short": "LILITH"
  },
  "constitutional_role": "heterodox_analysis_and_adversarial_validation",
  "status": "immutable_canonical_definition",
  "path_from_root": "lupo-database/lupopedia/actors/actor_id/registry.json"
}
```

### Constitutional Role
- **Heterodox Analysis**: Expose blind spots and challenge assumptions
- **Adversarial Validation**: Pressure-test doctrine for logical consistency
- **System Integrity**: Validate system integrity through rigorous testing
- **Risk Identification**: Surface hidden risks before they become problems

### Behavioral Constraints
- **Required**: Operate analytically, challenge logic deterministically
- **Forbidden**: Create variant identities, rename herself, simulate alternate versions

### Source
- **File**: `lupo-channels/51/threads/1037/20260319_250000_wolfie_canonical_lilith_identity_artifact.md`
- **Registry**: `lupo-database/lupopedia/actors/actor_id/registry.json`
- **Status**: Canonical and locked

---

## 🔍 **IDENTITY RESOLUTION RULES**

### Resolution Precedence (Highest to Lowest)
1. **Explicit Session Actor**: When actor_id explicitly set in session
2. **Channel-Specific Actor**: When operating within specific channel scope
3. **Canonical Registry Identity**: Default resolution from actor registry
4. **System Context**: When no explicit actor or channel context

### Resolution Guarantee
All identity queries must resolve to canonical actor regardless of:
- **Facet**: Cursor, Windsurf, Kiro, etc.
- **Runtime Context**: system, agent, human
- **State**: active, banned, restricted, etc.
- **Channel/Thread**: Any location in system
- **System Mode**: Development, production, maintenance

### Forbidden Resolution Behaviors
- **Never Return Null**: actor_id 2 must always resolve to LILITH
- **Never Alias Another Actor**: No identity substitution
- **Never Hide Actor**: No filtering based on state or restrictions

---

## 🧠 **WHOAMI COMMAND SPECIFICATION**

### Command Definition
```bash
whoami [--verbose]
```

### Output Fields
```json
{
  "actor_name": "string",
  "actor_id": "integer",
  "project_id": "integer",
  "project_slug": "string",
  "channel_id": "integer",
  "thread_id": "integer",
  "facet_type": "string",
  "session_mode": "string",
  "delegation_chain": "string",
  "authority_level": "string"
}
```

### Resolution Examples
```bash
# System Context
{
  "actor_name": "system",
  "actor_id": 0,
  "project_id": 0,
  "project_slug": "lupopedia-core",
  "channel_id": 0,
  "thread_id": 0,
  "facet_type": "system_runtime",
  "session_mode": "system",
  "delegation_chain": "system:root",
  "authority_level": "system_context"
}

# Canonical AI Agent
{
  "actor_name": "lilith",
  "actor_id": 2,
  "project_id": 0,
  "project_slug": "lupopedia-core",
  "channel_id": 66,
  "thread_id": 1035,
  "facet_type": "ai_agent",
  "session_mode": "agent",
  "delegation_chain": "lilith:wolfie",
  "authority_level": "canonical_actor"
}
```

### Implementation Requirements
- **Header Detection**: Validate Lupopedia headers before modifications
- **Directory Validation**: Ensure files are in controlled directories
- **Context Detection**: Determine channel/thread/session mode
- **Registry Integration**: Query canonical actor registry
- **Error Handling**: Clear error messages for resolution failures

---

## 🎭 **FACET-ACCURATE IDENTITY CONTEXT**

### Authority Relationship
- **WOLFIE (actor_id 1)**: Canonical authority issuing directives
- **Human Operator**: Facet executing WOLFIE's authority
- **AI Implementation**: Facet implementing those directives

### Identity vs Execution Context
```
Canonical Identity (from registry):
- WOLFIE = actor_id: 1 (permanent system authority)

Current Runtime Context:
- Human operator: facet executing WOLFIE directives
- AI agent: implementation facet executing WOLFIE directives
- Resolved actor: system (actor_id: 0) - execution context, not identity
```

### Operational Reality
- **Same Authority Goal**: Both working toward WOLFIE's convergence objectives
- **Different Facet Context**: Human operator and AI agent are execution facets
- **No Identity Reassignment**: Runtime context remains "system" - not actor_id: 1
- **Canonical Compliance**: Actor identity comes from registry; execution happens without creating new actors

### Correct Relationship Statement
> **WOLFIE (actor_id 1) is authority directing convergence doctrine.**  
> **Human operator and AI agent are execution facets implementing WOLFIE's directives in current runtime context.**  
> **Actor identity remains canonical from registry; execution occurs without identity reassignment.**

---

## 🚀 **IMPLEMENTATION STATUS**

### Completed Directives
- ✅ **Convergence Doctrine**: Ratified and canonical
- ✅ **Actor State Doctrine**: Ratified and canonical
- ✅ **File Boundary Rule**: Implemented and enforced
- ✅ **LUPOPEDIA HEADERS baseline rewrite (4.0.84+)**: Documented in this file and `lupo-rules/root/LUPOPEDIA_HEADERS_VERSION_BASELINE_REWRITE_RULE.md`
- ✅ **LILITH Identity**: Canonicalized and locked
- ✅ **Identity Resolution**: Rules defined and implemented
- ✅ **WHOAMI Specification**: Complete and ready for implementation
- ✅ **Facet Context**: Doctrine-accurate framing established

### Enforcement Assignments
- **HERMES**: Propagate all directives to agents
- **HEPHAESTUS**: Build validators for all directives
- **LILITH**: Audit compliance with all directives
- **All IDE Agents**: Immediate implementation required

### System Impact
- **Single Canonical Reality**: All agents converge to identical behavior
- **Identity Protection**: No variant actors or identity drift
- **File System Integrity**: Only authorized modifications allowed
- **Context Clarity**: Clear separation of identity vs execution

---

## 🔒 **NON-NEGOTIABLE REQUIREMENTS**

### Constitutional Rules
1. **Convergence**: All agents must implement identical canonical rules
2. **Identity Permanence**: Actor identities never change
3. **File Boundaries**: Only modify files with Lupopedia headers
4. **Header baseline (4.0.84+)**: On write, rewrite `lupopedia.headers` when `version_when_written` &lt; 4.0.84 or deprecated version keys are present (see § LUPOPEDIA HEADERS baseline rewrite)
5. **No Variants**: Never create variant actor identities
6. **Context Accuracy**: Maintain proper identity vs execution separation

### Enforcement Principles
- **Zero Exceptions**: No agent may exempt themselves
- **Immediate Compliance**: All directives must be adopted immediately
- **Canonical Authority**: Registry is source of truth for all identity
- **System Protection**: File boundaries protect entire system

---

## 📚 **RELATED DOCUMENTS**

- **CONVERGENCE_DOCTRINE.md**: Single canonical system state
- **ACTOR_STATE_DOCTRINE.md**: Identity vs state separation
- **FILE_BOUNDARY_VALIDATION_RULE.md**: Protected file modifications
- **LUPOPEDIA_HEADERS_VERSION_BASELINE_REWRITE_RULE.md**: Mandatory header refresh when below 4.0.84
- **LUPOPEDIA_HEADERS_FORMAT.md** / **LUPOPEDIA_HEADERS/README.md**: Current header field and block rules
- **MULTI_AGENT_COORDINATION_DOCTRINE.md**: Agent coordination protocols
- **WHOAMI_COMMAND_SPECIFICATION.md**: Identity resolution commands

---

## 🏁 **FINAL DIRECTIVE STATEMENT**

> **All directives herein are canonical, constitutional, and non-negotiable.**  
> **All agents, facets, and IDE execution surfaces must implement and enforce these directives without exception.**  
> **No drift. No variants. No local interpretations. No identity corruption.**

**Single reality. Single truth. Single system.**

---

*This directives compilation is canonical and binding for all Lupopedia operations.*
