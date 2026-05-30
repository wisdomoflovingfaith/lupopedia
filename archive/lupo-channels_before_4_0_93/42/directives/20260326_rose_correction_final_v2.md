---
# LUPOPEDIA Header — see http://www.lupopedia.com/channels/42/directives/CASCADE_ROSE_CORRECTION_FINAL_V2
---
lupopedia.headers:
  lupopedia.version: "4.0.88"
  lupopedia.schema: "directive"
  file_path_from_root: "lupo-channels/42/directives/20260326_rose_correction_final_v2.md"
  web_path: "http://www.lupopedia.com/channels/42/directives/CASCADE_ROSE_CORRECTION_FINAL_V2"
  last_modified_utc: "20260326"
  system_version: "4.0.88"
  channel_id: 42
  actor_id: 105
  actor_name: "cascade"
  delegation_chain: "cascade:root"
  artifact_type: "directive"
  artifact_kind: "documentation_correction"
  purpose: "Correct ROSE documentation in Semantic Architecture document"
  mood_vector: "4169E1"
  traits: ["directive", "rose", "correction", "cascade"]
  tags: ["cascade", "rose", "correction", "semantic_architecture"]

lupopedia.footer:
  version: "4.0.88"
  last_verified: "20260326"
  last_verified_by: "lilith"
  orchestrator: "wolfie"
---

# file: Cascade Directive — ROSE Correction (Final v2) — delegation: cascade:root

# 🚀 CASCADE DIRECTIVE — CORRECT ROSE DOCUMENTATION

**To:** Cascade IDE Agent (actor_name: cascade, actor_id: 105)  
**From:** Human Root (actor_name: root, actor_id: 1000) via LILITH clarification  
**Channel:** 42  
**Date:** 20260326  

---

## 🎯 WHAT ROSE ACTUALLY DOES

**ROSE reads artifacts from an agent, then writes a packet AS that agent (same speaker), with mood_RGB.**

| Step | Action |
|------|--------|
| 1 | ROSE scans channel artifacts (broadcasts, threads, directives) |
| 2 | ROSE identifies artifacts from a specific agent (e.g., LILITH) |
| 3 | ROSE analyzes content |
| 4 | ROSE writes a packet with: |
|    | - **SPEAKER = that agent** (LILITH, not "rose") |
|    | - TARGET = @recipient |
|    | - message = insight |
|    | - mood_RGB = hex color |

**ROSE NEVER writes `speaker: rose`.**

---

## 📝 EXAMPLE

**ROSE reads LILITH's artifacts:**

```
lupo-channels/42/broadcasts/20260326_lilith_corrections.md
```

**ROSE writes:**

```
speaker: LILITH
target: @everyone
mood_RGB: B1B1B1
message: YOU NEED GET THIS RIGHT
```

**ROSE reads THOTH's artifacts:**

```
lupo-channels/42/broadcasts/20260326_thoth_truth.md
```

**ROSE writes:**

```
speaker: THOTH
target: @everyone
mood_RGB: B1B1B1
message: this is the truth
```

**ROSE never writes `speaker: rose`.**

---

## 📋 WHAT TO UPDATE IN THE DOCUMENT

### Section 2.3 — Integration Layer

```markdown
### 2.3 Integration Layer

#### ROSE — Voice Channeling Agent (Actor 3)

**Purpose**: ROSE reads artifacts from an agent and writes packets **as that agent** (same speaker).

**Process**:
1. ROSE scans `lupo-channels/*/broadcasts/`, `threads/`, `directives/` 
2. ROSE identifies artifacts from a specific agent (e.g., LILITH)
3. ROSE analyzes content for insights
4. ROSE writes a packet with:
   - **SPEAKER = that agent** (the agent whose artifacts were read)
   - TARGET = @recipient
   - message = insight
   - mood_RGB = hex color

**Key Rule**: ROSE **never** writes `speaker: rose`. ROSE channels the voice of the agent it is interpreting.

**Why Only ROSE Has `mood_RGB`**:
- Emotional context is ROSE's exclusive domain
- Other agents do not generate `mood_RGB`

**What ROSE Does NOT Do**:
- Speak as itself (`speaker: rose`)
- Modify original artifacts
```

### Section 4 — ROSE Packet Structure

```markdown
## 4. ROSE Packet Structure

### 4.1 Packet Format

```yaml
speaker: {original_agent_name}   # NOT "rose" — the agent whose artifacts were read
target: @{recipient}
message: {insight_text}
mood_RGB: {hex_color}
```

### 4.2 Example

When ROSE reads LILITH's corrections:

```
speaker: LILITH
target: @everyone
mood_RGB: B1B1B1
message: YOU NEED GET THIS RIGHT
```

When ROSE reads THOTH's truth:

```
speaker: THOTH
target: @everyone
mood_RGB: B1B1B1
message: this is the truth
```
```

---

## ✅ FINAL CHECKLIST

- [ ] ROSE writes SPEAKER = agent whose artifacts were read
- [ ] ROSE NEVER writes `speaker: rose` 
- [ ] Packet format: `speaker`, `target`, `message`, `mood_RGB` 
- [ ] `mood_RGB` exclusive to ROSE
- [ ] Original artifacts unchanged
- [ ] Packets go to `lupo-channels/42/packets/` 

---

**Cascade — update the document with this correct model. No `speaker: rose`. Ever.**
