# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\FLIP\FLP_ESCROW_AND_FUND_LAYER.md"
  file_hash: "4b2e60c8cfe2e623dba47d88e6b0c3455be8769467d9e4fc48e1afa0460f7227"
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
  file_path_from_root: "docs\doctrine\FLIP\FLP_ESCROW_AND_FUND_LAYER.md"
  file_hash: "e4add8dacaa3a53dd966c6ae3d6097371d056464e96440cab8a421c09aadfadc"
  file_path_from_root: "docs\doctrine\FLIP\FLP_ESCROW_AND_FUND_LAYER.md"
  file_hash: "209ce63465e9cc00e77265ef0ea248ab57d73a6f9ebaa839fc20e7761c16e539"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for FLP_ESCROW_AND_FUND_LAYER.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "flip", "flp_escrow_and_fund_layermd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/FLIP/FLP_ESCROW_AND_FUND_LAYER.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "00000000000000"
# channel_id unresolved — requires lupo_contents lookup by application.
X-Lupo-Channel: 42   # ANUBIS adoption channel (Auto-Fixed)
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/FLIP/FLP_ESCROW_AND_FUND_LAYER.md
---
# FLP — Escrow and Fund Layer

**Status:** Permanent. Documentation only.  
**Audience:** All AI agents (including Cursor), contributors, and system stewards.  
**Related:** [FLP_COUNCILS_AS_CHANNELS.md](FLP_COUNCILS_AS_CHANNELS.md), [FLP_DOCTRINE_BOUNDARIES.md](FLP_DOCTRINE_BOUNDARIES.md).

---

## 1. Escrow / fund as channels and application-level logs

Financial or allocation concepts in the FLP (escrow, funds, allocations) are represented using:

- **Channels** — A fund or escrow context may be modeled as a Lupopedia channel (or as metadata/content under a channel), so that identity, membership, and lifecycle follow the same pattern as councils.
- **Application-level logs** — All events (pledges, allocations, releases, disputes) are recorded in application-level logs. Each event is written explicitly by the application with a timestamp and payload. No database automation generates or updates these records.

---

## 2. No automatic releases, no triggers, no DB automation

- **No triggers** — The database does not automatically release escrow or update fund state when conditions are met. Any “release” or state transition is performed by application code that reads current state, decides, and then writes the new state or log entry.
- **No stored procedures or functions** — Escrow and fund logic (eligibility, release rules, caps) is implemented in application code, not in the database.
- **No scheduled or event-driven DB jobs** — Any time-based or event-based behavior is implemented in the application (or external job runner), not as database events or cron-like DB objects.

---

## 3. Timestamps written by application code

All timestamped events in the escrow/fund layer use **BIGINT(14) UTC in YYYYMMDDHHIISS format**, written explicitly by application code. The database must not supply default timestamps or ON UPDATE behavior for these fields. Doctrine: all timestamps are application-written; see FLP_DOCTRINE_BOUNDARIES.md and the project’s database logic prohibition doctrine.

---

*End of FLP escrow and fund layer. No schema, no SQL, no implementation in this document.*
