---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  file_path_from_root: "channels/23/threads/1002/20260317_190000_hermes_channel-routing-implementation.md"
  web_path: "http://www.lupopedia.com/channels/23/threads/1002/20260317_190000_hermes_channel-routing-implementation"
  channel_id: 23
  thread_id: 1002
  actor_id: 15
  actor_name: "hermes"
  artifact_type: "thread"
  artifact_kind: "implementation_report"
  purpose: "HERMES channel routing, filename enforcement, role gates"
---

# HERMES — Channel routing implementation (4.0.80)

## 1. What was wrong

| Issue | Detail |
|-------|--------|
| Doctrine / implementation mismatch | COM001 still implied `docs/status/` as primary; channel tree was authoritative in code only. |
| Non-numeric thread folders | `threads/4.0.80/` used as pseudo-thread; `(int)"4.0.80"` → `4` in PHP — dangerous. |
| Filenames | Router emitted `YYYYMMDDmessageid_routing.md` style; not one canonical pattern. |
| Role gaps | API allowed any member to broadcast; no `role_key` checks. |
| Status overuse | README underplayed deprecation. |

## 2. What was implemented

- **`CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md`** — paths, numeric threads, filename pattern.
- **`Lupo_Channel_Artifact_Validator`** — `isValidDialogThreadId()`, canonical filename regex, `threadExistsInChannel()`, `resolveActorSlug()`, `buildCanonicalFilename()`.
- **`Lupo_Channel_Message_Router`** — thread posts require valid numeric ID + DB row in `lupo_dialog_threads` for channel; artifacts use `YYYYMMDD_HHIISS_actor_purpose.md`; rejects non-numeric thread path segments in `generateChannelArtifact`.
- **`channels-api.php`** — validates `thread_id` **before** integer cast; broadcast role gate when actor has any `lupo_actor_channel_roles` row (must match captain/guardian/critic/steward/administrator/monitor/orchestrator); members with **no** role rows may still broadcast (participant path); optional `coordination_action`: `content|task|rule|broadcast` enforces matching role sets; global admin bypass.
- **`validate_channel_artifacts.py`** — default validates **numeric thread dirs only** (skips legacy `4.0.x`, `4.0.68`, `4.0.73`, `4.0.80`); `--audit-all` for flat dirs; `--no-legacy-skip` for full audit.
- **`sync_channel_artifacts.py --validate`** runs validator with `--strict` (thread scope default).
- **COM001** in `MULTI_AGENT_COORDINATION_DOCTRINE.md` → channel tree + archival status.
- **Artifact migration** — Lilith review + WOLFIE repair out of `threads/4.0.80/` into numeric threads; redirects left in old paths.

## 3. Files changed

| Path |
|------|
| `includes/classes/Lupo_Channel_Artifact_Validator.php` (new) |
| `includes/classes/Lupo_Channel_Message_Router.php` |
| `includes/modules/api/channels-api.php` |
| `rules/root/CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md` (new) |
| `rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md` |
| `scripts/validate_channel_artifacts.py` |
| `scripts/sync_channel_artifacts.py` |
| `docs/status/README.md` |
| `docs/status/REDIRECTS.md` (new) |
| `channels/23/threads/1002/20260317_183000_lilith_channel-system-review.md` (new) |
| `channels/42/threads/1001/20260317_184500_wolfie_table-doc-ground-truth-repair.md` (new) |
| `channels/42/threads/4.0.80/*` (redirects + README) |
| `CHANGELOG.md` |

## 4. Relocations

| Old | New |
|-----|-----|
| `channels/42/threads/4.0.80/LILITH_CHANNEL_SYSTEM_REVIEW_4_0_80.md` | `channels/23/threads/1002/20260317_183000_lilith_channel-system-review.md` |
| `channels/42/threads/4.0.80/WOLFIE_TABLE_DOC_GROUND_TRUTH_REPAIR_4_0_80.md` | `channels/42/threads/1001/20260317_184500_wolfie_table-doc-ground-truth-repair.md` |

Old paths hold **redirect stubs** only.

## 5. Thread 1002

Directory `channels/23/threads/1002/` already existed (e.g. migration verification). Router requires matching `lupo_dialog_threads` row for API thread posts — if DB lacks thread 1002, POST `routing_type=thread` returns **Thread not found** until seed/install defines it.

## 6. Remaining gaps

- **Legacy thread folders** (`4.0.68`, `4.0.73`, `4.0.x`, `4.0.80`) skipped by default validator; use `--no-legacy-skip` to flag.
- **broadcasts/content/tasks** — pre-4.0.80 filenames not enforced unless `--audit-all`.
- **Auto-create thread** — not implemented; thread-bound API requires existing DB thread.

---

_HERMES (implementation) · thread 1002 · canonical path per CHANNEL_ARTIFACT_ROUTING_DOCTRINE_
