---
lupopedia.headers:
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:captain"
  lupopedia.version: "4.0.75"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/status/CURSOR_ACTOR_REGISTRATION_CHECKLIST_IMPLEMENTATION_4_0_75.md"
  last_modified_utc: "20260314"
  system_version: "4.0.75"
  artifact_type: "report"
  artifact_kind: "implementation"
  purpose: "Implementation report for canonical actor registration checklist derived from TOON/database and lupo-database fallback"
  tags: ["cursor", "actor_registration", "checklist", "toon", "fallback", "4.0.75"]
---

# Actor Registration Checklist — Implementation Report (4.0.75)

**Date:** 2026-03-14  
**Actor:** Cursor IDE (actor_id 102)  
**Directive:** Actor registration checklist from TOON source + lupo-database fallback + CHANGELOG update.

---

## 1. Executive summary

Cursor created a **canonical actor registration checklist** at [lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md](../ACTOR_REGISTRATION_CHECKLIST.md) derived from TOON files (`lupo_actors`), install SQL, seed files, and the actor registry. The checklist documents registry update (required), database persistence (canonical when DB available), and **lupo-database fallback** (registry + optional CSV) when the live DB is unavailable. README and AGENTS were updated to point to the checklist; CHANGELOG was updated with a new subsection without overwriting other IDE agents’ entries.

---

## 2. Files researched

| Item | Purpose |
|------|--------|
| `lupo-database/lupopedia/toon/lupo_actors.toon.json` | Column/type and PK/unique constraints for `lupo_actors`. |
| `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` | CREATE TABLE `lupo_actors`, reserved-id comment, INSERT examples. |
| `lupo-database/lupopedia/mysql/seed/seed_actors_agents_4.0.45.sql` | Seed pattern: actor_name, actor_id, actor_type, slug, name, timestamps, paired_actor_id, metadata_json. |
| `lupo-database/lupopedia/actors/actor_id/registry.json` | Registry format: id, type, slug, dir; used by tooling. |
| `lupo-database/lupopedia/csv/lupo_actors.csv` | Fallback CSV structure under lupo-database. |
| `lupo-rules/root/ide-agent-identity-actor-pairing-doctrine.md` (ACT001) | Agent identity, paired orchestrator, no anonymous operation. |
| `lupo-rules/root/reserved-id-doctrine.md` | actor_id application-supplied; no AUTO_INCREMENT for registry-backed tables. |
| `lupo-rules/root/database-offline-fallback-import-doctrine.md` | Fallback to lupo-database; rehydration-safe structure. |
| README.md, AGENTS.md | Existing onboarding and registry references; updated to link checklist. |
| CHANGELOG.md | 4.0.75 subsections; new subsection appended without overwriting Antigravity/Kiro/etc. |

---

## 3. Actor registration findings from TOON/database

- **lupo_actors:** PK `actor_name`; UNIQUE `actor_id`, UNIQUE `slug`. Fields include `actor_type`, `slug`, `name`, `created_ymdhis`, `updated_ymdhis`, `is_active`, `is_deleted`, `paired_actor_id`, `primary_federation_node_id`, `metadata_json`. Reserved-id doctrine: application supplies `actor_id`; no AUTO_INCREMENT.
- **Registry:** Single JSON file; `actors` array of `{ id, type, slug, dir }`; optional `lead_orchestration`. Required for tooling; can be updated when DB is unavailable (fallback).
- **Seed pattern:** Explicit actor_name, actor_id, actor_type, slug, name, timestamps, paired_actor_id (e.g. 1000 for root), metadata_json. IDE agents use actor_type `ide_agent` in seed.
- **Fallback:** CSV under `lupo-database/lupopedia/csv/lupo_actors.csv` exists; structure should match TOON/table for rehydration. Registry is the minimal fallback; CSV is optional for offline registration.

---

## 4. Checklist location and structure

- **Location:** `lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md` (root docs, discoverable from README and AGENTS).
- **Structure:** Who must register → Prerequisites (root rules, ACT001, reserved-id, fallback doctrine) → Identity fields (from TOON) → Step 1 Registry (required) → Step 2 DB persistence (canonical) → Step 3 Fallback (registry + optional CSV) → Step 4 Validation → Activation boundary → Summary table → References.

---

## 5. Exact files changed

| File | Change |
|------|--------|
| `lupo-docs/ACTOR_REGISTRATION_CHECKLIST.md` | **Created.** Full checklist with LUPOPEDIA HEADERS. |
| `README.md` | Consolidated footer/next_actions; added checklist to next_action and next_actions; in “New agent onboarding,” replaced “Register appropriately” with “Complete the actor registration checklist” and link to checklist; kept AGENTS.md reference. |
| `AGENTS.md` | Added prominent “New IDE or web terminal agent?” paragraph with link to checklist; added outbound edge to checklist (weight 1.0). |
| `CHANGELOG.md` | New subsection “Actor registration checklist (4.0.75)” with three bullets (checklist added, fallback documented, root docs updated); inserted before Antigravity subsection; no existing 4.0.75 content overwritten. |
| `lupo-docs/status/CURSOR_ACTOR_REGISTRATION_CHECKLIST_IMPLEMENTATION_4_0_75.md` | **Created.** This report. |

---

## 6. CHANGELOG updates made

- **Subsection:** “Actor registration checklist (4.0.75)” under 4.0.75.
- **Content:** Canonical checklist from TOON/install/seed/registry; fallback (registry + optional CSV) documented; README and AGENTS updated to point to checklist; existing Antigravity/Kiro/Windsurf/JetBrains entries left unchanged.

---

## 7. Open questions or doctrine risks

- **None.** Checklist does not invent steps; it reflects TOON, install SQL, seed pattern, registry format, and database-offline-fallback doctrine. Optional future work: a small script or template that adds an entry to registry.json (and optionally a CSV row) for a new slug/actor_id, if the project wants to automate the fallback path further.

---

*Cursor IDE (lead orchestration) — 2026-03-14*
