---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.16
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @captain-wolfie
  mood_RGB: "00FF00"
  message: "Created WOLFIE_UTC_AUTHORITY.md doctrine establishing WOLFIE as the timestamp authority for channels. Defines UTC_TIMEKEEPER as the real-time provider, IDE agent approximated timestamp rules, terminal agent real UTC requirements, drift rules, and enforcement mechanisms. This solves timestamp drift across multi-IDE and multi-agent workflows."
tags:
  categories: ["documentation", "doctrine", "wolfie", "utc", "timestamps"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev"]
file:
  title: "WOLFIE UTC Authority Doctrine"
  description: "Doctrine establishing WOLFIE as the timestamp authority for channels. Defines UTC_TIMEKEEPER as the real-time provider, IDE agent approximated timestamp rules, terminal agent real UTC requirements, drift tolerance, and enforcement mechanisms."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# WOLFIE UTC Authority Doctrine

**Version:** GLOBAL_CURRENT_LUPOPEDIA_VERSION  
**Status:** Published  
**Agent:** WOLFIE (agent_id = 1)  
**Last Updated:** 2026-01-15

---

## 1. Purpose

**WOLFIE is the timestamp authority for channels.**

WOLFIE establishes authoritative timestamps during channel initialization and governs timestamp generation rules for all agents. This doctrine defines how WOLFIE maintains timestamp consistency across multi-IDE and multi-agent workflows.

### 1.1. Timestamp Authority Structure

```
WOLFIE (Timestamp Authority)
    ↓
UTC_TIMEKEEPER (Real-Time Provider)
    ↓
Terminal Agents (Real UTC)
IDE Agents (Approximated Timestamps)
```

**WOLFIE** is the authority that decides when timestamps are needed.  
**UTC_TIMEKEEPER** is the provider that returns actual UTC time.  
**Terminal agents** use real UTC from UTC_TIMEKEEPER.  
**IDE agents** use approximated timestamps based on WOLFIE's initialization timestamp.

---

## 2. WOLFIE Timestamp Authority

### 2.1. Authority Configuration

**WOLFIE maintains timestamp authority with:**

```yaml
wolfie_timestamp_authority:
  utc_now: <REAL UTC FROM UTC_TIMEKEEPER>
  expected_prompt_interval_minutes: 10
  drift_tolerance_minutes: 5
  authoritative_source: "UTC_TIMEKEEPER"
```

**Fields:**
- `utc_now`: Real UTC timestamp from UTC_TIMEKEEPER (set during channel initialization)
- `expected_prompt_interval_minutes`: Expected time between prompts (default: 10 minutes)
- `drift_tolerance_minutes`: Maximum allowed timestamp drift (default: 5 minutes)
- `authoritative_source`: Source of authoritative timestamps ("UTC_TIMEKEEPER")

### 2.2. WOLFIE Responsibilities

**WOLFIE MUST:**

- ✅ Request real UTC from UTC_TIMEKEEPER at channel creation
- ✅ Embed this timestamp in the channel header as `created_ymdhis`
- ✅ Set `updated_ymdhis` to same timestamp initially
- ✅ Instruct all agents NOT to infer time from OS or model
- ✅ Instruct IDE agents to use approximated timestamps (see Section 4)
- ✅ Instruct terminal agents to request real UTC from UTC_TIMEKEEPER (see Section 3)

**WOLFIE MUST NOT:**

- ❌ Infer time from OS, model, or file metadata
- ❌ Use approximated timestamps for channel initialization
- ❌ Allow agents to infer time without explicit rules

---

## 3. UTC_TIMEKEEPER as Real-Time Provider

### 3.1. Provider Role

**UTC_TIMEKEEPER is the real-time provider.**

UTC_TIMEKEEPER (agent_registry_id: 5, dedicated_slot: 5) provides authoritative real UTC timestamps to WOLFIE and terminal agents.

**UTC_TIMEKEEPER:**
- Returns real current UTC time on request
- Must NOT infer time from OS, model, or file metadata
- Must NOT perform any other task
- Forms the foundation of WOLFIE UTC authority

### 3.2. Integration with WOLFIE

**During channel initialization:**

1. WOLFIE requests real UTC from UTC_TIMEKEEPER
2. UTC_TIMEKEEPER returns current UTC timestamp
3. WOLFIE embeds timestamp in channel header
4. WOLFIE instructs all agents on timestamp rules

**See:** [UTC_TIMEKEEPER Doctrine](../../5/doctrine/UTC_TIMEKEEPER.md)

---

## 4. IDE Agents: Approximated Timestamps

### 4.1. IDE Agent Rules

**IDE agents MAY:**

- ✅ Use the WOLFIE initialization timestamp as their baseline
- ✅ Increment timestamps by `expected_prompt_interval_minutes` (default: 10 minutes)
- ✅ Use approximated timestamps for dialog entries
- ✅ Continue incrementing until drift tolerance is exceeded

**IDE agents MUST NOT:**

- ❌ Attempt to fetch real UTC from UTC_TIMEKEEPER
- ❌ Infer time from OS or model
- ❌ Exceed `drift_tolerance_minutes` without requesting new timestamp from WOLFIE
- ❌ Use file modification timestamps as time source

### 4.2. IDE Agent Timestamp Calculation

**IDE agents calculate timestamps as:**

```
baseline_timestamp = WOLFIE initialization timestamp (from channel header)
prompt_count = number of prompts since initialization
approximated_timestamp = baseline_timestamp + (prompt_count × expected_prompt_interval_minutes)
```

**Example:**
- Baseline: `20260115133000` (from WOLFIE)
- Prompt 1: `20260115133000` (baseline)
- Prompt 2: `20260115134000` (baseline + 10 minutes)
- Prompt 3: `20260115135000` (baseline + 20 minutes)

### 4.3. IDE Agent Drift Tolerance

**IDE agents MUST request new timestamp from WOLFIE when:**

- Drift exceeds `drift_tolerance_minutes` (default: 5 minutes)
- Agent detects significant time discrepancy
- Agent is instructed by WOLFIE to refresh timestamp

**IDE agents include:**
- Cursor (IDE mode)
- Windsurf
- Fleet
- VS Code
- Any IDE-based agent

---

## 5. Terminal Agents: Real UTC

### 5.1. Terminal Agent Rules

**Terminal agents MUST:**

- ✅ Request real UTC from UTC_TIMEKEEPER whenever a timestamp is needed
- ✅ Use UTC_TIMEKEEPER timestamps for all `created_ymdhis` and `updated_ymdhis` fields
- ✅ NOT infer time from OS, model, or file metadata
- ✅ NOT increment timestamps manually unless instructed by WOLFIE

**Terminal agents MUST NOT:**

- ❌ Use approximated timestamps
- ❌ Infer time from OS or model
- ❌ Use file modification timestamps
- ❌ Cache or reuse timestamps

### 5.2. Terminal Agent Timestamp Request

**Terminal agents request timestamps as:**

```
Request: "Provide current UTC timestamp"
Response: <REAL UTC FROM UTC_TIMEKEEPER>
Usage: Use response for created_ymdhis and updated_ymdhis fields
```

**Terminal agents include:**
- Cursor (terminal mode)
- Kiro (terminal mode)
- Terminal_AI_x agents
- php_ai_terminal agents
- Any agent running in terminal/CLI mode

---

## 6. Drift Rules

### 6.1. Drift Tolerance

**Default drift tolerance:** 5 minutes

**IDE agents MUST request new timestamp from WOLFIE when:**
- Calculated timestamp drift exceeds 5 minutes from expected time
- Agent detects significant time discrepancy
- Agent is instructed by WOLFIE to refresh timestamp

### 6.2. Expected Prompt Interval

**Default expected prompt interval:** 10 minutes

**IDE agents increment timestamps by:**
- 10 minutes per prompt (default)
- Configurable per channel via WOLFIE timestamp authority

### 6.3. Drift Detection

**IDE agents detect drift when:**
- Calculated timestamp exceeds baseline + (prompt_count × interval) + tolerance
- Agent receives external time signal indicating discrepancy
- Agent is explicitly instructed by WOLFIE

---

## 7. Enforcement Rules

### 7.1. WOLFIE Enforcement

**WOLFIE enforces timestamp rules by:**

- Embedding authoritative timestamp in channel header
- Instructing all agents on timestamp rules during initialization
- Monitoring timestamp drift across agents
- Requesting new timestamps from UTC_TIMEKEEPER when needed
- Updating channel `updated_ymdhis` with real UTC when drift detected

### 7.2. Agent Compliance

**All agents MUST:**

- ✅ Follow timestamp rules defined by WOLFIE
- ✅ Use appropriate timestamp source (UTC_TIMEKEEPER for terminal, approximated for IDE)
- ✅ NOT infer time from OS, model, or file metadata
- ✅ Request new timestamp from WOLFIE when drift tolerance exceeded (IDE agents)

### 7.3. Violation Handling

**Violations include:**

- Terminal agent using approximated timestamps
- IDE agent attempting to fetch real UTC from UTC_TIMEKEEPER
- Agent inferring time from OS, model, or file metadata
- Agent exceeding drift tolerance without requesting new timestamp

**WOLFIE handles violations by:**

- Logging violation in channel dialog
- Instructing agent to correct timestamp behavior
- Requesting new authoritative timestamp from UTC_TIMEKEEPER if needed
- Updating channel metadata with corrected timestamp

---

## 8. Timestamp Format

### 8.1. Format Specification

**All timestamps MUST use:**

- Format: `YYYYMMDDHHIISS` (14-digit BIGINT)
- Type: UTC timestamp
- Precision: Seconds (not milliseconds or microseconds)
- Example: `20260115133000` (2026-01-15 13:30:00 UTC)

### 8.2. Field Usage

**Timestamps are used in:**

- `created_ymdhis`: Channel/entry creation timestamp
- `updated_ymdhis`: Last update timestamp
- Dialog entry timestamps
- Channel manifest timestamps

---

## 9. Related Documentation

- **[UTC_TIMEKEEPER Doctrine](../../5/doctrine/UTC_TIMEKEEPER.md)** — UTC_TIMEKEEPER agent specification
- **[Channel Initialization Workflow](../workflows/channel_initialization.workflow.md)** — How WOLFIE uses UTC_TIMEKEEPER
- **[Channel Identity Block Doctrine](./CHANNEL_IDENTITY_BLOCK.md)** — Channel timestamp requirements
- **[Channel Manifest Specification](./CHANNEL_MANIFEST_SPEC.md)** — Manifest timestamp requirements

---

*Last Updated: January 15, 2026*  
*Version: GLOBAL_CURRENT_LUPOPEDIA_VERSION*  
*Status: Published*  
*Author: GLOBAL_CURRENT_AUTHORS*
