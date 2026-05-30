---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: null
  file_path_from_root: "docs/versions/4.0.93/LILITH_APPROVED_INSTALLER_SANDBOX_FIX.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/versions/4.0.93/LILITH_APPROVED_INSTALLER_SANDBOX_FIX.md"
  questions_toon: null
  federation_node_id: null
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: documentation
  artifact_kind: lilith_approved_fix
  thread_id: "lilith-installer-fix"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: ""
  summary: ""
  module: null
  dialog_transcript: null
---
# LILITH-Approved Installer Sandbox Fix - 4.0.93

Generated: 2026-03-30 16:35:00

## ⭐ **LILITH-APPROVED FIX**

### **Issue Identified**
The original RULE 93.INSTALLER_SANDBOX was too absolute:
```
"Installer may only write inside the Lupopedia directory."
```

This would break the installer because Lupopedia must support secure config placement outside the web root.

### **Solution Approved by LILITH**

Updated RULE 93.INSTALLER_SANDBOX with controlled exception for `lupopedia-config.php`:

#### **9.13.1 General Sandbox Restrictions**
- Installer may only write inside `/public_html/lupopedia/`
- EXCEPT for the secure configuration file

#### **9.13.2 Secure Configuration Exception (Allowed)**
- Installer may attempt to write `../lupopedia-config.php`
- **IF AND ONLY IF:**
  - Directory is writable
  - Hosting environment permits it
  - Safe write test performed first

#### **9.13.3 Fallback Behavior (Mandatory)**
If cannot write above web root:
- Write config inside Lupopedia directory
- Continue installation normally
- Warn user about public location

#### **9.13.4 No Other Exceptions**
- Config file is ONLY permitted exception
- Prevents drift, security issues, IDE misbehavior

## 🎯 **Alignment with Industry Standards**

This fix aligns Lupopedia with:
- ✅ **WordPress** (wp-config.php above web root)
- ✅ **phpBB** (config.php placement options)
- ✅ **MediaWiki** (LocalSettings.php placement)
- ✅ **Crafty Syntax** (config file handling)

## 📋 **Implementation Requirements**

1. **Installer must test write permissions** before attempting config placement
2. **Installer must provide clear feedback** about config location
3. **Installer must support both placement options** (secure + fallback)
4. **Installer must warn user** if config is in public directory

## ✅ **Constitutional Compliance**

- ✅ Maintains sandbox restrictions
- ✅ Provides controlled exception for security
- ✅ Ensures compatibility with shared hosting
- ✅ Prevents unrestricted filesystem access
- ✅ Aligns with Softaculous/Installatron requirements

## 🚨 **Security Considerations**

- Config file outside web root = **MORE SECURE**
- Config file inside web root = **LESS SECURE** (but functional)
- Installer must **always warn** about security implications
- User must be **informed** of config location choice

---

**STATUS**: ✅ LILITH-APPROVED AND IMPLEMENTED
**PRIORITY**: Critical - installer functionality
**IMPACT**: Enables secure config placement while maintaining sandbox
**APPLIES TO**: All 4.0.x releases
