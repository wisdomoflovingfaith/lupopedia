---
lupopedia.headers:
  version_when_written: "4.0.86"
  file_path_from_root: "lupo-docs/doctrine/ROSE_PACKET_MAPPING.md"
  web_path: "http://www.lupopedia.com/lupo-docs/doctrine/ROSE_PACKET_MAPPING.md"
  last_modified_utc: "20260323_131000"
  channel_id: 60
  actor_id: 102
  actor_name: "cursor"
  artifact_type: "doctrine_mapping"
  artifact_kind: "rose_packet_db_mapping"
  purpose: "Define deterministic DB-to-packet field mapping for ROSE packet construction using canonical TOON schema sources."
  references:
    - "lupo-actors/rose/prompts/system/universal_ide_coordination_prompt.md"
    - "lupo-database/lupopedia/toon/lupo_actor_moods.toon"
    - "lupo-database/lupopedia/toon/lupo_emotional_frameworks.toon"
    - "lupo-database/lupopedia/toon/lupo_dialog_messages.toon"
    - "lupo-database/lupopedia/toon/lupo_actors.toon"
  tags: ["rose", "packet", "mapping", "doctrine", "deterministic", "4.0.86"]
---

# ROSE -> ATHENA Packet Mapping

## Overview
- Purpose: Define deterministic mapping from canonical DB (TOON JSON) to ROSE packet fields.
- Source of truth: DB schema as defined in TOON JSON files ONLY.

### TOON JSON References
- lupo-database/lupopedia/toon/lupo_actor_moods.toon
- lupo-database/lupopedia/toon/lupo_emotional_frameworks.toon
- lupo-database/lupopedia/toon/lupo_dialog_messages.toon
- lupo-database/lupopedia/toon/lupo_actors.toon

---

## Mapping Table

| Packet Field | DB Table | DB Column | Mapping Rule | Deterministic |
|--------------|----------|-----------|--------------|---------------|
| speaker | lupo_actors + lupo_dialog_messages | lupo_actors.slug (via lupo_dialog_messages.from_actor_id) | Resolve actor slug in application layer from dialog sender actor id. | YES |
| channel_id | lupo_dialog_messages | channel_id | Direct copy (no transformation). | YES |
| thread_id | lupo_dialog_messages | dialog_thread_id | Direct copy from dialog thread id as packet thread_id. | YES |
| created_utc | lupo_dialog_messages | created_ymdhis | Format BIGINT YYYYMMDDHHIISS to YYYYMMDD_HHMMSS in application layer only. | YES |
| created_ymdhis | lupo_dialog_messages | created_ymdhis | Pass-through BIGINT (trace field). | YES |
| mood_RGB | lupo_actor_moods | mood_r, mood_g, mood_b | Resolve latest actor mood by actor_id/timestamp, then encode R,G,B as 6-char uppercase hex. | YES |
| mood_label | lupo_actor_moods | mood_r, mood_g, mood_b, mood_framework | Derived via Mood Label Rules from canonical mood tuple; no DB write-back. | YES |
| mood_framework | lupo_actor_moods + lupo_emotional_frameworks | lupo_actor_moods.mood_framework; lupo_emotional_frameworks.framework_name | Use actor mood framework; validate membership in frameworks table in application layer. | YES |
| message | lupo_dialog_messages | message_body (fallback message_text) | Use message_body when present, else message_text, without semantic transformation. | YES |

Notes:
- This table includes all ROSE packet fields currently used in runtime packet construction (`speaker`, `channel_id`, `thread_id`, `created_utc`, `mood_RGB`, `message`) plus enforced doctrine fields (`mood_label`, `mood_framework`, `created_ymdhis`) required for deterministic interpretation and traceability.
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
