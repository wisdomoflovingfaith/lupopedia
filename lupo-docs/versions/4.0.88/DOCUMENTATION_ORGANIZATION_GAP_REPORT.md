---
lupopedia.headers:
  when_updated: "20260327122537"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/versions/4.0.88/DOCUMENTATION_ORGANIZATION_GAP_REPORT.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.88/DOCUMENTATION_ORGANIZATION_GAP_REPORT.md"
  last_modified_utc: "20260327122537"
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "version_report"
  artifact_kind: "gap_report"
  purpose: "File-by-file contradiction and documentation gap report from 4.0.88 organization pass"
  tags: ["4.0.88", "documentation", "gaps", "contradictions", "organization"]
  namespace: "documentation"
lupopedia.footer:
  last_verified: "20260327122537"
  verified_by:
    identity_type: "actor"
    actor_id: 102
    agent_name_identity: "Cursor IDE Agent (Lead Orchestration)"
    department_id_delta: 0
  verified_via:
    type: "faucet"
    faucet_slug: "cursor"
  orchestrator: "cursor:root"
---

# 4.0.88 Documentation Organization Gap and Contradiction Report

## Scope

This report lists observed documentation drift found during the 4.0.88 organization research pass.

Each finding includes:

- file path
- conflicting statement/pattern
- observed repository reality
- recommended correction

## Findings

| File | Conflict | Observed reality | Recommended correction |
|---|---|---|---|
| `DIRECTORY_STRUCTURE_DOCTRINE.md` | Declares old `/docs/`, `/database/`, and says channels are conceptual/not filesystem-based | Current repository uses `lupo-docs/`, `lupo-database/`, and active file-based `lupo-channels/` with many thread artifacts and validation scripts | Mark file as historical/obsolete or rewrite to current `lupo-*` layout and channel filesystem reality |
| `lupo-docs/DIRECTORY_STRUCTURE.md` | Uses old header model and claims generalized mappings that do not match all current paths | Current runtime has mixed path reality, including significant app code under `lupo-database/lupopedia/content/lupo-app/` | Rewrite using current constants and actual runtime path map; remove stale assumptions |
| `lupo-docs/database/README.md` | Contains duplicated/stale FLARE header blocks, old table-count narrative, and legacy path assumptions | Current database docs are under `lupo-docs/database/lupopedia/tables/active/`; install SQL and generated TOON/JSON surfaces evolved | Rewrite this README as a current DB docs index with authority map and no duplicated legacy headers |
| `lupo-docs/database/lupopedia/README.md` | Legacy FLARE-era content, stale roadmap, weak alignment to current schema generation scripts | Current scripts and install SQL indicate different authority flow for 4.0.88 | Rewrite to align with install SQL authority and derived TOON/JSON outputs |
| `lupo-channels/channel_index.md` | Stale metadata, outdated web path style, old regeneration/maintenance claims not verified in this pass | Active channel dirs and thread dirs exist; tooling references `lupo-channels/<id>/threads/...` heavily | Refresh channel index from current filesystem + DB evidence, update headers to current doctrine |
| `lupo-channels/channel_creation_doctrine.md` | Uses old header model and legacy assumptions mixed with newer slug guidance | Channel filesystem remains mixed (numeric legacy plus slug-first policy text) | Keep doctrine but normalize headers and explicitly separate current behavior from migration target |
| `lupo-channels/42/THREAD_INDEX.md` | Contains stale `version_when_written` and old footer model | Current header doctrine for 4.0.88 is migration phase toward timestamp-first fields | Normalize to current LUPOPEDIA headers/footer model and verify listed thread stats |
| `README.md` (pre-pass state) | Root orientation linked to organization docs but did not provide enough concrete MySQL/file-based authority map | Repository has complex hybrid DB/filesystem model and many lupo-* directories needing explicit map | Add explicit organization + DB/file-system authority links and read order |
| `ORGANIZATION.md` (pre-pass state) | Broad directory summaries without evidence-level classification and stale header fields | Needed explicit canonical/active/generated/legacy/transitional map for all root lupo-* dirs | Fully rewrite with observed classifications and authority boundaries |
| `lupo-docs/ORGANIZATION.md` (pre-pass state) | High-level structure only; insufficient detail on doctrine/version/database/report boundaries | `lupo-docs/` has many sections with mixed freshness and authority levels | Rewrite with section-by-section structure and role map |

## Additional structural gaps (not full contradictions)

1. `lupo-database/lupopedia/mysql/manifest/` has only simple text manifests and no explanatory README.
2. `lupo-database/lupopedia/postgres/` is currently empty and undocumented.
3. `lupo-context/` is present but currently empty at top subfolder level (`actors/`, `channels/`).
4. `lupo-content/` is sparse compared with configured expectations in `lupopedia-config.php`.

## Recommended follow-up order

1. Rewrite stale database readmes in `lupo-docs/database/`.
2. Normalize channel index and thread index docs under `lupo-channels/` to current header model.
3. Add a short `README.md` in `lupo-database/lupopedia/mysql/manifest/` explaining intended usage.
4. Add explicit status notes for sparse/transitional directories (`lupo-context/`, `lupo-content/`, `lupo-chats/`).
