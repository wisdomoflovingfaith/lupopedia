---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: docs/doctrine/current_utc_reference.md
  web_path: https://www.lupopedia.com/lupopedia/docs/doctrine/current_utc_reference.md
  status: ''
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: reference
  channel_key: null
  federation_node_id: null
  thread_key: utc-timestamp-reference
  lupopedia.schema: documentation
  prd_cluster: null
  title: ''
  summary: ''
---
# Canonical UTC Timestamp Reference for Lupopedia

## Purpose
Lupopedia uses strict UTC timestamps (YYYYMMDDHHIISS) for all artifact headers. To ensure reliable, canonical UTC values (especially in environments where AI or scripts may not have accurate UTC), a shared file and update script are provided.

## Filespython 
- `CURRENT_UTC.txt`: Contains the most recently updated canonical UTC timestamp in `YYYYMMDDHHIISS` format (one line, no extra whitespace).
- `scripts/update_current_utc.sh`: Shell script to update `CURRENT_UTC.txt` with the current UTC time. Usage: `sh scripts/update_current_utc.sh`

## Usage
- Run the update script before editing or importing artifacts to ensure `CURRENT_UTC.txt` is current.
- Reference the value in `CURRENT_UTC.txt` when setting `when_updated`, `last_modified_utc`, or other UTC fields in headers.
- This ensures all contributors and automation use a single, canonical UTC value.

## Example
```
$ cat CURRENT_UTC.txt
20260328153045
```

## Integration
- Editors, scripts, and agents should read from `CURRENT_UTC.txt` when a canonical UTC timestamp is needed.
- The update script can be run manually or scheduled (e.g., via cron or Windows Task Scheduler).

## Rationale
- Prevents drift and ambiguity in UTC values across tools and contributors.
- Ensures compliance with Lupopedia doctrine for timestamp fields.

---

See also: [LUPOPEDIA_HEADERS doctrine](LUPOPEDIA_HEADERS/README.md)
