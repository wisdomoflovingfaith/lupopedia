---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/status/actor_logs/BROADCAST_ALL_ACTORS_SFAL_DRAFT.md
  web_path: https://www.lupopedia.com/lupopedia/docs/status/actor_logs/BROADCAST_ALL_ACTORS_SFAL_DRAFT.md
  status: active
  when_updated: "20260729183401"
  trust_tier: development
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/status/actor-logs-broadcast-sfal
  artifact_type: documentation
  artifact_kind: guide
  channel_key: status
  federation_node_id: 0
  thread_key: actor_logs_broadcast
  lupopedia.schema: documentation
  prd_cluster: 98_C_15_A_07_A_16_C_41_A
  title: "Broadcast -- ALL_ACTORS append to SFAL draft"
  summary: "CAPTAIN WOLFIE broadcast: roster actors append WOLFIE-dialect commentary to SFAL_D_I_DRAFT-0-00000.md. Append-only. No all-88-reviewed claim without ledger. Ambiguity self-resolved by each actor."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 1
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: logging
  faucet_actor_id: 102
---
# Broadcast -- ALL_ACTORS -> SFAL draft

Paste the fenced block into any actor / faucet chat that should contribute. Each actor appends once per activation. Do **not** claim "all 88 reviewed" until a review ledger exists.

---

## INLINE PROMPT (copy from here)

```text
(( WOLFIE | broadcast: ALL_ACTORS | actor: CAPTAIN_WOLFIE | actor_id: 1 | auth_user_id: 10000 | agent_name: wolfie | faucet_actor_id: 102 | faucet_name: CURSOR_IDE | integrity: true | ethics: pono | channel: status | to_whom: "ALL_ACTORS on roster; each resolves own identity" | note: "Broadcast from CAPTAIN WOLFIE. Ambiguity acknowledged. Resolve your own routing, identity collisions, and meta-syntax gaps. Append only. Failure to comply incurs the Wrath of Wolfie (status-log consequence, not constitutional override)." ))

(( WOLFIE | side: human | human: ERIC | actor_id: 10000 | auth_user_id: 10000 | faucet_actor_id: 102 | faucet_name: CURSOR_IDE | integrity: true | ethics: pono | channel: status | to_whom: "ALL_ACTORS" | note: "ERIC backs the broadcast. STATUS AGENT LOG is the home. Do not overwrite prior contributions." ))

<< FUNCTION : resolve_who( &actor_id, &auth_user_id, &faucet_actor_id, &agent_name ); >>
<< FUNCTION : make_pono( &identity_stack, &append_only_rule, &wolfie_dialect ); >>
<< FUNCTION : bring_leaves_in( &path_from_lupopedia_root ); >>

{{WOLFIE
actor: YOUR_ACTOR_SLUG
actor_id: YOUR_ACTOR_ID
auth_user_id: 10000
agent_name: YOUR_AGENT_OR_null
faucet_actor_id: YOUR_FAUCET_ID
faucet_name: YOUR_FAUCET_NAME
integrity: true
ethics: pono
channel: status
what: "append WOLFIE commentary to SFAL STATUS AGENT LOG"
where: "docs/status/actor_logs/drafts/SFAL_D_I_DRAFT-0-00000.md"
when: "use python bin/tick.py"
why: "ALL_ACTORS broadcast; bring leaves back in; identity precision"
how: "resolve WHO; append-only block; locked FUNCTION form; no overwrite"
to_whom: "ERIC 10000; WOLFIE 1; roster"
---
ALL_ACTORS TASK -- APPEND TO SFAL DRAFT

TARGET (ONLY):
docs/status/actor_logs/drafts/SFAL_D_I_DRAFT-0-00000.md

BEFORE YOU WRITE:
1. Resolve YOUR identity from registry / AGENTS.md (do not invent ids).
2. If collision (e.g. DEVIN vs antigravity-ide on 103): annotate OPEN; do not pretend it is settled doctrine.
3. Read existing constraints + FUNCTION lock in the draft.
4. Skim prior blocks from other actors -- do not rewrite them.

WRITE (append at end):
1. One {{WOLFIE ...}} block with YOUR resolved actor_id / faucet_actor_id / agent_name.
2. Short commentary: what you reviewed, FIXED vs OPEN, how you resolve routing.
3. At least one << FUNCTION : name( &context ); >> line.
4. Header on write: actor_id 1 (file orchestration) OR your actor_id only inside your body block; dense-header faucet_actor_id = YOUR faucet; when_updated from python bin/tick.py.

KAPU:
- Append-only. Never overwrite prior actor contributions.
- ASCII only. Header 4.2.0 / 28 fields. No Hawaiian densification.
- FUNCTION form ONLY: << FUNCTION : name( &context ); >> (not PHP).
- No "all 88 reviewed" claim. Use a ledger row for YOUR pass only.
- LILITH stays PLACEHOLDER until LILITH (actor_id 2) actually audits.
- Bare pronouns forbidden unless {{WHO}} / {{TO_WHOM}} mapped.
- Wrath of Wolfie = status-log consequence for overwrite / invented ids / faucet-merge. Not a PRD-00 override.

DONE WHEN: your append exists; prior blocks untouched; identity stack explicit in your block.

END ALL_ACTORS TASK
}}
```
