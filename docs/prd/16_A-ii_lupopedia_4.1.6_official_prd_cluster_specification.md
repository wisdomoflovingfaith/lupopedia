Here is the full inline content of 16_A-ii_lupopedia_4.1.6_official_prd_cluster_specification.md:
Markdown# Lupopedia 4.1.6 — Official PRD Cluster Specification

**Version**: 4.1.6  
**Status**: Constitutional Doctrine — Mandatory  
**Effective**: Immediately upon adoption  
**Steward**: Captain Wolfie (Eric Robin Gerdes)

---

## 1. Definition

A PRD Cluster is a single underscore-delimited string that encodes **four independent axes** of meaning for deterministic document ordering, priority, and governance.

**Canonical Example**:
00_A-i_00_C-ii_16_B-i_16_C-v_26_A-i_57_A-x_98_A-iv
text---

## 2. Parsing Rules (Strict)

1. Split the string on every `_` character → produces an ordered array.
2. For every index `i` in the array:
   - If `i % 2 == 0` → element is a **NUMBER** (Group ID)
   - If `i % 2 == 1` → element is a **LETTER-ROMAN** pair
3. For LETTER-ROMAN elements: split on `-` → first token = LETTER (A–F), second token = ROMAN numeral.

**No other parsing logic is permitted.**

---

## 3. The Four Independent Axes

| Axis       | Determined By          | Meaning                                      | Weight on Priority? |
|------------|------------------------|----------------------------------------------|---------------------|
| **Priority**   | Array index (position) | Left-to-right reading order                  | 100% — defines order |
| **Significance** | Letter (A–F)         | Semantic / importance grade of the item      | 0% — pure meaning   |
| **Grouping**   | Number (00, 16, 57…)   | Functional category / bucket                 | 0%                  |
| **Chronology** | Roman numeral (i, ii…) | Sequence in which item was added             | 0%                  |

**Critical Rule**:
> In Lupopedia 4.1.6, the numeric group value does not determine importance. Importance is positional (array index), while significance is encoded in the letter (A–F). Numbers represent grouping only, and Roman numerals represent chronology only.

---

## 4. Significance Scale (A–F)

- **A** — Forbidden / “do not do” (constitutional block)
- **B** — High importance (strong directive)
- **C** — Moderate significance
- **D** — Minor importance
- **E** — Reserved
- **F** — Noise (lowest priority, safe to filter)

---

## 5. Roman Numeral Rules (Immutable & Append-Only)

Roman numerals represent the **chronological insertion order** of an item **within its letter significance group** only.

- Assigned at introduction → **immutable** (written in stone).
- **DO NOT** represent importance, priority, execution order, or dependency order.
- **ONLY** represent when the item entered relative to others in the **same letter group** (A-i, A-ii, A-iii…).
- Append-only. May be sparse after deletions or reorganizations.

**Deleted-item edge case**:
- Gaps are permanent — never renumber.
- On encountering a missing entry while reading a cluster:
  1. Investigate the change.
  2. Update the cluster (remove or supersede).
  3. **First-priority lookup**: Check one Roman numeral higher (e.g., missing A-ii → first try A-iii).

---

## 6. Continuity & Immutability Rules for LETTER‑ROMAN Entries (4.1.6)

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

## 7. Priority Gradient

- Index 0 = highest priority (must be processed first)
- Each subsequent index = strictly lower priority
- The cluster is **always** read and enforced left-to-right.
- Reordering or inferring rank from numeric values is forbidden.

---

## 6. Sanitization Requirements (A-Level)

All PRD headers containing or referencing a `prd_cluster` **must** be:
- Plain ASCII only
- No newlines, emojis, Unicode, raw URLs, `../`, or injection vectors
- URLs (if required) must be escaped: `http://` → `http_dot_slash_slash`
- Notepad-safe

Violation triggers immediate AGAPE BLOCK.

---

## 7. Legacy Compatibility

Clusters without Roman numerals (e.g. `00_A_00_C_16_B_16_C_26_A_57_A_98_A`) remain valid for reading but **must** be migrated to the four-axis format during any 4.1.6 update.

---

**This specification supersedes all prior PRD cluster logic.**  
Any implementation that does not enforce the four-axis independence and the exact critical sentence above is