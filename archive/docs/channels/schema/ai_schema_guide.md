# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "docs/channels/schema/AI_SCHEMA_GUIDE.md"
  file_hash: "1db89efaff482f0cc2d2da9ca8d8c738a552bea21218827ad2f621ef4d00a6dc"
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
  file_path_from_root: "docs\channels\schema\AI_SCHEMA_GUIDE.md"
  file_hash: "2bb1e5ef1c66c037565d2e6f3b1b9d3714eefbc5e0356bffcc0488ea106d2f97"
  file_path_from_root: "docs\channels\schema\AI_SCHEMA_GUIDE.md"
  file_hash: "3b54390e596de7bb55077768aec1d94d9f468312d15aa7e7d842231cc7403d01"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "ðŸº Wolfie's Schema Reference Guide"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "schema", "ai_schema_guidemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# ðŸº Wolfie's Schema Reference Guide  
### *Why `database/lupopedia/csv/` and `database/lupopedia/toon/` exist and why AI tools must use them*

Modern AI coding assistants (Cursor, Windsurf, Copilot, DeepSeek, Gemini, etc.) are powerful â€” but they all share the same blind spot:

> **They cannot reliably read or reason over large SQL schemas.**

Lupopedia has **250+ tables**, and Crafty Syntax adds another ~50.  
That's far beyond what current LLMs can safely ingest without hallucinating.

To keep development consistent across multiple AI tools, Lupopedia uses **two complementary schema reference systems** that force models to rely on explicit structure instead of guessing:

1. **CSV Schema Snapshots** (`database/lupopedia/csv/*.csv`) - Lightweight, AI-friendly format
2. **Toon Files** (`database/lupopedia/toon/*.json`) - Complete table structures with column definitions

This document explains why.

---

# ðŸ“Œ Background

AI coding assistants claim they can:

- Read SQL files  
- Introspect databases  
- Understand schemas  

In practice, they **don't**.

When given a large schema, they:

- Skip sections due to token heuristics  
- Silently ignore tables  
- Invent columns  
- Hallucinate data types  
- Assume foreign keys that don't exist  
- "Fill in the blanks" instead of asking  

This is catastrophic when working with a system like Lupopedia, where:

- **No foreign keys**  
- **BIGINT UTC timestamps**  
- **Soft deletes everywhere**  
- **Domainâ€‘scoped tables**  
- **Polymorphic edges**  
- **Federated architecture**  

...mean that guessing is unacceptable.

---

# âŒ The Problem With Letting AI "Read the Database"

AI models:

- Avoid long SQL files  
- Truncate schemas internally  
- Rely on patternâ€‘matching instead of truth  
- Confidently output wrong structures  
- Assume ORM conventions that Lupopedia does *not* use  
- Treat missing information as permission to invent  

Even when connected to MySQL through Scoop or similar tools, the model:

- May not actually run the query  
- May return cached or assumed results  
- May hallucinate the schema entirely  

This makes real development impossible.

---

# âœ… The Solution: Dual Schema Reference System

To force AI tools to use the **real** schema, Lupopedia provides two complementary reference formats:

## 1. CSV Schema Snapshots (`database/lupopedia/csv/`)

Lightweight CSV files optimized for AI consumption:

```
Row 0 â†’ column names  
Row 1 â†’ column types  
Rows 2+ â†’ optional sample data  
```

### Why CSV?

Because CSV is:

- Deterministic  
- Tiny  
- Explicit  
- Universally understood  
- Impossible for LLMs to "halfâ€‘read"  
- Easy to fit into context windows  
- Easy to diff  
- Easy to regenerate  

And most importantly:

> **CSV forces the model to use the exact structure you give it â€” nothing more, nothing less.**

## 2. Toon Files (`database/lupopedia/toon/`)

Complete TOON format files (with `.toon` or `.json` extensions) containing full table structures:

```json
{
  "table_name": "actors",
  "fields": [
    "`actor_id` bigint NOT NULL auto_increment",
    "`actor_type` enum('user','ai_agent','service') NOT NULL",
    ...
  ],
  "data": [...]
}
```

### Why Toon Files?

- **Complete column definitions** - Full SQL column definitions from INFORMATION_SCHEMA
- **Human-readable** - Easy to inspect and understand
- **Structured format** - TOON format is parseable by both humans and tools
- **Sample data included** - Shows actual data patterns
- **Generated automatically** - Always matches the current database structure
- **READ-ONLY for agents** - Only the Python cron job writes TOON files

### **IMPORTANT: TOON Files Are READ-ONLY**

- âœ… **READ** TOON files to understand the schema
- âœ… **REFERENCE** TOON files when writing code or documentation
- âŒ **NEVER** modify TOON files (changes will be overwritten)
- âŒ **NEVER** regenerate TOON files
- âŒ **NEVER** fix TOON files

**The database (phpMyAdmin) is the authoritative source of truth. TOON files are automatically generated reflections of the database.**

See [TOON Doctrine](../doctrine/TOON_DOCTRINE.md) for complete rules.

### When to Use Which?

- **CSV files**: When you need lightweight, fast schema reference for AI tools
- **Toon files**: When you need complete column definitions and want to see sample data

Both systems serve the same goal: **preventing schema hallucination** by providing authoritative references.

---

# ðŸ§  Multiâ€‘Model Advantage

The CSV snapshots are intentionally lightweight so they can be fed to:

- Cursor  
- Windsurf  
- Copilot  
- DeepSeek  
- Gemini  
- Grok  
- Claude  
- And any other LLM you want to test  

Every model receives the **same authoritative schema reference**, which:

- Prevents crossâ€‘model drift  
- Keeps output consistent  
- Eliminates hallucinated columns  
- Makes parallel AI development actually work  

This is essential when multiple AI tools are helping build a system with hundreds of tables.

---

# ðŸ”’ Why MySQL Access Is Disabled for AI Tools

During development:

- **PHP and the web app** can access MySQL normally  
- **AI tools (Cursor/Windsurf)** cannot  

This prevents the model from:

- Pretending it queried the DB  
- Inventing results  
- Assuming ORM conventions  
- Hallucinating missing tables  
- Mixing real schema with imagined schema  

Instead, the model must rely on:

```
database/lupopedia/csv/*.csv    (lightweight, AI-optimized)
database/lupopedia/toon/*.json  (complete structures with definitions)
```

as the **authoritative schema references**.

---

# ðŸ§© Why This Works So Well

### âœ” CSV is simple enough for every LLM  
No parsing ambiguity. No SQL grammar. No hidden assumptions.

### âœ” The model cannot "pretend" it knows the schema  
With DB access disabled, hallucination becomes obvious.

### âœ” The schema fits entirely in the model's attention window  
No truncation. No skipped tables.

### âœ” Multiple LLMs can share the same snapshot  
Consistency across tools.

### âœ” The AI is forced to use the provided structure  
No invented columns. No imaginary foreign keys.

---

# ðŸº Summary

This isn't a hack â€” it's a **practical adaptation** to how LLMs behave.

When your schema is too large to load directly, the dual reference system (CSV + Toon files) provides:

- A stable  
- Predictable  
- Explicit  
- Modelâ€‘friendly  

reference that keeps every AI assistant honest.

Wolfie didn't choose CSV and JSON because they're oldâ€‘school.  
He chose them because:

> **These are formats every LLM understands exactly the same way.**

Supabase, Prisma, ORMs, schema explorers â€” all great tools.  
But none of them solve the core problem of:

- Getting multiple AI models  
- To share the same schema truth  
- Across a massive database  
- With consistent behavior  
- And zero hallucination  

CSV and JSON toon files do.

And that's why Lupopedia uses them.

## Generating Schema References

**For AI Agents and IDEs: DO NOT regenerate schema references.**

**TOON files are generated automatically by a Python cron job that reads the live database schema.**

**The correct workflow:**
1. Eric updates tables directly in phpMyAdmin (authoritative source)
2. Python script regenerates TOON files from the database (automated cron job)
3. Agents and IDEs read TOON files to understand the schema (read-only)
4. Agents update documentation only, not TOON files

**During active development:**
- The schema is fluid â€” tables may be redesigned, renamed, or dropped at any time
- No migration scripts are required unless explicitly requested
- This is the schema forging phase, not the migration phase

**See [TOON Doctrine](../doctrine/TOON_DOCTRINE.md) for complete rules.**

---

## Related Documentation

- **[TOON Doctrine](../doctrine/TOON_DOCTRINE.md)** - Complete rules for TOON file handling and read-only requirements
- **[Database Schema](DATABASE_SCHEMA.md)** - Complete table structure documentation
- **[No Foreign Keys Doctrine](../doctrine/NO_FOREIGN_KEYS_DOCTRINE.md)** - Why Lupopedia avoids foreign key constraints
- **[Database Philosophy](../architecture/DATABASE_PHILOSOPHY.md)** - Core principles behind Lupopedia's database design
