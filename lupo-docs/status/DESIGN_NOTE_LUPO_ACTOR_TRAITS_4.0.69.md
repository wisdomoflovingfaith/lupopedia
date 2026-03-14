---
lupopedia.headers:
  lupopedia.version: "4.0.69"
  lupopedia.schema: "documentation"
  system_version: "4.0.69"
  file_path_from_root: "lupo-docs/status/DESIGN_NOTE_LUPO_ACTOR_TRAITS_4.0.69.md"
  web_path: "http://www.lupopedia.com/status/DESIGN_NOTE_LUPO_ACTOR_TRAITS_4.0.69"
  last_modified_utc: "20260311"
  channel_id: 42
  channel_name: "Lupopedia Development (general)"
  actor_id: 1003
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "design_note"
  artifact_kind: "schema_decision"
  purpose: "Decision to add lupo_actor_traits as a real table for intrinsic actor constraints; narrow scope, no channel-scoped traits."
  tags: ["traits", "actors", "schema", "4.0.69", "cursor"]
lupopedia.session:
  session_id: "L-LUPO-ROOT-CURSOR"
  channel_id: 42
  actor_id: 1003
lupopedia.footer:
  last_verified: "20260311"
  last_verified_by: "cursor"
---
# file: Design Note — lupo_actor_traits (4.0.69) — session: L-LUPO-ROOT-CURSOR — delegation: cursor:root — web_path: http://www.lupopedia.com/status/DESIGN_NOTE_LUPO_ACTOR_TRAITS_4.0.69

# Design Note: lupo_actor_traits (v4.0.69)

## Decision

**Approved:** Add **`lupo_actor_traits`** as a **real table** in the canonical schema. Intrinsic actor constraints (e.g. “dialog-only emotional”, “timekeeper kernel”) are stored here. Traits remain **actor-scoped only**; channel-specific permissions stay in **`lupo_actor_channel_roles`**.

## Rationale

- **Gap:** Intrinsic actor constraints are currently implicit in `metadata_json`, seeds, or docs; enforcement is weak (e.g. blocking a non-dialog actor from sending emotional `mood_rgb`).
- **Scope:** One table for **actor-level** traits only. Do not overload with channel roles or transient tasks.
- **Doctrine:** No foreign keys; BIGINT UTC timestamps (`created_ymdhis`, `updated_ymdhis`, `deleted_ymdhis`); explicit IDs (reserved-ID doctrine); soft delete (`is_deleted`, `deleted_ymdhis`). Optional `metadata` text for opaque extension.

## Schema (minimum)

| Column | Type | Purpose |
|--------|------|--------|
| `actor_trait_id` | bigint NOT NULL | Primary key; explicit ID from registry or allocator. |
| `actor_id` | bigint NOT NULL | Actor this trait belongs to. |
| `trait_key` | varchar(128) NOT NULL | Canonical trait identifier (e.g. `EMOTIONAL_DIALOG_AUTHORIZED`, `SCHEMA_ARCHITECT`). |
| `trait_value` | varchar(512) DEFAULT NULL | Optional value or variant. |
| `created_ymdhis` | bigint NOT NULL | Creation time (UTC YmdHis). |
| `updated_ymdhis` | bigint DEFAULT NULL | Last update. |
| `is_deleted` | tinyint NOT NULL DEFAULT 0 | Soft delete flag. |
| `deleted_ymdhis` | bigint DEFAULT NULL | When soft-deleted. |
| `metadata` | text DEFAULT NULL | Optional opaque JSON or text. |

Indexes: PK on `actor_trait_id`; index on `(actor_id, trait_key)` for lookups; index on `actor_id`.

## Out of scope (this table)

- **Channel roles / permissions** — remain in `lupo_actor_channel_roles`.
- **Skills** — documented in MD and attached via `lupopedia.skills` / `lupo_metadata`; not duplicated here.
- **Rules** — remain in `lupo_rules` / `lupo_rule_targets`; rules can *reference* traits for enforcement.

## Enforcement path (future)

After this table exists, rule checks (e.g. via `RuleEvaluator` / `lupo_rules`) can block or warn when actor behavior conflicts with trait/rule combinations (e.g. non-dialog actor sending emotional message). Reuse existing `lupo_rules`, `lupo_rule_targets`, and evaluator; do not add a second enforcement system.

## References

- `lupo-docs/status/cursor_actors_channels_semantic_architecture_4.0.69.md` — canonical architecture.
- `lupo-docs/doctrine/ActorFaucetOntology.md` — actor vs faucet.
- Antigravity / Codex proposals on actor roles and traits (exploratory; this design note is the approved baseline).
