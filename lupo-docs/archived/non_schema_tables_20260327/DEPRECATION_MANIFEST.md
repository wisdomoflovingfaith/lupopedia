---
lupopedia.headers:
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/archived/non_schema_tables_20260327/DEPRECATION_MANIFEST.md"
  web_path: "http://www.lupopedia.com/lupopedia/archived/non_schema_tables_20260327/DEPRECATION_MANIFEST.md"
  last_modified_utc: "20260327"
  channel_id: 42
  actor_id: 23
  actor_name: "HEPHAESTUS"
  faucet_name: "cursor"
  delegation_chain: "wolfie:hephaestus"
  artifact_type: "documentation"
  artifact_kind: "manifest"
  purpose: "Deprecation manifest for aspirational tables moved from active directory"
  tags: ["documentation", "deprecation", "manifest", "4.1.0", "hephaestus"]

lupopedia.edges:
  outbound_edges:
    - { to: "lupo-docs/doctrine/DATABASE_DOCTRINE.md", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260327"
  last_verified_by_actor_id: 23
  last_verified_by_actor_name: "HEPHAESTUS"
---

# DEPRECATION MANIFEST — Aspirational Tables Archive

## Purpose

Documents aspirational and experimental table documentation files that have been moved from the active directory to maintain alignment between documentation and canonical schema.

## Archive Decision

**Authority**: WOLFIE Directive (Thread 2007)  
**Executor**: HEPHAESTUS (actor_id 23)  
**Date**: 2026-03-27  
**Policy**: Remove completely (archive) all tables not in `install_new_lupopedia.sql`

## Archived Tables

### Emotional Framework Tables (Experimental)

| Table | Original Path | Archive Path | Reason |
|--------|---------------|---------------|---------|
| lupo_emotional_constellations.md | lupo-docs/database/lupopedia/tables/active/ | lupo-docs/archived/non_schema_tables_20260327/ | Not in install SQL - experimental emotional mapping |
| lupo_emotional_frameworks.md | lupo-docs/database/lupopedia/tables/active/ | lupo-docs/archived/non_schema_tables_20260327/ | Not in install SQL - experimental emotional framework |
| lupo_emotional_stars.md | lupo-docs/database/lupopedia/tables/active/ | lupo-docs/archived/non_schema_tables_20260327/ | Not in install SQL - experimental star mapping |
| lupo_emotional_translations.md | lupo-docs/database/lupopedia/tables/active/ | lupo-docs/archived/non_schema_tables_20260327/ | Not in install SQL - experimental translation mapping |

### Persona Framework Tables (Aspirational)

| Table | Original Path | Archive Path | Reason |
|--------|---------------|---------------|---------|
| lupo_persona_dialogue_patterns.md | lupo-docs/database/lupopedia/tables/active/ | lupo-docs/archived/non_schema_tables_20260327/ | Not in install SQL - aspirational persona system |
| lupo_persona_profiles.md | lupo-docs/database/lupopedia/tables/active/ | lupo-docs/archived/non_schema_tables_20260327/ | Not in install SQL - aspirational persona profiles |

### Kapu System Tables (Experimental)

| Table | Original Path | Archive Path | Reason |
|--------|---------------|---------------|---------|
| lupo_kapu_events.md | lupo-docs/database/lupopedia/tables/active/ | lupo-docs/archived/non_schema_tables_20260327/ | Not in install SQL - experimental Kapu system |
| lupo_kapu_restoration_paths.md | lupo-docs/database/lupopedia/tables/active/ | lupo-docs/archived/non_schema_tables_20260327/ | Not in install SQL - experimental Kapu restoration |

## Archive Statistics

**Total Tables Archived**: 8  
**Archive Date**: 2026-03-27  
**Archive Location**: `lupo-docs/archived/non_schema_tables_20260327/`

## Rationale

### Doctrine Alignment
- **Database Doctrine**: Requires documentation to align with canonical schema
- **Install SQL**: `install_new_lupopedia.sql` is the single source of truth
- **Active Directory**: Must contain only tables that exist in canonical schema

### Future Considerations
- **Preservation**: All archived tables remain accessible for future reference
- **Reintegration**: Tables can be restored if added to canonical schema
- **Documentation**: Full deprecation manifest maintains traceability

## Impact Assessment

### Benefits
- ✅ **Schema Alignment**: Active directory now matches canonical schema
- ✅ **Clarity**: Removes confusion between deployed and aspirational features
- ✅ **Maintenance**: Reduces documentation maintenance overhead

### Risks
- ⚠️ **Lost Context**: Experimental features may be harder to rediscover
- ⚠️ **Development**: May hinder experimental development visibility

### Mitigations
- 📋 **Archive**: Complete preservation in organized archive structure
- 📋 **Manifest**: Full documentation of rationale and locations
- 📋 **Cross-Reference**: Links maintained to original documentation concepts

## Related Documentation

- [Database Doctrine](../../../doctrine/DATABASE_DOCTRINE.md)
- [Install SQL](../../../mysql/install/install_new_lupopedia.sql)
- [Phase 1 Completion Report](../../reports/Phase1_Completion_Report_20260327.md)

---

*Last updated: 2026-03-27 (4.1.0 remediation)*  
*Maintained by: HEPHAESTUS (actor_id 23) through cursor faucet*
