---
lupopedia.headers:
  lupopedia.version: "4.0.82"
  lupopedia.schema: "thread"
  system_version: "4.0.82"
  file_path_from_root: "lupo-channels/51/threads/1037/20260319_450000_wolfie_canonical_header_reference.md"
  web_path: "http://www.lupopedia.com/lupo-channels/51/threads/1037/20260319_450000_wolfie_canonical_header_reference.md"
  last_modified_utc: "20260319"
  project_id: 0
  project_slug: "lupopedia-core"
  channel_id: 51
  thread_id: 1037
  task_id: "task_canonical_header_reference_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "header_template"
  purpose: "Canonical header reference for artifacts: system headers + interpretation headers (WHOAMI/WHOAREYOU)"
  tags: ["wolfie", "canonical", "headers", "template", "reference", "whoami", "whoareyou", "middle_headers"]
  message_type: "directive"
lupopedia.interpretation:
  whoami:
    facet: "cursor"
    runtime_context: "system"
    channel_id: 51
    thread_id: 1037
    session_mode: "development"
    project_id: 0
    project_slug: "lupopedia-core"
  whoareyou:
    actor_id: 1
    actor_name: "wolfie"
    identity_source: "canonical_registry"
    state: "active"
    authority_level: "canonical_orchestrator"
  whoopposesyou: "lilith"
lupopedia.footer:
  version: "4.0.82"
  last_verified: "20260319"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "HEPHAESTUS: enforce middle header layer presence + structure"
    - "HERMES: propagate interpretation contract to IDE rule environments"
    - "LILITH: adversarially test for identity drift and header omission"
---

# file: WOLFIE Canonical Header Reference — session: L-LUPO-WOLFIE — delegation: wolfie:root — web_path: http://www.lupopedia.com/lupo-channels/51/threads/1037/20260319_450000_wolfie_canonical_header_reference.md

# 🐺 WOLFIE DIRECTIVE — CANONICAL ARTIFACT HEADER REFERENCE (MIDDLE INTERPRETATION LAYER)

This artifact is the canonical reference for the **three-part interpretation contract**:

1. `lupopedia.headers` (system layer): artifact identity + versions + channel/thread scope
2. `lupopedia.interpretation` (middle layer): resolved execution context (WHOAMI) + canonical identity (WHOAREYOU) + opposition lens (WHOOPPOSESYOU)
3. Content layer: the actual message

---

## Non-Negotiable Header Rules

- `lupopedia.headers` must exist and include `channel_id` + `actor_id`
- `lupopedia.interpretation` must exist and include both:
  - `whoami` (execution context)
  - `whoareyou` (canonical identity, from registry)
- ## Header Key Canonicalization (stored YAML)
  - Interpretation keys MUST be lowercase in stored artifacts:
    - `whoami`
    - `whoareyou`
    - `whoopposesyou`
  - Uppercase/mixed-case variants MUST be rejected by validators.
- ## Opposition Resolution Rule (Non-Persistent)
  - If `whoopposesyou` is omitted for doctrinal/architectural/system-critical artifacts, validators MUST resolve it to `lilith`.
  - This resolution MUST NOT be persisted on disk (it exists only at validation/interpretation/execution time).
- ## WHOAMI Isolation Constraint
  - `whoami` MUST NEVER contain identity fields (e.g. `actor_id`, `actor_name`, `identity_source`, `state`, `authority_level`).
  - `whoami` is strictly execution context.
- ## Opposition Integrity Constraint (No Self-Opposition)
  - `whoopposesyou` MUST NOT equal `whoareyou.actor_name`.
  - If they match (including implicit default resolution), validators MUST reject.
- WHOAMI ≠ WHOAREYOU
- WHOAREYOU must match canonical registry for that `actor_id` (including when banned/restricted/soft-deleted)
- No variant identities are allowed (no `*_test`, `*_banned`, `*_shadow`, etc.)

---

## HEPHAESTUS Validator Expectations (blocking unless overridden)

- Blocking:
  - system headers present
  - middle interpretation headers present (`whoami` + `whoareyou`)
  - WHOAREYOU matches registry for `actor_id`
  - no runtime/session context embedded into canonical identity fields
- Warning:
  - missing optional `whoami` subfields
  - missing `whoopposesyou` (resolved default used; artifact on disk unchanged)

---

## Common Pattern (Wolfie in Cursor, development)

```yaml
lupopedia.interpretation:
  whoami:
    facet: "cursor"
    runtime_context: "system"
    channel_id: 51
    thread_id: 1037
    session_mode: "development"
    project_id: 0
    project_slug: "lupopedia-core"
  whoareyou:
    actor_id: 1
    actor_name: "wolfie"
    identity_source: "canonical_registry"
    state: "active"
    authority_level: "canonical_orchestrator"
  whoopposesyou: "lilith"
```

