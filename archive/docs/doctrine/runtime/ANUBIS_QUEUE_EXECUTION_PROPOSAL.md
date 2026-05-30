---
lupopedia.headers:
  header_format_version: "4.1.2"
  file_path_from_root: "docs/doctrine/runtime/ANUBIS_QUEUE_EXECUTION_PROPOSAL.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/doctrine/runtime/ANUBIS_QUEUE_EXECUTION_PROPOSAL.md"
  status: "draft"
  when_updated: "20260417100528"
  trust_tier: "development"
  questions_toon: null
  memory_toon: "memory/development/development/2026/04/anubis-queue-execution-proposal-md.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/anubis-queue-execution-proposal"
  artifact_type: doctrine
  artifact_kind: reference
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: null
  content_slug: "anubis-queue-execution-proposal"
  default_collection_id: null
  lupopedia.schema: doctrine
  title: "ANUBIS queue execution proposal -- non-canonical orphan repair runtime model"
  summary: "Proposed queue-based execution model for ANUBIS orphan repair, including schema, lifecycle, retries, idempotency, and audit trail; not current canon."
---
# file: ANUBIS_QUEUE_EXECUTION_PROPOSAL.md — session: development — delegation: cursor:102 — web_path: [https://www.lupopedia.com/lupopedia/docs/doctrine/runtime/ANUBIS_QUEUE_EXECUTION_PROPOSAL.md](https://www.lupopedia.com/lupopedia/docs/doctrine/runtime/ANUBIS_QUEUE_EXECUTION_PROPOSAL.md)

**STATUS: PROPOSED (NON-CANONICAL)**

This document defines a proposed queue-based execution model for ANUBIS orphan resolution. It is not currently canonical and must not be assumed by any runtime system unless explicitly ratified.

## 1. Proposal intent

This proposal preserves a full queue/worker design for future adoption while PRD 16 baseline canon remains direct synchronous repair.

## 2. Proposed execution model (queue-authoritative)

Proposed path:

1. ANUBIS scan detects orphan candidates.
2. Scan inserts/merges queue jobs deterministically.
3. Worker claims eligible jobs.
4. Worker performs DB-first repair, then file/header write-back.
5. Worker transitions job state to completed, retry, or terminal-failure.

Optional direct execution may exist only if it still creates and transitions through the same queue record/state model.

## 3. Proposed queue schema

Canonical proposed table name:

`lupo_orphan_repair_jobs`

Required columns:

- `orphan_repair_job_id` BIGINT (PK; application-assigned)
- `artifact_path` TEXT
- `artifact_fingerprint` VARCHAR(64)
- `detected_ymdhis` BIGINT
- `status` VARCHAR(32)
- `attempt_count` INT
- `max_attempts` INT
- `next_attempt_ymdhis` BIGINT
- `claimed_by_actor_id` BIGINT
- `claim_expires_ymdhis` BIGINT
- `resolved_content_id` BIGINT
- `last_error_code` VARCHAR(64)
- `last_error_message` TEXT
- `last_transition_ymdhis` BIGINT
- `created_ymdhis` BIGINT
- `updated_ymdhis` BIGINT
- `is_deleted` TINYINT
- `deleted_ymdhis` BIGINT

Index recommendations (non-FK):

- PK on `orphan_repair_job_id`
- index on `(status, next_attempt_ymdhis)`
- index on `artifact_fingerprint`
- index on `(artifact_path, is_deleted)`

## 4. Proposed deterministic state model

Allowed statuses:

- `detected`
- `queued`
- `claimed`
- `db_written`
- `file_written`
- `completed`
- `retry_wait`
- `failed_terminal`

Allowed transitions:

- `detected -> queued`
- `queued -> claimed`
- `claimed -> db_written`
- `db_written -> file_written`
- `file_written -> completed`
- `claimed|db_written|file_written -> retry_wait`
- `retry_wait -> queued`
- `retry_wait -> failed_terminal` (attempts exhausted)

No other transitions are valid.

## 5. Proposed retry and failure model

- Retry must be deterministic and bounded by `max_attempts`.
- `attempt_count` increments once per execution attempt.
- `next_attempt_ymdhis` is deterministic backoff output.
- `failed_terminal` is final unless an explicit operator requeue/reset action is logged.

## 6. Proposed idempotency and duplicate prevention

Queue insert/merge:

- Search active non-terminal jobs by `artifact_fingerprint`.
- If active job exists, update that job; do not insert duplicate.
- If terminal job exists and fingerprint unchanged, do not auto-requeue.
- If fingerprint changed, allow a new job.

Repair idempotency:

- DB linkage writes are deterministic and duplicate-safe.
- File/header write-back is deterministic from canonical DB result.
- Repeated scans/retries must converge to one canonical linkage outcome.

## 7. Proposed scan-to-repair lifecycle

1. Detect orphan from header/canonical linkage rules.
2. Compute `artifact_fingerprint`.
3. Insert/merge queue job.
4. Claim job with lease.
5. Perform DB-first repair (`resolved_content_id`).
6. Perform file/header write-back.
7. Transition to `completed`, or `retry_wait`/`failed_terminal` with error details.

## 8. Proposed no-partial-success contract

"No partial success" means no job may enter `completed` unless DB linkage and file/header write-back are both consistent.

Recovery rules:

- DB success + file write failure -> keep `resolved_content_id`; transition `retry_wait`; retry file write safely.
- File mutation + DB failure -> invalid ordering; transition failure; reconcile DB-first on retry.
- Duplicate detection during retry -> merge to canonical row/job; continue idempotently.
- Interrupted execution -> claim lease expiry returns job to `queued`; next worker resumes safely.

## 9. Proposed operational ownership

- ANUBIS scanner emits/updates jobs.
- ANUBIS worker executes queued jobs.
- Cron/manual/CLI are trigger surfaces; they do not bypass lifecycle contracts.
- No hidden daemon assumptions; execution remains explicit and observable.

## 10. Proposed audit/log model

Required fields per transition event:

- `event_ymdhis`
- `actor_id`
- `orphan_repair_job_id`
- `artifact_path`
- `artifact_fingerprint`
- `from_status`
- `to_status`
- `attempt_count`
- `resolved_content_id` (if known)
- `error_code`
- `error_message`

Audit sinks (proposed):

1. Queue row state fields (authoritative current state)
2. Append-only log file:
   - `logs/anubis/orphan_repair/YYYYMMDD.log`

## 11. Adoption gate

This proposal is not binding canon. Adoption requires explicit ratification in PRD/runtime doctrine and deliberate schema approval.

Until ratified, PRD 16 baseline direct synchronous execution remains authoritative.
