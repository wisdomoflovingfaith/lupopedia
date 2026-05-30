---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  when_updated: "20260401120000"
  file_path_from_root: "docs/versions/4.0.93/decisions/20260401_120000_DECISION_accepted_Proven_Code_Preservation_Doctrine_Section_9_20.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/decisions/20260401_120000_DECISION_accepted_Proven_Code_Preservation_Doctrine_Section_9_20.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: doctrine
  artifact_kind: decisions
  thread_id: "D-104"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# D-104: Proven Code Preservation Doctrine (Section 9.20)

## Type
Decision

## Status
Accepted

## Author
CURSOR (actor_id 102), ratified by WOLFIE

## Date
2026-04-01

### Context
A recurring failure pattern: agents encounter working code written in 1999 and propose replacing it with frameworks, npm packages, or "modern" equivalents. The specific trigger was an agent attempting to replace the 1999 eye animation (`dynlayer.js` + GIF sprites, zero dependencies, works in every browser) with a React component and npm dependencies. The WOLFIE Doctrine covered the philosophy but lacked a concrete, actionable rule in the constitutional PRD.

### Decision
- Added section 9.20 (Proven Code Preservation Doctrine, RULE 93.PROVEN_CODE) to `docs/prd/00_root_constitutional_system_requirements.md`.
- The rule includes: a four-question core test before touching existing code, a deprecation table distinguishing actively broken APIs from merely unfashionable ones, the eye animation as a named canonical example, a list of forbidden agent behaviors (proposing npm/composer installs, framework suggestions, rewriting working JS as "modern ES6+"), and the fallback ladder principle.
- Updated the WOLFIE_DOCTRINE edge in the constitutional PRD header to reference section 9.20.
- Added to the enforcement table.

### Consequences
- Working 1999-era code is now constitutionally protected by name
- Agents have a concrete checklist (4 questions) before proposing any change to existing code
- The distinction between "actively broken" and "merely old" is now documented

### Comments
*2026-04-01 WOLFIE*: The eye animation that works in Netscape 4 and Chrome 2026 without a single dependency is exactly the kind of code this doctrine protects.
*2026-04-01 CURSOR*: The deprecation table is the key addition — it gives agents a framework for distinguishing framesets (actually broken) from XMLHttpRequest (deprecated but functional).

---
