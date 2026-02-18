---
# FLIP Header (alias: Wolfie Header, CROP Header, FLIPPING Header)
wolfie.headers: explicit architecture with structured clarity for every file.
file_path_from_root: docs/doctrine/ANUBIS/ANUBIS_IMPLEMENTATION_SUMMARY.md
file.last_modified_system_version: "4.0.16"
file.last_modified_utc: "20260217153700"
# channel_id unresolved — requires lupo_contents lookup by application.
dialog:
  speaker: ARA_GROK
  target: @lupopedia
  message: "Adopted orphaned ANUBIS summary per ANUBIS_ORPHAN_RULES.md: resolution order applied, adopted to channel 42/thread 1/actor 3 (WOLFIE)."
mood_rgb: "00FF00"
tags: ["anubis", "orphan-adoption", "implementation-summary"]
atoms:
  commit_hash: "b91afdc"
---
# ANUBIS Implementation Summary (Adopted from Orphan)

**Status:** Permanent (adopted via ANUBIS).  
**Audience:** All AI agents, LEXA (security), LILITH (critique).  
**Purpose:** Canonical summary of ANUBIS doctrine, programs, seed, changelog, commit—now with FLIP header to no mo' orphan.

---

## 1. New doctrine files

### docs/doctrine/ANUBIS/ANUBIS_OVERVIEW.md

- Role: custodial intelligence for dialogs, lineage, orphans, redirects.
- Responsibilities: orphan detection, parent resolution, adoption into seed, redirect mapping, soft-delete and timestamp rules, no guessing, FLIP/FLP alignment.
- Inputs/outputs: dialog text, optional actor_id/channel_id/thread_id → classification, resolution, adoption plan.

### docs/doctrine/ANUBIS/ANUBIS_ORPHAN_RULES.md

- Orphan: missing or invalid channel_id, dialog_thread_id, or actor_id.
- Resolution order: 1) channel_id, 2) dialog_thread_id, 3) actor_id; if still unresolved → adopt to channel 42, thread 1, from_actor_id 3 (WOLFIE).
- Adoption: explicit dialog_message_id, @now, message_type='system', idempotent INSERT ... ON DUPLICATE KEY UPDATE, update message_count.

### docs/doctrine/ANUBIS/ANUBIS_PROGRAM_SPEC.md

- Python: tools/anubis_orphan_scanner.py — scanner, resolver, adoption planner; parameterized SQL; no schema inference.
- PHP: lupo-includes/classes/ANUBIS_Resolver.php — classifyOrphan, resolveParent, adoptIntoSeed; PHP 5.3, PDO_DB only.
- Tables: lupo_dialog_messages, lupo_dialog_threads, lupo_dialog_channels, lupo_actor_channels, lupo_actors, lupo_edges (reference). No schema changes.

---

## 2. New program files

### tools/anubis_orphan_scanner.py

- Input: dialog text; optional --channel, --thread, --actor.
- Output: classification (orphan/resolved), resolution (channel_id, dialog_thread_id, from_actor_id), adoption_plan (dialog_message_id, channel_id, dialog_thread_id, from_actor_id, message_type).
- Resolution: resolve_channel_id, resolve_thread_id, resolve_actor_id, next_dialog_message_id; all queries parameterized.
- Default adoption: channel 42, thread 1, actor 3 (WOLFIE).
- Can run with or without DB (uses defaults when DB is unavailable).

### lupo-includes/classes/ANUBIS_Resolver.php

- PHP 5.3: array() only, no ??, no typed properties/return types.
- Constructor: ($db, $prefix).
- classifyOrphan($text, $channel_id, $thread_id, $actor_id) → is_orphan, channel_id, dialog_thread_id, from_actor_id.
- resolveParent($text, ...) → channel_id, dialog_thread_id, from_actor_id (resolved or 42, 1, 3).
- adoptIntoSeed($text, $actorId, $threadId, $channelId) → INSERT into lupo_dialog_messages (explicit id, ON DUPLICATE KEY UPDATE), then UPDATE lupo_dialog_channels.message_count. Uses PDO_DB query() and bound params only.

---

## 3. Seed SQL

The orphan is already in seed from the previous ANUBIS adoption commit (dialog_message_id 32, channel 42, thread 1, from_actor_id 3, message_type system, message_count 32). No further seed changes were made in this session; database/migrations/seed_lupopedia.sql had no uncommitted changes and was not modified again.

---

## 4. CHANGELOG (4.0.14)

Added under Lupopedia 4.0.14:

- Completed ANUBIS doctrine and ANUBIS program. Doctrine: docs/doctrine/ANUBIS/ (ANUBIS_OVERVIEW.md, ANUBIS_ORPHAN_RULES.md, ANUBIS_PROGRAM_SPEC.md). Program: tools/anubis_orphan_scanner.py (Python orphan scanner, resolver, adoption planner); lupo-includes/classes/ANUBIS_Resolver.php (PHP 5.3: classifyOrphan, resolveParent, adoptIntoSeed). Adopted orphaned dialog message into channel 42 seed thread via ANUBIS.

---

## 5. Staged file list

- docs/doctrine/ANUBIS/ANUBIS_OVERVIEW.md
- docs/doctrine/ANUBIS/ANUBIS_ORPHAN_RULES.md
- docs/doctrine/ANUBIS/ANUBIS_PROGRAM_SPEC.md
- tools/anubis_orphan_scanner.py
- lupo-includes/classes/ANUBIS_Resolver.php
- CHANGELOG.md

database/migrations/seed_lupopedia.sql was included in the git add command but had no local changes, so it did not appear in the commit (seed already contained message 32 from the earlier ANUBIS adoption commit).

---

## 6. Commit hash

- Short: b91afdc
- Full: b91afdc46ce932a04f35b4f78c135bf2d6e564a3

---

## 7. Push

- Pushed to origin main: 622ac00..b91afdc main -> main.

---

**Note:** The scanner is named anubis_orphan_scanner.py (ANUBIS spelling). The correct path is tools/anubis_orphan_scanner.py.

---

*End of adopted ANUBIS summary. LEXA enforces; LILITH critiques. ANUBIS adopted dis orphan—now headed an' ready fo' channel 42.*
