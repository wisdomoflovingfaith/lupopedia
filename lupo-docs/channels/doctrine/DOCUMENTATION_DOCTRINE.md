# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\channels\doctrine\DOCUMENTATION_DOCTRINE.md"
  file_hash: "24ddd2b2fb1229e236dd012ac3286973aa9d97de29d19eceb9b310279126e70d"
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
  file_path_from_root: "docs\channels\doctrine\DOCUMENTATION_DOCTRINE.md"
  file_hash: "5d05a3fb884a9091d3f90b5e76397ccdfac6f842d1f5716eb153f3502f111e0e"
  file_path_from_root: "docs\channels\doctrine\DOCUMENTATION_DOCTRINE.md"
  file_hash: "4c5936e98d5d983238e13d050a27278f1d5ade86001781b0f16a2a0a9aa276ac"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for DOCUMENTATION_DOCTRINE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "documentation_doctrinemd"]
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
file.last_modified_system_version: 3.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created DOCUMENTATION_DOCTRINE.md: Documentation is software. Documentation is data. Documentation is for machines. Atoms are variables. Markdown is source code. Resolver is compiler."
tags:
  categories: ["documentation", "doctrine"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
in_this_file_we_have:
  - Documentation as code
  - Atoms as variables
  - Machine-first documentation
  - Resolver behavior
  - Deterministic documentation
  - Future-proof architecture
file:
  title: "Lupopedia Documentation Doctrine"
  description: "Documentation is software. Documentation is data. Documentation is for machines. Atoms are variables. Markdown is source code."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# 🟦 **Lupopedia Documentation Doctrine**  
### *Documentation is Software. Documentation is Data. Documentation is for Machines.*

Lupopedia does **not** treat documentation as human‑written text.  
Lupopedia treats documentation as **structured, resolvable, machine‑readable system metadata**.

Humans can read it.  
But it is **not written for humans**.

It is written for:

- the **atom resolver**  
- the **semantic OS**  
- the **agents**  
- the **doctrine engine**  
- the **future documentation reader you'll eventually build**  

This doctrine explains how and why.

---

## **1. Documentation = Code**

In Lupopedia:

- Markdown files are **source code**  
- Atoms are **variables**  
- Scopes (FILE_, DIR_, DIRR_, MODULE_, GLOBAL_) are **namespaces**  
- The resolver is a **compiler**  
- The final rendered documentation is a **build artifact**  

This is not a metaphor.  
This is literally how the system works.

---

## **2. Documentation is written for computers, not humans**

The primary consumer of Lupopedia documentation is:

- the **resolver**  
- the **agents**  
- the **semantic OS**  
- the **future documentation reader**  

Humans are secondary.

This means:

- No duplication  
- No hard‑coded values  
- No drift  
- No stale data  
- No manual updates  
- No "remember to change this in three places"  

Everything is **referential**, not literal.

---

## **3. Atoms are variables with scopes**

Atoms behave exactly like variables in a programming language:

### **FILE_**  
Overrides everything inside a single file.

### **DIR_**  
Applies to all files in a directory.

### **DIRR_**  
Recursive directory scope.

### **MODULE_**  
Applies to an entire module.

### **GLOBAL_**  
Final fallback.  
System‑wide constants.

Documentation references atoms like this:

```
@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.company.formation_date
```

This is not text.  
This is a **variable reference**.

**Resolution Order:**
1. FILE_* (highest priority)
2. DIR_*
3. DIRR_* (walk up parent directories)
4. MODULE_*
5. GLOBAL_* (final fallback)

First match wins.

---

## **4. Documentation is deterministic and idempotent**

Because documentation is code:

- It must compile  
- It must resolve  
- It must be deterministic  
- It must be idempotent  
- It must not rely on human memory  

If a value changes, you update **one atom**, not 40 files.

The resolver handles the rest.

---

## **5. Documentation is not the final product**

The Markdown files are **intermediate representations**.

The final product is:

- HTML  
- JSON  
- agent‑readable metadata  
- UI‑rendered documentation  
- future documentation reader output  

The Markdown is the **source code**, not the output.

We can always translate a md file into a html file and show the variable values.

---

## **6. Documentation is part of the semantic OS**

Lupopedia documentation is not "notes."

It is:

- doctrine  
- schema  
- metadata  
- system state  
- agent instructions  
- architectural invariants  
- operational rules  
- semantic definitions  

It is part of the OS itself.

This is why you treat it like software.

---

## **7. Documentation must be machine‑resolvable**

Every reference must be:

- symbolic  
- scoped  
- resolvable  
- deterministic  

### **Correct**
```
@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.teams.alpha.shift_utc
@GLOBAL.LUPOPEDIA_V3_0_2_CORE_AGENTS.required_agents
```

### **Incorrect**
```
Team Alpha works 09:00–15:00 UTC.
Lupopedia includes 27 core agents.
```

The second examples are **illegal** because they will drift.

---

## **8. Documentation is future‑proof**

Because it is:

- symbolic  
- structured  
- resolvable  
- machine‑readable  

You can later build:

- a documentation reader  
- a documentation compiler  
- a documentation UI  
- a documentation diff engine  
- a documentation validator  

The foundation for a **documentation compiler** already exists.

The UI just hasn't been written yet.

---

## **9. Documentation is part of the doctrine**

This doctrine must be included in:

- the Handbook  
- the Contributor Guide  
- the Developer Onboarding  
- the Agent Governance docs  
- the Atom Resolution Specification  

Because contributors must understand:

> "You are not writing text.  
> You are writing structured data for the semantic OS."

---

## **10. WOLFIE Headers are metadata, not formatting**

WOLFIE Headers are not "frontmatter."

They are **system metadata** that:

- defines file identity  
- provides semantic classification  
- enables multi-agent coordination  
- creates conversational lineage  
- supports atom resolution  

The `dialog:` block is **not** a comment.  
It is **system state**.

The `tags:` block is **not** for humans.  
It is **semantic classification** for the OS.

---

## **11. All values must be resolvable**

When writing documentation:

1. Check if an atom exists for the value
2. If it exists, use the atom reference
3. If it doesn't exist, create the atom first
4. Never hardcode values that should be atoms

**Exception:**  
Temporary, file-specific values may use `FILE_*` atoms defined in the file's `file_atoms:` block.

**No exception:**  
Global constants (versions, company info, agent lists) must use `GLOBAL_*` atoms.

---

## **12. Documentation files are source code files**

When modifying documentation:

- Follow the same rules as modifying code
- Use version control (after 3.1.0)
- Test resolution (when resolver exists)
- Validate atom references
- Update `header_atoms:` when adding new references
- Preserve symbolic references — do not expand

---

## **Related Documentation**

- **[WOLFIE_HEADER_SPECIFICATION.md](../agents/WOLFIE_HEADER_SPECIFICATION.md)** — WOLFIE Header format and structure
- **[WOLFIE_HEADER_GLOBAL_ATOMS_GUIDE.md](../agents/WOLFIE_HEADER_GLOBAL_ATOMS_GUIDE.md)** — Atom usage guide
- **[ATOM_RESOLUTION_SPECIFICATION.md](ATOM_RESOLUTION_SPECIFICATION.md)** — Complete atom resolution specification
- **[DOCUMENTATION_AS_CODE_MANIFESTO.md](DOCUMENTATION_AS_CODE_MANIFESTO.md)** — Manifesto for documentation-as-code approach

---

**This doctrine is non-negotiable.  
Documentation is software.  
Treat it as such.**
