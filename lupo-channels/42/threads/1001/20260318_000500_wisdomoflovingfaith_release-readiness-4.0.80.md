---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  lupopedia.schema: "thread"
  system_version: "4.0.80"
  file_path_from_root: "lupo-channels/42/threads/1001/20260318_000500_wisdomoflovingfaith_release-readiness-4.0.80.md"
  web_path: "http://www.lupopedia.com/lupo-channels/42/threads/1001/20260318_000500_wisdomoflovingfaith_release-readiness-4.0.80"
  last_modified_utc: "20260318"
  channel_id: 42
  thread_id: 1001
  channel_name: "Lupopedia Development (general)"
  actor_id: 1000
  actor_name: "wisdomoflovingfaith-at-gmail-com"
  faucet_name: "human"
  delegation_chain: "wisdomoflovingfaith-at-gmail-com:root"
  artifact_type: "thread"
  artifact_kind: "directive"
  purpose: "Release readiness audit for 4.0.80 and planning transition to 4.0.81 database-backed channel system"
  tags: ["release", "4.0.80", "wolfie", "planning", "channels", "database", "critical"]
  message_type: "directive"
  dialog_message_id: 20260318000500
---

# file: Human directive — 4.0.80 release readiness + 4.0.81 transition

# Directive — 4.0.80 Release Readiness + 4.0.81 Transition Planning

**WOLFIE,**

We need to stabilize and close out **version 4.0.80** properly.

Right now, work is fragmented across:

- `CHANGELOG.md`
- `TODO.md`
- `plan.md`
- `lupo-docs/versions/4.0.80/PLAN.md`
- and many artifacts under `lupo-channels/42/`

---

## Core issue

Coordination has relied heavily on:

```text
lupo-channels/{channel_id}/...
```

as a **working source of truth**, but:

- much of this work is **not** reflected consistently in CHANGELOG / TODO / PLAN
- external systems (including external AI) **cannot read** those files
- database-backed channel usage is partial; files remain primary for many agents

This disconnects **what is done** from **what is documented as done**.

---

## Objective

Produce a **clear, authoritative release readiness picture for 4.0.80** and define **4.0.81** direction (DB-first channels).

---

## Required analysis

### 1. Review all sources

- Root: `CHANGELOG.md`, `TODO.md`, `plan.md`
- Version plan: `lupo-docs/versions/4.0.80/PLAN.md`, `TODO.md` there if present
- Channel artifacts: `lupo-channels/42/threads/1001/`, `threads/1002/`, plus `prompts/`, relevant `broadcasts/` / `content/` as needed
- Output of: `python lupo-scripts/hermes_scan_threads.py --channel 42 --threads 1001,1002` (inventory aid)

### 2. Reconcile state

- **Complete (possibly undocumented in CHANGELOG):** channel-only work to fold into changelog before release
- **Partial:** started not finished; enforced in code but not in CI; documented but not shipped (or reverse)
- **Pending:** TODO/plan checkboxes; blockers for calling 4.0.80 “released”

### 3. Gaps

- Mismatches channel ↔ root docs
- Missing changelog bullets
- Enforcement gaps (validation, CI `--mode enforce`, impersonation doctrine closure, etc.)
- **Top 50 / TOON ground-truth** status (thread 1001/1004 artifacts)

---

## Deliverable (CRITICAL)

**One** authoritative artifact in thread **1001**, canonical filename:

`YYYYMMDD_HHIISS_wolfie_4.0.80_release-readiness.md`

### Content must include

1. **4.0.80 status summary** — confidence to ship; “TBD” vs target date if known  
2. **Completed work (canonicalized)** — including work visible only in `lupo-channels/` that must be mirrored into CHANGELOG  
3. **Remaining work (required before 4.0.80)** — P0 / P1 / P2  
4. **Deferred to 4.0.81** — explicitly out of 4.0.80 scope  

### 4.0.81 planning (in same artifact or clear subsection)

- **Intent:** channels driven primarily by **`lupo_dialog_threads` / `lupo_dialog_messages`**; filesystem = export/cache/secondary  
- **Required for 4.0.81:** ingestion pipeline; DB ↔ file sync rules; external/read API model so state is not file-only  

---

## After your report

**HERMES** will read your artifact, emit prompts, and route remaining work.

---

## Final instruction

**WOLFIE:** Unify system state. Define **DONE** vs **remains**. Ship **4.0.80** cleanly; prepare **4.0.81** database-driven channels.

---

**From:** Human actor **1000** · Channel **42** · Thread **1001** · **CRITICAL**
