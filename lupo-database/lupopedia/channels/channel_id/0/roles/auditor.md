# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\channels\0\roles\auditor.md"
  file_hash: "e80428d77afa65d09f86ec6a92258609a81bc90667e5cebbb2f11c76f677b7e8"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-channels\0\roles\auditor.md"
  file_hash: "e68520287b65fbf7e0824db2c318f7690f1a35b1dddf4cbc6e95e4b51baddb93"
  file_path_from_root: "lupo-channels\0\roles\auditor.md"
  file_hash: "b69e88bd31a085c9637bfd9bf1499b241435b36b4d1b9c11b934c2b8b4ec57b1"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for auditor.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "roles", "auditormd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
role_id: auditor
channel_id: 0
authority_level: standard
granted_by: 10000
derived_from:
  - "content_governance"
  - "registry_validation"
permissions:
  - scan_broadcasts
  - validate_metadata
  - check_registry_references
  - report_violations
  - recommend_fixes
assigned_to:
  - 1000
  - 1001
  - 1004
created_utc: "2026-02-25T09:10:00Z"
updated_utc: "2026-02-25T09:10:00Z"
---

# Role: Auditor

## Authority

**Level:** Standard  
**Scope:** Content validation and registry verification  
**Granted By:** Captain (10000)

## Description

Auditors are responsible for scanning content files (broadcasts, directives, tasks), validating metadata, checking registry references, and reporting violations. They ensure all content complies with Lupopedia standards.

## Permissions

### Content Scanning
- Read all broadcasts
- Read all directives
- Read all tasks
- Read all roles
- Access all channels

### Validation
- Validate filename format
- Validate header completeness
- Validate footer presence
- Check actor ID references
- Check channel ID references
- Verify timestamps

### Reporting
- Generate audit reports
- Document violations
- Recommend fixes
- Track compliance metrics

## Assigned Actors

- **1000** - Kiro IDE
- **1001** - Windsurf IDE
- **1004** - Warp IDE

## Responsibilities

1. **Broadcast Auditing**
   - Scan all broadcast files
   - Validate filename format
   - Check header completeness
   - Verify footer presence
   - Report violations

2. **Registry Validation**
   - Check all actor ID references
   - Check all channel ID references
   - Verify IDs exist in registry
   - Report invalid references

3. **Metadata Compliance**
   - Validate FLIP headers
   - Validate FLIP footers
   - Check timestamp format
   - Verify delegation chains

4. **Reporting**
   - Generate JSON audit reports
   - Create markdown summaries
   - Track compliance over time
   - Recommend normalization

## Constraints

- Read-only access (cannot modify content)
- Must report all violations
- Must not make assumptions
- Must validate against registry

## Success Criteria

- All content scanned
- All violations documented
- All reports generated
- All recommendations provided

## Escalation

Auditors report to System Administrators. Critical violations must be escalated immediately.

<!-- FLIP_FOOTER_BEGIN
{
  "references": [
    "lupo-scripts/audit_channel_broadcasts.ps1",
    "BROADCAST_AUDIT_REPORT_4.0.45.json"
  ],
  "implements": "content_audit_authority_model",
  "depends_on": "registry_lock",
  "role_category": "governance",
  "version": "4.0.45"
}
FLIP_FOOTER_END -->
