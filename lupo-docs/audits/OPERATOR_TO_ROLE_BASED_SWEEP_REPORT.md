# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/audits/OPERATOR_TO_ROLE_BASED_SWEEP_REPORT.md"
  file_hash: "a4e894d5da7faac8e9a907b2fd03914becf862d1f4484bc6970e46dddc150496"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\audits\OPERATOR_TO_ROLE_BASED_SWEEP_REPORT.md"
  file_hash: "94ecf35c470465fca5664b1fdc4a748edfb5490ecc893e5dba34008b4b2c622f"
  file_path_from_root: "lupo-docs\audits\OPERATOR_TO_ROLE_BASED_SWEEP_REPORT.md"
  file_hash: "1faccef980673fca226dd9c5dd73e74a14b5010e12f40b75274a265e7c21638e"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Operator-to-Role-Based Sweep Report"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "audits", "operator_to_role_based_sweep_reportmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Operator-to-Role-Based Sweep Report

## 1. Files Where Operator Logic Was Removed or Updated

| File | Changes |
|------|--------|
| **livehelp_js.php** | Replaced `role_key IN ('captain','monitor','operator')` with `('captain','monitor','administrator')`; comment updated (no lupo_operator_*). |
| **image.php** | Replaced `role_key IN ('captain','monitor','operator')` with `('captain','monitor','administrator')`; comment updated. |
| **install_wizard_classes.php** | Reserved channel 1 set to 'Administration'. `createOperatorChannels` now uses **lupo_actor_channel_roles** (role_key='captain'), creates personal channels with channel_name = name + "'s Channel", assigns captain on channel_id=1 for livehelp_users.isadmin='Y'. `ensureOperatorChannels` uses lupo_actor_channel_roles. Reserved channels also get a row in lupo_actor_channel_roles for system actor (0). |
| **install.php** | Wording: "operator channels" → "personal channels and captain roles"; "Operator channels created" → "Personal channels created"; step descriptions updated. |
| **lupo-includes/modules/channels/channels-controller.php** | All **lupo_channel_roles** usage replaced with **lupo_actor_channel_roles**; **channel_role_id** → **actor_channel_role_id**, **role_type** → **role_key**. Comments updated; "operator" → "staff" / "actor" where appropriate. |
| **lupo-includes/classes/AdminUsersHandler.php** | Table **channel_roles** → **actor_channel_roles**; **channel_role_id** → **actor_channel_role_id**; **role_type** → **role_key** for channel 1 (admin channel) role. |
| **lupo-includes/themes/default/layouts/main_layout.php** | Comment: "channel operator interface" → "channel staff interface". |
| **README.md** | "operator sessions" → "staff (captain/administrator/monitor) sessions"; uploads path "operators" removed. |
| **lupo-database/migrations_legacy/migration_operator_to_actor_channel_roles.sql** | One-time migration. Updates channel 1 to Administration; copies lupo_channel_roles into lupo_actor_channel_roles for existing DBs. |

**Unchanged by design**

- **import_from_old_crafty_syntax.sql**: Keeps legacy table names (livehelp_operator_*, etc.) for ALTER/comment and data source; keeps `isoperator='Y'` for auth/actor import; no INSERT into lupo_operators; column `operator_user_id` in lupo_crafty_syntax_auto_invite retained (legacy column name).
- **lupo-database/migrations_legacy/** and **old_crafty_syntax_3_7_5_start.sql**: Legacy; not modified.
- **lupo-docs/** (notes_from_legacy, IMAGE_PHP_MIGRATION, etc.): Historical; not edited.

---

## 2. Corrected import_from_old_crafty_syntax.sql

The importer was already aligned in a previous task:

- **Auth users:** First INSERT from livehelp_users WHERE isoperator='Y'; second INSERT for remaining users. Idempotent. All timestamps CAST(... AS SIGNED), BIGINT UTC.
- **Actors:** Only Crafty operators (isoperator='Y') become lupo_actors; actor_id = auth_user_id; actor_source_id/auth_user_id, actor_source_type = 'lupo_auth_users'; created_ymdhis/updated_ymdhis via CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED). Idempotent.
- **Roles:** Importer does not assign roles; wizard assigns them.
- **Department mapping:** UPDATE lupo_actor_departments.actor_id retained.
- **No UNSIGNED;** no lupo_operators.

No further edits were made to the importer in this sweep.

---

## 3. Updated Installer Logic

### Personal channels for imported Crafty operators

- **Where:** `InstallWizardChannels::createOperatorChannels()` in **install_wizard_classes.php**.
- **Behavior:** Selects actors from lupo_actors where actor_source_type = 'lupo_auth_users'. For each: creates a row in **lupo_channels** with channel_name = **name + "'s Channel"**, then inserts into **lupo_actor_channel_roles** with role_key = **'captain'**. Uses explicit channel_id and actor_channel_role_id (no AUTO_INCREMENT).

### Global admin channel (channel_id = 1)

- **Reserved channels:** In `createReservedSystemChannels()`, channel_id=1 is defined as key **administration**, name **Administration**, desc "Global admin channel (channel_id = 1)."
- **Captain on channel 1:** After creating personal channels, for each livehelp_users row where isadmin='Y', resolves auth_user_id (actor_id) and inserts into **lupo_actor_channel_roles** (actor_id, channel_id=1, role_key='captain') if not already present.

### Captain role assignments

- **Personal channels:** One captain per created channel in lupo_actor_channel_roles.
- **Channel 1:** One captain per Crafty admin (isadmin='Y') in lupo_actor_channel_roles.
- **Reserved channels (0, 1, 42, 51):** System actor (0) is still assigned captain in both lupo_channel_roles and lupo_actor_channel_roles for channels 1, 42, 51 so role-based checks see them.

---

## 4. Required TOON Updates

- **None.** TOONs for lupo_auth_users, lupo_actors, lupo_actor_channel_roles, lupo_channels already match the schema used. No lupo_operators TOON exists. No TOON files were modified (doctrine: TOONs are read-only for Cursor; generated by script).

---

## 5. Required install_new_lupopedia.sql Updates

- **None.** lupo_operators is not in the install file. lupo_actor_channel_roles and lupo_channels are already defined. Channel 1 is created by the wizard (reserved channels) with name 'Administration' in code; install does not need to create channel 1 by name.

---

## 6. Migration SQL File for Live DB

- **File:** `lupo-database/migrations_legacy/migration_operator_to_actor_channel_roles.sql`
- **Contents:**
  1. UPDATE lupo_channels SET channel_key/channel_slug/channel_name/description/updated_ymdhis for channel_id=1 to Administration (BIGINT UTC timestamp).
  2. INSERT into lupo_actor_channel_roles from lupo_channel_roles where no matching (actor_id, channel_id, role_key) exists; actor_channel_role_id generated as MAX(actor_channel_role_id)+row_num; role_type → role_key.

Run once on existing databases that previously used lupo_channel_roles for permission checks. New installs get roles from the wizard in lupo_actor_channel_roles only.

---

## 7. Confirmations

- **actor_id = auth_user_id:** Enforced in importer for imported humans (lupo_actors INSERT).
- **Operator-only actor import:** Preserved (only isoperator='Y' get lupo_actors rows).
- **UNSIGNED removed:** No UNSIGNED in importer; migration uses SIGNED.
- **Timestamps BIGINT UTC:** Importer and wizard use gmdate('YmdHis') or CAST(DATE_FORMAT(UTC_TIMESTAMP(), '%Y%m%d%H%i%S') AS SIGNED).
- **No lupo_operators logic:** No INSERT or reference to lupo_operators; no operator_id; permissions use lupo_actor_channel_roles (role_key: captain, administrator, monitor).
