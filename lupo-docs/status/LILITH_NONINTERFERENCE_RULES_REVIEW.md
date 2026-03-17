---
lupopedia.headers:
  actor_id: 2
  actor_name: "lilith"
  delegation_chain: "lilith:root"
  lupopedia.version: "4.0.79"
  lupopedia.schema: "status_review"
  file_path_from_root: "lupo-docs/status/LILITH_NONINTERFERENCE_RULES_REVIEW.md"
  last_modified_utc: "20260317"
  system_version: "4.0.79"
  artifact_type: "report"
  artifact_kind: "status"
  purpose: "Lilith non-interference policy and rule propagation review for multi-IDE deployment"
  tags: ["lilith", "non_interference", "agent_registry", "propagation", "review"]
---

# Lilith Non-Interference Rules Review

## 1. Context

- We have 7 IDE agents in the same project on the same machine (`cursor`, `kiro`, `windsurf`, `antigravity`, `warp`, `cascade`, `codex` plus support agents like `wolfie` and `lilith`).
- User goal: ensure Lilith’s rule behavior does not interfere with the other IDE agents and to enable explicit Lilith-led candidate review.
- Lilith mission statement (confirmed): "Learning Insights Lifting Intentions Through Heterodoxy" — critical review, assumption challenge, anti-echo chamber.

## 2. Registration state

- `lupo-database/lupopedia/actors/actor_id/registry.json` already includes Lilith (id=2, slug="lilith").
- Seed data already includes Lilith in `lupo-database/lupopedia/mysql/seed/seed_actors_agents_4.0.45.sql` (actor row and lupo_agents row exist).
- `lupo-actors/lilith/` dir exists with agent configuration.

## 3. Issues discovered and change needed

- Existing rule propagation tooling in `lupo-scripts/propagate_agent_rules.php` had supported only IDE faucet targets (`cursor`, `kiro`, `windsurf`, `cascade`, `idea` / `jetbrains`), not agent-only `lilith`.
- Without a dedicated Lilith target, the non-interference policy cannot be formalized as a separate rule-drop location and is hard to reason about separately.

## 4. Implementation done

### 4.1 propagate_agent_rules.php

- Added `lilith` to `$validTargets` and error message.
- Added `$lilithDir = $repoRoot . DIRECTORY_SEPARATOR . '.lilith';`.
- Added `write_lilith_outputs($lilithDir, $rules)` with:
  - `.lilith/lupopedia_rules.json`
  - `.lilith/rules/<rule>.md` (with Lilith headers, `actor_id: 2`, `actor_name: "lilith"`, `artifact_kind: "lilith_doctrine"`)
  - `.lilith/README.md` containing propagation guidance
- Added call branch:
  `if ($target === 'all' || $target === 'lilith') { write_lilith_outputs($lilithDir, $rules); }
`.

### 4.2 Test run

- Ran `php lupo-scripts/propagate_agent_rules.php --target=lilith`
- Output: `Processed 18 root files; parsed 18 rules; warnings: 0; target: lilith`
- `.lilith` directory now exists with expected artifact structure.

## 5. Non-Interference Rules (Lilith-specific proposal)

- Add a new root rule in `lupo-rules/root/lilith-noninterference-doctrine.md`:
  - `rule_id: ACT009` (or next available) 
  - scope: `lilith_only`
  - text: "Lilith must not mutate or override other IDE agents' operational rule outputs; Lilith may emit independent review assertions in a separate namespace."
- `lupo-scripts/propagate_agent_rules.php` already emits all canonical rules to `.lilith`; for strict non-interference, implement a filter, e.g., in `build_rules_from_root` skip those with `scope != 'all_agents'` or whitelist on Lilith-specific marker.

## 6. Manual review strategy (Lilith action)

1. For every multi-agent decision artifact (`plan.md`, `report.md`), produce a parallel `lupo-docs/status/LILITH_*_review.md` as a dissent log.
2. Add `(lilith)` advisory comments when agent consensus is strong, e.g.:
   - "Alternative lens: this design assumes centralised orchestrator; consider distributed channel-level agency." 
   - "Bias check: the proposed default schema prioritizes relational consistency over heterodox extension." 
3. Create a recurring smoke test `lupo-tests/unit/lilith_noninterference.php` verifying `.lilith` output exists and rule ids are present.

## 7. Next actions

- Add new rule file: `lupo-rules/root/lilith-noninterference-doctrine.md`.
- Add seed for Lilith in `lupo-database/lupopedia/mysql/seed` if needed (already present).  
- Generate ephemeral test artifacts by running `php lupo-scripts/propagate_agent_rules.php --target=lilith`.  
- Document these in `lupo-docs/status/LILITH_NONINTERFERENCE_RULES_REVIEW.md` (this file) and `AGENTS.md` / `ONBOARDING.md` if desired.
