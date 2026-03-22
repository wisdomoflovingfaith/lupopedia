---
lupopedia.headers:
  version_when_written: "4.0.85"
  lupopedia.schema: "doctrine"
  file_path_from_root: "lupo-docs/doctrine/MOOD_RGB_DOCTRINE.md"
  last_modified_utc: "20260322_170424"
  channel_id: 42
  thread_id: 2015
  actor_id: 4
  actor_name: "athena"
  delegation_chain: "athena:wolfie"
  artifact_type: "doctrine"
  artifact_kind: "canonical_semantic_system"
  purpose: "Canonical 4.0.85 doctrine for mood_rgb as a semantic state vector used in dialog, routing, validation, and artifact signaling."
  tags: ["mood_rgb", "dialog", "routing", "semantic_state", "doctrine", "4.0.85"]

lupopedia.edges:
  outbound_edges:
    - { to: "dialog.yaml", type: "formalizes", weight: 1.0, reason: "dialog.yaml defines mood_RGB as a six-hex semantic field and names the historical axes." }
    - { to: "lupo-api/dialog/send-message.php", type: "formalizes", weight: 1.0, reason: "API input validation and default handling define current runtime constraints." }
    - { to: "lupo-api/v1/dialog/metrics.php", type: "formalizes", weight: 0.9, reason: "Metrics endpoint aggregates mood values as operational telemetry." }
    - { to: "lupo-includes/class-caduceus.php", type: "formalizes", weight: 1.0, reason: "CADUCEUS derives routing currents from R, G, and B channels." }
    - { to: "lupo-includes/class-hermes.php", type: "formalizes", weight: 1.0, reason: "HERMES consumes CADUCEUS currents when no explicit destination exists." }
    - { to: "lupo-channels/42/threads/1037/20260321_160000_lilith_versioning_doctrine_gap_analysis.md", type: "evidence", weight: 0.9, reason: "B1B1B1 is used in live ambiguity/gap-analysis work." }
    - { to: "lupo-channels/42/threads/1045/20260321_185000_wolfie_system_correction_directive.md", type: "evidence", weight: 0.9, reason: "FF0000 is used in mandatory correction directives." }
    - { to: "lupo-channels/42/threads/1045/20260321_193000_wolfie_phase_2_gate_pass.md", type: "evidence", weight: 0.9, reason: "00FF00 is used for gate-pass and approval states." }
    - { to: "lupo-channels/42/threads/1036/20260321_150000_athena_canonical_actor_architecture_and_repair_plan.md", type: "evidence", weight: 0.85, reason: "666666 is used for neutral architectural analysis." }
    - { to: "lupo-docs/channels/doctrine/MOOD_RGB_DOCTRINE.md", type: "supersedes_in_root_doctrine", weight: 0.8, reason: "Older channel-doctrine material provides lineage but is not the current canonical root doctrine surface." }

lupopedia.footer:
  last_verified: "20260322_170424"
  last_verified_by: "athena"
  orchestrator: "wolfie"
  next_action:
    - "Use canonical tokens for decision-bearing mood_rgb output in 4.0.85+."
    - "Treat non-canonical values as vector-only routing influence unless a later doctrine extends semantic authority."
---

# MOOD_RGB Doctrine

`mood_rgb` is a six-hex-digit semantic state vector. It is stored in RGB-shaped encoding, but in Lupopedia 4.0.85 it is **not canonicalized as a visual color system**. It is a compact signaling field used by dialog tooling, routing helpers, audits, directives, and artifact headers.

This doctrine is based on current repository behavior, not on aspirational emotional-geometry prose alone.

This doctrine now defines `mood_rgb` as a **hybrid system** with two explicit layers:

- **Layer 1: Canonical Tokens (authoritative)** for agent decisions and system-level semantic actions
- **Layer 2: Continuous Vector (non-authoritative)** for numeric routing influence and signal weighting

## Core Definition

- Storage form: `RRGGBB`
- Allowed characters: six hex digits, no leading `#`
- Runtime default: `666666`
- Primary meaning: encode semantic posture across three channels that can be consumed by humans, validators, and routing helpers
- Current repository status: formalized as a hybrid model with authoritative canonical tokens and non-authoritative continuous routing influence

Historical materials describe the channels as `strife`, `harmony`, and `memory depth`. Current 4.0.85 operational usage supports the following working interpretation:

- `R`: urgency, blocking pressure, correction demand, stop-state intensity
- `G`: approval, constructive alignment, completion, stabilization
- `B`: reflection, ambiguity, clarification pressure, retained context depth

This keeps continuity with the historical axis names while matching the way current thread artifacts are actually using the field.

## System Architecture

### Layer 1 - Canonical Tokens (Authoritative)

The following tokens are authoritative and decision-safe in 4.0.85:

- `FF0000`
- `00FF00`
- `666666`
- `B1B1B1`
- `88FF88`

These tokens define agent behavior and may be used as semantic authority in audits, directives, gate states, response signaling, and workflow decisions.

### Layer 2 - Continuous Vector (Non-Authoritative)

All valid six-hex values remain syntactically allowed by runtime.

This layer is used by:

- `CADUCEUS`
- `HERMES`
- metrics/telemetry collection
- any future signal-weighting or routing helper that consumes RGB bytes numerically

This layer is **not** authoritative for strong system semantics. A non-canonical value may influence routing or weighting, but by itself it must not be treated as a blocking, approving, or contradiction-resolving decision.

## Precedence Rule

The precedence model is binding:

```text
IF mood_rgb is a canonical token:
  canonical behavior MUST be applied
ELSE:
  treat mood_rgb as vector-only signal
  DO NOT infer strong semantic meaning
  DO NOT use it alone for decision authority
```

This rule resolves the token-vs-axis conflict.

- Canonical tokens carry behavioral authority.
- Non-canonical values carry numeric influence only.
- Continuous values may bias routing, but they may not create hard semantic outcomes on their own.

## Channel Definitions

### Red Channel

The red byte represents pressure that something is wrong, blocked, urgent, or must be corrected.

Observed usage:

- `FF0000` on mandatory directives and hard correction work
- `8B0000` on severe synchronization broadcasts
- low red on neutral architecture or observational artifacts

Interpretation rule: increasing `R` raises the probability that the artifact is warning, blocking, or demanding action.

### Green Channel

The green byte represents alignment, approval, completion, stabilization, or constructive forward movement.

Observed usage:

- `00FF00` on gate-pass artifacts and correction completions
- `88FF88` in `DialogManager` as the default positive response mood
- low green on hard-stop directives

Interpretation rule: increasing `G` raises the probability that the artifact is affirming, resolving, or authorizing work.

### Blue Channel

The blue byte represents reflection, depth, ambiguity handling, or a need to preserve nuance rather than force immediate closure.

Observed usage:

- historical examples such as `0000FF` and `0088FF` in Counting-in-Light documentation
- `B1B1B1` used in live doctrine-gap analysis where ambiguity and clarification are central
- CADUCEUS includes `B` in both routing currents, meaning blue influences either analytical or creative routing rather than only one side

Interpretation rule: increasing `B` raises the probability that the artifact should be read with more context retention, reflection, or unresolved nuance.

## Numeric Mapping

- Each channel is one unsigned hex byte from `00` to `FF`
- Full stored token: `RRGGBB`
- Storage pattern: `^[0-9A-Fa-f]{6}$`
- Database and YAML/storage convention: no leading `#`
- Display convention, if a UI wants one: `#` may be added for rendering only

Examples:

- `FF0000` = maximal asserted red, no explicit green or blue emphasis
- `00FF00` = maximal asserted green, no explicit red or blue emphasis
- `666666` = neutral mid-range default used by runtime and thread artifacts
- `B1B1B1` = balanced non-extreme token currently used for ambiguity/gap-analysis rather than for pass/fail signaling

## Threshold Semantics

The repository does **not** currently implement semantic decision thresholds for arbitrary numeric values beyond hex-format validation.

Therefore the binding 4.0.85 rule is:

- **canonical tokens** define semantic authority
- **non-canonical values** do not define semantic authority

Channel magnitudes may still influence routing numerically, but they do not become semantic decisions merely by crossing an implied boundary.

Examples:

- `880000` is a valid vector with red influence, but not an authoritative blocking token
- `4444FF` is a valid vector with blue influence, but not an authoritative ambiguity token
- `22CC99` is a valid vector with green/blue influence, but not an authoritative approval token

Doctrine consequence:

- do not invent continuous semantic threshold behavior for agent decisions
- do allow continuous numeric influence for routing and weighting
- use canonical tokens whenever a system component needs decision-safe semantics

## Known Canonical Values

### Live Operational Tokens

- `FF0000`: mandatory directive, correction required, blocking or stop-state emphasis
- `8B0000`: severe red-state broadcast; still blocking/directive, but used as a variant rather than a separate semantic family
- `00FF00`: approval, gate pass, completion, successful correction, authorization to proceed
- `666666`: neutral observation, architecture/design analysis, default runtime fallback
- `B1B1B1`: ambiguity, doctrine gap, clarification required, non-binary review state
- `88FF88`: positive default response mood emitted by `DialogManager`

For authority purposes in this doctrine, the canonical token set is:

- `FF0000`
- `00FF00`
- `666666`
- `B1B1B1`
- `88FF88`

`8B0000` remains recognized historical/runtime evidence of strong red-state signaling, but it is not promoted here as a decision-authoritative canonical token. New decision-bearing outputs should prefer `FF0000` when a canonical blocking token is required.

### Historical but Still Recognizable Examples

- `0000FF`: reflective / insight-heavy / memory-depth emphasis
- `888888`: balanced neutral example used in older documentation
- `FF8800`, `00CC88`, `0088FF`: documented intermediate examples from Counting-in-Light materials

These historical examples remain useful as vocabulary, but they are less authoritative than the live operational tokens above because they are not currently tied to binding execution logic.

## Agent Rules

The following agent rules are binding:

1. Agents **MUST** use canonical tokens for decision-bearing output.
2. Agents **MUST NOT** rely on arbitrary non-canonical values for logic, approval, blocking, contradiction resolution, or task closure.
3. Agents **MUST** treat unknown but valid values as non-authoritative vector signals unless a later doctrine defines more authority.
4. Agents **MAY** preserve a non-canonical value when passing through existing runtime data, but must not upgrade it into a semantic decision by interpretation alone.
5. Consumers must not treat `mood_rgb` as culturally fixed UI color meaning. The repository meaning is doctrinal, not generic RGB symbolism.

## Runtime Rules

The following runtime rules are binding:

1. Runtime **MAY** accept any syntactically valid six-hex `mood_rgb` value.
2. Runtime **MAY** compute routing currents from any valid value.
3. Runtime **MUST NOT** assume strong semantic authority from a non-canonical value beyond numeric influence.
4. Runtime **MUST** preserve canonical-token semantics when a canonical token is present.
5. Routing influence and semantic authority are separate concerns.

## Default Behavior

`666666` is the canonical explicit neutral signal.

Binding consequences:

- neutral must not trigger blocking, approval, escalation, closure, or contradiction creation on its own
- when a caller explicitly emits `666666`, that means observation / no action / semantic neutrality
- when runtime falls back to `666666` because a value was omitted or invalid, consumers must treat that state carefully and must not promote the fallback into a decision

Current runtime does not distinguish omitted-from-explicit neutral in the stored token alone. Therefore agents and higher-level logic must assume that fallback `666666` is **non-actionable neutral**, not silent approval.

## Behavioral Contract

### `FF0000` - Blocking / Correction

Agents encountering `FF0000` in a decision-bearing context **MUST**:

- treat the state as blocking or stop-state authoritative
- escalate or surface the issue as requiring correction
- create or maintain a correction task when the surrounding workflow is task-driven
- avoid treating the artifact as approved, complete, or closable

### `00FF00` - Approval / Proceed / Close

Agents encountering `00FF00` in a decision-bearing context **MUST**:

- treat the state as approval to proceed
- allow completion/closure where the surrounding workflow expects a pass token
- avoid creating contradiction or correction behavior from this token alone

### `B1B1B1` - Clarification Required

Agents encountering `B1B1B1` in a decision-bearing context **MUST**:

- treat the state as ambiguity requiring clarification
- open a question, clarification request, or explicit follow-up when the workflow supports one
- avoid marking the issue as resolved, approved, or blocked-final without additional evidence

### `666666` - Observation / No Action

Agents encountering `666666` in a decision-bearing context **MUST**:

- treat the state as explicit neutral observation
- take no semantic action from the token alone
- avoid using it to infer approval, failure, or ambiguity

### `88FF88` - Positive Response / Supportive Acknowledgment

Agents encountering `88FF88` in a decision-bearing context **MUST**:

- treat the state as positive response or supportive acknowledgment
- avoid using it by itself as governance-grade approval or closure unless the surrounding workflow explicitly maps it to one

## Multi-Axis Interpretation

Axis interpretation remains valid, but only for the non-authoritative vector layer.

### Vector Reading

Read relative emphasis numerically for routing influence only:

- high `R`, low `G`, low `B` -> stronger correction/urgency weighting
- low `R`, high `G`, low `B` -> stronger constructive/approval weighting
- low `R`, low `G`, high `B` -> stronger reflection/ambiguity/context weighting
- high `G` and high `B` -> supportive with retained context
- high `R` and high `B` -> urgent with context retention

### Authority Reading

Authority does not come from arbitrary vector shape. Authority comes from canonical tokens only.

- `666666` -> neutral/default observation
- `B1B1B1` -> ambiguity / gap-analysis / needs clarification
- `FF0000` -> hard directive / blocking correction
- `00FF00` -> gate pass / completion
- `88FF88` -> positive response / supportive acknowledgment

If a value is not canonical, interpret it as vector-only signal.

## Usage in Validation Systems

### Input Validation

`lupo-api/dialog/send-message.php` accepts `mood_rgb` and validates only the hex shape. Invalid shape is rejected. Omitted input defaults to `666666`.

### Runtime Storage

`DialogManager` stores the packet mood as provided, falling back to `666666` when absent. Response insertion currently uses `88FF88` as a default positive response token.

### Routing

`Caduceus::computeCurrents()` interprets the channels as:

- `R` -> strife/intensity
- `G` -> harmony/balance
- `B` -> memory depth/introspection

It computes:

- `left_current = G + B`
- `right_current = R + B`

after normalization, and `HERMES` uses those currents when no explicit destination actor exists.

Practical consequence: blue is not passive. It strengthens both routing branches, meaning reflective/ambiguous context remains important regardless of whether the system leans analytical or creative.

## CADUCEUS Alignment

CADUCEUS and HERMES are aligned with this doctrine as follows:

- CADUCEUS uses continuous numeric channel values
- HERMES may route from CADUCEUS currents
- this numeric processing is routing influence only
- this numeric processing is not semantic authority

Therefore:

- CADUCEUS may process `880000`, `4444FF`, or any other valid token numerically
- agents and governance logic must not treat those non-canonical values as authoritative semantic commands
- when a canonical token is required for decision safety, emit a canonical token explicitly rather than relying on vector shape

### Metrics and Audit

`lupo-api/v1/dialog/metrics.php` groups messages by `mood_rgb`, which makes the field part of system telemetry. Channel and thread artifacts also use `mood_rgb` in headers to signal directive state, pass/fail posture, and ambiguity state.

Metrics aggregation does not itself grant semantic authority to arbitrary values. Telemetry may observe any valid token, while decision logic remains bounded by canonical-token rules.

## Limitations / Current State

- No current validator enforces canonical-token-only emission for decision-bearing artifacts.
- No current runtime layer distinguishes explicit neutral from omitted neutral in stored token form alone.
- Older documentation sometimes speaks in “color” terms or stronger emotional-geometry language than current code justifies.
- The older file at `lupo-docs/channels/doctrine/MOOD_RGB_DOCTRINE.md` is valuable lineage, but it mixes historical framing, duplicated headers, and broader claims that are not all enforced by current runtime behavior.
- Current doctrine is intentionally conservative: strong semantics are bounded to canonical tokens, while continuous values remain available for routing influence.

## Future Direction

Future work may extend `mood_rgb` into stronger validation, UI rendering rules, aggregation rules, or richer routing semantics, but any such extension must first define:

- whether additional canonical tokens should be admitted
- whether non-canonical values should ever gain decision authority
- how multiple mood values aggregate at thread or channel level
- whether UI display should remain symbolic rather than literal-color driven

Until then, `mood_rgb` should be treated as a hybrid system: authoritative canonical tokens plus non-authoritative continuous routing influence.

## Examples

### Blocking Directive

- token: `FF0000`
- reading: correction required now
- typical artifact type: WOLFIE directive, hard stop, enforcement action

### Completion / Gate Pass

- token: `00FF00`
- reading: approved, complete, proceed
- typical artifact type: gate pass, completion report, validation success

### Positive Response / Supportive Acknowledgment

- token: `88FF88`
- reading: positive response, supportive acknowledgment
- typical artifact type: runtime response default from `DialogManager`
- decision rule: not governance-grade approval by itself

### Neutral Analysis

- token: `666666`
- reading: observation without strong blocking or approval pressure
- typical artifact type: design analysis, architecture explanation, runtime default

### Ambiguity / Doctrine Gap

- token: `B1B1B1`
- reading: issue is real but not reducible to pass/fail; clarification required
- typical artifact type: non-interfering review, doctrine gap analysis, unresolved semantic conflict