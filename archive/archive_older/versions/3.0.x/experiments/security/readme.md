---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: null
  file_path_from_root: "docs/versions/3.0.x/experiments/security/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/3.0.x/experiments/security/README.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: null
  artifact_kind: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: "archived_experiment_context"
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# file: Security experiments vault (3.0.x context) — delegation: wolfie:root

# Security research vault (historical)

This folder holds **archival context** for early **adversarial testing** (red-team style): banned-user login attempts, prompt-injection probes, and privilege checks using **reserved test identities**.

## What “Stoned Wolfie” referred to (plain language)

In **pre-4.0.x experimentation**, the operator created **banned test accounts** and **fixed adversarial actor rows** (later formalized as **actor_id 420** and related seed data) to answer questions such as:

- Does a ban block session use as intended?
- Are banned actors still visible to policy queries (per convergence doctrine)?
- Are prompt or routing paths blocked for disallowed identities?

That work is **security engineering**, not a product feature. The **display name** used in some seeds and logs was a **test label**, not an approved orchestration persona.

## What this is not

- **Not** a canonical “persona” for agents to role-play (see root rules: banned experimental personas).
- **Not** a replacement for WOLFIE (actor_id 1) or any primary coordination persona.
- **Not** documentation of a supported user-facing character.

## Where live 4.0.x rules live

| Topic | Location |
|-------|-----------|
| Ban reason codes and metadata | `docs/doctrine/BAN_REASONS.md` (stays in **live** doctrine) |
| Retirement of agent 420 experiment | `docs/doctrine/AGENT_420_RETIREMENT.md` |
| AI-facing definition of adversarial test identities | `docs/doctrine/ADVERSARIAL_TEST_IDENTITY_DOCTRINE.md` |

## Installer / harness references

The installer may still insert **rows** for reserved adversarial test identities (e.g. actor **420**) using **stable keys** in code. Those keys are **implementation identifiers**, not an invitation to revive the “Stoned Wolfie” label in user-facing or narrative docs.

This output complies with Lupopedia Constitutional Root Rules.
