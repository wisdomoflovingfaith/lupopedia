---
lupopedia.headers:
  header_format_version: "4.2.11"
  path_from_lupopedia_root: docs/prd/01_B-i_COLOR_REGISTRY.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/01_B-i_COLOR_REGISTRY.md
  status: planning
  when_updated: "20260816175729"
  trust_tier: proposed
  questions_toon: null
  memory_toon: memory/development/canonical/1026/08/01_b_color_registry.toon
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/prd-01-b-color-registry
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: prd
  prd_cluster: 01_A_01_B_80_A
  title: "PRD 01_B: Color registry tables (planning)"
  summary: "Planning PRD for GroupColor and ColorName registry tables. HEX5 is not a color column (PRD 90: multi-agent conflict). hex6 for CSV import. GOLD# TBD. No install SQL yet. Color is not a LUP KEY token."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 10000
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: federation
  faucet_actor_id: 102
lupopedia.identity:
  LUPOPEDIA: PRT.LUP
  LUP.KEY: PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
  LUP.HEX: PRT.HEX.000001.000028.000000.ROOT.EN.000001
  LUP.SHORT: PRT.LUP
  LUP.ROOT: PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A
  LUP.OMIT: REGISTERED_SHORT_FORMS_ONLY
  LUP.DEFAULTS: PRT.NAME.000000.000000.ROOT.ROOT.EN.0
  key_specification_version: "4.2.26"
lupopedia.map:
  index: PRT.HEX.000001.000028.000000.ROOT.EN.000001
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/01_B-i_COLOR_REGISTRY.md
  path_from_lupopedia_root: docs/prd/01_B-i_COLOR_REGISTRY.md
  prd_cluster: 01_A_01_B_80_A
  edges_toon: null
  memory_toon: memory/development/canonical/1026/08/01_b_color_registry.toon
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/prd-01-b-color-registry
  questions_toon: null
lupopedia.metadata:
  media_kind: document
  cc_by_name: "Eric Robin Gerdes"
---
# PRD 01_B: Color registry tables

**Status:** planning. Not active. No install SQL. No PHP implementation. No ALTER TABLE.

**Does not override:** PRD 01_A core identity tables, PRD 16_C KEY grammar, PRD 80 database doctrine, PRD 99 color bands.

**Product atom:** `GLOBAL_CURRENT_LUPOPEDIA_VERSION` remains **4.2.11**.

**Display handshake (not a KEY):**

```text
lupopedia poweredby [GroupColor] [ColorName]
```

SQL column names are snake_case: `group_color`, `color_name`.

---

## 0. Why this PRD exists

Color nicknames are two-part identities:

- **GroupColor** -- the cultural first half (White, Blue, Grey, ...)
- **ColorName** -- the registered nickname (aliceblue, GOLDENWOLF, ocean, ...)

Today that mapping lives in a flat CSV. HEX.COLORS already says SQL comes later. This PRD plans the tables. CSV remains the live read path until this PRD moves to development.

Color is **not** a LUP.KEY token. Do not store GroupColor or ColorName inside KEY grammar.

---

## 1. What already exists

| Surface | Job | Do not |
|---------|-----|--------|
| `docs/protocols/hex/PRT.LUP/PRT.LUP.colors.csv` | Live name-to-HEX6 seed (`word`, `hex_color`, `field_type`, `word_registry_id`) | Treat as SQL |
| `docs/protocols/hex/HEX.COLORS.md` | Protocol lookup rules. CSV now. SQL later. Same API. | Put color in KEY |
| `{{prefix}}agent_colors` | Per-actor chat UI colors with `#` prefix | Overload as the color registry |
| Captain's Log 20260816 two-part color identity | Narrative handshake | Treat as doctrine that overrides PRDs |

CSV `word_registry_id` maps to `color_name_id` on a later import. CSV `hex_color` maps to `hex6` (six characters, no `#`).

---

## 2. Two tables

Soft reference only. No FOREIGN KEY. `color_names.group_color` copies the GroupColor string. Application logic joins. The database does not.

### 2.1 `{{prefix}}color_groups`

Closed cultural first half.

| Column | Type | Notes |
|--------|------|-------|
| `color_group_id` | BIGINT | PK. Explicit ID. No AUTO_INCREMENT. |
| `group_color` | VARCHAR(32) | Display token. ASCII. Unique per `protocol_short` among live rows. |
| `protocol_short` | VARCHAR(32) | Example `PRT.LUP`. |
| `sort_order` | INT NOT NULL DEFAULT 0 | Display order. |
| `created_ymdhis` | BIGINT | UTC YYYYMMDDHHIISS. Seed may be 0. |
| `updated_ymdhis` | BIGINT | UTC YYYYMMDDHHIISS. Seed may be 0. |
| `is_deleted` | TINYINT DEFAULT 0 | Soft delete. |
| `deleted_ymdhis` | BIGINT DEFAULT 0 | 0 when live. |
| `federation_node_id` | BIGINT NOT NULL DEFAULT 0 | Local default 0. |

Human label: **GroupColor**.

### 2.2 `{{prefix}}color_names`

Second half. Many ColorName values may share one GroupColor. Many ColorName values may share one color value.

| Column | Type | Notes |
|--------|------|-------|
| `color_name_id` | BIGINT | PK. Explicit ID. CSV `word_registry_id` maps here on import. |
| `protocol_short` | VARCHAR(32) | Example `PRT.LUP`. |
| `group_color` | VARCHAR(32) | Soft copy of GroupColor. Not an FK. |
| `color_name` | VARCHAR(64) | Registry nickname. Seed CSS words lowercase. Crest names such as GOLDENWOLF only after Captain registration. |
| `hex6` | VARCHAR(6) NOT NULL DEFAULT '' | Import from CSV `hex_color`. No `#`. Canonical color format per PRD 90. |
| `gold_mark` | VARCHAR(32) NOT NULL DEFAULT '' | GOLD# grammar TBD. |
| `field_type` | VARCHAR(16) NOT NULL DEFAULT 'node' | Same set as HEX.COLORS: node, actor, group, artifact, mode, protocol. |
| `iso_language` | VARCHAR(8) NOT NULL DEFAULT 'EN' | EN for now. |
| `source_table` | VARCHAR(32) NOT NULL DEFAULT 'seed' | CSV `source_table`. |
| `usage_count` | INT NOT NULL DEFAULT 0 | CSV `usage_count`. |
| `actor_hex` | VARCHAR(6) NOT NULL DEFAULT '808080' | CSV `actor_hex`. |
| `created_ymdhis` | BIGINT | UTC YYYYMMDDHHIISS. Seed may be 0. |
| `updated_ymdhis` | BIGINT | UTC YYYYMMDDHHIISS. Seed may be 0. |
| `is_deleted` | TINYINT DEFAULT 0 | Soft delete. |
| `deleted_ymdhis` | BIGINT DEFAULT 0 | 0 when live. |
| `federation_node_id` | BIGINT NOT NULL DEFAULT 0 | Local default 0. |

Human label: **ColorName**.

Lookup uniqueness (application-enforced until development): `protocol_short` + `color_name` + `field_type` + `iso_language`.

NODE is the fallback `field_type`, same as HEX.COLORS. Do not guess a HEX value. If the name is missing, request creation. Captain approves canonical names.

---

## 3. Deferred columns

| Column | Status |
|--------|--------|
| `hex5` | **Withdrawn as a color column.** PRD 90: HEX5 is AI slang for `MULTI_AGENT_CONFLICT`. Not a five-digit color. Not a registry HEX field. Conflict records are a separate conceptual table. |
| `gold_mark` | Present. GOLD# grammar TBD. |
| `hex6` | Present for CSV import. Canonical color format per PRD 90. This PRD does not rewrite HEX6 across the repo. |

---

## 4. Explicit non-goals (this pass)

- No install SQL
- No seed SQL
- No PHP
- No LRL rewrite
- No repo-wide HEX6 replacement
- No GOLD# grammar invention
- Do not merge into `lupo_agent_colors`
- Do not put GroupColor or ColorName into KEY tokens
- CSV remains the live read path until `status` moves to development

---

## 5. After Captain confirmation

When this PRD is set to development:

1. Follow PRD 90. Do not add a color `hex5` column.
2. Lock GOLD# / `gold_mark` grammar if still required.
3. Add CREATE TABLE to `install_new_lupopedia.sql` (fresh install only; no ALTER until 4.1.0) only after PRD 90 conceptual fields are reconciled.
4. Seed from `PRT.LUP.colors.csv` into `color_names` (`hex6` from `hex_color`). GroupColor assignment for seed CSS words is a separate Captain decision.

Until then, this file is column planning only.

---

## 6. Related

- PRD 90 (doctrine): `docs/prd/90_A-i_COLOR_IDENTITY_DOCTRINE.md`
- PRD 01_A: `docs/prd/01_A-i_CORE_IDENTITY.md`
- PRD 80: `docs/prd/80_A-i_DATABASE_DESIGN_DOCTRINE.md`
- Protocol: `docs/protocols/hex/HEX.COLORS.md`
- Seed CSV: `docs/protocols/hex/PRT.LUP/PRT.LUP.colors.csv`
- Handshake log: `content/federation_node/0/captains_log/origin_stories_architure/2026/08/20260816_the_two_part_color_identity_revelation.md`
