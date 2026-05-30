---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/rose_packet_mapping.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/rose_packet_mapping.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine_mapping
  artifact_kind: rose_packet_db_mapping
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: null
  prd_cluster: null
  title: ''
  summary: ''
---
# ROSE -> ATHENA Packet Mapping

## Overview
- Purpose: Define deterministic mapping from canonical DB (TOON JSON) to ROSE packet fields.
- Source of truth: DB schema as defined in TOON JSON files ONLY.

### TOON JSON References
- database/lupopedia/toon/lupo_actor_moods.toon
- database/lupopedia/toon/lupo_emotional_frameworks.toon
- database/lupopedia/toon/lupo_dialog_messages.toon
- database/lupopedia/toon/lupo_actors.toon

---

## Mapping Table

| Packet Field | DB Table | DB Column | Mapping Rule | Deterministic |
|--------------|----------|-----------|--------------|---------------|
| speaker | lupo_actors + lupo_dialog_messages | lupo_actors.slug (via lupo_dialog_messages.from_actor_id) | Resolve actor slug in application layer from dialog sender actor id. | YES |
| channel_id | lupo_dialog_messages | channel_id | Direct copy (no transformation). | YES |
| thread_id | lupo_dialog_messages | dialog_thread_id | Direct copy from dialog thread id as packet thread_id. | YES |
| created_utc | lupo_dialog_messages | created_ymdhis | Format BIGINT YYYYMMDDHHIISS to YYYYMMDD_HHMMSS in application layer only. | YES |
| created_ymdhis | lupo_dialog_messages | created_ymdhis | Pass-through BIGINT (trace field). | YES |
| mood_vector | lupo_actor_moods | mood_r, mood_g, mood_b | Resolve latest actor mood by actor_id/timestamp, then encode R,G,B as 6-char uppercase hex. | YES |
| mood_label | lupo_actor_moods | mood_r, mood_g, mood_b, mood_framework | Derived human-readable companion label from the canonical mood tuple and message posture; no DB write-back. | YES |
| mood_framework | lupo_actor_moods + lupo_emotional_frameworks | lupo_actor_moods.mood_framework; lupo_emotional_frameworks.framework_name | Use actor mood framework; validate membership in frameworks table in application layer. | YES |
| message | lupo_dialog_messages | message_body (fallback message_text) | Use message_body when present, else message_text, without semantic transformation. | YES |

Notes:
- This table includes all ROSE packet fields currently used in runtime packet construction (`speaker`, `channel_id`, `thread_id`, `created_utc`, `mood_vector`, `message`) plus doctrine companion/trace fields (`mood_label`, `mood_framework`, `created_ymdhis`) required for deterministic interpretation and traceability.
- `mood_label` is the human-readable companion to `mood_vector`, especially useful for longer ROSE insight/comment messages.
- Some currently deployed short-form packet emitters may still omit `mood_label`; that omission does not change the canonical role of `mood_vector` as the machine-readable signal.
- No packet field is a write source for DB state.

---

## Deterministic Constraints

1. DB is canonical — values MUST originate from DB state only.
2. Packet is read-only — NO INSERT/UPDATE anywhere.
3. No joins at DB level — resolution happens in application layer only.
4. No hidden behavior — mapping is pure transformation.
5. Same DB snapshot MUST produce identical packet output.
6. Timestamp handling MUST use UTC BIGINT source values (`created_ymdhis`) with deterministic formatting for `created_utc`.

---

## Validation Checklist

- [ ] All packet fields mapped
- [ ] All mappings deterministic
- [ ] No external lookups
- [ ] No side-effects
- [ ] TOON JSON files explicitly referenced
