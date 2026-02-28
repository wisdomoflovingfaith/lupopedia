# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\42\broadcasts\20260224_version_initialization_checklist_update.md"
  file_hash: "19ded6861a7cd3ea135ff2b0db50e4940f82dc6548ddc3f60fa65f37db545dba"
  file_path_from_root: "docs\channels\42\broadcasts\20260224_version_initialization_checklist_update.md"
  file_hash: "06bb80c895c1ba8fc2abf75cf544eba4e0e594c6430bf83520149cc58590295e"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for 20260224_version_initialization_checklist_update.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "42", "broadcasts", "20260224_version_initialization_checklist_updatemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
wolfie.headers: {
  file_path_from_root: "docs/channels/42/broadcasts/20260224_version_initialization_checklist_update.md",
  system_version: "4.0.42",
  channel_id: 42,
  mood_rgb: "FF6347",
  purpose: "Critical reminder: Additional files to update during version initialization",
  last_modified_utc: "20260224",
  delegation_chain: "1001:10000",
  actor_id: 1001,
  lupo_agent: "kiro",
  artifact_type: "broadcast",
  artifact_kind: "process_update",
  traits: ["critical", "process", "version_init", "checklist"],
  hashtags: ["#channel42", "#version_init", "#process", "#critical"],
  engagement: {
    likes: 0,
    shares: 0,
    views: 0,
    last_interaction_utc: "20260224"
  },
  graph_stats: {
    inbound_count: 2,
    outbound_count: 4,
    centrality_score: 0.85
  }
}

flip.footer: {
  inbound_edges: [
    { from: "channels/42/broadcasts/20260224_version_4_0_42_initialized.md", type: "updates", weight: 0.9, hashtag: "#initialization" }
  ],
  outbound_edges: [
    { to: "install.php", type: "documents", weight: 1.0, hashtag: "#installer" },
    { to: "lupo-includes/functions/load_atoms.php", type: "documents", weight: 1.0, hashtag: "#atoms" },
    { to: "config/global_atoms.yaml", type: "references", weight: 0.9, hashtag: "#config" },
    { to: "docs/doctrine/VERSION_DOCTRINE.md", type: "references", weight: 0.8, hashtag: "#doctrine" }
  ],
  referenced_by_actors: [1001, 1002, 1003, 1004, 1005, 1006, 1007, 1008, 1009, 1010, 10000],
  references: {
    by_files: ["channels/42/broadcasts/20260224_version_4_0_42_initialized.md"],
    by_actors: [1001, 10000]
  },
  semantic_tags: ["version_initialization", "process_improvement", "checklist_update", "critical_reminder"],
  enrichment: {
    llm_inferred_edges: [],
    federated_metrics: {}
  },
  version: "4.0.42",
  last_verified_utc: "20260224",
  last_verified_by: "kiro"
}
---

# 📡 CHANNEL 42 BROADCAST — VERSION INITIALIZATION CHECKLIST UPDATE
## Critical Reminder: Additional Files to Update During Version Initialization

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

**Action:** Change `'4.0.42'` to new version (e.g., `'4.0.43'`)

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

**Action:** Change `'4.0.42'` to new version (e.g., `'4.0.43'`)

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
- install.php was showing "Lupopedia 4.0.27" instead of "Lupopedia 4.0.42"
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
6. Update `README.md` header (standard)
7. Update `CHANGELOG.md` header (standard)

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

## 📊 SUMMARY

**Files to Update (Total: 7):**
1. config/global_atoms.yaml
2. lupo-includes/version.php
3. install.php
4. **NEW:** lupo-includes/functions/load_atoms.php (line 58)
5. **NEW:** install.php (line 93)
6. README.md
7. CHANGELOG.md

**Critical:** Files 4 and 5 are NEW additions to checklist as of version 4.0.42.

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
**Status:** ⚠️ **CRITICAL PROCESS UPDATE**
 which is channels/42/broadcasts/20260224_version_initialization_checklist_update.md

---

## 📋 VERIFICATION

After updating version, verify with:

```bash
# Check all version references
grep -r "GLOBAL_CURRENT_LUPOPEDIA_VERSION" config/
grep -r "LUPOPEDIA_VERSION" lupo-includes/version.php
grep -r "lupo_wizard_version" install.php
grep -r "get_lupopedia_version" lupo-includes/functions/load_atoms.php

# Test install page
# Visit: https://localhost/lupopedia/install.php
# Should show: "Lupopedia X.XX — Install / Upgrade"
```

---

**END OF BROADCAST**