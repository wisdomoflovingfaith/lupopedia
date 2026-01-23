# DOCTRINE: GLOBAL TABLE CEILING (199 TABLES)

**Filename:** doctrine/TABLE_LIMIT_CONSTRAINT.md  
**Status:** Architectural Hard Limit  
**Authority:** High (below Ethical Foundations, above AAL)  
**Version:** 1.0

## 1. HARD LIMIT: 199 TABLES PER DATABASE

### 1.1 MAX_TABLES_PER_DATABASE = 199  
No database in the Lupopedia ecosystem may exceed 199 total tables at rest.

### 1.2 This limit is absolute and applies to:

- lupopedia (canonical shipping DB)
- lupopedia_worms (experimental/ORM sandbox)
- any future databases

### 1.3 Purpose of the limit:

- Prevent schema explosion
- Maintain human auditability
- Keep migrations sane
- Stop AI‑generated ORM drift
- Preserve long‑term maintainability

## 2. MIGRATION EXCEPTION (TEMPORARY OVERAGE ALLOWED)

### 2.1 Migrations ARE allowed to temporarily exceed 199 tables only if:

- The migration is actively running
- The overage is temporary
- Deprecated tables are dropped before the migration completes
- The final post‑migration table count is ≤ 199

### 2.2 This is called the "Migration Overage Window."

### 2.3 The window closes the moment the migration finishes.
At that moment, the table count must be ≤ 199 or the migration is invalid.

## 3. DEPRECATED TABLE DROP REQUIREMENT

### 3.1 Any migration that adds tables must include a cleanup step that:

- Drops deprecated tables
- Drops replaced tables
- Drops obsolete tables
- Drops temporary staging tables

### 3.2 The cleanup step is mandatory and must run before the migration is considered successful.

### 3.3 A migration that ends with >199 tables is considered failed and must be rolled back.

## 4. MULTI‑DATABASE ARCHITECTURE

### 4.1 Canonical DB:

- lupopedia
- Doctrine‑aligned
- Human‑authored schema
- No AI‑generated tables

### 4.2 Experimental DB:

- lupopedia_worms
- AI sandbox
- ORM experiments allowed
- Still subject to the 199 table limit

### 4.3 Additional DBs require explicit human directive.

## 5. ORM RESTRICTIONS

### 5.1 ORMs are not permitted in lupopedia.

### 5.2 ORMs are allowed in lupopedia_worms only.

### 5.3 AI must not:

- Generate ORM classes for lupopedia
- Infer ORM mappings from doctrine
- Auto‑create tables in canonical DB
- Expand schema without human approval

## 6. COMPLIANCE

### 6.1 Any violation triggers:

- Immediate halt
- Human escalation
- Schema audit

### 6.2 Agents must load this doctrine before any schema reasoning.
