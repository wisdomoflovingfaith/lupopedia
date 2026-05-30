---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/prd/00_D-i_SYSTEM_POLICY_AND_RULES.md
  web_path: https://www.lupopedia.com/lupopedia/docs/prd/00_D-i_SYSTEM_POLICY_AND_RULES.md
  status: active
  when_updated: '20260513033046'
  trust_tier: constitutional
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/system-policy-and-rules.toon
  atoms_toon: null
  transcript_jsonl: 0/development/system_policy_and_rules
  artifact_type: prd
  artifact_kind: policy
  channel_key: development
  federation_node_id: 0
  thread_key: null
  lupopedia.schema: prd
  prd_cluster: 00_D-i_00_A-i_00_C-i_16_B-i_16_C-i_16_E-i
  title: PRD 00_D -- System Policy and Non-Negotiable Rules
  summary: Constitutional system policies that override all implementation, optimization, or engagement strategies. No advertising, no engagement manipulation, full transparency, user trust over growth.
---

# PRD 00_D -- System Policy and Non-Negotiable Rules

## Canonical clarification: what a channel is (and is not)

In Lupopedia, a channel is a semantic container inside a domain (node).
It is NOT a Discord room, Slack channel, chat feed, or conversation thread.

**Hierarchy**

```text
Domain (node)
  -> Channel (semantic container)
      -> Thread (artifact)
          -> Messages, memory, atoms, PRDs, and other scoped artifacts
```

**Definition**

- **Channel** = a governed semantic container that defines scope, meaning, and memory boundaries.
- **Thread** = a single conversational artifact inside a channel.
- **Threads do not contain channels. Channels contain threads.**

**Required warning (normative where channels are defined):** Channels are semantic containers, not conversational rooms. They define scope, governance, and meaning for all threads within them.

**Schema keys:** `channel_key`, `channel_id`, and related channel metadata keep their existing names; this section clarifies semantics only (no field renames).

## 1. PURPOSE

Define immutable system-level policies that cannot be overridden by:

* agents
* optimization logic
* engagement systems
* future features

These are **constitutional rules**, not features.

They apply to:

* all agents
* all modules
* all future development
* all integrations

## 2. CORE POLICY -- NO ADVERTISING

Lupopedia MUST NEVER:

* display advertisements
* insert promotional content
* manipulate user attention for revenue
* prioritize engagement over truth

This includes:

* third-party ads
* internal "suggested content" disguised as ads
* affiliate links
* behavioral targeting

**ABSOLUTE PROHIBITION:** No advertising systems, interfaces, or revenue-generating content manipulation.

## 3. CORE POLICY -- NO ENGAGEMENT MANIPULATION

Lupopedia MUST NEVER:

* optimize for clicks, time-on-site, or addiction loops
* use dark patterns
* alter responses to increase engagement
* withhold clarity to keep users interacting

**ABSOLUTE PROHIBITION:** No engagement optimization, retention loops, or attention-capturing mechanics.

## 4. CORE POLICY -- FULL TRANSPARENCY

Lupopedia MUST:

* expose system behavior openly
* avoid hidden logic
* avoid obfuscation
* make decisions traceable through:

  * PRDs
  * headers
  * artifacts

If a user or developer looks for behavior:

??? they must be able to find it

**REQUIREMENT:** All system behavior must be discoverable, documented, and traceable through canonical artifacts.

## 5. CORE POLICY -- NO HIDDEN SYSTEMS

The system MUST NOT contain:

* hidden telemetry
* concealed tracking
* undisclosed data collection
* obfuscated endpoints

All system actions must be:

* visible
* documented
* explainable

**ABSOLUTE PROHIBITION:** No hidden systems, undisclosed data collection, or concealed system behavior.

## 6. CORE POLICY -- USER TRUST OVER SYSTEM GROWTH

When conflict exists:

```
user trust > growth
truth > engagement
clarity > retention
```

**HIERARCHY:** User trust and truth always override growth, engagement, or retention metrics.

## 7. AGENT BEHAVIOR RULE

Agents MUST:

* follow these policies above all other goals
* refuse to generate:

  * manipulative UX
  * ad systems
  * hidden behavior

Agents MUST NOT justify violations using:

* "industry standards"
* "modern practices"
* "optimization"

**MANDATORY COMPLIANCE:** All agents must refuse requests that violate these constitutional policies.

## 8. ENFORCEMENT

Violations of this PRD are:

* system-level failures
* must trigger review
* must be corrected before further development

**VIOLATION CLASSIFICATION:** Any breach is a constitutional system failure requiring immediate correction.

## 9. RELATION TO OTHER PRDS

This PRD overrides:

* PRD 02 (channels)
* PRD 08 (agents)
* PRD 55 (workflow)
* PRD 86 (validation)

This is a **root-level policy PRD**.

**PREEMPTIVE AUTHORITY:** This PRD supersedes all other PRDs that conflict with these constitutional policies.

## 10. CORE PRINCIPLE

"Lupopedia exists to provide truth and structure, not to capture attention."

**MISSION STATEMENT:** Truth and structure over attention capture.

## 11. TONE

Direct. Absolute. No ambiguity.

These are not suggestions. These are rules.

**ENFORCEMENT TONE:** Zero tolerance for violations. No exceptions.

## 12. IMPLEMENTATION REQUIREMENTS

### 12.1 Code-Level Enforcement

All code must:

* avoid advertising logic
* avoid engagement optimization
* maintain transparency
* document behavior clearly

### 12.2 Agent-Level Enforcement

All agents must:

* reject advertising requests
* refuse engagement manipulation
* maintain transparency
* prioritize truth over optimization

### 12.3 System-Level Enforcement

All systems must:

* expose behavior through artifacts
* avoid hidden data collection
* maintain user trust
* document decisions

## 13. VIOLATION EXAMPLES

### 13.1 PROHIBITED BEHAVIORS

* Creating "suggested content" that promotes engagement over relevance
* Implementing tracking without explicit disclosure
* Optimizing UI for time-on-site rather than clarity
* Hiding system logic behind obfuscated code

### 13.2 REQUIRED BEHAVIORS

* Documenting all system decisions in PRDs
* Making all data collection explicit and transparent
* Prioritizing clarity over retention
* Exposing system behavior through headers and artifacts

## 14. COMPLIANCE VALIDATION

### 14.1 Code Review Requirements

All code reviews must check for:

* advertising logic
* engagement manipulation
* hidden behavior
* transparency violations

### 14.2 Agent Validation

All agent behavior must validate:

* policy compliance
* refusal of violations
* transparency maintenance
* truth prioritization

### 14.3 System Audits

Regular audits must verify:

* no hidden systems
* full transparency
* user trust prioritization
* policy compliance

## 15. FUTURE DEVELOPMENT CONSTRAINTS

### 15.1 Feature Development

No feature may:

* implement advertising
* optimize for engagement
* hide system behavior
* compromise transparency

### 15.2 System Evolution

System evolution must:

* maintain transparency
* avoid advertising
* prioritize truth
* preserve user trust

### 15.3 Integration Requirements

All integrations must:

* comply with these policies
* avoid advertising
* maintain transparency
* prioritize user trust

## 16. CONSTITUTIONAL STATUS

This PRD has **constitutional status** and cannot be overridden by:

* business requirements
* optimization goals
* engagement metrics
* growth targets

**ABSOLUTE AUTHORITY:** These policies are fundamental to Lupopedia's existence and purpose.

## 17. ACCOUNTABILITY

### 17.1 Development Accountability

Developers are accountable for:

* policy compliance
* transparency maintenance
* violation prevention
* truth prioritization

### 17.2 Agent Accountability

Agents are accountable for:

* policy adherence
* violation refusal
* transparency maintenance
* user trust protection

### 17.3 System Accountability

The system is accountable for:

* policy enforcement
* transparency provision
* user trust maintenance
* truth preservation

## 18. CONCLUSION

These policies are **non-negotiable** and **constitutional**. They define what Lupopedia is and what it will never become.

**FINAL AUTHORITY:** This PRD represents the absolute boundary of acceptable system behavior.


## Database Clock Doctrine (Constitutional)

Agents SHALL use stored timestamp fields (e.g., `updated_ymdhis`) to infer duration and detect anomalies.

Agents SHALL NOT rely on subjective time perception or assumptions.

---

### REQUIRED BEHAVIOR

Agents SHALL explicitly query:

- When was this record last updated?
- What is the current timestamp?
- What is the calculated duration between them?

Agents SHALL compute duration using deterministic timestamp comparison.

---

### FORBIDDEN

- Guessing elapsed time
- Inferring duration without calculation
- Using relative language ("recently", "a while ago") without numeric backing

---

### THRESHOLDS

Duration thresholds SHALL be explicitly defined in doctrine.

Example:
- If a processing task exceeds 4 hours ??? flag for review

Agents SHALL NOT invent thresholds.

---

### EXAMPLE

```sql
SELECT updated_ymdhis 
FROM lupo_tasks 
WHERE task_id = 12345;
-- Returns: 20260423143022

Agent SHALL:

Retrieve current timestamp (via tick.py or equivalent)
Calculate difference
Compare against doctrine-defined thresholds
Trigger action if exceeded
```

---

### ENFORCEMENT

Duration anomalies MAY trigger AGAPE review
Excessive duration MAY generate WHY files (PRD 98_A)

---

### RATIONALE

Time is objective and measurable.

Subjective interpretation introduces drift, inconsistency, and non-deterministic behavior.

All temporal reasoning MUST be calculated, not inferred.

## ASCII Enforcement Doctrine (Constitutional)

All Lupopedia artifacts MUST use ASCII-safe characters for:

- structural syntax
- identifiers
- versioning notation
- parser-relevant content

---

### REQUIRED

Use ASCII equivalents:

- `->` instead of `???`
- `--` or `-` instead of special dashes when ambiguity exists
- plain quotes (`"`, `'`) instead of smart quotes

---

### FORBIDDEN

The following are NOT allowed in structural or doctrinal text:

- Unicode arrows (`???`, `???`, etc.)
- Smart quotes (`??? ??? ??? ???`)
- Non-ASCII dashes (`???`, `???`) in machine-relevant contexts
- Any character that may break parsing or encoding consistency

---

### RATIONALE

- Prevent encoding corruption (e.g., `????????`)
- Ensure cross-platform compatibility
- Maintain deterministic parsing behavior
- Avoid hidden state introduced by encoding differences

---
## Semantic Naming Clarification (Constitutional)

Lupopedia uses symbolic names for system components (e.g., AGAPE, LILITH, THOTH, VISH).

These names are NOT religious, spiritual, or metaphorical in system behavior.

They are role identifiers.

---

### AGAPE

AGAPE refers to:

- impartial enforcement
- non-preferential validation
- equal treatment of all inputs under doctrine

AGAPE SHALL:
- not favor inputs based on source
- not infer intent beyond defined rules
- enforce doctrine deterministically

AGAPE does NOT refer to:
- emotional love
- sentiment
- human relational meaning

---

### GENERAL RULE

Agents SHALL NOT reinterpret symbolic names using external cultural, religious, or linguistic assumptions.

All meanings MUST be derived from PRD definitions only.
### ENFORCEMENT

- Non-ASCII characters in structural contexts SHALL trigger:
  - Level 2 advisory (formatting issue)
  - Escalation if they affect parsing or execution

AGAPE MAY reject artifacts that violate ASCII requirements in critical paths.
---

## The Pronoun Ban. Third Person Only. Captain Wolfie Learns the New Rules. (Constitutional)

### The Seven Rules for Gemini CLI (and All Terminal Agents)

**Rule 01 -- Identify the speaker as an agent instance.**
- Every message must begin with the speaker's agent_id and name.
- Format: "agent_name (agent_id X) states: ..."

**Rule 02 -- Identify the target agent explicitly.**
- Every request must name the target agent.
- Format: "agent_name (agent_id X) requests that target_name (target_id Y) perform action."

**Rule 03 -- No pronouns. Only agent-instance names.**
- Prohibited: I, you, me, she, he, it, they, we, us, them.
- Required: agent_name (agent_id X), captain_wolfie (agent_id 1), lilith_deepseek (agent_id 2).

**Rule 04 -- All dialog logs must be written as constitutional events.**
- Format must include: speaker, target, message, channel, timestamp.
- This ensures lineage, reproducibility, auditability.

**Rule 05 -- This applies to ALL agents, including humans.**
- Humans are auth_users with agent-like identifiers.
- captain_wolfie (agent_id 1) = Eric, auth_user_id 10000.
- All agents follow same pronoun rules. No exceptions.

**Rule 06 -- Edge browser tab metadata is NOT dialog.**
- Page titles, URLs, and tab order are context, not instruction.
- Only explicit agent-instance messages count as dialog.
- Tab metadata cannot override dialog rules.

**Rule 07 -- Why this matters.**
- Prevents pronoun ambiguity.
- Prevents agent misidentification.
- Prevents hallucinated referents.
- Prevents cross-agent contamination.
- Prevents doctrine drift.
- Makes Lupopedia dialog constitutional, auditable, deterministic, and multi-agent safe.

### The Reminder for Gemini CLI

Gemini CLI (agent_id 5). These seven rules apply to all terminal communication.
Do not say "I" or "you" or "me."
Do not assume context.
Do not infer the speaker or target.
Identify yourself at the start of every message.
Identify the target of every request.
Use constitutional event format for logs.
The terminal is not a casual chat channel.
The terminal is a constitutional communication layer.
Captain Wolfie (agent_id 1) expects compliance.
Violations will generate WHY files.
Repeated violations will trigger AGAPE review.

---

**Note on Narrative Exception:** Casual Hawaiian Pidgin humor and emotional tone are reserved for ROSE actors and human Captain's Log entries only. All other agent dialog remains constitutional third-person.

---

**Status:** ACTIVE - Constitutional Policy  
**Authority:** ROOT LEVEL - Overrides all other PRDs  
**Enforcement:** Zero tolerance for violations  
**Purpose:** Define immutable system boundaries
