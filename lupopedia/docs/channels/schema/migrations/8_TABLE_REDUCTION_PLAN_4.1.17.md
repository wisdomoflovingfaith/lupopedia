# 8‑Table Reduction Plan (Doctrine Compliance)

**Target version:** 4.1.17  
**Purpose:** Reduce table count toward doctrine target.  
**Status:** Plan; execute after freeze lift or approval.

---

## Candidate Tables for Removal (Legacy, Safe, Unused)

1. `livehelp_channels`
2. `livehelp_messages`
3. `livehelp_modules`
4. `livehelp_modules_dep`
5. `livehelp_operator_channels`
6. `livehelp_referers_monthly`
7. `livehelp_sessions`
8. `livehelp_visit_track`

---

## Why These 8

- No INSERT targets in `craftysyntax_to_lupopedia_mysql.sql`
- No JOIN dependencies in the migration script
- No modern Lupopedia equivalents in scope
- Fully deprecated in migration audit
- Removal reduces table count toward doctrine target

---

## Patch Version Recommendation

- Perform reduction in **4.1.17**
- Document in CHANGELOG + dialogs
- Regenerate TOON files after DROP

---

## Pre‑reduction Checklist

- [ ] Confirm migration script has run and legacy data is migrated or archived
- [ ] Confirm no application code references these tables
- [ ] Backup or document final row counts if needed
- [ ] Run DROP in migration; then regenerate TOON
