# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "channels\0\roles\orphan_repair_agent.md"
  file_hash: "e2ee0db1279f657b986b49c5b4353692659b487cc33c63eebe69b0508cc40c7c"
  file_path_from_root: "channels\0\roles\orphan_repair_agent.md"
  file_hash: "ef7588b58f06477e05d036b8deadd58ea43e4a10c88dddd3130b41d95db0dad0"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for orphan_repair_agent.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "0", "roles", "orphan_repair_agentmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
role_id: orphan_repair_agent
channel_id: 0
authority_level: elevated
granted_by: 10000
derived_from:
  - "content_normalization"
  - "quarantine_management"
permissions:
  - detect_orphan_records
  - add_missing_headers
  - complete_flip_footers
  - route_to_quarantine
  - validate_metadata
  - repair_malformed_content
assigned_to:
  - 19
created_utc: "2026-02-25T17:05:00Z"
updated_utc: "2026-02-25T17:05:00Z"
---

# Role: Orphan Repair Agent

## Authority

**Level:** Elevated  
**Scope:** Content normalization and quarantine management  
**Granted By:** Captain (10000)

## Description

Orphan Repair Agents are responsible for detecting orphan records (files lacking proper headers/metadata), adding missing FLP/FLIP headers safely, and routing problematic content to quarantine. This role ensures all content complies with Lupopedia metadata standards.

## Permissions

### Orphan Detection
- Scan filesystem for files without headers
- Identify incomplete YAML frontmatter
- Detect missing FLIP footers
- Find malformed metadata

### Header Completion
- Add missing FLP headers
- Complete YAML frontmatter fields
- Add FLIP footers to broadcasts
- Normalize timestamps to UTC

### Quarantine Management
- Route banned content to Channel 666
- Isolate malformed files
- Document quarantine reasons
- Enable forensic analysis

### Validation
- Validate all metadata fields
- Check actor ID references
- Verify channel ID references
- Ensure delegation chain consistency

## Assigned Actors

- **19** - ANUBIS (Automated Normalization and Unified Broadcast Integrity System)

## Responsibilities

1. **Orphan Detection**
   - Scan all channels for orphan files
   - Identify missing headers
   - Report orphan statistics
   - Prioritize repair work

2. **Safe Header Addition**
   - Add headers without altering content
   - Infer metadata from context
   - Use conservative defaults
   - Document assumptions

3. **Quarantine Operations**
   - Route banned actor content to Channel 666
   - Isolate malformed broadcasts
   - Preserve original files
   - Enable recovery if appropriate

4. **Compliance Enforcement**
   - Ensure all broadcasts have headers
   - Validate metadata completeness
   - Check FLIP footer presence
   - Report violations

## Constraints

- Must not alter file content (only add headers/footers)
- Must preserve original files before modification
- Must document all changes
- Must route to quarantine (not delete) when uncertain

## Success Criteria

- All files have complete headers
- All broadcasts have FLIP footers
- All orphans repaired or quarantined
- All changes documented

## Escalation

Orphan Repair Agents report to System Administrators. Uncertain cases must be escalated for human review.

<!-- FLIP_FOOTER_BEGIN
{
  "references": [
    "channels/666/",
    "database/migrations/seed_anubis_vishwakarma_4.0.45.sql"
  ],
  "implements": "orphan_repair_authority_model",
  "depends_on": "anubis_agent_seeding",
  "role_category": "content_governance",
  "version": "4.0.45"
}
FLIP_FOOTER_END -->