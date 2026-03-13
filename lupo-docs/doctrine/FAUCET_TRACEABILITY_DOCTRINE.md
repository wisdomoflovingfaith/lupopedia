---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/FAUCET_TRACEABILITY_DOCTRINE.md"
  last_modified_utc: "20260313"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  faucet_name: "windsurf"
  artifact_type: "doctrine"
  artifact_kind: "faucet_traceability"
  purpose: "Faucet tracking in messages and sessions: column names from install/TOON."
lupopedia.footer:
  last_verified: "20260313"
  last_verified_by: "windsurf"
  version: "4.0.73"
  next_action:
    - "Update doctrine to reflect Session::create() faucet traceability implementation in 4.0.73"
    - "Document runtime faucet context detection patterns"
    - "Add examples of faucet slug/instance ID usage"
---
# file: Faucet Traceability Doctrine — web_path: http://www.lupopedia.com/doctrine/FAUCET_TRACEABILITY_DOCTRINE

# Faucet Traceability Doctrine (v4.0.73)

## 1. Purpose

Record **which faucet** (execution surface) was used for a session or message. Enables auditing and fallback routing. No FK; columns are optional (NULL allowed).

## 2. Sessions (`lupo_sessions`)

Columns (from install/TOON): `faucet_slug`, `faucet_instance_id` (after `actor_id`). Populate on session create from runtime (e.g. IDE session file `faucet_name`, or constant `LUPO_FAUCET_SLUG`). Instance ID can be a unique run identifier.

## 3. Messages (`lupo_dialog_messages`)

Columns (from install/TOON): `source_faucet_slug`, `source_faucet_instance_id` (after `from_actor_id`). Populate when creating a message from current session/faucet context (e.g. from `LUPO_FAUCET_SLUG` / `LUPO_FAUCET_INSTANCE_ID` or session).

## 4. References

- Actor–faucet ontology: ActorFaucetOntology.md
- Communication: COMMUNICATION_DOCTRINE.md
- TOONs: `lupo_dialog_messages.toon`, `lupo_sessions.toon`
