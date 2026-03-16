---
lupopedia.headers:
  lupopedia.version: "4.0.79"
  lupopedia.schema: "documentation"
  system_version: "4.0.79"
  file_path_from_root: "lupo-docs/versions/4.0.79/TODO.md"
  web_path: "[TODO](http://www.lupopedia.com/versions/4.0.79/TODO)"
  last_modified_utc: "20260316"
  channel_id: 42
  actor_id: 102
  artifact_type: "todo"
  artifact_kind: "version_todo"
  purpose: "Concrete task list for 4.0.79 (remaining Top 50 table documentation, bounded cleanup)"
  tags: ["todo", "4.0.79", "table_documentation", "top_50"]

lupopedia.footer:
  version: "4.0.79"
  last_verified: "20260316"
  last_verified_by: "cursor"
  next_action:
    - "Work remaining Top 50 only; use review_of_cursor_cleanup_and_top_50_table_plan.md as scope authority"
---
# file: Version 4.0.79 TODO — web_path: http://www.lupopedia.com/versions/4.0.79/TODO

# Version 4.0.79 — TODO List

## Status

- **State:** Open (post–4.0.78 release)
- **Theme:** Remaining Top 50 operational table documentation; bounded header/namespace cleanup
- **Source:** [review_of_cursor_cleanup_and_top_50_table_plan.md](lupo-docs/status/review_of_cursor_cleanup_and_top_50_table_plan.md). Pattern: [TABLE_DOCUMENTATION_4_0_77_STOP_LINE.md](lupo-docs/status/TABLE_DOCUMENTATION_4_0_77_STOP_LINE.md). **Completed 4.0.78 work (25 table docs) is closed; do not list here.**

---

## A. Remaining Top 50 — Auth

1. **lupo_auth_providers.md** — [ ] Update to 4.0.79 LUPOPEDIA_HEADERS; Table Overview; Where This Table Is Used; columns from install SQL; namespace auth.
2. **lupo_auth_audit_log.md** — [ ] Same pattern (auth).
3. **lupo_banned_actors.md** — [ ] Same pattern (auth).
4. **lupo_bans_log.md** — [ ] Same pattern (auth).

---

## B. Remaining Top 50 — Content

5. **lupo_content_versions.md** — [ ] Update to 4.0.79 LUPOPEDIA_HEADERS; Table Overview; Where This Table Is Used; columns; namespace content (if table exists in install).
6. **lupo_content_revisions.md** — [ ] Same (content).
7. **lupo_content_tags.md** — [ ] Same (content).
8. **lupo_content_collections.md** — [ ] Same (content) — or equivalent from install SQL.

---

## C. Remaining Top 50 — Analytics

9. **lupo_unified_log.md** — [ ] Update to 4.0.79 LUPOPEDIA_HEADERS; Table Overview; Where This Table Is Used; schema-source note if not in install; namespace analytics.
10. **lupo_analytics_campaign_vars.md** — [ ] Same (analytics).
11. **lupo_analytics_events.md** — [ ] Same (analytics).

---

## D. Remaining Top 50 — Core / agent

12. **lupo_agents.md** — [ ] Update to 4.0.79 LUPOPEDIA_HEADERS; Table Overview; Where This Table Is Used; columns from install SQL; namespace core.
13. **lupo_actor_channels.md** — [ ] Same (core/channels).
14. **Remaining Top 50 (38–50)** — [ ] Add table docs from install SQL that round out the Top 50 by system criticality; same Zencoder pattern.

---

## E. Bounded cleanup

15. **Header version (Top 50 scope)** — [ ] Use header version report to update remaining Top 50 table docs to 4.0.79 where still needed; no mass-edit of full corpus.
16. **TABLE_INDEX.md — missing LUPOPEDIA_HEADERS** — [ ] Add minimal valid LUPOPEDIA_HEADERS block (only doc missing headers per 4.0.78 report).
17. **Namespace (Top 50)** — [ ] Ensure remaining Top 50 docs have valid namespace; use namespace audit for targeting.
18. **Duplicate/FLARE cleanup** — [ ] Only for active Top 50 or high-priority table docs; do not expand to full corpus.

---

## F. Optional

19. **Markdown-from-TOON automation** — [ ] If desired: design/implement tool to generate or update table markdown from TOON/install SQL (structure only).
20. **Repo-wide doc/schema validation** — [ ] If desired: run or document validation that table docs align with current schema and list mismatches.

---

*Update CHANGELOG.md and lupo-docs/version.md as 4.0.79 work progresses.*
