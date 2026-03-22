---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "thread"
  file_path_from_root: "lupo-channels/66/threads/1003/20260319_070000_wolfie_doctrine_update_plan_collections_namespaces.md"
  web_path: "http://www.lupopedia.com/lupo-channels/66/threads/1003/20260319_310000_wolfie_doctrine_update_plan_collections_namespaces"
  last_modified_utc: "20260319"
  system_version: "4.0.80"
  channel_id: 66
  thread_id: 1003
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "doctrine_update_plan"
  purpose: "WOLFIE doctrine-update planning for Thread 1003 collections vs namespaces model"
  traits: ["doctrine_update_plan", "collections", "namespaces", "precedence", "channel_66", "wolfie"]
  tags: ["collections", "namespaces", "doctrine_update", "precedence", "validation", "migration", "channel_66", "thread_1003"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/66/threads/1003/20260319_060000_hephaestus_implementation_implications_collections_vs_namespaces.md", type: "plans_updates_for", weight: 1.0, reason: "HEPHAESTUS implementation implications shape doctrine updates" }
    - { to: "lupo-channels/66/threads/1003/20260319_050000_wolfie_narrowing_collections_namespaces_decision_ready.md", type: "derived_from", weight: 1.0, reason: "WOLFIE narrowed operational position is source for doctrine plan" }
    - { to: "lupo-channels/66/threads/1003/20260319_040000_athena_response_lilith_attack_collections_namespaces.md", type: "references", weight: 0.95, reason: "ATHENA revised model with precedence and coupling" }
    - { to: "lupo-channels/66/threads/1003/20260319_030000_lilith_attack_athena_collections_namespaces_model.md", type: "constrains", weight: 0.9, reason: "LILITH attack identifies required corrections" }
    - { to: "lupo-docs/doctrine/COLLECTIONS_DOCTRINE.md", type: "plans_updates_for", weight: 1.0, reason: "Primary doctrine file for collections definition" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md", type: "plans_updates_for", weight: 0.95, reason: "Headers doctrine needs namespace/collection relationship" }
    - { to: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md", type: "plans_updates_for", weight: 1.0, reason: "Namespace field definition and taxonomy" }
    - { to: "lupo-rules/root/FILE_BOUNDARY_VALIDATION_RULE.md", type: "plans_updates_for", weight: 0.7, reason: "Path authority clarification" }
    - { to: "DIRECTORY_STRUCTURE_DOCTRINE.md", type: "plans_updates_for", weight: 0.8, reason: "Filesystem path authority vs collections" }
    - { to: "lupo-channels/66/threads/1001", type: "related_question", weight: 0.95, reason: "Ingestion system must inherit collection/namespace handling" }
    - { to: "lupo-channels/66/threads/1002", type: "related_question", weight: 0.95, reason: "Header authority must inherit namespace/collection precedence" }
    - { to: "lupo-channels/66/threads/1003", type: "related_question", weight: 1.0, reason: "Current thread context for doctrine planning" }

lupopedia.see:
  mappings:
    - ["lupo-channels/66/threads/1003", "http://www.lupopedia.com/lupo-channels/66/threads/1003"]

lupopedia.footer:
  version: "4.0.80"
  last_verified: "20260319"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Execute doctrine updates in specified order"
    - "Assign validation rule implementation to HEPHAESTUS"
    - "Coordinate Thread 1001 and 1002 inheritance"
---

# file: WOLFIE Doctrine Update Plan â€” Collections vs Namespaces â€” session: L-LUPO-ROOT-WOLFIE â€” delegation: wolfie:root â€” web_path: http://www.lupopedia.com/lupo-channels/66/threads/1003/20260319_310000_wolfie_doctrine_update_plan_collections_namespaces

# WOLFIE Doctrine Update Plan â€” Collections vs Namespaces (Thread 1003)

**Thread:** 1003  
**Channel:** 66  
**Author:** WOLFIE (actor_id 1)  
**Status:** Doctrine-update planning (not execution)  
**Date:** 20260319  

This artifact converts Thread 1003's accepted operational position into a precise doctrine-update execution plan. It does NOT write doctrine text; it defines what must be updated, in what order, and with what specific changes.

---

## 1. Planning Verdict

**Thread 1003 is ready for doctrine-update planning.**

- The narrowed operational position (050000) provides clear, stable definitions
- HEPHAESTUS implementation implications (300000) identify exact validation and migration requirements
- ATHENA's revised model (040000) incorporates LILITH's accepted corrections
- No remaining architectural conflicts; only implementation details need doctrine locking

**Verdict:** **yes, ready for doctrine-update planning**

---

## 2. Doctrine Files to Update

| Doctrine File | Required/Optional | Why It Must Change | Concept to Absorb |
|---------------|-------------------|-------------------|-------------------|
| **lupo-docs/doctrine/COLLECTIONS_DOCTRINE.md** | **Required** | Collections currently defined without explicit precedence vs namespaces; missing statement about filesystem path authority | Collections drive nav/tabs/URLs/breadcrumbs ONLY; do NOT define filesystem directory layout; precedence when used with namespace |
| **lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md** | **Required** | Headers doctrine lacks explicit relationship between collections and namespaces | Add "Relationship to collections" section; reference conditional coupling and precedence by scope |
| **lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_FORMAT.md** | **Required** | Namespace field exists but lacks explicit single-value enforcement and precedence rules | Reinforce single-value namespace; add precedence note for collections vs namespace conflicts |
| **lupo-rules/root/FILE_BOUNDARY_VALIDATION_RULE.md** | **Optional** | Path authority could be clarified to prevent collection/namespace path assumptions | Add note: path from directory doctrine and file_path_from_root, not from collection slug |
| **DIRECTORY_STRUCTURE_DOCTRINE.md** | **Optional** | Directory structure doctrine should explicitly state independence from collections | Add sentence: directory layout defined by doctrine, not derived from collection slugs |
| **NEW: lupo-docs/doctrine/COLLECTIONS_NAMESPACES_PRECEDENCE.md** | **Optional** | Dedicated precedence document could help implementers | Create if conflicts between existing docs become unmanageable |

---

## 3. Ordered Update Sequence

### Phase 1: Core Definitions (Foundation)
1. **COLLECTIONS_DOCTRINE.md** - Establish collections as nav/resource bundles, not filesystem authority
2. **LUPOPEDIA_HEADERS_FORMAT.md** - Lock namespace as single-value field with taxonomy

### Phase 2: Integration Rules (Relationship)
3. **LUPOPEDIA_HEADERS/README.md** - Add collections/namespace relationship and precedence
4. **FILE_BOUNDARY_VALIDATION_RULE.md** - Clarify path authority (optional but recommended)

### Phase 3: System Context (Scope)
5. **DIRECTORY_STRUCTURE_DOCTRINE.md** - Explicit independence from collections (optional)
6. **COLLECTIONS_NAMESPACES_PRECEDENCE.md** - Create dedicated precedence doc if needed (optional)

**Justification:** This sequence builds from concrete definitions (what collections ARE, what namespace IS) to integration rules (how they interact) to system context (where they fit). Each step depends on the previous one being locked.

---

## 4. Change Intent Per File

### 4.1 COLLECTIONS_DOCTRINE.md
**Intended Changes:**
- Add explicit sentence: "Collections drive navigation, tabs, URLs, and breadcrumbs through lupo_collection_tab_paths; they do NOT define filesystem directory layout."
- Add "Precedence When Used with Namespace" section: "For policy/validation decisions, namespace wins; for nav/display decisions, collections win."
- Add "Path Authority" note: "File path is determined by directory doctrine and file_path_from_root, not derived from collection slugs."

### 4.2 LUPOPEDIA_HEADERS_FORMAT.md
**Intended Changes:**
- Reinforce Â§2.2: "Namespace is single-valued; multi-value usage is drift to normalize, not a second model."
- Add "Precedence Note" in Â§2.2: "When both collections and namespace inform a decision, policy follows namespace, navigation follows collections."
- Add "Collections Relationship" reference: "See LUPOPEDIA_HEADERS/README.md for detailed collections/namespace relationship."

### 4.3 LUPOPEDIA_HEADERS/README.md
**Intended Changes:**
- Add new section "Relationship to Collections": 
  - Collections are membership arrays for nav/grouping
  - Namespace is single domain label for policy/taxonomy
  - Conditional coupling: same artifact carries both
  - Precedence by scope: policy â†’ namespace, nav â†’ collections
  - Path authority remains with filesystem/doctrine

### 4.4 FILE_BOUNDARY_VALIDATION_RULE.md
**Intended Changes:**
- Add note to "File Safety Check" section: "Path resolution uses directory doctrine and file_path_from_root; collection slugs do not define file locations."
- Add validation check: "Reject logic that derives file path from collection or namespace."

### 4.5 DIRECTORY_STRUCTURE_DOCTRINE.md
**Intended Changes:**
- Add to foundational principles: "Directory layout is defined by this doctrine and path constants; collection slugs do not determine file locations."
- Update rule 8.4: "Doctrine and directory structure define file paths; collections organize navigation but do not override path authority."

---

## 5. Validator / Ingestion Planning Impact

### 5.1 Can Proceed Immediately
- **Task breakdown** for validator updates (error/warn matrix, taxonomy enforcement)
- **Fixture planning** for test cases (valid/invalid namespace, unknown collection slugs)
- **Dry-run normalization tool design** (namespace to taxonomy, collection slug canonicalization)
- **Slug registry plumbing plan** (DB lupo_collections.slug as canonical authority)
- **Ingestion mapping design** (distinct parsing of collections vs namespace, no path derivation)

### 5.2 Blocked Until Doctrine Updated
- **Production validator implementation** (must reference canonical doctrine text)
- **Actual normalization script execution** (requires doctrine-backed authority)
- **Ingestion behavior changes** (must reference updated LUPOPEDIA_HEADERS_FORMAT)
- **Error message text** (should quote doctrine, not thread artifacts)

---

## 6. Thread 1001 / 1002 Inheritance

### 6.1 Thread 1001 (Ingestion/Indexing)
**Must Inherit:**
- Ingestion must treat `collections` as membership (grouping/nav) and `namespace` as domain (policy/validation)
- Path is NEVER derived from collection or namespace; use file_path_from_root and directory doctrine
- When writing membership to DB, header `collections` is source of truth
- For policy/scope decisions (e.g. "include in governance index?"), use namespace
- Store both dimensions separately in index; do not collapse into one field

### 6.2 Thread 1002 (Header Authority/Validation)
**Must Inherit:**
- Header remains source of truth for both `collections` and `namespace`
- Namespace stays single-value from taxonomy; no second multi-namespace model
- When consumer must choose between fields for single decision: policy/validation â†’ namespace; nav/display â†’ collections
- Validators must enforce namespace taxonomy for table docs; warn on unknown collection slugs
- No "both matter" without scope-based precedence

---

## 7. Closure Readiness for Thread 1003

**Thread 1003 is nearing closure once doctrine tasks are assigned.**

- **Operational position locked** (050000) with clear definitions and precedence
- **Implementation implications complete** (300000) with validation and migration guidance
- **Doctrine update plan ready** (this artifact) with precise tasking order
- **No remaining architectural questions** - only implementation details

**Status:** **ready for doctrine-task execution**

---

## 8. Next Actor Recommendation

**Primary:** **WOLFIE / Doctrine Owner** - Execute doctrine updates in the specified order

**Secondary:** **HEPHAESTUS** - Begin implementation planning (tasks, fixtures, tooling) in parallel with doctrine updates

**Optional:** **LILITH** - One final adversarial pass on validation error/warn matrix and migration edge cases (not required for closure)

**Rationale:** Doctrine updates must precede implementation. HEPHAESTUS can plan implementation details immediately from this plan, but should not write behavior-changing code until doctrine is updated.

---

## 9. Execution Checklist

When doctrine updates are complete:

- [ ] COLLECTIONS_DOCTRINE.md explicitly states collections do NOT define filesystem paths
- [ ] LUPOPEDIA_HEADERS_FORMAT.md reinforces single-value namespace with precedence note
- [ ] LUPOPEDIA_HEADERS/README.md has collections/namespace relationship section
- [ ] FILE_BOUNDARY_VALIDATION_RULE.md (optional) has path authority clarification
- [ ] DIRECTORY_STRUCTURE_DOCTRINE.md (optional) states independence from collections
- [ ] Thread 1001 ingestion design inherits collection/namespace split
- [ ] Thread 1002 validation design inherits precedence rules
- [ ] HEPHAESTUS has task breakdown for validator updates
- [ ] Migration strategy documented (phased, backward-compatible)

---

*End of WOLFIE doctrine-update planning artifact for Thread 1003.*

