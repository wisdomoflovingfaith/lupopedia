---
lupopedia.headers:
  when_updated: "20260327121457"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_MIGRATION.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_MIGRATION.md"
  last_modified_utc: "20260327121457"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "doctrine"
  artifact_kind: "reference"
  title: "LUPOPEDIA HEADERS Migration"
  purpose: "Compatibility and migration policy from FLARE-era and legacy LUPOPEDIA header variants to the timestamp freshness model"
  tags: ["headers", "migration", "compatibility", "validation"]
  namespace: "governance"
lupopedia.footer:
  last_verified: "20260327121457"
  verified_by:
    identity_type: "actor"
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent (Lead Orchestration)"
    department_id_delta: 0
  verified_via:
    type: "faucet"
    faucet_slug: "cursor"
  orchestrator: "cursor:root"
  next_action:
    - "Continue migrating legacy version_when_written artifacts during normal edits"
    - "Keep validator and generator behavior synchronized to the 4.0.88 compatibility window"
---
# file: LUPOPEDIA HEADERS Migration — delegation: cursor:root — web_path: [http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_MIGRATION.md](http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LUPOPEDIA_HEADERS/LUPOPEDIA_HEADERS_MIGRATION.md)

# LUPOPEDIA HEADERS - Incremental Migration

**Version window:** 4.0.88 compatibility, 4.0.89 enforcement

---

## 1. Principle

Migration to LUPOPEDIA HEADERS remains incremental, not an instant cutover.

- Existing FLARE-headed Markdown and channel artifacts remain valid until touched.
- Existing LUPOPEDIA artifacts that still carry `version_when_written` are compatibility artifacts.
- New or modified artifacts must write `when_updated` and `last_modified_utc` in `lupopedia.headers`.

---

## 2. Version-field migration rule

Compatibility mapping for legacy artifacts:

| Legacy field | Canonical replacement | 4.0.88 behavior | 4.0.89 behavior |
|-------------|-----------------------|-----------------|-----------------|
| `version_when_written` | `when_updated` | Read for compatibility, warn, do not emit | Reject in `lupopedia.headers` |
| `system_version` | none | Warn, remove during rewrite | Reject |
| `lupopedia.version` | none | Warn, remove during rewrite | Reject |
| `last_verified_system_version` | none | Warn, remove during rewrite | Reject |

If a legacy artifact lacks `when_updated`, tooling may derive it from existing timestamp fields or artifact creation metadata during rewrite.

---

## 3. Storage migration path

1. Parse existing FLARE or LUPOPEDIA YAML headers.
2. Normalize into the row-based model: root row -> block rows -> property rows -> repeating child rows.
3. Preserve legacy fields in compatibility reads only when needed for interpretation.
4. Write canonical output using `when_updated`, `file_path_from_root`, and `last_modified_utc`.
5. Optionally rewrite the file so the first line is `---`, followed by YAML blocks, then `---`, then the identity line and body.

---

## 4. Validator behavior

- Legacy FLARE remains accepted during migration.
- LUPOPEDIA artifacts must use canonical block order when rewritten.
- Validators in 4.0.88 warn on `version_when_written` but do not reject it.
- Validators in 4.0.89 reject `version_when_written` inside `lupopedia.headers`.
- Session fields remain part of `lupopedia.session`, not `lupopedia.headers`.
- Channel resolution must support `channel_id` as well as `entity_type` + `entity_id`.

---

## 5. Tooling expectations

- Exporters must build canonical YAML from `lupo_metadata` rows.
- Exporters must not emit `version_when_written`.
- Importers may read `version_when_written` during the 4.0.88 compatibility window.
- Rewriters must emit `when_updated` and `last_modified_utc` on rewritten artifacts.
- Lookup behavior remains entity-aware and channel-aware.

---

## 6. Caveats

- Do not claim the entire repository is already migrated.
- Rendered YAML in files is an export artifact; `lupo_metadata` remains canonical.
- Historical files may still contain deprecated fields until they are touched, but doctrine and tooling must not present those fields as the current write model.
