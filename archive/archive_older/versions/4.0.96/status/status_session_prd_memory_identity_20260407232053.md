---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: documentation
  when_updated: "20260407232053"
  file_path_from_root: "docs/versions/4.0.96/status/STATUS_SESSION_PRD_MEMORY_IDENTITY_20260407232053.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.96/status/STATUS_SESSION_PRD_MEMORY_IDENTITY_20260407232053.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: status_report
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: STATUS — session / PRD / memory / identity work (4.0.96)

## 1. Completed in this workstream (Cursor, actor_id 102)

| Area | What changed |
|------|----------------|
| **PRD 38** | `38_memory_unification.md`: IdGenerator + `created_ymdhis` alignment; DDL for `lupo_memory_nodes` / `lupo_memory_edges`; `MemoryExportService` spec; Phase 1 sync vs Phase 2 queue (section 6.5); outbound edges to PRDs 00, 07, 15, 24, 37; amendment scopes sections 10.1–10.5; IDE prompt fragment section 13. |
| **Install SQL** | `install_new_lupopedia.sql`: replaced minimal `memory_nodes` with full PRD 38 shape; added `memory_edges`; `created_ymdhis` = PK timestamp prefix doctrine in comments. |
| **Runtime** | New `includes/classes/MemoryExportService.php` (PDO_DB, `generateSlug`, export/remove/full/incremental). |
| **4.0.96 status** | `PRD_REVIEW_DISCREPANCIES_AND_IMPROVEMENTS_20260407224750.md`, `THREAD_INDEX.md` (PRD index vs README drift, memory model forks). |
| **PRD 01** | `01_core_identity.md`: `lupo_sessions` columns aligned to install; new **Session Identity Resolution (4.0.96+)** (proxy IP headers, Class C identity, SESSIONID chain, cookieless notes, `metadata` mapping, Crafty vs `App\Auth\Session` tokens); edge to `craftysyntax-reference/functions.php`. |
| **Changelog** | `docs/versions/4.0.96/CHANGELOG.md` entries for PRD 38, PRD 38 edges/phases, PRD 01 session section, and this batch (header ticks via `bin/tick.py`). |

## 2. Not yet done (tracked for follow-up)

- Reconcile **PRD 01 / 07 / 15 / 24 / 37** prose with PRD 38 (`memory_slug` vs derived slug, `lupo_edges` vs `lupo_memory_edges`).
- Regenerate **TOON/JSON** from install after DDL stabilizes (`scripts/generate_toon_from_sql.py` or project standard).
- Implement **resolved IP** in `App\Auth\Session` fingerprint path (PRD 01 documents `get_ipaddress()`; code still used `REMOTE_ADDR` at time of PRD edit).
- **Root `auth_user_id`**: prompt below targets **1**; verify **PRD 00 / seed** (historically **0** for root in some docs) before mass edits.

## 3. Forward prompt — PRD fixes (constitutional + actor/auth/department)

**Execute in Cursor/Claude Code** with actor_id **102**. Below is the operator-supplied prompt (verbatim intent); check repo state before re-running items already merged.

```markdown
# PRD Fixes: Constitutional Compliance + Actor/Auth/Department Model

## Your Role

You are acting as **Cursor IDE Agent** (actor_id 102) with authority to edit PRD files. You apply constitutional rules from PRD 00 and respect the actor/auth/department model.

## Core Identity Model (Important)

**Auth User** = human or system account that authenticates
**Actor** = runtime persona that does work (scoped to department, can learn)
**Agent** = immutable template (filesystem-based)

**Key relationships:**
- Auth users belong to departments (`lupo_auth_user_departments`)
- Actors belong to departments (`lupo_actor_departments`)
- An auth user may act as any actor whose department intersects with their own
- Memory is polymorphic: `owner_type = 'actor'` (learned divergence) OR `owner_type = 'agent'` (baseline template)

**IDE / Terminal Agents (Claude Code, Cursor):**
- Auth user is always **root** (department 0)
- Root auth_user_id should be **1** (not 1000 or 10000) — this aligns with actor_id 1 (WOLFIE) and maintains consistency
- Root auth user has bypass permissions and can act as any actor

## Priority Fixes (from Claude Code audit + LILITH review)

### CRITICAL — Do these first

#### 1. `04_tags_metadata.md` — Add missing edge dimensions to `lupo_edges`

Add these columns to the `lupo_edges` table spec:
```sql
edge_context VARCHAR(32) NOT NULL DEFAULT 'system_generated',
edge_status VARCHAR(32) NOT NULL DEFAULT 'supported',
edge_direction VARCHAR(16) NOT NULL DEFAULT 'unidirectional',
review_reason VARCHAR(64) DEFAULT NULL
```

Also change `weight DECIMAL(5,2)` → `weight_hundredths INT NOT NULL DEFAULT 100`
Change "Foreign reference" prose → "Application-managed reference"

**Note (2026-04-07):** Canonical install uses table `{{prefix}}edges` with `left_object_*` / `right_object_*`, `edge_context`, `edge_status`, `direction` enum, `review_reason`, plus legacy `semantic_weight` DECIMAL / `weight_score` INT. Align PRD text to **install_new_lupopedia.sql**, not a second fictional schema.

#### 2. `01_core_identity.md` — Fix PK naming and session cleanup

- Rename `memory_id` → `actor_memory_id` in `lupo_actor_memory` table (**and** `install_new_lupopedia.sql` + `KairosConsolidationService.php` + JSON/TOON regen)
- Update all references to `memory_id` throughout the file
- Session cleanup: either document hard DELETE exemption OR change to soft delete:
  ```sql
  UPDATE lupo_sessions SET is_deleted = 1, deleted_ymdhis = :now 
  WHERE expires_ymdhis < :cutoff AND is_deleted = 0
  ```

#### 3. DECIMAL → INT hundredths (5 PRDs)

Convert all DECIMAL score/weight/trust columns to INT hundredths in:
- `02_data_model.md`
- `03_truth_knowledge.md`
- `04_tags_metadata.md`
- `09_federation_sync.md`
- `11_analytics_tracking.md`

Pattern:
```sql
-- Before
confidence_score DECIMAL(3,2) NOT NULL DEFAULT 0.50

-- After
confidence_hundredths INT NOT NULL DEFAULT 50  -- 50 = 0.50
```

### MEDIUM — Do these second

#### 4. `27_installer_requirements.md` — Fix leading slash
Change `file_path_from_root: "/docs/prd/27_installer_requirements.md"` → remove leading `/`

#### 5. Replace `version_when_written` with `when_updated` (17 PRDs)

Files: 03, 04, 05_collections, 06, 07, 08_governance, 09, 10, 11, 12, 13, 14, 15_actors, 15_temporal, 23, 36, 37

Change header field name only — preserve the same timestamp value.

#### 6. `08_actors.md` — Fix malformed timestamp
Change `last_modified_utc: '20260331'` → `'20260331000000'`

#### 7. Legacy `.toon` → `json/` paths

Update these files:
- `05_auth_user_actor_agent_transformation.md`
- `18_channel_chat_display.md`
- `36_rose_multi_persona_synthetic_dialog.md`
- `37_kairos_channel_memory_consolidation.md`

Change `database/lupopedia/toon/` → `database/lupopedia/json/`
Remove `.toon` / `.toon.json` extensions

#### 8. `02_data_model.md` — Fix SQL example
Replace `generate_id()` with PHP comment showing `IdGenerator::generate()`

### LOW — Do these third

#### 9. Mark PRD 08 as superseded in indexes
- `PRD_INDEX.md`: add "(SUPERSEDED — see 15_actors.md)" next to 08_actors.md
- `29_project_structure.md`: same annotation

#### 10. Update `PRD_INDEX.md` version banner
Change "Version: 4.0.89" → "Version: 4.0.96"

#### 11. `04_tags_metadata.md` — Fix misleading prose
Change "Foreign reference to lupo_edge_types" → "Application-managed reference to lupo_edge_types"

#### 12. `README.md` — Update PRD count
Either update from "14 files" to actual count OR clarify "14 core namespaces"

## Auth User ID for Root

**Decision:** Root auth_user_id = **1** (not 1000, not 10000)

This aligns with actor_id 1 (WOLFIE) and maintains consistency across the identity system. If no root auth user exists in seed data, add it:

```sql
INSERT INTO lupo_auth_users (auth_user_id, email, password_hash, created_ymdhis, updated_ymdhis)
VALUES (1, 'root@lupopedia.local', '[bcrypt hash]', :now, :now);
```

For IDE agents (Claude Code, Cursor), the auth user is always 1 (root) with department 0 access.

## Memory Model Reminder

- `lupo_memory_nodes.owner_type` = 'actor' (learned, divergent) OR 'agent' (baseline template)
- Actor memory can diverge from Agent memory
- Track divergence with edge type `'diverges_from'`

## Output Format

For each PRD you fix, output:

```
FIXED: {filename}
- Changed: {specific change}
- Status: {compliant / needs review}
```

At the end, output a summary table of all fixes applied.

## Do Not Change

- Do not change `owner_type` enum — it already supports 'actor' and 'agent'
- Do not remove polymorphic memory capabilities
- Do not change the department-scoped act-as model (PRD 05, 15, 25)
- Do not add FOREIGN KEY or AUTO_INCREMENT to any SQL

Begin with the CRITICAL fixes (1-3), then proceed to MEDIUM, then LOW.
```

## 4. Same-session follow-up execution (partial CRITICAL prompt)

| Item | Status |
|------|--------|
| **CRITICAL 1 — `04_tags_metadata.md` `lupo_edges`** | **Done:** Section rewritten to match **`install_new_lupopedia.sql`** (`left_object_*`, `right_object_*`, `edge_context`, `edge_status`, `direction`, `review_reason`, `weight_score`, legacy `semantic_weight` DECIMAL called out). Header: `version_when_written` → `when_updated`. |
| **CRITICAL 2 — `lupo_actor_memory` PK** | **Done in code + install:** `memory_id` → **`actor_memory_id`** in `install_new_lupopedia.sql`, **`KairosConsolidationService.php`**, **`docs/prd/01_core_identity.md`** (table + index names + training cross-ref), **`docs/prd/09_federation_sync.md`**. **`database/lupopedia/json/lupo_actor_memory.json`** updated to match; **`.toon.json`** regenerated via script. |
| **CRITICAL 2 — session cleanup prose** | **Done:** `01_core_identity.md` Session Cleanup uses **soft `UPDATE`** pattern. |
| **CRITICAL 3 — DECIMAL → INT hundredths (5 PRDs)** | **Not done** in this pass — still apply **`02`, `03`, `09`, `11`** (and any remaining `04` examples) per embedded prompt. |
| **`python scripts/generate_toon_from_sql.py`** | **Was run:** regenerated **177** TOONs and **removed 48** prior `json`/`toon` exports not present in current install DDL. **Verify with `git status`** — restore any removed files if your workflow still expected them. |

**MEDIUM / LOW** items in the embedded prompt (17× `version_when_written`, `27` path, `08` timestamp, toon→json paths in four PRDs, `PRD_INDEX`, `README`, etc.) remain **for a follow-up pass**.

---

This output complies with Lupopedia Constitutional Root Rules.
