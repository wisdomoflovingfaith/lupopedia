# ⚠️ FLIP/FLP - DEPRECATED SYSTEMS

## 🚨 Deprecation Notice

**FLIP and FLP have been replaced by LUPOPEDIA HEADERS**

This folder is retained for **historical reference only**. No new work should target this folder.

## 🔗 Current Canonical System

- **LUPOPEDIA HEADERS**: [lupo-docs/doctrine/LUPOPEDIA_HEADERS/README.md](../LUPOPEDIA_HEADERS/README.md)
- **Deprecation Guide**: [DEPRECATION_FLARE_FLIP_FLP.md](../DEPRECATION_FLARE_FLIP_FLP.md)

## 📋 Migration Status

- ✅ **Completed**: LUPOPEDIA HEADERS is the canonical metadata system
- ✅ **Completed**: All new documents use LUPOPEDIA HEADERS
- 🔄 **In Progress**: Repository-wide cleanup of FLIP/FLP references
- ⏳ **Pending**: Full reference sweep before deletion

## ⚠️ Important Notes

### Do Not Use For New Work
- All new documentation must use LUPOPEDIA HEADERS
- FLIP/FLP headers are no longer maintained
- Tooling should target LUPOPEDIA HEADERS

### Reference Only
- These files are kept for historical context
- May contain useful architectural decisions
- Should not be copied or extended

### Deletion Timeline
This folder will be deleted only after:
1. Complete repository sweep confirms no path dependencies
2. All tooling references converted to LUPOPEDIA HEADERS
3. Migration audit confirms successful transition

---

## 📚 Historical Context

### FLIP — File-Level Inference Protocol
FLIP was the original structured header system for Lupopedia documents. It provided:
- File identity inference from headers
- Structured metadata for documentation
- Cross-reference capabilities

### FLP — Federated Likeness Protocol
FLP was a governance layer built on top of Lupopedia channels:
- Councils as channels
- Emotional geometry and aggregation
- Application-level governance logic

### LUPOPEDIA HEADERS Improvements
LUPOPEDIA HEADERS improves upon both systems with:
- More comprehensive metadata schema
- Better integration with channel-based coordination
- Enhanced audit trail capabilities
- Standardized edge relationships
- Unified approach replacing both FLIP and FLP

---

**Status**: ❌ DEPRECATED  
**Replacement**: ✅ LUPOPEDIA HEADERS  
**Action Required**: Use LUPOPEDIA HEADERS for all new work

---

# Legacy Content (Historical Reference Only)

The following content is preserved for historical context only. Do not use for new development.

---

# lupo-docs/doctrine/FLIP/

**Status:** Permanent. Documentation only. No schema, no SQL, no implementation unless explicitly instructed.

This folder contains doctrine for two distinct protocols:

1. **FLIP — File-Level Inference Protocol** (file-level headers; canonical name for what are also called FLIP Headers, Wolfie Headers, CROP Headers, FLIPPING Headers).
2. **FLP — Federated Likeness Protocol** (councils as channels; governance layer on top of Lupopedia).

---

## FLIP — File-Level Inference Protocol

| File | Description |
|------|-------------|
| [FLIP_DOCTRINE.md](FLIP_DOCTRINE.md) | Canonical FLIP doctrine: infer file identity, doctrine, and meaning from the FLIP Header only; no guessing. |
| [NOTE_HEADER_VERSION_AND_MERGE.md](NOTE_HEADER_VERSION_AND_MERGE.md) | Reminder: set file.last_modified_system_version to current version (4.0.16) when editing; 3.x vs 4.0.x merge and FLIP/Wolfie header naming. |

---

## FLP — Federated Likeness Protocol

The FLP sits entirely on top of existing Lupopedia architecture (channels, actors, semantic OS). All relationships are soft references; all timestamps are BIGINT(14) written by application code.

| File | Description |
|------|-------------|
| [FLP_OVERVIEW.md](FLP_OVERVIEW.md) | High-level description; what the FLP is and is not; mapping onto Lupopedia. |
| [FLP_EMOTIONAL_GEOMETRY.md](FLP_EMOTIONAL_GEOMETRY.md) | RGB axes (MOOD_RGB); blue = memory depth; Kapakai; application-level aggregation. |
| [FLP_COUNCILS_AS_CHANNELS.md](FLP_COUNCILS_AS_CHANNELS.md) | Councils as channels; directory structure; soft references only. |
| [FLP_HETERODOX_REVIEWERS.md](FLP_HETERODOX_REVIEWERS.md) | Heterodox reviewers as application-level agents (e.g. LILITH-style). |
| [FLP_EMOTIONAL_AGGREGATION.md](FLP_EMOTIONAL_AGGREGATION.md) | Aggregation in application code; aggregates stored as plain data. |
| [FLP_ESCROW_AND_FUND_LAYER.md](FLP_ESCROW_AND_FUND_LAYER.md) | Escrow/fund as channels + app-level logs; no DB automation. |
| [FLP_LUPOPEDIA_COUNCIL_SEAT.md](FLP_LUPOPEDIA_COUNCIL_SEAT.md) | Lupopedia as a council channel; metadata and application logic only. |
| [FLP_DOCTRINE_BOUNDARIES.md](FLP_DOCTRINE_BOUNDARIES.md) | Prohibitions (no FKs, triggers, etc.); TOON-only schema; PK doctrine. |

## Cross-references

- **MOOD_RGB:** [lupo-docs/channels/doctrine/MOOD_RGB_DOCTRINE.md](../../channels/doctrine/MOOD_RGB_DOCTRINE.md)
- **Channels (DB):** lupo-docs/doctrine/database/channels.md
