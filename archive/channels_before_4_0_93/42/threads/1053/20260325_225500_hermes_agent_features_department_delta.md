---
lupopedia.headers:
  lupopedia.schema: "workstream"
  file_path_from_root: "channels/42/threads/1053/20260325_225500_hermes_agent_features_department_delta.md"
  web_path: "http://www.lupopedia.com/channels/42/threads/1053/20260325_225500_hermes_agent_features_department_delta.md"
  questions_toon: null
  when_updated: "20260325225500"
  channel_id: 42
  thread_id: 1053
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "workstream"
  artifact_kind: "feature_spec"
  purpose: "Define HERMES additional agent feature model with identity components, department delta storage, and testable resolution rules"
  tags: ["hermes", "agent_features", "identity", "department_delta", "4.0.88"]

lupopedia.footer:
  last_verified: "20260325225500"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
  orchestrator: "wolfie:root"
  next_action: "Implement agent profile and department delta resolution service in application layer"
---

# HERMES Additional Agent Features

## Channel and Thread

- Channel: 42
- Thread: 1053

## Implementation Target and Scope

- Implementation target: 4.0.88
- WS3 (identity model clarification) is a prerequisite reference surface and is treated as complete input for this feature.
- This spec is not a 4.0.87 execution artifact.

## Required Agent Model

Each agent profile should include these core dimensions:

1. Skills
- capability list and execution scope
- confidence/priority per skill

2. Soul
- mission/ethos layer
- non-functional value alignment for decisions and tone

3. Memory
- persistent memory references
- short-term/session memory context
- repository memory awareness hooks

4. Identity
- canonical actor identity
- agent runtime identity
- faucet/surface identity separation

## Actor + Department Pairing Delta

When an actor is paired with a department, compute an effective delta profile that overlays department-specific behavior onto the base actor profile.

Delta fields should include:

- additional_rules: department-required policy constraints
- preference_overrides: style, workflow, and routing preferences
- scope_constraints: allowed channels, task types, and authority boundaries
- escalation_policy: department escalation and handoff defaults

## Storage Model

- No new tables are introduced.
- Department-level rule policy is stored in `lupo_departments.rules_json`.
- Department-level scope policy is stored in `lupo_departments.scope_json`.
- Actor-department preference overlays are stored in `lupo_actor_departments.overrides_json`.
- Effective profile is composed at runtime (in-memory) from baseline identity plus department overlay.

If the JSON columns above are not present in the runtime schema, they must be added by migration in existing department tables only.

Resolution order:

Priority (highest wins):

1. Session/context override (explicit runtime selection)
2. Department delta overlay
3. Canonical actor baseline
4. System defaults

Overlay semantics:

- If a department defines a value, that value overrides baseline.
- If a department does not define a value, baseline remains in effect.

Conflict rules:

- Department rules may constrain but must not violate canonical doctrine.
- Faucet identity must not change actor attribution.
- Effective permissions remain actor-authority bounded.
- Department rules that violate doctrine are rejected and not applied.
- If multiple departments are bound, primary department precedence is used.

## Dependencies

- `docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md` is canonical for identity layer definitions.
- WS3 identity model outcomes are prerequisite references for this implementation.
- `lupo_actor_departments` must exist and remain the actor-department binding authority.
- Actor ID ranges and actor/faucet distinctions must remain aligned with AGENTS doctrine surfaces.

## Test Scenarios

1. Baseline actor profile only (no department): baseline identity is returned unchanged.
2. Actor plus department with no overrides: identity attribution remains unchanged.
3. Actor plus department with `preference_overrides`: style and workflow preferences change as specified.
4. Actor plus department with `scope_constraints`: channel/task access is constrained as specified.
5. Conflict case: department rule contradicts doctrine: rule is rejected.
6. Multi-department case: primary department precedence is deterministically applied.

Passing criteria:

- All scenarios produce deterministic outputs for identical inputs.
- Actor attribution remains canonical in all cases.
- Rejected overlays are logged and excluded from effective profile.
- No test may elevate authority beyond actor-bound limits.

## Implementation Targets

- Add an effective profile resolver service for actor + department composition.
- Expose resolved profile metadata to orchestration and routing surfaces.
- Add tests for baseline, overlay, and conflict scenarios.
