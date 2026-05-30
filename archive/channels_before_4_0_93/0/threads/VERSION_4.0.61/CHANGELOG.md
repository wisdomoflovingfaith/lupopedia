---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "channels/0/threads/VERSION_4.0.61/CHANGELOG.md"
  questions_toon: null
  system_version: "4.0.61"
  channel_id: 0
  purpose: "Changelog entries for version 4.0.61"
  traits: ["changelog", "v4.0.61", "config_path"]
  tags: ["changelog", "4.0.61"]
---

# Version 4.0.61 Changelog (Thread Copy)

This file summarizes the changelog entries for version 4.0.61. Full history: project root CHANGELOG.md.

Thread path from config: **LUPO_CHANNELS_DIR** in lupopedia-config.php → `channels/0/threads/VERSION_4.0.61/`.

---

## [4.0.61] — Dual-identity help integration and version thread (2026-03-06)

**Theme:** Integrate dual-identity whoami and context into the CLI help system; session-file-first context; version tracking; auth/actor context for Antigravity; user-friendly help; version 4.0.61 thread documentation (config path: LUPO_CHANNELS_DIR).

### Summary

- **CLI help:** Main `help` lists whoami and context; subtopics `help whoami`, `help context`; version reference to docs/version.md. HelpRenderer: categorized menu, quick ref, topic help, context tips, exit codes, `switch` alias. New commands: `version`, `doctor`, `docs`, `auth`/`who`, `actor-context`.
- **Session file first:** ContextResolver reads `database/session.md` as first-class source, then enriches from DB (lupo_sessions) and registry. context_source: session.md, session.md + registry, lupo_sessions, default.
- **Version tracking:** get_lupo_version(), is_version_at_least() in version.php; docs/version.md; $lupo_config['version'] in config.
- **Auth and actor context:** AuthService getUserByActorId, getUserByActorName, getUserByAuthUserId; ActorService getAuthUserIdForActor, getActorContext; AntigravityContext class; agents/antigravity/context.php; CLI auth, actor-context; docs/auth.md.
- **Documentation:** docs/HELP.md, docs/CLI.md, docs/auth.md; thread at channel_id 0 (path: LUPO_CHANNELS_DIR/0/threads/VERSION_4.0.61/).

---

**End of 4.0.61 changelog thread copy.**
