---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260403190000"
  file_path_from_root: "channels/0/organization/prd_29_project_organization/comments/20260403120000_COMMENT_channel_42_archive_cherry_pick_review.md"
  web_path: "http://www.lupopedia.com/lupopedia/channels/0/organization/prd_29_project_organization/comments/20260403120000_COMMENT_channel_42_archive_cherry_pick_review.md"
  last_modified_utc: "20260403190000"
  federation_node_id: 0
  channel_id: 0
  thread_id: "prd_29_project_organization"
  actor_id: 102
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "comment"
  purpose: "Review of channels_before_4_0_93/42/ dated 20260325+ for cherry-pick vs reference-only"
  tags:
    - "archive"
    - "channel_42"
    - "prd_29"
lupopedia.edges:
  outbound_edges:
    - to: "docs/prd/29_project_structure.md"
      type: references
      weight: 1.0
      reason: "Archive cherry-pick policy"
    - to: "channels_before_4_0_93/42/broadcasts/20260326_180000_all_channels_organization_report.md"
      type: references
      weight: 0.95
      reason: "Organization broadcast analysis"
    - to: "channels_before_4_0_93/42/threads/2021/20260330_080000_hephaestus_coordination_database-vs-filesystem-channels.md"
      type: references
      weight: 0.95
      reason: "DB vs filesystem channel architecture discussion"
    - to: "channels_before_4_0_93/42/threads/1054/20260325_231500_cursor_v487_002_channel_docs_alignment_completion.md"
      type: references
      weight: 0.9
      reason: "Channel docs alignment completion"
lupopedia.footer:
  last_verified: "20260403190000"
  verified_by:
    actor_id: 102
  orchestrator: "cursor:root"
---

# file: channel_42 archive review — delegation: cursor:root

# Comment: Channel 42 archive scan (20260325+)

## Policy reminder

**Authoritative:** `docs/prd/29_project_structure.md` — **Archive cherry-pick policy** (channel **42** only; filenames **newer than** `20260325` = **2026-03-26** or later).

Files whose names start with **`20260325_`** are **not** strictly “newer than” `20260325`; treat them as **borderline** (human review) or **reference-only** unless you explicitly extend policy to include that calendar day.

## Inventory (sample)

| Date prefix | Approx. count | Notes |
|-------------|---------------|--------|
| `20260325_` | 24 | Mixed: broadcasts, thread completions, audits for 4.0.87 |
| `20260326_` | 11 | Includes **all_channels_organization_report** |
| `20260327_` | 18 | Heavy thread 2007 (semantic / corruption / phase work) |
| `20260328_` | 18 | Staged drift / header / lupo_context threads |
| `20260329_` | 1 | Lilith coordination log |
| `20260330_`–`20260331_` | 11 | **PRD audits** (thread 1002), **DB vs filesystem** thread |

## What is worth **carrying forward** (by reference, not bulk migration)

Do **not** copy the whole tree. Link from this thread or from PRD 29 / `TODO.md` when needed.

1. **Organization / structure (high)**  
   - **`…/broadcasts/20260326_180000_all_channels_organization_report.md`** — channel/broadcast organization analysis and recommendations.  
   - **`…/threads/2021/20260330_080000_hephaestus_coordination_database-vs-filesystem-channels.md`** — still relevant background for **filesystem vs DB** channel story (update conclusions against current doctrine before treating as decided).

2. **Channel literacy / docs alignment (high)**  
   - **`…/threads/1054/20260325_231500_cursor_v487_002_channel_docs_alignment_completion.md`** — V487-002 listed **root** `README.md`, **`channels/channel_index.md`**, **`channel_creation_doctrine.md`**, legacy **`channels/42/THREAD_INDEX.md`**, and **repo-root** `AGENTS.md`. **AGENTS.md is not** inside `channels/{federation_node_id}/{channel_key}/{thread_key}/`; see **`docs/prd/29_project_structure.md`** (`AGENTS.md` vs thread tree). **Reference** when updating `.cursorrules` and contributor docs (old headers may still point at `channels/42/` — fix paths on any re-publish).

3. **PRD system snapshot (medium)**  
   - **`…/threads/1002/20260331_160000_lilith_prd_system_audit_summary.md`** (and sibling 20260331 LILITH audits) — historical **audit summary**; current truth is in `docs/prd/`. Use as **archive reference**, not a second source of truth.

4. **Large thread 2007 / 2019 / 2020 work (lower priority for PRD 29)**  
   - Mostly **phase execution**, drift manifests, and version-specific (4.0.88) work. **Leave in archive** unless a specific decision is still open.

## Recommendation

- **No mass copy** into `channels/0/organization/prd_29_project_organization/` beyond this comment and future **targeted** decisions.  
- For items (1)–(2), add **short `decisions/` or `questions/`** entries only when you need a **new** decision record (e.g. “resolved: hybrid mirror + new path layout”) with **edges** back to these archive paths.  
- **Optional:** one **`DECISION`** file stating that **V487-002** canonical doc list remains valid with path updates for `channels/` layout.
