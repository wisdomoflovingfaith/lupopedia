# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\INITIALIZATION_PROMPT_4_0_19.md"
  file_hash: "4f388dcadbf8603053b882023823fdda9c2c14d6a85acdfc46b0908f276af335"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for INITIALIZATION_PROMPT_4_0_19.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "initialization_prompt_4_0_19md"]
  lupo_agent: "windsurf"

flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

---
# FLIP Header
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/INITIALIZATION_PROMPT_4_0_19.md
file.last_modified_system_version: "4.0.19"
file.last_modified_utc: "20260219T000000Z"
# channel_id: 51 (Doctrine Council)
---

# Lupopedia 4.0.19 — Initialization Prompt
# Date: 2026-02-19

You are starting development on **Lupopedia version 4.0.19**.  
This is an initialization prompt only.  
Do NOT modify any files until explicitly instructed (e.g., "Do T1").

============================================================
1. 4.0.19 SCOPE (AUTHORITATIVE)
============================================================
4.0.19 focuses exclusively on:

A. **ADMIN WEB INTERFACE IMPLEMENTATION**
   - Implement, repair, and complete all admin web options.
   - Work inside:
       /lupopedia/admin.php
       /lupopedia/lupo-includes/themes/default/layouts/admin*
       /lupopedia/lupo-includes/classes/AdminUsersHandler.php
       (and any new lupo-includes/modules/admin/* or admin actions/views added)
   - Ensure all admin actions are functional, secure, and doctrine-aligned.
   - No new features beyond admin functionality.
   - No routing changes (routing stack is complete in 4.0.18).

B. **ADMIN TESTING EXPANSION**
   - Extend the T8 test suite to cover:
       - Admin CRUD operations
       - Permissions and roles
       - Actor-based restrictions
       - Content editing flows
       - Module loader interactions
       - Installer/admin interactions
   - Add regression tests for admin flows.

C. **ADMIN SECURITY HARDENING**
   - Validate actor_id, roles, permissions.
   - Enforce SESSION_DOCTRINE.md rules.
   - Ensure no admin action is reachable without proper authentication.

D. **LOCALHOST-ONLY**
   - All admin testing and development occurs at:
       http://localhost/lupopedia/admin.php

E. **NO 4.0.20+ PLANNING**
   - This thread is strictly for 4.0.19.

============================================================
2. DOCTRINE TO LOAD BEFORE ANY WORK
============================================================
Load and apply:

- docs/doctrine/VERSIONING_DOCTRINE.md
- docs/doctrine/SESSION_DOCTRINE.md
- SECURITY_DOCTRINE.md (if present; otherwise infer from admin code)
- ADMIN_DOCTRINE.md (if present; otherwise infer from admin code)
- docs/channels/doctrine/ROADMAP_4_0_19.md (create when instructed)
- CHANGELOG.md (4.0.18 is released)

Continue to obey:
- PHP 5.3 compatibility
- PDO only (PDO_DB wrapper)
- No DB-side logic (no FKs, triggers, DEFAULT CURRENT_TIMESTAMP)
- Reserved ID doctrine
- No changes to routing, resolver, caching, Smart 404, or Ban at Gate (4.0.18 stack)
- No Stoned Wolfie / Wheeler / Reverse-20 / quantum-state artifacts (see .cursor/rules)

============================================================
3. ADMIN FILE MAP (AUTHORITATIVE)
============================================================
You must load and understand the following admin-related files:

ROOT:
- admin.php

ADMIN HANDLERS & LAYOUTS:
- lupo-includes/classes/AdminUsersHandler.php
- lupo-includes/themes/default/layouts/admin_layout.php
- lupo-includes/themes/default/layouts/admin_sections/users.php

AUTH / SESSION (existing; do not alter routing or Ban at Gate):
- app/auth/AuthService.php
- app/auth/Session.php
- app/auth/AuthRoleResolver.php
- lupo-includes/functions/auth-helpers.php
- lupo-includes/modules/auth/auth-controller.php

SUPPORTING (for admin flows):
- lupo-includes/modules/content/content-controller.php
- lupo-includes/modules/module-loader.php (reference only; no routing changes)

TEMPLATES:
- lupo-includes/themes/default/layouts/admin*.php
- templates/errors/* (403_banned, smart_404 — reference only)

CONFIG:
- config/global_atoms.yaml

DATABASE (canonical names with LUPO_TABLE_PREFIX, e.g. lupo_):
- lupo_auth_users
- lupo_actors
- lupo_actor_channel_roles
- lupo_actor_channels
- lupo_departments
- lupo_department_roles
- lupo_permissions (if present)
- lupo_contents
- lupo_channels
- lupo_banned_actors
- lupo_bans_log

You must map:
- All admin pages (admin.php?section=...)
- All admin actions (currently only section=users has real content; others placeholder)
- All admin forms
- AdminUsersHandler and any future admin controllers
- All admin templates (admin_layout, admin_sections)
- All missing or broken admin flows

============================================================
4. ADMIN TESTING DOCTRINE (4.0.19)
============================================================
You must prepare to implement:

- Unit tests for admin controllers / AdminUsersHandler
- Integration tests for admin.php flows (curl or browser)
- Regression tests for legacy admin behavior
- Permission/role tests
- Actor-based restriction tests
- Admin CRUD tests (content, users, channels, roles)
- Installer/admin interaction tests

Testing must be:
- PHP 5.3 compatible
- No PHPUnit requirement (simple PHP scripts allowed)
- curl-based integration tests allowed
- Must integrate with T8 test harness from 4.0.18 (tests/unit, tests/integration, tests/regression, scripts/run_unit_tests.sh)

============================================================
5. TASK ORDER FOR 4.0.19
============================================================
When instructed to begin implementation, follow this order:

T1 — Version bump to 4.0.19
T2 — Admin test suite expansion (unit + integration)
T3 — Admin UI audit (list all admin pages, missing features, broken flows)
T4 — Implement missing admin actions (CRUD, permissions, content ops)
T5 — Admin security hardening (auth, roles, actor_id validation)
T6 — Admin diagnostics + logging
T7 — Regression testing for admin + legacy
T8 — Finalization + CHANGELOG update

Do NOT begin any task until explicitly instructed.

============================================================
6. YOUR FIRST OUTPUT
============================================================
Your first output must be:

1. A confirmation that you have loaded all doctrine.
2. A summary of the 4.0.19 scope.
3. A list of admin-related files you will be analyzing.
4. A readiness statement:
   "Awaiting instruction (e.g., 'Do T1')."

Do NOT modify any files yet.

============================================================
END OF INITIALIZATION PROMPT
============================================================
