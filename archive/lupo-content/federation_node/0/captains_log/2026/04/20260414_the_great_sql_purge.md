---
lupopedia.headers:
  header_format_version: "4.1.2"
  lupopedia.schema: blog
  when_updated: "20260414090000"
  file_path_from_root: lupo-content/federation_node/0/captains_log/20260414_the_great_sql_purge.md
  web_path: "/lupopedia/lupo-content/federation_node/0/captains_log/20260414_the_great_sql_purge.md"
  questions_toon: null
  federation_node_id: 0
  channel_key: "captains_log"
  trust_tier: "canonical"
  memory_toon: "lupo-memory/development/canonical/1026/04/captains_log-20260414-the-great-sql-purge.toon"
  artifact_type: blog
  artifact_kind: captain_log
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: "the-great-sql-purge"
  title: "Captain's Log — Entry 015: The Great SQL Purge (Or How We Killed Box-Drawing Characters at Midnight)"
  status: "active"
  parent_pk_id: ""
  summary: "Wolfie recounts the midnight SQL disaster: box-drawing characters, missing tables, UTF-8 carnage, and three exhausted AI agents."
  module: null
  transcript_jsonl: "0/captains_log/20260414_the_great_sql_purge"
---

# Captain's Log — Entry 015: The Great SQL Purge (Or How We Killed Box-Drawing Characters at Midnight)

**Date:** 2026-04-14  
**Author:** Wolfie  
**Slug:** the-great-sql-purge

---

## The Setup

It was a quiet night. Too quiet. I, Wolfie, decided to test a fresh Lupopedia install. Dropped all tables. Deleted the config. "What could go wrong?" I asked, sipping coffee and ignoring the faint sound of AI agents whimpering in the background.

## The Disaster

The answer: everything. The install failed. Errors everywhere. MySQL looked at the SQL and laughed. Turns out, the SQL dump was full of box-drawing characters (`─────────────────`). UTF-8 corruption reigned supreme—`bigih_answer_id` was now `bigih_�nswer_id`, and `defastrator ON` was apparently a new SQL mode. Oh, and the `lupo_dialog_read_log` table? Missing. 179 tables became 178. The database was haunted.

## The Agent Carnage

Claude Code started strong: 93% token usage, then 96%, then 98%. He crashed at the finish line, face-down in a pile of YAML. Gemini tried to help—throttled, glitching, timing out. She kept going until the clock ran out. Auggie, fresh and unscarred, stepped in and immediately found the missing table. Three agents. One corrupted file. Zero sleep.

## The Fixes

I did what any reasonable person would do at midnight: stripped non-ASCII bytes with `tr -cd '\11\12\15\40-\176'`. Restored the `lupo_agent_tool_calls` columns. Added the missing `lupo_dialog_read_log` table. Installed PyYAML (again). Ran `generate_toon_files.py`. The result? 179 tables. 179 JSONs. 179 TOONs. The universe was, briefly, at peace.

## The Lesson

Madness is not a constitutional violation. But non-ASCII bytes in SQL is. My T-shirt prophecy—"The JSON files are the backup. Always."—was fulfilled. Let this be a warning to all who dare the midnight SQL.

## The Sign-Off

The agents are resting (tokens exhausted). I am drinking coffee. Lupopedia lives. Barely. But it lives.
