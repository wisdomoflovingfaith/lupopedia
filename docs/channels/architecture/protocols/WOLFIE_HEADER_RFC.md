---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2026-01-08
author: Wolfie (Eric Robin Gerdes)
dialog:
  speaker: cursor
  target: documentation
  message: "Created RFC 4000 - WOLFIE Header Metadata Standard in formal IETF RFC style. Defines canonical format and behavioral rules for multi-agent ecosystem."
  mood: "00FF00"
tags:
  categories: ["documentation", "specification", "rfc", "standards"]
  collections: ["core-docs", "protocols"]
  channels: ["dev", "standards"]
file:
  title: "RFC 4000 â€” The WOLFIE Header Metadata Standard"
  description: "Formal RFC specification for WOLFIE Header metadata format in Lupopedia Semantic OS"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: "Eric Robin Gerdes (Wolfie)"
---

# **RFC 4000 â€” The WOLFIE Header Metadata Standard**  
**Lupopedia Semantic OS â€” Request for Comments**  
**Category:** Standards Track  
**Version:** 4.0.1  
**Updated:** 2026â€‘01â€‘08  
**Author:** Eric Robin Gerdes ("Wolfie")  
**Part of:** Lupopedia 4.0.1 Standards Track

---

## **Status of This Memo**
This document specifies the **WOLFIE Header Metadata Standard**, a required metadata format for all files created or modified within the Lupopedia 4.0.1 ecosystem. This RFC defines the structure, semantics, behavioral rules, and validation requirements for WOLFIE Headers across all agents, IDEs, modules, and nodes.

Distribution of this memo is unlimited.

---

## **1. Introduction**
Lupopedia is a semantic operating system built on a multiâ€‘agent, multiâ€‘LLM, multiâ€‘IDE architecture. Files within Lupopedia serve as communication surfaces between:

- 101+ AI agents  
- 8 LLM models  
- 3 IDE modules  
- thousands of federated nodes  
- human developers  
- system actors  

To maintain coherence across this distributed ecosystem, every file must contain a standardized metadata block known as a **WOLFIE Header**.

The WOLFIE Header provides:

- versioning  
- authorship  
- semantic classification  
- conversational lineage  
- agentâ€‘toâ€‘agent communication  
- file intent  
- change tracking  

This RFC defines the canonical format and behavioral rules.

---

## **2. Terminology**

**MUST**, **MUST NOT**, **SHOULD**, **MAY** follow RFC 2119 semantics.

- **Agent** â€” Any Lupopedia AI agent (0â€“100+).  
- **IDE Module** â€” Cursor, Windsurf, Winston, or similar.  
- **LLM** â€” Any large language model used by agents.  
- **Header Dialog** â€” The latest edit message stored in the header.  
- **Inline Dialog** â€” Margin notes inside the file body.  
- **TOON Files** â€” Readâ€‘only schema reflection files.  
- **Node** â€” A domain installation of Lupopedia (server installation, not an AI agent). Each node is sovereign with its own database, agents, content, and governance. Identified by `domain_name`, `domain_root`, and `install_url`.  

---

## **3. WOLFIE Header Format**

### **3.1 Syntax**
A WOLFIE Header is a YAML frontmatter block enclosed in `---` delimiters.

```
---
wolfie.headers.version: 4.0.1
updated: YYYY-MM-DD
author: Wolfie (Eric Robin Gerdes)
dialog:
  speaker: <AGENT_KEY>
  target: <TARGET>
  message: "<Short technical summary of the change>"
  mood: "<optional RGB value>"
tags:
  categories: []
  collections: []
  channels: []
sections:
  - title: "<STRING>"
    anchor: "#anchor-slug"
file:
  title: "<STRING>"
  description: "<STRING>"
  version: "4.0.1"
  status: published | draft | review
  author: "<STRING>"
---
```

### **3.2 Required Fields**
- `wolfie.headers.version` â€” MUST be "4.0.1" for this RFC
- `updated` â€” Date in YYYY-MM-DD format
- `author` â€” Author identifier (e.g., "Wolfie (Eric Robin Gerdes)")
- `dialog.speaker` â€” Agent key when file is created/modified by an agent
- `dialog.message` â€” Short technical summary (â‰¤ 272 characters)

### **3.3 Optional Fields**
- `dialog.mood` â€” RGB hex value (e.g., "00FF00")
- `dialog.target` â€” Defaults to "@everyone" if omitted
- `tags.*` â€” Semantic classification arrays
- `sections.*` â€” Programmatic file TOC (array of {title, anchor} objects)
- `file.*` â€” File metadata (title, description, version, status, author)

---

## **4. Semantics**

### **4.1 The Header as Present Voice**
The WOLFIE Header represents the **current state** of the file. The header dialog block contains the **latest edit snapshot** â€” what changed in the most recent modification.

### **4.2 The Database as Memory**
All previous header dialogs MUST be archived into:

- `dialog_messages` table â€” Individual dialog messages
- `dialog_threads` table â€” Thread associations

This preserves conversational lineage and ensures:
- The header contains only the current/latest edit
- All previous edits are preserved in the database
- Full conversational lineage is maintained

**"The header is the voice of the present. The database is the memory of the past."**

### **4.3 Distinction from Inline Dialogs**
- **Header Dialog** â€” Latest edit snapshot, updated only on file modification
- **Inline Dialogs** â€” Margin notes anywhere in file body, do not modify file logic

These are separate features. See RFC 4001 (Inline Dialog Specification) for complete details on inline dialogs.

### **4.4 Sections Module (Optional)**
The `sections:` module provides a machineâ€‘readable list of all major sections in the file, extracted from Markdown headings beginning with `##`. This allows AI agents to understand the full structure of the file **before reading it**, enabling smarter navigation, summarization, and editing.

**Format:**
```yaml
sections:
  - title: "Overview"
    anchor: "#overview"
  - title: "Installation"
    anchor: "#installation"
```

**Rules:**
- **Optional** â€” Only include when file has 3+ major sections
- Extract only headings beginning with `## ` (H2 level)
- Generate anchor slugs: lowercase, hyphenated, prefixed with `#`
- Order MUST match file order
- Agents MAY populate when creating/modifying large files
- Agents MUST NOT modify unless changing file structure
- Mirrors `content_sections` field in `lupo_contents` table

**Anchor Generation:**
- Convert heading text to lowercase
- Replace spaces with hyphens
- Remove punctuation
- Prefix with `#`

**Example:** `## Getting Started` â†’ `title: "Getting Started"`, `anchor: "#getting-started"`

---

## **5. Agent Behavior Requirements**

### **5.1 When Reading a File**
Agents:

- **MUST NOT** modify the header dialog block
- **MAY** add inline dialogs (margin notes) anywhere in the file
- **MUST** preserve existing inline dialogs
- **MUST NOT** overwrite existing inline dialogs

### **5.2 When Modifying a File**
Agents:

- **MUST** update the header dialog block
- **MUST** archive the previous dialog to the `dialog_messages` table
- **MUST** insert the new dialog into the `dialog_messages` table
- **MUST** set `dialog.speaker` to their agent key
- **MUST** set `dialog.target` to `@everyone` unless otherwise required
- **MUST** write a real conversational message reflecting what changed
- **MUST** ensure `dialog.message` does not exceed 272 characters
- **MAY** add inline dialogs if leaving notes for other agents
- **MAY** update the `sections:` block if modifying file structure (adding/removing `##` headings)
- **SHOULD** populate `sections:` for large files (3+ major sections) when creating or significantly modifying

### **5.3 When Creating a File**
Agents:

- **MUST** include a WOLFIE Header with `wolfie.headers.version: 4.0.1`
- **MUST** include a `dialog:` block with initial message
- **MUST** insert the initial dialog into the `dialog_messages` table
- **MUST** set `dialog.speaker` to their agent key
- **SHOULD** include `updated` and `author` fields

---

## **6. IDE Behavior Requirements**

IDE modules (Cursor, Windsurf, Winston):

- **MUST** detect missing headers in Markdown files
- **MUST** insert headers automatically when creating new Markdown files
- **MUST NOT** modify TOON files (read-only)
- **MUST NOT** modify database files
- **MUST NOT** remove or rewrite existing headers
- **MUST** preserve existing header content when updating files
- **SHOULD** validate header format before writing

---

## **7. Validation Rules**

A valid WOLFIE Header:

- **MUST** contain `wolfie.headers.version: 4.0.1`
- **MUST** be valid YAML syntax
- **MUST** be enclosed in `---` delimiters
- **MUST** be under 10,000 characters total
- **MUST** have `dialog.message` â‰¤ 272 characters (database compatibility)
- **MUST NOT** contain executable code
- **MUST NOT** contain symbolic or mystical content (purely technical)
- **MUST NOT** conflict with schema doctrine (no foreign keys, BIGINT UTC timestamps, etc.)

Invalid headers **MUST** be rejected and **MUST NOT** be written to files.

---

## **8. Security Considerations**

WOLFIE Headers provide security and integrity guarantees:

- **Agent drift prevention** â€” Standardized format prevents agents from deviating from doctrine
- **Version mismatch prevention** â€” Version field ensures compatibility checking
- **Unauthorized schema mutation prevention** â€” Headers document all changes, preventing silent modifications
- **Traceability of changes** â€” Full conversational lineage enables audit trails
- **Safe multiâ€‘agent collaboration** â€” Header dialogs enable agent-to-agent communication without conflicts

Agents **MUST NOT** bypass WOLFIE Header requirements, as this would compromise system integrity and multi-agent coordination.

---

## **9. Versioning**

This RFC defines **WOLFIE Headers v4.0.1**, aligned with:

- Lupopedia 4.0.1
- Crafty Syntax 4.0.1
- Schema 4.0.1

All future versions **MUST** maintain backward compatibility unless superseded by a new RFC. Version 3.0.0 and v2.x headers remain valid but are deprecated.

---

## **10. References**

- **[ARCHITECTURE.md](../ARCHITECTURE.md)** â€” Lupopedia Semantic OS Architecture
- **[ARCHITECTURE_SYNC.md](../ARCHITECTURE_SYNC.md)** â€” Authoritative subsystem reference
- **[WOLFIE_HEADER_SPECIFICATION.md](../../agents/WOLFIE_HEADER_SPECIFICATION.md)** â€” Complete WOLFIE Header Specification
- **[INLINE_DIALOG_SPECIFICATION.md](../../dialogs/agents/INLINE_DIALOG_SPECIFICATION.md)** â€” Inline Dialog Specification
- **[TOON_DOCTRINE.md](../../doctrine/TOON_DOCTRINE.md)** â€” TOON File Doctrine
- **[DATABASE_PHILOSOPHY.md](../DATABASE_PHILOSOPHY.md)** â€” Database Design Principles
- **RFC 2119** â€” Key words for use in RFCs to Indicate Requirement Levels

---

## **11. Author's Address**

Eric Robin Gerdes  
Lupopedia Architect  
Sioux Falls, South Dakota  
United States  

---

## **12. Change Log**

- **v4.0.1 (2026-01-09)** â€” Added sections module
  - Added optional `sections:` module for programmatic file TOC
  - Extracts `##` headings and generates anchor links
  - Enables agents to understand file structure before reading
  - Mirrors `content_sections` field in `lupo_contents` table
  
- **v4.0.0 (2026-01-08)** â€” Initial RFC specification
  - Formalized WOLFIE Header standard in RFC format
  - Defined canonical format and behavioral rules
  - Established validation requirements
  - Documented security considerations

---

*This RFC is part of the Lupopedia Semantic OS documentation set. For related RFCs, see:*
- *RFC 4001 â€” Inline Dialog Specification (pending)*
- *RFC 4002 â€” Multiâ€‘Agent Synchronization Protocol (pending)*
- *RFC 4003 â€” Semantic OS Actor Model (pending)*
- *RFC 4004 â€” Node Federation Protocol (pending)*

*Last Updated: January 2026*  
*Version: 4.0.1*  
*Category: Standards Track*  
*Status: Published*  
*Part of: Lupopedia 4.0.1 Standards Track*
