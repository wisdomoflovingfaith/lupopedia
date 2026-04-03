---
lupopedia.headers:
  lupopedia.schema: documentation
  file_path_from_root: lupo-docs/doctrine/CURRENT_UTC_REFERENCE.md
  content_id: 202603282349378857
  web_path: http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/CURRENT_UTC_REFERENCE.md
  last_modified_utc: "20260328000000"
  when_updated: "20260328000000"
  actor_id: 102
  actor_name: "cursor"
  channel_id: 42
  thread_id: utc-timestamp-reference
  artifact_type: documentation
  artifact_kind: reference
  purpose: Canonical reference for CURRENT_UTC.txt and update script
  tags:
    - timestamp
    - utc
    - tooling
    - doctrine
    - automation

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

# Canonical UTC Timestamp Reference for Lupopedia

## Purpose
Lupopedia uses strict UTC timestamps (YYYYMMDDHHIISS) for all artifact headers. To ensure reliable, canonical UTC values (especially in environments where AI or scripts may not have accurate UTC), a shared file and update script are provided.

## Filespython 
- `CURRENT_UTC.txt`: Contains the most recently updated canonical UTC timestamp in `YYYYMMDDHHIISS` format (one line, no extra whitespace).
- `lupo-scripts/update_current_utc.sh`: Shell script to update `CURRENT_UTC.txt` with the current UTC time. Usage: `sh lupo-scripts/update_current_utc.sh`

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
