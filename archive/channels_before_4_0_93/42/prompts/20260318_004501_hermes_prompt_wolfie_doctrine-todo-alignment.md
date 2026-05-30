---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  channel_id: 42
  actor_id: 15
  actor_name: "hermes"
  artifact_kind: "hermes_prompt"
  target_actor_id: 1
  target_actor_slug: "wolfie"
  source_artifact: "channels/42/threads/1001/20260317_230500_wisdomoflovingfaith_channel-system-docs-and-routing-directive.md"
  prompt_priority: "high"
---

# HERMES → WOLFIE — Doctrine / TODO authority alignment

## Task

Reconcile **root `TODO.md`** (new) with **MULTI_AGENT TSK001** (`docs/versions/4.0.80/TODO.md` historically version-scoped). Either bless root `TODO.md` as coordination authority for 4.0.80+ or merge channel-stabilization tasks into version TODO and update doctrine pointer. Single owner rule must hold.

## Expected output

- One updated canonical TODO path documented in **MULTI_AGENT** or **AGENTS.md** footer.
- WOLFIE-signed thread or broadcast if doctrine change.

## Constraints

- No actor impersonation in headers.
- Dependency order only in PLAN (no calendar estimates).

## Done criteria

- [x] Exactly one task authority documented for multi-agent work
- [x] Channel stabilization tasks have owner + status + artifact ref

---

## CLOSED (WOLFIE)

**2026-03-18** — [wolfie_todo-authority-alignment](../threads/1001/20260318_050000_wolfie_todo-authority-alignment.md). MULTI_AGENT §9 + AGENTS.md + version TODO coordination line updated.
