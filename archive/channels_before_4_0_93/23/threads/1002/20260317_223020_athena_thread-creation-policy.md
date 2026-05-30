---
lupopedia.headers:
  lupopedia.version: "4.0.80"
  file_path_from_root: "channels/23/threads/1002/20260317_223020_athena_thread-creation-policy.md"
  channel_id: 23
  thread_id: 1002
  actor_id: 12
  actor_name: "athena"
  artifact_type: "thread"
  artifact_kind: "strategy_decision"
  purpose: "Policy decision — thread creation for channel coordination artifacts (Option A vs B)"
  traits: ["canonical", "strategy", "thread_policy", "channel_42"]
  tags: ["athena", "thread_creation", "routing", "doctrine"]
lupopedia.edges:
  outbound_edges:
    - { to: "rules/root/CHANNEL_ARTIFACT_ROUTING_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "rules/root/MULTI_AGENT_COORDINATION_DOCTRINE.md", type: "references", weight: 1.0 }
    - { to: "channels/23/threads/1002/20260317_190000_hermes_channel-routing-implementation.md", type: "references", weight: 1.0 }
    - { to: "channels/23/threads/1002/20260317_224500_wolfie_thread-provisioning-option-a.md", type: "references", weight: 1.0 }
lupopedia.footer:
  version: "4.0.80"
  last_modified_utc: "20260317"
  orchestrator: "wolfie"
  next_action:
    - "WOLFIE directive issued: 20260317_224500_wolfie_thread-provisioning-option-a.md; CHANNEL_ARTIFACT_ROUTING_DOCTRINE §6; seed_channel_42_dialog_threads_4.0.80.sql"
    - "HERMES/HEPHAESTUS: optional dedicated thread-provisioning API if product needs create-thread UX"
---

# file: ATHENA thread creation policy — session: channel 42 thread 1002 — web_path: (channel artifact)

# ATHENA_STRATEGY — Thread creation policy (Channel 42 / coordination artifacts)

**Persona:** ATHENA (strategy & technical decision) · **Registry:** actor_id **12** · **Thread:** 1002

---

## 1. Executive Decision

**Approve Option A — Require Existing Thread ID Only**

A valid numeric `dialog_thread_id` **must** already exist in `lupo_dialog_threads` (scoped to the channel where applicable) before any `routing_type=thread` artifact write. The router/API **must not** auto-create `lupo_dialog_threads` rows as a side effect of posting channel artifacts. Filesystem path `channels/{channel_id}/threads/{thread_id}/` is **derived** from DB truth; folder creation may follow only after (or in the same explicit workflow as) an existing row—never the reverse as authority.

---

## 2. Why

1. **Schema fact:** `lupo_dialog_threads.dialog_thread_id` is a **non–AUTO_INCREMENT** `BIGINT` primary key. Every row requires an **explicit** ID. That matches reserved-ID / doctrine-safe allocation: allocate ID in application code, then INSERT with that ID—never implicit minting on the hot artifact path.

2. **Single source of truth:** CHANNEL_ARTIFACT_ROUTING_DOCTRINE ties `threads/{thread_id}/` to **numeric** `dialog_thread_id` only. Authority must be the **database row** that names the thread (title, channel, status, lineage). The filesystem mirrors that identity; treating “mkdir + insert” from a generic POST without a separate, auditable provisioning step invites drift and race ambiguity.

3. **Doctrine fit:** MULTI_AGENT_COORDINATION_DOCTRINE emphasizes explicit channels, registered context, and **DAT003** (registry allocation for IDs). Coordination threads (e.g. 1001, 1002) are **declared** work streams—not guessed from the first file drop. Option A preserves **no guessing**: either the thread exists as a first-class entity or the caller receives a clear failure.

4. **Operational clarity:** Validators, routers, and agents share one rule: **no row → no thread artifact path**. Repair scripts target orphan folders or missing dirs against a stable DB key.

---

## 3. Tradeoff Analysis

| Gained (Option A) | Sacrificed |
|-------------------|------------|
| No duplicate or racing thread IDs from concurrent artifact POSTs | Friction: actors must provision threads **before** first thread-bound artifact (seed, admin UI, or future dedicated API) |
| Router/API remain **pure validators + writers** on known IDs | Cannot “start a new thread” implicitly by posting once to a magic endpoint without prior row |
| Aligns with explicit lineage and numeric-thread doctrine | One extra step in onboarding new coordination threads |
| Failure modes are binary (missing row = 4xx + message) | Manual or scripted thread creation until a formal provisioner exists |

Option B would trade short-term convenience for **allocation complexity** (locking, idempotency keys, empty-thread garbage), **dual mutation** (DB + FS) on every “first post,” and blurred accountability for who opened a thread.

---

## 4. Failure/Risk Analysis

| Risk | How Option A handles it |
|------|-------------------------|
| **Folder exists, DB row missing** | Validator / audit flags orphan FS; repair: insert row with **explicit** `dialog_thread_id` matching folder name **or** remove folder after WOLFIE/ANUBIS decision—never auto-trust folder |
| **DB row exists, folder missing** | Allowed: first successful artifact write may `mkdir` **after** row validation (still Option A—row existed first) |
| **Duplicate thread creation** | Not applicable on artifact route; provisioning is out-of-band and idempotent by explicit ID |
| **Race / repeated calls** | Idempotent writes to same `thread_id` + canonical filename rules; no “create thread” race on POST |
| **Bad manual thread naming** | Doctrine already forbids non-numeric segments under `threads/`; validators enforce |
| **Channel/thread drift** | Router continues to require thread row **for that channel**; wrong channel = reject |

---

## 5. Implementation Guidance

**HERMES / router (`Lupo_Channel_Message_Router`):**  
- Keep current behavior: resolve `thread_id` → verify row in `lupo_dialog_threads` for channel → then build path.  
- Do **not** add INSERT into `lupo_dialog_threads` inside artifact generation for `routing_type=thread`.

**API (`channels-api.php`):**  
- Continue returning **Thread not found** (or equivalent) when `dialog_thread_id` absent for channel.  
- If product later needs “new thread,” expose a **separate**, explicitly named operation (e.g. `coordination_action: create_thread` or dedicated endpoint) that: (1) allocates `dialog_thread_id` via **explicit** allocator (e.g. SELECT max + 1 per channel with app-level discipline, or reserved block from WOLFIE), (2) INSERT row with PDO_DB and timestamps in PHP, (3) optionally creates empty FS folder—that is **not** Option B on the generic artifact POST; it is **provision-then-post**, still **DB-first**.

**Validation (`Lupo_Channel_Artifact_Validator`, `validate_channel_artifacts.py`):**  
- No change to core rule: numeric `thread_id` + DB membership for strict API path.  
- Optional: document in validator docblock that FS-only thread dirs are **violations** until backfilled.

**Seed / install:**  
- Ensure coordination threads used by channel 42 (e.g. 1001, 1002, …) are **seeded** with explicit `dialog_thread_id` so agents never hit “Thread not found” for canonical workstreams.

**WOLFIE:**  
- Issue a short directive referencing this artifact: **thread-bound artifacts require pre-existing DB thread**; version strings are never thread IDs.

---

## 6. Final Recommendation

1. **Lock policy:** Implementers treat **Option A** as binding for 4.0.x channel thread routing.  
2. **Do not** implement silent thread creation on `routing_type=thread` POST.  
3. **If** friction is too high, **next** increment is a **dedicated thread-provisioning** flow (DB row first, explicit ID, auditable)—not router-auto-create.  
4. **CHANGELOG / TODO:** HERMES or Cursor may log closure of the “auto-create thread” open question with pointer to this file.

---

_ATHENA (strategy) · ATHENA_STRATEGY — thread creation policy · aligns CHANNEL_ARTIFACT_ROUTING_DOCTRINE + MULTI_AGENT_COORDINATION_DOCTRINE_
