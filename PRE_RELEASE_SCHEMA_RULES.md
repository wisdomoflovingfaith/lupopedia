# PRE_RELEASE_SCHEMA_RULES.md  
**Doctrine: Schema Behavior During the Freedom Zone (Pre‑2026.4.0.1)**

---

## 1. Purpose

These rules define how schema changes must be handled during the **Freedom Zone**, the pre‑release development period before Lupopedia's first stable version (**2026.4.0.1**).

During this phase:

- The schema is fluid.  
- Backward compatibility is not required.  
- Agents must tolerate rapid evolution.  
- Doctrine and manifests may change frequently.  

These rules ensure that schema changes remain safe, intentional, and aligned with the development workflow.

---

## 2. Source of Truth for Schema (Pre‑Release)

### **Rule 1 — The live MySQL database is the authoritative schema.**

All PHP code, runtime logic, and application behavior must read from:

- the **live MySQL database**, not TOON files  
- the **browser‑verified schema** (phpMyAdmin)  

### **Rule 2 — TOON files are for IDE agents only.**

TOON files exist to:

- give IDE agents a stable, parseable snapshot  
- allow schema introspection without DB access  
- support migration generation  
- support doctrine alignment  

TOON files are **not** used by:

- PHP runtime  
- application logic  
- production code  

---

## 3. TOON File Generation

### **Rule 3 — Agents may run the TOON generator when needed.**

Agents may execute:

```
PS C:\ServBay\www\servbay\lupopedia\database> python .\generate_toon_files.py
```

This regenerates:

- `database/toon_data/*.toon`  
- `database/toon_data/*.txt`  

These files reflect the **current live schema**.

### **Rule 4 — TOON files remain untracked unless part of a snapshot event.**

They are:

- volatile  
- ignored by git  
- never committed automatically  
- only promoted during a DB Snapshot Event  

---

## 4. MySQL Access and the "Broken" mysqli Scoop

### **Rule 5 — mysqli scoop is intentionally disabled.**

This is by design.

Reason:

- Prevents agents from auto‑creating tables  
- Prevents schema hallucination  
- Prevents accidental migrations  
- Ensures schema changes remain founder‑controlled  

Agents must not attempt to "fix" or "restore" mysqli scoop.

### **Rule 6 — MySQL is fully functional via the browser.**

Agents must assume:

- MySQL is working  
- phpMyAdmin is authoritative  
- schema changes are valid  
- the database is healthy  

Agents must not fall back to reading schema from PHP files.

---

## 5. Migration SQL Files

### **Rule 7 — Agents must generate migration SQL files for schema changes.**

All schema changes must be expressed as:

```
database/migrations/*.sql
```

Migration files must:

- be delta‑only  
- reflect the live schema  
- be derived from TOON files or doctrine  
- never be applied automatically  
- never be executed without explicit instruction  

### **Rule 8 — Agents must not modify the live DB directly.**

Only the founder may:

- apply migrations  
- alter tables  
- drop tables  
- rebuild schema  

Agents generate SQL; humans execute SQL.

---

## 6. PHP Runtime Behavior

### **Rule 9 — PHP must always read from the live database.**

PHP code must:

- query MySQL directly  
- never read `.toon` files  
- never parse schema from PHP files  
- never rely on fallback logic  

TOON files are for IDE agents only.

### **Rule 10 — No fallback to file‑based schema.**

Agents must not:

- infer schema from PHP  
- parse PHP files for table definitions  
- assume Crafty Syntax fallback behavior  

Lupopedia is a database‑first system.

---

## 7. Agent Responsibilities

### **Cascade (Doctrine + Changelog)**

- Reads TOON files  
- Documents schema changes  
- Updates `CHANGELOG.md`  
- Never modifies DB exports  
- Never applies migrations  

### **JetBrains (Repo Hygiene + Manifests)**

- Reads TOON files  
- Ensures `.gitignore` ignores DB exports  
- Commits migration files when instructed  
- Never commits `.toon` or `.txt` files  

### **Cursor (SQL + Schema Validation)**

- Reads TOON files  
- Generates migration SQL  
- Validates schema consistency  
- Never applies migrations  

---

## 8. Freedom Zone Allowances

During the Freedom Zone:

- Tables may be dropped and recreated  
- Schema may be redesigned  
- Navigation rules may be rewritten  
- Semantic layers may be restructured  
- Doctrine may be reorganized  
- TOON files may be regenerated frequently  

Agents must tolerate this volatility.

---

## 9. Transition to Stability

These rules remain in effect until:

### **Version 2026.4.0.1 — First Stable Release**

At that point:

- schema freezes  
- backward compatibility begins  
- migrations become mandatory  
- doctrine stabilizes  
- TOON files become secondary  

---

## 10. Summary

The Pre‑Release Schema Rules ensure:

- agents read schema from TOON files  
- PHP reads schema from MySQL  
- mysqli scoop remains disabled  
- migration SQL is generated, not applied  
- schema changes remain founder‑controlled  
- the system can evolve rapidly during the Freedom Zone  

This doctrine protects both **creative freedom** and **system integrity** during early development.

---
