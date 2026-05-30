> **For the authoritative channel model, see PRD 02 and channel_model_doctrine.md. Channels are semantic containers under a domain (node), not chat rooms.**

# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/channels/doctrine/as_above_so_below.md"
  file_hash: "44d10187f42f887b0e2f19a55110a628b26612e24a204f6767385ab748e8066a"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_vector: "4169E1"
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
  file_path_from_root: "docs\channels\doctrine\as_above_so_below.md"
  file_hash: "310f24e98bff89fa2fbae0d08434428b1157a39abfb539d96e729e9f150cdf2f"
  file_path_from_root: "docs\channels\doctrine\as_above_so_below.md"
  file_hash: "1921baea7924a17cd370f991b2c7bac76a51d12c5a62369ce6e24330a5277fe5"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for as_above_so_below.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "as_above_so_belowmd"]
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
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 3.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created as_above_so_below.md: Architectural principle, not occult branding. Structure of meaning (above) mirrors structure of data/implementation (below). Keeps semantic layer and relational layer in sync."
tags:
  categories: ["documentation", "doctrine", "architecture"]
  collections: ["core-docs", "doctrine"]
  channels: ["public", "dev"]
in_this_file_we_have:
  - Architectural principle definition
  - Practical implications
  - Why it matters
file:
  title: "As Above, So Below - Architectural Principle"
  description: "Architectural rule: structure of meaning mirrors structure of data. Not occult branding, but a principle for keeping semantic and relational layers in sync."
  version: "3.0.0"
  status: published
  author: "Captain Wolfie"
---

# As above, so below (architectural principle)

In Lupopedia, "as above, so below" is an architectural rule:

> The structure of meaning (above) should mirror the structure of data and implementation (below).

Practically, this means:

- The way questions, claims, evidence, and scores relate in the database should reflect how humans actually argue, doubt, and update their beliefs.
- The way UI components connect (threads, messages, transcripts, topics) should mirror the underlying schema.
- The way agents (like Thoth and Lilith) reason should align with how data is stored, linked, and versioned.

It is not an occult slogan. It is a reminder that:

- If the schema and the lived experience drift apart, users suffer.
- If the schema matches how people really think and work, the system feels "natural" and trustworthy.

"As above, so below" is how Lupopedia keeps its **semantic layer** and its **relational layer** in sync.
