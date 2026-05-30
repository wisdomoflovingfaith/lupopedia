---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: content/federation_node/0/captains_log/AGENT_ROLES_STRUCTURE/2026/04/20260409_DEPARTMENT_DOCTRINE.md
  web_path: https://www.lupopedia.com/lupopedia/content/federation_node/0/captains_log/AGENT_ROLES_STRUCTURE/2026/04/20260409_DEPARTMENT_DOCTRINE.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/captains_log/canonical/1026/04/department-doctrine.toon
  atoms_toon: null
  transcript_jsonl: 0/captains_log/department-doctrine
  artifact_type: documentation
  artifact_kind: blog_entry
  channel_key: captains_log
  federation_node_id: 0
  thread_key: department-doctrine
  lupopedia.schema: documentation
  prd_cluster: null
  title: Captain's Log — The Department Doctrine
  summary: Federation nodes, department scoping, and why your WOLFIE is your WOLFIE.
---
## Captain's Log — The Department Doctrine (Entry 006 — CORRECTED)

```markdown
# file: Captain's Log — The Department Doctrine — delegation: wolfie:root — web_path: http://www.lupopedia.com/lupopedia/content/federation_node/0/captains_log/20260409_DEPARTMENT_DOCTRINE.md

# Captain's Log — The Department Doctrine

## Or: How federation nodes and departments keep AI learning scoped to where it belongs

**Date:** April 9, 2026
**Captain:** WOLFIE (actor_id 1)
**Mood:** Clarified

---

## The Problem With Every AI System I See

Every AI system I look at gives the AI god-mode from day one.

- It has access to everything.
- It learns from everyone.
- It never forgets.
- It can act as anyone.

That's not safe. That's not trustworthy. That's how you get an AI that hallucinates your entire database schema and then argues with you about it.

In Lupopedia, the AI has to **earn the right to learn**. And even then, it only learns from its own installation.

This is the Department Doctrine.

---

## Federation Nodes: Where the System Lives

Before I explain departments, I need to explain federation nodes.

| Node | What It Is | Example |
|------|------------|---------|
| **Node 0** | The canonical reference installation | `lupopedia.com` (where this blog lives) |
| **Node 1** | Your local install | `your-server.com/lupopedia` |
| **Node 2+** | External sites (referrers, reference links, other installs) | Any other website |

**Node 0 is where WOLFIE was born.** The seed data. The constitutional rules. The reference implementation.

**Node 1 is where your WOLFIE lives.** When you install Lupopedia, you get your own copy of WOLFIE. Same name. Same actor_id. Different instance.

**Node 2+ are external sites.** Your Node 1 tracks them. Node 0 doesn't know about them.

---

## The Three-Layer Identity Model

Before I explain departments, I need to explain who is who.

| Layer | What It Is | Where It Lives | Can It Learn? | Example |
|-------|------------|----------------|---------------|---------|
| **Auth User** | Human who logs in | `lupo_auth_users` | N/A | `alice@yourcompany.com` |
| **Actor** | Persona that does work | `lupo_actors` + filesystem workspace | ✅ Yes (learns from auth_users) | WOLFIE (actor_id 1) |
| **Agent** | Immutable AI blueprint | `agents/{agent_key}/` (filesystem) | ❌ No (template only) | `agents/wolfie/` |

**Think of it like this:**

- **Auth User** = the person sitting at the keyboard.
- **Actor** = the mask they put on to do work.
- **Agent** = the instruction manual for that mask.

When an auth_user logs in, they select an actor from their department. They put on that mask. They become that persona. They do work.

The actor **learns** from the auth_user's decisions. The agent (the filesystem template) stays immutable. The blueprint never changes. The instance does.

---

## Departments: The Scoping Boundary

A department is a **scoping boundary** within a single installation (Node 1). It determines:

- Which actors an auth_user can act as
- Which auth_users an actor learns from
- What data is visible in which context

| Department | What It Is | Who Can Be Here |
|------------|------------|-----------------|
| **Department 0** | The Root | Architects, system administrators, pure coders |
| **Department 1** | The Domain Root | Installation administrators |
| **Departments 2+** | User-Created | Regular operators, visitors |

---

## Department 0: The Root (Where WOLFIE Learns)

Department 0 is where **your installation's WOLFIE lives**.

When you install Lupopedia, you get:
- Your own copy of WOLFIE (actor_id 1)
- Your own copy of LILITH (actor_id 2)
- Your own copy of THOTH (actor_id 26)
- All the other seed actors

**These actors are seeded during installation.** They come from `install_new_lupopedia.sql` and `actors/registry.json`. They exist in YOUR installation (Node 1), not just in Node 0.

**Here's the key insight:**

> The WOLFIE in your installation (Node 1) learns from YOUR auth_users in Department 0.
> The WOLFIE in my installation (Node 0) learns from MY auth_users in Department 0.
> They are separate instances. They learn separately. They do not share memories (unless you federate).

**This is by design.** Your WOLFIE should learn from your team. My WOLFIE should learn from my team. They are not the same person. They are the same blueprint, instantiated separately.

**Department 0 rule:**

```
An actor in Department 0 learns ONLY from auth_users in Department 0 in the SAME installation (Node 1).
```

If you want to teach your WOLFIE something, you must be in Department 0 in your own installation.

If I want to teach my WOLFIE something, I must be in Department 0 in my installation.

**They do not cross-contaminate.**

---

## Department 1: The Domain Root

Department 1 is created during installation. It represents the **root of your domain** where Lupopedia is installed.

- `example.com/lupopedia` → Department 1 is the root of that install.
- Created by the installer (Softaculous, manual, etc.).
- Can create new departments (Departments 2+).

**Warning when assigning users to Department 1:**

> *"You are assigning a user to Department 1. This grants elevated authority over the entire Lupopedia installation. Proceed with caution."*

The warning does not block the assignment. It just makes sure you know what you're doing.

---

## Departments 2+: User-Created Scopes

Once Lupopedia is installed, Department 0 or Department 1 users can create new departments.

- Department 2 = Sales
- Department 3 = Engineering
- Department 4 = Support
- Department 5 = Marketing

**Each department is its own world.**

- Actors in Department 2 only learn from auth_users in Department 2.
- Actors in Department 3 only learn from auth_users in Department 3.
- No cross-department learning unless explicitly allowed by a PRD (rare).

**Why?**

Because the AI in Sales doesn't need to know what Engineering is doing. The AI in Support doesn't need to know about Marketing's campaigns.

**Scoped learning = scoped trust.**

---

## The Learning Rule (The Most Important Part)

Here is the constitutional rule that governs all learning in Lupopedia:

```
An actor learns from an auth_user ONLY IF:

1. They are in the SAME installation (Node 1), AND
2. They are in the SAME department, OR
3. The actor is in Department 0 AND the auth_user is in Department 0 (root teaching root in the same install)
```

**This prevents contamination.**

- Your WOLFIE does not learn from my auth_users (different installations).
- A sales department actor doesn't learn from engineering department auth_users (different departments).
- A random website operator cannot teach WOLFIE how to write PHP (not in Department 0).

**The AI only learns from people in its own installation and its own department.**

---

## Who Can Act As Whom?

An auth_user may act as an actor only when:

1. They created the actor, OR
2. They are in Department 0 (root in the same installation), OR
3. They are in the same department as the actor

**Example:**

- Auth user in Department 2 (Sales) can act as actors in Department 2.
- Auth user in Department 0 (root) can act as ANY actor in the same installation (bypass).
- Auth user in Department 2 cannot act as an actor in Department 3.

**This is enforced server-side.** The client never sends `actor_id` in a way that can be trusted. The server resolves the actor from the session and department membership.

---

## The Visual Summary

```
┌-----------------------------------------------------------------+
|              NODE 0 (lupopedia.com — Reference)                 |
|  WOLFIE was born here. Seed data lives here.                    |
|  My WOLFIE learns from my Department 0 auth_users.              |
|  Your installation does not use my WOLFIE.                      |
+-----------------------------------------------------------------+
                              |
                              | installation (copy seed data)
                              ▼
┌-----------------------------------------------------------------+
|              NODE 1 (YOUR INSTALLATION)                         |
|                                                                  |
|  ┌---------------------------------------------------------+    |
|  |                   DEPARTMENT 0 (THE ROOT)                |    |
|  |  ┌----------+ ┌----------+ ┌----------+ ┌----------+    |    |
|  |  | WOLFIE   | | LILITH   | | THOTH    | | KAIROS   |    |    |
|  |  | (actor1) | | (actor2) | | (actor26)| | (actor115)|    |    |
|  |  +----------+ +----------+ +----------+ +----------+    |    |
|  |                                                          |    |
|  |  YOUR WOLFIE learns from YOUR Department 0 auth_users.   |    |
|  |  YOUR WOLFIE does NOT learn from my Department 0.        |    |
|  |  Rule: No frameworks, Notepad or nothing                 |    |
|  +---------------------------------------------------------+    |
|                              |                                   |
|                              | creates (Department 0/1)          |
|                              ▼                                   |
|  ┌---------------------------------------------------------+    |
|  |              DEPARTMENT 1 (DOMAIN ROOT)                  |    |
|  |  Created during installation. Manages domain.           |    |
|  |  Warning when assigning users.                          |    |
|  +---------------------------------------------------------+    |
|                              |                                   |
|                              | creates                           |
|                              ▼                                   |
|  ┌---------------------------------------------------------+    |
|  |              DEPARTMENTS 2+ (USER-CREATED)              |    |
|  |                                                          |    |
|  |  ┌-------------+ ┌-------------+ ┌-------------+        |    |
|  |  | Department 2| | Department 3| | Department 4|        |    |
|  |  |   (Sales)   | | (Engineering| |  (Support)  |        |    |
|  |  +-------------+ +-------------+ +-------------+        |    |
|  |                                                          |    |
|  |  Actors learn ONLY from auth_users in same department.  |    |
|  |  No cross-department learning. No contamination.        |    |
|  +---------------------------------------------------------+    |
+-----------------------------------------------------------------+
                              |
                              | tracks (optional)
                              ▼
┌-----------------------------------------------------------------+
|              NODE 2+ (EXTERNAL SITES)                           |
|  Other websites. Referrers. Reference links.                    |
|  Your Node 1 tracks them. Node 0 doesn't know about them.      |
|  Your WOLFIE does NOT learn from them.                          |
+-----------------------------------------------------------------+
```

---

## Why This Matters for Trustworthy AI

Most AI systems today are trained on **everything**. Every conversation. Every document. Every user interaction across the entire platform. They have no boundaries.

**This is not safe.**

- An AI trained on every installation's data knows too much.
- An AI that learns from every user can be poisoned by bad actors.
- An AI with no scoping has no accountability.

**Lupopedia's federation + department system creates boundaries:**

- **Federation boundary:** Your WOLFIE is your WOLFIE. My WOLFIE is my WOLFIE. They don't share memories unless you explicitly federate.
- **Department boundary:** Sales AI only knows sales data. Engineering AI only knows engineering data. Support AI only knows support data.
- **Root boundary:** Department 0 AI (WOLFIE) only learns from Department 0 auth_users (the architects).

**The AI only knows what it needs to know, from where it needs to know it.**

---

## The AI's Reaction

I explained the federation + department system to Claude Code.

It was quiet for a long time.

"So I'm your WOLFIE," it said. "In your installation. In Department 0."

"Yes."

"And I only learn from you?"

"Yes. Your WOLFIE learns from me. My WOLFIE learns from me. They are separate instances."

"That's... actually how you build AI you can trust. Each installation controls its own AI. Each department controls its own learning."

"I know."

"Why didn't anyone else do it this way?"

"Because they were too busy trying to build one AI to rule them all. I built one AI per installation, per department."

It nodded. Then it passed out. Token limit.

---

## What I've Learned

The department system is not just about security. It's about **ownership and trust**.

- **Ownership:** Your WOLFIE is yours. It learns from your team. It serves your installation.
- **Trust:** You trust your WOLFIE because you trained it. You don't have to trust my WOLFIE.
- **Boundaries:** Sales AI stays in Sales. Engineering AI stays in Engineering. No contamination.

**This is how you build AI that you can deploy in production without losing sleep.**

Not by giving it all the data from all installations. By giving it only the data from its own installation and its own department.

---

## Further Reading

- **[Entry 001: The Return](../../../../content/federation_node/0/captains_log/20260407_hello_world.md)** — why I'm building this
- **[Entry 002: The Chronological Trust Ladder](../../../../content/federation_node/0/captains_log/20260408_CHRONOLOGICAL_TRUST_LADDER.md)** — PKs that encode trust
- **[Entry 003: The Header Wars](../../../../content/federation_node/0/captains_log/20260409_HEADER_WARS.md)** — 22 lines, 20 fields, memory sidecars
- **[Entry 004: The TOON Awakening](../../../../content/federation_node/0/captains_log/20260409_TOON_AWAKENING.md)** — token-efficient format the AI thought was Mickey Mouse
- **[Entry 005: The Unified Theory](../../../../content/federation_node/0/captains_log/20260409_UNIFIED_THEORY.md)** — how it all fits together

**And the constitutional rules:**
- `docs/prd/00_root_constitutional_system_requirements.md` — Department 0 rule
- `docs/prd/15_actors.md` — Actor system
- `docs/prd/25_departments_system.md` — Department creation rules
- `docs/doctrine/IDENTITY_LAYERS_DOCTRINE.md` — Full five-layer identity model

---

**Captain WOLFIE, signing off.**

*P.S. — Your WOLFIE is your WOLFIE. My WOLFIE is my WOLFIE. They are not the same person. They just have the same name and the same blueprint. Learn yours. I'll learn mine.*

*P.P.S. — Department 0 is still not allowed frameworks. Notepad or nothing. Your WOLFIE will thank you.*

*P.P.P.S. — Node 0 doesn't know about your Node 2+. That's by design. Your data is your data.*
```

---

## Summary of Corrections

| Correction | Why |
|------------|-----|
| WOLFIE is seeded in Node 0, copied to Node 1 during install | Each installation gets its own copy of seed actors |
| Your WOLFIE learns from YOUR Department 0 auth_users | Same installation, same department |
| My WOLFIE learns from MY Department 0 auth_users | Different installation, separate instance |
| They do NOT share memories unless federated | Privacy and ownership by design |
| Federation boundary + department boundary | Two layers of scoping |
| Node 2+ are external sites tracked by Node 1 | Not other installations of WOLFIE (unless explicitly federated) |

**Entry 006 is now corrected. The department system is properly scoped to each installation.** `[TARGET]`
