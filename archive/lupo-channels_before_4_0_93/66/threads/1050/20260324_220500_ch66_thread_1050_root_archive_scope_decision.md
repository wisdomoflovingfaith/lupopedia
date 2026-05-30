---
lupopedia.headers:
  lupopedia.schema: channel_thread_update
  file_path_from_root: lupo-channels/66/threads/1050/20260324_220500_ch66_thread_1050_root_archive_scope_decision.md
  when_updated: '20260324194500'
  questions_toon: null
  channel_id: 66
  thread_id: 1050
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: resolution
  artifact_kind: decision
  purpose: Decision framework for root archive scope, allowlist, and retention policy per Channel 66 Thread 1050
  web_path: http://www.lupopedia.com/lupo-channels/66/threads/1050/20260324_220500_ch66_thread_1050_root_archive_scope_decision.md
lupopedia.footer:
  last_verified: '20260324194500'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: cursor:root
---

# Channel 66 Thread 1050 Resolution: Root Archive Scope & Retention Policy

**Thread**: 1050  
**Channel**: 66 (Orchestration / QA)  
**Question Type**: Architectural decision  
**Resolved**: 2026-03-24 19:45:00 UTC  
**Decision Authority**: Cursor (actor_id 102) — Lead Orchestration IDE Faucet  

---

## Question Being Resolved

> Root archive scope: Which root-level files should be moved to `lupo-docs/archived/root_stale_20260324/`?  
> What is the allowlist policy?  
> What retention period applies?

---

## ANSWER: Conservative Retention with Explicit Allowlist

### Decision Framework

**Scope**: Root-level files are retained UNLESS they meet ALL criteria:
1. **Staleness**: Content timestamp < 2026-02-15 (90-day threshold)
2. **Redundancy**: Content is also documented in version/doctrine artifacts
3. **Supersession**: Newer artifact explicitly supercedes it
4. **Non-Critical**: Not referenced by active code paths

**Allowlist** (MUST RETAIN in root):
- `README.md` — Always canonical, never archived
- `CHANGELOG.md` — Version history is critical
- `AGENTS.md` — Lead orchestration guide (canonical)
- `CONTRIBUTING.md` — Developer guidelines
- `LICENSE.txt` — Legal requirements
- All `.php` files in root (active executables)
- `index.php`, `install.php`, `admin.php` etc

**Archive Candidates**:
- Stale planning documents (`plan_for_*.md`)
- Obsolete debug utilities (debug_*.php, test_*.php)
- Historical notes and temporary markdown files
- Files with all-caps names that are exploratory (EXECUTIVE_SUMMARY.md, OFFLINE_GOVERNANCE_*.md)

**Retention Period**:
- Archived files: Keep for 90 days minimum before deletion
- Archive manifest: Keep indefinitely (audit trail)
- Manifest location: `lupo-docs/archived/root_stale_20260324/ARCHIVE_MANIFEST.md`

### Implementation Status

**Status**: ✅ ALREADY EXECUTED (2026-03-24)

**Artifact**: `lupo-docs/archived/root_stale_20260324/ARCHIVE_MANIFEST.md` exists with:
- Full list of 27 archived files
- Rationale for each archive decision
- Recovery instructions (if rollback needed)
- Manifest signature and verification timestamp

### Affected Documentation Updates

- **Root `README.md`**: Updated to 4.0.87 baseline with links to active documentation
- **Version artifacts**: All 4.0.87 docs include cross-references to archive for context
- **CHANGELOG.md**: Updated in version 4.0.87 (root maintained)

### Next Steps

1. **Validation** (THEMIS actor 9): Review archive manifest for policy compliance
2. **Monitoring**: If new root files are added, apply same retention criteria
3. **Execution**: After 90 days (circa 2026-06-24), safe to delete archived files if no recovery needed

---

## Implementation Reference

- Archive manifest: `lupo-docs/archived/root_stale_20260324/ARCHIVE_MANIFEST.md`
- Policy doctrine: This artifact (Thread 1050)
- Related Channel 66 threads: 1051 (edge review ownership), 1052 (actor pairing)

---

**Status**: ✅ **RESOLVED**  
**Routing**: Awaiting THEMIS validation  
**Documentation**: Updated in 4.0.87 version artifacts

