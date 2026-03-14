---
lupopedia.headers:
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:captain"
  lupopedia.version: "4.0.75"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/status/CURSOR_LILITH_ACTOR_REGISTRATION_CHECKLIST_INTEGRATION_4_0_75.md"
  last_modified_utc: "20260315"
  system_version: "4.0.75"
  artifact_type: "report"
  artifact_kind: "implementation"
  purpose: "Implementation report for integrating Lilith review improvements into Actor Registration Checklist"
  tags: ["cursor", "lilith", "actor_registration", "checklist", "4.0.75"]
---

# Lilith Review Integration — Actor Registration Checklist (4.0.75)

**Date:** 2026-03-15  
**Actor:** Cursor IDE (actor_id 102)  
**Directive:** Integrate Lilith review improvements into Actor Registration Checklist; verify against schema/TOON; update docs; CHANGELOG.

---

## 1. Executive summary

Cursor verified the Actor Registration Checklist against install SQL, TOON (`lupo_actors.toon.json`), and the actor registry; integrated **five Lilith review improvements**; restructured the checklist to the required final section order; consolidated README next_actions (removed duplicate bullets); and appended a new CHANGELOG subsection under 4.0.75 without overwriting any existing IDE agent entries. The checklist is now the definitive onboarding guide for new Lupopedia agents.

---

## 2. Files researched

| Item | Purpose |
|------|--------|
| `lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md` | Current checklist content and section order. |
| `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` | Confirmed `lupo_actors`: PRIMARY KEY (actor_name), UNIQUE (actor_id), UNIQUE (slug). |
| `lupo-database/lupopedia/toon/lupo_actors.toon.json` | Column list and primary_key/relationships. |
| `lupo-database/lupopedia/actors/actor_id/registry.json` | Format: schema_version, actors array with id, type, slug, dir; optional lead_orchestration. |
| `lupo-rules/root/*.md` (grep rule_id) | Actual rule IDs: ACT001, DB001, DB002, DB004–DB008, ARC001–ARC005, CTX001 for quick reference table. |
| `README.md` | next_action / next_actions blocks for consolidation. |
| `CHANGELOG.md` | 4.0.75 subsection order; append-only insertion point. |

---

## 3. Improvements integrated

| # | Improvement | Implementation |
|---|-------------|----------------|
| 1 | **actor_name generation guidance** | New §4 "Generating actor_name" after Identity fields: table (IDE Agent `{slug}-ide`, System Tool `tool-{slug}`, Web Terminal `terminal-{slug}`, Human `user-{id}`) and rules (lowercase, hyphens, no spaces, no special characters). |
| 2 | **Troubleshooting section** | New §9 "Troubleshooting common issues" after Validation: table for duplicate actor_id, duplicate actor_name, registry updated but DB fails, agent cannot find itself in registry, paired_actor_id fails. |
| 3 | **Automation note** | After Step 2 (Persist in database): "Automation note" subsection with `php lupo-scripts/propagate_agent_rules.php --target=<your-agent>` and example `--target=cursor`; explains IDE rule files in .cursor/, .kiro/, .windsurf/, .idea/. |
| 4 | **actor_id vs actor_name clarification** | In §3 Identity fields: actor_name (PRIMARY KEY, unique string, semantic identifier, example cursor-ide); actor_id (UNIQUE, integer, numeric identifier, explicitly assigned); Relationship (1:1 mapping); Purpose (actor_name → semantic, actor_id → numeric). |
| 5 | **Rule ID quick reference** | New §13 "Rule ID quick reference" after References: table with ACT001, DB001, DB002, DB006, DB008; document filenames and one-line summaries aligned with repo (database-logic-prohibition, migration-doctrine, reserved-id, database-offline-fallback, ide-agent-identity-actor-pairing). |

---

## 4. Checklist structure verification

Final section order implemented:

1. Who must register  
2. Prerequisites  
3. Identity fields (with actor_id vs actor_name)  
4. Generating actor_name  
5. Step 1: Add registry entry  
6. Step 2: Persist in database (including automation note)  
7. Step 3: Fallback when DB unavailable  
8. Step 4: Validation  
9. Troubleshooting common issues  
10. Activation boundary  
11. Summary  
12. References  
13. Rule ID quick reference  

Schema alignment: install SQL confirms actor_name PRIMARY KEY, actor_id UNIQUE, slug UNIQUE. TOON and seed examples referenced. Registry format includes schema_version and actors[] with id, type, slug, dir.

---

## 5. Files modified

| File | Change |
|------|--------|
| `lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md` | Full rewrite: restructured to required order; added §4 Generating actor_name, §9 Troubleshooting, automation note in Step 2, actor_id vs actor_name in §3, §13 Rule ID quick reference; header last_modified_utc 20260315; purpose/tags updated. |
| `README.md` | Consolidated next_action list: removed duplicate bullets so only one set remains (Required Reading, lupo-rules/root/, checklist; Getting Started 4.0.75; review actor/faucet when doctrine paths change). |
| `CHANGELOG.md` | Appended new subsection "Documentation hardening — Actor Registration Checklist (Lilith review integration, 4.0.75)" with four bullets (checklist finalized, five improvements, structure, README consolidation). Inserted before Antigravity subsection; no existing 4.0.75 content overwritten. |
| `lupo-docs/status/CURSOR_LILITH_ACTOR_REGISTRATION_CHECKLIST_INTEGRATION_4_0_75.md` | Created (this report). |

---

## 6. CHANGELOG update

- **Subsection:** "Documentation hardening — Actor Registration Checklist (Lilith review integration, 4.0.75)" under 4.0.75.
- **Content:** Checklist finalized; verified against install SQL, TOON, registry; five improvements (actor_name guidance, troubleshooting, automation note, actor_id vs actor_name, rule ID quick reference); structure restructured; README next_actions consolidated.
- **Placement:** Immediately after "Actor registration checklist (4.0.75)" and before "Antigravity / Google Rules Propagation Hardening (4.0.75)". All prior 4.0.75 entries left unchanged.

---

## 7. Remaining gaps

None. The checklist now includes naming guidance, troubleshooting, propagation automation, identity clarification, and rule navigation. README and AGENTS already reference the checklist; no further doc changes required for discoverability. Optional future work: add a one-line pointer from the checklist to `lupopedia.next_actions` in root docs if the project standardizes on that block for "after reading" actions.

---

*Cursor IDE (lead orchestration) — 2026-03-15*
