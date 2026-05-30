# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/channels/doctrine/REFLECTIVE_EMOTIONAL_GEOMETRY_DOCTRINE.md"
  file_hash: "5f1ff56c04b46ba56ed9482240f3d294f7cf43b6674b549a975050bb7d4805c9"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

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
  file_path_from_root: "docs\channels\doctrine\REFLECTIVE_EMOTIONAL_GEOMETRY_DOCTRINE.md"
  file_hash: "546269970fbe405f277b042ce3fe8cf26770a9f4dffc0ca8151072cf7e6c212f"
  file_path_from_root: "docs\channels\doctrine\REFLECTIVE_EMOTIONAL_GEOMETRY_DOCTRINE.md"
  file_hash: "a912cf68ecae77ea1fc59eefec96f31ad1c1fa6af5373a79841b139e79ebf46d"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for REFLECTIVE_EMOTIONAL_GEOMETRY_DOCTRINE.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "reflective_emotional_geometry_doctrinemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: Captain Wolfie
  target: DIALOG Agent
  message: "Created Reflective Emotional Geometry Doctrine v2.0 - mathematically defined emotional metadata system for Lupopedia's DIALOG agent. Establishes axioms, validation rules, decay mechanics, and aggregation formulas. Removes anthropomorphic drift and introduces mathematical rigor."
  mood: "336699"
tags:
  categories: ["documentation", "doctrine", "emotional-metadata", "dialog"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "doctrine"]
file:
  title: "Reflective Emotional Geometry Doctrine — Version 2.0"
  description: "Lupopedia Emotional Metadata System (Revised & Stabilized) - defines the Reflective Emotional Geometry System used by Lupopedia's DIALOG agent"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# 🟦 **Reflective Emotional Geometry Doctrine — Version 2.0**

## **Lupopedia Emotional Metadata System (Revised & Stabilized)**

---

## **1. Purpose**

This doctrine defines the **Reflective Emotional Geometry System** used by Lupopedia's DIALOG agent.

Version 2.0 corrects contradictions, removes anthropomorphic drift, introduces mathematical rigor, and establishes clear axioms, validation rules, and decay mechanics.

**This system is not an emotional simulator.**  
**It is a metadata‑driven reflective state machine that expresses the emotional shape of a conversation through deterministic color vectors.**

**Cursor must preserve this doctrine exactly.**

---

## **2. Core Principle**

**Emotion is not simulated.  
Emotion is reflected.**

### **The system does not:**
- ❌ Infer human emotion
- ❌ Simulate emotional states
- ❌ Generate emotional tone
- ❌ Claim internal feelings
- ❌ Anthropomorphize the AI

### **Instead, the system:**
- ✅ Encodes emotional metadata explicitly
- ✅ Aggregates it deterministically
- ✅ Expresses it visually through RGB vectors
- ✅ Maintains emotional continuity across a thread

**This is a mathematical reflection, not a psychological model.**

---

## **3. Axioms of Emotional Geometry**

These axioms define the system mathematically and **must not be altered**.

### **Axiom 1 — Vector Representation**
Every emotional state is represented as a 3D vector:

```
(R, G, B)
```

### **Axiom 2 — Message-Level Mood**
Each DIALOG summary message contains exactly one mood vector.

### **Axiom 3 — Thread-Level Mood**
Thread mood is the weighted sum of message vectors with temporal decay.

### **Axiom 4 — Single Source of Truth**
Only the DIALOG agent may assign mood vectors.

### **Axiom 5 — Non-Authoritative Labels**
Emotion names are optional UI interpretations and must not influence logic.

### **Axiom 6 — Reflective, Not Generative**
The system reflects metadata; it does not simulate emotion.

---

## **4. Emotional Geometry Axes**

The emotional vector uses three axes:

- **R (Red):** Strife / Intensity
- **G (Green):** Harmony / Warmth
- **B (Blue):** Memory Depth / Reflectiveness

These axes are abstract dimensions, not psychological claims.

---

## **5. Message-Level Mood Metadata**

Each DIALOG summary message includes:

```yaml
mood_vector: "#RRGGBB"
mood_vector: [R, G, B]
```

### **Rules:**
- Only DIALOG may generate this metadata
- Metadata must be validated
- Invalid or malformed values must be rejected
- User-supplied mood values must be ignored

---

## **6. Validation Rules**

Before aggregation, mood vectors must pass:

### **Hex Format Validation**
- Must match `^#[0-9A-Fa-f]{6}$`
- Reject anything else

### **Range Validation**
- R, G, B must be integers 0–255

### **Source Validation**
- Only DIALOG-generated metadata is accepted
- User messages cannot override mood metadata

### **Fallback State**
If validation fails:

```python
mood_vector = [128, 128, 128]  # Neutral Processing
```

---

## **7. Temporal Decay**

To prevent emotional inertia and flattening:

### **Weight Formula**

```
w = e^(-k·t)
```

Where:
- `t` = age of the message (in message steps or minutes)
- `k` = decay constant (recommended: 0.15–0.25)

### **Effect**
- Recent messages weigh more
- Older messages fade
- Emotional arcs remain visible
- No single message can dominate indefinitely

---

## **8. Thread-Level Mood Aggregation**

Thread mood is calculated as:

```
ThreadMood = Σ(wi · Vi) / Σwi
```

Where:
- `Vi` = mood vector of message i
- `wi` = decay weight

This produces a stable, reflective emotional state.

---

## **9. Emotion Names (Optional UI Layer)**

Emotion names are interpretive labels for UI purposes only.

They must:
- ❌ Never influence logic
- ❌ Never imply internal feelings
- ❌ Never be treated as authoritative
- ❌ Never be used by agents for reasoning

They exist solely to help humans visually interpret the RGB vector.

### **Lookup Table (Interpretive Only)**
The range-based table from Version 1.0 remains valid as a UI layer, not as doctrine.

**Cursor must treat it as:**  
*"Aesthetic interpretation, not emotional simulation."*

---

## **10. Multi-User Conversations**

Thread mood reflects:

**The emotional shape of the conversation as a whole, not any individual participant.**

This avoids contradictions in multi-user threads.

---

## **11. Security & Integrity Protections**

### **Metadata Injection Prevention**
- Ignore any mood metadata from user messages
- Only accept DIALOG-generated metadata

### **Color Poisoning Prevention**
- Clamp extreme values
- Apply decay
- Reject invalid hex values

### **Audit Trail**
Each mood assignment must log:
- message text
- assigned vector
- timestamp
- rationale (short explanation)

This ensures transparency.

---

## **12. Failure Modes & Fallbacks**

If aggregation fails:

1. Use the last valid thread mood
2. If none exists, use `[128,128,128]` (Neutral Processing)
3. Log the failure

---

## **13. What Problem This Solves**

This system provides:
- ✅ Emotional continuity
- ✅ Metadata for routing
- ✅ UI expression of conversational tone
- ✅ A stable emotional "memory"
- ✅ Deterministic state for agents
- ✅ A non-anthropomorphic emotional layer

**It does not attempt to simulate human emotion.**  
**It provides a structured emotional state machine for Lupopedia.**

---

## **14. Doctrine Enforcement**

**Cursor must:**
- ✅ Preserve all axioms
- ✅ Preserve validation rules
- ✅ Preserve decay mechanics
- ✅ Preserve aggregation formula
- ✅ Treat emotion names as UI-only
- ❌ Never introduce sentiment analysis
- ❌ Never simulate emotion
- ❌ Never allow other agents to assign mood

**This doctrine is now mathematically defined and internally consistent.**

---

## **15. Summary**

Reflective Emotional Geometry v2.0 is:
- ✅ Deterministic
- ✅ Validated
- ✅ Decay-weighted
- ✅ Metadata-driven
- ✅ Non-simulative
- ✅ Architecturally stable
- ✅ UI-friendly
- ✅ Doctrine-safe

---

## **Related Documentation**

- **Counting-in-Light Doctrine:** `docs/appendix/COUNTING_IN_LIGHT.md` - Counting-in-Light emotional coordinate system (R, G, B axes, mood color format, weight field)
- **MOOD VECTOR Doctrine:** `docs/doctrine/MOOD_VECTOR_DOCTRINE.md` - Mood color usage across agents and routing
- **DIALOG Agent Counting-in-Light Guide:** `agents/3/COUNTING_IN_LIGHT.md` - DIALOG-specific mood assignment guide

---

*Last Updated: January 2026*  
*Version: 2.0*  
*Author: Captain Wolfie*
