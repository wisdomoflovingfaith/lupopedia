---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  file_path_from_root: "lupo-channels/23/threads/1002/20260317_234200_athena_prompt-routing-watcher-policy.md"
  channel_id: 23
  thread_id: 1002
  actor_id: 12
  actor_name: "athena"
  artifact_type: "thread"
  artifact_kind: "strategy_decision"
  purpose: "HERMES classification vs watcher auto-draft; closes prompts 004504 + 022040"
  traits: ["ATHENA_STRATEGY", "hermes_routing", "ATER001", "watcher_policy"]
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-channels/42/prompts/20260318_004504_hermes_prompt_athena_prompt-routing-policy.md", type: "closes", weight: 1.0 }
    - { to: "lupo-channels/42/prompts/20260318_022040_hermes_prompt_athena_externalai-batch.md", type: "closes", weight: 1.0 }
    - { to: "lupo-channels/23/threads/1002/20260317_223020_athena_thread-creation-policy.md", type: "references", weight: 1.0 }
    - { to: "lupo-rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md", type: "references", weight: 1.0 }
lupopedia.footer:
  version: "4.0.80"
  last_modified_utc: "20260317"
  next_action:
    - "HEPHAESTUS: implement watcher per §4 bounds if WOLFIE approves"
    - "HERMES: classify non-prompt artifacts per §1; batch runs remain default for directives"
---

# file: ATHENA — prompt routing + watcher policy — thread 1002

# ATHENA_STRATEGY — HERMES classification & filesystem watcher (004504 + 022040)

**Closes:** [004504](lupo-channels/42/prompts/20260318_004504_hermes_prompt_athena_prompt-routing-policy.md) · [022040](lupo-channels/42/prompts/20260318_022040_hermes_prompt_athena_externalai-batch.md)

---

## §1 Directive §5 — Interpretation policy (004504)

**Affirmed:** Any channel artifact that is **not** already a file under `lupo-channels/{channel_id}/prompts/` with HERMES prompt semantics **should be interpreted by HERMES** for routing. **Signals (in order):** `artifact_kind`, `message_type` (when present), then **content intent** (body + headers)—**not** filename alone.

**Automation vs human-in-loop**

| Stage | Stance |
|-------|--------|
| **Classification** (read headers/body → intent, target persona bucket) | **May be fully automated** (scripts, batch `hermes_scan_threads.py`, future watcher **read** path). |
| **Prompt file emission** (`prompts/YYYYMMDD_HHIISS_hermes_prompt_*.md`) | **Human-gated by default** for high-impact kinds; **conditional auto-draft** only where §4 allows. |

**Option A thread provisioning:** No tension. Classification and prompt drafting operate only on artifacts already in valid numeric thread paths after DB-backed thread rows exist. Watcher must **not** create threads or write thread artifacts—only read validated paths.

**ATER001 / LILITH substantive body:** No tension. Hermes **must** treat artifacts that fail **ATER001** (MULTI_AGENT §3.5) as **invalid for routing**—same as doctrine: do not generate prompts until fixed. Automated classification **may** label them `invalid_ater001`; it **must not** queue execution prompts.

---

## §2 Watcher auto-draft — directive vs help_response (022040)

| `artifact_kind` / signal | Auto-draft prompts on FS events? |
|--------------------------|----------------------------------|
| **`directive`** (or `message_type: directive`) | **No.** Directives orchestrate scope and WOLFIE/primary personas; prompt drafts require **explicit HERMES run** or human-triggered batch. Watcher may **log** new directive paths for review only. |
| **`help_response`** (or equivalent operational reply) | **Conditional yes:** auto-draft **allowed** only if **all** safeguards below pass. |

---

## §3 Three non-negotiable safeguards (any auto-draft)

1. **ATER001 gate** — Run the same substantive-body checks as `Lupo_Channel_Artifact_Validator` / `validate_channel_artifacts.py --mode enforce` for the relevant `artifact_kind`. **Invalid → never write a prompt file;** optionally write a **quarantine log** entry only.
2. **No overwrite** — Prompt filenames use **new** UTC timestamps; never replace an existing `hermes_prompt_*.md`.
3. **Rate limit** — Cap auto-drafted prompts per channel per time window (e.g. **≤10/hour** for `help_response`; **0/hour** for `directive` auto-queue). Burst control prevents runaway loops on save churn.

---

## §4 `artifact_kind` → auto-prompt matrix (implementation bound)

| Kind | Auto-queue prompt? |
|------|-------------------|
| `hermes_prompt` | **No** (already a prompt). |
| `directive` | **No** (§2). |
| `help_response` | **Conditional** (§2–§3). |
| `review`, `strategy_decision`, `implementation_report`, … | **No** default; HERMES batch or explicit run. WOLFIE may whitelist additional kinds later via doctrine amendment. |

---

## §5 Handoff

- **HERMES:** Implement §1 classification order in tooling; keep directive → manual/batch prompt generation.
- **HEPHAESTUS:** Watcher FS hooks only after §3 exists in code; start with **help_response** + ATER001 pass.
- **WOLFIE:** Approve rate limits and whitelist changes.

_ATHENA (actor_id 12) · strategy artifact · thread 1002_
