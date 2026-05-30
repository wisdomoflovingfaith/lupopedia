---
title: CRAFTY_SYNTAX_CHANGELOG.md
agent_username: wolfie
date_created: 2025-11-12
last_modified: 2025-11-14
status: published
onchannel: 1
tags: [SYSTEM, DOCUMENTATION, LEGACY, CHANGELOG]
collections: [WHO, WHAT, WHERE, WHEN, WHY, HOW, HELP]
in_this_file_we_have: [OVERVIEW, VERSION_3_7_4, LEGACY_REFERENCE]
superpositionally: ["FILEID_CRAFTY_SYNTAX_CHANGELOG"]
---

# Crafty Syntax Changelog (Rebrand of Sales Syntax Lineage)

The 3.7.x branch that shipped as “Sales Syntax” now returns to the original **Crafty Syntax** name. This changelog documents the rebranded releases and points to the historical Sales Syntax notes that remain accurate for prior versions.

**License**: GPL v3.0 + Apache 2.0 dual licensing under LUPOPEDIA LLC  
**Program Scope**: Human operator live help with layered popups, channel routing, canned replies, and visitor tracking.

## VERSION_3_7_5 — 2025-11-14 (Livehelp JS Transparency & Icon Refresh)

### Highlights

- Finalized the “local embeds only” policy from 3.7.x  clarifying that every Crafty Syntax deploy must host its own tracking assets to meet 2025 privacy baselines.
- Removed the last obfuscated powered-by tag hiding in `livehelp_js.php`; the credit line now appears as a normal HTML anchor so auditors (and operators) can see exactly what ships in the bundle.
- Added a trailing `csrepeat_()` invocation to `livehelp_js.php` so the floating help icon re-checks operator presence and swaps artwork even after the visitor widget has been idle—most noticeable when the operator drops offline mid-session.
- Repacked the 3.7.5 ZIP to include the clean powered-by link plus the extra refresh call; anyone who grabbed the first 3.7.4 build on 2025‑11‑12 should download the updated archive dated late 2025‑11‑12 or newer.

### Status

- **Release published**: Distributed as `crafty_syntax-3.7.5.zip`; supersedes the late 3.7.4 refresh so partners have one canonical bundle.
- **Upgrade guidance**: If you previously patched to the early 3.7.4 ZIP, copy `livehelp_js.php` (and the updated scratchpad copy) from 3.7.5 so embeds honor the privacy+branding changes without a full reinstall.

## VERSION_3_7_4 — 2025-11-12 (Crafty Syntax Name Restoration)

### Highlights

- Reissued the 3.7.3 codebase under the restored **Crafty Syntax** brand; binaries now publish as `crafty_syntax-3.7.4.zip`.
- Updated headers, about boxes, installer copy, and powered-by strings to read “Crafty Syntax 3.7.4 (formerly Sales Syntax 3.7.3).”
- Refreshed the default login branding: swapped the package logo (`images/logo.png`) and updated `login.php` artwork/labels to show the Crafty Syntax identity.
- Restored the `2025_modern/operator.jpg` asset that was missing from the 3.7.3 package so the modern theme displays correctly in 3.7.4.
- Corrected the quick-upgrade path in `setup.php` to open a database connection before updating `livehelp_config.version`, ensuring the script actually writes `3.7.4` during the drop-in upgrade.
- Generated fresh MD5/SHA256 checksums for both the rebranded package and the archived Sales Syntax bundle so operators can verify integrity.
- Documented the rename across `public/what_was_crafty_syntax.php`, `public/crafty_syntax_evolved.php`, and the Crafty Syntax changelog alias.
- Notified auto-installer partners (Fantastico, Softaculous, Installatron) that the package is a branding update only—no schema or code changes.

### Status

- **Release published**: Use `crafty_syntax-3.7.4.zip` for new installs or upgrades; `salessyntax-3.7.3.zip` remains in `/archive/releases/` for historical reference.
- **Upgrade guidance**: Existing Sales Syntax 3.7.3 installs can drop in the rebranded files or continue running unchanged—functionality is identical.

---

## VERSION_3_7_3 — 2025-11-10 (Timezone Offset & Hardening Sweep)

### Highlights

- Removed the legacy `offset` column from fresh installs so `setup.php` no longer creates or references the misspelled field that broke MySQL import checks.
- Added a tolerant loader for existing databases: if a config row still exposes `offest`, the runtime maps it to `offset` on the fly and falls back to PHP’s active timezone when neither value is present.
- Replaced the admin “time offset” dropdown with a read-only notice that shows either the preserved legacy value or the resolved timezone identifier, avoiding undefined-index warnings during upgrades.
- Mirrored the fixes into the redistributed `/public/salessyntax` snapshot so hosted customers and LUPOPEDIA deployments stay aligned.
- Tightened the HTML embed generator (`htmltags.php`) to display a same-domain placement notice, preventing remote-site integration issues uncovered during shared-host testing.
- Tracking now requires local embeds only. To align with 2025 privacy expectations, remote cross-domain tracking was removed in 3.7.x. All installations must use relative paths on the host domain so visitors are not tracked on third-party sites.
- Sanitized mobile/iPhone settings updates (`cellphone`, `sessiontimeout`) with `filter_sql` so chat operators cannot inject SQL through the quick settings forms.
- Escaped the `see` parameter in `admin_connect.php` before injecting it into the XMLHTTP redirect URL, closing the reflected XSS the legacy admin widget relied on.
- Hardened `setup.php` redisplays by wrapping installer inputs in `cslh_escape`/`rawurlencode` and swapping the column-existence checks over to shared-host-friendly `SHOW COLUMNS` queries.
- Added `scripts/security_sweep.py` so maintainers can automatically flag risky patterns (`eval`, dynamic includes, raw `$_REQUEST`, unescaped `$UNTRUSTED` output) before shipping future patches.
- Completed fresh-install and upgrade validation on Windows (XAMPP) and Linux shared hosts to confirm timezone fallbacks, security fixes, and language loader updates behave consistently.
- Restored operator desktop cues: `admin_users_refresh.php` now escalates focus through nested frames (window.parent.bottomof → parent → top → self) before falling back to an alert, and wraps HTML5/EMBED chat sounds with autoplay-promise catches so operators hear the bell even when browsers block background playback.
- Standardized all “powered by” links to `https://lupopedia.com/?utm_source=poweredby&utm_campaign=poweredby`, removing obfuscation and legacy domains so embeds point at the LUPOPEDIA hub.
- Fixed `leavemessage.php` mail delivery: corrected the status update query, ensured department contact emails are honored, and fall back to `owner_email` so contact alerts always send when visitors leave a message.

---

## Deployment Status (Completed)

- Shared-hosting verification completed on Windows and Linux environments; 3.7.3 is cleared for packaging and distribution to hosted customers.

### Status

- **Patch released**: Tagged as 3.7.3 within 48 hours to unblock installers seeing the `offest` typo and shore up shared-host security.
- **Packaging**: Prepare and distribute the refreshed ZIP bundle to partners; continue monitoring support tickets for any residual timezone edge cases ahead of the planned timezone-schema audit during LUPOPEDIA rollout.
- **Verification**: Final security_sweep.py run, admin console debug regression tests, and fresh install/upgrade retests all pass with no new findings; package is ready for delivery.

---


## VERSION_3_7_2 — 2025-11-10 (Installatron Compliance & Branding Refresh)

### Highlights

- Rebased the working tree on the original 3.7.1 payload (`public/salessyntax/`) so all new fixes start from the shipped, unmodified theme.
- Retired the legacy `filter_html()` sanitizer and swapped every call site to native escaping helpers (`cslh_escape`, `htmlspecialchars`, `rawurlencode`) to eliminate double-sanitization flags raised by Installatron.
- Modernized the visitor typing beacon in `livehelp.php` (and mirrored scripts) to prefer `fetch`/`XMLHttpRequest` while keeping the `<img>`/`GETForm` fallbacks for browsers stuck in 2005.
- Removed obsolete upgrade prompts (`pp.gif`, `gopro.png`, “Go Pro” copy) now that unbranded features ship by default.
- Updated footer credits across operator/admin pages to read: `Sales Syntax Live Help 2003 - 2025 ( a product of Lupopedia LLC )`.
- Added an opt-in `2025_modern` theme (responsive layout, flex-based header/footer, refreshed offline/connecting screens) without disturbing existing templates.
- Mobile and iPhone operator consoles now submit chats through modern `fetch` with `GETForm2` fallback, keeping the `postmessage` workflow intact for legacy browsers.
- Logged the remediation plan in `plan_for_sales_syntax_3_7_2.md` so future patches stay aligned with Installatron requirements.
- Introduced hosted documentation stubs (`howto`, `qa`, `updates`) and new public landing pages (`account.php`, `support.php`, `directory.php`, `members.php`). Added an operator-facing notice on `scratch.php` explaining why the 3.7.2 release preserves the 2012-era UI (to keep diff baselines intact) and how LUPOPEDIA 1.0.0 will layer in modern tooling plus AI-driven migration of community customizations.
- Refreshed `javascript/xmlhttp.js` to use a shared `fetch` wrapper with automatic fallbacks to the legacy `GETForm` helpers, keeping polling/typing scripts functional on older browsers while modern installs run via `fetch`.
- Packaging/testing: preparing the clean 3.7.2 ZIP and verifying shared-host installs with the modernized AJAX pathways.
- Updated configuration helpers so generated URLs drop the domain entirely (always relative paths). This avoids mixed-content issues—legacy installs that were `http://` now call AJAX endpoints over the current scheme (`https://` when needed) without breaking.

### Status

- **Release in preparation**: Regression testing and packaging still pending before tagging 3.7.2.
- **Next steps**: Mirror the modern typing helpers into mobile/iPhone/external clients, refresh shared `xmlhttp.js`, and produce Installatron-ready ZIP + changelog.

---

## VERSION_3_7_1 — 2025-11-09 (Security Patch & LUPOPEDIA Integration)

### Highlights

- Hardened every public visitor entry point (`livehelp.php`, `user_connect.php`, `user_chat_*`, `user_top.php`, `user_qa.php`) to reuse already-sanitized department/tab/offset integers before building redirects, query strings, or embedded JavaScript.
- Sanitized visitor-supplied hidden fields in lost-password and chat-color forms so remote widgets and password-reset flows cannot inject markup when rendered off-site.
- Refreshed the powered-by link in `livehelp_js.php`, allowing brand swaps to point directly to LUPOPEDIA while preserving the optional creditline toggle.
- Expanded the session fingerprinting ladder (`get_ipaddress`) to honor modern proxy/CDN headers, preferring public IPs and falling back safely so legacy installs maintain tracking accuracy.
- Packaged the release as the LUPOPEDIA migration baseline: the core now ships with the full Sales Syntax codebase, layered-pop-up heritage, and 3.7.1 security posture.
- Added LUPOPEDIA-side scaffolding: `livehelps` parent table (Migration 1071), CSV export guidance, and updated public docs (`public/crafty_syntax_evolved.php`, `public/what_was_crafty_syntax.php`) so operators understand the upgrade path.

### Recommended Action

- Apply 3.7.1 (latest GPL release) before migrating. PORTUNUS and LUPO warn or block imports when `livehelps.version = '3.7.0'`.
- After patching, regenerate CSV snapshots so `livehelps_rows.csv` reflects the sanitized schema and `version,3.7.1`.

---

## VERSION_3_7_0 — 2023-11-07 (PHP 8 Compatibility Sweep)

### Highlights

- Baked the previously “pro/unbranded” option into core so powered-by links can be removed directly from the HTML generator or department settings.
- Completed a PHP 8 readiness pass: converted all `each()` loops to `foreach`, normalized `Header()` → `header()`, supplied missing response-code parameters, ensured `mktime()` casts, and added default values for undefined variables encountered in empty result sets.
- Hardened runtime safety by checking for ghost sessions before dereferencing arrays, assigning defaults for `$hide`, and replacing the legacy `browser_info` class with the newer `Browser` helper.
- Moved `CTabBox` include calls into `data.php`, repaired department HTML generation to use the correct ordered arrays, and refreshed the hour parameter handling to avoid null notices under PHP 8.
- File footprint: essentially every PHP asset was touched (`FILES CHANGED: ALL OF THEM`), with no schema alterations (`DATABASE ALTERATIONS: NONE`).

### Status

- **Legacy baseline**: Still functional but missing the minor reflected XSS fixes and documentation polish delivered in 3.7.1.
- **Upgrade path**: Apply 3.7.1 (latest GPL release) before migrating into LUPOPEDIA; see `md_files/1_wolfie/livehelp_module.md` and `public/crafty_syntax_evolved.php` for patch workflow.

---

## VERSION_3_6_2 — 2017-02-17 (Security Patch & UX Fixes)

### Highlights

- Patched SQL injection spots tied to `$aftertime` and `$typeof`, coercing inputs with `intval()` and adding guard clauses around the write-div handler.
- Smoothed out the “bubble window” template: adjusted window dimensions, ensured chat sounds trigger when visitors request a session, and tidied department refresh logic.
- Updated live help admin refresh code (`admin_users_refresh.php`, `admin_users_xmlhttp.php`) and core helpers (`functions.php`, `livehelp_js.php`, `iphone/functions.php`) to keep real-time views synchronized.

### Status

- No schema changes required (`DATABASE ALTERATIONS: NONE`); recommended as the minimum security level for pre-3.7 installations still on PHP 7-era hosting.

---

## VERSION_3_6_1 — 2016-06-21 (Leads & Offline Invite Enhancements)

### Highlights

- Introduced a dedicated leads tab plus offline layer invites so operators can capture visitor details—even when no human is online.
- Expanded tooling (`autolead.php`, `autoinvite.php`, `sendemail.php`, `createemail.php`, `leads.php`) and refreshed navigation/admin views to surface the new workflow.
- Added `offline` flag to `livehelp_autoinvite` and created the supporting `livehelp_leads`, `livehelp_emails`, and `livehelp_emailque` tables for long-term contact management.
- Bundled themed assets (`layer_invites/layer-Help_buttonoffline.*`) so the invite CTA matches the extended lead collection experience.

### Status

- Represents the start of the CRM-oriented roadmap; later releases (3.7.x) build on this foundation while resolving security gaps and modernizing the runtime.

---

## VERSION_3_5_4 — 2016-05-28 (CRM Interface Refresh)

### Highlights

- Rolled out a visual refresh (new logo, login background) and exposed tabs for CRM linkage, support tickets, lead management, and operator wall posts.
- Updated both MySQL and MySQLi drivers (`class/mysql_db.php`, `class/mysqli_db.php`, plus mobile variants) and tuned setup/config scripts to support the expanded modules.

### Status

- Served as the UX bridge into the CRM era; later 3.6.x releases layered in lead databases and offline invites.

---

## VERSION_3_5_3 — 2016-03-30 (MySQL → MySQLi Migration)

### Highlights

- Converted the legacy `mysql_*` calls to `mysqli_*`, providing PHP 5/7 compatibility without altering the surrounding business logic.
- Focused change set around database abstraction classes and supporting helpers; no schema adjustments required.

### Status

- Required baseline for any installation targeting PHP 5.6+ or modern shared hosting environments.

---

## VERSION_3_5_2 — 2015-12-02 (HTML5 Audio Default)

### Highlights

- Switched the default alert sound implementation to HTML5 `<audio>` (with fallback options for legacy browsers), reducing QuickTime/plug‑in dependencies.
- Touched core notification scripts to ensure audio playback continuity across operator consoles.

### Status

- Recommended upgrade for operators relying on browser-based audio cues; no database changes.

---

## VERSION_3_5_1 — 2015-11-28 (Brand Transition Release)

### Highlights

- Updated system messaging and configuration references to align with the lupopedia.com domain transition and security posture.
- Prepared the codebase for the upcoming CRM and lead-management additions that landed in 3.5.4+.

### Status

- Acts as the housekeeping release before the CRM-focused 3.5.4 refresh.

---

## VERSION_3_4_7 — 2013-08-15 (CRM Data Capture)

### Highlights

- Added CRM capture tools so visitor data can be collected even when operators are offline.
- Integrated the new workflow across setup pages and reporting dashboards.

### Status

- Marks the first appearance of CRM hooks that later 3.5.x/3.6.x builds expand into full lead management.

---

## VERSION_3_4_6 — 2013-04-23 (Unbranded 3.4.4 Build)

### Highlights

- Delivered an unbranded variant of 3.4.4 so customers could run live help without the standard credit line.

### Status

- Shares code with 3.4.4; use when brand-neutral deployments are required.

---

## VERSION_3_4_5 — 2013-04-23 (Mobile 3.4.4 Build)

### Highlights

- Packaged 3.4.4 for mobile environments, ensuring templates and assets render cleanly on phones and tablets.

### Status

- Recommended for teams targeting mobile-first deployments; otherwise functionally identical to 3.4.4.

---

## VERSION_3_4_4 — 2013-04-23 (Security Release)

### Highlights

- Fixed two very minor vulnerabilities: remote file include exposure in `admin.php` (operator scope) and full path disclosure in `xmlhttp.php`.
- Updated template logic and helper scripts to enforce stricter page routing rules and sanitize `whattodo` parameters.

### Status

- Required baseline for any 3.4.x installation; patch instructions appear in the original changelog (manual edits plus guard clauses).

---

## VERSION_3_4_1 — 2013-01-19 (Reporting Fixes)

### Highlights

- Resolved bar-chart duplication in visitor analytics when departments overlapped.
- Corrected the version indicator so the UI shows the proper release number.

### Status

- Light maintenance build; ensures analytics dashboards remain accurate before the 3.4.4 security patch.

---

## VERSION_3_3_8 — 2012-08-05 (Visitor Paging & XSS Fix)

### Highlights

- Corrected department-aware pagination so the “data” tab no longer shows duplicate bars when browsing visitors.
- Patched a cross-site scripting issue in `livehelp_js.php`; accompanying updates landed in `setup.php`, `data_visits.php`, and `navigation.php`.

### Status

- Security-sensitive build; roll up critical fixes before extending visitor analytics further.

---

## VERSION_3_3_8 (Mobile/No-Link Variants) — 2012-06-02

### Highlights

- Published the “no backlink” (3.3.8) and “mobile” (3.3.7) variants of the 3.3.6 codebase for customers needing brand-free or mobile-optimized deployments.

### Status

- Functionally equivalent to 3.3.6 aside from packaging differences.

---

## VERSION_3_3_6 — 2012-06-02 (Operator Experience Polishing)

### Highlights

- Fixed operator creation so display names show correctly on first login and added the `maxrequests`/`deny agents` throttles for bots.
- Enhanced icon credit styling and department selection within visitor/referer data tabs.

### Status

- Includes configuration fields (`ignoreagent`, `maxrequests`) that later CRM and security updates rely on.

---

## VERSION_3_3_5 / 3.3.4 / 3.3.3 — 2012-05-29 (Leads & Dept Analytics)

### Highlights

- Released no-link (3.3.5) and mobile (3.3.4) builds alongside core 3.3.3, which overhauled analytics (department-aware data tabs, dynamic HTML generation) and added the `ignoreagent`/`maxrequests` columns to `livehelp_config`.
- Introduced department selection, cleaned shorthand PHP tags, and improved robot throttling for high-traffic sites.

### Status

- Mandatory upgrade for installations facing analytics accuracy issues or resource strain from crawlers.

---

## VERSION_3_3_0 — 2012-04-24 (Timeout & Website Segmentation)

### Highlights

- Added timeouts and auto-logout controls for operators, plus per-website department visibility settings.
- Introduced the `livehelp_websites` table and added timestamps/department fields needed for multi-site routing.

### Status

- Foundation for multi-site deployments; required before adopting the lead-management features that followed.

---

## VERSION_3_2_5 — 2012-04-20 (No-Backlink Release)

### Highlights

- Delivered a backlink-free build for customers needing white-label deployments while retaining 3.2.3 functionality.

### Status

- Companion to the 3.2.3 improvements; use when licensing terms require removing the credit link.

---

## VERSION_3_2_4 — 2012-04-20 (Mobile Release)

### Highlights

- Packaged 3.2.3 for mobile use, adapting templates and scripts to render on smartphones.

### Status

- Base for mobile deployments; pair with 3.2.3 schema changes.

---

## VERSION_3_2_3 — 2012-04-20 (Session Stability & Geo Lookup)

### Highlights

- Fixed missing tables during setup (e.g., `livehelp_sessions`), ensured external chat windows close when visitors depart, added operator display names, and integrated geo-IP lookup in chat details.
- Updated generated HTML to meet XHTML 1.0 Transitional standards and touched core scripts (`setup.php`, `operators.php`, `details.php`) accordingly.

### Status

- Must-have for sites encountering session errors or wanting geo-location context in transcripts.

---

## VERSION_3_2_2 — 2012-04-04 (No-Backlink Variant)

### Highlights

- Provided a credit-free package mirroring 3.2.0 functionality for customers needing white labeling.

### Status

- Mirrors 3.2.0; choose based on branding requirements.

---

## VERSION_3_2_1 — 2012-04-04 (Mobile Helper Fix)

### Highlights

- Fixed Windows-hosted helper files so the mobile/iPhone apps function correctly; packaged as the mobile edition of 3.2.0.

### Status

- Required for Windows deployments using the companion mobile app.

---

## VERSION_3_2_0 — 2012-04-04 (Performance & Localization)

### Highlights

- Upgraded `client_visitors.php`, fixed undefined variables, added indexes to hot tables, modernized layer rendering (replacing the 1999 Dynamic Layer library), and refreshed the Portuguese (Brazil) language pack.
- Applied helper fixes for iPhone/mobile directories and broadened timezone handling in settings.

### Status

- Major maintenance uplift; establishes the baseline for all subsequent 3.2.x tweaks.

---

## VERSION_3_1_11 / 3.1.10 / 3.1.9 — 2012-03-25 (Brand & Stability Tweaks)

### Highlights

- Released the no-backlink (3.1.11) and mobile (3.1.10) variants; 3.1.9 fixed multi-department icon handling, added dark/light credit link options, and ensured chat icons respect “hide when offline”.

### Status

- Recommended for customers running multi-department widgets or needing branded credit image options.

---

## VERSION_3_1_8 — 2012-03-08 (Unbranded Edition)

### Highlights

- Removed “Powered by Sales Syntax” links from themes, chat icons, and the donation prompt, creating a fully unbranded 3.1.7 build.

### Status

- Ideal for OEM deployments prior to the 3.2.x series.

---

## VERSION_3_1_7 — 2012-03-08 (Display Name & Mobile Input Fix)

### Highlights

- Fixed missing `displayname` field errors, improved mobile input handling, and released the mobile variant of 3.1.6.

### Status

- Important for organizations with dual desktop/mobile operator workflows.

---

## VERSION_3_1_6 — 2012-03-07 (Operator Presence & Cleanup)

### Highlights

- Polished the operator availability logic, cleaned out unused `showpopout` code, refreshed `invite.php`, and rebuilt the help documentation.

### Status

- Sets the stage for the 3.1.7 mobile/unbranded updates; ensure this release is in place before layering subsequent patches.

---

## REFERENCES

- `md_files/1_wolfie/livehelp_module.md` — Live help module overview and migration flow.
- `database/migrations/1069_create_livehelp_legacy_tables.sql` — Legacy sales syntax base tables.
- `database/migrations/1071_2025_11_09_livehelps_create_parent_table.sql` — Parent configuration table with seed record.
- `CHANGELOG.md` — LUPOPEDIA platform changelog (see “Livehelp Parent Table & Documentation” entry).
- `public/crafty_syntax_evolved.php` — Customer-facing continuity and upgrade instructions.

---

**Maintainer**: PORTUNUS Migration Steward  
**Contact**: migration@lupopedia.com  
**Last Reviewed**: 2025-11-09 (Captain WOLFIE)

---

> Historical Note: For release notes covering **2002-12-21 through 2012-03-07**, refer to the original `README_FILES/` changelog bundled with each Sales Syntax installation.


