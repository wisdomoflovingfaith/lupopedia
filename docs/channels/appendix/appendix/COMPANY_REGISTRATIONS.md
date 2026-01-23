---
architect: Captain Wolfie
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.15
dialog:
  speaker: CURSOR
  target: @everyone
  message: "Created COMPANY_REGISTRATIONS.md documenting Lupopedia LLC and Crafty Syntax business registrations from South Dakota and Hawaii."
  mood: "00FF00"
tags:
  categories: ["documentation", "company", "legal"]
  collections: ["core-docs"]
  channels: ["public", "dev"]
in_this_file_we_have:
  - Lupopedia LLC Registration
  - Crafty Syntax DBA Registration
  - Historical Crafty Syntax LLC (Hawaii)
file:
  title: "Company Registrations"
  description: "Legal registrations and business entity information for Lupopedia LLC and Crafty Syntax"
  version: "4.0.1"
  status: published
  author: "Captain Wolfie"
---

# Company Registrations

This document catalogs all business registrations and legal entity information for Lupopedia and Crafty Syntax.

## Lupopedia LLC

### South Dakota DBA Registration

**Registration:** Doing Business As (DBA) Fictitious Business Name  
**ID:** UB313079  
**Filing Date:** November 6, 2025  
**Expiration Date:** November 6, 2030 (5-year term)  
**Status:** Active

**Business Address:**
- Physical: 3120 W RAMBLER #309, SIOUX FALLS, SOUTH DAKOTA 57108
- Mailing: 3120 W RAMBLER #309, SIOUX FALLS, SOUTH DAKOTA 57108

**Owner:**
- Eric Robin Gerdes

**Reference Document:** `RegistrationAcknowledge.pdf`

---

## Crafty Syntax

### South Dakota DBA Registration

**Registration:** Doing Business As (DBA) Business Name  
**ID:** UB313670  
**Filing Date:** November 14, 2025  
**Status:** Active  
**Receipt #:** 002752890

**Owner:**
- Eric Robin Gerdes
- Address: 3120 W RAMBLER #309, SIOUX FALLS, SOUTH DAKOTA 57108

**Reference Document:** `DBA_craftysyntax.pdf`

**Notes:**
- This DBA is registered under the name "CRAFTY SYNTAX"
- Filed under the name of Eric Robin Gerdes
- $10.00 filing fee paid via credit card

---

### Historical Crafty Syntax LLC (Hawaii)

**Entity Type:** Domestic Limited Liability Company (LLC)  
**File Number:** 56516 C5  
**Status:** Terminated  
**Purpose:** Computer Programming  
**Registration Date:** October 11, 2007  
**Terminated Date:** Not explicitly stated (status shows as terminated)

**Original Address:**
- Principal: 29 PAHAA PL, PUKALANI, HAWAII 96768
- Mailing: 29 PAHAA PL, PUKALANI, HAWAII 96768

**Registered Agent:**
- Name: Eric Robin Gerdes
- Address: 370 MOKUAHI ST, MAKAWAO, HAWAII 96768

**Manager:**
- Eric Gerdes (MGR) - Appointed October 11, 2007

**Trade Name:**
- Name: CRAFTY SYNTAX
- Registration Date: February 4, 2005
- Expiration Date: February 3, 2010
- Status: Expired

**Annual Filings:**
- 2008: Filed February 20, 2009 (Processed)
- 2009: Filed April 11, 2012 (Processed)
- 2010: Filed April 11, 2012 (Processed)
- 2011: Filed April 11, 2012 (Processed)
- 2012: Not Filed

**Reference Document:** `LLC_crafty_syntax.pdf`

**Notes:**
- Original Crafty Syntax LLC was formed in Hawaii in 2007
- Trade name was registered in 2005 (expired in 2010)
- Entity was terminated at some point after 2012 (exact date not shown in document)
- The Crafty Syntax name and brand continue under the new South Dakota DBA registration (2025)

---

## Company Structure Reference

For current operational structure, team information, and company policies, see the global atom:
- `@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE` in `config/global_atoms.yaml`

**Company Information:**
- Company name: `@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.company.name`
- Formation date: `@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.company.formation_date` (matches South Dakota DBA filing)
- State: `@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.company.state`
- DBA: `@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.company.dba`
- Status: `@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.company.status`

**Operational Target:**
- Target date: `@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.company_target.operational_target_date`
- Flexible: `@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.company_target.target_is_flexible`

**Team Structure:**
- Alpha Team: `@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.teams.alpha.shift_utc`
- Charlie Team: `@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.teams.charlie.shift_utc`
- Bravo Team: `@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.teams.bravo.shift_utc`
- Delta Team: `@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.teams.delta.availability`

**Work Rhythm:**
- Focused Build: `@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.work_rhythm.focused_build.days`
- Recreational Programming Zone: `@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.work_rhythm.recreational_programming_zone.days`
- Monday Adoption Review: `@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE.work_rhythm.monday_adoption_review.description`

---

## Document Storage

The original PDF registration documents should be stored in the project repository at:
- `docs/appendix/legal/RegistrationAcknowledge.pdf` - South Dakota DBA for "LUPOPEDIA" (Nov 6, 2025)
- `docs/appendix/legal/DBA_craftysyntax.pdf` - South Dakota DBA receipt for "CRAFTY SYNTAX" (Nov 14, 2025)
- `docs/appendix/legal/LLC_crafty_syntax.pdf` - Hawaii LLC registration for "CRAFTY SYNTAX LLC" (2007, terminated)

**Note:** The `docs/appendix/legal/` directory should be created and these PDF files should be placed there for permanent archival and easy reference.

These documents serve as legal proof of business registration and should be preserved for record-keeping and compliance purposes.

---

*Last Updated: 2026-01-09*  
*Version: 4.0.1*  
*Author: Captain Wolfie*

---

## Related Documentation

**Business Context:**
- **[History](../../history/HISTORY.md)** - Complete timeline including business evolution from Crafty Syntax to Lupopedia
- **[Founders Note](FOUNDERS_NOTE.md)** - Personal narrative including business journey and company formation

**Technical Context (LOW Priority):**
- **[Definition](../../overview/DEFINITION.md)** - Formal definition of Lupopedia as business product
- **[End Goal 4.2.0](../../overview/END_GOAL_4_2_0.md)** - Business vision for federated ecosystem
- **[Configuration Doctrine](../../doctrine/CONFIGURATION_DOCTRINE.md)** - References to company structure atoms

**Legal Reference (LOW Priority):**
- **Global Atoms:** `config/global_atoms.yaml` - `@GLOBAL.LUPOPEDIA_COMPANY_STRUCTURE` for current operational details
- **Document Storage:** `docs/appendix/legal/` - Physical registration documents (PDFs)

---
