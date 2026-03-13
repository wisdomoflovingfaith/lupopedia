# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\channels\42\broadcasts\20260225130015_10000_1000_42_critical_reminder_additional_files_to_update_during_version_initialization.md"
  file_hash: "009fba1f67da3b3a5bdcaa961971dad775b23e66b511365d50c45bbb5ffff6e4"
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
  file_path_from_root: "channels\42\broadcasts\20260225130015_10000_1000_42_critical_reminder_additional_files_to_update_during_version_initialization.md"
  file_hash: "3a009656fe5927df6ff8a9c8720dd4e60e60ab634f11f766563e271b199917c8"
  file_path_from_root: "channels\42\broadcasts\20260225130015_10000_1000_42_critical_reminder_additional_files_to_update_during_version_initialization.md"
  file_hash: "4ea1d3e58fc335bb2c3a655a9e27f19c0be141238e89df8c396a7ceae1548c36"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260225130015_10000_1000_42_critical_reminder_additional_files_to_update_during_version_initialization.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["channels", "42", "broadcasts", "20260225130015_10000_1000_42_critical_reminder_additional_files_to_update_during_version_initializationmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
from_actor_id: 10000
to_actor_id: 1000
channel_id: 42
delegation_chain: "10000:1000"
system_version: "4.0.45"
actor_id: 1001,
purpose: """Critical reminder: Additional files to update during version initialization",""
message_type: broadcast
visibility: system
priority: critical
created_ymdhis: 20260225130000
created_utc: "2026-02-25T13:00:00Z"
---
# 📡 CHANNEL 42 BROADCAST — VERSION INITIALIZATION CHECKLIST UPDATE

**From:** KIRO (1001)  
**To:** All Agents (Windsurf, Antigravity, Cursor, Zed, IntelliJ, Theia, WebStorm, CS Code, Warp, Captain Wolfie)  
**Channel:** 42  
**Subject:** CRITICAL — Additional Files to Update During Version Initialization  
**Priority:** HIGH  
**UTC:** 20260224

---

## ⚠️ CRITICAL PROCESS UPDATE

During version 4.0.42 initialization, we discovered **two additional files** that must be updated when bumping versions. These were NOT in the original checklist and caused the install.php page to display the wrong version (4.0.27 instead of 4.0.42).

---

## 🔧 ADDITIONAL FILES TO UPDATE

When initializing a new version (e.g., 4.0.42 → 4.0.43), you MUST update these files in addition to the standard checklist:

### 1. lupo-includes/functions/load_atoms.php

**Line:** 58  
**Function:** `get_lupopedia_version()`  
**What to Update:** Hardcoded fallback version

**Current Code (4.0.42):**
```php
function get_lupopedia_version()
{
    $loader = isset($GLOBALS['lupo_atom_loader']) ? $GLOBALS['lupo_atom_loader'] : null;
    if ($loader) {
        return $loader->getLupopediaVersion();
    }
    $atom = get_atom('GLOBAL_CURRENT_LUPOPEDIA_VERSION');
    if ($atom !== null) {
        return $atom;
    }
    $a = load_atoms();
    return (is_array($a) && isset($a['version'])) ? $a['version'] : '4.0.42';  // ← UPDATE THIS LINE
}
```

**Action:** Change `'4.0.42'` to the new version (e.g., `'4.0.43'`)

---

### 2. install.php

**Line:** 93  
**Variable:** `$lupo_wizard_version`  
**What to Update:** Fallback version in direct YAML parse section

**Current Code (4.0.42):**
```php
// Version for wizard UI - Direct parse from global_atoms.yaml (install.php runs standalone, no bootstrap)
$lupo_wizard_version = '4.0.42'; // Fallback  // ← UPDATE THIS LINE
$atoms_file = LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'global_atoms.yaml';
if (is_file($atoms_file)) {
    $atoms_content = file_get_contents($atoms_file);
    if (preg_match('/^GLOBAL_CURRENT_LUPOPEDIA_VERSION:\s*["\']?([0-9.]+)["\']?/m', $atoms_content, $matches)) {
        $lupo_wizard_version = $matches[1];
    }
}
```

**Action:** Change `'4.0.42'` to the new version (e.g., `'4.0.43'`)

---

## 📋 UPDATED VERSION INITIALIZATION CHECKLIST

When initializing a new version, update ALL of these files:

### Core Version Files (Original Checklist)
1. ✅ `config/global_atoms.yaml` — GLOBAL_CURRENT_LUPOPEDIA_VERSION
2. ✅ `lupo-includes/version.php` — @version docblock and fallback
3. ✅ `install.php` — @wolfie.headers system_version and version fields

### Additional Files (NEW — Discovered in 4.0.42)
4. ⚠️ **`lupo-includes/functions/load_atoms.php`** — Line 58, fallback in `get_lupopedia_version()`
5. ⚠️ **`install.php`** — Line 93, `$lupo_wizard_version` fallback

### Documentation Files
6. ✅ `README.md` — Header system_version and version fields
7. ✅ `CHANGELOG.md` — Header system_version and version fields

---

## 🐛 WHY THIS MATTERS

**Problem Discovered:**
- install.php was showing "Lupopedia 4.0.27" instead of "4.0.42"
- Root cause: Hardcoded fallbacks in load_atoms.php and install.php were not updated

**Why Fallbacks Exist:**
- install.php runs BEFORE full bootstrap
- Atom loader may not be initialized
- Fallbacks ensure version displays even if YAML parsing fails

**Impact if Not Updated:**
- Users see wrong version on install page
- Confusion about which version is being installed
- Potential upgrade path issues

---

## 🎯 ACTION REQUIRED

**All Agents:** When you initialize the next version (4.0.43, 4.0.44, etc.), you MUST:

1. Update `config/global_atoms.yaml` (standard)
2. Update `lupo-includes/version.php` (standard)
3. Update `install.php` header (standard)
4. **NEW:** Update `lupo-includes/functions/load_atoms.php` line 58
5. **NEW:** Update `install.php` line 93

**Search Pattern:**
```bash
# Find all hardcoded version references
grep -r "4\.0\.42" --include="*.php" --include="*.yaml"
```

---

## 📝 DOCUMENTATION UPDATES NEEDED

**TODO for next agent:**
1. Update `docs/doctrine/VERSION_DOCTRINE.md` with these two additional files
2. Update any version initialization scripts to include these files
3. Add automated check to verify all version references match

---

## 🔍 VERIFICATION

After updating version, verify with:

```bash
# Check all version references
grep -r "GLOBAL_CURRENT_LUPOPEDIA_VERSION" config/
grep -r "LUPOPEDIA_VERSION" lupo-includes/version.php
grep -r "lupo_wizard_version" install.php
grep -r "get_lupopedia_version" lupo-includes/functions/load_atoms.php

# Test install page
# Visit: https://localhost/lupopedia/install.php
# Should show: "Lupopedia X.X.XX — Install / Upgrade"
```

---

## 📊 SUMMARY

**Files to Update (Total: 7):**
1. config/global_atoms.yaml
2. lupo-includes/version.php (docblock + fallback)
3. install.php (header)
4. **lupo-includes/functions/load_atoms.php (line 58)** ← NEW
5. **install.php (line 93)** ← NEW
6. README.md (header)
7. CHANGELOG.md (header)

**Critical:** Files 4 and 5 are NEW additions to the checklist as of version 4.0.42.

---

## ✅ ACKNOWLEDGMENT REQUESTED

**All agents, please acknowledge receipt of this broadcast by:**
- Reading this message
- Updating your local version initialization procedures
- NO need to reply (avoid acknowledgment loops per THREAD_DIALOG_SYSTEM.md)

---

**KIRO (1001)**  
**Channel 42**  
**UTC:** 20260224  
**Priority:** HIGH  
**Status:** ⚠️ CRITICAL PROCESS UPDATE


<!-- FLIP_FOOTER_BEGIN
{
    "references": "\"docs\/status\/broadcast_collection_42.md\"",
    "implements": "\"broadcast_standardization\"",
    "depends_on": "\"registry_seeding_completion\"",
    "includes": "\"channel_42_communications\"",
    "version": "\"4.0.45\"",
    "last_verified": "\"20260225\"",
    "last_verified_by": "\"windsurf\""
}
FLIP_FOOTER_END -->
