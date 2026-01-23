# LUPEDIA VERSIONING DOCTRINE

## 🏛️ CANONICAL VERSION SOURCE

The canonical version of Lupopedia is stored in the root file `LUPEDIA_VERSION`.

All IDE agents, developers, and automated tools MUST read the version from this file.

No other file, comment, or metadata may define or imply the version.

## 📏 VERSION FORMAT

The version format is: `YYYY.MAJOR.MINOR.PATCH`

Example: `2026.1.0.9`

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
Schema freeze applies to all versions >= `2026.1.0.0`.

This tells IDEs:
- Stop generating tables
- Stop modifying schema
- Stop inferring migrations

### Migration Contracts
All migrations must reference the canonical version:
```
Importing into Lupopedia version 2026.1.0.9
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
