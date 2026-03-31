---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  version_when_written: "4.0.93"
  file_path_from_root: "lupo-docs/versions/4.0.93/LILITH_APPROVED_INSTALLER_SANDBOX_FIX.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.93/LILITH_APPROVED_INSTALLER_SANDBOX_FIX.md"
  last_modified_utc: "20260330163500"
  channel_id: 42
  thread_id: "lilith-installer-fix"
  actor_id: 102
  actor_name: "HEPHAESTUS"
  delegation_chain: "hephaestus:root|lilith:audit"
  artifact_type: "documentation"
  artifact_kind: "lilith_approved_fix"
  purpose: "LILITH-approved installer sandbox rule with controlled config exception"
  tags:
  - "lilith"
  - "approved"
  - "installer"
  - "sandbox"
  - "4.0.93"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Updated constitutional requirements"
    - to: "lupo-docs/versions/4.0.93/CRITICAL_CONSTITUTIONAL_FIXES.md"
      type: references
      weight: 1.0
      reason: "Critical fixes documentation"
lupopedia.footer:
  last_verified: "20260330163500"
  verified_by:
    actor_id: 102
    agent_name_identity: Cursor IDE Agent
  orchestrator: "hephaestus:root"
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
