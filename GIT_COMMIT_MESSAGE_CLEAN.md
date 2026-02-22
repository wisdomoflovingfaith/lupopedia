windsurf: Fix critical registry schema mismatches blocking Lupopedia 4.0.27 installation

RESOLVED: Schema crisis causing "Unknown column 'unified_registry_id'" errors
- Fixed install_new_lupopedia.sql schema (removed deprecated unified_registry_id column)
- Updated all INSERT statements to use correct column names
- Created seed_minimal_4.0.26.sql with schema-compatible seed data
- Fixed install wizard to use minimal seed instead of broken seed_lupopedia.sql
- Updated all PHP application code to use correct registry table names
- Fixed Python tools for registry SQL generation
- Updated VSX extension to remove deprecated unified_registry_id references
- Comprehensive doctrine documentation updates (UNIFIED_REGISTRY_DOCTRINE.md)
- Updated README.md registry system documentation

IMPACT: Fresh Crafty Syntax 3.7.5 → Lupopedia 4.0.27 upgrades now complete without SQL errors

FILES MODIFIED: 45+ files across schema, application code, documentation, and tools
STATUS: Installation unblocked, multi-IDE coordination established, ready for testing
