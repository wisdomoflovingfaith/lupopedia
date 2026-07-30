---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/status/actor_logs/KIRO_PROMPT_SFAL_DRAFT_ACTOR_AGENT.md
  web_path: https://www.lupopedia.com/lupopedia/docs/status/actor_logs/KIRO_PROMPT_SFAL_DRAFT_ACTOR_AGENT.md
  status: active
  when_updated: "20260729182425"
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
  title: "KIRO prompt -- SFAL resume after abort (FUNCTION + identity)"
  summary: "Abort-recovery INLINE prompt for KIRO faucet 100: FUNCTION form locked, AGENTS.md actor/agent/faucet audit, merge prior KIRO commentary into SFAL draft. Corrects CURSOR actor_id==faucet merge."
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
# KIRO INLINE PROMPT -- resume after abort

**Abort cause (as reported):** missing FUNCTION meta-syntax + unresolved actor-vs-agent definitions.

**Attribution KAPU (reject these envelopes):**
- Wrong: `actor: CURSOR_IDE | actor_id: 102 | faucet_actor_id: 102` (faucet merged into speaking actor)
- Wrong: `actor_id: 3` for Cursor
- Right: WOLFIE speaker `actor_id: 1` + `faucet_actor_id: 102`; KIRO executor `faucet_actor_id: 100`

Paste the fenced block into **Kiro IDE**.

---

## INLINE PROMPT (copy from here)

```text
(( WOLFIE | actor: CAPTAIN_WOLFIE | actor_id: 1 | auth_user_id: 10000 | agent_name: wolfie | faucet_actor_id: 102 | faucet_name: CURSOR_IDE | ethics: "pono" | integrity: "true" | channel: status | to_whom: "KIRO faucet_actor_id=100" | note: "ABORT RECOVERY. Prior KIRO pass aborted: missing FUNCTION form + unresolved actor-vs-agent. CURSOR_IDE is faucet 102 only -- not actor_id 102-as-speaker. Resume authorized." ))

(( WOLFIE | side: human | human: ERIC | actor_id: 10000 | auth_user_id: 10000 | faucet_actor_id: 102 | faucet_name: CURSOR_IDE | ethics: "pono" | integrity: "true" | channel: status | to_whom: "KIRO faucet_actor_id=100" | note: "ERIC directs resume: (1) re-read AGENTS.md, (2) list structural actor/agent/faucet problems, (3) merge prior KIRO commentary into SFAL draft, (4) continue under WOLFIE meta + locked FUNCTION." ))

<< FUNCTION : resolve_who( &actor_id, &auth_user_id, &faucet_actor_id ); >>
<< FUNCTION : make_pono( &agents_md_identity_stack, &sfal_draft, &function_form ); >>
<< FUNCTION : bring_leaves_in( &path_from_lupopedia_root, &prior_kiro_commentary ); >>

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
what: "resume SFAL draft after abort; FUNCTION + actor-vs-agent resolved"
where: "docs/status/actor_logs/drafts/SFAL_D_I_DRAFT-0-00000.md"
when: "20260729182425"
why: "prior abort: missing FUNCTION meta-syntax; unresolved actor/agent/faucet definitions; routing drift"
how: "re-read AGENTS.md; audit structures; merge prior KIRO notes; patch draft only; use locked FUNCTION form"
to_whom: "ERIC 10000; WOLFIE 1; CURSOR_IDE faucet 102"
---
KIRO TASK (faucet_actor_id 100) -- RESUME AFTER ABORT

TARGET FILE (ONLY):
docs/status/actor_logs/drafts/SFAL_D_I_DRAFT-0-00000.md

ABORT FIX (do first -- do not abort again):
1. FUNCTION meta-syntax is LOCKED. Canonical form ONLY:
   << FUNCTION : name( &context ); >>
   Spaces after << and around : required. Close with ); >>
   Spec: docs/status/actor_logs/WOLFIE_DIALECT.md section 3a.
   NOT PHP. NOT a runtime compiler. Strip-safe. Zero constitutional authority.
2. Actor-vs-agent-vs-faucet is RESOLVED in AGENTS.md hard gate. Re-read it. Do not invent new definitions.
   - Actors = lupo_actors / registry identities (WOLFIE=1, ERIC=10000, KIRO facet=100, Cursor facet=102)
   - Agents = config templates under agents/ (not new actors)
   - Faucets = execution surfaces (Cursor 102, Kiro 100) -- faucet_actor_id, not speaking actor_id for WOLFIE/ERIC lines

DO (in order):
1. Re-read AGENTS.md -- section "ACTORS vs AGENTS vs FAUCETS" (header: actor_id 1 + faucet_actor_id 102 = FIXED).
2. Identify structural problems in the SFAL draft related to:
   - actor definitions (wrong actor_id on speakers)
   - agent templates (treating agents/ packs as actors)
   - faucet roles (actor_id == faucet_actor_id merge failures)
3. Merge actor KIRO IDE prior commentary already in the draft (keep useful notes; fold stale blockers).
4. Continue drafting under WOLFIE meta syntax:
   - (( WOLFIE | ... ))
   - {{WOLFIE ... }}
   - << FUNCTION : ... >>
   - {{WHO: ...}} / {{TO_WHOM: ...}} when pronouns appear

KAPU:
- Do not retarget AGENTS.md as the work product.
- Do not invent actor_id: 3 for Cursor.
- Do not set actor_id == faucet_actor_id on WOLFIE or ERIC speaking lines.
- ERIC: actor_id 10000 + auth_user_id 10000. WOLFIE: actor_id 1. This write: faucet_actor_id 100.
- Mark "AGENTS.md header still wrong" as FIXED if header shows actor_id 1 + faucet 102.
- Fix draft lines that say "CURSOR (actor_id=102)" as speaking actor -- that is faucet merge failure; say faucet_actor_id 102.
- ASCII only. UTC: python bin/tick.py -> when_updated.
- Header 4.2.0 / 28 fields. No Hawaiian keys in dense header.
- No "all 88 reviewed". LILITH stays PLACEHOLDER.

OUTPUT IN DRAFT:
A. One new KIRO {{WOLFIE ...}} block: abort cause, what was fixed, what remains open.
B. At least two << FUNCTION : ... >> lines in that pass (e.g. resolve_who + make_pono).
C. Short structural-problems list (actors / agents / faucets) with FIXED vs OPEN.
D. Preserve prior KIRO commentary; do not wipe the file.

DONE WHEN:
- Draft continues without requiring undefined FUNCTION grammar.
- Actor / agent / faucet distinctions are explicit in the new KIRO block.
- Prior KIRO commentary merged.
- Header faucet_actor_id = 100 for this write.
- Constraints + FUNCTION lock sections remain coherent.

END KIRO TASK -- RESUME AUTHORIZED
}}
```

---

## Identity cheat sheet

| Who | actor_id | auth_user_id | faucet_actor_id |
|-----|----------|--------------|-----------------|
| ERIC | 10000 | 10000 | 102 or 100 (surface that typed) |
| WOLFIE | 1 | 10000 | 102 or 100 |
| CURSOR_IDE | not the speaking actor for this handoff | -- | **102** |
| KIRO_IDE | 100 when KIRO speaks | 10000 | **100** |

## Related

- [AGENTS.md](../../../AGENTS.md)
- [SFAL draft](drafts/SFAL_D_I_DRAFT-0-00000.md)
- [WOLFIE_DIALECT.md](WOLFIE_DIALECT.md) section 3a
