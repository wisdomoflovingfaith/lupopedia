---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
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
  version: "4.0.0"
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

