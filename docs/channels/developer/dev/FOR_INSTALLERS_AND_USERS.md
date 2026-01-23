---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.14
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Added WOLFIE Header v4.0.0 for documentation consistency."
tags:
  categories: ["documentation", "installation", "user-guide"]
  collections: ["core-docs"]
  channels: ["public"]
file:
  title: "For Auto-Installers and Users"
  description: "Simple, friendly explanation for auto-installers, hosting providers, and Crafty Syntax users"
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: "Captain Wolfie"
---

# ðŸ“¦ For Auto-Installers, Hosting Providers, and Crafty Syntax Users

## Simple Explanation

**Lupopedia 4.0.0 includes Crafty Syntax 4.0.0 â€” they are version-locked and released together.**

**Crafty Syntax 4.0.0 runs on the Lupopedia 4.0.0 platform, the same way an app runs on Windows.**

That's it.  
No deep architecture talk.  
No semantic graphs.  
No AI.  
No federation.  
No overwhelming technical details.

Just: **Crafty Syntax still works the same â€” it just runs on a modern foundation now.**

---

## The Safe, Friendly Upgrade Explanation

Here's the exact wording you can use for auto-installers, hosting providers, support teams, and users:

---

### **Lupopedia 4.0.0 + Crafty Syntax 4.0.0 Upgrade Notice**

**Lupopedia 4.0.0** includes **Crafty Syntax 4.0.0** as an integrated firstâ€‘party module. Both systems share the same version number (4.0.0) and are always released together.

Crafty Syntax Live Help continues to be fully supported and is now built on top of a modern platform called **Lupopedia**.

### **Content-Level Chat Configuration**

Chat functionality is **optional at the content level**. Each content item can enable or disable chat:

- **Enabled:** Content receives a default actor and can participate in dialogs, channels, and multiâ€‘agent communication
- **Disabled:** Content functions as a normal semantic object without chat capabilities

This generalizes the original Crafty Syntax design (chat icons on selected pages) to a systemâ€‘wide capability activated only where needed.

**Think of it like this:**
- **Lupopedia 4.0.0** is the operating system
- **Crafty Syntax 4.0.0** is the application running inside it

This gives Crafty Syntax:
- âœ… Better performance
- âœ… Better security
- âœ… A modern codebase
- âœ… Long-term maintainability
- âœ… Optional new features (only if you want them)

**Nothing changes about how Crafty Syntax works.**  
You still get the same Live Help system â€” just running on a stronger foundation.

---

## For Auto-Installers & Hosting Providers

### What You Need to Know

**Lupopedia 4.0.0 is the platform foundation.**  
**Crafty Syntax 4.0.0 is the application that runs on it.**

Both systems are version-locked at 4.0.0 and released together as a unified package.

### Installation Requirements

- **PHP 7.4+** (standard requirement)
- **MySQL 5.7+ / MariaDB 10.2+** (standard database)
- **Apache/Nginx** (standard web server)
- **Standard hosting environment** (shared hosting, VPS, or dedicated)

**Nothing special required.**  
If your hosting supports PHP and MySQL, it works.

### Installation Process

**Fresh Installation:**
1. Upload files to web directory
2. Import database SQL file
3. Configure database connection
4. Complete setup wizard

**Upgrade from Crafty Syntax 3.7.5:**
1. Upload Lupopedia 4.0.3 files to existing Crafty Syntax directory
2. Setup wizard automatically detects `config.php` (only exists in Crafty Syntax 3.7.5)
3. Setup enters upgrade mode and runs migration automatically
4. Migration SQL (`database/migrations/craftysyntax_to_lupopedia_mysql.sql`) executes
5. All 34 legacy tables are migrated to Lupopedia 4.0.3 schema
6. Legacy tables are dropped after successful migration
7. Upgrade complete - system is now Lupopedia 4.0.3

**Upgrade from Crafty Syntax 3.7.5:**
1. Upload all Lupopedia files to webroot (overlay on existing installation or new directory)
2. Access Lupopedia via browser
3. Setup automatically detects old `config.php` file
4. Setup migrates configuration automatically
5. Run database migration SQL (via phpMyAdmin or setup wizard)
6. Complete!

**No special plugins, no special configurations, no special requirements.**

### Database

- Single database installation
- Standard MySQL/MariaDB tables
- No special database features required
- Works with standard database management tools (phpMyAdmin, etc.)

### Support

Crafty Syntax functionality remains the same. All existing features work identically. Support documentation and procedures remain unchanged.

---

## For Crafty Syntax Users

### What This Means for You

**Nothing changes about how you use Crafty Syntax.**

- âœ… Same admin interface
- âœ… Same features
- âœ… Same functionality
- âœ… Same user experience
- âœ… Same support

### The Only Difference

Crafty Syntax now runs on a modern, updated foundation (Lupopedia) instead of the older platform. This is **completely transparent** to you as a user.

**Think of it like:**  
Your favorite app got updated to run on a newer version of Windows. The app still works the same, but now it's more stable, more secure, and will continue to receive updates for years to come.

### Optional New Features

The Lupopedia platform enables some optional new features that may be available in future Crafty Syntax updates. **These are completely optional** â€” you can continue using Crafty Syntax exactly as you always have, or explore new features when you're ready.

**You're in control.**  
Nothing is forced on you.

---

## Common Questions

### Q: Is this a new product?
**A:** No. This is the same Crafty Syntax you know, running on an updated platform foundation.

### Q: Do I need to learn anything new?
**A:** No. Crafty Syntax works exactly the same way.

### Q: Will my existing setup break?
**A:** No. Existing installations can be upgraded, and all functionality is preserved.

### Q: What is Lupopedia exactly?
**A:** Lupopedia is the platform foundation that Crafty Syntax runs on â€” like Windows is the operating system that applications run on. You don't need to interact with Lupopedia directly; it's just the foundation.

### Q: Do I need to use new Lupopedia features?
**A:** No. All new features are optional. You can use Crafty Syntax exactly as you always have.

### Q: Is this more complex?
**A:** No. From a user perspective, nothing changes. From a technical perspective, the foundation is actually simpler and more maintainable.

---

## Key Messages Summary

**For Everyone:**
- Crafty Syntax continues to work the same way
- Lupopedia is just the platform foundation (like an OS)
- No learning curve required
- All existing features preserved
- Optional new features available
- Better performance and security

**For Technical People:**
- Standard PHP/MySQL application
- No special requirements
- Familiar installation process
- Standard database structure
- Cleaner, more maintainable codebase

**Bottom Line:**
**Crafty Syntax = Same product, stronger foundation.**

---

*This document is designed to prevent confusion and overwhelm. Keep it simple, keep it friendly, and focus on continuity rather than change.*

---

## Related Documentation

- **[Configuration Doctrine](../../doctrine/CONFIGURATION_DOCTRINE.md)** - WordPress-style configuration model that makes installation familiar
- **[Database Philosophy](../../architecture/DATABASE_PHILOSOPHY.md)** - Why Lupopedia uses application-first validation for easier installation
- **[Upgrade Plan 3.7.5 to 4.0.0](../modules/UPGRADE_PLAN_3.7.5_TO_4.0.0.md)** - Technical upgrade documentation for existing Crafty Syntax users
- **[Architecture](../../architecture/ARCHITECTURE.md)** - Complete system architecture for administrators who want technical details

---

