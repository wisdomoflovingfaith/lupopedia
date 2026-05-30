# Migration Impact Summary — Corrected Schema vs Import SQL
# Date: 20260406 | Reviewer: claude-code (actor_id 102)
# Source: import_from_old_crafty_syntax.sql (1790 lines, verified)

---

## STEP 1 — What the Import SQL Actually Does (Verified)

This is a factual summary of what `import_from_old_crafty_syntax.sql` does.
No assumptions. Every statement is from the SQL itself.

### Crafty → Lupopedia Table Map (complete, verified)

| Crafty Table | Action | Target Table(s) | Method |
|---|---|---|---|
| livehelp_autoinvite | IMPORTED | lupo_crafty_syntax_auto_invite | INSERT SELECT |
| livehelp_channels | CHARSET/COMMENT only | (dropped) | No data import |
| livehelp_config | JSON into modules | lupo_modules.config_json | UPDATE SET JSON_OBJECT |
| livehelp_departments | IMPORTED | lupo_departments + lupo_department_metadata | INSERT SELECT |
| livehelp_emailque | CHARSET/COMMENT only | (dropped) | No data import |
| livehelp_emails | IMPORTED | lupo_crm_lead_messages | INSERT SELECT |
| livehelp_identity_daily | CHARSET/COMMENT only | (dropped) | No data import |
| livehelp_identity_monthly | CHARSET/COMMENT only | (dropped) | No data import |
| livehelp_keywords_daily | CHARSET/COMMENT only | (dropped) | No data import |
| livehelp_keywords_monthly | CHARSET/COMMENT only | (dropped) | No data import |
| livehelp_layerinvites | IMPORTED | lupo_crafty_syntax_layer_invites | INSERT SELECT |
| livehelp_leads | IMPORTED | lupo_crm_leads | INSERT SELECT |
| livehelp_leavemessage | IMPORTED | lupo_crafty_syntax_leave_message | INSERT SELECT |
| livehelp_messages | CHARSET/COMMENT only | (dropped, empty) | No data import |
| livehelp_modules | CHARSET/COMMENT only | (not directly imported) | No data import |
| livehelp_modules_dep | CHARSET/COMMENT only | (dropped intentionally) | No data import |
| livehelp_operator_channels | CHARSET/COMMENT only | (dropped) | No data import |
| livehelp_operator_departments | IMPORTED | lupo_actor_departments | INSERT SELECT + UPDATE |
| livehelp_operator_history | IMPORTED | lupo_audit_log | INSERT SELECT |
| livehelp_paths_firsts | IMPORTED | lupo_paths | INSERT SELECT |
| livehelp_paths_monthly | IMPORTED | lupo_paths | INSERT SELECT |
| livehelp_qa | IMPORTED | lupo_truth_questions + lupo_truth_answers + lupo_collections + lupo_collection_tabs | INSERT SELECT (WHERE typeof) |
| livehelp_questions | IMPORTED | lupo_crafty_syntax_chat_questions | INSERT SELECT |
| livehelp_quick | IMPORTED | lupo_actor_reply_templates | INSERT SELECT |
| livehelp_referers_daily | IMPORTED | lupo_referers | INSERT SELECT |
| livehelp_referers_monthly | IMPORTED | lupo_referers | INSERT SELECT |
| livehelp_sessions | CHARSET/COMMENT only | (dropped) | No data import |
| livehelp_smilies | CHARSET/COMMENT only | (dropped, replaced by filesystem) | No data import |
| livehelp_transcripts | IMPORTED | lupo_dialog_threads + lupo_dialog_messages | INSERT SELECT |
| livehelp_visit_track | IMPORTED | lupo_visits (is_processed=0, raw) | INSERT SELECT |
| livehelp_visits_daily | IMPORTED | lupo_visits (is_processed=1, synthetic) | INSERT SELECT |
| livehelp_visits_monthly | IMPORTED | lupo_visits (is_processed=1, synthetic) | INSERT SELECT |
| livehelp_websites | IMPORTED | lupo_federation_nodes | INSERT SELECT |
| livehelp_users | IMPORTED | lupo_auth_users → lupo_actors | INSERT SELECT (two passes) |

---

### How livehelp_users is imported (verified from SQL lines 1512–1790)

**Step 1: auth_users (two passes)**
```sql
auth_user_id = 10000 + u.user_id
```
- Pass 1: operators only (isoperator = 'Y'), guarded with NOT EXISTS
- Pass 2: all remaining users (visitors, non-operators), guarded with NOT EXISTS
- `lastaction` (Unix epoch) → `last_login_ymdhis` via `DATE_FORMAT(FROM_UNIXTIME(u.lastaction), '%Y%m%d%H%i%S')`
- NULL/0 lastaction → NULL last_login_ymdhis
- created_ymdhis/updated_ymdhis = `DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S')` (migration moment, not original)
- password_hash = u.password (existing hash, NULL if blank)
- auth_provider / provider_id preserved as-is (NULLIF('',''))

**Step 2: lupo_actors — created from lupo_auth_users, NOT from livehelp_users directly**
```sql
actor_id = au.auth_user_id  (= 10000 + user_id)
actor_source_id = au.auth_user_id
actor_source_type = '{{prefix}}auth_users'
```
- Only operators (isoperator = 'Y') get actor rows
- actor_name = username (or 'actor_{id}' fallback)
- slug = username
- name = display_name (or username)
- actor_type = 'user'
- No IdGenerator::generate() — actor_id is explicit: auth_user_id

**Step 3: operator_departments → actor_departments**
```sql
actor_department_id = recno
actor_id = (10000 + user_id)  -- initial placeholder
```
Then immediately UPDATEd to correct actor_id via JOIN chain:
```sql
UPDATE actor_departments ad
INNER JOIN livehelp_operator_departments od ON ad.actor_department_id = od.recno
INNER JOIN livehelp_users u ON u.user_id = od.user_id
INNER JOIN auth_users au ON au.username = u.username
INNER JOIN actors a ON a.actor_source_id = au.auth_user_id AND a.actor_source_type = '...'
SET ad.actor_id = a.actor_id;
```

**Step 4: Admin actors → department 0**
Crafty `isadmin = 'Y'` operators get:
- `lupo_actor_departments` row with department_id = 0, title = 'System Administrator'
- `lupo_department_roles` row with role_key = 'administrator'
- Guarded with NOT EXISTS

**Step 5: Core actor dept 0 memberships restored (always)**
Hardcoded inserts for actor_ids: 0 (system), 1 (wolfie/captain), 2 (lilith), 111 (countermeasure), 19 (anubis)

**Step 6: Department hybrid actors created**
For each non-zero department:
```sql
actor_id = 280000 + department_id
actor_type = 'human_agent'
actor_source_id = department_id
actor_source_type = 'lupo_departments'
metadata = '{"agent_model":"wolfie","template_actor_id":1,"purpose":"department_hybrid_import"}'
```
Then added to actor_departments with role_key = 'hybrid'.

---

### How transcripts are imported (verified, SQL lines 1394–1471)

`livehelp_transcripts` → **both** `lupo_dialog_threads` AND `lupo_dialog_messages`:
- thread: dialog_thread_id = recno, title from `who` field, created_by_actor_id = 1 (WOLFIE), channel_id = 1
- message: dialog_message_id = recno, message_body = transcript (raw HTML), from_actor_id = 1
- Transcripts are not parsed into per-message rows — one blob per transcript becomes one message
- Both `starttime` and `endtime` are stored as BIGINT as-is (already 14-digit in Crafty Syntax)

---

### Timestamp Conversion Strategy (verified)

| Source type | Conversion | Target |
|---|---|---|
| Crafty Unix epoch (`lastaction`) | `DATE_FORMAT(FROM_UNIXTIME(x), '%Y%m%d%H%i%S')` | `_ymdhis` BIGINT |
| Crafty 14-digit (`starttime`, `endtime`) | Direct copy — already YYYYMMDDHHIISS | `_ymdhis` BIGINT |
| Crafty 8-digit date (`dateof` in visits_daily) | `CONCAT(dateof, '120000')` — append noon | `_ymdhis` BIGINT |
| Crafty 6-digit month (`dateof` in visits_monthly) | `CONCAT(dateof, '01120000')` — first of month noon | `_ymdhis` BIGINT |
| Migration moment | `DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S')` | `_ymdhis` BIGINT |
| Hardcoded historical | `20250101000000` | `_ymdhis` BIGINT |

---

### ID Generation Strategy (verified — no IdGenerator)

The import SQL uses **explicit integer arithmetic**, not IdGenerator::generate():
- Human auth_user_id / actor_id: `10000 + livehelp_users.user_id`
- Department hybrid actor_id: `280000 + department_id`
- Sequential PKs: `SET @seq := 0; (@seq := @seq + 1)` for tables with no natural PK
- Preserve original PK: `recno AS target_id` where PKs are stable (departments, transcripts, etc.)
- operator_user_id in crafty tables: `(10000 + user_id)` remapping

---

## STEP 2 — Impact of Corrected Schema on Import SQL

### Columns Removed from lupo_actors (corrected schema)

The following columns were removed and are referenced in the import SQL:

| Removed column | Import SQL reference | Corrected action |
|---|---|---|
| `metadata text` | `metadata = NULL` (operator actors) | Remove from INSERT column list |
| `metadata text` | `metadata = '{"agent_model":"wolfie"...}'` (dept hybrids) | Move to `metadata_json` |
| `adversarial_role` | `adversarial_role = 'none'` (both inserts) | Remove from INSERT column list |
| `adversarial_oversight_actor_id` | `adversarial_oversight_actor_id = NULL` (both inserts) | Remove from INSERT column list |

These columns are now in satellite tables (`lupo_actor_relationships`, `lupo_actor_pairing`). Crafty has no adversarial or pairing data, so no rows need to be inserted into those tables during migration.

### New Columns Needed in lupo_actors

The corrected schema adds `agent_key` to `lupo_actors`. During import:
- Operator actors: `agent_key = NULL` (humans have no agent template)
- Dept hybrid actors: `agent_key = 'wolfie'` (they are Wolfie-model hybrids)

### New Satellite Tables to Populate During Import

| New table | Source | Action |
|---|---|---|
| `lupo_actor_filesystem` | lupo_actors (post-insert) | INSERT path computed from actor_id |
| `lupo_actor_sync_state` | lupo_actors (post-insert) | INSERT with status = 'pending' |
| `lupo_actor_pairing` | Crafty | NO DATA — Crafty has no pairing data |
| `lupo_actor_relationships` | Crafty | NO DATA — Crafty has no adversarial data |
| `lupo_actor_faucets` (renamed) | Crafty | NO DATA — Crafty has no faucet data |
| `lupo_kairos_observations` | Crafty | NO DATA — Crafty has no KAIROS data |
| `lupo_kairos_memory` | Crafty | NO DATA — Crafty has no memory data |
| `lupo_actor_runtime_state` | Crafty | NO DATA — initialized at first runtime login |
| `lupo_faucet_rules` | Crafty | NO DATA — post-install configuration |
| `lupo_agent_definitions` | Crafty | NO DATA — seeded separately, not from Crafty |
| `lupo_agent_llm_configs` | Crafty | NO DATA — seeded separately |

### lupo_actor_departments UNIQUE Constraint Risk

The corrected schema adds `UNIQUE (actor_id, department_id)` to `lupo_actor_departments`.

The import does multiple INSERT passes into this table:
1. INSERT from livehelp_operator_departments (operator dept memberships)
2. UPDATE to fix actor_ids (safe — no PK conflict)
3. INSERT admins to dept 0 (guarded with NOT EXISTS)
4. INSERT core dept 0 actors (guarded with NOT EXISTS)
5. INSERT dept hybrid actors to their depts (guarded with NOT EXISTS)

Risk: if livehelp_operator_departments contains duplicate (user_id, department) rows (not impossible in legacy data), the UNIQUE constraint on (actor_id, department_id) will reject them.

Fix: Add `ON DUPLICATE KEY UPDATE updated_ymdhis = VALUES(updated_ymdhis)` to the initial INSERT from livehelp_operator_departments.

### Renamed: lupo_agent_faucets → lupo_actor_faucets

The import SQL does not reference lupo_agent_faucets or lupo_actor_faucets. No change needed.

### Renamed PK: governance_overrid_id → governance_override_id

The import SQL does not reference lupo_governance_overrides. No change needed.

---

## STEP 3 — New Schema Tables: What Maps, What Stays Empty

| New Table | Maps from Crafty | Action |
|---|---|---|
| lupo_agent_definitions | NO | Seeded by install; not a Crafty concept |
| lupo_agent_llm_configs | NO | Seeded by install; not a Crafty concept |
| lupo_agent_performance_stats | NO | Populated at runtime |
| lupo_agent_capabilities | NO | Seeded by install (from agent identity.json files) |
| lupo_agent_tools | NO | Seeded by install (from agent tools.json files) |
| lupo_agent_boundaries | NO | Seeded by install (from agent boundaries.json files) |
| lupo_agent_memory_config | NO | Seeded by install (default config per agent) |
| lupo_actor_filesystem | YES | Computed paths for each imported actor |
| lupo_actor_sync_state | YES | Initialize 'pending' for each imported actor |
| lupo_actor_pairing | NO | Crafty has no pairing data |
| lupo_actor_relationships | NO | Crafty has no adversarial/oversight data |
| lupo_actor_versions | NO | No version history to import |
| lupo_actor_runtime_state | NO | Populated at first login |
| lupo_actor_runtime_events | NO | Populated at runtime |
| lupo_faucet_rules | NO | Post-install configuration |
| lupo_pairing_rules | NO | Post-install configuration |
| lupo_kairos_observations | NO | No Crafty equivalent |
| lupo_kairos_memory | NO | No Crafty equivalent |
| lupo_department_capabilities | NO | Post-install configuration |
| lupo_identity_layers | NO | Seeded at install (2 fixed rows) |
| lupo_identity_context | NO | Populated at runtime |
| lupo_versions | NO | Seeded at install |
| lupo_edge_types | PARTIAL | System edge types seeded at install |

---

## STEP 4 — Verification Checklist

- [ ] Fresh install works: lupo_actors PK is actor_id — no actor_name PK conflict
- [ ] Import works: metadata/adversarial columns removed from INSERT statements
- [ ] No data loss: metadata_json captures dept hybrid JSON; actor identity fully preserved
- [ ] Deterministic IDs: auth_user_id = 10000+user_id, actor_id = auth_user_id, dept hybrids = 280000+dept_id
- [ ] Correct timestamp conversion: Unix → ymdhis via DATE_FORMAT(FROM_UNIXTIME()), 14-digit → direct copy
- [ ] UNIQUE constraint safe: ON DUPLICATE KEY UPDATE added to actor_departments initial INSERT
- [ ] Satellite tables populated: actor_filesystem and actor_sync_state inserted for each imported actor
- [ ] Soft delete: all imported rows have is_deleted = 0, deleted_ymdhis = NULL
- [ ] Core actor dept memberships restored: wolfie(1), lilith(2), system(0), anubis(19), countermeasure(111)
- [ ] Human ID range: all Crafty operator actors have actor_id >= 10000

---

Reviewer: claude-code actor_id 102 | Date: 20260406
