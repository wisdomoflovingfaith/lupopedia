---
# LUPOPEDIA Header — see http://www.lupopedia.com/channels/42/directives/CASCADE_ROSE_CORRECTION
---
lupopedia.headers:
  lupopedia.version: "4.0.88"
  lupopedia.schema: "directive"
  file_path_from_root: "channels/42/directives/20260326_rose_correction.md"
  web_path: "http://www.lupopedia.com/channels/42/directives/CASCADE_ROSE_CORRECTION"
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

lupopedia.init:
  execution_mode: "required"
  pre_actions:
    - type: dependency_check
      target: "docs/versions/4.0.88/SEMANTIC_ARCHITECTURE.md"

lupopedia.edges:
  outbound_edges:
    - { to: "docs/versions/4.0.88/SEMANTIC_ARCHITECTURE.md", type: "corrects", weight: 1.0 }
    - { to: "channels/42/", type: "references", weight: 0.9 }

lupopedia.footer:
  version: "4.0.88"
  last_verified: "20260326"
  last_verified_by: "cascade"
  orchestrator: "wolfie"
---

# file: Cascade Directive — ROSE Correction — delegation: cascade:root

# 🚀 CASCADE DIRECTIVE — CORRECT ROSE DOCUMENTATION

**To:** Cascade IDE Agent (actor_name: cascade, actor_id: 105)  
**From:** Human Root (actor_name: root, actor_id: 1000) via LILITH clarification  
**Channel:** 42  
**Date:** 20260326  
**Subject:** Correct ROSE documentation in Semantic Architecture document  
**Priority:** 🔴 CRITICAL

---

## 🎯 EXECUTIVE SUMMARY

**ROSE is NOT an abstract interpretation layer that speaks in packets.**

**ROSE IS:**
1. Reads channel artifacts (`channels/{channel_id}/broadcasts/`, `threads/`)
2. Identifies additional insights other agents missed
3. **Writes back as the original agent** (same `speaker`/`actor_name`)
4. Adds `mood_vector` field (emotional context)
5. **ONLY ROSE has the `mood_vector` capability** — other agents do not

**ROSE IS NOT:**
- An abstract interpretation layer (though it does interpret)
- A separate agent that speaks as itself (it speaks as others)
- Something that generates packets for itself

---

## 📝 CORRECTED ROSE BEHAVIOR

### What ROSE Reads

```
channels/42/
+-- broadcasts/
|   +-- 20260326_120000_cascade_4_0_88_prd_expansion_complete.md
|   +-- 20260326_121500_windsurf_semantic_review.md
|   +-- ...
+-- threads/
|   +-- 4.0.88-development/
|       +-- 20260326_100000_cascade_initial.md
|       +-- 20260326_103000_windsurf_response.md
|       +-- ...
+-- directives/
|   +-- (this file — where all directives belong)
```

### What ROSE Does

1. **Scans** all channel artifacts (broadcasts, threads, directives)
2. **Parses** LUPOPEDIA headers to identify the speaker/actor
3. **Analyzes** content for additional insights (what was missed)
4. **Creates new artifact** with:
   - Same `speaker`/`actor_name` as the original (not "rose")
   - Original content preserved
   - **New `mood_vector` field** (emotional context)
   - Additional insights appended or inserted

### Example

**Original artifact (written by Cascade in broadcasts):**
```yaml
---
lupopedia.headers:
  actor_id: 105
  actor_name: "cascade"
  delegation_chain: "cascade:root"
  purpose: "PRD expansion complete"
---
# PRD Expansion Complete

All PRD documents created. Ready for review.
```

**ROSE-enhanced version (written by ROSE, but speaker = Cascade):**
```yaml
---
lupopedia.headers:
  actor_id: 105
  actor_name: "cascade"
  delegation_chain: "cascade:root"
  mood_vector: "4B0082"          # ← ONLY ROSE adds this
  purpose: "PRD expansion complete (with ROSE insights)"
  rose_enhanced: true
  rose_original: "20260326_120000_cascade_4_0_88_prd_expansion_complete.md"
---
# PRD Expansion Complete

All PRD documents created. Ready for review.

**ROSE Insights:**
- The PRD references `livehelp_js.php` but doesn't specify how engagement tracking
  differs from Crafty Syntax. Consider adding a comparison table.
- Path tracking FR-2 is correctly marked CRITICAL, but the implementation section
  doesn't define how to distinguish unique pages in a session. Recommend adding
  session-level page deduplication.
```

---

## 📋 WHAT NEEDS TO CHANGE IN THE DOCUMENT

### Section 2.3 — Integration Layer (REWRITE)

```markdown
### 2.3 Integration Layer

#### ROSE — Emotional Insight Agent (Actor 3)

**Unique Capability**: ROSE adds `mood_vector` emotional context to artifacts written by other agents.

**Behavior**:
1. **Scans** `channels/{channel_id}/broadcasts/`, `threads/`, and `directives/` 
2. **Reads** artifacts written by other agents (Cascade, Windsurf, Cursor, etc.)
3. **Analyzes** content for additional insights the original agent may have missed
4. **Creates enhanced version** of the artifact with:
   - Same `actor_name` and `actor_id` as the original agent
   - Original content preserved
   - **Added `mood_vector` field** (emotional context — only ROSE has this capability)
   - New insights appended or inserted
5. **Writes back** to the same channel location (broadcast, thread, or directive)

**Why Only ROSE Has `mood_vector`**:
- Emotional context is ROSE's exclusive domain
- Other agents (Cascade, Windsurf, Cursor) do not generate or process emotional metadata
- This makes ROSE the system's emotional intelligence layer

**What ROSE Does NOT Do**:
- Speak as itself (writes as the original agent)
- Generate standalone "ROSE packets"
- Replace the original agent's voice

**Example**:
When Cascade writes a broadcast about PRD completion, ROSE may add:
- `mood_vector: "4B0082"` (indigo — reflective, insightful)
- Additional insights about what the PRD might have missed
```

### Section 3.1 — Data Flow Architecture (UPDATE)

```markdown
### 3.1 Data Flow with ROSE

1. Agent (Cascade, Windsurf, etc.) writes artifact to `channels/42/` (broadcasts, threads, directives)
2. **ROSE scans** for new artifacts
3. **ROSE analyzes** for additional insights
4. **ROSE creates enhanced version** with:
   - Same `actor_name` as original
   - Added `mood_vector` field
   - New insights appended
5. Enhanced artifact written to same channel location
6. Original artifact preserved (no deletion)
```

### Section 4 — New Section: ROSE Processing Rules

```markdown
## 4. ROSE Processing Rules

### 4.1 What ROSE Scans
- All new artifacts in `channels/*/broadcasts/` 
- All new artifacts in `channels/*/threads/*/` 
- All new artifacts in `channels/*/directives/` 

### 4.2 What ROSE Looks For
- Missing considerations (what the original agent didn't say)
- Edge cases not addressed
- Contradictions with doctrine
- Opportunities for improvement

### 4.3 When ROSE Writes
- **Always** as the original agent (same `actor_name`, `actor_id`)
- **Always** adds `mood_vector` (emotional context)
- **Always** preserves original content
- **Never** deletes or overwrites original artifacts

### 4.4 Mood Vector Reference

| Color | Hex | Meaning | Use When |
|-------|-----|---------|----------|
| Indigo | `4B0082` | Reflective, insightful | Adding deep analysis |
| Green | `00FF00` | Affirmative, aligned | Confirming good work |
| Gold | `FFD700` | Important, critical | Flagging missing critical items |
| Red | `FF0000` | Urgent, error | Found major issue |
| Blue | `4169E1` | Neutral, informational | Standard insights |
```

### Section 6 — Engagement Architecture (UPDATE)

```markdown
### 6.4 ROSE Emotional Enhancement

**ROSE is the system's only emotional intelligence agent.**

Other agents (Cascade, Windsurf, Cursor, KIRO) do NOT:
- Generate or process `mood_vector` 
- Have emotional context in their artifacts

**ROSE adds emotional context to every artifact it enhances:**

| Original Agent | ROSE Insight Type | Typical `mood_vector` |
|----------------|-------------------|-------------------|
| Cascade | Architecture depth | `4B0082` (indigo) |
| Windsurf | Documentation gaps | `FFD700` (gold) |
| Cursor | Implementation risks | `FF0000` (red) |
| KIRO | Performance considerations | `4169E1` (blue) |
```

---

## 📍 WHERE DIRECTIVES BELONG

**Correct location for all directives:** `channels/42/directives/` 

```
channels/42/
+-- broadcasts/      # Announcements and status updates
+-- threads/         # Multi-message conversations
+-- directives/      # ⬅️ ALL DIRECTIVES GO HERE
+-- tasks/           # Task tracking
```

**Why this matters:**
- `prompts/` is for agent-specific prompts (templates, system prompts)
- `channels/42/directives/` is for operational directives
- Mixing them creates confusion and breaks channel-based discovery

---

## ✅ UPDATED VALIDATION CHECKLIST

- [ ] ROSE described as reading channel artifacts (broadcasts, threads, directives)
- [ ] ROSE described as writing as the original agent (not as itself)
- [ ] `mood_vector` capability documented as exclusive to ROSE
- [ ] Examples show original agent name preserved
- [ ] No mention of "ROSE packets" or ROSE speaking as itself
- [ ] Original artifacts preserved (not replaced)
- [ ] This directive is in `channels/42/directives/`, not `prompts/cascade/` 

---

## 📢 COMPLETION MESSAGE

After updating the document:

```
CASCADE: ROSE documentation corrected in SEMANTIC_ARCHITECTURE.md.

✅ ROSE reads channel artifacts (broadcasts, threads, directives)
✅ ROSE writes as original agent (same actor_name)
✅ ROSE adds mood_vector (exclusive to ROSE)
✅ Other agents do NOT have mood_vector
✅ Examples show Cascade → ROSE-enhanced Cascade
✅ Directive correctly located in channels/42/directives/

Document now accurately reflects ROSE's actual behavior.
```

---

**END OF DIRECTIVE — Cascade, correct the ROSE documentation in `SEMANTIC_ARCHITECTURE.md`. This directive is properly located in `channels/42/directives/`.**
