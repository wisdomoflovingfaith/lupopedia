# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\channels\agents\INDEX.md"
  file_hash: "7d3036fd798ff56ef9f2ac5ea72049fc13eba467fef23e094e6f60e03de3b668"
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
  file_path_from_root: "docs\channels\agents\INDEX.md"
  file_hash: "ace4d739f8bff372df56adfee24ff11f74def3d57a2f05a10c7566a1eacb03c5"
  file_path_from_root: "docs\channels\agents\INDEX.md"
  file_hash: "c6303c3fe908c624fa7bc0b995ce1832904f49b601330016f827135adad8f74a"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for INDEX.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "agents", "indexmd"]
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
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: JETBRAINS
  target: @everyone
  message: "Created channel index for agents documentation organization."
tags:
  categories: ["documentation", "index", "channels"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "agents Channel Index"
  description: "Index for agents channel documentation"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# agents Channel Index

Purpose: Agent system documentation, roles, and runtime specs.

Files:
- [AGENT_GUIDELINES.md](AGENT_GUIDELINES.md)
- [AGENT_PROMPT_TEMPLATING_STANDARD.md](AGENT_PROMPT_TEMPLATING_STANDARD.md)
- [AGENT_RUNTIME.md](AGENT_RUNTIME.md)
- [ARA.md](ARA.md)
- [CHRONOS.md](CHRONOS.md)
- [HERMES_AND_CADUCEUS.md](HERMES_AND_CADUCEUS.md)
- [lilith.md](lilith.md)
- [OHANA.md](OHANA.md)
- [thoth.md](thoth.md)
- [wolfie.md](wolfie.md)
- [WOLFIE_HEADER_GLOBAL_ATOMS_GUIDE.md](WOLFIE_HEADER_GLOBAL_ATOMS_GUIDE.md)
- [WOLFIE_HEADER_SECTIONS_GUIDE.md](WOLFIE_HEADER_SECTIONS_GUIDE.md)
- [WOLFIE_HEADER_SPECIFICATION.md](WOLFIE_HEADER_SPECIFICATION.md)

Related Channels:
- [doctrine](../doctrine/README.md)
- [architecture](../architecture/README.md)
- [dialogs](../dialogs/README.md)
