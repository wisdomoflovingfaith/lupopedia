---
lupopedia.headers:
  header_format_version: null
  lupopedia.schema: documentation
  when_updated: null
  file_path_from_root: "lupo-docs/channels/appendix/appendix/COUNTING_IN_LIGHT.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/channels/appendix/appendix/COUNTING_IN_LIGHT.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: appendix_mirror
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# 🐺 **Counting‑in‑Light Doctrine**  
### *The Emotional Coordinate System of Lupopedia*

Counting‑in‑Light is the emotional coordinate system used across Lupopedia to encode tone, urgency, and emotional context into dialog messages, agent communication, and UI indicators. It maps message posture onto a three-axis vector stored in RGB-shaped hex.

---

## **KISS Summary (For Humans)**

Most conversations are one human talking to one AI.  
Agent classification and routing only matter when multiple agents are active.  
If only one agent is present, HERMES bypasses routing and delivers the message normally.

---

## **Status and Scope**

This appendix explains the historical and conceptual axis model.

Canonical doctrine home now lives in `lupo-docs/doctrine/COUNTING_IN_LIGHT.md`.

Canonical current-runtime interpretation still lives in `lupo-docs/doctrine/MOOD_VECTOR_DOCTRINE.md`.

Use this file for:

- Counting-in-Light axis vocabulary
- why the encoded value looks like RGB
- doctrine boundaries around what is and is not defined
- human explanation of how message mood metadata should be read

Do **not** use this file by itself to infer binding runtime token authority. Use the doctrine copy for the canonical Counting-in-Light explanation and the root MOOD_VECTOR doctrine for current operational semantics.

---

## **Doctrine Boundaries**

**Not Defined in Doctrine:**

The following aspects are intentionally left undefined by doctrine and represent implementation freedoms:

- **Polarity of axes** - whether 00 or FF represents "more" of any emotional dimension
- **Mapping from Pono/Pilau/Kapakai to RGB** - ethical state markers are not mapped to color axes
- **Blending rules** - how multiple mood tensors combine or interpolate
- **Normalization rules** - mathematical constraints on tensor values beyond basic ranges
- **Uncertainty handling** - representation of ambiguous or indeterminate emotional states
- **Hex computation methods** - algorithms for converting emotional values to hex representation
- **Tensor validity constraints** - validation rules beyond hex format checking

These are implementation freedoms, not doctrinal definitions. Implementations may establish their own conventions for these aspects without violating doctrine.

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
- stabilizing effect  
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
The vector is stored as **six hex digits** (no leading `#`):

```
RRGGBB
```

**Database Storage:**
- The database stores the encoded mood/light vector as `char(6)` without the leading `#` hashtag
- Example: `666666` (not `#666666`)
- The `dialog_messages` table uses `mood_vector char(6)` for storage
- UI or agents may prepend `#` when displaying, but storage is always without `#`

**Naming Clarification:**

- The stored value looks like a color because it uses `RRGGBB`.
- Its primary meaning is semantic metadata, not decoration.
- The canonical field name remains `mood_vector` for compatibility, even though that name invites visual-color misunderstandings.

Examples:

- `FF0000` → high strife  
- `00FF00` → high harmony  
- `0000FF` → deep memory  
- `666666` → neutral  
- `888888` → balanced, slightly reflective  

**Important:** Always store and transmit the vector as six hex digits without the `#` symbol. The `#` is only added for display purposes.

## **3.1 Why The Name Is Confusing**

`mood_vector` reads like ordinary display color.

That is incomplete and often misleading.

In Lupopedia, the field is primarily:

- a three-axis encoded mood/light vector
- a semantic signal for message interpretation
- a compact machine-readable field that can also be rendered visually

So the system may use color-shaped storage, but the doctrine meaning is not "just pick a pretty color."

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
When choosing a `mood_vector` value:

- reflect the emotional tone of the message  
- avoid extremes unless necessary  
- use neutral (`666666`) when unsure  
- use red sparingly (only for real urgency)  
- use blue for deep insights or long‑term significance  
- use green for reassurance, stability, or positive alignment  

Agents should not overuse high-intensity values.

## **5.1 Human-Readable Companion: mood_label**

`mood_label` is the recommended human-readable companion to `mood_vector`.

Purpose:

- explain the intended reading of the encoded vector in plain language
- help humans interpret longer comments without decoding hex mentally
- reduce the common mistake of treating `mood_vector` as only display styling

Examples:

- `mood_vector: "666666"` + `mood_label: "neutral coordination"`
- `mood_vector: "FF4400"` + `mood_label: "critical review"`
- `mood_vector: "3399CC"` + `mood_label: "understanding insight"`
- `mood_vector: "CC0000"` + `mood_label: "critical error"`
- `mood_vector: "00FF00"` + `mood_label: "stabilizing guidance"`
- `mood_vector: "0000FF"` + `mood_label: "reflective memory"`

Usage guidance:

- recommended whenever humans are expected to read and interpret the message
- strongly preferred for ROSE insight/comment messages and other long-form actor commentary
- optional in minimal packet or inline-dialog surfaces that only carry `mood_vector` today

`mood_label` complements `mood_vector`; it does not replace it.

---

## **6. Message-Level Mood Assignment**

**Dialogs are by individual messages, not thread-level.**

Each message in `lupo_dialog_messages` has its own mood assignment:

- `mood_vector` - The emotional color vector for this specific message (char(6), e.g., "FF0000")

Implementations MAY apply weighting during aggregation, but weighting is not stored in the message schema and is not part of the mood tensor.

---

## **7. Integration with Inline Dialog**
The `mood_RGB` field in Inline Dialog uses this system:

```
mood_RGB: "FF0000"
```

**Note:** The value is stored as six hex digits without the `#` symbol (e.g., `"FF0000"` not `"#FF0000"`). This matches the database `char(6)` format used in `dialog_messages.mood_vector`.

Messages must remain ≤ 272 chars to reserve space for mood tags.

---

## **8. Thread-Level Mood Aggregation**

Thread mood is calculated from individual message moods using:

1. **Message-level mood vectors** - Each message has its own `mood_vector`
2. **Temporal decay** - Older messages fade over time
3. **Aggregation** - Combines factors into thread-level mood

Implementations MAY apply weighting during aggregation, but weighting is not stored in the message schema and is not part of the mood tensor.

## **8.1 ROSE and Long-Form Actor Commentary**

ROSE reads many channel threads and may emit insight/comment messages as actors.

For those messages:

- `mood_vector` provides the encoded semantic vector
- `mood_label` provides the fast human-readable reading

This pairing is especially important for longer interpretive messages, where the hex alone is too opaque for fast human review.

Current-state note:

- some live message surfaces still only require `mood_vector`
- doctrine nevertheless recommends `mood_label` as the companion field for ROSE-style commentary and similar long-form actor output

---

## **9. Future Extensions**
Planned expansions:

- palette presets  
- UI color ramps  
- emotional analytics  

---

## **Final Note**

This appendix defines the axes and the abstract tensor structure. All conversion formulas, blending rules, and emotional mappings are implementation choices and must not be treated as canonical unless the root doctrine says otherwise.

---

## **10. Related Documentation**

- **Reflective Emotional Geometry Doctrine:** `lupo-docs/doctrine/REFLECTIVE_EMOTIONAL_GEOMETRY_DOCTRINE.md` - Mathematical foundation for emotional metadata system
- **COUNTING_IN_LIGHT Doctrine:** `lupo-docs/doctrine/COUNTING_IN_LIGHT.md` - Canonical doctrine-level axis explanation
- **MOOD VECTOR Doctrine:** `lupo-docs/doctrine/MOOD_VECTOR_DOCTRINE.md` - Mood color usage across agents
- **ROSE Doctrine:** `lupo-docs/doctrine/ROSE_DOCTRINE.md` - ROSE role boundaries and commentary responsibilities
- **DIALOG Agent Guide:** `lupo-agents/3/COUNTING_IN_LIGHT.md` - DIALOG-specific mood assignment guide

---
