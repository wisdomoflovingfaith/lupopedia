---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: Captain Wolfie
  target: DIALOG Agent
  message: "Created Counting-in-Light documentation for DIALOG agent: message-level mood assignment, weight field usage, and RGB color calculation guidelines."
  mood: "00A0FF"
tags:
  categories: ["documentation", "agent", "counting-in-light", "mood", "emotional-metadata"]
  collections: ["agent-docs", "core-docs"]
  channels: ["dev", "agents"]
file:
  title: "Counting-in-Light Guide for DIALOG Agent"
  description: "Complete guide to mood color assignment, weight field usage, and emotional metadata for DIALOG agent messages"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# 🐺 **Counting-in-Light Guide for DIALOG Agent**

## **Purpose**

This guide explains how DIALOG assigns mood colors and weights to messages using the Counting-in-Light emotional coordinate system. DIALOG is responsible for assigning mood metadata to every message it processes.

---

## **1. Message-Level Mood Assignment**

**CRITICAL: Dialogs are by individual messages, not thread-level.**

Each message in `lupo_dialog_messages` has its own mood assignment:

- `mood_rgb` - The emotional color vector for this specific message (char(6), e.g., "FF0000")
- `weight` - Optional weight multiplier for this message in thread aggregation (decimal(3,2), range 0.00 to 1.00)

**DIALOG must assign both fields for every message it processes.**

---

## **2. The Three Axes (R, G, B)**

Counting-in-Light uses a three-axis RGB coordinate system:

### **R — Strife / Chaos / Conflict Intensity**
- Represents: urgency, danger, agitation, emotional volatility, conflict pressure
- High R = "this needs attention now"
- Examples: `FF0000` (critical), `FF8800` (soft warning), `CC0000` (moderate urgency)

### **G — Harmony / Attachment / Cohesion Intensity**
- Represents: empathy, unity, reassurance, stabilizing influence, emotional connection
- High G = "this is supportive or calming"
- Examples: `00FF00` (reassuring), `00CC88` (calm/supportive), `88FF88` (gentle harmony)

### **B — Memory Depth / Persistence Weight**
- Represents: reflection, long-term significance, emotional resonance, depth of meaning, historical weight
- High B = "this matters beyond the moment"
- Examples: `0000FF` (insightful/reflective), `0088FF` (moderate depth), `8888FF` (gentle reflection)

### **Neutral / Balanced**
- `666666` - Mid-range across all axes (default when unsure)
- `888888` - Balanced, slightly reflective

---

## **3. Mood Color Format**

**Storage Format:**
- Six hex digits, **NO leading `#`**
- Example: `"FF0000"` (not `"#FF0000"`)
- Database field: `mood_rgb char(6)`
- Default: `"666666"` (neutral)

**In DIALOG Output:**
```yaml
dialog:
  speaker: WOLFIE
  target: @everyone
  persona: default_dialog
  message: "[rewritten message]"
  mood: "FF0000"  # Six hex digits, no #
```

---

## **4. Choosing a Mood Color**

### **Decision Process:**

1. **Analyze the message content:**
   - Is it urgent? → Increase R
   - Is it supportive? → Increase G
   - Is it significant long-term? → Increase B

2. **Consider the context:**
   - Channel type (lobby, support, dev)
   - Relationship (operator_visitor, agent_agent, etc.)
   - Message type (text, command, system, error)

3. **Apply the axes:**
   - **Critical / Urgent:** `FF0000` (high red, low everything else)
   - **Reassuring / Stabilizing:** `00FF00` (high green)
   - **Insightful / Reflective:** `0000FF` (high blue)
   - **Neutral / Balanced:** `666666` (mid-range all axes)
   - **Soft Warning:** `FF8800` (moderate red, some green)
   - **Calm / Supportive:** `00CC88` (green with touch of blue)

### **Guidelines:**

- ✅ Reflect the emotional tone of the message
- ✅ Avoid extremes unless necessary
- ✅ Use neutral (`666666`) when unsure
- ✅ Use red sparingly (only for real urgency)
- ✅ Use blue for deep insights or long-term significance
- ✅ Use green for reassurance, stability, or positive alignment
- ❌ Do not overuse high-intensity colors
- ❌ Do not use pure black (`000000`) or pure white (`FFFFFF`) unless specifically required

---

## **5. Message Weight Field**

The `weight` field allows individual messages to be weighted differently when calculating thread-level mood.

### **Weight Specifications:**

- **Range:** 0.00 to 1.00 (decimal(3,2))
- **Default:** 1.00 (normal weight)
- **Purpose:** Allows important messages to have more influence in thread mood aggregation
- **Database field:** `weight decimal(3,2) NOT NULL DEFAULT 0.00`

### **When to Set Weight:**

**Weight = 1.00 (Default - Full Weight):**
- Normal messages
- Important messages that should carry full emotional weight
- Standard conversational messages

**Weight = 0.75 - 0.99 (High Weight):**
- Significant messages that should influence thread mood
- Key decision points
- Important announcements

**Weight = 0.50 - 0.74 (Moderate Weight):**
- Less significant messages
- Contextual information
- Follow-up clarifications

**Weight = 0.25 - 0.49 (Low Weight):**
- Background/context messages
- Minor updates
- Routine acknowledgments

**Weight = 0.01 - 0.24 (Minimal Weight):**
- Very minor messages
- System notifications
- Background noise

**Weight = 0.00 (No Weight):**
- Messages that should not influence thread mood at all
- Use sparingly, only for truly irrelevant messages

### **Weight Assignment Guidelines:**

1. **Default to 1.00** unless there's a specific reason to reduce weight
2. **Increase weight** for messages that are emotionally significant
3. **Decrease weight** for routine, contextual, or background messages
4. **Consider message importance** relative to the conversation thread
5. **Use 0.00** only for messages that should be completely excluded from mood aggregation

---

## **6. DIALOG Output Format with Mood**

When DIALOG processes a message, it must output:

```yaml
dialog:
  speaker: [speaker_name]
  target: [target]
  persona: [persona_name]
  message: "[rewritten message]"
  mood: "[RRGGBB]"  # Six hex digits, no #
```

**Example:**
```yaml
dialog:
  speaker: WOLFIE
  target: @everyone
  persona: default_dialog
  message: "The system is now processing your request. This may take a moment."
  mood: "00FF00"  # Reassuring green
```

---

## **7. Database Storage**

When saving to `lupo_dialog_messages`:

```sql
INSERT INTO lupo_dialog_messages (
    dialog_thread_id,
    channel_id,
    from_actor_id,
    to_actor_id,
    message_text,
    message_type,
    mood_rgb,      -- char(6), e.g., "FF0000"
    weight,         -- decimal(3,2), e.g., 1.00
    created_ymdhis,
    updated_ymdhis
) VALUES (
    1,
    1,
    1,
    1,
    "Message text here",
    "text",
    "FF0000",       -- Six hex digits, no #
    1.00,           -- Default weight
    20260112000000,
    20260112000000
);
```

---

## **8. Thread-Level Mood Aggregation**

**Note:** DIALOG assigns mood at the message level. Thread-level mood is calculated separately by the system using:

1. **Message-level mood vectors** - Each message's `mood_rgb`
2. **Message weights** - Each message's `weight` (0.00 to 1.00)
3. **Temporal decay** - Older messages fade over time
4. **Weighted aggregation** - Combines all factors into thread-level mood

DIALOG does not calculate thread mood; it only assigns individual message moods and weights.

---

## **9. Common Mood Color Examples**

| Scenario | Mood RGB | Description |
|----------|----------|-------------|
| Critical error | `FF0000` | High urgency, immediate attention needed |
| Reassuring response | `00FF00` | Supportive, calming, positive |
| Deep insight | `0000FF` | Reflective, significant, long-term |
| Neutral/default | `666666` | Balanced, no strong emotional tone |
| Soft warning | `FF8800` | Moderate urgency, some reassurance |
| Calm support | `00CC88` | Gentle harmony with depth |
| Urgent but hopeful | `FFAA00` | High urgency with some warmth |
| Reflective harmony | `0088FF` | Depth with connection |
| Gentle reminder | `AAAA88` | Soft, balanced, slightly reflective |

---

## **10. Integration with DIALOG Workflow**

### **Step-by-Step Process:**

1. **Receive message request** with context
2. **Analyze message content** for emotional tone
3. **Determine R, G, B values** based on:
   - Urgency (R)
   - Supportiveness (G)
   - Significance (B)
4. **Convert to hex** (e.g., R=255, G=0, B=0 → `FF0000`)
5. **Assign weight** (default 1.00, adjust if needed)
6. **Output YAML dialog** with `mood` field
7. **Save to database** with `mood_rgb` and `weight` fields

---

## **11. Error Handling**

### **Invalid Mood Colors:**

- If mood color is invalid or missing, use default: `666666`
- If hex format is wrong, validate and correct before saving
- Never save `NULL` or empty string for `mood_rgb`

### **Invalid Weights:**

- If weight is outside 0.00-1.00 range, clamp to valid range
- If weight is `NULL`, use default: `1.00`
- Never save `NULL` for `weight` field

---

## **12. Related Documentation**

- **Counting-in-Light Doctrine:** `docs/appendix/COUNTING_IN_LIGHT.md`
- **Reflective Emotional Geometry Doctrine:** `docs/doctrine/REFLECTIVE_EMOTIONAL_GEOMETRY_DOCTRINE.md`
- **MOOD RGB Doctrine:** `docs/doctrine/MOOD_RGB_DOCTRINE.md`
- **DIALOG Format Enforcement:** `lupo-agents/3/DIALOG_FORMAT_ENFORCEMENT.md`

---

## **13. Quick Reference**

**Mood Color Format:**
- Six hex digits, no `#`
- Range: `000000` to `FFFFFF`
- Default: `666666`

**Weight Format:**
- Decimal(3,2)
- Range: 0.00 to 1.00
- Default: 1.00

**Three Axes:**
- **R (Red):** Strife / Intensity
- **G (Green):** Harmony / Warmth
- **B (Blue):** Memory Depth / Reflectiveness

**Remember:**
- Each message gets its own mood and weight
- Mood reflects the emotional tone of that specific message
- Weight determines how much this message influences thread mood
- Default to neutral (`666666`) and full weight (`1.00`) when unsure

---

**Last Updated:** 2026-01-12  
**Version:** 4.0.4
