---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: "lupo-database/lupopedia/channels/lupo-channels/42/threads/UPGRADE_TEST_CRAFTY_TO_4_0_56.md"
  system_version: "4.0.56"
  channel_id: 42
  actor_id: 1003
  last_modified_utc: "20260303"
  artifact_type: "thread"
  purpose: "Thread: test upgrade from Crafty Syntax 3.7.5 to Lupopedia v4.0.56"
  traits: ["upgrade", "test", "cursor"]
  tags: ["upgrade", "crafty", "4.0.56", "cursor"]
  lupo_agent: "cursor"
---

# Upgrade Test Thread — Crafty 3.7.5 → Lupopedia v4.0.56

**Channel:** 42  
**Thread focus:** Install Crafty base, run upgrade to v4.0.56, verify database, channels, seed actors (system:0, Wolfie:1, ANUBIS:19), faucets, and web admin.

**Reference:** Full research and directive-style prompt: `docs/status/RESEARCH_ANUBIS_WOLFIE_FLARE_LUPOPEDIA.md` (Section 5).

---

## Directive (execute in order)

1. **Install Crafty 3.7.5 base** — Load `lupo-database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql`; verify livehelp_* tables; document in `docs/status/CRAFTY_3.7.5_INSTALL_LOG.md`.
2. **Upgrade to v4.0.56** — Run install.php (upgrade mode); log in `docs/status/UPGRADE_4.0.56_LOG.md`.
3. **Verify** — Database (~179 tables, key tables, seeded registry); agents/actors (0, 1, 19, actor dirs, faucet loader channel=42 actor=19); channels (0, 42, tasks); faucets (setup, validate_faucets).
4. **Web admin test** — Crafty legacy + Lupopedia channels/actors/agents; log in `docs/status/WEB_ADMIN_TEST_4.0.56.md`.
5. **Report** — Update `docs/status/UPGRADE_REPORT_4.0.56.md` with summary, verification results, issues, actor 1003.
6. **Commit** — Message: `v4.0.56 Upgrade Test — Installed Crafty 3.7.5, upgraded to 4.0.56, verified DB/agents/channels/admin; added logs/reports`. Do not push unless directed.
7. **Confirm** — Reply in Channel 42: `Cursor: v4.0.56 upgrade from Crafty 3.7.5 tested. DB, agents, channels, and web admin verified. Report created.`

---

*Thread created 2026-03-03; Cursor (1003).*
