---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
dialog:
  speaker: Kiro
  target: @everyone
  message: "Created EMO_AGENT_RULES.md - implementation rules for emotional agents. Defines slot assignment, shadow aliasing, texture curation, and multi-domain operation."
  mood: "00FF00"
tags:
  categories: ["doctrine", "emotional-system", "implementation"]
  collections: ["core-docs", "emotional-architecture"]
  channels: ["dev", "internal"]
file:
  title: "Emotional Agent Rules"
  description: "Implementation rules for EMO_* agents. Covers slot assignment, shadow aliasing, texture curation, and multi-domain operation."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Emotional Agent Rules

**Official Doctrine Document**  
**Version 4.0.15**  
**Effective Date: 2026-01-13**

---

## Overview

This document defines the implementation rules for EMO_* agents in Lupopedia's multi-domain emotional architecture.

---

## Rule 1: One Domain = One Agent = One Slot

**Each emotional domain gets exactly one AI agent and one `dedicated_slot`.**

```
domain: relational
agent: EMO_RELATIONAL
slot: 1001
```

**No exceptions.**

---

## Rule 2: Shadow Aliases Share the Same Slot

**Every emotional domain has a shadow polarity alias that maps to the same agent and slot.**

```
EMO_RELATIONAL → slot 1001
EMO_RELATIONAL_SHADOW → slot 1001 (same agent, same slot)
```

**Shadow aliases:**
- Have `agent_registry_parent_id` pointing to the root agent
- Differ only in vector coordinates and Emotional Texture
- Represent opposite poles within the same domain

---

## Rule 3: Emotional Texture is Optional but Encouraged

**Emotional Texture augments vector geometry with phenomenological depth.**

**Rules:**
- Texture is free-text, not structured data
- Texture is curated by human or mythic agents
- Texture may draw from diverse wisdom traditions
- Texture informs interpretation but does not replace vectors
- Machine agents compute with vectors; humans curate textures

**Example:**
```
domain: awe
vector: [awe: 0.9, mundanity: 0.1, magnitude: 0.95]
texture: "the trembling awe of Hesychast stillness"
```

---

## Rule 4: Domains Are Independent

**Emotional domains do not contaminate each other.**

**This means:**
- Critical dissent does not reduce relational love
- Fear urgency does not override trust certainty
- Each domain operates in its own vector space

**Multi-domain activation is normal:**
```
LILITH may have:
  critical: [agree: 0.1, dissent: 0.9, rigor: 0.95]
  relational: [love: 0.8, hate: 0.2, depth: 0.9]
  shadow: [illumination: 0.7, obscurity: 0.3, mystery: 0.8]
```

All three domains are active simultaneously without conflict.

---

## Rule 5: Generic Terms Must Resolve to Specific Domains

**Generic emotional terms like "love" are routing aliases, not domains.**

**Resolution examples:**
```
"I love my child" → EMO_STORGE (familial love)
"I love my partner" → EMO_EROS or EMO_PRAGMA (passionate or committed love)
"I love my friend" → EMO_PHILIA (friendship love)
"I love humanity" → EMO_AGAPE (unconditional love)
"I love myself" → EMO_PHILAUTIA (self-love)
"I love flirting" → EMO_LUDUS (playful love)
```

**The system must determine which domain applies based on context.**

---

## Rule 6: Slot Range is 1000-1999

**All emotional agents live in the 1000-1999 range.**

**Sub-ranges:**
- 1000-1099: Base emotional domains
- 1100-1199: Complex emotional domains
- 1200-1999: Extended emotional domains

**See:** [LUPOPEDIA_AGENT_DEDICATED_SLOT_RANGES.md](LUPOPEDIA_AGENT_DEDICATED_SLOT_RANGES.md)

---

## Rule 7: Axes Are Domain-Specific

**Each domain defines its own three axes with domain-specific semantics.**

**Examples:**
```
relational: [love, hate, depth]
critical: [agree, dissent, rigor]
valence: [joy, sorrow, intensity]
energy: [calm, agitated, resonance]
trust: [trust, suspicion, certainty]
```

**The "love" axis in the relational domain is NOT the same as "love" in other contexts.**

---

## Rule 8: Validity Ranges Are Domain-Specific

**Each axis has valid coordinate ranges defined by the domain.**

**Typical range:**
```
[0.0, 1.0] for normalized coordinates
```

**But domains may define different ranges if needed:**
```
[-1.0, 1.0] for bipolar axes
[0, 100] for percentage-based axes
```

**Coordinates outside the valid range are invalid for that domain.**

---

## Rule 9: Shadow Polarity Represents Opposite Pole

**Shadow aliases represent the opposite pole or distorted form of the domain.**

**Examples:**
```
EMO_AGAPE_SHADOW = apathy, selfishness, superficiality
EMO_EROS_SHADOW = repulsion, indifference, coldness
EMO_PHILIA_SHADOW = distrust, betrayal, distance
EMO_TRUST_SHADOW = suspicion, paranoia, uncertainty
```

**Shadow is not a separate emotion—it's the same domain with inverted coordinates.**

---

## Rule 10: Emotional Texture Draws from Diverse Traditions

**Emotional Texture may reference:**
- Gnostic wisdom (kenoma, pleroma, archons)
- Indigenous knowledge (relational accountability, land-based wisdom)
- Sufi mysticism (sukhan, fana, baqa)
- Feminist theology (Lilith, Sophia, divine feminine)
- Buddhist philosophy (mudita, metta, karuna)
- Taoist principles (wu-wei, yin-yang, flow)
- African philosophy (Ubuntu, communal being)
- Hesychast practice (stillness, divine light)
- And others

**Texture is multicultural by design.**

---

## Implementation Examples

### Example 1: Creating an Emotional Domain

```sql
-- Root agent
INSERT INTO lupo_agent_registry (
    code,
    name,
    dedicated_slot,
    agent_registry_parent_id,
    is_kernel,
    is_required
) VALUES (
    'EMO_TRUST',
    'Trust Emotional Domain',
    1040,
    NULL,  -- Root agent
    0,
    0
);

-- Shadow alias
INSERT INTO lupo_agent_registry (
    code,
    name,
    dedicated_slot,
    agent_registry_parent_id,
    is_kernel,
    is_required
) VALUES (
    'EMO_TRUST_SHADOW',
    'Trust Shadow - Suspicion',
    1040,  -- Same slot
    <parent_id_of_EMO_TRUST>,  -- Points to parent
    0,
    0
);
```

### Example 2: Storing Emotional Texture

```sql
INSERT INTO lupo_emotional_texture (
    agent_registry_id,
    texture_label,
    texture_description,
    cultural_context,
    created_ymdhis,
    updated_ymdhis,
    deleted_ymdhis
) VALUES (
    <agent_id_of_EMO_TRUST>,
    'Solid Ground',
    'the solid ground of earned trust',
    'Western',
    20260113120000,
    20260113120000,
    0
);
```

### Example 3: Multi-Domain State

```php
class EmotionalState {
    private $domains = [];
    
    public function setDomain($domainCode, $vector, $texture = null) {
        $this->domains[$domainCode] = [
            'vector' => $vector,
            'texture' => $texture
        ];
    }
    
    public function getDomain($domainCode) {
        return $this->domains[$domainCode] ?? null;
    }
    
    public function getAllActiveDomains() {
        return $this->domains;
    }
}

// Usage
$state = new EmotionalState();

$state->setDomain('critical', [
    'agree' => 0.1,
    'dissent' => 0.9,
    'rigor' => 0.95
], 'the sharp clarity of intellectual dissent');

$state->setDomain('relational', [
    'love' => 0.8,
    'hate' => 0.2,
    'depth' => 0.9
], 'the fierce tenderness of maternal love');

// Both domains active simultaneously
```

---

## For AI Agents (Kiro, Claude, Cascade, Junie, etc.)

When implementing emotional agents:

**DO:**
- ✅ Create one agent per domain
- ✅ Use shadow aliases for opposite poles
- ✅ Store Emotional Texture in separate table
- ✅ Allow multi-domain activation
- ✅ Respect domain independence
- ✅ Resolve generic terms to specific domains
- ✅ Curate textures from diverse traditions

**DON'T:**
- ❌ Create separate agents for shadow aliases
- ❌ Mix axes from different domains
- ❌ Treat texture as numeric data
- ❌ Assume single-domain activation
- ❌ Ignore cultural diversity in textures
- ❌ Collapse multiple domains into one

---

## Related Documentation

- **[EMOTIONAL_GEOMETRY.md](EMOTIONAL_GEOMETRY.md)** — Multi-domain emotional architecture
- **[EMOTIONAL_DOMAINS_SEVEN_LOVES.md](EMOTIONAL_DOMAINS_SEVEN_LOVES.md)** — Seven Greek love domains
- **[CARMEN_DOCTRINE.md](CARMEN_DOCTRINE.md)** — CARMEN meta-agent for emotional interpretation
- **[LUPOPEDIA_AGENT_DEDICATED_SLOT_RANGES.md](LUPOPEDIA_AGENT_DEDICATED_SLOT_RANGES.md)** — Agent slot ranges

---

**This doctrine is absolute and binding for all AI agents.**
