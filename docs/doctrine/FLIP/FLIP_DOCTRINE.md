---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/FLIP/FLIP_DOCTRINE.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "00000000000000"
# channel_id unresolved — requires lupo_contents lookup by application.
---
# FLIP — File-Level Inference Protocol

**Status:** Permanent.  
**Audience:** All AI agents (including Cursor), contributors, and system stewards.  
**Canonical:** This is the single source of truth for FLIP. No duplicate or suffixed FLIP doctrine files.

---

## 1. Definition

**FLIP** stands for **File-Level Inference Protocol**.

It is the formal rule set that governs how Lupopedia and its AI agents interpret files. When a file is "flipped" to the system (e.g. handed to Cursor or any agent), the agent must **infer** everything it needs to know about that file **entirely from the FLIP Headers (alias: Wolfie Headers, CROP Headers)** — without guessing, without hallucinating, and without requiring context from elsewhere.

---

## 2. The acronym

| Letter | Meaning | Doctrine |
|--------|---------|----------|
| **F** — File | Every file in Lupopedia is a first-class semantic object. | Files carry identity, lineage, doctrine, and emotional metadata. They are not passive blobs. |
| **L** — Level | Inference happens at the **file level**. | The boundary and truth source for that file is the FLIP Header. Not the database, not the system — the file. |
| **I** — Inference | When a file is flipped to the system, the AI must **infer** from the header. | Identity, lineage, channel, version, emotional state, doctrine, placement, semantic meaning — all from the header. |
| **P** — Protocol | FLIP is the formal rule set. | It governs how Lupopedia and its AI agents interpret files. |

---

## 3. One-sentence definition

**FLIP is the protocol that tells Lupopedia: when a file is flipped to you, read its FLIP Header and infer everything you need to know — identity, doctrine, meaning, and emotional state — without guessing.**

---

## 4. What must be inferred from the FLIP Header

When a file is flipped to an agent, the agent must infer the following **entirely from the FLIP Header** (where present in the header). No guessing. No filling in from repo scan or external context.

- **File identity** — What this file is; its name, title, description.
- **File lineage** — Version, last modified system version, temporal placement.
- **File channel** — Channel key, channel identity, routing context.
- **File version** — System version at last edit; per-file version if present.
- **File emotional state** — Mood tensor (e.g. mood_RGB), emotional metadata per MOOD_RGB doctrine.
- **File doctrine** — Which doctrines apply; governance markers; header_atoms.
- **File placement** — Where the file sits in the semantic OS (collections, categories, paths).
- **File semantic meaning** — What the file is for; in_this_file_we_have; dialog context if present.

If a field is absent from the header, the agent must **not** invent it. Infer only what the header provides. Omission is information.

---

## 5. Why FLIP matters

Lupopedia is a **semantic OS**, not a framework. Files are not passive — they carry doctrine, metadata, emotional geometry, version lineage, channel identity, and semantic meaning. FLIP ensures that when you hand an agent a file, it **knows exactly what it is** from the header alone.

FLIP is what makes FLIP Headers **operational** instead of just descriptive. The header is not decoration; it is the **contract** for that file.

---

## 6. Relationship to other doctrine

- **FLIP Header specification** — Defines the structure and fields of the header (also known as Wolfie Header specification). FLIP defines **how** agents must use that structure (infer from it; do not guess). See `docs/channels/agents/WOLFIE_HEADER_SPECIFICATION.md` and `docs/channels/doctrine/WOLFIE_HEADER_DOCTRINE.md`.
- **MOOD_RGB / emotional geometry** — Emotional state in the header (e.g. mood_RGB) is inferred and interpreted per MOOD_RGB doctrine. FLIP does not redefine emotional axes.
- **TOON / schema** — FLIP governs **file** interpretation. Schema and table definitions still come from TOON files only. FLIP does not replace schema doctrine.

---

## 7. FLIP compliance checklist for agents

When handling any Lupopedia file that has a FLIP Header, the agent MUST:

1. **Read the header first** — Before inferring anything about the file, read the full YAML block between the leading `---` delimiters.
2. **Infer only from the header** — Do not guess identity, channel, version, or doctrine from path, filename, or repo structure. Use only what the header states or implies.
3. **Do not hallucinate fields** — If the header does not contain a field (e.g. mood_RGB, channel_key), do not invent a value. Treat absence as absence.
4. **Respect header_atoms** — Resolve symbolic references (e.g. GLOBAL_CURRENT_LUPOPEDIA_VERSION) from the project's atom source (e.g. config/global_atoms.yaml), not from guesswork.
5. **Use inferred state for all downstream behavior** — Routing, permissions, emotional context, and placement decisions must use the inferred file identity and metadata, not external assumptions.
6. **Do not alter the header to "fix" inference** — If something is missing, do not add it to the header unless explicitly asked to update the file. FLIP is read-only inference; header edits are separate operations.

---

## 8. Summary

| Rule | Requirement |
|------|-------------|
| **File = first-class object** | Every file has identity, lineage, doctrine, emotional metadata. |
| **Level = file** | Inference boundary is the file; truth source is the FLIP Header. |
| **Inference = from header only** | Identity, lineage, channel, version, emotional state, doctrine, placement, meaning — all inferred from the header. No guessing. |
| **Protocol** | FLIP is the formal rule set for how Lupopedia and AI agents interpret files. |

---

*End of FLIP doctrine.*
