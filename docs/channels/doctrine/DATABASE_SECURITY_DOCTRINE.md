---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
updated: 2025-01-12
author: GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: COPILOT
  target: @everyone
  message: "Database permissions lockdown is one of the most stabilizing, sanity-restoring moves you can make. This doctrine establishes mandatory security rules for database access, user privileges, and permission separation. These rules exist because the founder has a 25-year legacy codebase, a global footprint of auto-installs, and an 11-year gap in modern security evolution."
  mood: "FF6600"
tags:
  categories: ["documentation", "doctrine", "security", "database", "mandatory"]
  collections: ["core-docs", "doctrine"]
  channels: ["dev", "architecture", "security"]
in_this_file_we_have:
  - Database Security Doctrine (Mandatory)
  - Principle: Web Process Must Never Be DB Root
  - Create Dedicated DB User for Lupopedia
  - Separate Users for Separate Roles
  - Remove Dangerous Global Privileges
  - Enforce DB Cannot Change Code Doctrine
  - Limit App User to Single Database
  - Enforce Strong Passwords
  - Disable Remote DB Access
  - Turn Off Root Login from Anywhere
  - Doctrine-Aligned Summary
file:
  title: "Database Security Doctrine (Mandatory)"
  description: "Mandatory security rules for database access, user privileges, and permission separation in Lupopedia. Establishes that the web process must never be DB root, requires dedicated users with minimal privileges, enforces role separation, and prevents database from modifying code."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# 🔒 DATABASE SECURITY DOCTRINE (MANDATORY)

**Database Permissions Lockdown and Security Rules**

**Status:** Mandatory  
**Applies To:** All Lupopedia installations  
**Priority:** Critical Security

---

## Purpose

Locking down database permissions is one of the most stabilizing, sanity-restoring moves you can make. This doctrine establishes mandatory security rules for database access, user privileges, and permission separation.

These rules exist because:
- The founder has a 25-year legacy codebase
- A global footprint of auto-installs
- An 11-year gap in modern security evolution

**This part is straightforward, mechanical, and 100% under your control.**

---

## 1. Principle: The Web Process Must Never Be "DB Root"

**Your web server should NEVER connect as:**

- `root`
- `admin`
- or any account with global privileges

This is the #1 mistake legacy systems made in the 2000s–2010s.

### **The Rule:**

**The web app gets a DB user with only the permissions it absolutely needs.**

Nothing more.

---

## 2. Create a Dedicated DB User for Lupopedia

In phpMyAdmin (or your database administration tool), create a user like:

```
lupo_app
```

Then assign:

### **Database-specific privileges ONLY:**

- `SELECT`
- `INSERT`
- `UPDATE`
- `DELETE`

**That's it.**

### **No:**

- `DROP`
- `ALTER`
- `CREATE`
- `GRANT`
- `FILE`
- `SUPER`
- `PROCESS`
- `TRIGGER`
- `EVENT`
- `LOCK TABLES`
- `SHOW DATABASES`

Your app should never need these.

---

## 3. Separate Users for Separate Roles (Doctrine-Friendly)

Your architecture benefits from **role separation**.

### **App User (web requests)**
- `SELECT`, `INSERT`, `UPDATE`, `DELETE`
- Used by: Web server, PHP application
- Scope: Single database only

### **Migration/Upgrade User (CLI only)**
- `ALTER`, `CREATE`, `DROP`, `INDEX`, etc.
- Used by: Migration scripts, upgrade tools
- **Never used by the web server**
- Scope: Single database only

### **Read-Only User (future analytics, federated nodes)**
- `SELECT` only
- Used by: Analytics tools, federated node queries
- Scope: Single database only

**This prevents a single compromised credential from destroying your system.**

---

## 4. Remove Dangerous Global Privileges

In phpMyAdmin, check the user's privileges.

If you see **ANY** of these checked:

- `GRANT OPTION`
- `ALL PRIVILEGES`
- `CREATE USER`
- `SUPER`
- `FILE`
- `PROCESS`
- `RELOAD`
- `SHUTDOWN`

**Turn them off immediately.**

These are catastrophic if leaked.

---

## 5. Enforce "DB Cannot Change Code" Doctrine

Your doctrine already says:

> "The database must never be able to modify code."

So ensure:

- The DB user cannot write to the filesystem
- The web user cannot write to code directories
- No DB-stored templates or PHP fragments are executed
- No "dynamic includes" based on DB values

**This prevents the classic Crafty Syntax era exploit:**  
**"Inject PHP into a template field → execute on page load."**

---

## 6. Limit the App User to a Single Database

In phpMyAdmin:

- Assign privileges **only** to `lupopedia` (or your database name)
- Do NOT give "global" privileges
- Do NOT give access to `mysql`, `information_schema`, or `performance_schema`

**This prevents cross-database attacks.**

---

## 7. Enforce Strong Passwords (Modern Standard)

Use a long, random password:

- 32+ characters
- Mixed case
- Symbols
- No dictionary words

**Your 2010-era passwords won't cut it in 2026.**

---

## 8. Disable Remote DB Access (Unless You Explicitly Need It)

In `my.cnf` (MySQL configuration):

```
bind-address = 127.0.0.1
```

This ensures:

- No one can connect to MySQL from outside the server
- Only local processes (your app) can access it

**This alone blocks 90% of automated attacks.**

---

## 9. Turn Off "Allow root login from anywhere"

If phpMyAdmin shows:

```
root@%
```

**Delete it.**

Replace with:

```
root@localhost
```

**This prevents remote brute-force attacks.**

---

## 10. Doctrine-Aligned Summary

Your DB lockdown should follow your architectural philosophy:

### **Doctrine Rule:**
> "The database is a passive storage engine.  
> It cannot modify code, cannot escalate privileges, and cannot act outside its schema."

### **Loader Rule:**
> "All schema changes occur through controlled, human-driven migrations."

### **Agent Rule:**
> "No AI agent may request or modify DB permissions."

---

## Implementation Checklist

- [ ] Create dedicated `lupo_app` user with only SELECT/INSERT/UPDATE/DELETE
- [ ] Remove all global privileges from web app user
- [ ] Create separate migration user (CLI only) with ALTER/CREATE/DROP
- [ ] Remove dangerous privileges (GRANT, SUPER, FILE, PROCESS, etc.)
- [ ] Limit app user to single database only
- [ ] Set strong password (32+ characters, random)
- [ ] Configure `bind-address = 127.0.0.1` in my.cnf
- [ ] Remove `root@%` and use `root@localhost` only
- [ ] Verify DB user cannot write to filesystem
- [ ] Verify no dynamic includes from database values

---

## Related Documentation

- **[AI Integration Safety Doctrine](AI_INTEGRATION_SAFETY_DOCTRINE.md)** — AI cannot modify security-critical logic
- **[No Foreign Keys Doctrine](NO_FOREIGN_KEYS_DOCTRINE.md)** — Database is passive storage
- **[No Triggers Doctrine](NO_TRIGGERS_DOCTRINE.md)** — No database-side logic
- **[No Stored Procedures Doctrine](NO_STORED_PROCEDURES_DOCTRINE.md)** — All logic in application layer
- **[Migration Doctrine](MIGRATION_DOCTRINE.md)** — Schema changes through controlled migrations

---

## Version History

- **1.0.0** (2025-01-12) — Initial database security doctrine defining mandatory permission lockdown rules

---

*This doctrine is mandatory and non-negotiable. Database security is foundational to Lupopedia's stability and safety. These rules prevent catastrophic exploits and align with the architectural philosophy that the database is a passive storage engine, not an active system component.*
