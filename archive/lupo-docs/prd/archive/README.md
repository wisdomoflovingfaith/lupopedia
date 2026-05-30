# Archived PRDs

These PRDs are deprecated, superseded, or no longer active.

They are kept for **historical reference only**. Do not implement from these documents.
Do not create edges to archived PRDs.

---

| Original PRD | File | Archived Date | Superseded By | Reason |
|---|---|---|---|---|
| 81 | `81_agent_orchestration_chat.md` | 2026-04-15 | PRD 02 | Merged into PRD 02 (Channels & Agent Orchestration) on 2026-04-13 |
| 16 (v4.1.1) | `16_lupopedia_headers.md` | 2026-04-15 | `prd/16_lupopedia_headers.md` (v4.1.2) | Stale snapshot; canonical version is in `lupo-docs/prd/` |

---

## Rules

1. **Do NOT implement** from archived PRDs. Always use the superseding document.
2. **Do NOT create edges** from the memory graph to archived PRDs.
3. **Do NOT update** archived PRDs (except to correct the `archived_date` or `superseded_by` fields).
4. Files here keep their **original filenames** for git history continuity.
5. To archive a new PRD: move it here, set `status: "archived"`, add `archived_date` and `superseded_by` to its header, then update `prd_index.md`.
