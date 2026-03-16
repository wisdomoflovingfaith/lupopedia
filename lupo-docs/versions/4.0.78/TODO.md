---
lupopedia.headers:
  lupopedia.version: "4.0.78"
  lupopedia.schema: "documentation"
  system_version: "4.0.78"
  file_path_from_root: "lupo-docs/versions/4.0.78/TODO.md"
  web_path: "[TODO](http://www.lupopedia.com/versions/4.0.78/TODO)"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 102
  artifact_type: "todo"
  artifact_kind: "version_todo"
  purpose: "Concrete task list for 4.0.78 (table documentation, header cleanup, optional automation)"
  tags: ["todo", "4.0.78", "table_documentation", "headers"]

lupopedia.footer:
  version: "4.0.78"
  last_verified: "20260316"
  last_verified_by: "cursor"
  next_action:
    - "Use TABLE_DOCUMENTATION_4_0_77_STOP_LINE.md for pattern and priorities"
---
# file: Version 4.0.78 TODO — web_path: http://www.lupopedia.com/versions/4.0.78/TODO

# Version 4.0.78 — TODO List

## Status

- **State:** Open (post–4.0.77 release)
- **Theme:** Table documentation continuation, header/version cleanup, optional automation
- **Source:** All items carried forward from 4.0.77; see `lupo-docs/status/TABLE_DOCUMENTATION_4_0_77_STOP_LINE.md`

---

## A. Table documentation (Priority 1)

1. **lupo_channels.md**
   - [ ] Refresh to 4.0.78 LUPOPEDIA_HEADERS; ensure "Where This Table Is Used" is present and accurate; align with install SQL/TOON.

2. **lupo_actors.md**
   - [ ] Refresh to 4.0.78 LUPOPEDIA_HEADERS; ensure "Where This Table Is Used" is present and accurate; align with install SQL/TOON.

---

## B. Table documentation (Priority 2)

3. **lupo_actor_apps.md**
   - [ ] Apply Zencoder pattern: 4.0.78 headers, Table Overview, Where This Table Is Used, key columns, doctrine notes.

4. **lupo_channel_departments.md**
   - [ ] Same as above.

5. **lupo_edge_type_definitions.md**
   - [ ] Same as above.

---

## C. Table documentation (Priority 3)

6. **lupo_analytics_visits.md**
   - [ ] Apply Zencoder pattern.

7. **lupo_audit_log.md**
   - [ ] Apply Zencoder pattern.

8. **lupo_system_logs.md**
   - [ ] Apply Zencoder pattern.

---

## D. Full 161-table modernization and header cleanup

9. **Remaining table docs**
   - [ ] Continue table-doc improvements across the 161-table inventory as capacity allows. Follow priority order in TABLE_DOCUMENTATION_4_0_77_STOP_LINE.md.

10. **Mass header version cleanup**
    - [ ] Plan or execute batch update of 80+ table docs with outdated headers (4.0.73 or earlier) to 4.0.78 where appropriate. Prefer doing this when materially improving a doc.

---

## E. Optional

11. **Markdown-from-TOON automation**
    - [ ] If desired: design/implement tool to generate or update table markdown from TOON/install SQL (structure only).

12. **Repo-wide doc/schema validation**
    - [ ] If desired: run or document validation that table docs align with current schema and list mismatches.

---

*Update CHANGELOG.md and lupo-docs/version.md as 4.0.78 work progresses.*
