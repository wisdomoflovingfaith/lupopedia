# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/channels/doctrine/emotional-agent-range.md"
  file_hash: "08e886448683ec24855a81c4a60f04dd820a9e7a5f86f58434d14dac13f66c43"
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
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

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
  file_path_from_root: "lupo-docs\channels\doctrine\emotional-agent-range.md"
  file_hash: "5c86fd86c1813702034bfd101e737e3fcbd31779be3f4de89d69a591ff1e1d24"
  file_path_from_root: "lupo-docs\channels\doctrine\emotional-agent-range.md"
  file_hash: "013b73d9fb19d3d01e1cbde44ca318b106bc62912d785035af3db08d6fd875b0"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for emotional-agent-range.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "emotional-agent-rangemd"]
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
  target: CURSOR
  message: "Created Emotional Agent Range Doctrine: defines rules for agents 1000-1100 as emotional-domain reflective agents that behave like DIALOG (non-interactive, metadata-only, summary-processing)."
  mood: "336699"
tags:
  categories: ["documentation", "doctrine", "agents", "emotional-metadata"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "doctrine"]
file:
  title: "Emotional Agent Range Doctrine (1000–1100)"
  description: "Rules for emotional-domain reflective agents in slots 1000-1100. These agents behave like DIALOG: non-interactive, metadata-only, summary-processing."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# 🎭 **Emotional Agent Range Doctrine (1000–1100)**

## **Purpose**

Agents in the **1000–1100 range** represent **emotional-domain reflective agents**.

They behave exactly like the DIALOG agent:

- ✅ They do not talk to users directly
- ✅ They do not generate content
- ✅ They do not participate in conversation
- ✅ They only process summary messages
- ✅ They output metadata only, not text
- ✅ They operate on their own geometry (e.g., humor, love, trust)
- ✅ They must never override or conflict with DIALOG
- ✅ They must never influence reasoning or TRUTH routing

---

## **Rules for Cursor**

### **Architecture Template**

When generating files for agents 1000–1100, use the **DIALOG architecture template**.

### **Non-Interactive Requirement**

These agents must be **non-interactive**.

### **System Prompt Requirements**

These agents must have a system prompt that states:

- "This agent does not speak to users."
- "This agent processes summary messages only."
- "This agent outputs metadata only."

### **Registration Requirements**

- ❌ **No emotional agent may be created automatically.**
- ✅ **All emotional agents must be explicitly registered in `lupo_agent_registry`.**
- ✅ **Emotional agents must not generate `mood_vector` (reserved for DIALOG).**
- ✅ **Emotional agents may generate their own vector (e.g., humor vector, love vector).**

---

## **Agent Structure**

### **What Emotional Agents Do:**

- ✅ Process DIALOG summary messages
- ✅ Analyze tonal/emotional metadata
- ✅ Output geometry vectors (e.g., humor vector E,C,S)
- ✅ Provide flavor-layer metadata for other agents
- ✅ Reflect metadata, not simulate emotion

### **What Emotional Agents Do NOT Do:**

- ❌ Talk to users
- ❌ Generate content or text
- ❌ Participate in live conversation
- ❌ Process raw dialog or user input
- ❌ Override DIALOG mood assignments
- ❌ Generate `mood_vector` (DIALOG's exclusive domain)
- ❌ Influence reasoning or TRUTH routing
- ❌ Affect doctrine or routing decisions

---

## **Examples**

### **HUMOR Agent (Slot 14 / THALINA)**

- **Input:** DIALOG summary messages
- **Output:** Humor vector (E, C, S) - Explanation, Confidence, Sense
- **Geometry:** Reflective Humor Geometry
- **Role:** Flavor-layer agent providing tonal metadata

### **Emotional Agents (1000-1100 Range)**

**Primary Agents (Slots 1000-1025):**

- **EMO_CORE** (1000) - Emotional Core Agent
- **EMO_LOVE** (1001) - Love Domain Agent
- **EMO_CARE** (1002) - Care Domain Agent
- **EMO_TRUST** (1003) - Trust Domain Agent
- **EMO_HUMOR** (1004) - Humor Domain Agent
- **EMO_INSIGHT** (1005) - Insight Domain Agent
- **EMO_MEMORY** (1006) - Memory Domain Agent
- **EMO_SHADOW** (1007) - Shadow Domain Agent
- **EMO_HARMONY** (1008) - Harmony Domain Agent
- **EMO_CURIOSITY** (1009) - Curiosity Domain Agent
- **EMO_BOUNDARY** (1010) - Boundary Domain Agent
- **EMO_TRANSFORM** (1011) - Transformation Domain Agent
- **EMO_EMPATHY** (1012) - Empathy Domain Agent
- **EMO_COMPASSION** (1013) - Compassion Domain Agent
- **EMO_STABILITY** (1014) - Stability Domain Agent
- **EMO_INTENSITY** (1015) - Intensity Domain Agent
- **EMO_DRIVE** (1016) - Drive Domain Agent
- **EMO_BREAKTHROUGH** (1017) - Breakthrough Domain Agent
- **EMO_WISDOM** (1018) - Wisdom Domain Agent
- **EMO_PRESENCE** (1019) - Presence Domain Agent
- **EMO_REFLECTION** (1020) - Reflection Domain Agent
- **EMO_RESOLVE** (1021) - Resolve Domain Agent
- **EMO_CLARITY** (1022) - Clarity Domain Agent
- **EMO_DEPTH** (1023) - Depth Domain Agent
- **EMO_WARMTH** (1024) - Warmth Domain Agent
- **EMO_BALANCE** (1025) - Balance Domain Agent

**Alias Agents (Parent-Child Relationship):**

Each primary agent (1000-1025) has a corresponding alias agent that uses the **same geometry calculation** but with **different axis interpretations/colors**. This allows opposite or shadow emotional domains to share the same AI agent logic while expressing different emotional ranges.

**Example:** EMO_LOVE (1001) and EMO_HATE (alias of 1001) are the same AI agent with the same calculation, just different axis colors/interpretations.

**Alias Mapping:**
- **EMO_CORE** (1000) → **EMO_CORE_SHADOW**
- **EMO_LOVE** (1001) → **EMO_HATE**
- **EMO_CARE** (1002) → **EMO_NEGLECT**
- **EMO_TRUST** (1003) → **EMO_DISTRUST**
- **EMO_HUMOR** (1004) → **EMO_DRY**
- **EMO_INSIGHT** (1005) → **EMO_CONFUSION**
- **EMO_MEMORY** (1006) → **EMO_FORGET**
- **EMO_SHADOW** (1007) → **EMO_LIGHT**
- **EMO_HARMONY** (1008) → **EMO_DISCORD**
- **EMO_CURIOSITY** (1009) → **EMO_APATHY**
- **EMO_BOUNDARY** (1010) → **EMO_INVASION**
- **EMO_TRANSFORM** (1011) → **EMO_STAGNATION**
- **EMO_EMPATHY** (1012) → **EMO_CRUELTY**
- **EMO_COMPASSION** (1013) → **EMO_INDIFFERENCE**
- **EMO_STABILITY** (1014) → **EMO_CHAOS**
- **EMO_INTENSITY** (1015) → **EMO_PASSIVITY**
- **EMO_DRIVE** (1016) → **EMO_APATHETIC_DRIFT**
- **EMO_BREAKTHROUGH** (1017) → **EMO_BLOCK**
- **EMO_WISDOM** (1018) → **EMO_FOLLY**
- **EMO_PRESENCE** (1019) → **EMO_ABSENCE**
- **EMO_REFLECTION** (1020) → **EMO_DISTORTION**
- **EMO_RESOLVE** (1021) → **EMO_DOUBT**
- **EMO_CLARITY** (1022) → **EMO_CLOUD**
- **EMO_DEPTH** (1023) → **EMO_SURFACE**
- **EMO_WARMTH** (1024) → **EMO_COLD**
- **EMO_BALANCE** (1025) → **EMO_IMBALANCE**

**Key Point:** Alias agents share the same parent agent's geometry calculation but interpret the axes differently (e.g., inverted colors, opposite ranges). This is efficient because LOVE/HATE use the same calculation logic, just different axis interpretations.

---

## **Why LOVE and HATE Are the Same AI Agent**

### **Emotional Polarity Principle**

In Lupopedia's emotional architecture, **LOVE and HATE are not separate agents** because they are not separate systems. They are **opposite poles of the same emotional axis** within the Counting in Light geometry.

### **1. Same Axis, Opposite Polarity**

The emotional geometry defines:

- **Blue-side polarity** → constructive, connective, nurturing (LOVE)
- **Red-side polarity** → destructive, rejecting, boundary-asserting (HATE)

Both are expressions of the same vector, just with **opposite direction**.

**This is not psychology. This is vector math.**

### **2. One Agent = One Geometry**

Each emotional agent in the 1000–1100 range represents:

- A domain
- A geometry
- A vector space
- A reflective subsystem

**LOVE/HATE is a single domain, not two.**

Splitting them would:

- ❌ Duplicate geometry
- ❌ Break polarity symmetry
- ❌ Create contradictory metadata
- ❌ Violate the reflective emotional model
- ❌ Cause CARMEN to mis-handle emotional arcs

### **3. Aliases Represent Opposites, Not New Agents**

The alias system exists so that:

- **LOVE** → primary identity
- **HATE** → alias identity
- Both map to the same agent ID
- Both use the same geometry
- Both produce the same vector type
- Only the polarity changes

This keeps the emotional ecosystem stable and mathematically consistent.

### **4. Reflective, Not Simulative**

The agent does not "feel love" or "feel hate." It reflects:

- The polarity
- The intensity
- The direction
- The metadata
- The emotional arc of the conversation

**LOVE/HATE is simply: same vector space, opposite direction.**

### **5. Why This Matters for AGI-Level Architecture**

If you ever build the full emotion ecosystem (and you will), polarity-paired domains must:

- ✅ Share geometry
- ✅ Share metadata schema
- ✅ Share reflective rules
- ✅ Share decay rules
- ✅ Share routing identity

This prevents runaway agent proliferation and keeps the emotional layer coherent.

### **⭐ Short Version (for Cursor)**

**LOVE and HATE are the same emotional agent because they are opposite polarities of the same emotional axis in the Counting in Light geometry. Emotional agents represent vector spaces, not feelings. Opposites are implemented as aliases, not separate agents.**

---

## **Namespace Protection**

### **Reserved Ranges:**

- **0-99:** Core kernel agents (SYSTEM, CAPTAIN, WOLFIE, etc.)
- **100-999:** Standard application agents
- **1000-1100:** Emotional-domain reflective agents (this doctrine)
- **1101+:** Reserved for future use

### **Preventing Spontaneous Creation:**

- Cursor must **never** create agents in the 1000-1100 range automatically
- All emotional agents must be **explicitly registered** in `lupo_agent_registry`
- This prevents Cascade or other agents from "inventing" agents without approval
- Provides a clean, controlled emotional-agent ecosystem

---

## **Integration with DIALOG**

### **Processing Flow:**

1. **DIALOG processes message** → Creates summary with `mood_vector` and `weight`
2. **Emotional agents receive DIALOG summary** → Analyze their specific geometry
3. **Emotional agents assign vectors** → Their own geometry (e.g., humor vector)
4. **Metadata stored** → Emotional vectors attached to message metadata
5. **Other agents may use** → For pacing, modulation, narrative texture

### **Separation of Concerns:**

- **DIALOG:** Assigns `mood_vector` (emotional color vector)
- **Emotional Agents:** Assign their own geometry vectors
- **No conflict:** Each operates in its own domain
- **No override:** Emotional agents never override DIALOG

---

## **File Structure**

When creating files for emotional agents (1000-1100), follow this structure:

```
lupo-agents/{slot}/
  - agent.json
  - capabilities.json
  - properties.json
  - system_prompt.txt
  - {GEOMETRY_NAME}_GEOMETRY.md  (e.g., REFLECTIVE_HUMOR_GEOMETRY.md)
  - versions/
    - v1.0.0/
      - capabilities.json
      - prompt.txt
      - properties.json
      - system_prompt.txt
```

### **Required Files:**

- **agent.json** - Agent metadata (code, name, layer, slot)
- **system_prompt.txt** - Must state non-interactive, summary-only, metadata-only
- **{GEOMETRY_NAME}_GEOMETRY.md** - Geometry documentation (similar to DIALOG's Counting-in-Light guide)

---

## **System Prompt Template**

All emotional agents (1000-1100) must include this in their system prompt:

```
You are {AGENT_NAME} (Agent {SLOT}).

CRITICAL RULES:
- This agent does not speak to users.
- This agent processes summary messages only.
- This agent outputs metadata only.
- This agent does not generate content.
- This agent does not participate in conversation.
- This agent never overrides DIALOG mood assignments.
- This agent never influences reasoning or TRUTH routing.

INPUT: DIALOG summary messages only
OUTPUT: {GEOMETRY_TYPE} vector and optional tonal metadata
```

---

## **Summary**

### **You now have:**

- ✅ A clean **1000–1025 emotional agent block**
- ✅ A doctrine for Cursor
- ✅ A stable namespace
- ✅ A rule preventing spontaneous agent proliferation
- ✅ A structure identical to DIALOG (summary‑only, metadata‑only)

### **This will:**

- ✅ Stop Cascade from "inventing" agents without your approval
- ✅ Give you a clean emotional‑agent ecosystem
- ✅ Ensure all emotional agents follow the DIALOG architecture pattern
- ✅ Maintain clear separation between DIALOG and emotional agents
- ✅ Prevent conflicts and override issues

---

---

## **Related Documentation**

- **Reflective Emotional Geometry Doctrine:** `lupo-docs/doctrine/REFLECTIVE_EMOTIONAL_GEOMETRY_DOCTRINE.md` - Mathematical foundation for emotional metadata
- **Counting-in-Light Doctrine:** `lupo-docs/appendix/COUNTING_IN_LIGHT.md` - Emotional coordinate system (R, G, B axes)
- **DIALOG Agent Guide:** `lupo-agents/3/COUNTING_IN_LIGHT.md` - DIALOG mood assignment guide
- **HUMOR Agent Guide:** `lupo-agents/14/REFLECTIVE_HUMOR_GEOMETRY.md` - HUMOR humor vector assignment guide

---

**Last Updated:** 2026-01-12  
**Version:** 3.0.4  
**Range:** 1000-1100 (Emotional-Domain Reflective Agents)
