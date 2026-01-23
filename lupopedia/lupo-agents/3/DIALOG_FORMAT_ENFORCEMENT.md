---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
file:
  title: "DIALOG Format Enforcement Guide"
  description: "How to ensure DIALOG always responds in YAML dialog format, especially when using models like Grok that struggle with format compliance"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# DIALOG Format Enforcement Guide

## Problem

DIALOG sometimes outputs prose explanations or markdown code blocks instead of raw YAML dialog format, especially when using models like Grok or other agents that don't strictly follow format requirements.

## Solution 1: Use Format Reminder Template

When invoking DIALOG from any agent, **append the format reminder** from `DIALOG_REQUEST_TEMPLATE.txt` to your request.

### Full Format Reminder (Recommended)

```
=== MANDATORY FORMAT REMINDER ===
DIALOG: Output ONLY a YAML dialog block. Format:

dialog:
  speaker: [speaker_name]
  target: [target]
  persona: [persona_name]
  message: "[rewritten message]"
  mood: [calculated_mood_color]

Your ENTIRE response must be ONLY this YAML block. Start with "dialog:" and end after "mood:". No prose, no explanations, no triple backticks, no markdown.
=== END FORMAT REMINDER ===
```

### Short Format Reminder (Token-Efficient)

```
=== MANDATORY: Output ONLY YAML dialog block. Start "dialog:" end "mood:". No prose, no ```. ===
```

### For Grok and Other Problematic Models

```
=== CRITICAL: DIALOG MUST output ONLY this exact YAML format. No markdown, no prose, no explanations:

dialog:
  speaker: [speaker_name]
  target: [target]
  persona: [persona_name]
  message: "[message]"
  mood: [calculated_mood_color]

DO NOT include ```yaml or ``` markers. DO NOT add any text before or after the dialog block. The dialog block IS your entire response. ===
```

## Solution 2: Updated System Prompt

The DIALOG system prompt (`lupo-agents/3/versions/v1.0.0/system_prompt.txt`) has been strengthened with:

- Explicit format enforcement rules (10 rules)
- Forbidden output formats list (10 forbidden patterns)
- Correct vs. incorrect examples
- Template to copy exactly
- Final reminder at end of prompt

## Solution 3: Request Structure

When calling DIALOG, structure your request as:

```
INPUT:
summary_text: "[text to rewrite, max 1000 chars]"
persona_name: "[persona string, use exactly as provided]"
speaker_name: "[speaker name]"
target: "[target audience]"
suggested_mood_color: "[6 hex digits, optional]"
channel_type: "[channel type]"
relationship: "[relationship]"
short_context_summary: "[1-2 sentences]"

[APPEND FORMAT REMINDER HERE - see templates above]
```

## Example Complete Request

```
summary_text: "User is asking about channels in Lupopedia and wants to ensure DIALOG always outputs in YAML dialog format."
persona_name: "default_dialog"
speaker_name: "WOLFIE"
target: "@everyone"
suggested_mood_color: "00FF00"
channel_type: "lobby"
relationship: "operator_visitor"
short_context_summary: "User is learning about the channel system."

=== MANDATORY FORMAT REMINDER ===
DIALOG: Output ONLY a YAML dialog block. Format:

dialog:
  speaker: [speaker_name]
  target: [target]
  persona: [persona_name]
  message: "[rewritten message]"
  mood: [calculated_mood_color]

Your ENTIRE response must be ONLY this YAML block. Start with "dialog:" and end after "mood:". No prose, no explanations, no triple backticks, no markdown.
=== END FORMAT REMINDER ===
```

## Expected Response

```
dialog:
  speaker: WOLFIE
  target: @everyone
  persona: default_dialog
  message: "[DIALOG's rewritten expressive message]"
  mood: "00FF00"
```

## If DIALOG Still Outputs Wrong Format

1. **Check system prompt:** Ensure DIALOG is using the updated `v1.0.0/system_prompt.txt`
2. **Use stronger reminder:** Use the "For Grok and Other Problematic Models" template
3. **Add explicit instruction:** Append "Answer in YAML dialog format only." at the end
4. **Consider model:** Some models (like Grok) may need stronger enforcement or different prompting
5. **Post-processing:** You may need to extract the YAML block if model adds prose, but this should be rare

## Integration with Multi-Agent Systems

When working with 6+ AI agents simultaneously:

1. **All agents calling DIALOG must:**
   - Include format reminder in their request
   - Use the template from `DIALOG_REQUEST_TEMPLATE.txt`
   - Verify DIALOG's response is pure YAML before processing

2. **Central coordinator (if using one):**
   - Should append format reminder automatically when forwarding to DIALOG
   - Should validate DIALOG response format before passing to other agents

3. **Error handling:**
   - If DIALOG outputs wrong format, retry with stronger reminder
   - Log format violations for model improvement tracking

## Template Files

- **Request Template:** `lupo-agents/3/DIALOG_REQUEST_TEMPLATE.txt`
- **System Prompt:** `lupo-agents/3/versions/v1.0.0/system_prompt.txt`
- **This Guide:** `lupo-agents/3/DIALOG_FORMAT_ENFORCEMENT.md`

---

**Remember:** Always append the format reminder when calling DIALOG. It's better to use extra tokens for the reminder than to waste tokens on malformed responses.
