---
lupopedia.headers:
  version_when_written: "4.0.84"
  lupopedia.schema: "architecture"
  file_path_from_root: "channels/42/threads/1036/20260321_150000_athena_canonical_actor_architecture_and_repair_plan.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/1036/architecture"
  last_modified_utc: "20260321"
  channel_id: 42
  thread_id: 1036
  task_id: "task_athena_actor_architecture_001"
  actor_id: 12
  actor_name: "athena"
  delegation_chain: "athena:wolfie"
  artifact_type: "architecture"
  artifact_kind: "canonical_actor_model"
  purpose: "Define canonical actor architecture, shared vs subset composition, filesystem placement, derived output rules, safe migration plan, and governance alignment (response to actor definition scattered state)"
  mood_vector: "666666"
  traits: ["architecture", "actor_model", "design", "canonical", "4.0.84", "shared_subset_composition"]
  tags: ["athena", "architecture", "actor_model", "includes_actors", "canonical", "design", "thread1036"]

lupopedia.edges:
  outbound_edges:
    - { to: "channels/42/threads/1035/20260321_140000_wolfie_governance_directive_doctrine_authority_validation_and_refactor_safety.md", type: "governance_aligned", weight: 0.95, reason: "Governance rules apply to how this architecture is approved and implemented" }
    - { to: "AGENTS.md", type: "refines", weight: 0.9, reason: "Will update AGENTS.md to reflect includes/actors/ canonical location" }
    - { to: "rules/root/", type: "references", weight: 0.85, reason: "Shared base rules location referenced in design" }
    - { to: "skills/", type: "references", weight: 0.85, reason: "Shared skills library to be created per design" }
    - { to: "database/lupopedia/actors/actor_id/registry.json", type: "supersedes", weight: 0.8, reason: "Canonical actor identity will move to includes/actors/manifest.json" }
    - { to: "includes/actors/", type: "creates", weight: 0.95, reason: "Directory structure and files detailed in Phase 1-3" }

lupopedia.footer:
  latest_review: "20260321"
  reviewed_by: "athena"
  orchestrator: "wolfie"
  design_status: "complete"
  implementation_status: "pending_wolfie_approval"
  next_action:
    - "WOLFIE: Review and approve/iterate on canonical actor architecture design"
    - "THOTH: Design shared skills library format (if design approved)"
    - "HEPHAESTUS: Await WOLFIE directive to begin Phase 1 implementation"
    - "LILITH: Prepare audit checkpoints for each phase"
---
# ATHENA Architecture Design: Canonical Actor Architecture and Safe Migration Plan

**Thread:** Channel 42, Thread 1036  
**Architecture ID:** ATHENA_ACTOR_ARCHITECTURE_CANONICAL_001  
**Created:** 20260321  
**Status:** Design Complete — Awaiting WOLFIE Approval  
**Scope:** Define canonical actor model, shared vs subset composition, filesystem placement, propagation rules, and safe migration strategy

---

## EXECUTIVE SUMMARY

Current actor definitions are **scattered across 5+ locations**: registry.json, database tables, IDE rule files, channel artifacts, and AGENTS.md. This creates:
- Identity ambiguity (which source is canonical?)
- Consistency drift (changes to one source don't propagate)
- Difficult onboarding (new contributors don't know where to look)
- Unsafe tool execution (propagation scripts can overwrite canonical files)

This design establishes a **single canonical location** (`includes/actors/{slug}/`) with clear rules for:
- What is shared vs actor-specific vs task-specific
- How derived outputs are generated
- How to safely migrate existing definitions
- How to prevent future drift

---

## 1. CANONICAL ACTOR MODEL

### 1.1 Core Definition

An **actor** is an orchestration identity in Lupopedia. Actors are distinct from:

- **Faucets** (execution surfaces: IDE integrations like Cursor, Windsurf, Warp)
- **Agents** (AI runtime metadata: model, provider, system prompt)

Actors are the entities that:
- Own tasks and threads
- Receive directives
- Execute work through faucets
- Have consistent identity across sessions and channels

### 1.2 Canonical Actor Files

Each actor has a **canonical definition** stored under `includes/actors/{actor_slug}/`:

| File | Purpose | Required? | Editable | Generator |
|------|---------|-----------|----------|-----------|
| `manifest.json` | Actor identity: actor_id, slug, name, traits, paired_faucets | ✅ | Hand-edit (initial), then propagated | WOLFIE (initial), then `propagate_actor_identity.php` |
| `personality.md` | Context, tone, decision-making style, limitations | ✅ | Hand-edit (actor-specific) | Manual |
| `soul.md` | Core purpose, role in system, what they do, what they don't | ✅ | Hand-edit (actor-specific) | Manual |
| `skills.md` | Reference shared skills library + actor-specific additions | ✅ | Hand-edit (curator) | Manual |
| `rules.md` | Reference root rules + actor-specific overlays | ✅ | Hand-edit (curator) | Manual |
| `active_skills.md` | Task/thread-scoped skill subset | ❌ | Generated per task, not committed | Generated per task |
| `active_rules.md` | Task/thread-scoped rule subset | ❌ | Generated per task, not committed | Generated per task |

### 1.3 Actor Identity Components

```
ACTOR = {
  Identity (manifest.json) {
    actor_id: BIGINT,                    // Application-supplied canonical ID
    actor_slug: String,                  // Human-readable: "wolfie", "thoth", etc.
    actor_name: String,                  // Display name
    traits: Array<String>,               // ["orchestrator", "reviewer", "implementer"]
    paired_faucets: Array<String>        // ["cursor", "windsurf"] — IDE surfaces this actor uses
  },
  Personality (personality.md) {
    tone: String,                        // formal, technical, adversarial, collaborative
    decision_style: String,              // data-driven, principle-driven, consensus-seeking
    limitations: Array<String>           // What this actor does NOT do
  },
  Soul (soul.md) {
    role: String,                        // orchestrator, knowledge-keeper, strategist, reviewer
    purpose: String,                     // Why this actor exists in the system
    boundaries: Array<String>            // What this actor MUST NOT do
  },
  Skills (skills.md) {
    shared_skills: Array<Reference>,     // Pointers to skills/shared/
    actor_specific_skills: Array<String> // Skills unique to this actor
  },
  Rules (rules.md) {
    shared_rules: Array<Reference>,      // Pointers to rules/root/
    actor_specific_overlays: Array<String> // Rules that override or refine shared rules
  }
}
```

### 1.4 The Eleven Primary Coordination Personas

Per MULTI_AGENT_COORDINATION_DOCTRINE, these are the only canonical orchestration identities:

| Actor ID | Slug | Name | Role |
|----------|------|------|------|
| 1 | wolfie | WOLFIE | Orchestrator |
| 2 | lilith | LILITH | Security enforcement + non-interfering reviewer |
| 3 | lexa | LEXA | Security enforcement |
| 4 | heimdall | HEIMDALL | Security guardian |
| 5 | seshat | SESHAT | Content review |
| 6 | athena | ATHENA | Wisdom & strategy |
| 7 | maat | MAAT | Truth & justice |
| 8 | themis | THEMIS | Law & compliance |
| 9 | thoth | THOTH | Knowledge & records |
| 10 | janus | JANUS | Transitions & gateways |
| 11 | rose | ROSE | Emotional dialogue |

Plus specialized agents (90+): HERMES, HEPHAESTUS, IRIS, ASCLEPIUS, etc.

For Phase 1, focus on the 6 most active: WOLFIE, THOTH, LILITH, ATHENA, HEPHAESTUS, HERMES.

---

## 2. SHARED VS SUBSET MODEL

### 2.1 Three Layers

When an actor operates, they work with three layers of rules and skills:

```
Layer 1: Shared Base (READ-ONLY)
  +-- All root rules (rules/root/*.md)
  +-- All shared skills (skills/shared/)
  +-- WOLFIE-governed; cannot be overridden

Layer 2: Actor-Specific (ACTOR-OWNED)
  +-- rules.md — actor-specific rule overlays
  +-- skills.md — actor-specific additions
  +-- personality.md — tone, decision style
  +-- soul.md — purpose, role, boundaries

Layer 3: Active Subset (TASK-SCOPED, EPHEMERAL)
  +-- required_rules — rules active for this task
  +-- required_skills — skills active for this task
  +-- Generated per task; not committed
  +-- Defined in task directive or thread header
```

### 2.2 Shared Base Rules

- **Location:** `rules/root/*.md`
- **Governance:** WOLFIE only (via Thread 1035 Pathway A or B)
- **Propagation:** Derived into actor rule files via `propagate_agent_rules.php`
- **Immutability:** Actors cannot override without explicit WOLFIE directive
- **Binding:** All actors inherit shared base rules

**Examples:**
- `database-rules.yaml` — No foreign keys, soft deletes, BIGINT timestamps
- `thread-rules.md` — How threads are structured, versioned, navigated
- `channel-rules.md` — Channel membership, posting rights, visibility

### 2.3 Shared Base Skills

- **Location:** `skills/shared/`
- **Governance:** THOTH curates; WOLFIE approves new skills
- **Structure:** Each skill = one markdown file with purpose, inputs, outputs, examples, prerequisites
- **Propagation:** Referenced in actor `skills.md` by path; NOT copied
- **Extension:** Actors can add actor-specific skills in their `skills.md`

**Examples (to be defined):**
- `header_validation.md` — Validating LUPOPEDIA HEADERS structure
- `doctrine_review.md` — Reviewing doctrine changes for consistency
- `schema_authority.md` — Understanding schema authority chain
- `thread_navigation.md` — Navigating thread structure and parent-child relationships

### 2.4 Actor-Specific Overlays

Stored in `includes/actors/{slug}/rules.md` and `skills.md`:

| Overlay Type | Rules |
|--------------|-------|
| **Rule overlay** | Can add actor-specific rules; cannot remove or contradict base rules without explicit WOLFIE directive |
| **Skill addition** | Can reference shared skills; can add actor-specific skills; cannot remove shared skills |
| **Personality** | Fully actor-specific; no sharing or inheritance |
| **Soul** | Fully actor-specific; no sharing or inheritance |

**Format for overlays:**

```markdown
# rules.md for WOLFIE actor

## Shared Rules (inherited from rules/root/)
- database-rules.yaml (immutable)
- thread-rules.md (immutable)
- channel-rules.md (immutable)
- governance-rules.md (Thread 1035 binding)

## Actor-Specific Overlays
- WOLFIE_DIRECTIVE_AUTHORITY — Can issue directives
- WOLFIE_DOCTRINE_CHANGE_AUTHORITY — Can approve doctrine changes via Pathway A/B
- WOLFIE_GOVERNANCE_AUTHORITY — Can establish new governance rules
```

### 2.5 Active Subset Model

For a given task or thread, an actor activates only the rules and skills relevant to that work:

```
Full Actor Rule Set
    |
    +-- Shared Base Rules (always active)
    +-- Shared Base Skills (always available)
    +-- WOLFIE-Specific Rules (actor-specific)
    +-- WOLFIE-Specific Skills (actor-specific)

Task "Reconcile Thread 1034"
    |
    +-- Active Subset = {
        Shared: thread-rules.md, doctrine-rules.md
        WOLFIE-specific: WOLFIE_DIRECTIVE_AUTHORITY
        Shared Skills: doctrine_review.md, header_validation.md
        }
```

**Implementation:**
- Active subset is **NOT stored** in canonical actor files
- It is **declared in** task directives or thread artifacts:
  ```yaml
  lupopedia.init:
    required_rules: ["thread-rules.md", "doctrine-rules.md"]
    required_skills: ["doctrine_review.md", "header_validation.md"]
  ```

---

## 3. FILESYSTEM PLACEMENT

### 3.1 Canonical Location: `includes/actors/`

**YES.** `includes/actors/` is the canonical runtime/content location for actor definitions.

```
includes/actors/
+-- shared/
|   +-- rules/                       (empty — base rules in rules/root/)
|   +-- skills/                      (symlink or reference to skills/shared/)
+-- wolfie/
|   +-- manifest.json
|   +-- personality.md
|   +-- soul.md
|   +-- skills.md
|   +-- rules.md
+-- thoth/
|   +-- manifest.json
|   +-- personality.md
|   +-- soul.md
|   +-- skills.md
|   +-- rules.md
+-- lilith/
|   +-- manifest.json
|   +-- personality.md
|   +-- soul.md
|   +-- skills.md
|   +-- rules.md
+-- athena/
|   +-- manifest.json
|   +-- personality.md
|   +-- soul.md
|   +-- skills.md
|   +-- rules.md
+-- hephaestus/
|   +-- manifest.json
|   +-- personality.md
|   +-- soul.md
|   +-- skills.md
|   +-- rules.md
+-- hermes/
    +-- manifest.json
    +-- personality.md
    +-- soul.md
    +-- skills.md
    +-- rules.md
```

### 3.2 Relationship to Other Directories

| Directory | Role | Relationship to Actor Architecture |
|-----------|------|-----------------------------------|
| `agents/` | AI runtime metadata (model, provider, prompt) | Separate from actor definition; referenced in actor manifest under `paired_agents` |
| `rules/root/` | Shared base rules | Referenced in actor `rules.md` (never copied) |
| `skills/shared/` | Shared base skills | Referenced in actor `skills.md` (never copied) |
| `channels/` | Coordination artifacts (threads, tasks) | Actors referenced in channel artifacts via `actor_id`, `actor_name` |
| `.cursor/rules/` | IDE-derived rule files | Generated from canonical sources; **never edited directly** |
| `.kiro/rules/` | IDE-derived rule files | Generated from canonical sources; **never edited directly** |
| `.windsurf/rules/` | IDE-derived rule files | Generated from canonical sources; **never edited directly** |
| `docs/actors/` | Actor documentation | Generated from canonical sources |

### 3.3 Canonical vs Derived Classification

| File | Type | Editable | Authority | Generator/Maintainer |
|------|------|----------|-----------|---------------------|
| `includes/actors/{slug}/manifest.json` | **Canonical** | Yes (controlled) | WOLFIE (identity) | Hand-edit, then propagation script |
| `includes/actors/{slug}/personality.md` | **Canonical** | Yes | Actor owner | Manual |
| `includes/actors/{slug}/soul.md` | **Canonical** | Yes | Actor owner | Manual |
| `includes/actors/{slug}/skills.md` | **Canonical** | Yes | Actor owner (curator) | Manual |
| `includes/actors/{slug}/rules.md` | **Canonical** | Yes | Actor owner (curator) | Manual |
| `.cursor/rules/*.mdc` | **Derived** | **NEVER** | System | `propagate_agent_rules.php` |
| `.kiro/rules/*.md` | **Derived** | **NEVER** | System | `propagate_agent_rules.php` |
| `.windsurf/rules/*.md` | **Derived** | **NEVER** | System | `propagate_agent_rules.php` |
| `channels/42/actors/{slug}/README.md` | **Derived** | **NEVER** | System | Generated from canonical |
| `database/lupopedia/actors/actor_id/registry.json` | **Derived** | **NEVER** (after migration) | System | `propagate_actor_identity.php` |

---

## 4. CANONICAL VS DERIVED RULES

### 4.1 Propagation Flow

```
Canonical Sources                           Derived Outputs
---------------------------------------------------------------------
rules/root/*.md                        .cursor/rules/*.mdc
    (shared base rules)                          (Cursor IDE)
            |
            +----------------------------→ .kiro/rules/*.md
            |                                  (Kiro IDE)
            +----------------------------→ .windsurf/rules/*.md
            |                                  (Windsurf IDE)
            +----------------------------→ .cascade/rules/*.md
                                               (Cascade IDE)

includes/actors/{slug}/rules.md
    (actor-specific overlays)
            |
            +----------------------------→ Merged output
                                           (actor rules + base)
                                                    |
                                                    +-→ IDE-specific files
                                                        (propagate_agent_rules.php)
```

### 4.2 Propagation Script Rules

1. **Shared base rules** are never copied into actor directories. Actors reference them by path.

2. **Actor-specific overlays** are stored in `includes/actors/{slug}/rules.md` and `skills.md` and are referenced/merged, not copied.

3. **Derived IDE files** are generated by `scripts/propagate_agent_rules.php` which:
   - Reads root rules from `rules/root/`
   - Reads actor-specific overlays from `includes/actors/{slug}/rules.md`
   - Merges them (actor overlay wins where defined; base rules inherited where not overridden)
   - Writes to IDE-specific directories with provenance header
   - **Never overwrites hand-edited files without explicit flag**

4. **Generated files must declare provenance** in their footers:
   ```yaml
   lupopedia.footer:
     generated_by: "propagate_agent_rules.php v1.2"
     source_canonical: ["rules/root/*.md", "includes/actors/wolfie/rules.md"]
     source_timestamp: "20260321_120000"
     auto_regenerated: true
     do_not_edit: true
   ```

### 4.3 Actor Identity Propagation

`manifest.json` is the **canonical source of truth** for:
- `actor_id`
- `actor_slug`
- `actor_name`
- `traits`
- `paired_faucets`
- `paired_agents`

Derived outputs (kept in sync via `propagate_actor_identity.php`):
- Actor rows in `lupo_actors` database table
- Actor registry (`database/lupopedia/actors/actor_id/registry.json`)
- Actor README files in channels (`channels/42/actors/{slug}/README.md`)

**Propagation order:**
1. Edit `includes/actors/{slug}/manifest.json`
2. Run `scripts/propagate_actor_identity.php`
3. Script updates database and registry
4. Derived files are regenerated via `propagate_agent_rules.php`
5. Channel README files updated

---

## 5. SAFE REPAIR STRATEGY

### 5.1 Current State Assessment

| Issue | Current State | Target State |
|-------|--------------|--------------|
| **Actor identity location** | Scattered: registry.json, DB, AGENTS.md, IDE rule files | Single canonical: `includes/actors/{slug}/manifest.json` |
| **Shared rules storage** | Only in `rules/root/` | Already correct — no change needed |
| **Actor-specific rules storage** | In IDE directories (derived, hand-edited) | Move to `includes/actors/{slug}/rules.md` (canonical) |
| **Actor skills definition** | Undefined, scattered, or implied | Share library in `skills/shared/`; refs in `includes/actors/{slug}/skills.md` |
| **IDE outputs** | Mixed with canonical files | Separate: canonical in `includes/actors/`, derived in IDE dirs |
| **Missing actors** | Some actors have incomplete definitions | Build canonical definitions for all 11 actors |
| **Personality/Soul** | Partially defined in channel artifacts | Move to `includes/actors/{slug}/personality.md` and `soul.md` |

### 5.2 Migration Phases

#### Phase 1: Infrastructure Setup (No Actor Data Changes)
**Goal:** Create directory structure and scripts; no actor definitions modified yet.

- [ ] Create `includes/actors/` directory structure with subdirs for each actor (empty for now)
- [ ] Create `skills/shared/` directory (empty for now)
- [ ] Create `scripts/propagate_actor_identity.php` (new script)
- [ ] Extend `scripts/propagate_agent_rules.php` to:
  - Read actor-specific overlays from `includes/actors/{slug}/rules.md`
  - Merge with base rules
  - Add provenance headers to generated files
  - Support `--dry-run` flag for verification
- [ ] Add validation to ensure generated files are not edited directly

**Verification Checklist:**
- [ ] Directories created; permissions correct
- [ ] Scripts run without errors in dry-run mode
- [ ] No actor files modified
- [ ] Current state unchanged after Phase 1

#### Phase 2: Define Shared Skill Library
**Goal:** Create foundational shared skills that all actors can reference.

**Owner:** THOTH (content design), HEPHAESTUS (file structure)

- [ ] Define skill format:
  - Purpose: what this skill enables
  - Prerequisites: required context or rules
  - Inputs: what actor needs to know/have
  - Outputs: what actor produces
  - Examples: concrete usage scenario
- [ ] Create initial shared skills library:
  - `header_validation.md` — validating LUPOPEDIA HEADERS structure
  - `doctrine_review.md` — reviewing doctrine changes for consistency
  - `schema_authority.md` — understanding schema authority chain
  - `thread_navigation.md` — navigating thread structure and parent-child relationships
  - `governance_application.md` — applying governance rules from Thread 1035
- [ ] Document skill usage and discovery in actor `skills.md`

**Verification Checklist:**
- [ ] Shared skills are documented with consistent format
- [ ] Each skill has purpose, prerequisites, inputs, outputs, examples
- [ ] Skills are referenced (not copied) in at least one actor's `skills.md`

#### Phase 3: Migrate Existing Actor Definitions
**Goal:** Build canonical actor definitions from existing sources.

**Owner:** HEPHAESTUS (file creation), with content from ATHENA/THOTH

For each actor (start with: wolfie, thoth, lilith, athena, hephaestus, hermes):

- [ ] Create `includes/actors/{slug}/manifest.json` from:
  - Existing `database/lupopedia/actors/actor_id/registry.json` (actor_id, slug, name)
  - AGENTS.md or similar (traits, paired_faucets, paired_agents)
- [ ] Create `personality.md`:
  - Extract from existing channel artifacts, AGENTS.md, or conversation history
  - If no existing definition, create initial version with reasonable defaults
- [ ] Create `soul.md`:
  - Extract from AGENTS.md §Purpose or role definitions
  - If no existing definition, create initial version
- [ ] Create `rules.md`:
  - List inherited shared rules
  - Add actor-specific rule overlays (if any exist in current IDE files)
  - Format: references to `rules/root/`, overlays as new sections
- [ ] Create `skills.md`:
  - List shared skills this actor uses
  - Add actor-specific skills (if any exist currently)
  - Format: references to `skills/shared/`, additions as new sections

**Verification Checklist:**
- [ ] All 6 canonical actors have complete file sets
- [ ] No contradictions between manifest.json and DB/registry
- [ ] rules.md correctly references root rules (not copying content)
- [ ] skills.md correctly references shared skills (not copying content)
- [ ] No hand-edited copies of shared rules/skills in actor dirs

#### Phase 4: Regenerate Derived Outputs
**Goal:** Derive all outputs from canonical sources.

- [ ] Run `propagate_actor_identity.php` to sync:
  - Database `lupo_actors` table
  - Registry `database/lupopedia/actors/actor_id/registry.json`
  - Actor documentation
- [ ] Run `propagate_agent_rules.php` to regenerate:
  - `.cursor/rules/*.mdc` files
  - `.kiro/rules/*.md` files
  - `.windsurf/rules/*.md` files
  - All with provenance headers
- [ ] Verify generated files contain:
  - Correct actor rules (base + overlays merged)
  - Provenance headers with source references
  - Consistent formatting

**Verification Checklist:**
- [ ] Derived files match canonical source exactly
- [ ] All generated files contain provenance headers
- [ ] Running propagation scripts twice produces identical output (deterministic)
- [ ] No hand-edited content in derived files

#### Phase 5: Remove Obsolete Files
**Goal:** Eliminate duplicate actor definitions.

- [ ] Archive old actor definitions that are now derived:
  - Old README files in root directories
  - Old registry.json entries
  - Hand-edited IDE rule files (now derived)
- [ ] Verify all unique content from old files was captured in canonical definitions
- [ ] Remove archived files from repo

**Verification Checklist:**
- [ ] No duplicate actor definitions anywhere in repo
- [ ] All actor truth is in `includes/actors/`
- [ ] All derived files are clearly marked as generated
- [ ] No significant content lost in archive step

#### Phase 6: Add Active Subset Support (Optional, Future)
**Goal:** Enable task-scoped rule/skill subsets.

This phase is **optional and deferred** unless needed immediately.

- [ ] Define header fields for task directives:
  - `required_rules: Array<String>` — rules active for this task
  - `required_skills: Array<String>` — skills active for this task
- [ ] Extend propagation scripts to generate task-specific subsets
- [ ] Add validation to ensure referenced rules/skills exist
- [ ] Document usage in onboarding

---

## 6. IMPLEMENTATION ORDER (HEPHAESTUS)

### 6.1 Immediate (Phase 1 — Infrastructure)
1. Create `includes/actors/` directory structure with actor subdirectories
2. Create `skills/shared/` directory (empty; ready for skills)
3. Write `scripts/propagate_actor_identity.php` (new script)
   - Reads `includes/actors/*/manifest.json`
   - Updates `lupo_actors` table
   - Updates registry.json
   - Supports `--dry-run` flag
4. Extend `scripts/propagate_agent_rules.php` to:
   - Read actor-specific overlays from `includes/actors/{slug}/rules.md`
   - Merge with base rules from `rules/root/`
   - Add provenance footers to generated files
   - Support `--dry-run` flag
   - Report conflicts or anomalies

**Deliverable:** Scripts run without error on dry-run; infrastructure ready for Phase 2

### 6.2 Short-Term (Phase 2–3 — Actor Definitions)
5. Create shared skill library structure (THOTH designs content; HEPHAESTUS creates files)
   - `skills/shared/header_validation.md`
   - `skills/shared/doctrine_review.md`
   - `skills/shared/schema_authority.md`
   - `skills/shared/thread_navigation.md`
6. Build canonical actor files for 6 primary actors:
   - `includes/actors/wolfie/` (complete set)
   - `includes/actors/thoth/` (complete set)
   - `includes/actors/lilith/` (complete set)
   - `includes/actors/athena/` (complete set)
   - `includes/actors/hephaestus/` (complete set)
   - `includes/actors/hermes/` (complete set)

**Deliverable:** All 6 actors have canonical file sets in `includes/actors/`

### 6.3 Medium-Term (Phase 4–5 — Regeneration & Cleanup)
7. Run propagation scripts to generate derived outputs
8. Remove obsolete files and archive old definitions
9. Verify no duplicates remain

**Deliverable:** Single canonical source of actor truth; derived outputs clean

### 6.4 Long-Term (Phase 6 — Active Subsets, Deferred)
10. Implement active subset support (if needed)

---

## 7. DOCUMENTATION ORDER (THOTH)

### 7.1 Before Implementation (Parallel with Phase 1)
1. Create `docs/doctrine/ACTOR_ARCHITECTURE_DOCTRINE.md`
   - Canonical reference for this design
   - Authority hierarchy for actor definitions
   - Governance rules
2. Create `docs/doctrine/SHARED_SKILLS_DOCTRINE.md`
   - Skill format and requirements
   - How to define new shared skills
   - How actors reference skills

### 7.2 During Implementation (Phase 2–3)
3. Design shared skills library with examples
4. Update `AGENTS.md` with:
   - Reference to `includes/actors/` as canonical location
   - Removed obsolete information
   - Links to ACTOR_ARCHITECTURE_DOCTRINE
5. Update `ONBOARDING.md` to point new actors to:
   - `includes/actors/{slug}/personality.md` — understand how to interact
   - `includes/actors/{slug}/soul.md` — understand role and boundaries
   - `includes/actors/{slug}/rules.md` — understand constraints
   - `includes/actors/{slug}/skills.md` — understand capabilities

### 7.3 After Implementation (Phase 4–5)
6. Create actor-specific documentation in `includes/actors/{slug}/README.md` (generated)
   - Links to personality, soul, rules, skills
   - Contact/coordination info
7. Update ACTOR_FACET_SEPARATION_DOCTRINE (if needed) to reference new architecture

---

## 8. AUDIT CHECKPOINTS (LILITH)

### 8.1 Phase 1 Completion Audit
**Verification:**
- [ ] `includes/actors/` directory structure exists
- [ ] Subdirectories for all 6 actors created
- [ ] `skills/shared/` directory created
- [ ] `propagate_actor_identity.php` written and runs without errors (dry-run)
- [ ] `propagate_agent_rules.php` extended and runs without errors (dry-run)
- [ ] No actor files modified yet
- [ ] CI/CD detects hand-edited derived files during Phase 1

**Sign-off:** ✅ Infrastructure ready

### 8.2 Phase 3 Completion Audit
**Verification:**
- [ ] Each of 6 actors has complete file set: manifest.json, personality.md, soul.md, skills.md, rules.md
- [ ] manifest.json values match existing registry.json and DB (no conflicts)
- [ ] rules.md correctly references `rules/root/` files (not copying content)
- [ ] skills.md correctly references `skills/shared/` files (not copying content)
- [ ] No hand-edited copies of shared rules/skills in actor directories
- [ ] All 6 actors have valid, parseable JSON and YAML

**Sign-off:** ✅ Canonical definitions complete

### 8.3 Phase 4 Completion Audit
**Verification:**
- [ ] Derived files contain correct provenance headers with source references
- [ ] `lupo_actors` table updated correctly via `propagate_actor_identity.php`
- [ ] Registry.json updated and synced
- [ ] `.cursor/rules/*.mdc` files generated correctly
- [ ] `.kiro/rules/*.md`, `.windsurf/rules/*.md` files generated correctly
- [ ] Running propagation scripts twice produces identical output (deterministic)
- [ ] No stale or conflicting definitions remain

**Sign-off:** ✅ Derived outputs clean and consistent

### 8.4 Phase 5 Completion Audit
**Verification:**
- [ ] No duplicate actor definitions exist anywhere in repo
- [ ] All actor identity truth is in `includes/actors/`
- [ ] All derived files clearly marked with provenance headers
- [ ] Old obsolete files archived or removed
- [ ] No significant content lost during cleanup
- [ ] CI/CD blocks commits with hand-edits to derived files

**Sign-off:** ✅ Single source of truth established

### 8.5 Post-Migration Ongoing Checks
- [ ] CI/CD validator detects if derived files are edited directly
- [ ] CI/CD validator enforces provenance headers on all generated files
- [ ] CI/CD blocks commits with stale derived files
- [ ] Periodic audit runs return zero conflicts

---

## 9. GOVERNANCE ALIGNMENT

### 9.1 Relationship to Thread 1035 (Governance Authority)

**Thread 1035** (Governance Directive) governs **how doctrine and governance are changed**.

**Thread 1036** (This architecture) governs **what the actor architecture should be**.

These are **complementary, not conflicting:**

- Thread 1035: Authority hierarchy for doctrine changes, tool validation rules, safe refactoring protocol
- Thread 1036: Canonical structure for actor definitions, shared vs subset composition, filesystem placement

### 9.2 Required Approvals

| Item | Authority | Current Status |
|------|-----------|---|
| Actor architecture design | WOLFIE directive (Thread 1035 Pathway A) | **Pending WOLFIE review** |
| Shared skills library governance | THOTH (curates), WOLFIE (approves) | **Pending design approval** |
| Propagation script changes | WOLFIE directive (Thread 1035 Pathway A) | **Pending architecture approval** |
| Phase 1 implementation | HEPHAESTUS (executes), WOLFIE (oversight) | **Pending directive** |
| Actor file migration | HEPHAESTUS (executes), THOTH (content), LILITH (audits) | **Pending directive** |

### 9.3 Governance Path Forward

1. WOLFIE reviews this architecture design
2. If approved: Issue WOLFIE directive adopting this architecture
3. Directive enables Phases 1–3 implementation
4. THOTH defines shared skills library
5. HEPHAESTUS and team execute migration
6. LILITH audits each phase
7. Thread 1036 closes when Phase 5 complete

---

## 10. SUCCESS CRITERIA

After full implementation, this architecture design is successful if:

- [ ] A new contributor can find actor definitions **only** in `includes/actors/{slug}/`
- [ ] Actor identity is unambiguous: **`manifest.json` is the single canonical source**
- [ ] Shared vs actor-specific vs task-specific content is **clearly separated and labeled**
- [ ] IDE-derived files are **obviously generated** (provenance headers, do-not-edit markers, CI/CD enforcement)
- [ ] All 11 primary coordination personas have **complete canonical definitions**
- [ ] All 6 active actors + key specialized agents have **complete definitions**
- [ ] Shared skill library **exists and is actively referenced** by actors
- [ ] Propagation scripts produce **consistent, deterministic outputs**
- [ ] CI/CD **prevents direct edits** to derived files
- [ ] No actor definition exists **outside** the canonical location
- [ ] New actors can be added to the system by **creating a new actor subdirectory only**
- [ ] Actor definitions are **easily browseable and maintainable**

---

## CONCLUSION

This architecture design provides a **single canonical location** for all actor definitions, with clear rules for shared content, actor-specific customization, and safe derivation of IDE-specific rule files. The **safe migration strategy** protects existing definitions while moving toward the canonical model.

**The design is complete and ready for WOLFIE approval.**

Implementation follows Thread 1035 governance protocols:
- WOLFIE directive required to proceed
- HEPHAESTUS executes with THOTH support
- LILITH audits each phase
- Thread 1036 documents complete architecture

---

_ATHENA (actor_id 12) — Canonical Actor Architecture design complete. Awaiting WOLFIE directive for Phase 1 infrastructure setup._
