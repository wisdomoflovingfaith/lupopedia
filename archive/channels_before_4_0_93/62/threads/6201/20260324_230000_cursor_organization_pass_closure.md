---
lupopedia.headers:
  lupopedia.schema: channel_closure
  file_path_from_root: channels/62/threads/6201/20260324_230000_cursor_organization_pass_closure.md
  when_updated: '20260324230000'
  questions_toon: null
  channel_id: 62
  thread_id: 6201
  actor_id: 102
  actor_name: cursor
  delegation_chain: cursor:root
  artifact_type: closure
  artifact_kind: organization_pass_manifest
  purpose: Closure artifact for channel 62 root/archive cleanup pass — manifest of moved and retained files
lupopedia.footer:
  last_verified: '20260324230000'
  last_verified_by: cursor
  last_verified_by_actor_id: 102
  orchestrator: wolfie:root
  next_action:
  - Post-4.0.87 sweep for remaining stale report files (tracked in backlog)
---
# file: channel 62 organization pass closure — delegation: cursor:root

# Channel 62 — Root/Archive Cleanup Pass Closure

**Channel**: 62  
**Thread**: 6201  
**Closure authority**: Cursor (actor_id 102)  
**UTC**: 2026-03-24 23:00:00  

---

## Scope

Channel 62 tracked the * folder organization pass and root-level cleanup for 4.0.87.

## Retention Policy (per Thread 1050 Decision)

Root-level files are retained unless they meet ALL four criteria simultaneously:
1. Content timestamp < 2026-02-15 (90-day staleness)
2. Content is also documented in version/doctrine artifacts (redundant)
3. A newer artifact explicitly supersedes it
4. Not referenced by active code paths

## Manifest: Retained Root Files (Allowlist)

The following root-level files are canonical and MUST remain at root:

| File | Reason |
|------|--------|
| `README.md` | Always canonical |
| `CHANGELOG.md` | Version history |
| `AGENTS.md` | Lead orchestration guide (canonical) |
| `CONTRIBUTING.md` | Developer guidelines |
| `license.txt` | Legal requirement |
| All `*.php` root files | Active executables |
| `lupopedia-config.php` | Runtime config |
| `ONBOARDING.md` | New contributor guide |

## Files Reviewed This Pass

Checked root-level `.md` report files (CAPTAINS_LOG, EXECUTIVE_SUMMARY, MINIMAL_SEED_*, etc.) against allowlist criteria. No files met all four archive criteria simultaneously — all retained.

## New Files Added This Session

| New File | Purpose |
|----------|---------|
| `includes/classes/EdgeQueryService.php` | Edge graph read-only query interface |
| `tests/unit/test_header_validators.py` | Tier 2/3 validator unit tests |
| `check_edge_state.php` | Dev diagnostic (temporary, can remove post-release) |
| `check_metadata_state.php` | Dev diagnostic (temporary, can remove post-release) |

## Status

✅ CLOSED — organization pass complete for 4.0.87 scope. Remaining stale report files deferred to post-4.0.87 cleanup backlog.
