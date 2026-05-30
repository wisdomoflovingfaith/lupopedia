---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/CONTINUITY_LAYER_DOCTRINE.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/CONTINUITY_LAYER_DOCTRINE.md
  status: active
  when_updated: '20260513033046'
  trust_tier: canonical
  questions_toon: null
  memory_toon: memory/development/canonical/1026/04/continuity-layer-doctrine.toon
  atoms_toon: null
  transcript_jsonl: 0/development/continuity-layer-doctrine
  artifact_type: documentation
  artifact_kind: guide
  channel_key: development
  federation_node_id: 0
  thread_key: ''
  lupopedia.schema: documentation
  prd_cluster: null
  title: Continuity Layer and Limited Degraded Operation
  summary: 'Normative wording: live database is system of record; exported artifacts enable bounded continuity only.'
---
# Continuity Layer and Limited Degraded Operation

## 1. System of record

Under normal operation, authoritative application state lives in the **database** accessed through the sanctioned PDO wrapper. Filesystem artifacts do **not** replace the database as a full secondary system of record.

## 2. Continuity layer (bounded)

The continuity layer means **exported structure snapshots**, **persisted content**, **memory or graph artifacts**, and **append-only operational logs** (for example JSONL transcripts) that allow **limited** service when the database or upstream dependencies are unavailable or degraded.

Scope is intentionally **bounded**: selected reads, explanations, or operator surfaces—not a guarantee that every feature runs forever without the database.

## 3. Wording to avoid externally

Do not describe this as “the database falls back to files” or “files become the full database.” Prefer **database-backed with continuity artifacts** and **limited continuity / degraded mode**.

## 4. Normative references

- **PRD 38** — Memory unification and persistence boundaries: `docs/prd/38_memory_unification.md`
- **Translation channel** — Audience-layer explanation of continuity: `channels/0/translation/concepts/01_continuity_layer.md` (must stay aligned with this doctrine)
- **Production precedent (Captain narrative)** — Honolulu DMV continuity work (1999): `content/federation_node/0/captains_log/20260416_dmv_1999_continuity_precedent.md`

## 5. Weekly report cross-check

Executive weekly report (week of 2026-04-10 through 2026-04-16) Section 5 claims are satisfied when this file and the translation concept above remain aligned. Evidence index: `docs/versions/4.1.2/status/weekly_report_evidence_index_20260416.md`.
