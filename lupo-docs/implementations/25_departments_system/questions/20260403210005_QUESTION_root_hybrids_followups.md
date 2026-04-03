---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260403210005"
  file_path_from_root: "lupo-docs/implementations/25_departments_system/questions/20260403210005_QUESTION_root_hybrids_followups.md"
  last_modified_utc: "20260403210005"
  channel_id: 42
  actor_id: 102
  artifact_type: implementation
  artifact_kind: question
  purpose: "Open design questions after seeding root hybrids and Crafty per-department Wolfie actors"
lupopedia.footer:
  last_verified: "20260403210005"
  verified_by:
    actor_id: 102
---

# QUESTION: Root hybrids and Crafty department actors — follow-ups

## Context

Seed and import now:

- Put **Captain (wolfie 1)**, **Lilith (2)**, and **COUNTERMEASURE (111)** in **department 0** with `role_key = hybrid`, plus **system 0** and **ANUBIS 19**.
- Remove other personas from **department 0** in seed (they remain in `lupo_actors` without a row in `lupo_actor_departments` until assigned).
- After Crafty import truncates/rebuilds `lupo_actor_departments`, **re-insert** those five root rows.
- Create **`dept_{id}`** / **`actor_id = 280000 + department_id`** Wolfie-model hybrids for **non-root** departments.

## Open questions

1. **Coordination personas (lexa … asclepius):** Should they receive a dedicated **department** (e.g. “Coordination”) and `lupo_actor_departments` rows, or stay **unassigned** until first use?
2. **`lupo_agents` rows:** Should **wolfie / lilith / countermeasure** agent templates be **required** in DB for UI “create actor from agent,” or is filesystem **`lupo-agents/`** sufficient for 4.0.x?
3. **Channel 42 membership:** Install SQL still grants channel 42 to several `actor_id` values (e.g. 3+). If those actors have **no** `lupo_actor_departments` row, should channel membership be **trimmed** or is channel access **independent** of department act-as?
4. **280000 + department_id band:** Confirm this reserved band is acceptable long-term or switch to allocator-driven IDs in PHP during a later pass.
5. **PostgreSQL import:** Current import uses MySQL-only functions (`REGEXP`, `DATE_FORMAT`, etc.). Is a parallel import script required before claiming portability?

## Suggested owner

WOLFIE / product owner + HEPHAESTUS for schema alignment.
