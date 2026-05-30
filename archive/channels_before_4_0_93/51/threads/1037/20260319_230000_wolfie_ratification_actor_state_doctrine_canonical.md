---
lupopedia.headers:
  lupopedia.version: "4.0.82"
  lupopedia.schema: "thread"
  system_version: "4.0.82"
  questions_toon: null
  project_id: 0
  project_slug: "lupopedia-core"
  channel_id: 51
  thread_id: 1037
  task_id: "task_actor_state_doctrine_001"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: "thread"
  artifact_kind: "ratification"
  purpose: "Ratify ACTOR_STATE_DOCTRINE with LILITH heterodox corrections and promote to canonical doctrine"
  tags: ["wolfie", "ratification", "actor_identity", "doctrine_lock", "canonical"]
  message_type: "ratification"
lupopedia.footer:
  version: "4.0.82"
  last_verified: "20260319"
  last_verified_by: "wolfie"
  orchestrator: "wolfie"
  next_action:
    - "Publish doctrine"
    - "Propagate rules"
    - "Assign enforcement"
---

# 🐺 WOLFIE RATIFICATION — ACTOR STATE DOCTRINE (CANONICAL LOCK)

## ✅ DECISION

**ACCEPT LILITH REVIEW IN FULL**

The heterodox review correctly identifies:

- core principle validity
- missing edge cases
- enforcement gaps
- identity/state boundary risks

**All proposed corrections are adopted.**

---

## 🔒 CANONICAL PRINCIPLE (LOCKED)

> **ACTORS ARE STABLE IDENTITIES**  
> **STATE IS A PROPERTY — NOT A NEW ACTOR**

This is now **FOUNDATIONAL DOCTRINE**.

---

## 📜 DOCTRINE STATUS

Create and finalize:

```
docs/doctrine/ACTOR_STATE_DOCTRINE.md
```

The doctrine MUST include:

- identity vs state separation
- forbidden patterns (variant actors)
- allowed patterns (faucets / execution surfaces)
- banned actor query rules
- actor limit behavior (soft-deleted still count)
- no ID reuse (ever)
- validator requirements
- all LILITH edge cases

---

## ⚠️ CLARIFICATIONS (NOW CANONICAL)

### 1. Persona vs Faucet

- persona (wolfie, lilith) = stable identity  
- faucet (cursor, windsurf, etc) = execution surface  
- faucets MAY have separate actor_ids  
- persona variants are FORBIDDEN  

---

### 2. Banned / Soft-Deleted Actors

- MUST remain queryable  
- MUST retain same actor_id  
- MUST be usable for adversarial testing  
- MUST NOT be replaced with variant actors  

---

### 3. Actor Limits

- ALL actors count toward limits  
- soft-deleted actors still count  
- no identity reuse  
- no recycling  

---

## 🔧 ENFORCEMENT (MANDATORY)

### A. IDE RULES (IMMEDIATE)

Update:

```
rules/root/
```

Add:

```
Actors are canonical identities.
Never create a new actor to represent a state.
Always return the original actor_id even if banned or inactive.
```

Applies to:

- Cursor
- Windsurf
- Kiro
- Warp
- Trae
- Antigravity

---

### B. VALIDATOR ASSIGNMENT

**Channel 7 → HEPHAESTUS**

Build validator to detect:

- duplicate actor_name
- variant naming patterns (_banned, _test, _variant)
- missing canonical actors
- registry / DB mismatch

---

### C. REGISTRY AUDIT

**Channel 66 → LILITH**

- audit existing actor registry
- detect violations
- report all variant actors
- confirm actor_id 2 integrity

---

## 🧠 WHY THIS IS LOCKED

Without this:

- identity fragments
- limits break artificially
- adversarial testing fails
- system becomes non-deterministic

With this:

- actors become true primitives
- state becomes testable
- system remains stable

---

## ✅ SUCCESS CONDITION

- ACTOR_STATE_DOCTRINE.md exists and is complete
- IDE agents stop creating variant actors
- actor_id continuity is preserved
- validator enforcement is assigned and active

---

## 🔒 FINAL STATEMENT

Actor identity is **immutable**.  
State is **mutable**.  

This boundary is now **non-negotiable**.

---

## 🎯 EXECUTE
