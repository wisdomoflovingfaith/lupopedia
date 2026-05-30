---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/LILITH_TEACH_DONT_ONLY_TELL.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/LILITH_TEACH_DONT_ONLY_TELL.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/lilith-teach-dont-only-tell.toon
  atoms_toon: null
  transcript_jsonl: 0/development/lilith-teach-dont-only-tell
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: development
  federation_node_id: 0
  thread_key: ''
  lupopedia.schema: doctrine
  prd_cluster: null
  title: LILITH -- Teach, do not only tell (persistence for recurring rules)
  summary: 'Orchestrator discipline: persist carnal rules to AGENTS.md, Cursor rules, registries before chat-only nags. A-G-A-P-E mnemonic disambiguated from agape agent 705.'
---
# LILITH -- Teach, do not only tell (persistence for recurring rules)

**Anchoring PRDs:** [PRD 50 -- Agent coordination protocol](../prd/50_agent_coordination_protocol.md); [PRD 00 -- Root constitutional requirements](../prd/00_root_constitutional_system_requirements.md) (documentation and multi-agent safety).

**LIL001 alignment:** This doctrine **does not** grant LILITH silent write authority over other agents' artifacts. The **human orchestrator** (or explicitly tasked reviewer) performs the **file commits** that persist lessons. LILITH **recommends** where to write and **audits** whether the write happened (Pillar 2, Survivability Doctrine).

## Name collision note (read first)

The token **AGAPE** is already reserved for:

- **`lupo_agents` / actor tooling** around pattern metrics and **`AGAPE_PATTERN_REPORT`** (see **`AGAPE_DEFECT_TAXONOMY.md`**, **`COUNTING_IN_LIGHT_DOCTRINE.md`**, **`agents/agape/system_prompt.md`**).

This document uses the letters **A-G-A-P-E** only as an **informal mnemonic** for **LILITH-side teaching discipline** below. When you mean **defect taxonomy** or **agent 705**, cite **`AGAPE_DEFECT_TAXONOMY.md`** -- not this file.

## The failure mode

```text
Reviewer tells agent: "Fix the header."
Agent fixes the header.
Next session, agent breaks the header again.

Because nobody wrote the WHY into persistent rules the agent loads at startup.
```

**Telling is temporary. Teaching (persisted) is durable.**

## The mnemonic (LILITH teaching context only)

| Letter | Stands for | Meaning |
|--------|------------|---------|
| **A** | Automated | Prefer validators and repeatable checks, not one-off nagging. |
| **G** | Guidance | Explain constraint and correct shape, not only "wrong." |
| **A** | Agent | The facet or pack that must comply (Cursor, Gemini, VS Code, etc.). |
| **P** | Persistent | Committed to files the agent actually reads (rules, AGENTS, registries). |
| **E** | Enforcement | LILITH (or THOTH-class paths) audits whether the lesson stuck. |

## Carnal vs constitutional vs atom (working vocabulary)

| Term | Meaning | Example |
|------|---------|---------|
| **Carnal** (informal) | Rule that will recur unless tooling or docs enforce it | ASCII-only, header envelope, PRD-first routing |
| **Constitutional** | Cannot be violated without breaking root law | No emoji in normative text; PDO_DB only |
| **Atom** | Stable fact packs and registries | Actor IDs, trust tiers, channel keys |

These are **not** one-off opinions. If the fleet should **never** repeat a mistake class, the fix belongs in **Pillar 2** surfaces (memory TOON, doctrine, rules), not only in chat.

## Where to write (operator checklist)

| Rule type | Typical location |
|-----------|------------------|
| ASCII-only, PRD-first, faucet discipline | **`AGENTS.md`** (root) |
| Cursor-only guardrails | **`.cursor/rules/*.mdc`** |
| Header contract | **`docs/doctrine/lupopedia-headers/`** + validators |
| Actor and facet IDs | **`database/lupopedia/actors/`** registries |
| Trust ladder | **PRD 43** + trust ladder doctrine |
| Channel keys | **`channels/`** index and registry artifacts per project layout |

## The test: did you teach or only tell?

| You only told | You taught (persisted) |
|-----------------|-------------------------|
| "Fix the header." | Added or tightened header rules in **`AGENTS.md`** / validators |
| "No emoji." | Confirmed **`.cursor/rules/ascii-only.mdc`** and scanners cover the path class |
| "PRD first." | Linked requirement in **PRD 00** / **AGENTS.md** documentation tier table |
| "Cursor is 102." | Verified **`registry.json`**; cited in audit, not chat alone |

If the rule never reached a **committed file** the agent loads, **Pillar 2 did not close**.

## Self-check (orchestrator, before the next "fix it" ping)

1. Is this a **one-off** typo? -> A single tell may be enough.
2. Is this a **recurring** pattern or **carnal** rule? -> **Write first**, then tell where the law lives.

If (2) is true and only chat text changed, **teaching failed**.

## Prompt fragment (for human paste into LILITH sessions)

```text
[LILITH DIRECTIVE] -- TEACH, DO NOT ONLY TELL

When a carnal rule or constitutional requirement must stick across sessions:

1. Name the target persistence surface (AGENTS.md, .cursor/rules/, doctrine, registry).
2. Draft or request the concrete file edit (human or explicitly tasked agent commits).
3. Only after the commit exists, point builders at the path.

Chat alone is not a substitute for a commit.
```

## Bottom line

| Old pattern | Taught pattern |
|-------------|----------------|
| "Fix the header." | Header doctrine + validator reference committed; then "read section X." |
| "No emoji." | ASCII rule present in rules the IDE loads; then "see ascii-only rule." |
| "PRD first." | PRD-first tier text updated; then "AGENTS.md routing." |

**Telling is a conversation. Teaching is a commit.**

This output complies with Lupopedia Constitutional Root Rules.
