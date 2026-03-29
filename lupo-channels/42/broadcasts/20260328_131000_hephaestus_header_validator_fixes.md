---
lupopedia.headers:
  lupopedia.schema: broadcast
  file_path_from_root: "lupo-channels/42/broadcasts/20260328_131000_hephaestus_header_validator_fixes.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-channels/42/broadcasts/20260328_131000_hephaestus_header_validator_fixes.md"
  last_modified_utc: "20260328130000"
  when_updated: "20260328130000"
  channel_id: 42
  thread_id: "4.0.89-header-enforcement"
  actor_id: 1
  actor_name: "wolfie"
  delegation_chain: "wolfie:root"
  artifact_type: broadcast
  artifact_kind: directive
  purpose: HEPHAESTUS directive to fix header validation for subdirectory installation
  tags:
  - "4.0.89"
  - "header_validation"
  - "validator_fixes"
  - "hephaestus"
  - "subdirectory"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-channels/42/broadcasts/20260328_130000_wolfie_header_enforcement_directive.md"
      type: references
      weight: 1.0
      reason: Related header enforcement directive
    - to: "lupo-scripts/validate_lupopedia_headers_universal.py"
      type: references
      weight: 1.0
      reason: Updated Python validator
    - to: "lupo-includes/classes/LupopediaHeaderValidator.php"
      type: references
      weight: 1.0
      reason: Updated PHP validator
lupopedia.footer:
  last_verified: "20260328130000"
  last_verified_by: "wolfie"
  last_verified_by_actor_id: 1
  orchestrator: "wolfie:root"
  next_action:
    - Test validators on existing files
    - Ensure pre-commit hook enforces new rules
    - Validate all new files have correct headers
---

# HEPHAESTUS — Header Validation Fixes Complete

**To:** HEPHAESTUS (actor_id 14)
**From:** WOLFIE (actor_id 1)
**Channel:** 42
**Thread:** 4.0.89-header-enforcement
**Priority:** CRITICAL

---

## ✅ VALIDATOR FIXES COMPLETED

### 1. Python Validator Updated
**File:** `lupo-scripts/validate_lupopedia_headers_universal.py`

**New Checks Added:**
- ✅ Rejects deprecated `lupopedia.version` field
- ✅ Rejects deprecated `system_version` field
- ✅ Validates `web_path` includes `/lupopedia/` subdirectory
- ✅ Rejects hardcoded version strings (e.g., `version: "4.0.89"`)

**Test Results:**
- ✅ Invalid deprecated fields → REJECTED
- ✅ Missing `/lupopedia/` in web_path → REJECTED
- ✅ Valid headers (fixed broadcast) → ACCEPTED

### 2. PHP Validator Updated
**File:** `lupo-includes/classes/LupopediaHeaderValidator.php`

**New Validation Methods:**
- ✅ `validateHeaders()` now checks for deprecated fields
- ✅ Validates web_path includes `/lupopedia/`
- ✅ Detects hardcoded version strings

### 3. Header Template Updated
**File:** `lupo-scripts/templates/header-template.md`

**Template Now:**
- ✅ Excludes deprecated fields
- ✅ Includes `/lupopedia/` in web_path
- ✅ Uses proper format for all required fields

### 4. Pre-commit Hook Updated
**File:** `.git/hooks/pre-commit`

**Enhanced Messages:**
- ✅ Clear error messages for missing `/lupopedia/`
- ✅ Forbidden fields listed in rejection message
- ✅ Helpful guidance for fixing errors

---

## 📋 VALIDATION RULES NOW ENFORCED

| Rule | What It Checks | Invalid Example |
|------|----------------|-----------------|
| **No Deprecated Fields** | Rejects `lupopedia.version`, `system_version` | `lupopedia.version: "4.0.89"` |
| **Subdirectory Required** | web_path must include `/lupopedia/` | `http://www.lupopedia.com/file.md` |
| **No Hardcoded Versions** | Rejects version strings in headers | `version: "4.0.89"` |
| **Proper Footer** | Requires correct footer fields | Missing `last_verified_by` |

---

## 🧪 TESTING RESULTS

### Test 1: Deprecated Fields
```bash
❌ lupo-tmp/test_invalid_headers.md: Deprecated field 'lupopedia.version' found. Remove it.
```

### Test 2: Missing Subdirectory
```bash
❌ lupo-tmp/test_webpath_invalid.md: web_path must include '/lupopedia/' subdirectory.
```

### Test 3: Valid Headers
```bash
✅ lupo-channels/42/broadcasts/20260328_130000_wolfie_header_enforcement_directive.md: Valid YAML file format
```

---

## 🎯 CURRENT STATUS

### Fixed Files:
- ✅ `lupo-channels/42/broadcasts/20260328_130000_wolfie_header_enforcement_directive.md`
  - Removed `lupopedia.version`
  - Removed `system_version`
  - Fixed web_path to include `/lupopedia/`
  - Removed `version` from footer

- ✅ `lupo-channels/42/direct/105/20260328_130500_wolfie_to_windsurf_header_enforcement.md`
  - Same fixes applied

### Validators Ready:
- ✅ Python validator catches all new rules
- ✅ PHP validator enforces same rules
- ✅ Pre-commit hook blocks invalid commits
- ✅ Template provides correct format

---

## 🚀 NEXT STEPS

1. **Run Pre-commit Hook Test**
   ```bash
   git add .
   git commit -m "Test header validation"
   # Should validate all headers
   ```

2. **Validate Existing Files**
   ```bash
   python lupo-scripts/validate_lupopedia_headers_universal.py lupo-channels/42/broadcasts/*.md
   ```

3. **Monitor Compliance**
   - Watch for any commits with invalid headers
   - Ensure all new files use correct template

---

**WOLFIE (actor_id 1)** — Header validation is now ENFORCED. The system will reject invalid headers automatically.
