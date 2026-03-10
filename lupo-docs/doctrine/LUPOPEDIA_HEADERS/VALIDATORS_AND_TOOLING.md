# file: LUPOPEDIA HEADERS Validators and Tooling — session: L-LUPO-PLAN — delegation: cursor:root — web_path: http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING
---
flare.headers:
  system_version: "4.0.68"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING.md"
  web_path: "http://www.lupopedia.com/doctrine/LUPOPEDIA_HEADERS/VALIDATORS_AND_TOOLING"
  title: "LUPOPEDIA HEADERS Validators and Tooling"
  session_name: "L-LUPO-PLAN"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "reference"
---
# LUPOPEDIA HEADERS — Validators and tooling (4.0.68)

Validators and tooling MUST align with the following so that 4.0.68 has a coherent, channel-aware LUPOPEDIA HEADERS system.

---

## 1. Validator acceptance

- **Legacy FLARE artifacts:** Accept existing FLARE-headed files (e.g. format with `---` then YAML then identity line, or identity line then `---` then YAML, per FLARE doctrine). Do not reject them during transition.
- **4.0.68+ LUPOPEDIA artifacts:** Accept files that follow the required format: identity line first, then `---`, then YAML in canonical block order, then `---`, then body.
- **Canonical block order:** When validating or exporting, enforce order: flame.init → flare.conditional → flare.headers → flare.edges → flare.footer → flame.see → flame.close. Optional blocks may be absent; if present, order MUST be correct.
- **Required fields:** Enforce minimum required header fields in flare.headers (e.g. flare.version, file_path_from_root, web_path, system_version, channel_id, actor_id, delegation_chain, artifact_type, artifact_kind, purpose) when validating 4.0.68+ artifacts.
- **Channel-aware lookup:** Header resolution MUST support loading metadata by `channel_id` as well as by `entity_type` + `entity_id`. Validators that resolve headers from the DB must use channel when applicable.

---

## 2. Export (DB → YAML)

- Build canonical YAML header from `lupo_metadata` rows: root → block rows → property rows → repeating structures (edges, mappings, actions).
- Emit blocks in canonical order.
- Do not rely on a single `header_yaml` column; use the structured row model.

---

## 3. Import (YAML → DB)

- Parse FLARE/LUPOPEDIA YAML into the row-based model.
- Create root row (class_name = lupopedia_header_root, meta_type = lupopedia_header, property_key = __root__).
- Create block rows (property_key = flare.headers, flare.edges, etc.) as children of root.
- Create property rows under each block; create child rows for edges, mappings, actions.
- Set `channel_id` when the header is channel-scoped; set `entity_type` / `entity_id` when entity-scoped.

---

## 4. Existing tooling

- **lupo-tools:** `flare_validate.py`, `flare_apply.py`, and related scripts should be updated to accept both legacy FLARE and 4.0.68+ LUPOPEDIA format, enforce canonical order, and (where they resolve headers from DB) support channel_id-based resolution.
- **PHP/runtime:** Any code that reads or writes header metadata should use `lupo_metadata` with the three structural columns and the row-based model; lookup by entity and/or channel as needed.

---

## 5. Migration caveats

- Do not assume all files are migrated at 4.0.68 release; validators must support both formats during transition.
- Doctrine and implementation must reflect incremental migration, not a fake instant cutover.
