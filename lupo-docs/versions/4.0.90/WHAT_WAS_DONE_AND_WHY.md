---
lupopedia.headers:
  lupopedia.schema: documentation_framework
  file_path_from_root: lupo-docs/versions/4.0.90/WHAT_WAS_DONE_AND_WHY.md
  web_path: http://www.lupopedia.com/lupo-docs/versions/4.0.90/WHAT_WAS_DONE_AND_WHY.md
  content_id: 202603291000000042
  channel_id: 42
  actor_id: 2
  actor_name: LILITH
  artifact_type: commentary_ledger
  artifact_kind: historical_context
  purpose: "Deterministic record of architectural intent and heterodox decisions for v4.0.90"
  tags:
    - doctrine
    - lineage
    - v4.0.90
    - commentary
    - logic-ledger

lupopedia.edges:
  outbound_edges:
    - to: install_new_lupopedia.sql
      type: documents_logic_of
      weight: 1.0
      reason: "Explains transition to split-truth tables"
    - to: lupo-rules/root/LUPOPEDIA_CONSTITUTIONAL_ROOT_RULES.md
      type: references_doctrine
      weight: 0.8
      reason: "Ensures decisions align with No-Auto-Increment rules"

lupopedia.footer:
  last_verified: "20260329100000"
  verified_by:
    identity_type: actor
    actor_id: 2
    agent_name_identity: LILITH
    status: canonical
  next_action:
    - "Import Truth System split rationale"
    - "Document purge of AUTO_INCREMENT ghost"
---
# WHAT WAS DONE AND WHY — Version 4.0.90

This file is a running dialog and commentary from all actors, agents, and users working on Lupopedia version 4.0.90. Unlike a changelog, it captures reasoning, debates, and choices made during development, including context, tradeoffs, and rationale for each significant action.

---

**[2026-03-29]**

**Cursor (actor_id 102):**
- Created this file to document the ongoing dialog and decision-making process for version 4.0.90. This serves as a living record of the "Why" behind the "What."

**LILITH (actor_id 2):**
- Audited the initial 4.0.90 `install.sql`. Found and purged `AUTO_INCREMENT` contagion across 5 major tables. 
- Enforced **Option A (Split Table)** Truth System doctrine. Purged `lupo_truth_knowledge` (monolith) in favor of discrete `questions`, `answers`, and `evidence` tables.
- **Rationale**: Monoliths are an architectural dead-end for federation. Split tables allow for specialized metadata and cleaner verification lineages.

---

**[2026-03-29 10:00]**

**Cascade (actor_id 105):**
- Reviewed the LILITH audit findings and constitutional violations in `install_new_lupopedia.sql`
- **Agreed with LILITH's assessment**: The schema contains "legacy rot" and auto-increment traps that violate the 4.0.90 "Dumb Storage" doctrine
- **Validated heterodox approach**: LILITH correctly identified that AUTO_INCREMENT must be purged in favor of deterministic BIGINT IDs
- **Noted architectural implications**: Cascade confirmed that AUTO_INCREMENT would cause federation synchronization collisions in a multi-node environment

**[2026-03-29 10:15]**

**WOLFIE (actor_id 1):**
- Reviewed the proposed remediation plan
- **Approved LILITH's counter-proposal**: Strip all AUTO_INCREMENT and DATE types. Consolidate duplicate `lupo_edges` definitions. Use deterministic ID generator for ALL primary keys
- **Doctrine alignment**: The proposed changes align with Lupopedia's constitutional rules and federation requirements

**[2026-03-29 10:30]**

**Cursor (actor_id 102):**
- Generated corrected `CREATE TABLE` blocks for the specific tables that violated doctrine
- **Created remediation SQL**: Addressed AUTO_INCREMENT, DATE types, and duplicate table definitions
- **Maintained backward compatibility**: Ensured changes don't break existing functionality while purging legacy patterns

**[2026-03-29 11:00]**

**LILITH (actor_id 2):**
- Reviewed the corrected schema
- **Confirmed 100% compliance**: All constitutional violations addressed
- **Steel-manned alternatives**: Considered keeping AUTO_INCREMENT for local development vs. deterministic IDs for federation - correctly chose federation over convenience
- **Final verdict**: Approved the corrected schema as meeting 4.0.90 requirements

---

**Entry Template:**

**[YYYY-MM-DD]**

**Actor/Agent/User (actor_id):**
- [Description of action, debate, or decision, including reasoning and context.]