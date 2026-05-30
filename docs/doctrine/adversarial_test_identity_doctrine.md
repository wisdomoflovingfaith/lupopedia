---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/adversarial_test_identity_doctrine.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/adversarial_test_identity_doctrine.md
  status: active
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: security_adversarial_identity
  channel_key: null
  federation_node_id: 0
  thread_key: ''
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
---
# file: ADVERSARIAL_TEST_IDENTITY_DOCTRINE — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/docs/doctrine/ADVERSARIAL_TEST_IDENTITY_DOCTRINE.md

# Adversarial test identity doctrine (4.0.x)

## Purpose

Lupopedia uses **reserved identities** and **seeded rows** to run **adversarial tests** (ban enforcement, routing, logging). A historical label, **“Stoned Wolfie,”** appears in **old logs, seeds, and security checklists** as the name of a **banned test identity**, not as a supported orchestration persona.

This doctrine tells **humans, IDEs, and AI tools** how to interpret those references so they do **not** treat them as product canon or a character to emulate.

## Rules for AI and contributors

1. **Do not** invent new narrative personas named “Stoned Wolfie” or similar. Root documentation lists **banned experimental persona names**; this is one of them.
2. **Do** treat **actor_id 420** / **`stoned_wolfie_*` keys** in **installer or validator code** as **implementation identifiers** for a **fixed adversarial test identity**, unless and until a refactor renames them.
3. **Do** use **`banned_test_identity`** (see `docs/doctrine/BAN_REASONS.md`) when describing **policy**, not colloquial nicknames, in new specs.
4. **Do** read **`docs/doctrine/AGENT_420_RETIREMENT.md`** for the formal retirement of the experimental 420-series identity in doctrine.

## Relationship to convergence doctrine

Canonical **actors** remain queryable by id; **state** (e.g. banned) is mutable. Adversarial tests must **not** create **variant actors** to represent ban state. Test harnesses may use **explicit reserved ids** only where install/seed and doctrine already allow.

## Where historical narrative lives

Red-team **context** and archive notes: **`docs/versions/3.0.x/experiments/security/README.md`**

## Verification

- Grep for display strings only when auditing **UX** or **public docs**; **do not** remove stable **DB/seed keys** without a migration plan (4.1.0+ per project release doctrine).

This output complies with Lupopedia Constitutional Root Rules.
