---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: documentation
  when_updated: "20260408001717"
  file_path_from_root: "docs/versions/4.0.96/status/STATUS_MEMORY_ARCHIVE_OPTION_B_20260408001717.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.96/status/STATUS_MEMORY_ARCHIVE_OPTION_B_20260408001717.md"
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
# STATUS — Memory archive Option B (observations only)

## Observations

- **Section numbering:** Canonical PRD 38 already had **§7 Filesystem Structure**; the prompt’s “§7 after §6” was interpreted as **new §8** after that filesystem section, with **§§8–13 → §§9–14** and amendments **§10 → §11** to avoid collision.
- **`created_ymdhis` vs path:** Export layout follows **`created_ymdhis`** (and §4.0’s PK-prefix rule). Option B only works cleanly if the **archived** row’s **`created_ymdhis`** is updated to match the **new** **`memory_node_id`** prefix; the doc states that explicitly.
- **Two PRDs with prefix `24_`:** `python scripts/generate_prd_shorthands.py --prd 24 --force` regenerates **both** `24_actor_onboarding_flow` and `24_cli_interface_prd` shorthands; use **`--stem 24_cli_interface_prd`** when only the CLI PRD should be refreshed.
- **CLI vs code:** `memory archive` / `memory restore` are **specified** in PRD 24; **MemoryCommands** / **`lupo.php`** routing are still **next_action** items — no runtime implementation was added in this pass.
- **Edge type registry:** **`archived_to`** and **`restored_from`** are documented for CLI **`edges add`**; validate against **`install_new_lupopedia.sql`** / edge-type seed when implementing (no schema edit in this task).
- **Id width:** PHP helpers assume string-safe handling for **18-digit** ids; **`toLongTermId`** returns unchanged for strings shorter than four characters (covers non-timestamp ids).

## Suggestions

- Add **integration tests** when implementing archive: original soft-deleted, new row inserted, **`archived_to`** edge, mirror under **`memory/1026/`** (or appropriate archive year), **`restore`** round-trip.
- Consider a **single transaction** (or explicit saga) for archive: failure after soft-delete but before insert should be recoverable.
- Document **retention policy** (who may run **`--older-than`**, dry-run in CI) in operator docs or PRD 14.
- Reconcile **slug stem** in mirror filename: archived node may still carry **human-readable** date fragments from original payload; confirm **`generateSlug`** rules so filenames do not confuse operators (same PRD §6.2).
- Fix **malformed YAML** in **`35_mobile_native_app_separation.md`** and **`40_versioning_doctrine.md`** (broken `tags` / list under `status`) so the shorthand generator stops warning on every run.
