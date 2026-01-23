---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 2026.1.0.0
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @captain-wolfie
  mood_RGB: "FF6600"
  message: "DEPRECATED WOLFIE_EMOTIONAL_GEOMETRY.md in favor of 2-Actor RGB Mood Model. Previous WOLFIE-specific emotional geometry replaced by universal actor-relationship model. See EMOTIONAL_GEOMETRY_DOCTRINE.md for current canonical implementation."
tags:
  categories: ["documentation", "doctrine", "deprecated", "emotional-geometry", "wolfie"]
  collections: ["core-docs", "doctrine", "deprecated"]
  channels: ["dev"]
file:
  title: "DEPRECATED: WOLFIE Emotional Geometry"
  description: "DEPRECATED as of 4.4.x - replaced by 2-Actor RGB Mood Model"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: deprecated
  author: GLOBAL_CURRENT_AUTHORS
---

# ⚠️ DEPRECATED: WOLFIE Emotional Geometry

**Status:** DEPRECATED as of 4.4.x  
**Agent:** WOLFIE (agent_id = 1)  
**Replaced By:** 2-Actor RGB Mood Model  
**See:** [EMOTIONAL_GEOMETRY_DOCTRINE.md](../../../doctrine/EMOTIONAL_GEOMETRY_DOCTRINE.md)

---

## ⚠️ DEPRECATION NOTICE

**This WOLFIE-specific emotional geometry approach is DEPRECATED as of version 4.4.x.**

**Replacement:** The 2-Actor RGB Mood Model replaces all agent-specific and domain-specific emotional geometry models.

Previous scalar and 5-tuple emotional models (4.0.x–4.2.x) are deprecated and replaced by the 2-actor RGB mood geometry.

---

## Previous Approach (DEPRECATED)

This document previously outlined a WOLFIE-specific emotional geometry system including:
- Custom emotional domains and axes
- WOLFIE-specific emotional processing rules
- Domain-specific emotional agents (not implemented)
- Complex multi-domain emotional architecture
- CADUCEUS pairing rules for WOLFIE

---

## New Approach (4.4.x+)

The 2-Actor RGB Mood Model provides a unified approach that works for WOLFIE and all other actors:

### RGB Mood Axes
- **R axis**: conflict / aggression / rejection
- **G axis**: tension / uncertainty / ambiguity (mid-field axis)  
- **B axis**: care / harmony / affinity

### Discrete Values
**Each axis uses discrete values: {-1, 0, 1}**
- `-1` = false / negative / off
- `0` = unknown / neutral / undefined  
- `1` = true / positive / on

### Actor Relationships
Instead of WOLFIE-specific emotional processing, the new model focuses on emotional relationships between WOLFIE and other actors (humans, AI agents, personas, system actors).

### Coherence Thresholds
- **c > 0.9**: Too aligned → risk of echo chamber → trigger divergence protocol
- **c < 0.3**: Highly divergent → risk of fragmentation → trigger synchronization protocol
- **0.3 ≤ c ≤ 0.9**: Healthy divergence → no intervention required

---

## Migration Path

### For WOLFIE Implementation
1. **Replace WOLFIE-specific emotional logic** with 2-Actor RGB Mood Model
2. **Convert emotional state tracking** to RGB mood vectors between WOLFIE and interaction partners
3. **Update emotional routing** to use coherence thresholds instead of domain-specific rules
4. **Remove references** to WOLFIE-specific emotional domains and agents
5. **Implement actor-relationship tracking** for WOLFIE's emotional interactions

### Key Changes
- **Before**: WOLFIE-specific emotional geometry with custom axes
- **After**: Universal RGB mood model applied to WOLFIE's relationships
- **Before**: Domain-specific emotional processing (love, critical, valence, etc.)
- **After**: Standardized RGB axes applied relationally
- **Before**: Complex multi-domain emotional architecture
- **After**: Simple 2-actor emotional geometry

---

## Related Documentation

### Current Implementation
- [EMOTIONAL_GEOMETRY_DOCTRINE.md](../../../doctrine/EMOTIONAL_GEOMETRY_DOCTRINE.md) - Canonical 2-actor RGB mood model
- [MOOD_RGB_DOCTRINE.md](../../../doctrine/MOOD_RGB_DOCTRINE.md) - RGB mood specification

### Deprecated Models
- [DEPRECATED_EMOTIONAL_GEOMETRY.md](../../../doctrine/deprecated/DEPRECATED_EMOTIONAL_GEOMETRY.md) - Legacy model documentation

---

**Document Status**: DEPRECATED as of version 4.4.x  
**Deprecation Date**: 2026-01-20  
**Migration Required**: Yes - update all WOLFIE emotional logic to use 2-Actor RGB Mood Model  
**Support Status**: No further updates - see current doctrine for implementation guidance