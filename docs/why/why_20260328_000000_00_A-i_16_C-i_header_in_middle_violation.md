# WHY VIOLATION -- 20260328000000

**Failing Cluster:** 00_A-i_16_C-i  
**File being updated:** docs/hawaiian_pidgin_translation_guide.md  
**Validation step:** Header placement / PRD 16 compliance  

## What the AI did wrong

The AI inserted or preserved normal document content before the `lupopedia.headers` block, leaving the header in the middle of the file instead of at the top.

## Root cause

The editing task treated the file as ordinary markdown content instead of a Lupopedia artifact governed by PRD 16 header placement rules.

The AI failed to enforce the rule that `lupopedia.headers` must be the first structural block in markdown artifacts.

The AI also failed to re-scan the full file after edits to verify that no content appeared before the header.

## Correct rule

For markdown artifacts, the `lupopedia.headers` block must be the first block in the file.

No headings, lists, notes, vocabulary entries, comments, or prose may appear before it.

## Required correction

Move the complete `lupopedia.headers` block to the top of `docs/hawaiian_pidgin_translation_guide.md`.

Then validate:

- header appears before all content
- header has 22 canonical fields
- header order matches PRD 16
- no duplicate header fragments remain
- no content was lost during move

## PRD corrections needed

PRD 16 should explicitly state that when editing any existing markdown artifact, agents must verify header placement after edits, not only when creating new files.

PRD 00 should include a forbidden pattern for treating Lupopedia markdown artifacts as ordinary markdown when a `lupopedia.headers` block is present.

A validator should reject any markdown file where non-header content appears before `lupopedia.headers`.

## Recommended validator rule

Reject file if:

```
first non-empty content block is not:
---
lupopedia.headers:
```

for markdown artifacts that require YAML-style Lupopedia headers.

Constitutional reference
PRD 00: Never infer missing doctrine; never update code or artifacts without reading governing PRDs
PRD 16: Lupopedia header structure and placement
PRD 98: WHY Files doctrine

## Rules

- Do NOT modify `docs/hawaiian_pidgin_translation_guide.md` in this task.
- Do NOT modify PRD files in this task.
- Only create the WHY file.
- Report the created filename inline.
