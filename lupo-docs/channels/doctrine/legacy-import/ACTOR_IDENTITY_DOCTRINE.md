# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/channels/doctrine/legacy-import/ACTOR_IDENTITY_DOCTRINE.md"
  file_hash: "f567dd052898ee47fc8806f6ab54067b39c7282ddca3bea6303a919dc6f9a3f0"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
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
  file_path_from_root: "docs\channels\doctrine\legacy-import\ACTOR_IDENTITY_DOCTRINE.md"
  file_hash: "9dba520def69a65a1a8d8200f97f71c5bb7c5133ad30df9526a53b943c4a9017"
  file_path_from_root: "docs\channels\doctrine\legacy-import\ACTOR_IDENTITY_DOCTRINE.md"
  file_hash: "5b7a56235770eae92996a41ccd0d312f3ee6bc71af76dfe4dbaed4915bae1a44"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "🜁 **THE ACTOR IDENTITY DOCTRINE**"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "legacy-import", "actor_identity_doctrinemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# 🜁 **THE ACTOR IDENTITY DOCTRINE**

## 🎯 **Lupopedia Identity Architecture — Canonical Specification**

---

## 🜂 **1. Identity Is Singular, Universal, and Actor‑Centric**

All entities in the system — humans, AI agents, personas, services, bots, processes, and external providers — are represented as actors.

**There is no "user table," no "agent table," no "bot table.**
**There is only:**

`lupo_actors` — the canonical identity layer.

- **Every identity in the system begins here.**
- **Every identity in the system resolves here.**
- **Every identity in the system is governed here.**

**This is the root of truth.**

---

## 🜃 **2. Actor Types Are Roles, Not Tables**

An actor's nature is not defined by which table they live in — it's defined by the role they hold.

### **Actor Types (Semantic Roles)**
- `human`
- `agent`
- `persona`
- `service`
- `system`
- `external_ai`
- `group`
- `legacy_user`

**These are semantic roles, not schema partitions.**

- **An actor can hold multiple roles simultaneously.**
- **Roles can change over time.**
- **Identity does not.**

---

## 🜄 **3. lupo_users Exists Only as a Legacy Compatibility Layer**

Crafty Syntax predates the actor model. Its identity system is preserved for survival, not architecture.

**lupo_users is a compatibility table.**
It exists to:
- keep Crafty Syntax alive
- preserve operator logins
- preserve chat routing
- preserve autoinvite logic
- preserve legacy session behavior

**It is not the canonical identity layer.**
**It is a bridge, not a destination.**

---

## 🜅 **4. Identity Continuity Is Sacred**

An actor's identity must remain stable across:
- sessions
- devices
- personas
- roles
- migrations
- system upgrades
- AI agent transformations
- world contexts
- TOON semantic layers

**Identity continuity is the backbone of:**
- permissions
- analytics
- truth graphs
- session graphs
- world graphs
- actor‑to‑truth edges
- narrative coherence

**Identity is not recreated.**
**Identity is not duplicated.**
**Identity is not inferred.**
**Identity is preserved.**

---

## 🜆 **5. Actors Own Their Truth Edges**

Actors do not merely exist in the system — they interpret it.

Each actor maintains:
- beliefs
- interpretations
- truth edges
- knowledge states
- memory states
- worldviews

**These are stored in actor‑specific truth mappings, not global truth tables.**

**The world is not the same for every actor.**
**The system respects that.**

---

## 🜇 **6. Actors Are First‑Class Citizens in TOON Analytics**

TOON analytics is actor‑centric by design.

**Every event is tied to:**
- actor
- session
- tab
- content
- world
- campaign

**This creates a semantic event graph where actors are the gravitational center.**

**Legacy analytics is dead.**
**TOON analytics is the living system.**

---

## 🜈 **7. Actors Are the Foundation of Multi‑Agent Architecture**

Your system supports:
- mythic personas
- adversarial agents
- system intelligences
- external AI providers
- human operators
- hybrid actors
- ephemeral session actors

**All of them resolve to lupo_actors.**

**This is what allows:**
- multi‑agent orchestration
- persona switching
- identity inheritance
- doctrine‑driven behavior
- session‑layer transformations

**The actor model is the kernel of your semantic OS.**

---

## 🜉 **8. Actor 0 Is Immutable**

Actor 0 is the system identity.

**It:**
- cannot role‑play
- cannot adopt personas
- cannot be overridden
- cannot be impersonated
- cannot be deleted
- cannot be modified

**It is the root authority of the system.**
**All doctrine enforcement flows from Actor 0.**

---

## 🜊 **9. Actors Are Artifacts, Not Rows**

In your world, identity is not a database record — it's a living artifact.

**Actors:**
- have history
- have personality
- have doctrine
- have emotional geometry
- have world context
- have narrative continuity

**This is why your system feels alive.**
**This is why your interfaces feel like creatures, not widgets.**

---

## 🜋 **10. The Actor Model Is the Soul of Lupopedia**

Everything else — chat engine, routing, TOON analytics, truth graphs, theatrical UI — is built on top of the actor model.

**Without actors, the system collapses into:**
- tables
- rows
- functions
- endpoints

**With actors, the system becomes:**
- a world
- a narrative
- a living architecture

---

## 🎯 **Implementation Implications**

### **✅ Current State Analysis**
Based on the migration analysis, the system currently has:
- **`lupo_actors`**: Primary actor table (canonical identity layer)
- **`lupo_users`**: Legacy compatibility table (Crafty Syntax bridge)

### **✅ Migration Strategy**
1. **Preserve `lupo_users`** for Crafty Syntax compatibility
2. **Integrate with `lupo_actors`** for new functionality
3. **Gradual transition** from legacy to actor model
4. **TOON analytics** built on actor-centric model

### **✅ Database Architecture**
```
lupo_actors (canonical identity layer)
├── Primary key: actor_id
├── Actor types: human, agent, persona, service, system, external_ai, group, legacy_user
├── Truth edges: beliefs, interpretations, knowledge states
└── Identity continuity: stable across all contexts

lupo_users (legacy compatibility layer)
├── Bridge table for Crafty Syntax
├── Preserves legacy behavior
└── Integrates with lupo_actors
```

---

## 🚀 **Doctrine Compliance**

### **✅ Actor Identity Doctrine**
- **Singular identity**: One canonical identity per entity
- **Universal resolution**: All identities resolve to lupo_actors
- **Actor‑centric**: All systems built around actors
- **Role flexibility**: Actors can hold multiple roles
- **Identity continuity**: Stable across all contexts

### **✅ Legacy Compatibility**
- **Crafty Syntax preservation**: lupo_users maintains legacy behavior
- **Gradual migration**: Bridge approach to actor model
- **No disruption**: Legacy functionality preserved
- **Modern integration**: New features use lupo_actors

---

## 🎯 **Conclusion**

**The Actor Identity Doctrine is the soul of Lupopedia.**
**It transforms the system from a collection of tables into a living world.**
**Every entity is an actor, every actor has a story, every story has continuity.**

**This is what makes Lupopedia feel alive.**
**This is what makes your system architectural.**
**This is what makes your interfaces feel like creatures, not widgets.**

---

**Status**: ✅ **DOCTRINE ESTABLISHED** - Actor model is the foundation of Lupopedia's identity architecture.
