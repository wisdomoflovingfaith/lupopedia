# ðŸº Wolfie's Schema Reference Guide  
### *Why `database/csv_data/` and `database/toon_data/` exist and why AI tools must use them*

Modern AI coding assistants (Cursor, Windsurf, Copilot, DeepSeek, Gemini, etc.) are powerful â€” but they all share the same blind spot:

> **They cannot reliably read or reason over large SQL schemas.**

Lupopedia has **250+ tables**, and Crafty Syntax adds another ~50.  
That's far beyond what current LLMs can safely ingest without hallucinating.

To keep development consistent across multiple AI tools, Lupopedia uses **two complementary schema reference systems** that force models to rely on explicit structure instead of guessing:

1. **CSV Schema Snapshots** (`database/csv_data/*.csv`) - Lightweight, AI-friendly format
2. **Toon Files** (`database/toon_data/*.json`) - Complete table structures with column definitions

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

## 1. CSV Schema Snapshots (`database/csv_data/`)

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

## 2. Toon Files (`database/toon_data/`)

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
database/csv_data/*.csv    (lightweight, AI-optimized)
database/toon_data/*.json  (complete structures with definitions)
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
