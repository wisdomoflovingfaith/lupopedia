---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/faucet_traceability_doctrine.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/faucet_traceability_doctrine.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: faucet_traceability
  channel_key: null
  federation_node_id: null
  thread_key: ''
  lupopedia.schema: doctrine
  prd_cluster: null
  title: ''
  summary: ''
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
