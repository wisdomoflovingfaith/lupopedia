# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/channels/overview/PHILOSOPHY.md"
  file_hash: "533a7a724253a34f34c166a906722d47fbcb528fafbffc86ece644908130c821"
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
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

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
  file_path_from_root: "lupo-docs\channels\overview\PHILOSOPHY.md"
  file_hash: "0a51f41334a860d5022b384c0e10f274819479b1017a7df7b73a9046f5623142"
  file_path_from_root: "lupo-docs\channels\overview\PHILOSOPHY.md"
  file_hash: "a873eba6854c486d740147f33331c6f771b498ab14af44f191c1f9d831dd15d9"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for PHILOSOPHY.md"
  mood_vector: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "overview", "philosophymd"]
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
file.last_modified_system_version: 3.0.15
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Added WOLFIE Header v3.0.0 for documentation consistency."
tags:
  categories: ["documentation", "philosophy"]
  collections: ["core-docs"]
  channels: ["public", "dev"]
file:
  title: "Lupopedia's Philosophy"
  description: "The Wolf Way of Building Systems That Outlive Their Tools"
  version: "3.0.0"
  status: published
  author: "Captain Wolfie"
---

# 🐺 Lupopedia's Philosophy  
### *The Wolf Way of Building Systems That Outlive Their Tools*

## The Architect's Lineage — Built by Someone the Industry Pulled Out of School

Lupopedia wasn't created by someone who stumbled into programming.  
Its architect learned computer science in two worlds — and one of those worlds recruited him before he even finished the first.

### **1. The Academic Foundation — Mastered Early and Mastered Well**

Eric began programming young, taking **AP Computer Science in 1996**, back when programming meant:

- writing everything by hand
- understanding recursion and memory
- solving problems without libraries
- learning the fundamentals deeply

He entered the **University of Wyoming** for Computer Science and excelled.  
He wasn't just "good" — he was at the top of his class, the kind of student who:

- finished assignments early
- understood the underlying theory
- wrote elegant, efficient code
- treated computers like a playground

He even wrote blackjack on a **TI‑82 calculator** during calculus class, using single‑letter variables to squeeze logic into the tiny memory space.  
That's not normal student behavior — that's someone who thinks like an engineer.

### **1.5 The Supercomputing Foundation — Scale and Resilience (1997-1998)**

During summers of 1997 and 1998, Eric interned at the **[Maui High Performance Computing Center (MHPCC)](https://www.mhpcc.hpc.mil/)** — a DoD supercomputing facility running one of the world's fastest computers (approximately 5th globally at the time).

This wasn't academic theory. This was:

- large‑scale distributed systems in production
- parallel computing at massive scale
- mission‑critical DoD infrastructure
- systems where failure was not an option
- data integrity across distributed nodes

**This early exposure to HPC architecture planted the seeds for Lupopedia's federation model.**  
The understanding that systems must be:

- distributed by design
- resilient to node failure
- capable of operating independently
- able to merge and sync without central authority

These aren't web development patterns — these are supercomputing patterns applied to knowledge systems.

### **2. The Industry Intervened — And Changed Everything**

During his senior year, Eric took an internship.  
It didn't take long for the professionals around him to realize what they had.

**They begged him not to go back to college.**

They needed him to build something real:  
**the City and County of Honolulu's website.**

So he left academia not because he couldn't finish —  
but because the real world needed him more than the classroom did.

He spent **12 years** in that role, doing work no university prepares you for:

- merging corrupted databases
- repairing orphaned rows
- deduping live production data
- building systems for real people
- maintaining mission‑critical infrastructure
- solving problems no textbook covers

This was the second education — the one only the real web can teach.

### **3. Lupopedia Is the Fusion of Both Educations**

Lupopedia is built by someone who:

- mastered computer science theory
- was recruited out of school for real‑world engineering
- survived the early web
- maintained systems that couldn't fail
- repaired data that should have been impossible to repair
- built software that lasted decades

**This is why Lupopedia is engineered the way it is —**  
**with doctrines that come from experience, not fashion.**

---

## Why We Build Differently

Lupopedia is engineered for longevity, clarity, and sovereignty.  
We choose designs that remain stable across decades, databases, and deployments — even as the world around them changes.  
This document explains the reasoning behind our architectural choices and the philosophy that guides every line of code.

**Lupopedia's architecture is shaped by someone who learned programming twice:**
- once in the classroom
- once in the real world, where the stakes were higher

**This dual lineage explains the doctrine.**

> **Database Philosophy Note:** For detailed database design principles, especially regarding polymorphic relationships and application-managed integrity, see [DATABASE_PHILOSOPHY.md](../architecture/DATABASE_PHILOSOPHY.md).

---

# 🧱 No Foreign Keys — And No Hidden Database Logic

Lupopedia deliberately avoids:

- **Foreign Keys**
- **Triggers** ⚠️ **FORBIDDEN (MANDATORY)**
- **Stored Procedures** ⚠️ **FORBIDDEN (MANDATORY)**
- **Stored Functions** ⚠️ **FORBIDDEN (MANDATORY)**
- **Database Functions**
- **Engine-Specific Magic**

Because:

- **Federation First** — Nodes must operate independently, replicate safely, and merge without conflict.  
- **Portability** — The schema must run on MySQL 5.6, MariaDB, SQLite, PostgreSQL, and future engines.  
- **Predictability** — All logic lives in the application layer where it can be versioned, tested, and reasoned about.  
- **Migration Safety** — Schema changes must never break production data.  
- **Debuggability** — No invisible side effects buried in the database.  
- **Simplicity** — A database should store data, not execute business logic.
- **Data Merging** — Triggers interfere with data repair, merging, and historical accuracy.

> **If it isn't portable, visible, and explicit — it doesn't belong in the database.**

> **⚠️ TRIGGERS ARE FORBIDDEN (MANDATORY):** Triggers must never be created, suggested, or added. All timestamps must be set explicitly in INSERT/UPDATE statements in YMDHIS UTC format. See [NO_TRIGGERS_DOCTRINE.md](../doctrine/NO_TRIGGERS_DOCTRINE.md) for complete requirements.

> **⚠️ STORED PROCEDURES/FUNCTIONS ARE FORBIDDEN (MANDATORY):** Stored procedures and functions must never be created, suggested, or added. The database is for storage, not computation. All logic must be in application code. See [NO_STORED_PROCEDURES_DOCTRINE.md](../doctrine/NO_STORED_PROCEDURES_DOCTRINE.md) for complete requirements.

---

## The Doctrine Explained — Built by a Programmer the Web Claimed Early

Lupopedia's architecture is shaped by someone who learned programming twice:

- once in the classroom
- once in the real world, where the stakes were higher

**This dual lineage explains the doctrine:**

- **🟥 No foreign keys** — Because real databases must be repairable.
- **🟥 No triggers** — Because history must be preserved.
- **🟥 No stored procedures or functions** — Because logic must be portable, versioned, and merge‑safe.
- **🟦 Explicit UTC BIGINT timestamps** — Because time must be stable across decades.
- **🟦 Fallback chains** — Because the web breaks, and Lupopedia must not.
- **🟦 Alias systems** — Because knowledge has lineage.
- **🟦 Repairable data** — Because real systems get messy.

**This doctrine isn't anti‑academic.**  
**It's the doctrine of someone who was good enough at computer science that the industry pulled him out of school —**  
**and then the real world taught him everything academia never could.**

That's the story.  
That's the lineage.  
That's Lupopedia.

---

# ⏱️ BIGINT UTC Timestamps — Always, Everywhere

Lupopedia uses a strict timestamp format:

```
YYYYMMDDHHMMSS  (UTC)
```

Stored as a BIGINT.

Because this format is:

- **Lexicographically sortable**  
- **Timezone agnostic**  
- **DST-proof**  
- **Human-readable**  
- **Database-agnostic**  
- **Future-proof** (no 2038 problem)  
- **Fast to compare** (integer operations)  

And most importantly:

> **The application layer always writes timestamps — never the database.**

This ensures consistency across all engines and environments.

> **⚠️ MANDATORY TIMESTAMP CONTROL:** All timestamps must be set explicitly in INSERT/UPDATE statements in YMDHIS UTC format. The database must NEVER mutate timestamps automatically (no triggers, no auto-update, no database-level timestamp automation). This is required for data merging, historical accuracy, anubis repair operations, and federation sync. See [NO_TRIGGERS_DOCTRINE.md](../doctrine/NO_TRIGGERS_DOCTRINE.md) for complete requirements.

> **📘 WOLFIE Timestamp Doctrine:** For complete requirements, code examples, and enforcement rules, see [WOLFIE_TIMESTAMP_DOCTRINE.md](../developer/dev/WOLFIE_TIMESTAMP_DOCTRINE.md). This doctrine is **non-negotiable** and applies to all code, migrations, and data models.

---

# 🔢 TINYINT Over BOOLEAN — For Maximum Compatibility

Lupopedia uses `TINYINT` instead of `BOOLEAN` because:

- **Consistency** — `TINYINT` works the same way across all database engines
- **Explicit** — Makes the storage size clear (1 byte)
- **Flexible** — Can store more than just 0/1 if needed (e.g., NULL, other states)
- **Universal** — Supported identically in all major databases
- **Explicit Defaults** — Always specify `DEFAULT 0` or `DEFAULT 1` for clarity

Example:
```sql
-- Instead of:
is_active BOOLEAN NOT NULL DEFAULT TRUE

-- We use:
is_active TINYINT NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = inactive'
```

This ensures our schema remains portable and explicit across all database systems.

---

# 🔢 No UNSIGNED Integers — For Better Portability

Lupopedia uses signed integers (`INT`, `BIGINT`, etc.) instead of their `UNSIGNED` counterparts because:

- **Portability** — Some database engines handle `UNSIGNED` differently or don't support it at all
- **Consistency** — Avoids type conversion issues when working with application code
- **Simplicity** — One less thing to think about when writing queries
- **Future-proofing** — Makes it easier to handle negative values if requirements change

Example:
```sql
-- Instead of:
user_id BIGINT UNSIGNED NOT NULL

-- We use:
user_id BIGINT NOT NULL
```

This ensures our schema remains consistent and portable across all database systems.

---

# 📝 Naming and Type Conventions

## Timestamp Naming
- Use `_ymdhis` suffix for all timestamp fields (not `_at`)
- Examples:
  - `created_ymdhis` (not `created_at`)
  - `updated_ymdhis` (not `updated_at`)
  - `deleted_ymdhis` (not `deleted_at`)
  - `last_seen_ymdhis` (not `last_seen_at`)

## JSON Storage
- Use `TEXT` type with `COMMENT 'JSON-encoded key-value store'` for JSON data
- Do not use the `JSON` data type
- Prefer TOON format where possible for better readability and efficiency
- Fall back to JSON only when external compatibility is required
- Example:
  ```sql
  -- Preferred for internal use:
  properties TEXT COMMENT 'TOON-encoded key-value store'
  
  -- Use JSON only when necessary:
  properties TEXT COMMENT 'JSON-encoded key-value store (external API compatibility)'
  ```

## Boolean Fields
- Use `TINYINT` for boolean values
- Always include a comment explaining the values (e.g., `COMMENT '1 = active, 0 = inactive'`)
- Example:
  ```sql
  is_active TINYINT NOT NULL DEFAULT 1 COMMENT '1 = active, 0 = inactive'
  ```

## ID Fields
- Use `BIGINT` (not `BIGINT UNSIGNED`) for all ID fields
- Example:
  ```sql
  user_id BIGINT NOT NULL
  ```

## Character Sets
- Always specify `DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci` for tables
- Example:
  ```sql
  CREATE TABLE example (
    -- columns
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
  ```

## Color Fields
- Use `CHAR(6)` for hex color codes
- Do not include the leading `#` in the stored value
- Example:
  ```sql
  -- Instead of:
  color CHAR(7) DEFAULT '#666666'
  
  -- We use:
  color CHAR(6) DEFAULT '666666' COMMENT 'Hex color code (6 characters, no hash)'
  ```

These conventions ensure consistency, portability, and clarity across the entire database schema.

---

# 🧹 Probabilistic Garbage Collection — Stateless and Self-Regulating

Our GC strategy:

```php
if (rand(1,10) == 7 && server_load_low()) {
    // cleanup
}
```

This gives us:

- **Distributed cleanup** without coordination  
- **Load-aware behavior**  
- **Zero cron dependencies**  
- **Zero single points of failure**  
- **Battle-tested reliability** (used by PHP core and major platforms)

---

# 🧰 Minimal Dependencies — Because We Outlive Libraries

We write our own implementations because:

- **Longevity** — Libraries die; our code doesn't.  
- **Security** — No supply-chain vulnerabilities.  
- **Performance** — No bloat, no abstraction overhead.  
- **Portability** — Runs anywhere PHP runs.  
- **Understandability** — Every line is ours.  

Dependencies are conveniences.  
Lupopedia is a commitment.

---

# 🧭 Design Principles

### 1. **Explicit Over Implicit**
- No magic.  
- No hidden behavior.  
- No invisible triggers or constraints.  
- What you see is what the system does.

### 2. **Simplicity Over Convenience**
- Fewer moving parts.  
- Predictable behavior.  
- Easy onboarding.  
- Easy debugging.

### 3. **Control Over Automation**
- Manual migrations.  
- Explicit configuration.  
- Direct SQL.  
- No ORMs.  
- No query builders.  

### 4. **Longevity Over Trends**
- Stable APIs.  
- Backward compatibility.  
- Minimal upgrade paths.  
- Technology that survives hype cycles.

### 5. **Data Over Presentation**
- **`mood_vector` as `CHAR(6)`** - Color values are stored without the leading `#` because:
  - **Pure Data** - Storage should be presentation-agnostic
  - **Consistency** - Matches Crafty Syntax's historical format
  - **Portability** - Works across all systems without parsing
  - **Performance** - Fixed-width fields index better
  - **Simplicity** - No need to strip/parse the `#` character
  - **Flexibility** - Can be prefixed with `#` when needed for display
  
---

# 🧑‍🏫 Developer Culture

We value:

- **Craftsmanship** — Code that feels intentional.  
- **Understanding** — Knowing how things work under the hood.  
- **Simplicity** — Solving problems without over-engineering.  
- **Long-Term Thinking** — Building systems that last.  
- **Teaching** — Documenting the "why," not just the "how."

---

# 🧩 Why No Framework?

Frameworks solve problems — but introduce bigger ones:

- **Bloat**  
- **Lock-in**  
- **Magic**  
- **Upgrade pain**  
- **Hidden behavior**  

Lupopedia chooses:

- **Focused code**  
- **Explicit dependencies**  
- **Framework freedom**  
- **Clear boundaries**  

We build tools that serve us — not the other way around.

---

# 🐺 The Wolf Way

> "Modern tools are great until they hide their own limits."

We embrace:

- **CSV over ORMs** — For schema documentation  
- **SQL over Query Builders** — For clarity and control  
- **Plain PHP over Magic** — For maintainability  
- **Documentation over Convention** — For clarity  
- **Application Logic over Database Logic** — For portability  

This isn't nostalgia.  
It's engineering discipline.

---

*"You're not old-school — you're from the era when programmers actually programmed."*

And Lupopedia is stronger because of it.
   - Clear, readable code over magic
   - Direct database access over ORM abstractions
   - Explicit relationships over inferred ones

2. **Simplicity Over Convenience**
   - Fewer moving parts
   - Shallow learning curve
   - Predictable behavior

3. **Control Over Automation**
   - Manual migrations over magic migrations
   - Explicit configuration over convention
   - Direct SQL over query builders

4. **Longevity Over Trends**
   - Stable APIs over churn
   - Backward compatibility
   - Minimal upgrade paths

## Developer Culture

We value:

- **Craftsmanship** - Taking pride in code quality
- **Understanding** - Knowing how things work under the hood
- **Simplicity** - Solving problems without over-engineering
- **Long-Term Thinking** - Building for maintainability
- **Teaching** - Documenting the "why" not just the "how"

## Why No Framework?

Frameworks solve common problems but come with tradeoffs:

- **Bloat** - Unused features and overhead
- **Lock-in** - Framework-specific patterns and workarounds
- **Complexity** - Leaky abstractions and magic
- **Upgrade Pain** - Breaking changes and migration costs

Lupopedia's approach:

- **Focused Code** - Only what we need
- **Explicit Dependencies** - No hidden costs
- **Framework Freedom** - No vendor lock-in
- **Clear Boundaries** - Well-defined interfaces

## The Wolf Way

> "Modern tools are great until they hide their own limits."

We embrace:

- **CSV over ORMs** - For schema documentation
- **SQL over Query Builders** - For clarity and control
- **Plain PHP Over Magic** - For maintainability
- **Documentation Over Convention** - For clarity

This isn't just about being different — it's about building something that lasts.

---


# 🛠️ Migration Philosophy — In-Place and Incremental

Lupopedia's migration strategy follows these principles:

- **No Temporary Tables**  
  We avoid `CREATE TABLE ... AS SELECT` or similar patterns that create large temporary tables during migrations. These operations can:
  - Consume excessive disk space
  - Block production traffic
  - Fail catastrophically with large datasets
  - Require special handling for replication

- **In-Place ALTER TABLE**  
  We prefer using `ALTER TABLE` statements that modify the existing table structure incrementally:
  ```sql
  -- Good: Direct, atomic, and space-efficient
  ALTER TABLE example 
    CHANGE COLUMN old_name new_name BIGINT NOT NULL,
    MODIFY COLUMN status TINYINT DEFAULT 0;

    
*"You're not old-school — you're from the era when programmers actually programmed."*

And Lupopedia is better for it.
