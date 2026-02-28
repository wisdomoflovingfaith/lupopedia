# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\channels\doctrine\DOCTRINE_VERSIONING.md"
  file_hash: "913afdbc724c46a9e94a88f202979c40dd92f6860585cf84805b0c87356942a2"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\channels\doctrine\DOCTRINE_VERSIONING.md"
  file_hash: "d63d6daad6b8b1fea813dd30bbf403bd3a07d2b00e18db66858c0c6ac4533b64"
  file_path_from_root: "docs\channels\doctrine\DOCTRINE_VERSIONING.md"
  file_hash: "21e993a973d84eaabce487ff1e41a1831dbe2c8601d1a23207d9cdcf31f9397d"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "LUPEDIA VERSIONING DOCTRINE"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "channels", "doctrine", "doctrine_versioningmd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# LUPEDIA VERSIONING DOCTRINE

## 🏛️ CANONICAL VERSION SOURCE

The canonical version of Lupopedia is stored in the root file `LUPEDIA_VERSION`.

All IDE agents, developers, and automated tools MUST read the version from this file.

No other file, comment, or metadata may define or imply the version.

## 📏 VERSION FORMAT

The version format is: `YYYY.MAJOR.MINOR.PATCH`

Example: `3.0.0`

- **YYYY**: Year of release
- **MAJOR**: Major feature release
- **MINOR**: Minor feature release  
- **PATCH**: Bug fixes and patches

## 🤖 IDE AGENT COMPLIANCE

IDE agents will obey this doctrine.
They treat root-level doctrine files as law.

### Required Behavior:
1. **Read version from `LUPEDIA_VERSION` file only**
2. **Never infer version from comments, code, or metadata**
3. **Never generate alternative version definitions**
4. **Use this version for all version-dependent operations**

## 🔄 VERSION-DEPENDENT OPERATIONS

### Schema Freeze
Schema freeze applies to all versions >= 4.1.0 (when release cycle begins).

This tells IDEs:
- Stop generating tables
- Stop modifying schema
- Stop inferring migrations

### Migration Contracts
All migrations must reference the canonical version:
```
Importing into Lupopedia version 3.0.0
```

### Installer Requirements
Installers must read:
```php
$version = LUPEDIA_VERSION;
```

## 🚫 PROHIBITED ALTERNATIVES

- ❌ Version in package.json
- ❌ Version in composer.json
- ❌ Version in PHP constants
- ❌ Version in database tables
- ❌ Version in environment variables
- ❌ Version in comments or docblocks

## ✅ MANDATORY IMPLEMENTATION

### PHP Bootstrap
```php
define('LUPEDIA_VERSION', trim(file_get_contents(__DIR__ . '/LUPEDIA_VERSION')));
```

### Agent Version Check
```python
with open('LUPEDIA_VERSION', 'r') as f:
    version = f.read().strip()
```

### Installer Integration
```bash
VERSION=$(cat LUPEDIA_VERSION)
echo "Installing Lupopedia version $VERSION"
```

---

**This doctrine is absolute. No exceptions.**