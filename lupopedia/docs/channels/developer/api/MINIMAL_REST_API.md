# Lupopedia Minimal REST API (Canonical Draft)

**Version:** 4.1.4  
**Doctrine:** stateless, UTC-driven, no triggers, no FK, BIGINT IDs, artifact-first, minimal.

Baseline REST surface for actors, artifacts, and subsystems. Ancestral layer—extend as needed.

---

## Endpoints

| Method | Path | Purpose |
|--------|------|---------|
| GET | `/api/v1/health` | Health / readiness |
| POST | `/api/v1/actor/handshake` | Actor handshake — identity, timeline, constraints |
| POST | `/api/v1/artifact` | Register artifact |
| GET | `/api/v1/artifact?artifact_id=` | Retrieve artifact |
| GET | `/api/v1/timeline?utc_day=YYYYMMDD` | Artifacts for a UTC day |
| GET | `/api/v1/actor/state?actor_id=` | Actor state snapshot (last handshake) |
| GET | `/api/v1/governance/branch-budget` | Branch budget and thaw version (Version-Gated Branch Freeze) |

---

## 1. Health

**GET /api/v1/health**

```json
{ "status": "ok", "utc_timestamp": "YYYYMMDDHHMMSS" }
```

---

## 2. Actor Handshake

**POST /api/v1/actor/handshake**

**Request:**
```json
{
  "actor_id": "string-or-bigint",
  "actor_type": "human | ai | system",
  "utc_timestamp": "YYYYMMDDHHMMSS",
  "purpose": "string",
  "constraints": ["string"],
  "forbidden_actions": ["string"],
  "context": "string",
  "expires_utc": "YYYYMMDDHHMMSS"
}
```

**Response:**
```json
{ "status": "accepted", "session_id": 1, "expires_utc": "YYYYMMDDHHMMSS" }
```

---

## 3. Artifact Registry

**POST /api/v1/artifact**

**Request:**
```json
{
  "actor_id": 1,
  "utc_timestamp": "YYYYMMDDHHMMSS",
  "type": "dialog | changelog | schema | lore | humor | protocol | fork_justification",
  "content": "string"
}
```

For `type: "fork_justification"`, `content` MUST be JSON with `reason`, `blockedby` (artifact_id of blocking artifact), `proposed_branch`. Optional when approved by human steward: `approved`, `approved_by_actor_id`, `approved_utc`. See `docs/doctrine/VERSION_GATED_BRANCH_FREEZE_PROTOCOL.md`.

**Response:**
```json
{ "status": "stored", "artifact_id": 1 }
```

**GET /api/v1/artifact?artifact_id=1**

**Response:**
```json
{
  "artifact_id": 1,
  "utc_timestamp": "YYYYMMDDHHMMSS",
  "type": "dialog",
  "content": "string"
}
```

---

## 3b. Branch Budget (Governance)

**GET /api/v1/governance/branch-budget**

Returns branch budget and thaw version for the Version-Gated Branch Freeze Protocol.

```json
{ "branch_budget": 0, "thaw_version": "4.2.0", "utc_timestamp": "YYYYMMDDHHMMSS" }
```

Until `thaw_version` (4.2.0), `branch_budget` is 0. Doctrine: `VERSION_GATED_BRANCH_FREEZE_PROTOCOL`.

---

## 4. Timeline

**GET /api/v1/timeline?utc_day=YYYYMMDD**

**Response:**
```json
{
  "utc_day": "20260119",
  "artifacts": [
    { "artifact_id": 1, "type": "dialog", "utc_timestamp": "20260119120000" }
  ]
}
```

---

## 5. Actor State

**GET /api/v1/actor/state?actor_id=1**

**Response:**
```json
{
  "actor_id": 1,
  "actor_type": "human",
  "lasthandshakeutc": "YYYYMMDDHHMMSS",
  "purpose": "string",
  "constraints": ["string"],
  "forbidden_actions": ["string"]
}
```

---

## Tables (migration 4.1.4)

- **lupo_artifacts** — `artifact_id`, `actor_id`, `utc_timestamp`, `type`, `content`, `created_ymdhis`, `is_deleted`, `deleted_ymdhis`
- **lupo_actor_handshakes** — `handshake_id`, `actor_id`, `actor_type`, `utc_timestamp`, `purpose`, `constraints_json`, `forbidden_actions_json`, `context`, `expires_utc`, `created_ymdhis`, `is_deleted`, `deleted_ymdhis`

No foreign keys. No triggers. BIGINT where appropriate.

---

## Run order

1. Run `database/migrations/4.1.4_lupopedia_minimal_rest_api_tables.sql` (if below table ceiling or after reduction).
2. Run `database/migrations/4.1.5_add_fork_justification_artifact_type.sql` (adds `fork_justification` to artifact type comment).
