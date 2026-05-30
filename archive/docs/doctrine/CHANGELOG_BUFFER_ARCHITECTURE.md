---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "docs/doctrine/CHANGELOG_BUFFER_ARCHITECTURE.md"
  web_path: "https://www.lupopedia.com/lupopedia/docs/doctrine/CHANGELOG_BUFFER_ARCHITECTURE.md"
  status: "active"
  when_updated: "20260419010000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "memory/development/canonical/1026/04/changelog-buffer-architecture-md.toon"
  atoms_toon: null
  transcript_jsonl: "0/development/changelog-buffer-architecture"
  artifact_type: documentation
  artifact_kind: guide
  channel_key: "development"
  federation_node_id: 0
  thread_id: ""
  content_id: null
  content_parent_id: null
  content_slug: "changelog-buffer-architecture"
  default_collection_id: null
  lupopedia.schema: documentation
  title: "CHANGELOG_BUFFER_ARCHITECTURE -- mandatory pending/archive JSON buffer protocol"
  summary: "Defines mandatory JSON buffer entries in changelog-pending and changelog-archive, with consolidation rules and merge logic."
---
# CHANGELOG_BUFFER_ARCHITECTURE.md

## 1. Purpose
The Changelog Buffer System provides a non-blocking, multi-agent way to record changes without causing git merge conflicts on a single central CHANGELOG.md file. Agents write individual JSON files; a consolidator process merges them periodically.

## 2. Directories
- Pending: changelog-pending/
  - Contains unmerged JSON entries.
- Archive: changelog-archive/
  - Contains processed JSON entries (moved here after consolidation).
- Version Changelogs: docs/versions/{version}/CHANGELOG.md
  - The target for consolidated entries.

## 3. JSON Schema (Required)
All files in changelog-pending/ must be valid JSON with these fields:
- timestamp: 14-digit UTC (YYYYMMDDHHIISS).
- agent_id: Identifier of the agent (e.g., cursor, gemini).
- channel: Target channel (e.g., development, captains_log).
- thread: Thread identifier or short slug.
- summary: Concise ASCII description of the work.
- files_changed: Array of repo-relative file paths.
- open_questions: Array of strings (questions/concerns).
- handoff_to: Target agent ID or null.
- related_toons: Array of repo-relative .toon file paths.

Example:
{
  "timestamp": "20260419002533",
  "agent_id": "cursor",
  "channel": "development",
  "thread": "captains-log-headers",
  "summary": "Updated headers for captain log entries.",
  "files_changed": ["content/federation_node/0/captains_log/30_file.md"],
  "open_questions": [],
  "handoff_to": null,
  "related_toons": ["memory/captains_log/canonical/1026/04/30.toon"]
}

## 4. Consolidation and Merge Rules
The consolidator (e.g., `scripts/consolidate_changelog_v412.py` or `scripts/consolidate_lupo_changelog_pending.py`) follows these rules:
1. **Sort by timestamp**: All pending files are processed in chronological order (oldest first).
2. **Temporal Merge (10-Minute Rule)**: Entries from the **SAME agent** and **SAME thread** occurring within **10 minutes** (600 seconds) of each other are merged into a single logical block.
   - Summaries are combined using a semicolon and space ("; ").
   - `files_changed` arrays are merged and deduplicated.
   - The timestamp of the *first* entry in the group is used for the header.
3. **Idempotency**: The consolidator checks the target `CHANGELOG.md` for a hidden merge marker `<!-- changelog-merged: {filename} -->` to avoid duplicate processing.
4. **OQ Extraction**: Any `open_questions` found in the buffer entries are extracted and appended to the version's `open_questions.md` ledger.
5. **Archive on Success**: Processed buffer files are moved to `changelog-archive/` only after successful write to the target `CHANGELOG.md`.

## 5. Conflict Detection
- **Inter-Agent Conflicts**: If two or more agents modify the same file within overlapping timestamps (determined by the `files_changed` lists), the consolidator flags the entry with a `[CONFLICT]` tag in the summary.
- **Sequential Processing**: Processing one version at a time prevents race conditions during consolidation.

## 6. Agent Protocol
- Agents MUST NOT write directly to any `CHANGELOG.md`.
- Agents MUST write one buffer entry per logical task/session to `changelog-pending/`.
- **JSON Preferred**: Use `.json` format for structured data.
- **Legacy Markdown**: `.md` fragments are supported but will be converted to JSON or processed by legacy scripts.
- Filename format: `{timestamp}_{agent_id}_{task_slug}.json`
- All text MUST be ASCII only. No emoji or special Unicode characters.
