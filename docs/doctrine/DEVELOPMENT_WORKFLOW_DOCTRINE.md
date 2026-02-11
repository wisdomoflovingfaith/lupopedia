# Development Workflow Doctrine

**Status:** Canonical  
**Applies to:** Lupopedia 4.0.0 development and testing  
**Overrides:** Any previous assumptions about how development and schema changes are applied.

---

## 1. Development Workflow (Non-Negotiable)

During development and testing, the workflow is routinely:

1. **Drop** all tables in the database  
2. **Load** the 34 legacy Crafty Syntax 3.7.5 tables (from `old_crafty_syntax_3_7_5.sql`)  
3. **Load** the old Crafty Syntax `config.php` (so the wizard can detect upgrade)  
4. **Run** the Lupopedia install/upgrade wizard  
5. **Verify** the full upgrade path  
6. **Test** Lupopedia  
7. **Make changes**  
8. **Repeat** the cycle  

Assume this workflow is used constantly during development. The wizard and all tooling must support repeated, idempotent cycles.

---

## 2. Canonical Starting Point: `old_crafty_syntax_3_7_5.sql`

The file **`old_crafty_syntax_3_7_5.sql`** (in `database/migrations/` or as specified in project layout) contains the exact **34 legacy Crafty Syntax tables**.

Treat this file as:

- The **canonical starting point** for all upgrade tests  
- The **baseline schema** for importer logic  
- The **reference** for identity normalization  
- The **reference** for operator detection  
- The **reference** for legacy table dropping  

**Do not modify this file unless explicitly instructed.** The importer expects the legacy schema exactly as defined there.

---

## 3. Absolute prohibitions (enforced)

Lupopedia uses **no frameworks, no middleware, no Composer, no DB logic, no ORM**. Pure procedural PHP + PDO only. No stored procedures, triggers, views, foreign keys, cascades, query builders, or vendor packages. All logic lives in PHP; the database is for storage only. These rules apply to all code generated for the wizard, importer, and application.

---

## 4. Dual-Path Doctrine for Schema Changes

Whenever a schema change is made, apply **all** of the following that apply:

### A. One-time migration file

Add the change to a one-time development migration, e.g.:

- `database/migrations/dev_YYYYMMDD_description.sql`  

Use for: ALTER TABLE, ADD/DROP INDEX, new columns on existing tables. Run once by the developer during the cycle.

### B. New install SQL

Add the **same** change to:

- `database/migrations/install_new_lupopedia.sql`  

So that fresh installs and every future "drop → load Crafty → run wizard" cycle see the new schema after install.

### C. Seed file (if the change affects required rows)

Update:

- `database/migrations/seed_lupopedia.sql`  

When the change affects: required seed rows, registry, default channels, actor/agent seed data, or any required post-schema rows.

### D. Wizard (if the change affects install/upgrade flow)

Update **install.php** (or wizard logic) when the change affects:

- New required tables  
- New required seed rows  
- New identity normalization rules  
- New reserved channels  
- New operator logic  
- Config writer or installer UI  

### Importer SQL

**Never modify** `database/migrations/import_from_old_crafty_syntax.sql` unless **explicitly instructed**. The importer expects the legacy schema exactly as in `old_crafty_syntax_3_7_5.sql`.

---

## 5. Wizard: Repeatable and Self-Healing

Because the development workflow involves repeatedly dropping and reloading Crafty Syntax:

- The wizard must **always** be able to run from scratch  
- The wizard must **always** detect upgrade correctly (when legacy tables exist)  
- The wizard must **always** normalize identities  
- The wizard must **always** import correctly  
- The wizard must **always** create operator channels  
- The wizard must **always** drop legacy tables  
- The wizard must **always** write config  
- The wizard must **always** complete cleanly  

The wizard must be **idempotent**, **self-healing**, and **repeatable**. Doctrine enforcement (reserved channels, import-once skip, operator channels, legacy drop retry) supports this.

---

## 6. Wizard Evolution

As Lupopedia continues to be built:

- New features, tables, seed rows, doctrine, reserved channels, normalization rules, operator logic, config writer, and installer UI will be added.  
- **Do not freeze assumptions.** The wizard will be updated many times during development.  
- Always integrate new doctrine and new schema changes into: one-time migration, install SQL, seed (if needed), and wizard (if needed).

---

## 7. PHP Compatibility and Fallback (Permanent)

Continue to follow:

- PHP 5.3 → 8.1+ compatibility  
- No PHP 7/8-only features (no strict types, typed properties, return types, enums)  
- No modern frameworks  
- ASCII-safe slug generation; no mbstring dependency  
- PDO only  
- Shared-hosting compatibility  

See **PHP_COMPATIBILITY_AND_MINIMAL_HOSTING_DOCTRINE.md**.

---

## 8. Documentation requirements

The following documents must explicitly include: **no frameworks, no middleware, no Composer, no DB logic**, **PHP 5.3 → 8.1+ compatibility**, **fallback requirements**, **dual-path schema rules**, and **wizard repeatability**:

- **docs/doctrine/COMPATIBILITY_MATRIX.md**
- **docs/doctrine/MINIMAL_HOSTING_REQUIREMENTS.md**
- **docs/doctrine/DEVELOPMENT_WORKFLOW_DOCTRINE.md**
- **.cursorrules**

See COMPATIBILITY_MATRIX §6, MINIMAL_HOSTING_REQUIREMENTS §4, and .cursorrules "ABSOLUTE PROHIBITION" section for the full prohibition lists.

---

## 9. Summary

| Action | Where to update |
|--------|------------------|
| Schema change | (A) One-time migration, (B) `install_new_lupopedia.sql`, (C) `seed_lupopedia.sql` if needed, (D) wizard if needed |
| Importer / Crafty mapping | Only when explicitly instructed; do not modify `import_from_old_crafty_syntax.sql` otherwise |
| Legacy baseline | `old_crafty_syntax_3_7_5.sql` — canonical; do not modify unless instructed |
| Development cycle | Drop → load `old_crafty_syntax_3_7_5.sql` → load Crafty config → run wizard → test → repeat |
