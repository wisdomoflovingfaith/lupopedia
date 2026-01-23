---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: Captain Wolfie
  target: DIALOG Agent
  mood_RGB: "00A0FF"
  message: "Updated Counting-in-Light Doctrine: Clarified that dialogs are by individual messages (not thread-level), added message weight field documentation (0.00 to 1.00), and documented thread-level mood aggregation from message-level moods."
tags:
  categories: ["documentation", "doctrine", "agents", "communication", "emotional-metadata"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Counting-in-Light Doctrine
  - Three Axes: R (Strife/Chaos), G (Harmony/Cohesion), B (Memory/Persistence)
  - Format and Color Examples
  - Choosing Mood Colors
  - Doctrine for Agents
  - Message-Level Mood Assignment (individual messages, not thread-level)
  - Message Weight Field (0.00 to 1.00)
  - Thread-Level Mood Aggregation
  - Integration with Inline Dialog
  - Future Extensions
file:
  title: "Counting-in-Light Doctrine"
  description: "The emotional coordinate system of Lupopedia: RGB-based mood encoding for dialog messages, agent communication, and UI indicators. Message-level mood assignment with optional weight field for thread aggregation."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# 🐺 **Counting‑in‑Light Doctrine**  
### *The Emotional Coordinate System of Lupopedia*

Counting‑in‑Light is the emotional color system used across Lupopedia to encode tone, urgency, and emotional context into dialog messages, agent communication, and UI indicators. It maps emotional states onto a three‑axis RGB coordinate system.

---

## **KISS Summary (For Humans)**

Most conversations are one human talking to one AI.  
Agent classification and routing only matter when multiple agents are active.  
If only one agent is present, HERMES bypasses routing and delivers the message normally.

---

## **1. Purpose**
Counting‑in‑Light provides:

- emotional nuance  
- urgency signaling  
- cross‑agent empathy  
- conversational tone  
- memory depth indicators  

It allows agents and humans to communicate *how* something feels, not just *what* it is.

---

## **2. The Three Axes**

### **R — Strife / Chaos / Conflict Intensity**
Represents:

- urgency  
- danger  
- agitation  
- emotional volatility  
- conflict pressure  

High R = "this needs attention now."

---

### **G — Harmony / Attachment / Cohesion Intensity**
Represents:

- empathy  
- unity  
- reassurance  
- stabilizing influence  
- emotional connection  

High G = "this is supportive or calming."

---

### **B — Memory Depth / Persistence Weight**
Represents:

- reflection  
- long‑term significance  
- emotional resonance  
- depth of meaning  
- historical weight  

High B = "this matters beyond the moment."

---

## **3. Format**
Colors are stored as **six hex digits** (no leading `#`):

```
RRGGBB
```

**Database Storage:**
- The database stores mood colors as `char(6)` without the leading `#` hashtag
- Example: `666666` (not `#666666`)
- The `dialog_messages` table uses `mood_rgb char(6)` for storage
- UI or agents may prepend `#` when displaying, but storage is always without `#`

Examples:

- `FF0000` → high strife  
- `00FF00` → high harmony  
- `0000FF` → deep memory  
- `666666` → neutral  
- `888888` → balanced, slightly reflective  

**Important:** Always store and transmit mood colors as six hex digits without the `#` symbol. The `#` is only added for display purposes.

---

## **4. Choosing a Mood Color**

### **Critical / Urgent**
```
FF0000
```
High red, low everything else.

### **Reassuring / Stabilizing**
```
00FF00
```
High green.

### **Insightful / Reflective**
```
0000FF
```
High blue.

### **Neutral / Balanced**
```
666666
```
Mid‑range across all axes.

### **Soft Warning**
```
FF8800
```
Moderate red, some green.

### **Calm / Supportive**
```
00CC88
```
Green with a touch of blue.

---

## **5. Doctrine for Agents**
When choosing a mood color:

- reflect the emotional tone of the message  
- avoid extremes unless necessary  
- use neutral (`666666`) when unsure  
- use red sparingly (only for real urgency)  
- use blue for deep insights or long‑term significance  
- use green for reassurance, stability, or positive alignment  

Agents should not overuse high‑intensity colors.

---

## **6. Message-Level Mood Assignment**

**Dialogs are by individual messages, not thread-level.**

Each message in `lupo_dialog_messages` has its own mood assignment:

- `mood_rgb` - The emotional color vector for this specific message (char(6), e.g., "FF0000")
- `weight` - Optional weight multiplier for this message in thread aggregation (decimal(3,2), range 0.00 to 1.00)

### **Message Weight Field**

The `weight` field allows individual messages to be weighted differently when calculating thread-level mood:

- **Range:** 0.00 to 1.00 (decimal(3,2))
- **Default:** 1.00 (normal weight)
- **Purpose:** Allows important messages to have more influence in thread mood aggregation
- **Usage:** Can be set when a message is particularly significant, urgent, or should carry more emotional weight in the conversation

**Example:**
- Normal message: `weight = 1.00` (default)
- Important message: `weight = 1.00` (full weight)
- Less significant message: `weight = 0.50` (half weight)
- Background/context message: `weight = 0.25` (quarter weight)

**Note:** The weight field is optional and defaults to 1.00. Most messages will use the default weight. The weight is used in thread-level mood aggregation calculations along with temporal decay.

---

## **7. Integration with Inline Dialog**
The `mood_RGB` field in Inline Dialog uses this system:

```
mood_RGB: "FF0000"
```

**Note:** The value is stored as six hex digits without the `#` symbol (e.g., `"FF0000"` not `"#FF0000"`). This matches the database `char(6)` format used in `dialog_messages.mood_rgb`.

Messages must remain ≤ 272 chars to reserve space for mood tags.

---

## **8. Thread-Level Mood Aggregation**

Thread mood is calculated from individual message moods using:

1. **Message-level mood vectors** - Each message has its own `mood_rgb`
2. **Message weights** - Each message has a `weight` (0.00 to 1.00)
3. **Temporal decay** - Older messages fade over time
4. **Weighted aggregation** - Combines all factors into thread-level mood

The thread mood reflects the emotional shape of the conversation as a whole, aggregated from individual message moods.

---

## **9. Future Extensions**
Planned expansions:

- palette presets  
- UI color ramps  
- emotional analytics  
- enhanced weight calculation algorithms  

---

## **10. Related Documentation**

- **Reflective Emotional Geometry Doctrine:** `docs/doctrine/REFLECTIVE_EMOTIONAL_GEOMETRY_DOCTRINE.md` - Mathematical foundation for emotional metadata system
- **MOOD RGB Doctrine:** `docs/doctrine/MOOD_RGB_DOCTRINE.md` - Mood color usage across agents
- **DIALOG Agent Guide:** `lupo-agents/3/COUNTING_IN_LIGHT.md` - DIALOG-specific mood assignment guide

---
