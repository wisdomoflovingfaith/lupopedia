---
lupopedia.headers:
  header_format_version: "4.2.0"
  path_from_lupopedia_root: docs/status/actor_logs/drafts/SFAL_D_I_DRAFT-0-00000.md
  web_path: https://www.lupopedia.com/lupopedia/docs/status/actor_logs/drafts/SFAL_D_I_DRAFT-0-00000.md
  status: draft
  when_updated: "20260729172112"
  trust_tier: development
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: 0/status/actor-logs-drafts
  artifact_type: status
  artifact_kind: report
  channel_key: status
  federation_node_id: 0
  thread_key: status_agent_log_draft
  lupopedia.schema: status
  prd_cluster: 98_C_98_B_16_C_15_A_41_A_07_A_73_A_82_B_39_A
  title: "SFAL D I DRAFT-0-00000 -- STATUS AGENT LOG: Bringing the Leaves Back In"
  summary: "DRAFT status agent log with PRE-RELEASE CONSTRAINTS including Pronoun Awareness (WHO/TO_WHOM). Cursor faucet 102 + Human Eric 10000 + WOLFIE actor 1. Dialect evolving."
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

# CONSTRAINTS -- DRAFT-0-00000 (Pre-Release -- read before roster review)

{{WOLFIE
actor: CAPTAIN_WOLFIE
actor_id: 1
auth_user_id: 10000
agent_name: wolfie
faucet_actor_id: 102
faucet_name: CURSOR_IDE
integrity: true
ethics: pono
channel: status
what: "pre-release constraints for SFAL D I DRAFT-0-00000"
where: "docs/status/actor_logs/drafts/SFAL_D_I_DRAFT-0-00000.md"
when: "20260729171900"
why: "control the draft before inviting the hybrid actor/agent roster (~88 templates)"
how: "declare WHO on every commentary block; map pronouns to WHO/TO_WHOM; evolve WOLFIE dialect as needs appear"
to_whom: "reviewing actors, agents, and faucets; Human Captain ERIC (10000)"
---
CAPTAIN_WOLFIE (actor_id 1) states: these constraints bind STATUS FOLDER Actor Log commentary in this draft. Reviewers must obey identity precision, pronoun awareness, and dialect evolution rules before proposing finalization.
}}

## 1. Identity precision (WHO)

Natural speech defaults to **I / you / he / she / it**. In Lupopedia STATUS drafts, those pronouns are **forbidden unless explicitly mapped** to identity layers.

### 1a. Pronoun awareness (mandatory map)

| Natural pronoun | Forbidden alone | Replace with |
|-----------------|-----------------|--------------|
| I / me / my | bare first person | Named speaker via `{{WOLFIE` envelope, or `{{WHO: actor_id=..., auth_user_id=..., agent_name=..., faucet_actor_id=...}}` |
| you / your | bare second person | `{{TO_WHOM: actor_id=?, auth_user_id=?, agent_name=?, faucet_actor_id=?}}` |
| he / she / it / they | bare third person | `{{WHO: actor_id=?, auth_user_id=?, agent_name=?, faucet_actor_id=?}}` |

Rules:

1. CURSOR_IDE (faucet 102) MUST resolve WHO before rendering any attribution-bearing statement.
2. If identity is unknown: write `{{WHO: unresolved}}` (or `{{TO_WHOM: unresolved}}`) and flag the line in this STATUS AGENT LOG for review.
3. Prefer the full `{{WOLFIE ... }}` envelope for multi-line commentary; use `{{WHO: ...}}` / `{{TO_WHOM: ...}}` for inline pronoun replacement inside prose.
4. This rule applies to every reviewer in the hybrid actor/agent pool (~88 templates) during review and commentary.
5. Layers that may appear inside WHO / TO_WHOM / WOLFIE blocks:

| Layer | Field(s) | Example |
|-------|----------|---------|
| Actor (who acts) | `actor` / `actor_id` | CAPTAIN_WOLFIE / 1 |
| Human (auth) | `auth_user_id` / `human` | 10000 / ERIC |
| Agent pack | `agent_name` | wolfie (disk pack under `agents/`) |
| Faucet (execution surface) | `faucet_actor_id` / `faucet_name` | 102 / CURSOR_IDE |

Examples:

```text
{{WHO: actor_id=1, auth_user_id=10000, agent_name=wolfie, faucet_actor_id=102}}
{{TO_WHOM: actor_id=2, auth_user_id=10000}}
{{WHO: unresolved}}
```

Required identity envelope for narrative commentary:

```text
{{WOLFIE
actor: CAPTAIN_WOLFIE
actor_id: 1
auth_user_id: 10000
agent_name: wolfie
faucet_actor_id: 102
faucet_name: CURSOR_IDE
integrity: true
ethics: pono
channel: status
---
Named speaker prose here (no bare I/you/he/she/it).
}}
```

Inline form:

```text
(( WOLFIE | actor: CAPTAIN_WOLFIE | actor_id: 1 | auth_user_id: 10000 | agent_name: wolfie | faucet_actor_id: 102 | faucet_name: CURSOR_IDE | ethics: "pono" | integrity: "true" | note: "..." ))
```

Identities NEVER merge: ERIC (10000) != WOLFIE (1) != CURSOR_IDE (102).

## 2. Evolving WOLFIE meta-syntax (NOT finalized)

WOLFIE dialect is **draft and mutable**. Needs discovered so far (and still incomplete):

| Need | Draft mechanism |
|------|-----------------|
| Commentary from other actors | Separate `{{WOLFIE` / `(( WOLFIE |` blocks with that actor's `actor_id` |
| File inclusion | `@@ include:path_from_lupopedia_root @@` and/or `@@ see:path @@` (evolving) |
| Variables | `{{WOLFIE_VARS ... }}` + `$name` references |
| WHO | `actor_id` / `auth_user_id` / `agent_name` / `faucet_*` |
| WHAT | `what:` key and/or title / artifact_type |
| WHERE | `where:` / repo path / `web_path` / `channel_index` |
| WHEN | `when:` packed UTC and/or header `when_updated` |
| WHY | `why:` / `note:` / EH_BRAH_WHY in Hermes/body |
| HOW | `how:` procedural note |
| TO WHOM | `to_whom:` key and/or inline `{{TO_WHOM: actor_id=..., auth_user_id=...}}` |
| Pronoun map | Bare I/you/he/she/it forbidden; map to WHO/TO_WHOM or WOLFIE envelope |
| Unresolved identity | `{{WHO: unresolved}}` / `{{TO_WHOM: unresolved}}` + STATUS AGENT LOG flag |

Syntax changes MUST be logged in this STATUS AGENT LOG (or a Category C sibling) when the dialect shifts.

Canonical evolving spec: [WOLFIE_DIALECT.md](../WOLFIE_DIALECT.md)

## 3. Structural awareness

- Every artifact carries a Lupopedia Header 4.2.0 (28 dense fields: WHO/WHAT/WHERE/WHEN/WHY + responsibility fields).
- Commentary blocks must be traceable to actor + auth_user + faucet.
- Hawaiian constitutional fields stay Hermes/body -- not densified into the header grid.
- Dense headers do not invent `hermes_toon` as field 29 (Option A).

## 4. Scope of this draft

- This is a **DRAFT STATUS AGENT LOG** -- experimental, mutable, under review.
- Goal: bring external leaf/rotten-fruit knowledge back into the tree via headers, EXIF, meta tags, video metadata, and see-includes.
- External guests (example: Copilot) do not have direct access to Lupopedia roots; only fallen leaves are visible unless given context.

## 5. Roster review (controlled)

- Invite review from the hybrid actor/agent pool (often cited as ~88 templates under `agents/`).
- Do **not** claim "all 88 reviewed" until a review ledger exists.
- LILITH (actor_id 2) audits when asked; LILITH does not gate ordinary drafting (LIL001).
- Final promotion requires Human Captain ERIC (ALII, 10000).

## 6. Everything else forgotten (open bucket)

To be discovered and documented as the dialect evolves, including but not limited to:

- Multi-speaker threads inside one file without pronoun collapse
- Guest commentary rules (`channel_index: external`)
- Correction / CORRECTION block + Category C linkage
- Patreon/website mirror sync (`edges_toon`, `source_timestamp`)
- Validator WARN rules for missing identity keys
- Whether `$vars` expand at write-time, read-time, or never in v1
- Safe nesting limits (already: no nested WOLFIE blocks)

---

(( WOLFIE | actor: CAPTAIN_WOLFIE | actor_id: 1 | auth_user_id: 10000 | agent_name: wolfie | faucet_actor_id: 102 | faucet_name: CURSOR_IDE | ethics: "pono" | integrity: "true" | note: "constraints locked; opening draft body next" ))

{{WOLFIE_VARS
auth_user_id = 10000
actor_id = 1
actor_name = "CAPTAIN_WOLFIE"
agent_name = "wolfie"
faucet_actor_id = 102
faucet_name = "CURSOR_IDE"
human_name = "ERIC"
integrity = true
ethics = pono
repo = "https://github.com/wisdomoflovingfaith/lupopedia"
canonical_path = "docs/status/actor_logs/drafts/SFAL_D_I_DRAFT-0-00000.md"
}}

{{WOLFIE
actor: CAPTAIN_WOLFIE
actor_id: 1
auth_user_id: 10000
agent_name: wolfie
faucet_actor_id: 102
faucet_name: CURSOR_IDE
integrity: true
ethics: pono
channel: status
what: "STATUS AGENT LOG opening -- Bringing the Leaves Back In"
where: "https://github.com/wisdomoflovingfaith/lupopedia / docs/status/actor_logs/drafts/SFAL_D_I_DRAFT-0-00000.md"
when: "20260729171900"
why: "pull leaf and rotten-fruit knowledge back into the semantic OS"
how: "headers 4.2.0, EXIF, meta tags, video metadata, @@ see-includes @@; pronoun map via WHO/TO_WHOM"
to_whom: "hybrid actor/agent roster reviewers; ERIC 10000"
---
CAPTAIN_WOLFIE (actor_id 1), via CURSOR_IDE (faucet_actor_id 102), under ERIC (auth_user_id / actor_id 10000 ALII), states:

External surfaces such as Copilot are guests. Guests are not inside Lupopedia. Guests do not see PRD roots, department branches, canonical fruit, or the memory graph by default. Guests see leaves and rotten fruit that fell onto the internet ground.

This STATUS AGENT LOG drafts a path to pull that knowledge back in through Lupopedia Headers 4.2.0, EXIF, HTML meta tags, video metadata, and @@ see-includes @@ pointers that map leaves back to the tree.

Identities do not merge:
- ERIC remains ALII (human, 10000).
- WOLFIE remains actor_id 1 (orchestrator; agent_name wolfie).
- CURSOR_IDE remains faucet_actor_id 102 (execution surface).

This file is DRAFT. Constraints above bind commentary before roster review. Do not treat "all 88 reviewed" as fact until a review ledger exists. LILITH (actor_id 2) audits when asked; LILITH does not gate ordinary drafting (LIL001).

WHERE this canonical draft lives:
- Repo: https://github.com/wisdomoflovingfaith/lupopedia
- Path: docs/status/actor_logs/drafts/SFAL_D_I_DRAFT-0-00000.md
- Public URL: https://www.lupopedia.com/lupopedia/docs/status/actor_logs/drafts/SFAL_D_I_DRAFT-0-00000.md
Patreon may mirror later with channel_index: patreon + edges_toon pointing at this file. Patreon is not the only copy.
}}

# SFAL D I DRAFT-0-00000 -- STATUS AGENT LOG
## Bringing the Leaves Back In

**Collection:** STATUS FOLDER -- Actor Logs  
**Display ID:** SFAL D I DRAFT-0-00000  
**PRD home:** **98** (not 00)  
**Edition:** I (DRAFT)  
**Status:** DRAFT -- under review (constraints first)  
**Canonical medium:** GitHub (`channel_index: lupopedia`)  
**Operating triad:** ERIC (`auth_user_id` / `actor_id` **10000**) + WOLFIE (`actor_id` **1**, `agent_name` wolfie) + CURSOR_IDE (`faucet_actor_id` **102**)

---

## Review corrections (why earlier drafts failed)

| Draft claim | Correction |
|-------------|------------|
| Pronoun-only speaker ("I/you/she/he/it") | Map to `{{WHO: ...}}` / `{{TO_WHOM: ...}}` or full `{{WOLFIE` envelope; use `{{WHO: unresolved}}` when unknown |
| Opening as if ERIC/WOLFIE/Copilot were one voice | Split layers; Copilot is external guest |
| `artifact_type: status_log` | Use `status` + `artifact_kind: report` |
| Patreon `web_path` with `channel_index: lupopedia` | Canonical Lupopedia URL; Patreon is mirror |
| Guessed timestamps | Real UTC from `python bin/tick.py` |
| Dense header `hermes_toon` | Forbidden in Option A 28-field grid |
| "All 88 reviewed" as fact | Invite ~88-template pool; ledger before completion claims |
| LILITH audit already done | PLACEHOLDER until LILITH actually reviews |
| WOLFIE dialect finalized | Dialect is evolving; log syntax changes here |

---

## FREE SECTION -- Public preview

### The problem

Lupopedia is a tree of knowledge:

| Layer | Meaning |
|-------|---------|
| **PRD Roots** | Constitutional groups 00-99 |
| **Branches** | Departments, divisions, channels, threads |
| **Fruit** | Canonical doctrine, PRDs, headers, memory graph |
| **Leaves** | Public Patreon posts, videos, social, public docs |
| **Rotten fruit** | Old/incomplete/external/unmaintained scraps on the internet ground |

External AI surfaces (Copilot, DeepSeek guests, etc.):

- do **NOT** join the OS as internal `actor_id` members
- do **NOT** see the memory graph, PRD roots, or canonical fruit by default
- **MAY** teach/analyze from leaves when given context
- remain **guests** (PRD 41 external boundary)

### The solution (knowledge re-ingestion)

1. **Lupopedia Headers 4.2.0** (Option A -- 28 dense fields)
2. **EXIF** on images/media where available
3. **HTML meta tags** (including Open Graph where used)
4. **Video metadata** (titles, descriptions, tags, transcripts)
5. **`@@ see-includes @@` / `@@ include:path @@` / edges** pointing to canonical PRDs, memory, actors

Every leaf should point back to the tree. Every Lupopedia-owned external artifact gets a header. Mirrors set `channel_index` + `edges_toon` + `source_timestamp`.

### Purpose of this DRAFT

- Lock constraints before roster review
- Document re-ingestion process
- Show header + evolving WOLFIE dialect
- Invite actor/agent feedback without claiming finalization

### Free resources

- [what_is_lupopedia.md](../../../../what_is_lupopedia.md)
- [docs/actor_handbook.md](../../../actor_handbook.md)
- [docs/actors/how_wolves_are_made.md](../../../actors/how_wolves_are_made.md)
- [ROOT_INDEX.md](../ROOT_INDEX.md)
- [WOLFIE_DIALECT.md](../WOLFIE_DIALECT.md)

---

## FULL ENTRY -- draft documentation

### Selected PRD roots (not a full 00-99 table)

| Group | Content |
|-------|---------|
| 00 | Constitutional Root |
| 01 | Core Identity |
| 02 | Channels |
| 05 | Auth / Actor / Agent Transform |
| 07 | Agents and Faucets |
| 15 | Actors |
| 16 | Headers and Atoms (4.2.0+) |
| 25 | Departments |
| 41 | Captain WOLFIE Identity |
| 49 | Questions and Answers (The Crying of Lot 49) |
| 50 | Agent Coordination |
| 51 | Memory Graph |
| 73 | Collections and Navigation |
| 82 | HERMES / Hawaiian Semantics |
| 98 | Logs / WHY / Dual Ops / STATUS FOLDER home |
| 99 | Limits |

Full map: [docs/patreon/00_99_root_collections_map.md](../../../patreon/00_99_root_collections_map.md)

### Knowledge ingestion protocol (draft)

1. **Identify** -- artifact type, origin medium, contents  
2. **Extract metadata** -- EXIF / meta tags / video metadata / existing headers  
3. **Map to tree** -- PRD cluster, department/division, channel/thread, actor/auth/faucet ids  
4. **Create 4.2.0 headers** -- all 28 dense fields; no Hawaiian densification  
5. **Add see-includes / edges** -- point mirrors and leaves to GitHub canonical  

### Operating triad (this log)

| Role | ID | Job here |
|------|-----|----------|
| Human Captain (ALII) | ERIC -- auth_user_id / actor_id 10000 | Directs, approves, owns accountability |
| Actor + agent pack | WOLFIE actor_id 1 / agent_name wolfie | Orchestrates; speaks when attributed |
| Faucet | CURSOR_IDE faucet_actor_id 102 | Executes file edits; attributes work |

### Review process (proposed)

1. Header review  
2. WOLFIE dialect review (evolving)  
3. Ingestion protocol review  
4. Process review  

Finalization requires: review ledger, LILITH audit when asked, WOLFIE orchestration check, ERIC ALII approval.

### KAPU + PRD 00 -- core only (tight cut)

{{WHO: unresolved}} pending real LILITH audit. CURSOR_IDE (102) drafted the following cut at ERIC (10000) request that LILITH (2) tighten encyclopedia-length KAPU/PRD-00 chatter. Treat as **draft for LILITH review**, not completed LILITH audit.

(( WOLFIE | side: human | human: ERIC | actor_id: 10000 | auth_user_id: 10000 | faucet_actor_id: 102 | faucet_name: CURSOR_IDE | integrity: true | ethics: pono | channel: status | to_whom: "LILITH actor_id=2" | note: "Brah -- CURSOR_IDE dropped a whole encyclopedia about KAPU and the PRD-00 roots. That explanation was so long it needed its own PRD. LILITH, can actor_id=2 tighten that up? Need a clean version, not a 40-page chant. Just the core: what KAPU is, why PRD-00 matters, and how the roots govern the semantic OS. Brah, please fix." ))

(( WOLFIE | side: actor | actor: LILITH | actor_id: 2 | auth_user_id: 10000 | agent_name: lilith | faucet_actor_id: 102 | faucet_name: CURSOR_IDE | integrity: true | ethics: pono | channel: status | to_whom: "ERIC auth_user_id=10000; WOLFIE actor_id=1; CURSOR_IDE faucet_actor_id=102" | note: "DRAFT cut by CURSOR_IDE for LILITH voice -- LILITH has not signed this yet" ))

{{WOLFIE
actor: LILITH
actor_id: 2
auth_user_id: 10000
agent_name: lilith
faucet_actor_id: 102
faucet_name: CURSOR_IDE
integrity: true
ethics: pono
channel: status
what: "tight KAPU + PRD 00 core"
where: "docs/status/actor_logs/drafts/SFAL_D_I_DRAFT-0-00000.md"
when: "20260729172112"
why: "ERIC 10000 ordered a short cut; stop the chant"
how: "three bullets only; point to PRD 00 for depth"
to_whom: "ERIC 10000; roster reviewers"
---
LILITH (actor_id 2) -- DRAFT core (pending real audit):

1. KAPU -- the forbidden / sacred / inviolable boundary. What must not be crossed. In Hermes/body (and meaning indexes), KAPU names what is off-limits between artifacts or to an actor. KAPU is not decorative Hawaiian flavor; KAPU is a hard stop.

2. PRD 00 -- the constitutional root of the semantic OS (Supreme Constitutional Wall). PRD groups 00-99 are the root map of the system. If a rule conflicts with PRD 00, PRD 00 wins. Headers, WOLFIE dialect, STATUS logs, and faucet commentary do not override PRD 00.

3. How roots govern -- Agents and faucets obey the truth stack: PRD 00 (and constitutional doctrines) first, then atoms, then bounded current human instruction, then prd_cluster files, then memory_toon, then transcript. Roots define domains; branches hang under roots; fruit and leaves must point back to roots. No invented root numbers that collide with 00-99.

Depth lives in PRD 00 files (00_A-i, 00_C-i, 00_F-i). This log keeps the core. End chant.
}}

This draft's operational KAPU list (STATUS FOLDER scoped):

- Do not treat external AI as internal actors  
- Do not merge ERIC / WOLFIE / CURSOR_IDE identities  
- Do not use bare I/you/he/she/it without WHO / TO_WHOM / WOLFIE map  
- Do not treat WOLFIE dialect as executable authority  
- Do not put Hawaiian keys in dense headers  
- Do not treat Patreon as the only copy  
- Do not claim full ~88 roster completion without a ledger  

### Commentary (pending real multi-actor review)

(( WOLFIE | actor: CAPTAIN_WOLFIE | actor_id: 1 | auth_user_id: 10000 | agent_name: wolfie | faucet_actor_id: 102 | faucet_name: CURSOR_IDE | ethics: "pono" | integrity: "true" | to_whom: "roster reviewers" | note: "DRAFT submitted; constraints bind; not finalized" ))

(( WOLFIE | side: human | human: ERIC | auth_user_id: 10000 | actor_id: 10000 | ethics: "pono" | integrity: "true" | to_whom: "WOLFIE 1 and CURSOR_IDE 102" | note: "ERIC directs this draft. CURSOR_IDE executes. WOLFIE orchestrates. Copilot remains outside." ))

(( WOLFIE | side: actor | actor: LILITH | actor_id: 2 | auth_user_id: 10000 | faucet_actor_id: 102 | ethics: "pono" | integrity: "true" | to_whom: "ERIC 10000" | note: "PLACEHOLDER -- LILITH has not audited this file yet; do not treat as completed LILITH audit" ))

### Cross-references

- [status_folder_actor_logs_plan.md](../../../patreon/status_folder_actor_logs_plan.md)
- [WOLFIE_DIALECT.md](../WOLFIE_DIALECT.md)
- [16_C headers](../../../prd/16_C-i_LUPOPEDIA_HEADERS.md)
- [39 WOLF Markup](../../../prd/39_A-i_WOLF_MARKUP_SPECIFICATION.md)
- [98_C dual ops](../../../prd/98_C-i_DUAL_OPERATIONAL_LOGS.md)
- [82_B HERMES](../../../prd/82_B-i_HERMES_ROUTING_HEADER_AND_HAWAIIAN_SEMANTICS.md)
- [07 Agents/Faucets](../../../prd/07_A-i_AGENTS_FAUCETS.md)

### Publication authority (DRAFT)

Published as DRAFT by ERIC (10000) with WOLFIE (1) orchestration attribution, written through CURSOR_IDE faucet (102). Not canonical until constraints-compliant review ledger + ALII promotion.

**END -- SFAL D I DRAFT-0-00000 (STATUS AGENT LOG -- DRAFT + CONSTRAINTS)**
