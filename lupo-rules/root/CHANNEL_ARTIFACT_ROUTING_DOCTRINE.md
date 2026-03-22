---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  file_path_from_root: "lupo-rules/root/CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md"
  channel_id: 42
  artifact_type: "doctrine"
  purpose: "Canonical paths and filenames for channel-bound coordination artifacts"
---

# CHANNEL_ARTIFACT_ROUTING_DOCTRINE

## 1. Active coordination root

All **active** channel coordination artifacts MUST live under:

`lupo-channels/{channel_id}/`

For channel 42: `lupo-channels/42/`.

`lupo-docs/status/` is **not** the default sink for active channel work (archival / redirect only).

## 2. Subdirectories

| Path | Use |
|------|-----|
| `broadcasts/` | Channel-wide announcements |
| `content/` | Durable docs tied to channel work |
| `direct/{actor_id}/` | Direct coordination (numeric `actor_id`) |
| `tasks/` | Task artifacts |
| `threads/{thread_id}/` | Thread-bound discussion (`thread_id` = **numeric** `dialog_thread_id` only) |
| `rules/` | Channel-local policy snippets |
| `prompts/` | HERMES-generated execution prompts (handoff to target actors) |

## 3. Thread directory rule

- **MUST** use `lupo_dialog_threads.dialog_thread_id` (integer > 0).
- **MUST NOT** use version strings (`4.0.80`, `4.0.x`) or other non-numeric folder names under `threads/`.

## 4. Filename rule (canonical)

Pattern (UTC):

`YYYYMMDD_HHIISS_actor_purpose.md`

- `YYYYMMDD` — UTC date  
- `HHIISS` — UTC time (24h)  
- `actor` — lowercase actor slug (`[a-z][a-z0-9]*`)  
- `purpose` — lowercase descriptor (`[a-z][a-z0-9_-]*`, hyphens and underscores allowed)  
- Suffix `.md` only; **no uppercase** in the basename.

Reference implementation: `Lupo_Channel_Artifact_Validator` in `lupo-includes/classes/Lupo_Channel_Artifact_Validator.php`.

## 5. Enforcement

- API and router validate thread IDs before path generation.  
- Validators / `lupo-scripts/validate_channel_artifacts.py` flag malformed trees.  
- Role-based broadcast (and optional coordination_action) gates in `channels-api.php`.

## 6. Thread provisioning (Option A — binding for 4.0.x)

**Policy:** A row in **`lupo_dialog_threads`** for the target **`channel_id`** with the given **`dialog_thread_id`** MUST exist **before** any `routing_type=thread` artifact write or API post that attaches to that thread.

- **Router/API:** Do **not** INSERT into `lupo_dialog_threads` as a side effect of a generic thread message POST. Missing row → reject (e.g. thread not found). Filesystem path `threads/{thread_id}/` mirrors DB identity; folder may be created only **after** the row is validated (row-first).
- **Rationale:** `dialog_thread_id` is non–AUTO_INCREMENT; explicit allocation matches DAT003 / reserved-ID discipline. Stops races and orphan folders as authority.
- **Future “new thread”:** If product needs creation, use a **separate**, explicit operation (allocate ID in PHP → INSERT row → then post), not silent create-on-first-POST.
- **Strategy reference:** [ATHENA thread-creation policy](../../lupo-channels/42/threads/1002/20260317_223020_athena_thread-creation-policy.md) (thread 1002). **WOLFIE directive:** `20260317_224500_wolfie_thread-provisioning-option-a.md` (same thread).
- **Seed:** Coordination threads for channel 42 (e.g. 1001, 1002, 1004) SHOULD be present after seed — see `lupo-database/lupopedia/mysql/seed/seed_channel_42_dialog_threads_4.0.80.sql`.

## 7. Thread review body contract (API + filesystem)

Thread-bound **review** artifacts MUST NOT be header-only or metadata shells.

- **API (`routing_type=thread`):** If `message_type` is **`review`** OR `meta.artifact_kind` is **`review`**, substantive markdown **after** optional YAML frontmatter MUST be at least **500** characters with **≥3** `##` headings; else **400** (`THREAD_REVIEW_BODY`). If `message_type` is **`help_response`** OR `meta.artifact_kind` is **`help_response`**, body after frontmatter MUST be **≥200** characters, include a **`#`** title line, and **≥3** `##` headings; else **400** (`THREAD_HELP_RESPONSE_BODY`). Enforced in `Lupo_Channel_Artifact_Validator::validateThreadPostBody` and `Lupo_Channel_Message_Router::handleThreadMessage`.
- **Filesystem / CI:** `python lupo-scripts/validate_channel_artifacts.py --mode enforce` (strict filenames + review + help_response bodies). Path-level PHP: `ChannelArtifactValidator::validateThreadArtifact($path)`.
- **Reference review:** [LILITH channel system review, thread 1001](../../lupo-channels/42/threads/1001/20260317_223420_lilith_channel-system-review.md).

## 8. Prompts directory (`prompts/`)

| Path | Use |
|------|-----|
| `prompts/` | **HERMES-only** handoff files: actionable prompts for target actors (`artifact_kind: hermes_prompt`, `actor_id: 15`) |

- **Naming:** `YYYYMMDD_HHIISS_hermes_prompt_{target_slug}_{purpose}.md` — see [prompts README](../../lupo-channels/42/prompts/README.md).
- **Classification:** HERMES interprets non-prompt artifacts using **`artifact_kind`**, **`message_type`**, and body intent — not filename alone — before emitting prompts.
- **Human directive:** [20260317_230500 wisdomoflovingfaith channel-system directive](../../lupo-channels/42/threads/1001/20260317_230500_wisdomoflovingfaith_channel-system-docs-and-routing-directive.md).
