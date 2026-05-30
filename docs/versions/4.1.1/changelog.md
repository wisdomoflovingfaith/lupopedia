---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/versions/4.1.1/changelog.md
  web_path: https://www.lupopedia.com/lupopedia/docs/versions/4.1.1/changelog.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/version-4-1-1-changelog.toon
  atoms_toon: null
  transcript_jsonl: 0/development/4_1_1_stabilization
  artifact_type: changelog
  artifact_kind: version_specific
  channel_key: development
  federation_node_id: 0
  thread_key: ''
  lupopedia.schema: changelog
  prd_cluster: null
  title: Version 4.1.1 stabilization changelog
  summary: 'Stabilization-only PRD 16 refinements: strict-vs-standard envelope, header authority, boundaries, ANUBIS contract, migration cutoff, and naming separation.'
---
# Version 4.1.1 Changelog

## Entry
- **WHO:** Cursor IDE Agent
- **UTC (BIGINT):** `20260415145743`
- **WHAT:**
  - Relaxed formatting rigidity in PRD 16 by introducing standard mode vs strict envelope mode while keeping canonical 22-field order required.
  - Switched dual-field interpretation to header-authoritative; sidecar is derived/synchronized.
  - Added explicit Header Responsibility Boundaries section (key ring, not computation layer).
  - Added ANUBIS Operational Contract (idempotency, retries, failure modes, deterministic orphan handling, concurrency controls).
  - Added Migration Cutoff Policy (`pk_*` removal in 4.1.3, `dialog_transcript` removal in 4.1.5, canonical-only at 4.2.0) with no Lupopedia-to-Lupopedia upgrades before 4.2.0.
  - Added File Naming Doctrine Separation (docs/memory normalized; PHP runtime naming exempt until loader-safe validation).
- **WHY:** Reduce operational fragility, preserve doctrine architecture, and make validator/agent behavior enforceable during 4.1.1 stabilization leading to 4.2.0 readiness.

## Entry
- **WHO:** Cursor IDE Agent
- **UTC (BIGINT):** `20260415162356`
- **WHAT:**
  - Split PRD 16 into three documents: normative spec (`16_lupopedia_headers.md`), migration guide (`16_lupopedia_headers_migration.md`), and examples (`16_lupopedia_headers_examples.md`).
  - Removed dual-authority language and made header-authoritative transcript semantics explicit.
  - Clarified strict line-position checks as validator strict-mode behavior while keeping canonical key order mandatory.
- **WHY:** Reduce PRD coupling and length, make enforcement rules clearer, and lower authoring fragility without changing core architecture.
