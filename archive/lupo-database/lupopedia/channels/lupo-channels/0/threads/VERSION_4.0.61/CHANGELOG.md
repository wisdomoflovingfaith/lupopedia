---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-database/lupopedia/channels/lupo-channels/0/threads/VERSION_4.0.61/CHANGELOG.md"
  questions_toon: null
  system_version: "4.0.61"
  channel_id: 0
  purpose: "Changelog entries for version 4.0.61"
  traits: ["changelog", "v4.0.61"]
  tags: ["changelog", "4.0.61"]
---

# Version 4.0.61 Changelog (Thread Copy)

Changelog entries for version 4.0.61. Full history: [CHANGELOG.md](../../../../../../../CHANGELOG.md) at project root.

**Thread path:** `{LUPO_CHANNELS_DIR}/0/threads/VERSION_4.0.61/` → `lupo-database/lupopedia/channels/lupo-channels/0/threads/VERSION_4.0.61/`

---

## [4.0.61] — Dual-identity help integration and version thread (2026-03-06)

**Theme:** Dual-identity whoami/context in CLI help; session-file-first context; version tracking; auth/actor context for Antigravity; user-friendly help; VERSION_4.0.61 thread (config path: LUPO_CHANNELS_DIR).

### Summary

- **CLI help:** Main `help`, subtopics `help whoami` / `help context`; HelpRenderer (menu, quick ref, topic help, context tips, exit codes, `switch` alias). New commands: `version`, `doctor`, `docs`, `auth`/`who`, `actor-context`.
- **Session file first:** ContextResolver uses `lupo-database/session.md` first, then DB and registry. context_source: session.md, session.md + registry, lupo_sessions, default.
- **Version tracking:** get_lupo_version(), is_version_at_least() in version.php; lupo-docs/version.md; $lupo_config['version'] in config.
- **Auth and actor context:** AuthService (getUserByAuthUserId, getUserByActorId, getUserByActorName); ActorService (getAuthUserIdForActor, getActorContext); AntigravityContext; lupo-agents/antigravity/context.php; CLI auth, actor-context; lupo-docs/auth.md.
- **Documentation:** lupo-docs/HELP.md, lupo-docs/CLI.md, lupo-docs/auth.md; thread at LUPO_CHANNELS_DIR/0/threads/VERSION_4.0.61/.

---

**End of 4.0.61 changelog thread copy.**
