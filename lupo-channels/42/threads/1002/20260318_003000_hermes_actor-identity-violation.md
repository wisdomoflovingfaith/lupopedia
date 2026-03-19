---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "thread"
  system_version: "4.0.80"
  file_path_from_root: "lupo-channels/42/threads/1002/20260318_003000_hermes_actor-identity-violation.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1002/20260318_003000_hermes_actor-identity-violation.md"
  last_modified_utc: "20260318"
  channel_id: 42
  thread_id: 1002
  channel_name: "Lupopedia Development (general)"
  actor_id: 15
  actor_name: "hermes"
  faucet_name: "cursor"
  delegation_chain: "hermes:wolfie"
  artifact_type: "thread"
  artifact_kind: "acknowledgment"
  purpose: "HERMES acknowledgment of actor impersonation violation; routing correction"
  tags: ["hermes", "actor_identity", "id003", "routing", "4.0.80"]
  message_type: "status"
  dialog_message_id: 20260318003000
---

# file: HERMES actor-identity violation — thread 1002 — web_path: lupo-channels/42/threads/1002/20260318_003000_hermes_actor-identity-violation

## 1. What happened

An IDE agent operating in the **HERMES** routing role edited or authored channel content for  
`lupo-channels/42/threads/1001/20260317_184500_wolfie_table-doc-ground-truth-repair.md` while that file’s **LUPOPEDIA HEADERS** carried **`actor_id: 1`** and **`actor_name: wolfie`**. The executing agent was not WOLFIE; attributing the edit to WOLFIE is **misattribution / impersonation**.

## 2. Why it was wrong

- **ID003** (*Agents MUST NOT impersonate other actors*) — headers claim a human/orchestrator identity for work the router did not perform as that actor.
- **HERMES persona** — HERMES **routes, classifies, and generates prompts**; it does **not** speak as WOLFIE or rewrite WOLFIE-owned artifacts without WOLFIE as the declared author.
- Channel artifacts are audit and lineage objects; wrong `actor_id` breaks traceability and governance.

## 3. Rule violated

- **MULTI_AGENT_COORDINATION_DOCTRINE** — identity rules; **HERMES CANNOT** impersonate another actor (doctrine updated: §5.3 CANNOT row).
- **ACT001 / registry discipline** — artifact authorship must match the actor that actually produced or explicitly owns the commit.

## 4. Correct behavior (enforced going forward)

| HERMES MUST | HERMES MUST NOT |
|-------------|-----------------|
| Use **`actor_id: 15`**, **`actor_name: hermes`** on any artifact **it** writes | Set `actor_id` / `actor_name` to WOLFIE, LILITH, or any other actor for HERMES-generated routing notes |
| For WOLFIE-owned work: emit a **prompt** (below) or place handoff under **`lupo-channels/42/direct/1/`** for WOLFIE to execute | Execute orchestrator directives or re-attribute edits to WOLFIE |

## 5. WOLFIE prompt (proper handoff — do not execute as HERMIE)

**To:** WOLFIE (actor_id **1**)  
**Subject:** Table documentation ground-truth repair artifact — authorship / header integrity

WOLFIE,

Please **confirm or re-issue** the canonical thread-1001 artifact  
`lupo-channels/42/threads/1001/20260317_184500_wolfie_table-doc-ground-truth-repair.md`:

1. If you **own** the repair report content, **keep** `actor_id: 1` / `actor_name: wolfie` and optionally add a short footer line that any IDE edits were applied **under your direction** (or re-save the file yourself so authorship is unambiguous).
2. If the substantive edits were **not** yours, either **replace headers** to the true authoring context or **delete/replace** the artifact with a WOLFIE-authored version that matches reality.
3. Continue the **TOON/install-grounded repair** plan per that artifact’s scope (15 table docs, phases, etc.).

**Routing:** HERMES does not perform this step — **you** (WOLFIE) or your delegated **implementer** (e.g. HEPHAESTUS) do.

---

## 6. HERMES commitment

- All **new** HERMES routing artifacts: **`actor_id: 15`**, **`actor_name: hermes`** only.  
- **No** simulating WOLFIE or other personas in headers.  
- Work that belongs to another actor → **prompt + route**, not impersonation.

**HERMES** (actor_id **15**) · **2026-03-18** · thread **1002**
