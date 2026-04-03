---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  status: archived_experiment_context
  file_path_from_root: "lupo-docs/versions/3.0.x/experiments/security/README.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/3.0.x/experiments/security/README.md"
  last_modified_utc: "20260403121547"
  purpose: "Historical context for adversarial security experiments (3.0.x era); not operational doctrine"
  delegation_chain: "wolfie:root"
  actor_id: 1
  actor_name: "wolfie"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/doctrine/ADVERSARIAL_TEST_IDENTITY_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Canonical 4.0.x explanation of test identities and naming"
    - to: "lupo-docs/doctrine/SELF_HACKING_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Self-hacking and adversarial self-testing methodology"
    - to: "lupo-docs/doctrine/TWO_LAYER_SECURITY_DOCTRINE.md"
      type: references
      weight: 1.0
      reason: "Two-layer security: baseline checks then adversarial testing"
lupopedia.footer:
  last_verified: "20260403121547"
  verified_by:
    identity_type: actor
    actor_id: 2
    name: "lilith"
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
| Ban reason codes and metadata | `lupo-docs/doctrine/BAN_REASONS.md` (stays in **live** doctrine) |
| Retirement of agent 420 experiment | `lupo-docs/doctrine/AGENT_420_RETIREMENT.md` |
| AI-facing definition of adversarial test identities | `lupo-docs/doctrine/ADVERSARIAL_TEST_IDENTITY_DOCTRINE.md` |

## Installer / harness references

The installer may still insert **rows** for reserved adversarial test identities (e.g. actor **420**) using **stable keys** in code. Those keys are **implementation identifiers**, not an invitation to revive the “Stoned Wolfie” label in user-facing or narrative docs.

This output complies with Lupopedia Constitutional Root Rules.
