---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "docs/doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md"
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: identity
  channel_key: null
  federation_node_id: null
  thread_id: "actor-department-auth-user"
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: doctrine
  title: ""
  summary: ""
---
# file: ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE — delegation: cursor:root — web_path: http://www.lupopedia.com/lupopedia/docs/doctrine/ACTOR_DEPARTMENT_AUTH_USER_DOCTRINE.md

# Actor, Department, and Auth User Doctrine

**Audience:** Orchestrators and IDE agents documenting or implementing chat, act-as, or import.  
**Status:** **Canonical** single-page mental model for **`auth_user` + `department` + `actor`** joins (LILITH audit: accurate, constitutionally sound). PRDs **02, 05, 07, 13, 15, 18, 25, 32** reference this file and must not contradict it.  
**Companion:** Full five-layer stack remains **[IDENTITY_LAYERS_DOCTRINE.md](IDENTITY_LAYERS_DOCTRINE.md)** (adds Agent and Faucet). This document **narrows** to the three storage layers that control **who can act as whom** and **how visitors see chat identity**.

---

## 1. Three core concepts (tables)

| Concept | Meaning | Primary table | Notes |
|--------|---------|---------------|--------|
| **Auth user** | A **human** account (login, profile, accountability) | `lupo_auth_users` | One row per human user (per install). |
| **Department** | An **organizational** scope (team, queue, routing bucket) | `lupo_departments` | Application-managed hierarchy; no DB foreign keys. |
| **Actor** | An **operational identity** (posts messages, owns channel actions, appears in UI) | `lupo_actors` | One row per **`actor_id`**. **Shared**: many humans may **use** the same actor when policy allows — it is **not** “one actor per human” by default. |

Illustrative ids (verify in **registry / seed** for your environment): WOLFIE `actor_id` 1, LILITH 2, COUNTERMEASURE 111; department 0 = root; other department ids from `lupo_departments`.

---

## 2. Relationships (membership and bindings)

### 2.1 Auth user ↔ Department (many-to-many)

| Table | Role |
|-------|------|
| **`lupo_auth_user_departments`** | Which **humans** belong to which **departments** (optional primary flag per product rules). |

**Meaning:** A human can be in **multiple** departments; a department has **many** humans.

### 2.2 Actor ↔ Department (many-to-many)

| Table | Role |
|-------|------|
| **`lupo_actor_departments`** | Which **actors** are **in scope** for which **departments** (`role_key` e.g. hybrid, system). |

**Meaning:** An actor can serve **multiple** departments; a department has **many** actors. **Actors belong to departments** — they are **not** “owned by” a single `auth_user` as the primary model.

### 2.3 Auth user ↔ Actor (two mechanisms — do not confuse them)

| Mechanism | Table / rule | Role |
|-----------|----------------|------|
| **Eligibility to act as (web and most runtime)** | **Department intersection** | Human may select / post as **`actor_id`** only if **some** `department_id` appears in **both** their `lupo_auth_user_departments` rows **and** the actor’s `lupo_actor_departments` rows (plus bypass rules in **`AuthSessionManager`** / admin — see **PRD 05**). |
| **Explicit binding (import, audit, primary operator)** | **`lupo_actor_auth_users`** | Optional **many-to-many** (with roles / primary flags) linking **`auth_user_id`** to **`actor_id`**. This **does not** replace department intersection for **hybrid / department-scoped** act-as lists; it **supplements** Crafty mapping and accountability. |

**Correction vs informal shorthand:** It is wrong to say there is **no** table between auth user and actor. There **is** **`lupo_actor_auth_users`** — but **4.0.x normative act-as eligibility** is **department-first**, not “only if a binding row exists,” except where product code intentionally narrows (e.g. **`web_restrict_act_as_creator_or_root`**).

### 2.4 Illustrative SQL (department intersection only)

**Not a substitute for application code.** Real act-as lists are built in PHP (**`AuthSessionManager::getActorsUserCanActAs`**, admin bypass, **`web_restrict_act_as_creator_or_root`**, active-actor checks). Use your configured table prefix (`lupo_` or `LUPO_TABLE_PREFIX`). Join rows must respect **`is_deleted = 0`** where those columns exist.

Pedagogical shape:

```sql
SELECT a.*
FROM lupo_actors a
WHERE a.is_deleted = 0
  AND EXISTS (
    SELECT 1
    FROM lupo_actor_departments ad
    INNER JOIN lupo_auth_user_departments ud
      ON ud.department_id = ad.department_id
     AND ud.auth_user_id = :current_auth_user_id
     AND ud.is_deleted = 0
    WHERE ad.actor_id = a.actor_id
      AND ad.is_deleted = 0
  );
```

---

## 3. Diagram (mental model)

```
┌-------------+     ┌--------------------------+     ┌-------------+
|  auth_user  |----▶| lupo_auth_user_departments|----▶| department  |
|  (human)    |     |  (membership)            |     |  (group)    |
+-------------+     +--------------------------+     +-------------+
                                                              |
                                                              |
                                                              ▼
┌-------------+     ┌--------------------------+     ┌-------------+
|   actor     |◀----| lupo_actor_departments   |◀----| department  |
| (identity)  |     |  (membership)            |     | (same rows) |
+-------------+     +--------------------------+     +-------------+

        Optional explicit links: lupo_actor_auth_users (auth_user_id ↔ actor_id)
        Eligibility (typical): ∃ department D : user ∈ D AND actor ∈ D
```

---

## 4. Visitor chat (high level)

1. **Visitor** enters chat; product binds the session to a **department** (or queue) per channel / Crafty-parity rules.  
2. **Candidate actors** are those with **`lupo_actor_departments`** for that department (minus policy exclusions).  
3. **Response path:** optional **LLM / automation** when configured → else **human** `auth_user` in that department → else **queue / offline / leave-message** (see **PRD 05**).  
4. **Attribution:** Visitor-facing label is **`lupo_actors`** (actor name / persona). Stored line identity is **`from_actor_id`** on dialog messages. The **human** behind the session is **not** the primary display key; tie human to request in **session / logs / optional `lupo_actor_auth_users`** — **do not assume** a dedicated `lupo_actor_audit` table unless it exists in **install SQL** for your version.

---

## 5. Contrast with Crafty Syntax (legacy)

| Aspect | Crafty Syntax | Lupopedia |
|--------|----------------|-----------|
| **Primary chat identity** | Operator (human-centric account) | **Actor** (`actor_id`), department-scoped |
| **Humans vs line persona** | Often **1:1** operator ↔ user feel | **Many:1** allowed — many `auth_users` may act as **same** actor |
| **Department** | Operator tied to department | **Actor** tied via **`lupo_actor_departments`** |
| **Fallback chain** | Human-only typical | **LLM → human → queue** (product-dependent) |
| **Transcript attribution** | Operator display | **Actor** display; human in audit/session layer |

---

## 6. Constitutional alignment (summary)

- **No foreign keys:** Relationships enforced in **application** code; joins use explicit keys.  
- **No implicit ownership:** `actor_id` is not “the user’s private row” — **department intersection** + optional **`lupo_actor_auth_users`**.  
- **Explicit IDs and timestamps:** Per root database doctrine; BIGINT UTC in app.  
- **Server-side resolution:** Client must not spoof **`actor_id`** on POST; **`channels-api`** and auth stack resolve effective actor.

---

## 7. One-sentence summary

**Auth users (humans) and actors (operational identities) both attach to departments via join tables; a human may act as an actor when they share at least one department (with explicit bindings and bypass rules as coded); visitors chat with **actors**, not with raw auth-user accounts.**

---

## 8. Related PRDs (normative detail)

- **PRD 02** — Channels / threads / messages; **`actor_id`** (or **`from_actor_id`**) as line identity  
- **PRD 05** — Visitor chain, act-as, `AuthSessionManager`  
- **PRD 15** — Actor definition, workspace, department-first model  
- **PRD 25** — Department structure, visitor routing context  
- **PRD 13** — Crafty import, operator → actor  
- **PRD 18** — Chat UI, **`actor_id`** primary attribution  
- **PRD 32** — Approval authority vs **operational** department scope  
- **PRD 07** — **Agent** (filesystem template) vs **Actor** (DB runtime); actor carries **`lupo_actor_departments`**  

This output complies with Lupopedia Constitutional Root Rules.
