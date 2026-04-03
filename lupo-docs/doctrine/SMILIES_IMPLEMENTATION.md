---
lupopedia.headers:
  lupopedia.schema: documentation
  when_updated: "20260328000000"
  actor_name: "cursor"
  file_path_from_root: lupo-docs/doctrine/SMILIES_IMPLEMENTATION.md
  web_path: http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/SMILIES_IMPLEMENTATION.md
  last_modified_utc: "20260328"
  channel_id: 42
  thread_id: emoji-icons-implementation
  actor_id: 102
  agent_name_identity: "Cursor IDE Agent"
  artifact_type: documentation
  artifact_kind: implementation
  purpose: Documentation of smilies/emoji icon implementation in Lupopedia
  tags:
    - smilies
    - emoji
    - implementation
    - documentation
    - channel-42

lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  last_verified: "20260403113047"
  verified_by:
    identity_type: actor
    actor_id: 2
    name: "lilith"
  verified_via:
    type: "audit"
    script: "fix_doctrine_headers"
  next_action:
    - "Run: python lupo-scripts/apply_doctrine_prd_lineage.py --apply"

---

# Smilies (Emoji Icons) Implementation in Lupopedia

## Overview
Lupopedia supports smilies (emoji icons) for use in chat, content, and UI. This document describes the schema, code, and UI integration for smilies, and how they are managed and rendered.

## Database Table: `lupo_smilies`
- Stores all available smilies/emoji icons.
- Columns: `smiley_id`, `code`, `image_url`, `description`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`.
- Canonical schema in `install_new_lupopedia.sql` and JSON TOON.

## Features
- Smilies can be inserted into chat and content using their code (e.g., `:smile:`).
- UI renders the corresponding image/icon for each code.
- Admins can add, edit, or remove smilies from the database.
- Smilies are available in the chat input and content editor as a picker.

## Implementation Details
- PHP: Smilies are loaded from the `lupo_smilies` table and cached for performance.
- Rendering: Codes in content are replaced with `<img>` tags or Unicode emoji as appropriate.
- Admin UI: Management interface for smilies is planned for future versions.
- All smilies are soft-deletable (`is_deleted` flag).

## Channel Discussion
See channel 42, thread `emoji-icons-implementation` for ongoing discussion and updates on smilies/emoji support.

---

## Related Files
- `lupo_smilies` table: `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql`
- PHP code: `lupo-includes/modules/` (smilies loading/rendering)
- UI: Chat and content editor modules

---

## Next Steps
- Complete admin UI for smilies management
- Expand emoji picker in chat/content editors
- Document custom emoji upload process
- Gather feedback in channel 42, thread `emoji-icons-implementation`
