# Lupopedia 4.1.6 — PRD Cluster Positional Architecture Doctrine

**Version**: 4.1.6 (Round 2 Refactor)  
**Date**: 2026-04-24  
**Steward**: Captain Wolfie (Eric Robin Gerdes)  
**Status**: Constitutional — Mandatory for all agents and headers

---

## 🧩 Core Principle: Positional Parsing, Not Value-Based

**🧨 THE GROUP NUMBER DOES NOT DETERMINE IMPORTANCE.**  
**🧨 THE LETTER (A–F) DETERMINES SIGNIFICANCE.**  
**🧨 THE ARRAY INDEX (LEFT → RIGHT) DETERMINES PRIORITY.**

This is the single most important rule in 4.1.6.

When a PRD cluster string is split by `_`, the **array index position** (not the numeric value inside the token) determines the element type:

- **Even index** (0, 2, 4, …) → **NUMBER** (Group ID / Category only — grouping, nothing more)
- **Odd index** (1, 3, 5, …) → **LETTER-ROMAN** (Significance + Chronology)

**Example Cluster (new 4.1.6 format)**:
00_A-i_00_C-ii_16_B-i_16_C-v_26_A-i_57_A-x_98_A-iv
text**Parsed Array** (index position = priority — lower index is always higher priority):
| Index | Value    | Type          | Role                                      |
|-------|----------|---------------|----------------------------------|
| 0     | 00       | NUMBER        | Group ID (highest priority by position)  |
| 1     | A-i      | LETTER-ROMAN  | Significance A + Chronology i   |
| 2     | 00       | NUMBER        | Group ID                         |
| 3     | C-ii     | LETTER-ROMAN  | Significance C + Chronology ii  |
| 4     | 16       | NUMBER        | Group ID                         |
| 5     | B-i      | LETTER-ROMAN  | Significance B + Chronology i   |
| 6     | 16       | NUMBER        | Group ID                         |
| 7     | C-v      | LETTER-ROMAN  | Significance C + Chronology v   |
| 8     | 26       | NUMBER        | Group ID                         |
| 9     | A-i      | LETTER-ROMAN  | Significance A + Chronology i   |
| 10    | 57       | NUMBER        | Group ID                         |
| 11    | A-x      | LETTER-ROMAN  | Significance A + Chronology x   |
| 12    | 98       | NUMBER        | Group ID                         |
| 13    | A-iv     | LETTER-ROMAN  | Significance A + Chronology iv  |

**Simplified form (without Roman numerals for legacy compatibility)**:
00_A_00_C_16_B_16_C_26_A_57_A_98_A
text---

## 📉 Strict Left-to-Right Importance Gradient

- **Lower array index = Higher importance**
- **Higher array index = Lower importance**
- Index 0 is the **most important** element in the entire cluster.
- This creates a deterministic, non-negotiable priority chain.
- No semantic inference from the number value itself (00 is not "more important" than 98 because of its digits — position alone decides).

**Rule**: Read and process the cluster **strictly left-to-right**. Never reorder, never infer rank from numeric values.

---

## 🔠 Significance Grading Scale (A–F)

The **letter** in each odd-index `LETTER-ROMAN` pair defines significance:

| Letter | Meaning                  | Doctrine Level                  |
|--------|--------------------------|---------------------------------|
| A      | Forbidden / "do not do"  | Highest — constitutional block  |
| B      | High importance          | Strong directive / suggestion   |
| C      | Moderate significance    | Important but not critical      |
| D      | Minor importance         | Notable but low priority        |
| E      | (Reserved)               | Currently unused                |
| F      | Noise / low significance | Lowest — can be filtered        |

**Roman numeral rules (immutable, append-only, within-letter-group only)**:

- Roman numerals represent the **chronological insertion order** of an item **within its letter significance group** (A-i, A-ii, A-iii…).
- They are assigned at the exact moment the PRD element is introduced and are **immutable** (written in stone).
- They **DO NOT** represent: importance, priority, execution order, or dependency order.
- They **ONLY** represent: when the item entered the system relative to other items **in the same letter group**.
- Roman numerals are append-only. They may become sparse if documents are removed or reorganized.

**Edge-case handling when a Roman numeral is missing (deleted document)**:
- Gaps are **intentional and permanent** — never renumber.
- When a PRD cluster is read and a referenced item is found to be deleted:
  1. **Investigate** what changed.
  2. Update the cluster string: either remove the entry or replace it with the superseded document.
  3. **First priority lookup**: Check **one Roman numeral higher** (e.g., if A-ii is missing, first look for A-iii as the potential superseding document).

---

## 🛡️ Strict Security & Sanitization Rules for All PRD Headers (4.1.6+)

These are now **constitutional doctrine** — violation = AGAPE BLOCK.

**A-Level (Forbidden — do not do under any circumstances)**:
- No newlines in headers
- No emojis
- No Unicode weirdness or non-ASCII characters
- No raw URLs (http://, https://, ftp://, etc.)
- No `../` path traversal sequences
- No injection vectors of any kind
- Everything must be **plain ASCII**
- Everything must be **Notepad-safe** (visible and editable in basic text editors without rendering issues)

**Sanitization Rule for URLs (when unavoidable)**:
- `http://` → `http_dot_slash_slash`
- `https://` → `https_dot_slash_slash`
- All other special characters in URLs must be similarly escaped to plain text.

**Enforcement**: Every PRD header written or updated in 4.1.6+ **must** pass these checks before being committed. Agents must reject any header that violates these rules.

---

## 🧱 Continuity & Immutability Rules for LETTER‑ROMAN Entries (4.1.6)

These rules preserve the historical, append-only, immutable nature of Lupopedia’s PRD evolution.

1. **Roman numerals are append-only.**  
   Once a LETTER‑ROMAN entry (e.g., A-i) is deleted, it can **never** be reused or recreated.

2. **Missing numerals indicate historical deletion.**  
   If A-ii exists but A-i does not, the system **must** treat A-i as a deleted historical entry, **not** an error.

3. **Duplicates are allowed and must be preserved.**  
   Multiple instances of the same LETTER‑ROMAN entry (e.g., multiple A-i files) may exist due to multi‑agent generation.

4. **AI must read all duplicates.**  
   No duplicate may be ignored, overwritten, or merged automatically.

5. **AI may suggest copying duplicates into a canonical file.**  
   This is a safe migration step.  
   **Copy, not move.**  
   Never delete automatically.

6. **Human‑supervised cleanup only.**  
   Removal or consolidation of duplicates must be done manually after review.

**Why these rules exist**: Lupopedia is append-only, immutable, historical, constitutional, multi-agent, and multi-editor. Automatic “fixes” by agents cause data loss, timeline corruption, and semantic drift. These rules prevent that.

---

## 🎯 Formal Purpose of a PRD Cluster (4.1.6) — The Four-Axis System

**In Lupopedia 4.1.6, the numeric group value does not determine importance.**  
**Importance is positional (array index), while significance is encoded in the letter (A–F).**  
**Numbers represent grouping only, and Roman numerals represent chronology only.**

A PRD cluster encodes **four independent axes**:

1. **POSITION (array index)** — PRIORITY  
   Left → right = most important → least important. This is the reading order.

2. **LETTER (A–F)** — SIGNIFICANCE  
   Semantic weight / meaning level of the item (forbidden, high, moderate, minor, noise, etc.).

3. **NUMBER (00, 16, 57, 98…)** — GROUPING  
   Category / functional bucket only. Carries zero priority or significance weight.

4. **ROMAN (i, ii, iii…)** — CHRONOLOGY  
   Order in which the item was added to the system. Zero rank or importance weight.

These four dimensions are **completely independent**.  
Collapsing any of them is the root cause of all AI drift on this architecture.

**One-sentence definition**:
> Lupopedia 4.1.6 introduces a four-axis positional PRD-cluster system in which array index determines priority, letter (A–F) determines significance, number determines grouping only, and Roman numeral determines chronology only — with strict left-to-right reading order and sanitization rules to prevent injection and drift.

---

## 🧠 Why All AI Agents Keep Getting This Wrong (and How 4.1.6 Prevents It)

Every model defaults to the assumption that “numbers = hierarchy” and “letters = subcategory.”  

In Lupopedia 4.1.6 the architecture is the **opposite**:

- Position = hierarchy / priority  
- Letter = significance / meaning weight  
- Number = grouping / category only  
- Roman = chronology / sequence only  

This non-standard four-axis design is why agents collapse everything into a single hierarchy and produce incorrect inferences.  

**The exact sentence that must appear in every 4.1.6 parser, validator, and agent prompt**:

> In Lupopedia 4.1.6, the numeric group value does not determine importance. Importance is positional (array index), while significance is encoded in the letter (A–F). Numbers represent grouping only, and Roman numerals represent chronology only.

Any code or agent that violates this sentence is operating on 4.1.5 logic and must be rejected.

---

## ✅ Implementation Notes for Agents & Scripts

- All future PRD cluster handling code **must**:
  1. Split strictly by `_`
  2. Use modulo-2 index check (`index % 2 == 0` → NUMBER, else LETTER-ROMAN)
  3. Parse LETTER-ROMAN by splitting on `-` (letter part for significance, roman part for chronology)
  4. Never assume numeric value implies importance
  5. Enforce sanitization on any header containing or referencing a prd_cluster

- Legacy clusters (no Roman numerals) remain valid but should be migrated to the new format during 4.1.6 stabilization.

---

**Captain Wolfie — this is the complete, corrected, and constitutional 4.1.6 PRD cluster doctrine.**  
Ready for the next directive, Pack Leader. 🐺

*No pronouns used in reference to you. All references are direct to Captain Wolfie or the system.*