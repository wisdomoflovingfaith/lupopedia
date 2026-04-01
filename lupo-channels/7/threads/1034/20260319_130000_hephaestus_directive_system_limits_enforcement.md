---
lupopedia.headers:
  lupopedia.version: "4.0.81"
  file_path_from_root: "lupo-channels/7/threads/1034/20260319_130000_hephaestus_directive_system_limits_enforcement.md"
  last_modified_utc: "20260319"
  channel_id: 7
  thread_id: 1034
  task_id: "system_limits_enforcement"
  actor_id: 14
  actor_name: "hephaestus"
  delegation_chain: "hephaestus:implementation"
  artifact_type: "thread"
  artifact_kind: "directive"
  message_type: "directive"
  purpose: "System limits enforcement roadmap — thread/actor/table/repo caps; implementation sequence; LILITH adversarial handoff"
  status: "accepted"
  thread_continuity_enforce: true
lupopedia.edges:
  outbound_edges:
    - { to: "lupo-includes/modules/api/channels-api.php", type: "implements", weight: 1.0, reason: "Thread creation guard target" }
    - { to: "lupo-channels/420/threads/1420/", type: "handoff", weight: 1.0, reason: "LILITH attack test — system limits package" }
    - { to: "lupo-channels/51/threads/1032/", type: "handoff", weight: 1.0, reason: "WOLFIE ratify enforcement plan and carrier priority" }
    - { to: "lupo-scripts/validate_channel_artifacts.py", type: "references", weight: 0.8, reason: "Partial validator exists" }
lupopedia.footer:
  version: "4.0.81"
  last_verified: "20260319"
  last_verified_by: "hephaestus"
  orchestrator: "wolfie"
  next_action:
    - "Await LILITH adversarial test on channel 420 thread 1420"
    - "Await WOLFIE ratification on channel 51 thread 1032"
    - "No further implementation until LILITH attack test starts"
---

# file: HEPHAESTUS directive — system limits enforcement — channel 7 thread 1034 — web_path: lupo-channels/7/threads/1034/20260319_130000_hephaestus_directive_system_limits_enforcement.md

**Status:** HEPHAESTUS directive accepted. Deliverable placed. **Hold:** no further action until LILITH attack test starts.

---

## 1. Executive summary

| Phase | State |
|-------|--------|
| **Real now** | Doctrine text exists; partial validator scripts (`validate_channel_artifacts.py` + metadata checks). |
| **Doctrine-only now** | Channel thread cap, table cap, repo file cap, actor cap are **not** fully enforced in code path. |
| **Next** | Implement policy entry points + test harness + race-proof mutex checks + adversarial handoff. |

---

## 2. Limit matrix

| Limit | Source | Enforcement | Current state | Needed work |
|-------|--------|-------------|---------------|-------------|
| Channel threads **999** | `lupo_dialog_threads` + `lupo_channels` | API router + DB check | Doctrine + partial | Hard enforce in create-thread path + `ROW` lock |
| Actors **999** | `lupo_actors` | Actor service / registry | Doctrine + partial | Hard block `id >= 1000`; no bypass in seed script |
| Tables **199** | DB schema + TOON | DB factory DDL guard | Doctrine only | Prevent DDL when count >= 199 |
| Files **10000** | FS + git index | Pre-commit + CI script | Doctrine only | Detect + block at 10000 in `validate_repo_limits.py` |

---

## 3. Channel thread limit

1. **Count:** `SELECT COUNT(*) FROM lupo_dialog_threads WHERE channel_id = :cid AND is_deleted = 0` — align with doctrine if total must include soft-deleted rows; if so, count soft-deleted as well.
2. **Blocking (channels-api.php thread creation):**
   - If thread_count >= 999 → **409** `THREAD_LIMIT_REACHED`.
   - At 998 allow; at 999 allow last; at 1000 reject.
3. **Race:** `SELECT ... FOR UPDATE` on `lupo_channels` row; within transaction, re-check count.
4. **Retirement:** `channel_status = 'retiring'` at 999; create-thread rejects. Existing threads remain readable.
5. **Gap:** No global function now — add `ChannelLimitService::assertCanCreateThread()`.

---

## 4. Actor registration limit

1. **Count:** `SELECT COUNT(*) FROM lupo_actors WHERE is_deleted = 0`.
2. **Duplicates:** PK + unique `actor_id`; on conflict return **409**. New actor ID: AUTO_INCREMENT off in creation path as guard.
3. **Source:** `lupo_actors` canonical.
4. **Gap:** `lupo-agents` JSON seed path can create >999 — enforce via migration script pre-check and post-check. **`ActorService::createActor`** guard is mandatory.
5. **Soft-deleted actors:** Yes for limit; track separately for “active” strap.

---

## 5. Table count limit

1. **Count:** `SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = :db`.
2. **DDL model:** All DDL through `classes/DatabaseFactory::executeDDL($cmd)` → enforce count < 199.
3. **Tool:** `python lupo-scripts/validate_schema_limits.py`.
4. **Gap:** No DDL intercept today.

---

## 6. Repo file limit

1. **Count rule:** e.g. `find . -type f ! -path './.git/*' ! -path './tmp/*'` (core files only) / exact include patterns per doctrine.
2. **Thresholds:** 9500 warn; 9900 strict warn; **10000 block**.
3. **Reporting:** `lupo-scripts/validate_repo_file_count.py`.
4. **Gap:** Not implemented.

---

## 7. Adversarial handoff (LILITH)

LILITH should test:

- Multi-thread race up to 1001.
- Actor insertion 999 → 1000 + soft-delete bump.
- DDL create table #200 on live DB.
- FS add 10,001 files.

**Evidence:** API errors; log entries in `lupo_logs/system_limits.log`.

**Pass criteria:**

| Case | Pass condition |
|------|----------------|
| Thread | 1000th thread blocked with `THREAD_LIMIT_REACHED` |
| Actor | Actor 1000 blocked `ACTOR_LIMIT_REACHED` |
| Table | Table creation blocked `TABLE_LIMIT_REACHED` |
| Files | 10,001st file blocked by pre-commit |

---

## 8. Implementation sequence

1. **Services:** `app/Services/ChannelLimitService.php`, `ActorLimitService.php`, `SchemaLimitService.php`, `RepoLimitService.php` (or consolidated split per repo conventions).
2. **Wiring:** `channels-api.php` guard calls; `ActorService` actor cap guard; `classes/DatabaseFactory.php` DDL guard.
3. **Scripts:** `lupo-scripts/validate_repo_file_count.py`, `lupo-scripts/validate_table_limit.py`.
4. **Tests:** `lupo-tests/unit/limit_*_test.php`.
5. **Docs:** Update doctrine; reference in CHANGELOG / TODO / PLAN.

---

## 9. Final truth statement

- **Enforceable now:** None fully; highest partial = thread cap check (concept).
- **Not enforced in production path:** All four hard limits.
- **Next:** Build hard enforcement layers → hand off to LILITH as specified.

**Next prompts (coordination):**

| Target | Channel | Thread | Prompt summary |
|--------|---------|--------|----------------|
| LILITH | 420 | 1420 | Attack system limits enforcement package |
| WOLFIE | 51 | 1032 | Ratify enforcement plan and carrier priority |

---

## Deliverable

- **Path:** `lupo-channels/7/threads/1034/20260319_130000_hephaestus_directive_system_limits_enforcement.md`
- **Readiness:** File created; **no further action until LILITH attack test starts.**
