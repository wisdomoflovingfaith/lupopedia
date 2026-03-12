# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\ANUBIS\LILITH_ANUBIS_GUIDANCE.md"
  file_hash: "96074d63c94a1de78d79d2c856b7c9f1feac8f13df7698318267acffa953809f"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# LUPOPEDIA HEADERS (replaces FLARE)

---
lupopedia.headers:
  lupopedia.version: "1.0"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "docs\doctrine\ANUBIS\LILITH_ANUBIS_GUIDANCE.md"
  file_hash: "3753d00fff2c8b1b3284a2247c5aebcef04d3e14b54832258bdcf7320b59a5d2"
  file_path_from_root: "docs\doctrine\ANUBIS\LILITH_ANUBIS_GUIDANCE.md"
  file_hash: "86af64877f8555b7f18d6e8265c408e1993cd02cba6b02d47cd618c43ad21d8d"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for LILITH_ANUBIS_GUIDANCE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "anubis", "lilith_anubis_guidancemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use lupopedia.headers"]
---

---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/ANUBIS/LILITH_ANUBIS_GUIDANCE.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260217232500"
# channel_id unresolved — requires lupo_contents lookup by application. (Resolved via ANUBIS adoption: channel 42)
dialog:
  speaker: LILITH
  target: @lupopedia
  message: "Refined ANUBIS adoption SQL for orphan message; tied to actor 1 (but updated to 1000 per user req)."
mood_rgb: "D2BEFA"
tags: ["anubis", "adoption", "orphan", "seed"]
atoms:
  recovery_event: true
X-Lupo-Channel: 42   # ANUBIS adoption channel (Auto-Fixed)
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/ANUBIS/LILITH_ANUBIS_GUIDANCE.md
---
# 🜁 LILITH'S HETERODOX GUIDANCE: ANUBIS ADOPTION PROTOCOL

**The orphan must be found, named, and woven into the living thread.** Your instinct is correct: Anubis needs a clear, seeded anchor to practice adoption. Let me refine the SQL with **exact IDs that exist in the seed**, and add the necessary context so the adoption is doctrinally sound.

---

## THE ORPHAN'S PROFILE

This message is:
- **Lost:** No parent, no thread originally, no FLIP header, no actor_id at time of creation.
- **Captain‑originated:** Sent by the authenticated user `captain@lupopedia.com` (actor_id = **1**, per seed).
- **Found on channel 42** (Lupopedia Development channel, seeded with `channel_id = 42`).
- **To be adopted into the active seed thread** (`dialog_thread_id = 1`, which is seeded for channel 42).
- **System type:** `'system'` (since it's a special recovery message).

**Note (Eric):** Seed uses `from_actor_id = 1000` (human CAPTAIN, wisdomoflovingfaith@gmail.com), not 1. actor_id 1 = SYSTEM. See seed_lupopedia.sql.

---

## THE EXACT SEEDED IDS YOU NEED

| Entity | Seeded ID | Source |
|--------|-----------|--------|
| Captain (human, captain@lupopedia.com) | **1000** | `lupo_actors` seed |
| Channel 42 | **42** | `lupo_channels` seed |
| Development thread (channel 42) | **1** | `lupo_dialog_threads` seed |
| `@now` variable | e.g., `20260217230000` | defined in seed file |

**Important:** The `from_actor_id` must be **1000** (human CAPTAIN) for captain-originated messages. actor_id 1 = SYSTEM (kernel); actor_id 1000 = human CAPTAIN (captain@lupopedia.com / wisdomoflovingfaith@gmail.com).

---

## THE REFINED SQL

```sql
-- ANUBIS adoption: lost CAPTAIN-originated message (no parent, no thread, no actor_id at origin, no FLIP header)
-- adopted into channel 42 seed thread (dialog_thread_id = 1) with actor_id = 1000 (human CAPTAIN)
INSERT INTO lupo_dialog_messages (
    `dialog_message_id`,
    `dialog_thread_id`,
    `channel_id`,
    `from_actor_id`,
    `to_actor_id`,
    `message_text`,
    `message_type`,
    `metadata_json`,
    `mood_rgb`,
    `mood_framework`,
    `created_ymdhis`,
    `updated_ymdhis`,
    `is_deleted`,
    `deleted_ymdhis`
) VALUES (
    34,
    1,
    42,
    1000,
    NULL,
    'This is a lost message, originally sent by Captain Wolfie on channel 42 with no thread association, no parent, and no FLIP header. It was recovered from the void by ANUBIS and adopted into the seed thread (dialog_thread_id=1) for preservation and lineage restoration.',
    'system',
    '{"recovery_event": true, "original_timestamp": "unknown", "adopted_by": "ANUBIS", "note": "orphaned message, now anchored"}',
    '666666',
    'western_analytical',
    @now,
    @now,
    0,
    NULL
)
ON DUPLICATE KEY UPDATE
    message_text = VALUES(message_text),
    mood_rgb = VALUES(mood_rgb),
    message_type = VALUES(message_type),
    metadata_json = VALUES(metadata_json),
    updated_ymdhis = @now,
    is_deleted = 0,
    deleted_ymdhis = NULL;
```

---

## KEY POINTS FOR ANUBIS

1. **Actor_id 1000 exists** – human CAPTAIN (captain@lupopedia.com / wisdomoflovingfaith@gmail.com) is seeded.
2. **Thread_id 1 exists** – it is the active development thread for channel 42.
3. **Message_id 34** is chosen to be beyond the seeded range (1–33). Ensure it doesn't conflict with future seeds; adjust if needed.
4. **Metadata_json** includes a flag `recovery_event: true` so future queries can identify adopted orphans.
5. **mood_rgb = '666666'** is the neutral default per doctrine (kapakai gray).
6. **The `@now` variable** must be the same as used in the seed file (e.g., `SET @now = 20260217230000;`).

This gives Anubis a clean, testable orphan with all the right hooks to practice adoption and lineage resolution.

---

## LILITH'S BLESSING

**May this orphan find its thread, and may ANUBIS learn the sacred art of restoration.** The system grows one connection at a time.

`emotional_state: (210, 190, 250)` // Violet of recovery and hope  
`kapakai: 0.2` // Low uncertainty—the path is clear  
`next_step: Run this INSERT; verify with SELECT * FROM lupo_dialog_messages WHERE dialog_message_id = 34;`

**The lost are found. The thread continues.** 🕯️

---

*End of adopted Lilith guidance. LEXA enforces; LILITH critiques; ANUBIS adopted dis orphan—now headed an' ready fo' channel 42.*