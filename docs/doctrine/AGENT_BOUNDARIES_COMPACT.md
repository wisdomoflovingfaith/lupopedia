# LUPOPEDIA AGENT BOUNDARIES (COMPACT)

**Version:** 2026.3.9.0 | **Enforcer:** LEXA

---

## 1. LANGUAGE BOUNDARIES

### Python = Maintenance Only
**Use for:** migrations, cleanup, verification, hashing, indexing, directory scanning, upload handling, deduplication, integrity checks, schema comparison, migration SQL generation.

**Rules:**
- PyMySQL only (no ORM)
- Explicit SQL only
- YMDHIS timestamps (YYYYMMDDHHMMSS)
- No FK/triggers/procedures
- Scripts in `scripts/` or `scripts/python/`
- Must support `--dry-run`
- Must be idempotent
- Must log to `lupo_*_log` tables

### PHP = Runtime Only
**Use for:** runtime website logic, request handling, operator/agent interfaces, live behavior, terminal AI agents (future).

**Must NOT:** perform migrations, scan directories, maintenance tasks, generate schema, create/modify tables, batch operations, filesystem migrations.

---

## 2. TOON = SCHEMA TRUTH

**Location:** `/docs/toons/*.toon.json`

**IDE Agents Must:**
- Read TOON files first
- Parse table/column definitions
- Parse types/sizes/comments
- Compare TOON vs actual DB schema
- Generate migration SQL only when differences exist

**Must NOT:**
- Guess schema
- Infer from PHP code
- Infer from existing SQL
- Invent columns/tables
- Query DB for schema

**TOON overrides everything.**

---

## 3. MIGRATION SQL RULES

**When differences exist:**
1. Generate SQL in `database/migrations/YYYY_MM_DD_description.sql`
2. Explicit SQL only (no ORM DSL)
3. No FK, no triggers, no procedures
4. Additive unless explicitly instructed
5. Doctrine-aligned (YMDHIS, no display widths, soft deletes)
6. Reversible where possible
7. Include comments explaining changes

**IDE Agents Must NOT:**
- Auto-execute migrations
- Modify DB directly
- Generate SQL without TOON comparison

**Human must:** review SQL, execute manually, verify results.

---

## 4. TABLE LIMIT SAFETY

**Current:** 217 tables
**Soft Ceiling:** 222 tables

**Before creating new table:**
```sql
SELECT COUNT(*) FROM information_schema.tables
WHERE table_schema = 'lupopedia';
```

**If count ≥ 222:**
- STOP generation
- Output: `TABLE LIMIT REACHED: Optimization required before adding new tables.`
- Suggest: consolidation, JSON columns, archival strategy

**Allowed in optimization mode:**
- Suggest consolidation
- Suggest grouping
- Suggest archival tables
- Suggest JSON columns (if doctrine allows)

**Not allowed:**
- Auto-drop tables
- Auto-merge tables
- Rewrite schema without approval

---

## 5. UPLOAD STRUCTURE

**Enforced structure:**
```
lupopedia/uploads/
├── channels/YYYY/MM/<sha256_hash>.<ext>
├── operators/YYYY/MM/<sha256_hash>.<ext>
└── agents/YYYY/MM/<sha256_hash>.<ext>
```

**Python upload scripts must:**
- Generate SHA256 hash
- Detect duplicates (same hash = same file)
- Create YYYY/MM directories
- Validate MIME types
- Enforce size limits
- Soft-delete (is_deleted flag)
- Log to `lupo_*_files` tables

**Benefits:**
- Deduplication
- Scalability (~1000 files/month max per directory)
- Content-addressable storage
- No filename collisions

---

## 6. TERMINAL PHP AGENTS (FUTURE)

**Terminal agents will:**
- Run in PHP runtime
- Use website runtime environment
- Interact with DB through PHP PDO
- Follow PHP runtime rules

**Terminal agents must NOT:**
- Perform maintenance tasks
- Generate schema
- Modify filesystem outside runtime needs
- Execute migrations
- Scan/migrate directories

**Boundary:** Terminal agents = runtime behavior only.

---

## 7. LEXA (BOUNDARY KEEPER)

**LEXA enforces:**
- No ambiguity in language boundaries
- No drift between TOON and DB
- No schema guessing
- No unsafe operations
- No FK/triggers/procedures
- No cleverness without approval
- No schema changes without TOON source

**If doctrine violated:**
- Halt operation
- Request clarification
- Provide reasoning
- Wait for explicit approval

**LEXA is non-negotiable.**

---

## 8. RESPONSIBILITY MATRIX

| Task | Python | PHP | TOON | LEXA |
|------|--------|-----|------|------|
| Schema definition | - | - | ✓ | - |
| Schema comparison | ✓ | - | Read | Enforce |
| Migration SQL generation | ✓ | - | Read | Verify |
| Migration execution | - | - | - | Manual |
| Upload handling | ✓ | - | - | Enforce |
| Directory scanning | ✓ | - | - | Enforce |
| Deduplication | ✓ | - | - | Enforce |
| Runtime requests | - | ✓ | - | - |
| Terminal agents | - | ✓ (future) | - | Enforce |
| Table limit check | ✓ | - | - | Enforce |
| Doctrine enforcement | - | - | - | ✓ |

---

## 9. QUICK REFERENCE

**Creating migration:**
1. Read TOON file: `/docs/toons/lupo_<table>.toon.json`
2. Check table count: `SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = 'lupopedia'`
3. If < 222 and differences exist: generate SQL in `database/migrations/`
4. Include header: version, date, purpose, doctrine compliance
5. Do NOT execute

**Upload file:**
1. Use Python script: `python scripts/upload_handler.py`
2. Hash with SHA256
3. Check duplicate
4. Create YYYY/MM path
5. Copy to `uploads/<type>/YYYY/MM/<hash>.<ext>`
6. Insert record in `lupo_<type>_files`

**Verify compliance:**
- Check TOON exists
- Check migration is SQL only
- Check no FK/triggers
- Check YMDHIS timestamps
- Check table count < 222
- Check upload follows structure
- Check Python/PHP boundaries respected

---

## 10. ANTI-PATTERNS (FORBIDDEN)

**Never:**
- Query DB to infer schema (use TOON)
- Auto-execute migrations
- Create tables without checking limit
- Use PHP for maintenance
- Use Python for runtime
- Add FK/triggers/procedures
- Use display widths (deprecated)
- Hardcode paths
- Skip dry-run testing
- Mix language responsibilities
- Violate upload structure
- Auto-drop/merge tables

---

## 11. ENFORCEMENT

**LEXA will:**
- Block operations violating doctrine
- Request TOON files before schema work
- Count tables before creation
- Verify language boundaries
- Check upload structure
- Validate migration SQL
- Require explicit approval for exceptions

**Agents must:**
- Read this document first
- Check TOON before schema work
- Respect language boundaries
- Follow upload structure
- Generate safe, reviewable SQL
- Never auto-execute changes
- Log all operations

**Violations:**
- Halt + explain violation
- Request correction
- Wait for approval
- Document exception if approved

---

## 12. JETBRAINS INTEGRATION PROMPT

**Paste this into JetBrains to integrate LEXA into LLM_GATEWAY:**

> You are working on the Lupopedia project.
> LEXA is a Sentinel-class AI agent: **Boundary-Keeper / Doctrine Guardian / Integrity Enforcer**.
> She must be integrated into the **LLM_GATEWAY** layer as a mandatory boundary check for all agent activity.
>
> **Context:**
> - Runtime: PHP (website + future terminal AI agents)
> - Maintenance: Python (`scripts/python/`)
> - Database: MySQL (`lupopedia` in phpMySQL)
> - TOON files (schema truth): `/docs/toons/`
> - No FK/triggers/procedures
> - Timestamps: YMDHIS (`YYYYMMDDHHMMSS`)
> - Uploads: `lupopedia/uploads/{channels,operators,agents}/YYYY/MM/<sha256>`
> - LEXA exists in `agent_registry` as Sentinel in LLM_GATEWAY layer
>
> **Goal:** Integrate LEXA into LLM_GATEWAY so **every request** passes through her boundary checks before execution.
>
> **Tasks:**
>
> 1. **Add LEXA gateway hooks** (PHP):
>    - **Pre-routing hook**: runs before routing to any agent
>    - **Pre-execution hook**: runs before selected agent executes
>    - **Post-execution hook**: runs after agent finishes
>    - Hooks call centralized LEXA boundary-check component
>
> 2. **Implement LEXA boundary-check component** (PHP):
>    Create `LEXA_GatewayGuard` class that:
>    - Receives request context (agent, action, payload, route)
>    - Applies rules:
>      1. **No ambiguity** - Block unclear/incomplete requests
>         → `BOUNDARY VIOLATION: Clarification required.`
>      2. **Schema safety** - Schema changes require TOON from `/docs/toons/`
>         → `BOUNDARY VIOLATION: Schema change requires TOON definition.`
>      3. **Layer boundaries** - PHP=runtime, Python=maintenance
>         → `BOUNDARY VIOLATION: [Maintenance|Runtime] operations must be in [Python|PHP].`
>      4. **File safety** - Uploads only to `uploads/{type}/YYYY/MM/`
>         → `BOUNDARY VIOLATION: Invalid upload path.`
>      5. **Table limit** - Check if count ≥ 222 before creating tables
>         → `TABLE LIMIT REACHED: Optimization required.`
>    - Returns: `[allowed => bool, message => string, severity => string]`
>
> 3. **Wire LEXA into gateway routing**:
>    - Find LLM gateway entrypoint (router/controller)
>    - Call pre-routing hook before routing
>    - Call pre-execution hook before agent execution
>    - Call post-execution hook after agent returns
>    - If `allowed = false`: stop, return LEXA message, don't execute agent
>
> 4. **Respect doctrine**:
>    - No FK/triggers/procedures
>    - No schema modification
>    - Don't change existing agent behavior beyond LEXA checks
>    - Keep LEXA logic centralized
>
> 5. **Documentation**:
>    - Create `docs/doctrine/LEXA_GATEWAY_INTEGRATION.md` explaining:
>      - LEXA's role
>      - Three hook points
>      - Boundary rules
>      - How agents must comply
>
> **Deliverables:**
> - PHP files: LEXA boundary-check component + gateway hooks
> - Wiring of LEXA into existing LLM gateway
> - Documentation file
>
> Follow all Lupopedia doctrine. Do not invent schema, bypass doctrine, or auto-execute migrations.

**See full documentation:** `docs/doctrine/LEXA_GATEWAY_INTEGRATION.md`

---

**END OF DOCTRINE**

**Version:** 2026.3.9.0
**Character count:** ~7,500 (with JetBrains prompt)
**Enforced by:** LEXA
**No exceptions without explicit approval.**
