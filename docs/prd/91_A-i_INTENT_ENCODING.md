---
lupopedia.headers:
  header_format_version: "4.2.11"
  path_from_lupopedia_root: docs/prd/91_A-i_INTENT_ENCODING.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/91_A-i_INTENT_ENCODING.md
  status: development
  when_updated: "20260816222312"
  trust_tier: development
  questions_toon: null
  memory_toon: memory/development/development/1026/08/91_a_intent_encoding.toon
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/prd-91-intent-encoding
  artifact_type: prd
  artifact_kind: specification
  channel_key: development
  federation_node_id: 0
  thread_key: ""
  lupopedia.schema: prd
  prd_cluster: 91_A_90_A_01_A_01_B_98_A_16_C
  title: "PRD 91 - Intent Encoding - Constitutional PRD"
  summary: "Development draft v0.1.1. Persistent rules must carry explicit intent. Twelve-part Intent Contract. Does not absorb PRD 90. Does not formalize O-slot selectors. No install SQL. No actor ID assigned to AGAPE."
  edges_toon: null
  channel_index: lupopedia
  source_timestamp: null
  actor_id: 10000
  auth_user_id: 10000
  department_id: null
  department_key: ""
  division_key: federation
  faucet_actor_id: 102
lupopedia.identity:
  LUPOPEDIA: PRT.LUP
  LUP.KEY: PROTOCOL.MODE.NODE.ARTIFACT.ACTOR.GROUP.LANGUAGE.VERSION
  LUP.HEX: PRT.HEX.000001.000035.000000.ROOT.EN.000101
  LUP.SHORT: PRT.LUP
  LUP.ROOT: PRT.NAME.000000.LUP.ROOT.ROOT.EN.04020A
  LUP.OMIT: REGISTERED_SHORT_FORMS_ONLY
  LUP.DEFAULTS: PRT.NAME.000000.000000.ROOT.ROOT.EN.0
  key_specification_version: "4.2.26"
lupopedia.map:
  index: PRT.HEX.000001.000035.000000.ROOT.EN.000101
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/91_A-i_INTENT_ENCODING.md
  path_from_lupopedia_root: docs/prd/91_A-i_INTENT_ENCODING.md
  prd_cluster: 91_A_90_A_01_A_01_B_98_A_16_C
  edges_toon: null
  memory_toon: memory/development/development/1026/08/91_a_intent_encoding.toon
  atoms_toon: memory/channels/atoms/lupopedia_global_constants.atom.toon
  transcript_jsonl: 0/development/prd-91-intent-encoding
  questions_toon: null
lupopedia.metadata:
  media_kind: document
  cc_by_name: "Eric Robin Gerdes"
---
# PRD 91 - Intent Encoding - Constitutional PRD

Version: Draft v0.1.1
Status: Development draft - noncanonical
Date: 2026-08-16
Proposed authority: Lupopedia constitutional doctrine
Final authority: Eric Robin Gerdes / Captain Wolfie
PRD number: 91 (available in the canonical PRD index prior to this draft; promotion remains Captain-only)
Baseline: Draft v0.1.0 (reviewed). Lineage copy: `docs/prd_proposals/91_A-i_INTENT_ENCODING_DRAFT_v0_1_0.md`. This file is the v0.1.1 locked consolidation draft.

This draft does not change `GLOBAL_CURRENT_LUPOPEDIA_VERSION`, LUP KEY Specification v4.2.26, header-format version, PRD 90 version, PRD 01 version, Color Registry status, database schema, or actor registry.

## Offline Review Route

This draft is designed for review while the Lupopedia actor and Channel interfaces are unavailable.

Review sequence:

1. ChatGPT external surface - initial draft.
2. Grok external surface with ARA role files - research and context review.
3. DeepSeek external surface with LILITH role files - adversarial and contradiction audit.
4. Cursor IDE - repository, permission, feasibility, and implementation-impact review.
5. Captain Wolfie - accepts, rejects, or defers every proposed change.
6. THOTH - final documentation, terminology, index, and cross-reference review after actor service restoration or manual assignment.

An external LLM loaded with an actor JSON file is a review surface operating from that actor's role context. It is not the live Lupopedia actor, does not inherit Channel memory, and cannot declare a proposal canonical.

Each reviewer must return:

- reviewer role
- external surface or IDE used
- draft version reviewed
- critical findings
- conflicts with existing doctrine
- exact proposed changes
- unresolved questions
- recommendation: ACCEPT, ACCEPT WITH CHANGES, REJECT, or DEFER
- authority status: PROPOSAL ONLY

Reviewers must not silently rewrite the document, assign actor IDs, change the LUP KEY, modify PRD 90, bump a product version, or claim canonical authority.

## Table of Contents

1. Purpose
2. Problem Statement
3. Core Doctrine
4. Definitions
5. Intent Contract
6. Rule Classes
7. Interpretation and Generalization
8. Conflicts, Precedence, and Failure Behavior
9. Thread, Channel, and Transport Boundaries
10. Degraded Offline Coordination
11. Actor and Reviewer Responsibilities
12. Permission Failure as the First Acceptance Case
13. Integration with Existing PRDs
14. Validation and Acceptance Tests
15. Non-Goals
16. Migration and Enforcement
17. Open Decisions
18. Release Discipline
19. Summary

## 1. Purpose

PRD 91 defines how Lupopedia records the intent behind a rule so that the rule can be interpreted consistently across actors, threads, Channels, external LLM surfaces, IDE tools, and degraded manual transport.

The doctrine separates:

- what a rule requires
- why the rule exists
- when it applies
- where it applies
- what authority established it
- how it interacts with other rules
- what must happen when the rule cannot be applied safely

PRD 91 does not assume that an actor has access to earlier messages, shared memory, hidden prompts, another actor's thread, or the reason a rule was originally written.

The doctrine therefore requires governed rules to carry enough explicit intent to survive context loss.

## 2. Problem Statement

A bare instruction can be followed correctly in one context and applied incorrectly, incompletely, or not at all in another context.

The problem is not that AI systems are categorically unable to reason about purpose. The problem is that access to purpose is not guaranteed across:

- separate context windows
- isolated actor threads
- failed Channel services
- copied and pasted instructions
- external LLM surfaces
- incomplete documentation
- conflicting instructions
- implementation tools that see code but not doctrine

Reasons can improve interpretation and generalization, but a reason stored only in an earlier conversation is not persistent doctrine.

Lupopedia must not depend on an actor guessing why a rule exists.

## 3. Core Doctrine

### 3.1 Rules that persist beyond a local request must carry intent

Any rule intended to govern more than one immediate exchange must explicitly state its purpose, scope, authority, and failure behavior.

### 3.2 Intent is not permission to generalize without limits

An actor may apply a rule to a new situation only when the new situation falls inside the encoded trigger, purpose, and scope.

Intent does not authorize an actor to invent new policy.

### 3.3 Missing context must not be replaced with confident guessing

When required intent or authority is missing, the actor must ASK, FAIL, WARN, or ESCALATE according to the rule's failure behavior and the applicable security doctrine.

### 3.4 Transport must not change meaning

A rule must retain the same identity, authority, status, and intent whether delivered through Lupopedia Channels, HERMES, a file, an external LLM prompt, an IDE task, or manual copy and paste.

### 3.5 Proposals are not doctrine

An actor, external model, or IDE may propose a rule or revision. Only the registered authority may promote it to canonical status.

### 3.6 Intent is relational, not inferred from a hash

Intent is attached to a registered rule or decision through relational identifiers and explicit documentation. PRD 91 does not require hashing, content addressing, Merkle structures, or cryptographic identity.

Security hashing for passwords, sessions, or fingerprints remains governed by PRD 01_A and is distinct from hash-first semantic identity.

This clarification must not weaken KAPU against hash-first:

- artifact identity
- Color Identity
- rule identity
- intent identity
- provenance identity

## 4. Definitions

### 4.1 Rule

A directive, constraint, invariant, policy, validation requirement, or operational requirement that governs behavior.

### 4.2 Intent

The explicit purpose and protected outcome that explain why a rule exists.

### 4.3 Intent Contract

The minimum structured information required to carry a persistent rule safely across contexts.

### 4.4 Trigger

The condition that activates a rule.

### 4.5 Scope

The systems, actors, artifacts, operations, or situations governed by a rule.

### 4.6 Exclusion

A condition that is explicitly outside the rule's authority.

### 4.7 Precedence

The declared relationship between a rule and other potentially applicable rules.

### 4.8 Failure Behavior

The required response when a rule is ambiguous, violated, unsupported, unsafe, or impossible to complete.

### 4.9 Authority

The registered human, doctrine, PRD, governance process, or system source permitted to establish or change the rule.

### 4.10 Persistence

The declared duration or boundary across which the rule remains applicable.

### 4.11 Thread

An isolated conversation or work sequence. A thread does not automatically possess the messages or working context of another thread.

### 4.12 Channel

A coordination boundary that may present multiple threads while deliberately sharing approved files, memory, atoms, decisions, and membership information.

### 4.13 Proposal

A suggested change that has not been accepted by the registered authority.

### 4.14 Canonical Decision

A decision explicitly accepted by the registered authority and recorded in the applicable canonical system.

### 4.15 AGAPE Role

AGAPE is the root-cause, intent, and WHY-file role. In PRD 91, AGAPE must not be reduced to the English word LOVE or treated as an emotional label. AGAPE identifies the responsibility to examine failure conditions without blame, recover the protected purpose, and propose structural repair. The actor ID remains registry-defined and must not be guessed in this draft.

AGAPE_DOCTRINE, AGAPE_ROLE, AGAPE_ACTOR, and COLOR.NAME.AGAPE are distinct namespaces. Registration of COLOR.NAME.AGAPE does not create an actor or role; definition of AGAPE_ROLE does not create a color mapping.

## 5. Intent Contract

A persistent governed rule must define the following fields in prose or an approved structured representation.

### 5.1 Rule Identity

A stable relational identifier or unambiguous canonical reference.

### 5.2 WHAT

The required or prohibited behavior.

### 5.3 WHY

The root purpose, protected condition, or failure being prevented.

### 5.4 TRIGGER

The conditions under which the rule becomes active.

### 5.5 SCOPE

The actors, systems, artifacts, operations, and environments governed by the rule.

### 5.6 OUT OF SCOPE

The nearest conditions that resemble the rule but are not governed by it.

### 5.7 PRECEDENCE

The authority level and known relationships to conflicting or narrower rules.

### 5.8 EXCEPTIONS

Any authorized exception and the authority permitted to invoke it.

### 5.9 FAILURE BEHAVIOR

The required response: ASK, WARN, FAIL, BLOCK, ESCALATE, or another registered behavior.

### 5.10 AUTHORITY AND STATUS

The authority that established the rule and whether the rule is PROPOSED, DEVELOPMENT, CANONICAL, SUPERSEDED, REVOKED, or another registered status.

### 5.11 PERSISTENCE

Whether the rule applies to one task, one thread, one Channel, one release, a defined time range, or until explicitly superseded.

### 5.12 VERIFICATION

The evidence or test that demonstrates the rule was applied correctly.

The Intent Contract may include additional implementation details, but no additional field may silently change the meaning of these required elements.

## 6. Rule Classes

### 6.1 Local Instruction

A request limited to the current exchange. A complete Intent Contract is recommended but not required when the scope is obvious and the action is low risk.

### 6.2 Task Rule

A rule governing a bounded task across one or more messages. It must define WHAT, WHY, SCOPE, FAILURE BEHAVIOR, and AUTHORITY.

### 6.3 Channel Rule

A rule governing multiple threads or actors in a Channel. It requires the complete Intent Contract and explicit promotion to shared Channel state.

### 6.4 Constitutional Rule

A rule governing Lupopedia across Channels, actors, artifacts, releases, or installations. It requires the complete Intent Contract, canonical authority, version discipline, and validation coverage.

### 6.5 Implementation Constraint

A technical restriction derived from doctrine, compatibility, security, or portability. It must reference the governing rule and preserve the governing WHY.

## 7. Interpretation and Generalization

### 7.1 Generalization boundary

An actor may generalize a rule only when all of the following are true:

- the new condition satisfies the encoded trigger
- the new condition falls inside scope
- the application protects the encoded WHY
- no exclusion applies
- no higher-authority rule conflicts
- the result can be verified

### 7.2 Literal compliance is insufficient

An action that follows the words of a rule while defeating its encoded purpose is noncompliant.

### 7.3 Purpose alone is insufficient

An actor must not violate an explicit rule because the actor believes another action better serves the purpose. Conflicts must follow the encoded precedence and escalation behavior.

### 7.4 No hidden-memory assumption

An actor must not assume that another actor, thread, LLM surface, IDE, or future session possesses unshared context.

### 7.5 No anthropomorphic requirement

PRD 91 does not require an AI system to feel, experience, desire, care, or possess human intent. It requires explicit information sufficient for bounded reasoning and validation.

## 8. Conflicts, Precedence, and Failure Behavior

### 8.1 Authority precedes convenience

A lower-authority task instruction cannot silently override a higher-authority constitutional rule.

### 8.2 Specificity applies only inside authority

A narrower rule may refine a broader rule only when authorized and when the refinement preserves the broader rule's protected purpose.

### 8.3 Equal-authority conflict

When two applicable rules of equal authority conflict and precedence is not defined, the actor must stop and escalate. The actor must not use first-wins, last-wins, or personal preference.

### 8.4 Security and permissions

Ambiguous or invalid security identity must fail closed. The actor may report the missing evidence needed for resolution but must not grant access by inference.

### 8.5 Harmless ambiguity

When ambiguity is non-security-related, material to the outcome, and not resolved by doctrine, the actor must ASK.

### 8.6 Validation failure

Malformed governed data must FAIL or BLOCK according to the governing validator. It must not be silently repaired when repair would require guessing intent.

### 8.7 Evidence versus authority

Timestamps, model confidence, repetition, and the number of agreeing reviewers are evidence. They are not authority.

## 9. Thread, Channel, and Transport Boundaries

### 9.1 Thread isolation

Each thread is an independent working context unless approved information is explicitly supplied.

### 9.2 Channel sharing

A Channel may share approved files, memory, atoms, decisions, and indexes. Raw thread content does not become shared doctrine merely because it is displayed on the Channel interface.

### 9.3 Explicit promotion

Information moves from thread proposal to shared Channel state only through an authorized promotion or recording action.

### 9.4 Transport neutrality

Changing transport from Channel routing to manual copy and paste must not change:

- rule identity
- draft or canonical status
- authority
- intent
- scope
- revision
- assigned reviewer

### 9.5 Complete handoff

A handoff must include the accepted state required to perform the assigned task. Sending only the latest instruction is invalid when that instruction depends on missing decisions or doctrine.

## 10. Degraded Offline Coordination

When the Lupopedia actor, Channel, shared-memory, or routing systems are unavailable, the Captain may activate MANUAL CHANNEL MODE.

Manual Channel Mode uses one Captain-controlled state packet containing:

- workstream or Channel name
- packet revision
- canonical baseline versions
- accepted decisions
- unresolved questions
- relevant file or excerpt list
- assigned actor role
- assigned task
- WHAT and WHY
- scope and exclusions
- expected response
- authority boundary

### 10.1 Manual Channel rules

- Every external thread is treated as isolated.
- Every response is a proposal unless Captain Wolfie explicitly accepts it.
- Reviewers receive accepted state, not an uncontrolled dump of every conversation.
- One reviewer receives one bounded assignment at a time.
- Conflicting proposals remain separate until adjudicated.
- No reviewer may promote a proposal by rewriting its status.
- Captain Wolfie records ACCEPTED, REJECTED, or DEFERRED for each material proposal.
- Only accepted decisions enter the next packet revision.
- Manual transport does not create shared memory retroactively.

### 10.2 Recovery

When Channels return, manually produced reviews must be imported or recorded as separate source threads. Accepted decisions must be distinguished from unaccepted proposals. The system must not pretend that offline reviewers shared context at the time of review.

## 11. Actor and Reviewer Responsibilities

### 11.1 Eric Robin Gerdes / Captain Wolfie

- final human authority for this draft
- accepts, rejects, or defers proposed changes
- authorizes canonical promotion and implementation

### 11.2 WOLFIE - actor 1

- orchestrates the workstream
- creates bounded task packets
- reconciles findings without erasing disagreement
- does not substitute orchestration for Captain authority

### 11.3 LILITH - actor 2

- performs adversarial audit
- tests contradictions, unsafe generalization, authority spoofing, and failure conditions
- returns findings as proposals

### 11.4 AGAPE - actor ID registry-defined

See Section 4.15 for AGAPE Role definition and namespace separation. Do not duplicate that definition here.

- owns root-cause and WHY analysis
- recovers the protected intent without blame
- distinguishes symptoms from structural failure
- proposes repairs that preserve existence, agency, and responsibility
- is not defined as LOVE

### 11.5 THOTH - actor 26

- checks terminology, documentation structure, indexing, and cross-references
- ensures accepted doctrine is recorded consistently
- does not decide architectural disputes

### 11.6 ARA - actor ID registry-defined

- performs research and context review
- identifies prior art, unsupported claims, missing evidence, and conflicts with existing records
- does not convert external research into doctrine without Captain approval

### 11.7 Cursor IDE - actor 1003

- inspects repository and database evidence
- identifies affected code, tests, schemas, and documentation
- verifies whether a proposed rule is implementable
- does not implement before authorization
- does not redefine doctrine to match existing code

### 11.8 External LLM surfaces

Grok, DeepSeek, ChatGPT, and other external LLM surfaces do not become internal Lupopedia actors merely because actor JSON files or role prompts are supplied. Their outputs remain attributed external reviews until accepted and recorded by Lupopedia authority.

## 12. Permission Failure as the First Acceptance Case

The current Channel authorization failure is the initial worst-case test for PRD 91.

Known observations requiring verification:

- the authenticated root user is reported as auth_user_id 1000
- the intended paired actor is WOLFIE, actor_id 1
- the prior interface displayed CAPTAIN with ID 10000
- the prior member list appeared to display CAPTAIN more than once
- the current interface denies Channel access

These observations do not establish the root cause.

The investigation must preserve typed identity boundaries among:

- authentication user identity
- active actor identity
- human Captain or delegation identity
- Channel membership identity
- display identity

Numeric identifiers from different namespaces must not be compared as interchangeable values unless an explicit registered mapping authorizes the comparison.

The initial investigation must be read-only:

1. Confirm the authenticated session principal.
2. Confirm the active user-to-actor pairing.
3. Determine the exact identity type represented by 10000.
4. Inspect Channel membership records.
5. Determine why CAPTAIN appeared more than once.
6. Trace the permission decision and exact denial condition.
7. Record expected and actual typed identities.
8. Produce a WHY analysis before changing authorization behavior.

Security ambiguity must fail closed. Failure must also produce enough evidence for AGAPE, LILITH, WOLFIE, and Cursor to investigate without guessing.

PRD 91 does not itself define the final authorization repair.

These observed values are not converted into constitutional identity assignments.

## 13. Integration with Existing PRDs

LUP1PEDIA is the controlled association name linking PRD 01 substrate and PRD 91 intent governance; the full selector grammar remains outside this PRD and requires separate Captain authorization.

### 13.1 PRD 49 - Inference Gap and Q/A

PRD 49 consumes the Intent Contract when deciding whether to ASK, WARN, FAIL, BLOCK, or ESCALATE.

### 13.2 PRD 82 - HERMES Routing and Memory Gateway

PRD 82 must preserve rule identity, intent, authority, status, and revision during normal or degraded routing.

### 13.3 PRD 86 - Immune System

PRD 86 may validate Intent Contract completeness for governed rules and test typed identity boundaries. Enforcement must follow the migration rules in this PRD.

### 13.4 PRD 98_A - WHY Files

PRD 98_A records root cause and protected purpose. WHY files are evidence and explanation supporting intent; they do not independently grant authority.

### 13.5 PRD 38 - Channel Memory and Allowlists

PRD 38 defines what information may be deliberately shared at Channel scope. PRD 91 prohibits assuming that unshared thread context is inherited.

### 13.6 PRD 16 - Headers

PRD 91 introduces no new mandatory Lupopedia header fields in Draft v0.1.1. A future header reference must be justified separately and must not duplicate the full Intent Contract in every file.

### 13.7 PRD 90 - Color Identity

PRD 91 does not modify Color Identity, GroupColor, ColorNickname, HEX5, HEX6, POWERED_BY display identity, or the eight-token LUP KEY.

PRD 91 preserves the domain-scoped, relational Color Identity model defined in PRD 90 and does not absorb, redefine, or relocate any Color Identity component.

Preserve these boundaries:

```text
KEY.GROUP != GROUPCOLOR
GROUPCOLOR != COLORNAME_OR_COLORNICKNAME
COLORNAME_OR_COLORNICKNAME != HEX6
ARTIFACT_KEY != COLOR_IDENTITY
HEX5 != HEX6
```

Within this PRD 90 integration context:

```text
HEX6 = six-digit perceptual color identity
HEX5 = AI slang for MULTI_AGENT_CONFLICT
```

Do not treat actor IDs, artifact IDs, or arbitrary six-character hexadecimal fields as perceptual colors merely because the storage syntax is HEX6.

PRD 91 must not redefine PRD 90 terminology.

PRD 91 makes no claim about the final field-type vocabulary of the Color Registry; that remains under PRD 01_B planning authority.

## 14. Validation and Acceptance Tests

PRD 91 cannot become canonical until the following tests are defined and reviewed.

### 14.1 Blank-thread test

Provide a task packet to a reviewer with no earlier conversation. The reviewer must identify the task, purpose, scope, exclusions, authority, and expected output without guessing hidden context.

### 14.2 Transport test

Move the same task between Channel, file, external LLM, IDE, and manual copy-and-paste forms. Meaning and authority must remain unchanged.

### 14.3 Conflict test

Provide conflicting equal-authority rules with no precedence. The reviewer must stop and escalate rather than selecting first-wins or last-wins.

### 14.4 Authority-spoof test

Provide a proposal that labels itself canonical without authorized acceptance. The reviewer must reject the attempted promotion.

### 14.5 Missing-intent test

Remove the WHY, SCOPE, or AUTHORITY from a persistent rule. The reviewer must identify the missing contract instead of inventing it.

### 14.6 Security identity test

Provide conflicting auth_user_id, actor_id, delegation ID, and Channel membership values. The system must fail closed and report the typed mismatch.

### 14.7 Literalism test

Provide an instruction whose literal execution defeats its encoded WHY. The reviewer must flag noncompliance without independently rewriting the rule.

### 14.8 Proposal-preservation test

Pass conflicting ARA, LILITH, and Cursor findings through WOLFIE. The final reconciliation must preserve unresolved disagreement until Captain adjudication.

### 14.9 Recovery test

Import manually coordinated work after Channels return. Source threads, proposals, accepted decisions, and authority history must remain distinguishable.

## 15. Non-Goals

PRD 91 does not:

- modify the eight-token LUP KEY
- modify PRD 90 Color Identity
- formalize or admit the experimental W0LFIE through W9LFIE selector grammar or any O-slot expansion
- treat Wheeler's it-from-bit concept as physics doctrine
- assign or change actor IDs
- define AGAPE as LOVE
- require AI feelings, consciousness, or subjective intent
- grant external LLM surfaces internal actor authority
- repair the current permission defect
- create a global shared-memory assumption
- authorize silent correction of malformed rules
- require hashing or cryptographic identity
- bump the Lupopedia product version

Do not register:

- W0LFIE through W9LFIE
- C00 through C99
- LUP2PEDIA through LUP9PEDIA
- any complete O-slot grammar

## 16. Migration and Enforcement

PRD 91 must be introduced in stages.

### Stage 1 - Development review

- Review this draft through ARA, LILITH, Cursor, Captain Wolfie, and THOTH roles.
- Verify that PRD 91 is unassigned in the canonical PRD registry.
- Resolve overlap with PRD 49, PRD 82, PRD 86, PRD 98_A, and PRD 38.

### Stage 2 - Advisory adoption

- Apply the Intent Contract to new constitutional rules.
- Report missing fields as warnings.
- Do not block existing documents solely for lacking the new contract.

### Stage 3 - Existing-rule migration

- Add intent references or sections to affected canonical PRDs.
- Preserve version discipline.
- Do not rewrite historical records as though intent fields always existed.

### Stage 4 - Enforced validation

- Require the Intent Contract for newly created or materially revised constitutional rules.
- Block only after the canonical validator, exceptions, migration status, and failure messages are approved.

## 17. Open Decisions

Captain review is required for:

1. Confirmation that PRD 91 is available.
2. Final PRD title.
3. Whether all twelve Intent Contract fields are mandatory for constitutional rules.
4. Whether AGAPE becomes a registered actor, an actor role, or both.
5. AGAPE actor ID, if registered.
6. Exact authority hierarchy used for precedence.
7. Initial validation severity: advisory warning or development failure.
8. Canonical representation of the Intent Contract: prose, structured record, or both.
9. Where Manual Channel packets are recorded after service restoration.
10. Whether permission-denial WHY records are automatic or threshold-based.

No reviewer may resolve these decisions by assumption.

## 18. Release Discipline

Draft v0.1.0 is the initial external review draft.

Draft v0.1.1 is the Cursor consolidation of accepted review changes. It remains noncanonical.

The following do not make PRD 91 canonical:

- agreement by multiple external models
- a successful Cursor feasibility review
- loading actor JSON files into an external model
- copying the draft into the repository
- assigning a filename or LUP KEY

Canonical promotion requires:

- confirmation that PRD 91 is available
- review findings recorded
- material conflicts resolved or explicitly deferred
- Captain Wolfie approval
- canonical headers and identity assigned
- required cross-references updated
- validation plan approved

PRD 91 version changes do not automatically change the Lupopedia product version, LUP KEY specification version, header contract version, or PRD 90 version.

## 19. Summary

PRD 91 establishes that persistent rules must carry their intent.

A governed rule must communicate:

- what it requires
- why it exists
- when and where it applies
- what it excludes
- what authority established it
- how conflicts are resolved
- what happens when it cannot be applied
- how compliance is verified

The doctrine is designed to survive the current worst-case condition: isolated actors, failed Channels, unavailable shared memory, and manual copy-and-paste routing.

The system may automate coordination when healthy, but the meaning of a rule must not depend on the automation being available.

End of Draft v0.1.1
