---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  file_path_from_root: "lupo-channels/42/prompts/README.md"
  channel_id: 42
  actor_id: 15
  actor_name: "hermes"
  artifact_type: "documentation"
  artifact_kind: "hermes_prompt_convention"
  purpose: "HERMES-generated execution handoffs; naming and YAML contract"
---

# file: Channel 42 prompts — HERMES handoff layer

## Purpose

`lupo-channels/{channel_id}/prompts/` holds **actionable prompts** written by **HERMES** (actor_id **15**) only. Each file is a structured handoff to a **target actor** to execute work; it is **not** a substitute for that actor’s own channel artifacts after execution.

## Legacy Compatibility During 4.0.88 Refactor

This channel-wide prompts directory is a **legacy-compatible execution surface**.

- It remains valid for existing 4.0.80 to 4.0.88 HERMES handoff artifacts.
- The forward target structure moves prompt artifacts under `threads/{project_slug}/prompts/`.
- No existing prompt files should be moved out of `lupo-channels/42/prompts/` without a dedicated migration batch and edge reconciliation audit.
- New governance work for the refactor is piloted under `lupo-channels/1_channel_refactor_governance/threads/channel_refactor_4_0_88/prompts/`.

## Naming (canonical)

```
YYYYMMDD_HHIISS_hermes_prompt_{target_actor_slug}_{purpose}.md
```

- UTC timestamp, **`hermes`**, literal **`prompt`**, target slug (`wolfie`, `hephaestus`, `lilith`, `athena`, …), purpose (`[a-z0-9_-]+`).
- Example: `20260318_004501_hermes_prompt_wolfie_doctrine-alignment.md`

## Semi-automation (MVP)

From a thread artifact path, generate a draft prompt (HERMES-shaped YAML; **review** before treating as canonical):

```bash
python lupo-scripts/draft_hermes_prompt_from_artifact.py \
  --artifact lupo-channels/42/threads/1001/ARTIFACT.md --target wolfie --purpose short_slug --write
```

See WOLFIE stabilization: `../threads/1001/20260318_012000_wolfie_channel-hermes-mvp-stabilization.md`.

**Release readiness (4.0.80):** Human directive `../threads/1001/20260318_000500_wisdomoflovingfaith_release-readiness-4.0.80.md` → WOLFIE handoff **`20260318_032100_hermes_prompt_wolfie_4.0.80-release-readiness.md`**.

**Batch routing pattern:** External AI directive `../threads/1002/20260317_235500_externalai_hermes-routing-directive.md` → HERMES report `../threads/1002/20260318_022000_hermes_externalai-routing-batch-1001-1002.md` + grouped `02201x_hermes_prompt_*_externalai-batch.md`. Inventory: `python lupo-scripts/hermes_scan_threads.py --channel 42 --threads 1001,1002`.

## Required YAML (minimum)

```yaml
lupopedia.headers:
  lupopedia.version: "4.0.80"
  channel_id: 42
  actor_id: 15
  actor_name: "hermes"
  artifact_kind: "hermes_prompt"
  target_actor_id: 1
  target_actor_slug: "wolfie"
  source_artifact: "lupo-channels/42/threads/1001/20260317_230500_....md"
  prompt_priority: "high"
```

## Body (minimum sections)

1. **Task** — one paragraph
2. **Expected output** — paths or artifact names
3. **Constraints** — doctrine / no impersonation / install SQL authority
4. **Done criteria** — checklist

## Routing rule

Non-prompt channel artifacts (reviews, directives, status) should be **classified** by HERMES using **`artifact_kind`**, **`message_type`**, and intent — not filename alone — before generating prompts.

See **CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md** §8 (prompts).

## API Status

**Filesystem-only until 4.0.81** - The prompts directory is not exposed via channels-api.php. See TODO.md item `004502` for implementation status.
