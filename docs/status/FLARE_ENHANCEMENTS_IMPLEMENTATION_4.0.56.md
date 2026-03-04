# FLARE Enhancements Implementation — v4.0.56

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "docs/status/FLARE_ENHANCEMENTS_IMPLEMENTATION_4.0.56.md"
  last_updated_utc: "20260303"
  system_version: "4.0.56"
  channel_id: 42
  actor_id: 1003
  delegation_chain: "1003:10000"
  artifact_type: "report"
  artifact_kind: "documentation"
  purpose: "Implementation checklist for FLARE enhancements and actor_id resolution (Cursor takeover)"
  lupo_agent: "cursor"
flare.footer:
  last_verified: "20260303"
  last_verified_by: "cursor"
---

## 1. Scope

This document summarizes the **implementation** of FLARE enhancements for v4.0.56 and the **Actor ID Resolution** logic applied to IDE agents after Cursor (1003) took over Antigravity (1004) tasks.

## 2. Flame / FLARE Enhancements (Antigravity → Cursor)

- **flame.init / flame.close**: Typed actions, execution modes (`advisory` / `required`), canonical block order. See `lupo-docs/doctrine/FLARE/FLARE_ENHANCEMENTS_PLAN_4.0.56.md` and `lupo-docs/status/FLARE_ENHANCEMENTS_REPORT_4.0.56.md`.
- **flame.see**: URL-to-path mappings; index and CLI `lupo see`. See FLARE_DOCTRINE.md Section 17.
- **flare.conditional**: Guards (allow/deny, time_window, conditions) and brief (5W1H). See FLARE_DOCTRINE.md Section 16.
- **Tooling**: `flare_header_template.txt`, `flare_apply.py`, `flare_validate.py` updated for v4.0.56; FLARE_DOCTRINE.md Sections 14–17.

## 3. Actor ID Resolution (Implemented)

**Rule**: The `actor_id` in channel messages, artifacts, prompts, FLARE headers, tasks, and commits must be the **logged-in IDE user**, never hardcoded.

**Resolution order** (implemented in `lupo-tools/vsx-extension/src/lupopedia/identity.ts`):

1. **Logged-in Lupopedia user** — `.lupo_actor` in workspace root (actor_id, name).
2. **IDE auth token / stored identity** — `loadIdentity()` from extension globalState (registry or prior lookup).
3. **Default fallback** — `10000` (Captain Wolfie). Delegation chain set to `"<actor_id>:10000"`.

**Extension changes** (Cursor 1003):

- All call sites that need **current user attribution** now use `resolveEffectiveActorId()` instead of `loadIdentity()`:
  - `lupopedia.acquireLock` / `lupopedia.releaseLock`
  - `lupopedia.repairDelegationChain`
  - FLIP editor panel (createOrShow) — identity passed for header/metadata
  - `lupopedia.getStatus` — reports effective `actor_id` and `lupo_agent: cursor` post-takeover
- `channels.ts` already used identity from caller; callers now pass the result of `resolveEffectiveActorId()` where appropriate (e.g. channel panel already used identity from activation).

**Doctrine**: FLARE_DOCTRINE.md **Section 18** — Actor ID Resolution for IDE Agents.

## 4. Faucets and flame.close

Faucet resolution for actors uses:

- Per-actor: `lupo-database/lupopedia/channels/lupo-channels/<channel_id>/actors/<actor_id>/faucets.json`
- ID-scoped: `lupo-database/lupopedia/actors/faucets/<agent_faucet_id>/faucet.json` and `by_actor.json` manifest.

`flame.close` post-actions (e.g. register_completion) and any faucet create/select in the IDE should use the resolved effective actor and, when needed, the actors/faucets directory. See `docs/status/FAUCET_DIRECTORY_IMPLEMENTATION_REPORT.md`.

## 5. References

| Document | Purpose |
|----------|---------|
| `lupo-docs/doctrine/FLARE/FLARE_DOCTRINE.md` | Canonical FLARE doctrine; Sections 14–18 (lifecycle, ordering, guards, flame.see, actor_id resolution). |
| `lupo-docs/doctrine/FLARE/FLARE_ENHANCEMENTS_PLAN_4.0.56.md` | Plan for flame.init/close and related blocks. |
| `lupo-docs/status/FLARE_ENHANCEMENTS_REPORT_4.0.56.md` | Refined report; Section 5 documents actor_id resolution and Cursor takeover. |
| `docs/status/ANTIGRAVITY_TASK_TAKEOVER_REPORT.md` | Takeover summary and completed tasks. |

---

**Actor**: 1003 (Cursor)  
**Date**: 2026-03-03
