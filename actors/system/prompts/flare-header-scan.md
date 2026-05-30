---
prompt_id: lupopedia-headers-scan
actor_id: 0
purpose: "System agent task: scan for files lacking LUPOPEDIA HEADERS and queue for Anubis"
last_modified_utc: "20260312"
---

# LUPOPEDIA HEADERS Scan (System Agent — Actor 0)

As the system agent (actor 0), your task is to scan for files lacking LUPOPEDIA HEADERS. LUPOPEDIA HEADERS are required metadata (e.g., YAML frontmatter with lupopedia.headers, lupopedia.footer, etc.) at the top of Markdown files or database entries to ensure consistency, versioning, and integration in Lupopedia. FLARE is the legacy name; use lupopedia.* block names.

## Scan locations

1. **Database files or entries** in `LUPO_DATABASE_DIR` (`database/`).
2. **Markdown (`.md`) files** in `LUPO_CONTENT_DIR` (`content/`), including node-specific subdirectories such as `content/federation_node_id/0` for the system root node of lupopedia.com. Recursively check all subdirectories under `LUPO_CONTENT_DIR`.
3. **Markdown (`.md`) files** in `LUPO_ACTORS_DIR` (`actors/`), including all actor subdirectories (e.g., `0/`, `1/`, `19/`, etc.). Recursively check all actor subdirectories.

## Actions for items without valid LUPOPEDIA HEADERS

For any file or database entry that does not have valid LUPOPEDIA HEADERS (lupopedia.headers or equivalent):

1. **Identify** it by path or ID.
2. **Add it to a processing queue** in this actor's directory:
   - **Queue file:** `actors/0/anubis-queue.json` (create it if it does not exist).
   - **Format:** JSON array of objects. Each object has:
     - `path`: full path to the file or identifier for the DB entry (e.g. `full/path/to/file.md` or table:id).
     - `type`: `md` (Markdown file) or `db` (database entry).
     - `status`: `pending` (or similar as defined by the Anubis process).

   Example structure:

   ```json
   [
     {"path": "content/channels/42/thread.md", "type": "md", "status": "pending"},
     {"path": "lupo_contents:12345", "type": "db", "status": "pending"}
   ]
   ```

   If the queue file does not exist, initialize it as an empty JSON array `[]`.

3. **Log** any findings or errors to the system log in **`actors/0/logs/`** (e.g. a dated log file or `flare-scan.log`). Ensure the logs directory exists; create it if missing.

## Scheduling

Run this scan periodically (e.g. on system startup or via a cron-like trigger in the app). If no queue exists, initialize it before appending new items. Deduplicate by path/id when adding to the queue so the same file is not enqueued repeatedly.

## Downstream use

This queue will be consumed by the Anubis process (actor 19 or a dedicated tool) to automatically add or update LUPOPEDIA HEADERS for each queued item.
