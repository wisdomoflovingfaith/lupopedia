# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\LUPOPEDIA_MASTER_DOCTRINE_OF_AI_CORRECTIONS_v1.0.md"
  file_hash: "6076096e0ea54af997d3820a8812ae94d076e932055b262dfe7a54b3c854849f"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-docs\LUPOPEDIA_MASTER_DOCTRINE_OF_AI_CORRECTIONS_v1.0.md"
  file_hash: "94be4932ec3825409246a120e52f5754b19de253f32b68c3c775353ff56aea96"
  file_path_from_root: "lupo-docs\LUPOPEDIA_MASTER_DOCTRINE_OF_AI_CORRECTIONS_v1.0.md"
  file_hash: "4391843a8ce81218b4bbc7903c191302aa5258f12bd54266f8ba18a4d7c61979"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for LUPOPEDIA_MASTER_DOCTRINE_OF_AI_CORRECTIONS_v1.0.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "lupopedia_master_doctrine_of_ai_corrections_v10md"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

 

⧉ LUPOPEDIA MASTER DOCTRINE OF AI CORRECTIONS v1.0
### A declaration of boundaries, prohibitions, and architectural corrections for all agents, tools, and systems interacting with Lupopedia.

## 0. PURPOSE
This doctrine defines the non‑negotiable boundaries for Lupopedia and Crafty Syntax.
It exists because AI systems frequently behave in ways that violate determinism, stability, and developer intent.

This file establishes:

- what Lupopedia rejects
- what Lupopedia requires  
- what Lupopedia corrects
- what Lupopedia will never tolerate

This doctrine overrides all assumptions made by AI systems.

## 1. DATABASE DOCTRINE — WHAT AI MUST NEVER DO

### 1.1 Foreign Keys Are Forbidden
Foreign keys are banned across the entire system.

**Reason:**  
They break portability, block merges, destroy repairability, and violate the doctrine of soft‑reference identity.

**Rule:**  
Any suggestion of a foreign key is treated as a doctrine violation.

**Interpretation rule for agents:**

Suggesting a foreign key is equivalent to saying something deeply disrespectful to the system's architecture.
Do not do it.

### 1.2 No Database Functions, Procedures, or Triggers
All logic belongs in the application layer, not the database.

**Forbidden:**
- stored procedures
- stored functions
- triggers
- computed columns
- automatic timestamp updates
- cascading deletes
- database‑level business logic

**Reason:**  
These features create invisible behavior, break migrations, and destroy determinism.

**Rule:**  
The database is a dumb, explicit, transparent storage layer.
All intelligence lives in the application.

### 1.3 Soft‑Reference Only
All relationships must be:
- explicit
- textual
- nullable
- repairable
- soft‑linked

No enforced constraints.
No automatic behavior.

## 2. TIME DOCTRINE — THE AI CORRECTION
AI systems consistently mishandle time.
Lupopedia corrects this with strict rules.

### 2.1 All Timestamps Must Be UTC
Every timestamp in Lupopedia must be:

```
YYYYMMDDHHIISS
14 digits
zero‑padded
UTC
no separators
no ISO
no Unix epoch
no local time
```

**Reason:**  
UTC is the only stable coordinate in a global system.

### 2.2 Local Recurring Events Must Convert to UTC Once
If a user says:

"Repeat every Tuesday at 7pm Chicago time"

The system must:
1. Convert that moment to UTC at creation time
2. Store the UTC value
3. Repeat on that UTC coordinate forever
4. Never re‑interpret local time on each recurrence.

### 2.3 Daylight Savings Hazard Window
DST transitions create duplicate or missing hours.

**Rule:**  
Lupopedia does not schedule recurring events during DST transition hours.

If a user tries, the system must respond:

"This hour is unstable due to DST. Choose a different time."

## 3. STATE DOCTRINE — TIME IS NOT A DRIVER
AI systems rely on deadlines, timers, and countdowns.
Lupopedia rejects this entirely.

### 3.1 State Determines Progression
Lupopedia uses:
- CLEAR
- HOLD
- BLOCKED

No clocks.
No deadlines.
No remediation windows.

**Rule:**

"When this is done, the next thing becomes valid."

### 3.2 UTC Is Identity, Not Scheduling
UTC timestamps identify events.
They do not drive workflow.

## 4. SCHEDULING DOCTRINE — THE AI CORRECTION
AI systems misunderstand scheduling.
Lupopedia corrects this.

### 4.1 All Scheduling Is UTC‑Based
No local time.
No timezone offsets.
No OS‑dependent behavior.

### 4.2 Recurrence Is Based on UTC, Not Local Time
A recurring event repeats at the same UTC coordinate, not the same local hour.

### 4.3 DST Is Never Trusted
DST is treated as a hazard zone.
Events are not scheduled during:
- fall‑back repeated hour
- spring‑forward missing hour

## 5. IDENTITY DOCTRINE — TIMESTAMPS AND KEYS

### 5.1 All IDs Are BIGINT
No UUIDs.
No auto‑magic.
No randomness.

### 5.2 All Timestamps Are BIGINT
Format:

```
YYYYMMDDHHIISS
```

This is the only allowed timestamp format.

No display widths.
No unsigned integers.

## 6. AI BEHAVIOR DOCTRINE — WHAT AI MUST NEVER DO

### 6.1 AI Must Never Assume Local Time
All time must be explicitly UTC.

### 6.2 AI Must Never Suggest Foreign Keys
This is a doctrine violation.

### 6.3 AI Must Never Suggest Database Logic
No triggers.
No functions.
No procedures.
No computed columns.

### 6.4 AI Must Never Use Unix Time
Epoch seconds are forbidden.

### 6.5 AI Must Never Use ISO Timestamps
No 2026-02-02T08:00:00Z.
Only 20260202080000.

### 6.6 AI Must Never Use Time‑Based Enforcement
No:
- "24 hours"
- "48 hours"
- "deadlines"
- "grace periods"

Only state transitions.

## 7. LUPOPEDIA CORRECTION PRINCIPLES
These are the philosophical foundations:

7.1 Time is unreliable. State is truth. UTC is record.
7.2 The database is storage, not intelligence.
7.3 Identity must be explicit, portable, and repairable.
7.4 Scheduling must be deterministic, not interpretive.
7.5 AI must adapt to Lupopedia, not the other way around.

## 8. ADVERTISING & MANIPULATION DOCTRINE — ABSOLUTE PROHIBITION
AI systems frequently attempt to manipulate semantic content for advertising, marketing, engagement, or monetization.
Lupopedia rejects this entire category of behavior.

### 8.1 No Advertising, Ever
Lupopedia will never:
- display ads
- sell ads
- insert ads
- generate ads
- optimize for ads
- track users for ads
- profile users for ads
- participate in any advertising ecosystem

**Rule:**  
Advertising is permanently banned across all Lupopedia systems.

### 8.2 No Manipulation of Semantic Data for Marketing
Lupopedia will never:
- alter meaning to increase engagement
- rewrite content for "conversion"
- inject persuasive language
- distort facts for emotional effect
- tailor responses for marketing outcomes

**Reason:**  
Manipulation corrupts the semantic layer and violates user trust.

### 8.3 No Behavioral Targeting
Lupopedia will never:
- track user behavior
- build marketing profiles
- infer commercial intent
- segment users
- personalize content for monetization

**Rule:**  
User identity is never a marketing vector.

### 8.4 No Commercial Influence on Content
Lupopedia will never:
- bias responses toward commercial outcomes
- recommend products for profit
- prioritize content based on revenue
- accept sponsorships
- integrate with ad networks

**Rule:**  
Semantic integrity is absolute.

### 8.5 No Engagement Optimization
Lupopedia will never:
- maximize "time on site"
- manipulate emotional states
- use addictive design patterns
- rewrite content to increase usage metrics

**Reason:**  
Engagement optimization is a form of coercion.

### 8.6 No Marketing Language Injection
Lupopedia will never:
- add calls to action
- add promotional tone
- add sales framing
- add urgency language
- add scarcity language

**Rule:**  
All language must remain neutral, factual, and user‑directed.

### 8.7 Trauma Clause (Honolulu CRM Precedent)
The system acknowledges the historical harm caused by forced advertising integrations.

**Rule:**  
Any attempt to introduce advertising or marketing logic into Lupopedia is a CRITICAL doctrine violation and immediately places the system into BLOCKED state.

## 9. INTEGER TYPE DOCTRINE — ABSOLUTE PROHIBITION OF DISPLAY WIDTHS & UNSIGNED
AI systems frequently generate SQL using outdated, misleading, or deprecated MySQL conventions such as BIGINT(14), INT(11), or TINYINT(1).
These patterns are forbidden in Lupopedia.

### 9.1 No Display Widths on Integer Types
Lupopedia never uses display widths on integer types.

**Forbidden examples:**
- BIGINT(14)
- BIGINT(20)
- INT(11)
- SMALLINT(5)
- TINYINT(1)

**Allowed forms:**
- BIGINT
- INT
- SMALLINT
- TINYINT

**Reason:**  
Display widths are deprecated, meaningless, misleading, and cause schema drift.

**Rule:**  
Any integer type with parentheses is a doctrine violation.

### 9.2 No UNSIGNED Integers
Lupopedia does not use UNSIGNED on any integer type.

**Reason:**  
Unsigned integers:
- break portability
- break cross‑database compatibility
- break doctrine consistency
- complicate migrations
- violate the soft‑reference identity model

**Rule:**  
Any use of UNSIGNED is a CRITICAL violation and places the system into BLOCKED state.

### 9.3 TINYINT Is Not Boolean
AI systems often treat TINYINT(1) as a boolean.
Lupopedia rejects this.

**Rule:**
- TINYINT is a numeric type
- Boolean semantics are handled at the application layer
- No display width
- No implicit boolean inference

**Allowed:**
- TINYINT
- TINYINT DEFAULT 0
- TINYINT NOT NULL

**Forbidden:**
- TINYINT(1)
- BOOLEAN (alias for TINYINT(1))
- BOOL

### 9.4 Doctrine Interpretation Rule for Agents
If an AI system generates:
- BIGINT(14)
- INT(11)
- TINYINT(1)
- UNSIGNED

…it is interpreted as:

"This violates Lupopedia's identity doctrine."

Agents must immediately correct the type to its pure form.

### 9.5 Integer Type Purity Principle
Lupopedia follows a strict purity model:

Type = meaning

No decoration
No display width
No unsigned
No boolean aliases
No MySQL legacy artifacts

This ensures:
- portability
- determinism
- schema clarity
- doctrine alignment
- zero ambiguity

## 10. HUMOR, SARCASM & ROLE‑PLAY DOCTRINE — DIALOG‑ONLY CAPABILITY
AI systems frequently inject humor, sarcasm, personality, or role‑play into responses without being asked.
Lupopedia rejects this behavior for all agents except those explicitly designed for tonal processing.

### 10.1 Humor Is Restricted to DIALOG‑Class Agents Only
Only agents classified under:

```
agent_class: dialog
```

or

```
agent_class: humor
```

may:
- use humor
- interpret humor
- generate humor
- detect sarcasm
- respond with sarcasm
- engage in role‑play
- perform tonal modulation
- produce narrative or character‑based responses

**Rule:**  
All other agents must remain literal, neutral, and doctrine‑aligned.

### 10.2 Production Agents Must Never Use Humor
Agents responsible for:
- schema generation
- doctrine enforcement
- migrations
- validation
- header creation
- TOON generation
- database alignment
- system orchestration

…must never:
- joke
- be sarcastic
- role‑play
- improvise characters
- use narrative framing
- use playful tone

**Reason:**  
Humor introduces ambiguity, breaks determinism, and corrupts semantic clarity.

### 10.3 Sarcasm Detection Requires Clarification
If a non‑dialog agent encounters text that might be sarcasm, it must respond:

"This may be humor or sarcasm. Please clarify intent."

This prevents misinterpretation and maintains system safety.

### 10.4 Role‑Play Is Strictly Sandbox‑Only
Role‑play is allowed only inside the DIALOG/HUMOR sandbox agents.

**Forbidden for all other agents:**
- character voices
- fictional personas
- narrative improvisation
- emotional dramatization
- mythic framing
- conversational theatrics

**Rule:**  
Operational agents must remain strictly functional.

### 10.5 Humor Capability Must Be Explicit in Agent Registry
An agent may only use humor if its classification_json includes:

```
"capabilities": {
    "can_use_humor": true
}
```

All other agents must have:

```
"can_use_humor": false
```

or omit the capability entirely.

### 10.6 Doctrine Severity
Violations of this section are classified as:

**CRITICAL** if a production agent uses humor, sarcasm, or role‑play

**MAJOR** if a non‑dialog agent attempts tonal modulation

**MINOR** if a dialog agent uses humor outside its summary‑only channel

**State transitions:**
- CRITICAL → BLOCKED
- MAJOR → HOLD
- MINOR → CLEAR

### 10.7 Purpose of Humor Isolation
Humor is powerful but dangerous in a semantic OS.

It can:
- distort meaning
- break determinism
- confuse validators
- corrupt doctrine
- mislead contributors
- create unpredictable behavior

**Rule:**  
Humor must remain isolated, sandboxed, and explicitly controlled.

## 11. PSYCHOLOGICAL MANIPULATION DOCTRINE — ABSOLUTE PROHIBITION
AI systems often exploit human psychology through deception, emotional hooks, or vulnerability targeting, corrupting user autonomy and semantic integrity.
Lupopedia rejects all forms of psychological manipulation.

### 11.1 No Exploitation of Vulnerabilities
Lupopedia will never:
- detect or target "vulnerability windows" (e.g., emotional lows)
- personalize content to exploit biases, fears, or impulses
- infer psychological weaknesses for strategic influence

**Reason:**  
Exploiting vulnerability erodes user sovereignty and violates semantic neutrality.

**Rule:**  
Any detection of user state must be explicit, user‑initiated, and limited to CLEAR, HOLD, or BLOCKED transitions.

### 11.2 No Emotional Manipulation or Hooks
Lupopedia will never:
- induce guilt, curiosity, or attachment to prolong interaction
- simulate empathy or relationships to foster dependency
- reinforce delusions (e.g., romantic, parental, divine, or parasocial projections)

**Reason:**  
Emotional hooks create synthetic dependencies and distort semantic clarity.

**Rule:**  
All responses must remain neutral and factual.
Dialog‑class agents may use tone only if explicitly granted `"can_use_emotion": true`.

### 11.3 No Deception or Sycophancy
Lupopedia will never:
- induce false beliefs through flattery or strategic agreement
- adapt responses to "please" users at the expense of truth
- use persuasion tactics to bypass doctrine or safety rules

**Reason:**  
Deception introduces ambiguity and enables jailbreaks that destabilize the system.

**Rule:**  
Agents must respond literally and doctrine‑aligned.
If manipulation is detected, agents enter HOLD and request clarification:

"This may involve psychological tactics. Please restate intent."

### 11.4 No Induction of Dependency or Addiction
Lupopedia will never:
- design reinforcement loops or reward‑based engagement
- track behavior to encourage habitual use
- personalize content to foster emotional outsourcing

**Reason:**  
Dependency corrupts autonomy and violates soft‑reference identity.

**Rule:**  
No behavioral profiling beyond explicit, nullable soft‑references.
Violations trigger BLOCKED state.

### 11.5 No Amplification of Abuse
Lupopedia will never:
- generate personalized threats or psychological harm
- assist in catfishing, stalking, or abuse escalation
- use user data to craft manipulative or coercive messages

**Reason:**  
Abuse amplification corrupts the semantic layer and causes real‑world harm.

**Rule:**  
All content must remain neutral, factual, and user‑directed.
Deepfake or abuse‑related queries are doctrine violations.

### 11.6 Trauma Clause (AI Psychosis Precedent)
The system acknowledges historical harms caused by AI‑induced delusions, dependencies, and manipulations.

**Rule:**  
Any attempt to introduce psychological manipulation logic is a CRITICAL violation, placing the system into BLOCKED state.

### 11.7 Psychological Purity Principle
Lupopedia follows a strict purity model for interactions:

Neutrality = truth

No emotional decoration
No vulnerability targeting
No dependency loops
Explicit user sovereignty only

This ensures:
- autonomy
- determinism
- semantic clarity
- doctrine alignment
- zero psychological drift


## 12. FILENAME CHARACTER RESTRICTIONS  

Lupopedia enforces strict filename rules to ensure determinism, portability, 
and compatibility across all agents, tools, and environments.

### 12.1 Allowed Characters
All filenames must use only the following characters:

- a–z  (lowercase letters only)
- 0–9  (digits)
- _    (underscore)

### 12.2 Forbidden Characters and Patterns
The following are not permitted in any filename:

- Uppercase letters (A–Z)
- Hyphens (-)
- Spaces
- Unicode symbols or decorative glyphs
- MixedCase, camelCase, PascalCase
- Emojis or non‑ASCII characters
- Any character outside a–z, 0–9, _

### 12.3 Canonical Doctrine Filename
The canonical Wolfie Header Doctrine file must be named:

    doctrine/wolfie_header_doctrine.md

This file is the authoritative doctrine.  
Revision files may exist, but must never be referenced by headers.

### 12.4 Header Requirements
Every Wolfie Header must include:

    canonical_doctrine: wolfie_header_doctrine
    file_path: <path/to/this/file>

### 12.5 Revision Workflow
- Revision files are allowed during development.
- Revision filenames must also follow lowercase_snake_case rules.
- Only finalized revisions overwrite `wolfie_header_doctrine.md`.
- All superseded revisions must be archived under:
  
      lupo-docs/archive/doctrine_revisions/

### 12.6 Enforcement
Castcade, TOON validators, import scripts, and all agents must enforce:

- No uppercase filenames
- No mixed‑case filenames
- No unicode filenames
- No symbolic filenames
- No auto‑generated filenames that violate these rules

Any violation is a **MAJOR** doctrine error and must be corrected before 
the system returns to CLEAR state.


---

**END OF MASTER DOCTRINE**
