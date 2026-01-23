---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
dialog:
  speaker: Kiro
  target: @everyone
  message: "Added Canonical Time Rule to DIALOG_DOCTRINE.md and TIMESTAMP_DOCTRINE.md. All agents must request current UTC time explicitly and never infer from system sources."
  mood: "FF0000"
tags:
  categories: ["documentation", "doctrine", "timestamp"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "Canonical Time Rule Update Summary"
  description: "Summary of Canonical Time Rule addition to documentation"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Canonical Time Rule Update Summary

**Date:** 2026-01-13  
**Task:** Add Canonical Time Rule for all agents  
**Status:** ✅ COMPLETE

---

## Rule Added

**Canonical Time Rule for All Agents (MANDATORY):**

"All timestamps, dates, and 'when' values must be expressed in UTC. Agents must never infer the current date or time from file metadata, OS time, commit history, or content. If the current UTC date is required, the agent must request it explicitly unless it has already been provided in the current session."

---

## Changes Made

### 1. Added to DIALOG_DOCTRINE.md

**Location:** Section 4.4 (new subsection under "Newest-at-Top Rule")

**Content Added:**
- Complete Canonical Time Rule
- List of prohibited time sources (file metadata, OS time, commit history, etc.)
- Required behavior: request time explicitly from user
- Exception: may use time if already provided in current session
- Rationale for the rule
- Correct and incorrect behavior examples
- List of what the rule applies to

**Key Points:**
- Agents must never infer time from any implicit source
- Must request current UTC time explicitly from user
- Prevents timestamp drift and timezone confusion
- Ensures consistency in multi-agent workflows
- Maintains accurate historical records

---

### 2. Added to TIMESTAMP_DOCTRINE.md

**Location:** New Section 6 (inserted before "Doctrine Enforcement")

**Content Added:**
- Complete Canonical Time Rule
- Expanded list of prohibited time sources
- Required agent behavior
- Exception for session-provided time
- Comprehensive rationale
- Correct and incorrect behavior examples
- Complete list of applications
- Enforcement requirements

**Section Renumbering:**
- Old Section 6 → New Section 7 (Doctrine Enforcement)
- Old Section 7 → New Section 8 (Digital Archaeology)
- Old Section 8 → New Section 9 (Summary)
- Old Section 9 → New Section 10 (Code Examples)

**Key Points:**
- Applies to all BIGINT(14) YYYYMMDDHHMMSS values
- All AI agents must follow (Kiro, Claude, Cascade, Junie, Terminal AI)
- Human author (Captain_wolfie) provides authoritative time
- No exceptions for convenience or automation
- Violations are doctrine violations

---

## Files Modified

1. ✅ `docs/doctrine/DIALOG_DOCTRINE.md`
   - Added Section 4.4: Canonical Time Rule
   - Integrated with timestamp requirements section

2. ✅ `docs/doctrine/TIMESTAMP_DOCTRINE.md`
   - Added Section 6: Canonical Time Rule for All Agents
   - Renumbered subsequent sections (6→7, 7→8, 8→9, 9→10)

---

## Prohibited Time Sources

Agents must NEVER infer time from:
- ❌ File metadata
- ❌ OS time
- ❌ Commit history
- ❌ File content
- ❌ System clocks
- ❌ Local timezone information
- ❌ File modification timestamps
- ❌ Git commit timestamps
- ❌ Build timestamps
- ❌ Any other implicit time source

---

## Required Agent Behavior

**When current UTC time is needed:**
1. ✅ Request it explicitly from the user
2. ✅ Wait for explicit confirmation
3. ✅ Never assume or infer the current time
4. ✅ Never use system time without explicit permission

**Exception:**
- ✅ If current UTC time has been provided in the current session, agent may use that value for subsequent operations

---

## Correct vs Incorrect Behavior

### ✅ Correct Behavior
```
Agent: "I need to create a timestamp. What is the current UTC date and time?"
User: "2026-01-13 18:30:00 UTC"
Agent: [Creates timestamp: 20260113183000]
```

### ❌ Incorrect Behavior
```
Agent: [Checks system time: 2026-01-13 10:30:00 PST]
Agent: [Converts to UTC: 2026-01-13 18:30:00 UTC]
Agent: [Creates timestamp: 20260113183000] ❌ WRONG
```

---

## Applications

This rule applies to:
- ✅ Dialog entry timestamps
- ✅ WOLFIE header dates
- ✅ File modification dates
- ✅ Version timestamps
- ✅ Database timestamps
- ✅ Log timestamps
- ✅ Any temporal reference in documentation or code
- ✅ All BIGINT(14) YYYYMMDDHHMMSS values

---

## Rationale

**Why this rule is critical:**

1. **Prevents timestamp drift** - Different systems may have different times
2. **Ensures consistency** - All agents use the same authoritative time
3. **Avoids timezone confusion** - No ambiguity about which timezone was used
4. **Maintains accurate records** - Historical accuracy is preserved
5. **Prevents stale information** - Agents don't use outdated system time
6. **Ensures reproducibility** - Same results across different environments
7. **Maintains temporal accuracy** - Critical for distributed systems

---

## Enforcement

**All AI agents must follow this rule:**
- Kiro (documentation authority)
- Claude (via Cursor - implementation)
- Cascade (via Windsurf - legacy integration)
- Junie (via JetBrains - release management)
- Terminal AI (command-line interface)

**Human authority:**
- Captain_wolfie provides authoritative time when needed

**No exceptions:**
- No "convenience" shortcuts
- No "automation" bypasses
- Violations are doctrine violations and must be corrected

---

## Integration with Existing Doctrine

This rule integrates with:
- **TIMESTAMP_DOCTRINE.md** - Canonical timestamp format and storage rules
- **DIALOG_DOCTRINE.md** - Dialog entry timestamp requirements
- **WOLFIE_HEADER_SPECIFICATION.md** - Header timestamp requirements
- **Multi-IDE Workflow** - Multi-agent coordination and consistency

---

## Benefits

1. **Temporal Consistency** - All agents use the same time source
2. **Historical Accuracy** - Timestamps reflect actual time of changes
3. **Timezone Safety** - No confusion about local vs UTC time
4. **Multi-Agent Coordination** - All agents synchronized on time
5. **Reproducibility** - Same results regardless of system time
6. **Audit Trail Integrity** - Accurate historical records
7. **Future-Proofing** - Time handling remains consistent over decades

---

## Summary

Successfully added Canonical Time Rule to documentation:
- ✅ Added to DIALOG_DOCTRINE.md (Section 4.4)
- ✅ Added to TIMESTAMP_DOCTRINE.md (Section 6)
- ✅ Renumbered subsequent sections in TIMESTAMP_DOCTRINE.md
- ✅ Comprehensive examples and rationale provided
- ✅ Clear enforcement requirements established
- ✅ Integrated with existing timestamp doctrine

**Key Principle:** Agents must request current UTC time explicitly and never infer from any implicit source.

---

**End of Summary**
