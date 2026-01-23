---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created DOCUMENTATION_AS_CODE_MANIFESTO.md: Documentation is not text. Documentation is structured data. Markdown is source code. Atoms are variables. Resolver is compiler. Machines first, humans second."
tags:
  categories: ["documentation", "doctrine", "manifesto"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "public"]
header_atoms:
  - GLOBAL_CURRENT_AUTHORS
in_this_file_we_have:
  - Documentation-as-code philosophy
  - Why documentation is software
  - Machine-first documentation
  - Atom-based architecture
  - Future-proof documentation
file:
  title: "Documentation-as-Code Manifesto"
  description: "Documentation is not text. Documentation is structured data. Markdown is source code. Atoms are variables. Resolver is compiler."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# 🟦 **Documentation-as-Code Manifesto**

## **Documentation is not text**

Documentation is **structured, resolvable, machine-readable system metadata**.

It is **not** human-written prose.  
It is **not** comments.  
It is **not** notes.  
It is **not** explanations for humans.

It is **data**.  
It is **code**.  
It is **software**.

---

## **Markdown is source code**

The `.md` files in Lupopedia are **not** documentation.

They are **source code files**.

They are:

- **Intermediate representations**  
- **Build artifacts** (before compilation)  
- **Structured data** with semantic markup  
- **Machine-readable** specifications  

The **final** documentation is:

- HTML (rendered)  
- JSON (structured data)  
- Agent-readable metadata  
- UI-rendered documentation  
- Future documentation reader output  

Markdown is the **input**, not the output.

---

## **Atoms are variables**

Atoms are **not** placeholders.  
Atoms are **not** templates.  
Atoms are **not** macros.

Atoms are **variables** with scopes.

They behave exactly like variables in a programming language:

- **Scopes:** FILE_, DIR_, DIRR_, MODULE_, GLOBAL_  
- **Resolution:** Deterministic, idempotent  
- **Inheritance:** Scoped inheritance rules  
- **Type system:** Implicit (resolved to strings)  

```
@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.company.name
```

This is **not** text replacement.  
This is **variable resolution**.

---

## **Resolver is compiler**

The atom resolver is **not** a text processor.  
The atom resolver is **not** a template engine.  
The atom resolver is **not** a preprocessor.

The atom resolver is a **compiler**.

It:

- **Parses** atom references  
- **Resolves** variable scopes  
- **Validates** references  
- **Compiles** symbolic references to literal values  
- **Emits** resolved documentation  

Markdown + Resolver → Final Documentation

This is **compilation**, not substitution.

---

## **Machines first, humans second**

Documentation is written **for machines first**.

Primary consumers:

1. **Atom resolver**  
2. **Semantic OS**  
3. **Agents**  
4. **Doctrine engine**  
5. **Future documentation reader**  

Humans are **secondary**.

This means:

- No hard-coded values  
- No duplication  
- No drift  
- No stale data  
- No manual updates  
- Everything is **referential**  

If a human needs to read it, the **resolved output** is what they read.

The **source code** (Markdown with atoms) is for the system.

---

## **Documentation is part of the OS**

Lupopedia documentation is **not** external to the system.

It is **part of the semantic OS itself**.

Documentation contains:

- **Doctrine** (system rules)  
- **Schema** (data structures)  
- **Metadata** (system state)  
- **Agent instructions** (behavior definitions)  
- **Architectural invariants** (system constraints)  
- **Operational rules** (runtime behavior)  
- **Semantic definitions** (meaning and context)  

This is **system code**, not external documentation.

---

## **Deterministic and idempotent**

Documentation must be **deterministic**:

- Same input → Same output  
- No random behavior  
- No time-based values  
- No environment-dependent resolution  

Documentation must be **idempotent**:

- Resolving twice → Same result  
- No side effects  
- No state mutation  
- Pure function behavior  

This is **software engineering**, not content writing.

---

## **No duplication, no drift**

Because documentation is code:

- **One source of truth** for each value  
- **Atom references** instead of literal values  
- **Automatic updates** when atoms change  
- **No manual synchronization**  

If a value changes, update **one atom**.

The resolver updates **every reference** automatically.

This prevents:

- **Duplication** (same value in multiple places)  
- **Drift** (values becoming inconsistent)  
- **Stale data** (outdated values)  
- **Human error** (forgetting to update all copies)  

---

## **Future-proof architecture**

Because documentation is:

- **Symbolic** (atom references)  
- **Structured** (Markdown + YAML)  
- **Resolvable** (atom resolution engine)  
- **Machine-readable** (semantic markup)  

You can build:

- **Documentation reader** (render to HTML/UI)  
- **Documentation compiler** (compile to various formats)  
- **Documentation validator** (check atom references)  
- **Documentation diff engine** (track changes to atoms)  
- **Documentation search** (semantic search across docs)  
- **Documentation API** (programmatic access)  

The foundation exists.  
The tools just haven't been built yet.

---

## **WOLFIE Headers are metadata**

WOLFIE Headers are **not** frontmatter.  
WOLFIE Headers are **not** YAML blocks.  
WOLFIE Headers are **not** comments.

WOLFIE Headers are **system metadata**.

They define:

- **File identity** (what this file is)  
- **Semantic classification** (categories, tags, collections)  
- **Multi-agent coordination** (dialog blocks)  
- **Conversational lineage** (edit history)  
- **Atom resolution context** (header_atoms, file_atoms)  

This is **structured data**, not formatting.

---

## **Documentation contributors are developers**

When you write Lupopedia documentation:

You are **not** writing text.  
You are **not** explaining things.  
You are **not** documenting code.

You are **writing software**.

You are:

- **Defining system metadata**  
- **Creating resolvable references**  
- **Structuring semantic data**  
- **Building part of the OS**  

Documentation contributors are **developers**.

They must understand:

- **Atom scopes** (FILE_, DIR_, MODULE_, GLOBAL_)  
- **Resolution order** (first match wins)  
- **WOLFIE Header structure** (metadata format)  
- **Doctrine alignment** (system rules)  

This is **software development**, not technical writing.

---

## **The future is machine-readable**

The future of documentation is:

- **Fully automated** (generated from code)  
- **Machine-executable** (agents read and act on docs)  
- **Semantically linked** (knowledge graphs)  
- **Resolvable at runtime** (atoms resolve on-demand)  
- **Version-controlled as code** (Git for docs)  
- **Tested like code** (validation, linting)  

Lupopedia is building that future **now**.

Not later.  
Not "when we have time."  
**Now.**

---

## **Principles**

1. **Documentation is code**  
   Treat it like software. Write tests. Review changes. Version control.

2. **Atoms are variables**  
   Use references, not literals. One source of truth.

3. **Machines first**  
   Write for the resolver, agents, OS. Humans read the output.

4. **Deterministic**  
   Same input → Same output. Always.

5. **Idempotent**  
   Resolving twice → Same result. No side effects.

6. **No duplication**  
   Use atoms. One source of truth.

7. **No drift**  
   Atom references prevent inconsistency.

8. **Future-proof**  
   Build for machines. Tools will come later.

---

## **This is not optional**

Documentation-as-code is **not** a suggestion.  
It is **not** a best practice.  
It is **not** a recommendation.

It is **doctrine**.

It is **non-negotiable**.

All Lupopedia documentation must:

- Use atom references  
- Follow WOLFIE Header format  
- Be machine-resolvable  
- Be deterministic and idempotent  
- Follow this manifesto  

**No exceptions.**

---

## **Related Documentation**

- **[DOCUMENTATION_DOCTRINE.md](DOCUMENTATION_DOCTRINE.md)** — Core documentation doctrine
- **[ATOM_RESOLUTION_SPECIFICATION.md](ATOM_RESOLUTION_SPECIFICATION.md)** — Atom resolution specification
- **[WOLFIE_HEADER_SPECIFICATION.md](../agents/WOLFIE_HEADER_SPECIFICATION.md)** — WOLFIE Header format
- **[WOLFIE_HEADER_GLOBAL_ATOMS_GUIDE.md](../agents/WOLFIE_HEADER_GLOBAL_ATOMS_GUIDE.md)** — Atom usage guide

---

**Documentation is software.  
Documentation is data.  
Documentation is for machines.**

**This is doctrine.  
Treat it as such.**
