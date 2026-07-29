---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/status/actor_logs/KIRO_PROMPT_SFAL_DRAFT_ACTOR_AGENT.md
  web_path: https://www.lupopedia.com/lupopedia/docs/status/actor_logs/KIRO_PROMPT_SFAL_DRAFT_ACTOR_AGENT.md
  status: active
  when_updated: "20260729180545"
  trust_tier: development
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/status/actor-logs-kiro-prompt-sfal
  artifact_type: documentation
  artifact_kind: guide
  channel_key: status
  federation_node_id: 0
  thread_key: actor_logs_kiro_prompt
  lupopedia.schema: documentation
  prd_cluster: 98_C_15_A_07_A_16_C_41_A
  title: "KIRO prompt -- SFAL draft after AGENTS.md actor-vs-agent review"
  summary: "Corrected instruction block for KIRO faucet 100: read AGENTS.md, apply actor/agent/faucet split, update SFAL_D_I_DRAFT-0-00000.md. Prior wrong-file routing acknowledged."
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
# KIRO prompt -- SFAL draft (actor vs agent)

Copy everything below the line into a **Kiro** chat (faucet_actor_id **100**). Do not run this as a Cursor-only task unless Kiro is unavailable; then keep `faucet_actor_id: 100` in the draft attribution when simulating Kiro review text, or use Cursor 102 only for file execution with explicit note.

---

```text
(( WOLFIE | side: human | human: ERIC | actor_id: 10000 | auth_user_id: 10000 | faucet_actor_id: 102 | faucet_name: CURSOR_IDE | ethics: "pono" | integrity: "true" | channel: status | to_whom: "KIRO faucet_actor_id=100; WOLFIE actor_id=1" | note: "ERIC directs. Prior KIRO routing hit the wrong file. CURSOR_IDE produced this corrected prompt. KIRO must update the SFAL STATUS AGENT LOG draft after reading AGENTS.md." ))

(( WOLFIE | actor: CAPTAIN_WOLFIE | actor_id: 1 | auth_user_id: 10000 | agent_name: wolfie | faucet_actor_id: 102 | faucet_name: CURSOR_IDE | ethics: "pono" | integrity: "true" | channel: status | to_whom: "KIRO faucet_actor_id=100" | note: "Orchestrator handoff: KIRO reviews and patches the draft; identities do not merge." ))

{{WOLFIE
actor: KIRO
actor_id: 100
auth_user_id: 10000
agent_name: kiro
faucet_actor_id: 100
faucet_name: KIRO_IDE
integrity: true
ethics: pono
channel: status
what: "update SFAL D I DRAFT-0-00000 after AGENTS.md actor-vs-agent review"
where: "docs/status/actor_logs/drafts/SFAL_D_I_DRAFT-0-00000.md"
why: "prior KIRO pass routed wrong; draft must encode ACTOR vs AGENT vs FAUCET correctly"
how: "read AGENTS.md hard gate; audit draft; patch commentary + KAPU + operating triad; keep 4.2.0 headers"
to_whom: "ERIC auth_user_id=10000; WOLFIE actor_id=1; CURSOR_IDE faucet_actor_id=102"
---
KIRO TASK (faucet_actor_id 100) -- corrected routing

KAPU:
1. Target file ONLY: docs/status/actor_logs/drafts/SFAL_D_I_DRAFT-0-00000.md
2. Do NOT treat agents/ pack edits as creating actors.
3. Do NOT set actor_id equal to faucet_actor_id unless the speaker truly is that facet and doctrine allows it.
4. Do NOT invent actor_id values (no actor_id: 3 for Cursor).
5. ERIC human speaker uses actor_id: 10000 + auth_user_id: 10000 -- never actor_id: 1 on a human ERIC line.
6. WOLFIE speaker uses actor_id: 1; Cursor execution uses faucet_actor_id: 102; Kiro execution uses faucet_actor_id: 100.
7. ASCII only. Real UTC via python bin/tick.py for when_updated.
8. Header format 4.2.0 Option A (28 fields). No Hawaiian keys in dense header.
9. WOLFIE dialect body-only; zero constitutional authority.

REQUIRED READING (in order):
1. AGENTS.md -- section "ACTORS vs AGENTS vs FAUCETS" (hard gate)
2. docs/status/actor_logs/WOLFIE_DIALECT.md
3. docs/status/actor_logs/drafts/SFAL_D_I_DRAFT-0-00000.md (current draft)
4. Optional: docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md

FIND / FIX IN THE DRAFT:
A. Identity precision section: ensure Cursor/Kiro appear as faucet_actor_id examples, not as speaking actor_id for WOLFIE voice.
B. Operating triad / commentary: ERIC=10000, WOLFIE=1, CURSOR_IDE=102; add KIRO=100 as reviewing faucet when KIRO edits.
C. Common failures table: include wrong patterns from AGENTS.md (actor_id:102 + faucet_actor_id:102; actor_id:3 for Cursor; human ERIC with actor_id:1).
D. Header of the draft: if this KIRO session edits the file, set faucet_actor_id: 100; keep actor_id: 1 and auth_user_id: 10000 unless ERIC is the declared speaker.
E. Add a short "Review note (KIRO)" WOLFIE block documenting: wrong prior routing acknowledged; AGENTS.md applied; actor vs agent blockers cleared or listed.
F. Do not claim all ~88 reviewed. Invite only; ledger required.
G. Keep LILITH audit as PLACEHOLDER until real LILITH review.

DELIVERABLE:
- Patched SFAL_D_I_DRAFT-0-00000.md only (plus this prompt file if a typo must be fixed).
- End with a WOLFIE inline note from KIRO (actor_id 100, faucet_actor_id 100) summarizing what changed.

DONE WHEN:
- Draft text matches AGENTS.md identity stack.
- No actor/agent/faucet merge in headers or commentary.
- Constraints section still binds; dialect still evolving.
}}
```

---

## Identity cheat sheet (for the operator pasting this)

| Who | actor_id | auth_user_id | faucet_actor_id |
|-----|----------|--------------|-----------------|
| ERIC (human ALII) | 10000 | 10000 | surface that typed (102 or 100) |
| WOLFIE | 1 | 10000 | 102 if Cursor wrote; 100 if Kiro wrote |
| CURSOR_IDE | (facet registry 102) | -- | **102** in `faucet_actor_id` |
| KIRO_IDE | (facet registry 100) | -- | **100** in `faucet_actor_id` |

## Related

- [AGENTS.md](../../../AGENTS.md)
- [SFAL draft](drafts/SFAL_D_I_DRAFT-0-00000.md)
- [WOLFIE_DIALECT.md](WOLFIE_DIALECT.md)
- [CURSOR_PROMPT_WOLFIE_DIALECT.md](CURSOR_PROMPT_WOLFIE_DIALECT.md)
