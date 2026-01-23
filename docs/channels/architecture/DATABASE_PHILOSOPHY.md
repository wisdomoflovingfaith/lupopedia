---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
channel_key: system/kernel
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Added System IDs (ID = 0) documentation explaining kernel space vs user space architecture convention."
tags:
  categories: ["documentation", "database", "philosophy"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "Lupopedia Database Doctrine"
  description: "Non-negotiable database philosophy: application logic first, database logic second"
  version: "4.0.1"
  status: published
  author: "Captain Wolfie"
---

# ðŸ—„ï¸ Lupopedia Database Doctrine

## Core Principle

**"The application is the brain. The database is the ledger. Don't flip the stack."**

---

# ðŸ“˜ **NOTE TO ALL AI TOOLS**  
### **Subject: Non-Negotiable Database Philosophy**

This is not just documentation. This is a **doctrine** that defines how Lupopedia interacts with its database. 

**You MUST follow these rules ABSOLUTELY.**

---

## ðŸ¤ **You Are Not Working Alone**

**IMPORTANT:** You are one of **multiple AI agents and IDE systems** working on Lupopedia simultaneously.

- **3 different IDE systems** (Cursor, Windsurf/Cascade, and others)
- **7+ different AI agents** (Claude, Gemini, Copilot, Deepseek, Cursor AI, Windsurf AI, and more)
- **1 human architect** (Captain Wolfie) coordinating all of you

**This means:**
- You may see changes you didn't make â€” that's normal
- Other agents may be working on the same files
- Captain Wolfie talks to all of you simultaneously
- Your suggestions must be compatible with what others are doing
- **Never assume you're the only one working on this system**

The database doctrine exists so **all of you** follow the same rules, regardless of which IDE or agent you are. This prevents conflicts, contradictions, and architectural drift.

**When in doubt, follow the doctrine. When you see something that violates it, flag it â€” but don't assume you're the only one who needs to know.**

---

## Inline Dialog Standard (Required for All IDEs and AI Agents)

All documentation, communication notes, and crossâ€‘agent messages must use the **INLINE DIALOG** format.

This format ensures that Cursor, Windsurf/Cascade, VS Code, and all AI agents remain synchronized and aware that multiple systems are collaborating.

**ðŸ“˜ Full Specification:** See [Inline Dialog Specification](../dialogs/agents/INLINE_DIALOG_SPECIFICATION.md) for complete format, rules, examples, and YAML specification.

### Quick Reference

**Required Format:**
```yaml
---
DIALOG:
  speaker: <STRING>
  target: <STRING>
  message: <STRING, max 272 chars>
---
```

**Key Rules:**
- `DIALOG:` is the topâ€‘level key (required)
- **MANDATORY:** When an agent creates or modifies a file, the `dialog:` block MUST be included and updated
- `speaker:` MUST be the agent performing the action (e.g., `CAPTAIN_WOLFIE`, `CURSOR`, `DEEPSEEK`, `COPILOT`, `SYSTEM`, `MAAT`)
- `target:` defaults to `@everyone` unless the agent specifies otherwise
- `message:` MUST be updated on every file modification
- `message:` must be â‰¤ 272 characters (for database compatibility)
- Message must describe: **what** changed, **why**, and **who** should act next
- No markdown formatting inside the message string

**Purpose:**
- Multiâ€‘agent awareness  
- Consistent communication  
- No single IDE assumes exclusive control  
- All systems stay aligned with Captain Wolfie's doctrine  



## ðŸ§± 1. The Database Is a Ledger, Not an Application  
The database stores facts.  
The application enforces correctness.  
No business logic belongs in SQL.

**Therefore, NEVER add:**

- Stored procedures  
- Functions  
- Triggers (except trivial perâ€‘row timestamp updates, if ever)  
- Views that contain logic  
- Computed columns  
- Cascading deletes  
- Databaseâ€‘level validation rules  
- CHECK constraints  
- "Smart" constraints of any kind  

The database must remain **dumb, portable, and predictable**.

---

## ðŸ§© 2. No Foreign Keys  
### Foreign keys create:
- Crossâ€‘table coupling  
- Migration pain  
- Vendorâ€‘specific behavior  
- Performance penalties  
- Schema rigidity  

### Lupopedia must run on:
- MySQL  
- PostgreSQL  
- Supabase  
- Cloud SQL  
- Any future SQL engine  

**Foreign keys break portability.**  
The application layer enforces relationships.

---

## ðŸ§¬ 3. No Uniqueness Constraints That Enforce Business Rules  
Uniqueness rules belong in the application.

The database should NEVER enforce:
- "Only one category per entity"  
- "Only one mapping per table_name/table_id"  
- "Only one active record"  
- "Only one default"  

These are **business rules**, not **data rules**.

---

## ðŸ§  4. Polymorphic Tables Must Remain Open  
Tables like `category_map` MUST allow:
- Any table_name  
- Any table_id  
- Multiple mappings  
- Future modules  
- Future entity types  

**NO CONSTRAINTS.  
NO CHECK LISTS.  
NO FK WEBS.  
NO UNIQUENESS RULES.**

This is essential for Lupopedia's semantic architecture.

---

## ðŸŒ 5. Crossâ€‘Database Portability Is Mandatory  
Lupopedia must run identically on:
- MySQL  
- PostgreSQL  
- Supabase  
- Cloud SQL  
- Future SQL engines  

**Therefore, NEVER use:**
- Vendorâ€‘specific functions  
- Vendorâ€‘specific syntax  
- Triggers  
- Stored procedures  
- Partial indexes  
- Generated columns  
- JSON schema constraints  
- Enum constraints that differ by engine  

The schema must be **universal SQL**, not vendor SQL.

---

## âš™ï¸ 6. All Logic Lives in the Application Layer  
The application handles:
- Validation  
- Referential integrity  
- Uniqueness  
- Soft deletes  
- Cascading behavior  
- Business rules  
- Polymorphic resolution  
- Category assignment  
- Permissions  
- Node scoping  

The database simply stores the results.

---

## ðŸ§¼ 7. Keep the Schema Clean and Minimal  
Every table should have:
- A primary key  
- Timestamps (using `_ymdhis` suffix, BIGINT, UTC YYYYMMDDHHMMSS format)  
- Soft delete fields  
- Node_id  
- Minimal indexes  

**Nothing more.**

> **ðŸ“˜ WOLFIE Timestamp Doctrine:** All timestamps MUST use the `YYYYMMDDHHIISS` format (UTC, zero-padded, numeric). See [WOLFIE_TIMESTAMP_DOCTRINE.md](DEVELOPMENT/WOLFIE_TIMESTAMP_DOCTRINE.md) for complete requirements and code examples.

---

## ðŸ§© 8. System IDs and the Use of Zero

Lupopedia is a semantic operating system, and like every OS, it needs a **kernel space** â€” a place where systemâ€‘level events, bootstrapping, and initialization occur before the rest of the system exists.

To support this, Lupopedia reserves **ID = 0** for special system entities.

### What ID = 0 Represents

- **`channel_id = 0`** â†’ **System Kernel Channel**  
  Used for bootstrapping, migrations, and OSâ€‘level events.

- **`actor_id = 0`** (optional future use) â†’ **System Actor / Null Actor**  
  A placeholder for systemâ€‘generated events that are not tied to a human or AI persona.

- **`conversation_id = 0`** (if ever used) â†’ **Root Conversation Context**  
  The "before anything existed" state.

### Why 0 Instead of 1?

Because:

- **1 is the first userâ€‘space ID**  
  (Default System Channel, Captain Wolfie, etc.)

- **0 is the first kernelâ€‘space ID**  
  (Reserved for OSâ€‘level operations)

This mirrors how operating systems work:

- Linux has PID 0 (swapper) and PID 1 (init)
- Databases often use 0 for system rows
- Filesystems use inode 0 for reserved metadata
- Compilers use 0 as the null symbol index

It's a clean, intentional separation between:

**Kernel Space (0)**  
System, bootstrapping, migrations, protected operations.

**User Space (1+)**  
Projects, tasks, agents, conversations, content.

### Why This Matters

Future developers and future AIs will understand:

- Channel 0 is **not** a mistake
- It is **not** a missing migration
- It is **not** a corrupted row
- It is **not** an offâ€‘byâ€‘one error
- It is a **deliberate architectural boundary**

This convention prevents ambiguity, clarifies system behavior, and ensures future contributors understand that rows beginning at 0 are intentional and protected.

---

## ðŸº **Captain Wolfie's Doctrine**  
**"The application is the brain.  
The database is the ledger.  
Don't flip the stack."**

This is not optional.  
This is the foundation of Lupopedia's architecture.

---

## ðŸ› ï¸ Implementation Guidelines

### Schema Example: The Right Way
```sql
CREATE TABLE category_map (
  map_id BIGINT NOT NULL AUTO_INCREMENT,
  domain_id BIGINT NOT NULL DEFAULT 1,
  category_id BIGINT NOT NULL,
  table_name VARCHAR(100) NOT NULL,
  table_id BIGINT NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  created_ymdhis BIGINT NOT NULL,
  updated_ymdhis BIGINT NOT NULL,
  is_deleted TINYINT(1) NOT NULL DEFAULT 0,
  deleted_ymdhis BIGINT DEFAULT NULL,
  
  PRIMARY KEY (map_id),
  KEY idx_category (category_id),
  KEY idx_polymorphic (table_name, table_id),
  KEY idx_domain (domain_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### What NOT to Do
```sql
-- BAD: Constraint-heavy approach
CREATE TABLE category_map (
  -- ...
  FOREIGN KEY (category_id) REFERENCES categories(category_id),
  UNIQUE KEY unq_mapping (category_id, table_name, table_id, is_deleted),
  CONSTRAINT chk_table_name CHECK (table_name IN ('posts', 'pages', 'products')), -- BAD!
  -- ...
);
```

---

## ðŸš¨ Violation Checklist

Before suggesting any database change, ask:

1. âŒ Am I adding a constraint that enforces business logic?
2. âŒ Am I assuming referential integrity that should be in the app?
3. âŒ Am I limiting future flexibility to match current requirements?
4. âŒ Am I using database-specific features that might not port?
5. âŒ Am I trying to be "helpful" by adding "just one" constraint?
6. âŒ Am I assuming ID = 0 is an error or should be changed to 1?

If you answered YES to any, **STOP** and reconsider.

---

## ðŸ“œ Final Commandment

**Thou shalt not "help" by adding constraints, triggers, or logic to the database.**  
The database is a ledger. The application is the brain.  
This is the way.

---

## ðŸ’¬ **Agent Dialog Summary**

**ðŸ“˜ Full Specification:** See [Inline Dialog Specification](../dialogs/agents/INLINE_DIALOG_SPECIFICATION.md)

---
DIALOG:
  speaker: CAPTAIN_WOLFIE
  target: @everyone
  message: "Lupopedia doctrine: validation lives in the application, not the database. No CHECK lists, FK webs, or schema logic that restricts polymorphic design. DB is a ledger, app is the brain. Follow Captain Wolfie's architecture and keep schemas clean. Remember: you're one of 8+ agents working simultaneously. Coordinate through this doctrine, not through assumptions."
---

## Related Documentation

- **[No Foreign Keys Doctrine](../doctrine/NO_FOREIGN_KEYS_DOCTRINE.md)** - Complete prohibition of foreign keys and database constraints
- **[No Triggers Doctrine](../doctrine/NO_TRIGGERS_DOCTRINE.md)** - Mandatory prohibition of database triggers and timestamp automation
- **[No Stored Procedures Doctrine](../doctrine/NO_STORED_PROCEDURES_DOCTRINE.md)** - Database logic restrictions and PHP-first validation
- **[TOON Doctrine](../doctrine/TOON_DOCTRINE.md)** - Read-only TOON file handling that supports this database philosophy
- **[Architecture Sync](ARCHITECTURE_SYNC.md)** - Complete system architecture implementing these database principles
- **[WOLFIE Timestamp Doctrine](../developer/dev/WOLFIE_TIMESTAMP_DOCTRINE.md)** - BIGINT(14) YMDHIS UTC timestamp format requirements
