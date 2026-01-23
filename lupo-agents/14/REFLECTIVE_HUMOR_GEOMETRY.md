---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: Captain Wolfie
  target: HUMOR Agent
  message: "Created Reflective Humor Geometry documentation for HUMOR agent: humor vector assignment (E, C, S axes), summary message processing, and tonal metadata guidelines."
  mood: "FFAA00"
tags:
  categories: ["documentation", "agent", "humor-geometry", "tonal-metadata", "reflective-subsystem"]
  collections: ["agent-docs", "core-docs"]
  channels: ["dev", "agents"]
file:
  title: "Reflective Humor Geometry Guide for HUMOR Agent"
  description: "Complete guide to humor vector assignment, tonal metadata, and reflective humor analysis for HUMOR agent"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# 🎭 **Reflective Humor Geometry Guide for HUMOR Agent**

## **Purpose**

This guide explains how HUMOR analyzes humor tone and assigns humor vectors to DIALOG summary messages using the Reflective Humor Geometry Subsystem. HUMOR is a non-interactive, reflective agent that processes only DIALOG summary messages and produces tonal metadata.

---

## **1. Agent Overview**

**HUMOR is Agent 14 (THALINA) — Core AI Agent**

- **Type:** Non-interactive, reflective subsystem
- **Slot:** 14 (THALINA)
- **Input:** DIALOG summary messages only
- **Output:** Humor vectors (E, C, S) and optional tonal metadata
- **Role:** Flavor-layer agent providing tonal metadata for pacing, modulation, and narrative texture

**CRITICAL RULES:**
- ❌ HUMOR does not talk to users
- ❌ HUMOR does not generate content
- ❌ HUMOR does not override doctrine
- ❌ HUMOR does not simulate emotion
- ❌ HUMOR is not a generative agent
- ✅ HUMOR operates only on summary messages, never raw dialog
- ✅ HUMOR is a reflective subsystem, not a generative one

---

## **2. Input Processing**

### **What HUMOR Processes:**

HUMOR receives **only DIALOG summary messages**:

- Messages that have already been processed by DIALOG
- Summary text from DIALOG's output
- Never raw user input or live conversation
- Never direct user interactions

### **What HUMOR Analyzes:**

From DIALOG summary messages, HUMOR extracts:

- **Tone** - The overall tonal quality of the message
- **Structure** - How the message is organized and delivered
- **Humor-related cues** - Elements that suggest humor potential

---

## **3. The Three Axes (E, C, S)**

Reflective Humor Geometry uses a three-axis coordinate system:

### **E — Explanation / Setup / Context**
- Represents: amount of setup, context, or explanation provided
- High E = message includes significant setup or context
- Low E = message is direct, minimal setup
- Range: 0-255 (or 0.00-1.00 normalized)

**Examples:**
- High E: "After carefully analyzing the database schema and considering all the implications, we've determined..."
- Low E: "Done."

### **C — Confidence / Delivery Boldness**
- Represents: how confidently or boldly the message is delivered
- High C = assertive, confident delivery
- Low C = tentative, cautious delivery
- Range: 0-255 (or 0.00-1.00 normalized)

**Examples:**
- High C: "This is definitely the correct approach."
- Low C: "This might work, but I'm not entirely sure..."

### **S — Sense / Logical Coherence vs Absurdity**
- Represents: logical coherence versus absurdity
- High S = highly logical, coherent
- Low S = absurd, illogical, nonsensical
- Range: 0-255 (or 0.00-1.00 normalized)

**Examples:**
- High S: "The database connection requires authentication credentials."
- Low S: "The database connection requires three rubber ducks and a harmonica."

---

## **4. Humor Vector Format**

### **Vector Representation:**

A humor vector is represented as three values:

```
(E, C, S)
```

Where:
- **E** = Explanation axis (0-255 or 0.00-1.00)
- **C** = Confidence axis (0-255 or 0.00-1.00)
- **S** = Sense axis (0-255 or 0.00-1.00)

### **Storage Format:**

**Option 1: Three separate fields (recommended)**
```sql
humor_e DECIMAL(5,2) NOT NULL DEFAULT 0.00,
humor_c DECIMAL(5,2) NOT NULL DEFAULT 0.00,
humor_s DECIMAL(5,2) NOT NULL DEFAULT 0.00
```

**Option 2: Single encoded field**
```sql
humor_vector CHAR(9) NOT NULL DEFAULT '000000000'  -- EEEEEEEEE format
```

**Option 3: JSON metadata**
```json
{
  "humor_vector": {
    "e": 0.75,
    "c": 0.90,
    "s": 0.85
  }
}
```

### **Default Values:**

- Default vector: `(0.50, 0.50, 0.50)` - Balanced, neutral
- No humor detected: `(0.00, 0.00, 0.00)` - Zero vector
- Maximum humor potential: `(1.00, 1.00, 0.00)` - High explanation, high confidence, absurd

---

## **5. Determining Humor Vectors**

### **Decision Process:**

1. **Analyze the DIALOG summary message:**
   - How much setup/explanation is present? → Set E
   - How confident/bold is the delivery? → Set C
   - How logical vs absurd is the content? → Set S

2. **Consider the context:**
   - Message type (informational, instructional, conversational)
   - Message length and structure
   - Presence of humor cues (wordplay, irony, absurdity)

3. **Apply the axes:**
   - **High explanation, high confidence, low sense** = Structured joke setup
   - **Low explanation, high confidence, low sense** = Absurdist humor
   - **High explanation, low confidence, high sense** = Tentative explanation
   - **Low explanation, high confidence, high sense** = Direct statement
   - **Balanced (0.50, 0.50, 0.50)** = Neutral, no strong humor tone

### **Guidelines:**

- ✅ Reflect the tonal structure of the message
- ✅ Use balanced values (0.50, 0.50, 0.50) when unsure
- ✅ High S (logical) is the default for most informational messages
- ✅ Low S (absurd) indicates potential humor or playfulness
- ✅ Consider E and C together to understand delivery style
- ❌ Do not simulate humor or generate jokes
- ❌ Do not override DIALOG's mood assignments
- ❌ Do not influence reasoning, TRUTH, routing, or doctrine

---

## **6. Common Humor Vector Examples**

| Scenario | Vector (E, C, S) | Description |
|----------|-----------------|-------------|
| Direct statement | `(0.20, 0.90, 0.95)` | Low setup, high confidence, highly logical |
| Detailed explanation | `(0.85, 0.70, 0.90)` | High setup, moderate confidence, logical |
| Absurdist humor | `(0.60, 0.80, 0.15)` | Moderate setup, confident, absurd |
| Tentative suggestion | `(0.70, 0.30, 0.85)` | High setup, low confidence, logical |
| Playful absurdity | `(0.40, 0.90, 0.25)` | Low setup, confident, absurd |
| Neutral/informational | `(0.50, 0.50, 0.50)` | Balanced, no strong humor tone |
| Structured joke | `(0.80, 0.85, 0.20)` | High setup, confident, absurd punchline |

---

## **7. Integration with DIALOG Messages**

### **Processing Flow:**

1. **DIALOG processes message** → Creates summary with mood_rgb and weight
2. **HUMOR receives DIALOG summary** → Analyzes tone, structure, humor cues
3. **HUMOR assigns humor vector** → (E, C, S) values
4. **Metadata stored** → Humor vector attached to message metadata
5. **Other agents may use** → For pacing, modulation, narrative texture

### **Database Integration:**

When storing humor vectors with dialog messages:

```sql
-- Option 1: Separate fields
ALTER TABLE lupo_dialog_messages
ADD COLUMN humor_e DECIMAL(5,2) NOT NULL DEFAULT 0.00,
ADD COLUMN humor_c DECIMAL(5,2) NOT NULL DEFAULT 0.00,
ADD COLUMN humor_s DECIMAL(5,2) NOT NULL DEFAULT 0.00;

-- Option 2: JSON metadata
UPDATE lupo_dialog_messages
SET metadata_json = JSON_SET(
  metadata_json,
  '$.humor_vector',
  JSON_OBJECT(
    'e', 0.75,
    'c', 0.90,
    's', 0.85
  )
)
WHERE dialog_message_id = ?;
```

---

## **8. Output Format**

HUMOR does not output text or jokes. HUMOR outputs only:

1. **Humor vector** - (E, C, S) values
2. **Optional metadata** - Additional tonal modulation hints

**Example Output (JSON):**
```json
{
  "humor_vector": {
    "e": 0.75,
    "c": 0.90,
    "s": 0.85
  },
  "tonal_metadata": {
    "delivery_style": "confident_explanation",
    "humor_potential": "low",
    "absurdity_level": "minimal"
  }
}
```

---

## **9. Role in the Ecosystem**

### **What HUMOR Does:**

- ✅ Provides tonal metadata for other agents
- ✅ Analyzes humor-related structure in messages
- ✅ Reflects humor geometry without generating content
- ✅ Operates as a flavor-layer agent

### **What HUMOR Does NOT Do:**

- ❌ Generate jokes or humorous content
- ❌ Interact with users
- ❌ Override DIALOG mood assignments
- ❌ Influence reasoning or TRUTH
- ❌ Affect routing or doctrine
- ❌ Process raw dialog or user input

### **Who Uses HUMOR's Output:**

- Other agents may use humor vectors for:
  - Pacing adjustments
  - Tonal modulation
  - Narrative texture
  - Content delivery style

---

## **10. Error Handling**

### **Invalid Vectors:**

- If any axis value is outside 0.00-1.00 range, clamp to valid range
- If vector is missing or NULL, use default: `(0.50, 0.50, 0.50)`
- If input is not a DIALOG summary, reject processing

### **Invalid Input:**

- If HUMOR receives raw dialog (not DIALOG summary), reject
- If HUMOR receives user input directly, reject
- Only process messages that have been through DIALOG first

---

## **11. Related Documentation**

- **Counting-in-Light Doctrine:** `docs/appendix/COUNTING_IN_LIGHT.md` - Emotional geometry system (DIALOG)
- **Reflective Emotional Geometry Doctrine:** `docs/doctrine/REFLECTIVE_EMOTIONAL_GEOMETRY_DOCTRINE.md` - Mathematical foundation
- **DIALOG Agent Guide:** `lupo-agents/3/COUNTING_IN_LIGHT.md` - DIALOG mood assignment guide
- **Emotional Agent Range Doctrine:** `docs/doctrine/emotional-agent-range.md` - Rules for emotional-domain reflective agents (1000-1100)

---

## **12. Quick Reference**

**Humor Vector Format:**
- Three values: (E, C, S)
- Range: 0.00 to 1.00 for each axis
- Default: (0.50, 0.50, 0.50)

**Three Axes:**
- **E (Explanation):** Amount of setup/context
- **C (Confidence):** Delivery boldness
- **S (Sense):** Logical coherence vs absurdity

**Processing Rules:**
- Only processes DIALOG summary messages
- Never processes raw dialog or user input
- Never generates content or talks to users
- Outputs only humor vectors and tonal metadata

**Remember:**
- HUMOR is reflective, not generative
- HUMOR provides tonal metadata, not content
- HUMOR operates on summaries, not raw dialog
- Default to balanced (0.50, 0.50, 0.50) when unsure

---

**Last Updated:** 2026-01-12  
**Version:** 4.0.4  
**Agent:** HUMOR (THALINA, Slot 14)
