# Bulk Header Update to 4.1.6

## What This Does

Updates all files with old WOLFIE header versions to 4.1.6 and adds required fields.

## Required Updates

1. **Version:** `file.last_modified_system_version: 4.1.6`
2. **Header Atoms:** Must include:
   - `GLOBAL_CURRENT_LUPOPEDIA_VERSION`
   - `GLOBAL_CURRENT_AUTHORS`
3. **Dialog Block:** Must use `mood_RGB:` (not `mood:`)
4. **Simplify Wheeler Mode:** Remove quantum physics complexity, keep it simple

## Files to Update

Found 112 doctrine files with old versions. Update them systematically:

1. Update version number
2. Fix `mood:` → `mood_RGB:`
3. Ensure header_atoms are present
4. Simplify Wheeler Mode references

## Script

See `scripts/update_headers_to_4_1_6.php` for automated update script.

## Manual Updates Completed

- ✅ VERSION_DOCTRINE.md (4.0.35 → 4.1.6)
- ✅ AGENT_RUNTIME.md (4.0.14 → 4.1.6)
- ✅ PATCH_DISCIPLINE.md (4.0.14 → 4.1.6)
- ✅ METADATA_GOVERNANCE.md (4.0.14 → 4.1.6)
- ✅ AI_UNCERTAINTY_EXPRESSION_DOCTRINE.md (4.0.14 → 4.1.6, mood → mood_RGB)
- ✅ AI_INTEGRATION_SAFETY_DOCTRINE.md (4.0.14 → 4.1.6, mood → mood_RGB)
- ✅ DIALOG_FILE_ORDERING_DOCTRINE.md (4.0.15 → 4.1.6, mood → mood_RGB)

## Remaining Files

~105 doctrine files still need updates. Run the PHP script or continue manual updates.
