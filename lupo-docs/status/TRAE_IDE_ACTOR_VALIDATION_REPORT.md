# LUPOPEDIA HEADERS (replaces FLARE) — see http://www.lupopedia.com/status/TRAE_IDE_ACTOR_VALIDATION_REPORT

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "status"
  file_path_from_root: "docs/status/TRAE_IDE_ACTOR_VALIDATION_REPORT.md"
  web_path: "http://www.lupopedia.com/status/TRAE_IDE_ACTOR_VALIDATION_REPORT"
  last_modified_utc: "20260306"
  system_version: "4.0.57"
  channel_id: 42
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "report"
  artifact_kind: "verification"
  purpose: "Validate Trae IDE actor_id and faucet assignment; prevent registry and ID collisions."
  mood_rgb: "4169E1"
  traits: ["v4.0.57", "validation", "trae_ide", "registry", "windsurf"]
  tags: ["trae", "actor_id", "faucet", "validation", "registry", "4.0.57"]
  agent_name_identity: "Windsurf IDE Agent"
  lupo_agent: "windsurf"

lupopedia.footer:
  last_verified: "20260306"
  last_verified_by: "windsurf"
---

# Trae IDE Actor Registration Validation Report

**Date:** 2026-03-06  
**Author:** Windsurf (1002)  
**Directive:** Captain Wolfie (10000) — Validate Trae IDE's actor_id assignment and ensure no registry conflicts.  
**Version:** 4.0.57

---

## 1. Trae IDE Actions Extracted from CHANGELOG

**Source:** `CHANGELOG.md` (4.0.57 section).

| Item | Value |
|------|--------|
| **actor_id chosen by Trae** | **10001** |
| **agent_faucet_id chosen** | **1** |
| **Claimed paths** | `lupo-database/lupopedia/actors/` and `lupo-database/lupopedia/actors/faucets/` (directory structures and configuration files) |
| **Other** | `seed_initial_collection.sql` (Initial Menu Collection) — not in scope for actor/faucet validation |

**CHANGELOG_OLDER / CHANGELOG_ARCHIVE:** Not required for extraction; Trae appears only in current CHANGELOG [4.0.57]. No separate CHANGELOG_OLDER file found in repo.

---

## 2. actor_id 10001 — Validation Against Canonical Registry

**Registry path:** `lupo-database/lupopedia/actors/actor_id/registry.json`

### 2.1 Current registry contents (relevant IDs)

- **System/agents:** 0, 1, 2, 3, 4, 5, 19, 25, 420, 1000, 1001, 1002, 1003, 1004, 1005, 1006, 1007  
- **Human:** 10000 (root-captain-10000), 10420 (test-banned-user)

**10001 is not listed in registry.json** — but doctrine and migrations reserve it.

### 2.2 Conflict: actor_id 10001 is reserved for BANNED_TEST_HUMAN_10001

**Evidence:**

- **`database/migrations/fix_identity_collision_4.0.29.sql`** (lines 33–46):  
  `actor_id 10001` is the **Banned Human Test Identity**. Renamed from `stonedwolfie` to `BANNED_TEST_HUMAN_10001` / `banned-test-human-10001` / `banned.test.human.10001@banned.local`.
- **`IDENTITY_COLLISION_FIX_4.0.29.md`:** Actor 10001 = `stonedwolfie` (Banned Human); after fix = BANNED_TEST_HUMAN_10001.
- **`lupo-database/lupopedia/mysql/seed/seed_registry_additional_csv_entities_4.0.45.sql`** (line 36):  
  Entity `(9021001, 'actor', 10001, 10001, ... 'user-10001', 'Stoned Wolfie', ...)` — legacy user / banned test.
- **`lupo-docs/status/INITIALIZATION_PROMPT_4_0_21.md`:** "Stoned Wolfie human (actor_id 10001 for fresh install)".
- **CHANGELOG_ARCHIVE / doctrine:** 10001 is documented as banned test human; 10000–10001 range for human users; 10001 = BANNED_TEST_HUMAN_10001.

**Conclusion:** **actor_id 10001 is invalid for Trae IDE.** It is reserved for the banned test human identity (BANNED_TEST_HUMAN_10001). Assigning Trae to 10001 would collide with that reserved use.

### 2.3 Correct range for Trae IDE (IDE agent)

- IDE agents in registry use **1000–1007** (KIRO 1000, Windsurf 1001, Cursor 1002, Antigravity 1003, Warp 1004, Cascade 1005, Gemini-CLI 1006, Codex 1007).
- **Next free IDE slot:** **1008** (no existing entry in registry.json for 1008).
- Trae IDE is an IDE agent; it should use an ID in the **1000–1999** IDE range, not the human/banned range 10001.

---

## 3. Directory Structure Validation

**Expected (if Trae had created per CHANGELOG):**  
`lupo-database/lupopedia/actors/actor_id/10001/` with identity.json, profile.json, meta/flare.json, config/system.json, and optionally faucet-related content.

**Actual:**

- **`lupo-database/lupopedia/actors/actor_id/`** — no directory **10001** found.  
  Present: 0, 1, 2, 3, 4, 5, 19, 25, 420, 1000, 1001, 1002, 1003, 1004, 1005, 1006, 1007, 10000, 10420, plus `plans`, `meta`, `relationships.csv`, etc.
- Therefore **Trae’s actor directory for 10001 does not exist on disk** in this repo. Either it was never committed, or it was created under a different path. No relocation is needed for a non-existent directory; when Trae is assigned a valid ID (e.g. 1008), the directory should be created as `actor_id/1008/` with the required files.

**If a directory had existed at 10001:** It would need relocation to the correct actor_id (e.g. 1008) and FLARE headers updated to the new ID.

---

## 4. Faucet Validation

**Paths checked:**

- `lupo-database/lupopedia/actors/faucets/by_actor.json` — exists; entries for (19, 42) → 6 and (2, 42) → 7.
- `lupo-database/lupopedia/actors/faucets/6/faucet.json` — ANUBIS FLARE Ingestion (actor_id 19).
- `lupo-database/lupopedia/actors/faucets/7/faucet.json` — Lilith Flame Expert (actor_id 2).

**Trae’s faucet (agent_faucet_id 1):**

- **`lupo-database/lupopedia/actors/faucets/1/`** — **does not exist.** No `faucet.json` and no directory for ID 1.
- **agent_faucet_id 1** does not collide with existing file-based faucets (6 and 7). If Trae’s faucet were to be added, the next free faucet ID could be 1 (or 8 if reserving 1 for another use). The **problem is not faucet_id 1** but the **actor_id (10001)** that would be stored in that faucet.

**Conclusion:** No faucet directory exists for Trae. Faucet_id 1 is unique among current file-based faucets. Any new Trae faucet must reference a **valid** actor_id (e.g. 1008), not 10001.

---

## 5. registry.json — Correction Needed

- **Current state:** 10001 is not in `registry.json`. Doctrine and migrations still treat 10001 as BANNED_TEST_HUMAN_10001.
- **If Trae were added as 10001:** That would contradict doctrine and seed/migration use of 10001 for the banned test human.
- **Recommendation:** **Do not add Trae to the registry as 10001.** If Trae is to be registered, add an entry for the **next free IDE slot (1008)** with type `agent`, slug e.g. `trae-ide`, dir `actors/1008` (or per existing registry `dir` convention).

---

## 6. Summary Table

| Check | Result | Notes |
|-------|--------|--------|
| Trae’s chosen actor_id | **10001** | From CHANGELOG |
| actor_id 10001 valid? | **NO** | Reserved for BANNED_TEST_HUMAN_10001 (fix_identity_collision_4.0.29, seeds, doctrine) |
| IDE slot for Trae | **1008** | Next free in 1000–1007 range |
| Directory actor_id/10001 exists? | **NO** | Not present in repo |
| Faucet id 1 collision? | **NO** | Only 6 and 7 exist; 1 is free |
| Faucet dir for Trae (id 1) exists? | **NO** | No `faucets/1/` |
| registry.json lists 10001? | **NO** | 10001 not in registry (doctrine reserves it for banned human) |
| registry.json needs correction for Trae? | **YES (if adding Trae)** | Add Trae as **1008**, not 10001 |

---

## 7. Recommended Next Steps (Do Not Apply Until Authorized)

1. **Assign Trae IDE actor_id 1008** (next free IDE slot). Do not use 10001.
2. **Create directory** `lupo-database/lupopedia/actors/actor_id/1008/` with:
   - `identity.json`
   - `profile.json`
   - `meta/flare.json`
   - `config/system.json`
   - (Optional) faucet-related structure per project convention.
3. **Update `lupo-database/lupopedia/actors/actor_id/registry.json`** — add one entry:  
   `{ "id": 1008, "type": "agent", "slug": "trae-ide", "dir": "actors/1008" }` (or consistent with existing `dir` format).
4. **If Trae FLARE Expert faucet is created:** Use **agent_faucet_id 8** (next after 7) to avoid confusion with Trae’s original choice of 1; create `faucets/8/faucet.json` with `actor_id: 1008`. Add entry to `by_actor.json`: `{ "actor_id": 1008, "domain_id": 42, "agent_faucet_id": 8 }`.
5. **FLARE headers** in any Trae-created or -updated files: set `actor_id: 1008` (and optionally `agent_name_identity: "Trae IDE"`). Ensure `system_version` and see-URL format follow v4.0.57+.
6. **Do not** add 10001 to registry for Trae; do not create `actor_id/10001` for Trae. Reserve 10001 for BANNED_TEST_HUMAN_10001 per existing doctrine and migrations.

---

## 8. Corrective Actions (Applied per Captain Wolfie Authorization)

| # | Action | Status |
|---|--------|--------|
| 1 | Assign Trae **actor_id 1008** | **Applied** — identity.json, profile.json, meta/flare.json, meta/schema.json, config/system.json created under `actor_id/1008/` |
| 2 | Create **actor_id/1008/** with identity.json, profile.json, meta/flare.json, config/system.json | **Applied** |
| 3 | Add registry entry for **id 1008**, type agent, slug trae-ide, dir actors/1008 | **Applied** — `registry.json` updated |
| 4 | Create Trae faucet: **agent_faucet_id 8**, **actor_id 1008** in faucets/8/faucet.json | **Applied** — Trae FLARE Expert faucet created |
| 5 | Add by_actor.json entry (1008, 42, 8) | **Applied** |
| 6 | Trae-related FLARE headers use **actor_id 1008** | **In effect** — new files use 1008; no 10001 references for Trae |

**Corrections applied:** 2026-03-06 (Cursor, per Eric/Captain Wolfie authorization). Trae IDE is registered at actor_id 1008 with faucet 8 on domain 42. actor_id 10001 remains reserved for BANNED_TEST_HUMAN_10001.

---

**Report generated:** 2026-03-06  
**Windsurf (1002)** — Trae IDE actor and faucet validation complete.  
**Corrections applied:** 2026-03-06 — Cursor (1003), per Captain Wolfie (10000) authorization.
