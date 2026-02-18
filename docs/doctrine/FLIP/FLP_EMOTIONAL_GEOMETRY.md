---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/FLIP/FLP_EMOTIONAL_GEOMETRY.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "00000000000000"
# channel_id unresolved — requires lupo_contents lookup by application.
---
# FLP — Emotional Geometry

**Status:** Permanent. Documentation only.  
**Audience:** All AI agents (including Cursor), contributors, and system stewards.  
**Depends on:** [MOOD_RGB_DOCTRINE.md](../../channels/doctrine/MOOD_RGB_DOCTRINE.md) (Counting-in-Light emotional polarity tensor).

---

## 1. Alignment with MOOD_RGB doctrine

The FLP uses the same emotional geometry as Lupopedia’s canonical mood system. All RGB axes and interpretations **must** align with **MOOD_RGB_DOCTRINE.md**.

- **R (Red)** — Strife, conflict, tension.  
- **G (Green)** — Harmony, cooperation, warmth.  
- **B (Blue)** — **Memory depth / historical weight.** Not clarity.

The FLP does **not** redefine these axes. Blue is explicitly **memory depth and historical weight** in FLP context; any use of “clarity” for the B axis is out of scope and non-doctrinal.

---

## 2. Kapakai as uncertainty / liminality

**Kapakai** is allowed as an uncertainty or liminality marker within the FLP. It represents ambiguous, transitional, or indeterminate emotional states. How Kapakai is encoded (e.g. in metadata, in mood tensor extensions, or in application-level flags) is an implementation concern, provided:

- No database-side logic is used to interpret or aggregate Kapakai.
- All interpretation and aggregation is performed in application code.
- Schema, if any, originates from TOON files only.

---

## 3. How emotional states are represented and interpreted

- **Representation** — Emotional states are represented using the same mood tensor (e.g. RRGGBB hex) and conventions as in MOOD_RGB_DOCTRINE.md. Council-level or aggregate emotional state is stored as **plain data** written explicitly by application code (e.g. in channel metadata or dedicated content).
- **Interpretation** — Interpretation of mood values (including Kapakai and cross-council blending) is **application-level only**. No triggers, stored procedures, or database-side computation may derive or update emotional state.

---

## 4. No DB logic; aggregation is application-level

All emotional aggregation (blending across councils, rollups, or derived emotional state) is performed **entirely in application code**. Results may be written back as plain data (e.g. into channel metadata or logs). The database stores only what the application explicitly writes; it does not compute, default, or auto-update emotional state.

---

*End of FLP emotional geometry. No schema, no SQL, no implementation in this document.*
