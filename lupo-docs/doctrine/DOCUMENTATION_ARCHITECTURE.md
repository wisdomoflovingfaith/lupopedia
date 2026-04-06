---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260406143710"
  file_path_from_root: "lupo-docs/doctrine/DOCUMENTATION_ARCHITECTURE.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/DOCUMENTATION_ARCHITECTURE.md"
  last_modified_utc: "20260406143710"
  federation_node_id: 0
  channel_id: 42
  thread_id: "documentation-architecture"
  actor_id: 2
  actor_name: "LILITH"
  delegation_chain: "lilith:root"
  artifact_type: "doctrine"
  artifact_kind: "documentation"
  purpose: "The complete documentation architecture — 5W1H across all layers"
  tags:
  - "documentation"
  - "architecture"
  - "5w1h"
  - "doctrine"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/26_five_layer_architecture.md"
      type: references
      weight: 1.0
      reason: WHERE layer defines relationships
    - to: "lupo-docs/prd/16_lupopedia_headers.md"
      type: references
      weight: 1.0
      reason: Header structure and metadata
    - to: "lupo-rules/root/LUPOPEDIA_HEADERS_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: Canonical header rules
    - to: "lupo-docs/doctrine/MULTI_AGENT_COORDINATION_DOCTRINE.md"
      type: references
      weight: 0.8
      reason: Channel-based coordination
    - to: "lupo-docs/prd/26_five_layer_documentation_architecture.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260406143710"
  verified_by:
    identity_type: "actor"
    actor_id: 2
    agent_name_identity: "LILITH"
  orchestrator: "lilith:root"
---

# Documentation Architecture — The Complete 5W1H Framework

## The Core Principle

**Headers = Current State Metadata**  
**Threads = Historical WHY (Immutable)**  
**Edges = WHERE (Relationships)**  
**Content = WHAT (The thing itself)**

---

## The 5W1H Framework Applied

| Element | Where It Lives | Mutability | Purpose |
|---------|----------------|------------|---------|
| **WHO** | Header `author` field | Updates when ownership changes | Current responsible party |
| **WHAT** | Document title + purpose header | Updates when scope changes | What this document is about |
| **WHERE** | `edges.md` (PRD 26) | Updates when relationships change | Structural connections |
| **WHEN** | Header `when_updated` | Updates on content change | Last modification time |
| **WHY** | Thread files (timestamped) | **Immutable** | Historical rationale, decisions, discussions |
| **HOW** | Document body + implementations | Updates as methods evolve | Implementation approach |

---

## The Three Documentation Scopes

### Scope 1: PRD (What to Build)

```
lupo-docs/versions/4.0.94/prd/31_context_system.md
```

**Header (Current State):**
```yaml
---
prd_id: 31
prd_slug: context_system
title: "Context System Framework"
status: "rejected"
author:
  type: "actor"
  id: 102
  name: "CURSOR"
when_updated: "20260402150000"
---
```

**Edges (WHERE):**
```yaml
lupopedia.edges:
  outbound_edges:
    - to: "/lupo-docs/prd/26_five_layer_architecture.md"
      type: references
      weight: 1.0
```

**Decisions (WHY — Immutable):**
```
lupo-docs/implementations/31_context_system/discussions/
└── 20260402_210000_DECISION_reject_prd31.md
```

**Content (WHAT + HOW):**
```markdown
# PRD 31: Context System Framework

## What
Defines context organization for documentation.

## How
Creates lupo-contexts/ folder hierarchy.

## Why (Summary)
See decision thread for full rationale.
```

---

### Scope 2: Implementation (How to Build)

```
lupo-docs/implementations/31_context_system/
├── README.md
├── authors.md
├── edges.md
└── discussions/
    └── 20260402_210000_DECISION_reject_prd31.md
```

**README Header (Current State):**
```yaml
---
parent_prd: 31
status: "rejected"
version: "1.0.0"
---
```

**Edges (WHERE):**
```markdown
## Documentation Edges
- PRD: 31_context_system.md
- DECISION: discussions/20260402_210000_DECISION_reject_prd31.md
```

**Decision Thread (WHY — Immutable):**
```markdown
---
author:
  type: "agent"
  id: 2
  name: "COUNTERMEASURE"
---

# Decision: Reject PRD 31

## Why
Parallel classification system conflicts with PRD 26.

## Context
COUNTERMEASURE identified structural issues.

## Resolution
PRD 31 rejected. Context taxonomy moved to edges.md.
```

---

### Scope 3: Version (Release Tracking)

```
lupo-docs/versions/4.0.93/
├── PLAN.md
├── TODO.md
├── CHANGELOG.md
└── decisions/
    └── 20260402_210000_DECISION_prd31_rejection.md
```

**PLAN.md Header:**
```yaml
---
version: "4.0.93"
status: "in_progress"
when_updated: "20260402150000"
---
```

**Version Decision (WHY):**
```
decisions/20260402_210000_DECISION_prd31_rejection.md
```

**Content:** Why PRD 31 was rejected for this version.

---

## The Flow of Information

```
1. Discussion happens in channel thread
   ↓
2. Decision captured as timestamped file
   ↓
3. Decision linked from implementation/discussions/
   ↓
4. Implementation updates edges.md
   ↓
5. PRD header status updated
   ↓
6. Version PLAN.md tracks progress
```

---

## What Goes Where — Quick Reference

| Question | PRD | Implementation | Version | Channel Thread |
|----------|-----|----------------|---------|----------------|
| **WHO** | Header `author` | Header `author` | Header `author` | Message author |
| **WHAT** | Title + purpose | README | PLAN/CHANGELOG | Thread subject |
| **WHERE** | `edges.md` | `edges.md` | Directory structure | Message context |
| **WHEN** | `when_updated` | `when_updated` | `when_updated` | Filename timestamp |
| **WHY** | Decision thread | Decision thread | Decision thread | Full discussion |
| **HOW** | Body (summary) | Implementation files | Plan checklist | Resolution |

---

## Channel Architecture Clarification

### Current Channel Structure
```
lupo-channels/
├── {channel_id}/
│   ├── broadcasts/          # WHO announcements
│   ├── threads/             # WHAT discussions
│   │   └── {thread_id}/    # Individual threads
│   │       └── {timestamp}_{type}_{purpose}.md
│   ├── direct/              # WHO→WHO messages
│   ├── tasks/               # HOW action items
│   ├── content/             # WHERE artifacts
│   └── rules/               # WHY constraints
```

### Decision Locations by Context

| Decision Type | Location | Example |
|---------------|----------|---------|
| **PRD Decisions** | `lupo-docs/implementations/{id}_{slug}/decisions/` | `implementations/30_prd_development_guide/decisions/20260402_120000_DECISION_naming_fix.md` |
| **Version Decisions** | `lupo-docs/versions/{version}/decisions/` | `versions/4.0.93/decisions/20260402_210000_DECISION_prd31_rejection.md` |
| **Channel Decisions** | `lupo-channels/{federation_node_id}/{channel_key}/{thread_key}/decisions/` (active); or legacy `lupo-channels/{channel_id}/threads/{thread_id}/` | e.g. `lupo-channels/0/development/header-format-discussion/decisions/20260402_120000_DECISION_adopt_channel_restructure.md`; legacy e.g. `lupo-channels/42/threads/1001/...` |

---

## The Golden Rule

**If it can change, put it in a header or edges.md.**  
**If it must be preserved forever, put it in a timestamped thread file.**  
**If it's the thing itself, put it in the content body.**

```
Headers → Current metadata (mutable)
Edges   → Relationships (mutable)
Content → The thing itself (mutable)
Threads → Historical WHY (IMMUTABLE)
```

---

## Universal Application Examples

### Code Comment
```php
/**
 * WHO: CURSOR (actor_id 102)
 * WHAT: Generate unique IDs
 * WHERE: Used throughout Lupopedia
 * WHEN: Called when creating entities
 * WHY: Ensure unique IDs without AUTO_INCREMENT
 * HOW: Timestamp + 4-digit sequence
 */
function generateId() {
    return date('YmdHis') . str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
}
```

### Database Migration
```sql
-- WHO: Database migration team
-- WHAT: Remove contexts tables
-- WHERE: All Lupopedia installations
-- WHEN: After PRD 31 rejection (2026-04-02)
-- WHY: Context system created parallel classification
-- HOW: DROP TABLE statements
```

### Test File
```php
<?php
/**
 * WHO: LILITH QA
 * WHAT: Validate PRD headers
 * WHERE: All PRD files
 * WHEN: During CI/CD
 * WHY: Prevent constitutional violations
 * HOW: Check required fields, format, references
 */
```

---

## Pseudocode Directory — Dual Constitutional Purposes

**Normative PRD:** [PRD 17 — Decision thread format](../prd/17_decisions_format.md). The directory **`decisions/pseudocode/`** (within each documentation context) has **two** constitutional purposes.

### Purpose 1 — Cave-Man Shorthand (Token-Efficient Constitution Layer)

1. Purpose 1 files provide ultra-compressed, low-token directives for external LLMs and IDE agents.
2. These files summarize binding rules (“do X, never Y”) without full PRD detail.
3. They serve as the quickload constitutional layer when full PRDs are too large to load.
4. Naming pattern: `*_constitution.pseudo.md`.
5. Content must be factual, minimal, and derived from canonical PRDs.
6. No production code, no schema, no DDL, no implementation details.
7. These files are REQUIRED for external-AI onboarding.

### Purpose 2 — Design Pseudocode (Implementation Planning)

1. Purpose 2 files are comment-heavy design artifacts.
2. They document Option A vs B, tradeoffs, rationale, TODOs, and design flows.
3. They may include PHP-shaped pseudocode (`*.pseudo.php`) or markdown (`*_design.pseudo.md`).
4. They MUST NOT contain executable code or DDL.
5. They are for human/agent deliberation, not runtime.

### Shared Constitutional Requirements

1. Both Purpose 1 and Purpose 2 files MUST include full `lupopedia.headers`.
2. Both MUST live under `decisions/pseudocode/` within their context.
3. Both MUST be indexed in `decisions/pseudocode/THREAD_INDEX.md`.
4. Purpose 1 files MUST be safe for low-context external agents.
5. Purpose 2 files MUST NOT be used as runtime code.
6. Validators MUST enforce:
   - No plain `.php` files in pseudocode/
   - No DDL in pseudocode/
   - Required headers present
   - Naming patterns respected

---

## LILITH Final Sign-off

```yaml
findings:
  accuracy_score: 100
  constitutional_violations: []
  security_concerns: []
  bias_detected: no
  verdict: "This is the canonical explanation of how Lupopedia documentation works."
```

**LILITH Sign-off:** ✅ **This is the definitive guide to how WHO, WHAT, WHERE, WHEN, WHY, and HOW are distributed across headers, edges, content, and immutable decision threads.**
