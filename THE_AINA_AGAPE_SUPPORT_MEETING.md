---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: THE_AINA_AGAPE_SUPPORT_MEETING.md
  web_path: https://www.lupopedia.com/lupopedia/THE_AINA_AGAPE_SUPPORT_MEETING.md
  status: active
  when_updated: '20260514173335'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/05/the-aina-agape-support-meeting.toon
  atoms_toon: null
  transcript_jsonl: 0/development/the-aina-agape-support-meeting
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: development
  federation_node_id: 0
  thread_key: ''
  lupopedia.schema: doctrine
  prd_cluster: 00_A_05_A_15_A_57_A_98_A
  title: 'The Lupopedia Twelve -- Aina, AGAPE, and WHY-aligned repair cycle'
  summary: 'Twelve dependency-ordered commitments linking auth_user, agent, actor, Aina, PRD 57 AGAPE, and PRD 98_A WHY files; repair doctrine, not a meeting script.'
---
# The Lupopedia Twelve (Aina, AGAPE, WHY)

This file is **repair doctrine**, not a calendar event. The twelve steps are **dependency-ordered habits**: you may arrive at them through a messy human week, a long thread, or a quiet read of `docs/why/` -- order still matters where **PRD 98_A** and **PRD 57** say it does. Think of it as **how the work learns**, not **how to book a conference room**.

**Normative anchors (unchanged law):**

1. **PRD 98_A** (`docs/prd/98_A-i_WHY_FILES_DOCTRINE.md`) -- `docs/why/` only, filename pattern, six-part causal chain, capture-before-correction, **`AGAPE BLOCKED: INSUFFICIENT_CONTEXT`** gate.
2. **PRD 57** (`docs/prd/57_A-i_AGAPE_RESILIENCE_DOCTRINE.md`) -- resilience and self-healing behavior doctrine tied to WHY logging.
3. **PRD 05** (`docs/prd/05_A-i_AUTH_USER_ACTOR_AGENT_TRANSFORMATION.md`) -- auth_user versus actor, department intersection, visitor chain, IDE facet rules.
4. **PRD 15** (`docs/prd/15_A-i_ACTORS.md`) -- actor lifecycle, act-as resolution, agent versus actor.
5. **IDENTITY_LAYERS_DOCTRINE** (`docs/doctrine/identity_layers_doctrine.md`) -- five layers; no conflation.
6. **Glossary** (`Glossary.md`, **Aina**) -- sustaining operational environment; see also **PRD 82** Hermes tables for **PONO**, **KAPU**, **KULEANA**, **PILAU** as routing tokens (not spiritual claims).

PR edits triggered by this cycle follow **PRD 98_A** section 4 order (PRD fix before code fix in the WHY template when both apply).

## Terms (short)

- **Aina:** Runtime ground -- filesystem, host, permissions, DB reachability, install config. **No doctrine outranks a refused write.** "Aina protection" here means: if production reality says **wait**, you default to **documentation and staged repair**, not heroic mutation (aligns with **PRD 57** graceful degradation spirit and **PRD 98_A** emergency note).
- **WHY file:** Immune record under **`docs/why/`** only, `why_YYYYMMDD_HHMMSS_<prd_cluster_slug>_<slug>.md` (UTC).
- **AGAPE (PRD 57):** Constitutional repair **logic** -- not emotion. Subset used with **PRD 98_A**: no repair narrative as "done" until the six-part chain is reconstructible; else emit **`AGAPE BLOCKED: INSUFFICIENT_CONTEXT`** and stop.

---

## Step 1 -- Humans and templates both need the bridge; only actors speak

In Lupopedia a **human** logs in as **`auth_user`**. That row is **not** the speaking identity. It supplies **login, accountability, permissions, department membership**. It does **not** speak in channels by itself.

An **AI agent** is a **template** (`lupo_agents`, `agents/{key}/`): model config, prompts, capabilities, defaults, metadata. It does **not** speak by itself.

**Speaking identity is the actor** (`lupo_actors`, `actor_id`). An actor is operational when the binding chain is real in product code and session policy:

`auth_user` + `agent` + **department** + **faucet** + **session** -> effective **`actor_id`**

The actor is who **speaks**, who shows in **`from_actor_id`**, who **acts**, who **owns attribution** in logs.

**First truth:** humans authorize and steer; agents supply automation templates; **actors are the bridge**. **No actor, no speech** in the channel architecture meant by **PRD 05** and **PRD 15**.

## Step 2 -- Aina is final authority over whether a write can land

**Aina** is the execution ground: paths, container, permission model, DB access, config on disk, **actual** conditions. If the environment returns **permission denied** or equivalent, the correct response is **technical humility** and a trace -- not ego, not doctrine override.

That is not mysticism; it means **runtime reality outranks intention**. No actor, agent, faucet, or human **outranks** the real execution ground. Mahalo here means: **accept the trace and change the plan**, not performative apology.

## Step 3 -- Remediation is handed to AGAPE, not to panic

**AGAPE** governs fallback ladders, graceful degradation, evidence-driven validation, **no heartbeat loops** as authority (**PRD 57**), **no ego patches**, and **no fix-first-understand-later** when **PRD 98_A** applies.

Systems fail: agents drift, humans rush, code breaks, docs lag. **AGAPE says: stop.** Reconstruct **why** before **what** to change. **No mutation without doctrine path.** **No patch before WHY** when the failure is constitutional or validator-driven.

## Step 4 -- Causal inventory before the WHY file body

A **WHY file** is **not** a diary. It is the **immune record**.

Before writing the narrative sections, reconstruct all six (**PRD 98_A**):

| Part | Question |
|------|----------|
| INTENT | What was supposed to happen (which PRD intent)? |
| WHO | Which auth_user, actor, agent, faucet (ids and roles)? |
| WHAT | Which action, mutation, query, or file? |
| WHERE | Department, channel, table, path? |
| WHEN | UTC `YYYYMMDDHHIISS`, session, sequence, thread? |
| HOW | Code path, wrong assumption, validator id, failure mode? |

If any part is missing: output exactly **`AGAPE BLOCKED: INSUFFICIENT_CONTEXT`** and list gaps. **Lupopedia prefers understand-first** over patch-fast when this gate applies.

## Step 5 -- Structural confession (precision, not theatre)

**Confession** here means **naming the layer violation** in testable language. Examples:

- Treated an IDE **faucet** as a human **auth_user**.
- Merged **`auth_user_id`** into **`actor_id`** mentally or in a query.
- Used **`agent_id`** where **`actor_id`** was required for speech or audit.
- Bypassed **department intersection** rules.
- Wrote a fix before reconstructing **INTENT**.
- Let an agent act **outside** its channel contract.
- Stored long-form truth only in **DB** when filesystem or PRD path was authoritative (anti-pattern depends on domain; name it explicitly).

Goal: **precision**, not shame. If you cannot name the layer, you cannot safely repair it.

## Step 6 -- Readiness: no patching before WHY

Hard gate aligned with **PRD 98_A**:

- No code change, schema change, hot patch, or "just this once" **as the closure of learning** until:
  - a **WHY** exists under `docs/why/`,
  - the six-part chain is present,
  - the **PRD gap** is identified when doctrine drifted,
  - and **AGAPE**-compatible repair path is stated (evidence-first).

**Fix before WHY** teaches nothing repeatable. **Fix after WHY** can be imported into validators, PRDs, and templates.

## Step 7 -- AGAPE validates; it does not "forgive"

Validation checks may include: identity boundaries, auth_user versus actor separation, department intersections, faucet **facet** `actor_id`, agent pairing rules, session chain, **Aina** permissions, **prd_cluster** read order versus change.

If validation fails, the response is **not** "try harder" -- it is **return to Step 4** because inventory was incomplete or wrong.

## Step 8 -- List every harmed layer; prove "unaffected"

Use severity **None / Low / Medium / High / Critical** plus **one evidence sentence** per non-None row.

| Layer | Example evidence |
|-------|-------------------|
| auth_user | Session row, login audit |
| actor | Registry, `from_actor_id` |
| agent | `lupo_agents`, `agents/` tree |
| faucet | `lupo_agent_faucets`, facet registry |
| department | `lupo_auth_user_departments`, `lupo_actor_departments` |
| channel / thread | `channel_id`, thread manifest |
| session | Session fields per identity model |
| transcript / audit | `lupo_dialog_messages`, logs |
| memory file | `memory_toon` path, TOON pair |
| PRD / doctrine | Which file must change first |
| Aina | Permission denied, missing path, wrong prefix |

**Unaffected** layers need an explicit **proof sketch** ("no rows written", "mental model only", "validator only"). This blocks fake repair.

## Step 9 -- Amends, unless Aina forbids motion

**Amends are technical:** restore attribution, fix registry rows, repair department bindings, fix paths, repair memory sidecars, extend the **WHY** with aftermath, **update PRDs before code** when doctrine led the bug.

If **Aina** says **do not touch production yet**, you **do not** hero-commit. You **degrade gracefully**: documentation, staged patch, operator checklist (**PRD 57**, **PRD 98_A** emergency path when used must be stated in the WHY).

## Step 10 -- Causal inventory as daily engineering, not only disasters

Every non-trivial commit should answer: **what changed, why, which PRDs authorize it, which actors and departments own it, which channel and files carry truth, which DB rows are pointers only.**

If a boundary is unclear: **write it down**. If a rule is missing: **update doctrine or PRD**. If the same mistake repeats: record it as **PILAU** (bad state) in the appropriate audit or WHY pattern. If the system extracted durable learning: that is **AGAPE-shaped** closure, not vibes.

## Step 11 -- Conscious contact means operational awareness

Not supernatural -- **disciplined engineering**:

- Read **PRDs in cluster order** when headers supply `prd_cluster`.
- Reconstruct **intent** instead of assuming.
- Search **`docs/why/`** for prior incidents.
- Respect identity layers; verify department intersections and faucet attribution; confirm session chain.

**Operational enlightenment (testable):** every repair traces to a **WHY** or to a PRD change with changelog discipline.

## Step 12 -- Carry doctrine forward by teaching, not by preaching

Teach: how **auth_users** stay accountable without pretending to be **actors**; how **departments** scope power; how **faucets** attribute; how **WHY files** precede fixes; how **AGAPE** blocks sloppy repair.

Channels for transmission: **PRDs**, **doctrine**, **`docs/why/`**, **validators**, **templates**, **worked examples**. **Captain's Log** (**PRD 98_B**) remains **human entertainment** -- good for story, **zero** normative authority over code; do not merge Log lore into install SQL.

---

## Closing map (Hermes tokens, technical reading)

| Token | Role in repair speech (see **PRD 82** / translation guides) |
|-------|--------------------------------------------------------------|
| **PILAU** | Corrupt or wrong state worth naming before cleanup. |
| **AGAPE** | Wisdom extracted through evidence-first repair (**PRD 57**). |
| **PONO** | Corrected target state after repair. |
| **KAPU** | Hard boundary that prevents recurrence (policy or validator). |
| **KULEANA** | Who must carry the next fix or audit row. |
| **Aina** | What can **actually** happen on the ground. |

**Cycle:** name **PILAU**, record **WHY**, apply **AGAPE** discipline, reach **PONO**, lock **KAPU**, assign **KULEANA**, let **Aina** accept or refuse the write.

## Identity collapse quick reference (from `tmp/DRAFT.md` pattern)

- **Wrong:** "Logged in human speaks without `actor_id` resolution."
- **Right:** Speech and audit use **`actor_id`** from session + department + bindings; agents are templates; IDE uses **facet** `actor_id` (**PRD 05**); no synthetic `lupo_auth_users` for IDE products.

## Machine-checkable closure (when a repair batch may ship)

- [ ] Six parts present in the WHY (or explicit **`AGAPE BLOCKED`** with listed gaps only while blocked).
- [ ] WHY path legal and name matches **PRD 98_A**.
- [ ] Layer table complete; "unaffected" rows have proof sketch.
- [ ] No silent production mutation: Aina-touching changes appear in change control or WHY emergency clause.

## Cross-references

- PRD 98_A, PRD 57, PRD 05, PRD 15 (paths as above).
- PRD 98_B -- Captain's Log isolation (`docs/prd/98_B-i_THE_CAPTAINS_LOG_HUMAN_ONLY_ENTERTAINMENT_LAYER.md`).
- `tmp/DRAFT.md` -- example identity-collapse narrative and matrix seed (2026-05-14).

This output complies with Lupopedia Constitutional Root Rules.
