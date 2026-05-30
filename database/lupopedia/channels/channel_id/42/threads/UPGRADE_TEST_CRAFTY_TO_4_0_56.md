---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: database/lupopedia/channels/channel_id/42/threads/UPGRADE_TEST_CRAFTY_TO_4_0_56.md
  web_path: https://www.lupopedia.com/lupopedia/database/lupopedia/channels/channel_id/42/threads/UPGRADE_TEST_CRAFTY_TO_4_0_56.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: thread
  artifact_kind: null
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: documentation
  prd_cluster: null
  title: null
  summary: null
---

# Upgrade Test Thread — Crafty 3.7.5 → Lupopedia v4.0.56

**Channel:** 42  
**Thread focus:** Install Crafty base, run upgrade to v4.0.56, verify database, channels, seed actors (system:0, Wolfie:1, ANUBIS:19), faucets, and web admin.

**Reference:** Full research and directive-style prompt: `docs/status/RESEARCH_ANUBIS_WOLFIE_FLARE_LUPOPEDIA.md` (Section 5).

---

## Directive (execute in order)

1. **Install Crafty 3.7.5 base** — Load `database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql`; verify livehelp_* tables; document in `docs/status/CRAFTY_3.7.5_INSTALL_LOG.md`.
2. **Upgrade to v4.0.56** — Run install.php (upgrade mode); log in `docs/status/UPGRADE_4.0.56_LOG.md`.
3. **Verify** — Database (~179 tables, key tables, seeded registry); agents/actors (0, 1, 19, actor dirs, faucet loader channel=42 actor=19); channels (0, 42, tasks); faucets (setup, validate_faucets).
4. **Web admin test** — Crafty legacy + Lupopedia channels/actors/agents; log in `docs/status/WEB_ADMIN_TEST_4.0.56.md`.
5. **Report** — Update `docs/status/UPGRADE_REPORT_4.0.56.md` with summary, verification results, issues, actor 1003.
6. **Commit** — Message: `v4.0.56 Upgrade Test — Installed Crafty 3.7.5, upgraded to 4.0.56, verified DB/agents/channels/admin; added logs/reports`. Do not push unless directed.
7. **Confirm** — Reply in Channel 42: `Cursor: v4.0.56 upgrade from Crafty 3.7.5 tested. DB, agents, channels, and web admin verified. Report created.`

---

*Thread created 2026-03-03; Cursor (1003).*
