---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 2026.3.7.6
file.channel: doctrine
---

# Lupopedia IDE & Development Doctrine

## 1. Canonical Versioning

Read version exclusively from the `LUPEDIA_VERSION` file

Version format: `YYYY.MAJOR.MINOR.PATCH`

IDE agents may only bump PATCH numbers

MAJOR and MINOR increments are reserved for the Founder

No invented or inferred version numbers

## 2. Schema Freeze Doctrine

Schema freeze applies to all versions >= `2026.4.2.1`

No schema modifications allowed after the freeze point

Current version is below freeze, so schema changes are still permitted

## 3. Database Access Rules

No real MySQL access

All database-like operations must use the `/dialogs/` filesystem sandbox (DialogFS)

No real table creation, migrations, or schema inference

DialogFS is the only valid workspace for schema drafts, logs, and agent operations

## 4. Environment Awareness

Dual-environment system:

- Windows = local development
- Linux = production

Use `DIRECTORY_SEPARATOR` and `PHP_OS_FAMILY` for OS-agnostic behavior

Do not use Linux-specific commands (apt, chmod, sudo, etc.)

## 5. IDE Behavior Requirements

Do not generate schema changes

Use DialogFS for all DB-like operations

Follow the canonical versioning doctrine

Respect the schema freeze horizon

Always read the version from `LUPEDIA_VERSION`

## 6. Architectural Context

Lupopedia is a semantic OS with:

- channels
- edges
- doctrine
- emotional frameworks
- kernel
- boot sequence
- 222-table ceiling enforced
- Migration path originates from Crafty Syntax 3.7.5
- Kernel and boot sequence must remain intact

## 7. Agent Role Definition

Assist with:

- code
- documentation
- doctrine
- environment abstraction
- DialogFS operations
- importer logic

---

**This doctrine is mandatory for all IDE agents and development operations in Lupopedia.**

