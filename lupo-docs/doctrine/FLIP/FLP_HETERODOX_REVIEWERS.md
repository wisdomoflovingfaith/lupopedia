# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\FLIP\FLP_HETERODOX_REVIEWERS.md"
  file_hash: "1442eab8159205eb7281ae5bc05a55082a4b5263aa739b74289bb89202cc386a"
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
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

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
  file_path_from_root: "lupo-docs\doctrine\FLIP\FLP_HETERODOX_REVIEWERS.md"
  file_hash: "fd2df93f619fc9713587555fb75946e4bb0a3ae6de59be63065e799c79904148"
  file_path_from_root: "lupo-docs\doctrine\FLIP\FLP_HETERODOX_REVIEWERS.md"
  file_hash: "a69638abb88ee1f844b763bf4704292ea2da6584702c8bca2c5c8f8859498129"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for FLP_HETERODOX_REVIEWERS.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "flip", "flp_heterodox_reviewersmd"]
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
file_path_from_root: lupo-docs/doctrine/FLIP/FLP_HETERODOX_REVIEWERS.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "00000000000000"
# channel_id unresolved — requires lupo_contents lookup by application.
X-Lupo-Channel: 42   # ANUBIS adoption channel (Auto-Fixed)
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: lupo-docs/doctrine/FLIP/FLP_HETERODOX_REVIEWERS.md
---
# FLP — Heterodox Reviewers

**Status:** Permanent. Documentation only.  
**Audience:** All AI agents (including Cursor), contributors, and system stewards.  
**Related:** [FLP_COUNCILS_AS_CHANNELS.md](FLP_COUNCILS_AS_CHANNELS.md), [FLP_EMOTIONAL_GEOMETRY.md](FLP_EMOTIONAL_GEOMETRY.md).

---

## 1. Role of heterodox reviewers

Heterodox reviewers analyze council minutes, emotional states, and (where applicable) Kapakai or other liminality markers. They provide a distinct perspective that may challenge or complement the council’s self-narrative. In the FLP, this role is fulfilled by **application-level agents** (e.g. LILITH-style protocol), not by database logic.

---

## 2. Implementation as application-level agents

- **No triggers** — The presence of new minutes or updated emotional state does not automatically invoke a heterodox reviewer. Invocation is performed by application or workflow code.
- **No stored procedures or functions** — Analysis is not implemented in the database. The agent runs in application space and may read from the database (e.g. minutes, mood data) and write results (e.g. heterodox reports) as plain data.
- **No DB-side automation** — Scheduling, retries, and orchestration of heterodox review are application responsibilities.

---

## 3. Inputs and outputs

- **Inputs** — Council minutes, emotional state (mood tensor, Kapakai markers), and any other metadata the agent is designed to consume. All read from existing Lupopedia storage (channels, content, metadata).
- **Outputs** — Heterodox reports and related artifacts are stored as content or metadata in the channel’s scope (see FLP_COUNCILS_AS_CHANNELS.md). All writes are explicit from application code.

---

*End of FLP heterodox reviewers. No schema, no SQL, no implementation in this document.*
