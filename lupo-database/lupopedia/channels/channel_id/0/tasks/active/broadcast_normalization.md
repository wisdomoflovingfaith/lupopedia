# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\channels\0\tasks\active\broadcast_normalization.md"
  file_hash: "f49fcfc0f832e9a5de98ed39e58ade49e12b8ea3ab3d962a42d2d80c85892066"
  last_updated_utc: "20260228155738"
  system_version: "4.0.73"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.73"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

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
  file_path_from_root: "channels\0\tasks\active\broadcast_normalization.md"
  file_hash: "d295ef1ecc1905d8b141967edd9abb7bdaa4f6d28c6ed2d00e746b871746175b"
  file_path_from_root: "channels\0\tasks\active\broadcast_normalization.md"
  file_hash: "2e88aa239490c7d8217e3905eef443aa87ca0253b3e01dd6ee8abd7ae54ed34e"
  last_updated_utc: "20260228"
  system_version: "4.0.73"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for broadcast_normalization.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.73"]
  tags: ["channels", "0", "tasks", "active", "broadcast_normalizationmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.73"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
task_id: CH0-20260225-002
channel_id: 0
owner_actor_id: 10000
assigned_to:
  - 1000
  - 1001
status: active
priority: high
created_utc: "2026-02-25T08:35:00Z"
depends_on:
  - CH0-20260225-001
blocks: []
task_type: content_normalization
estimated_duration: "2 hours"
---

# Task: Broadcast Normalization (58 Files)

## Objective

Normalize all broadcast files in Channel 0 and Channel 42 to comply with the canonical filename and metadata standards.

## Context

Audit completed on 2026-02-25 revealed 58 violations across both channels:
- **Channel 0:** 39 files (25 filename issues, 13 incomplete headers)
- **Channel 42:** 19 files (19 filename issues)

No files are currently compliant with the full standard.

## Standards

### Filename Pattern
```
YYYYMMDDHHMMSS_FROM_TO_CHANNEL_TITLE.md
```

### Required Header Fields
```yaml
from_actor_id: <actor_id>
to_actor_id: <actor_id>
channel_id: <channel_id>
delegation_chain: "<from>:<to>"
created_utc: "YYYY-MM-DDTHH:MM:SSZ"
system_version: "4.0.73"
artifact_type: "broadcast"
```

### Required Footer
```html
<!-- FLIP_FOOTER_BEGIN
{
  "references": [],
  "implements": "",
  "depends_on": "",
  "includes": "",
  "version": "4.0.45"
}
FLIP_FOOTER_END -->
```

## Steps

1. **Read audit report**
   - File: `BROADCAST_AUDIT_REPORT_4.0.45.json`
   - Identify all violations

2. **Normalize Channel 0 broadcasts** (39 files)
   - Fix filename format
   - Add missing header fields
   - Add FLIP footer
   - Validate actor IDs against registry

3. **Normalize Channel 42 broadcasts** (19 files)
   - Fix filename format
   - Add missing header fields
   - Add FLIP footer
   - Validate actor IDs against registry

4. **Archive duplicates** (if any)
   - Move to `channels/{id}/broadcasts/archive/`
   - Document in archive log

5. **Verify normalization**
   - Re-run: `scripts/audit_channel_broadcasts.ps1`
   - Target: 0 violations

## Success Criteria

- ✅ All 58 files renamed to standard format
- ✅ All headers complete with required fields
- ✅ All footers present with valid edges
- ✅ All actor IDs validated against registry
- ✅ All timestamps normalized to UTC
- ✅ Audit report shows 0 violations

## Risks

- **Breaking references:** Other files may reference old filenames
- **Metadata loss:** Incorrect normalization may lose information
- **Duplicate detection:** May reveal previously hidden duplicates

## Notes

This task is blocked by database installation (need to verify actor IDs against seeded registry).

<!-- FLIP_FOOTER_BEGIN
{
  "references": [
    "BROADCAST_AUDIT_REPORT_4.0.45.json",
    "DUAL_CHANNEL_BROADCAST_AUDIT_REPORT_4.0.45.md",
    "scripts/audit_channel_broadcasts.ps1"
  ],
  "implements": "broadcast_normalization_standard",
  "depends_on": "CH0-20260225-001",
  "blocks": [],
  "task_category": "content_governance",
  "version": "4.0.45"
}
FLIP_FOOTER_END -->
