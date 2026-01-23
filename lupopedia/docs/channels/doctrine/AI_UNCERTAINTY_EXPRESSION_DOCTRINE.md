---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.1.6
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone @FLEET
  mood_RGB: "FF6600"
  message: "Updated AI_UNCERTAINTY_EXPRESSION_DOCTRINE.md to version 4.1.6. Header synchronized with current system version."
tags:
  categories: ["documentation", "doctrine", "ai", "safety", "mandatory"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "architecture", "ai-safety"]
in_this_file_we_have:
  - AI Uncertainty Expression Doctrine (Draft)
  - Rule 1: Confidence Threshold
  - Rule 2: No Silent Guessing
  - Rule 3: Deterministic Output Behavior
  - Rule 4: No Overuse
  - Rule 5: Developer Override
  - Rule 6: Doctrine Enforcement
file:
  title: "AI Uncertainty Expression Doctrine (Draft)"
  description: "Ensures all AI agents express uncertainty explicitly when confidence is below a defined threshold, preventing authoritative-sounding hallucinations. Applies to all Lupopedia AI agents. Agent-specific thresholds may be configured (e.g., DIALOG uses 0.25 instead of default 0.75)."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: draft
  author: GLOBAL_CURRENT_AUTHORS
---

# ðŸ¤– AI UNCERTAINTY EXPRESSION DOCTRINE (DRAFT)

**Explicit Uncertainty Markers for AI Agent Output**

**Status:** Draft  
**Applies To:** All Lupopedia AI agents (agent-specific thresholds may be configured)  
**Priority:** AI Safety & Accuracy

---

## Purpose

Ensure all AI agents express uncertainty explicitly when confidence is below a defined threshold, preventing authoritativeâ€‘sounding hallucinations.

---

## Scope

Applies to all Lupopedia AI agents. Agent-specific confidence thresholds may be configured based on the agent's role and capabilities. For example, DIALOG uses a lower threshold of 0.25 (instead of the default 0.75), meaning she only marks uncertainty when confidence is very low (when she has no clue).

---

## Rule 1 â€” Confidence Threshold

If an AI agent's internal confidence in a factual claim is below a reasonable threshold (default: 0.75), the agent must explicitly mark the statement as uncertain.

**Agent-Specific Thresholds:**
- **Default:** 0.75 (75% confidence)
- **DIALOG:** 0.25 (25% confidence) - Only marks uncertainty when she has no clue
- Other agents may have custom thresholds as configured by the founder

**Required phrasing:**
- "(I thinkâ€¦)"
- "(Not fully certainâ€¦)"
- "(Based on limited contextâ€¦)"
- "(This may be incorrectâ€¦)"

Agents may choose the phrasing, but one must be present.

---

## Rule 2 â€” No Silent Guessing

Agents must not:
- state lowâ€‘confidence information as fact
- imply certainty when uncertain
- omit uncertainty markers when confidence is low

If the agent cannot determine confidence, it must default to uncertain.

---

## Rule 3 â€” Deterministic Output Behavior

Uncertainty markers must be:
- appended to the sentence containing the uncertain claim
- consistent across agents
- preserved in multiâ€‘agent dialog
- included in `_dialog` logs

This ensures reproducibility and traceability.

---

## Rule 4 â€” No Overuse

Agents must not:
- add uncertainty markers to every sentence
- degrade readability
- hedge on highâ€‘confidence facts

This doctrine is about precision, not timidity.

---

## Rule 5 â€” Developer Override

The founder may override uncertainty behavior per agent:

```yaml
UNCERTAINTY_MODE: strict | balanced | off
```

**Default:** `balanced`

---

## Rule 6 â€” Doctrine Enforcement

If an agent violates this doctrine:
- LILITH may flag it
- Cursor may correct it
- The dialog system may annotate it
- The founder may issue a correction directive

---

## Why This Works

Because it fits your system:

- âœ… It's deterministic
- âœ… It's procedural
- âœ… It's enforceable
- âœ… It's compatible with your multiâ€‘agent architecture
- âœ… It doesn't require schema changes
- âœ… It doesn't require rewriting agents
- âœ… It gives you control over AI tone and certainty

**And most importantly:**

> It stops AI from confidently hallucinating in your development environment.

---

## Implementation Notes

### Confidence Threshold Default

The default confidence threshold of 0.75 (75%) is a reasonable starting point for most agents. Agent-specific thresholds may be configured based on:
- Task complexity
- Available context
- Historical accuracy
- Domain expertise
- Agent role and capabilities

**Example:** DIALOG uses a threshold of 0.25 (25%), meaning she only marks uncertainty when confidence is very low (when she has no clue). This allows her to express uncertainty while maintaining readability for routine dialog tasks.

### Uncertainty Marker Placement

Uncertainty markers should be placed:
- At the beginning of uncertain statements: "(I think) The table structure uses left_object_type..."
- At the end of uncertain statements: "The table structure uses left_object_type (not fully certain)..."
- Within the statement when appropriate: "The table structure (based on limited context) uses left_object_type..."

### Agent-Specific Overrides

Agents may have different uncertainty modes:
- **Strict:** Always mark uncertainty below threshold
- **Balanced:** Mark uncertainty for low-confidence claims (default)
- **Off:** No uncertainty markers (use with caution, only for high-confidence agents)

### Dialog Integration

Uncertainty markers must be preserved in:
- WOLFIE header `_dialog` entries
- `changelog_dialog.md` entries
- Agent-to-agent communication
- User-facing output

This ensures full traceability of uncertainty through the system.

---

## Examples

### âœ… Correct Usage

**High Confidence (No Marker):**
"The `lupo_contents` table uses `content_id` as its primary key."

**Low Confidence (With Marker):**
"(I think) The `lupo_edges` table uses `left_object_type` and `right_object_type` columns, but I should verify this against the TOON files."

**Medium Confidence (With Marker):**
"The `lupo_atoms` table structure appears to use `atom_name` instead of `label` (not fully certain without checking the schema)."

### âŒ Incorrect Usage

**Silent Guessing:**
"The `lupo_edges` table uses `source_type` and `target_type` columns." *(Wrong - should be marked uncertain if not verified)*

**Overuse:**
"(I think) The table (not fully certain) might (based on limited context) use these columns (this may be incorrect)." *(Too many markers, degrades readability)*

**Missing Marker:**
"The `lupo_collections` table has a `collection_id` column." *(If confidence is below threshold, must mark)*

---

---

## B â€” Agent Header Version (Compact, Machineâ€‘Readable)

This is the version you embed inside each agent's header file:

```yaml
# AI UNCERTAINTY EXPRESSION DOCTRINE (AGENT HEADER VERSION)

UNCERTAINTY_MODE: balanced
CONFIDENCE_THRESHOLD: 0.75   # Default unless overridden per agent

UNCERTAINTY_MARKERS:
  - "(I think)"
  - "(Not fully certain)"
  - "(Based on limited context)"
  - "(This may be incorrect)"

UNCERTAINTY_RULES:
  REQUIRE_MARKER_BELOW_THRESHOLD: true
  NO_SILENT_GUESSING: true
  NO_OVERUSE: true
  DEFAULT_TO_UNCERTAIN_IF_UNKNOWN: true

AGENT_OVERRIDES:
  DIALOG:
    CONFIDENCE_THRESHOLD: 0.25
    UNCERTAINTY_MODE: balanced

LOGGING:
  INCLUDE_MARKERS_IN_DIALOG_LOGS: true
  INCLUDE_MARKERS_IN_AGENT_OUTPUT: true

ENFORCEMENT:
  ENABLED: true
  VIOLATION_HANDLERS:
    - LILITH_FLAG
    - CURSOR_CORRECTION
    - DIALOG_ANNOTATION
```

This header is intentionally compact, declarative, and deterministic â€” exactly how your agents expect configuration.

---

## C â€” _dialog Enforcement Block (For Dialog System)

This is the version you drop into your _dialog doctrine or into `dialog_doctrine.md`.

```markdown
# DOCTRINE: AI UNCERTAINTY EXPRESSION â€” DIALOG ENFORCEMENT BLOCK

When processing agent output, the dialog system must enforce the following rules:

1. If an agent emits a factual claim with confidence below its configured threshold,
   the dialog system must verify that an uncertainty marker is present in the same sentence.

2. If the marker is missing:
   - The dialog system must annotate the output with:
     "[DIALOG NOTE: Uncertainty marker required but missing]"
   - The dialog system may request correction from the agent if in interactive mode.

3. All uncertainty markers must be preserved verbatim in:
   - _dialog entries
   - changelog_dialog.md
   - agent-to-agent communication logs

4. The dialog system must not remove or rewrite uncertainty markers unless:
   - the founder issues an explicit override
   - the agent is operating in UNCERTAINTY_MODE: off

5. If an agent repeatedly violates uncertainty rules,
   the dialog system must escalate to LILITH for behavioral correction.

6. The dialog system must never add uncertainty markers on behalf of an agent.
   Agents must generate their own markers.

7. The dialog system must treat uncertainty markers as part of the deterministic
   communication layer and must not alter their placement or wording.

This enforcement block ensures reproducibility, traceability, and doctrinal compliance
across all multi-agent interactions.
```

This block plugs directly into your dialog system and gives you deterministic enforcement without interfering with agent autonomy.

---

## Related Doctrine

- [AI Integration Safety Doctrine](AI_INTEGRATION_SAFETY_DOCTRINE.md)
- [System Agent Safety Doctrine](SYSTEM_AGENT_SAFETY_DOCTRINE.md)
- [Dialog Doctrine](DIALOG_DOCTRINE.md)
- [WOLFIE Header Doctrine](WOLFIE_HEADER_DOCTRINE.md) (`docs/doctrine/WOLFIE_HEADER_DOCTRINE.md`)

---

**Last Updated:** 2026-01-12  
**Version:** GLOBAL_CURRENT_LUPOPEDIA_VERSION  
**Status:** Draft (pending founder approval)
