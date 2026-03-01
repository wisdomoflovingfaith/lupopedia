# ANUBIS Legacy File Guard

## Governance Notice

**Effective Date**: 2026-02-28  
**Version**: 4.0.52  
**Authority**: Windsurf (1002)  

## Canonical Authority

The **only authoritative ANUBIS doctrine file** is:

```
docs/doctrine/ANUBIS/ANUBIS_CANONICAL.md
```

## Legacy File Restrictions

Any new file matching `ANUBIS_*.md` (excluding `ANUBIS_CANONICAL.md`) requires:

### Mandatory Requirements
1. **Explicit Version Bump**: Must target v4.1.0 or higher
2. **Governance Approval**: Requires Channel 42 consensus
3. **FLARE Header Compliance**: Must include complete FLARE structure
4. **Actor ID Verification**: Must reference ANUBIS actor_id 19

### Prohibited Files
The following file patterns are **PROHIBITED** from creation:
- `ANUBIS_OVERVIEW.md` (use canonical)
- `ANUBIS_PROGRAM_SPEC.md` (use canonical)
- `ANUBIS_ORPHAN_RULES.md` (use canonical)
- `ANUBIS_IMPLEMENTATION_SUMMARY.md` (use canonical)
- Any duplicate or variant naming

## Enforcement Mechanism

### Automated Guards
- **CI Validation**: `bin/guard_anubis_structure.php` will fail builds on violations
- **Reference Audit**: `tools/anubis_reference_audit.txt` tracks legacy references
- **Hash Verification**: `docs/doctrine/ANUBIS/ANUBIS_CANONICAL.lock` prevents unauthorized changes

### Manual Review Process
1. **Proposal**: Submit change proposal to Channel 42
2. **Review**: Governance review by Captain Wolfie (10000)
3. **Approval**: Explicit approval required before implementation
4. **Implementation**: Only after approval and version bump

## Consequences of Violation

### Build Failures
- CI/CD pipeline will reject commits with prohibited files
- Automated guards will fail with specific violation details
- Version control hooks will prevent merges

### Governance Actions
- Violations logged in governance registry
- Repeated violations may result in access restrictions
- Emergency rollback procedures may be invoked

## Historical Context

### Previous Consolidation
- **Date**: 2026-02-28
- **Files Archived**: 6 original files moved to `docs/archive/ANUBIS/pre_4.0.52/`
- **Canonical Created**: `docs/doctrine/ANUBIS/ANUBIS_CANONICAL.md`
- **Lead Agent**: Windsurf (1002)

### Archive Access
- **Historical Reference**: Archived files available for reference
- **Current Authority**: Only canonical file should be used
- **Version Control**: Archive preserves historical context

## Contact and Escalation

### Governance Questions
- **Channel 42**: Primary governance venue
- **Captain Wolfie**: Final authority (actor_id 10000)
- **Emergency**: Contact via Channel 42 broadcast

### Technical Issues
- **Guard Failures**: Check `bin/guard_anubis_structure.php` output
- **Reference Problems**: Review `tools/anubis_reference_audit.txt`
- **Hash Mismatches**: Verify against `docs/doctrine/ANUBIS/ANUBIS_CANONICAL.lock`

---

**Guard Established**: 2026-02-28  
**Guard Maintainer**: Windsurf (1002)  
**Version**: 4.0.52  
**Status**: ✅ ACTIVE
