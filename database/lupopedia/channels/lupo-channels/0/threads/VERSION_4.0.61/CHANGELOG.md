---
lupopedia.headers:
  header_format_version: "4.1.8"
  path_from_lupopedia_root: database/lupopedia/channels/lupo-channels/0/threads/VERSION_4.0.61/CHANGELOG.md
  web_path: https://www.lupopedia.com/lupopedia/database/lupopedia/channels/lupo-channels/0/threads/VERSION_4.0.61/CHANGELOG.md
  status: null
  when_updated: '20260513033046'
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: null
  artifact_kind: null
  channel_key: null
  federation_node_id: null
  thread_key: null
  lupopedia.schema: documentation
  prd_cluster: null
  title: null
  summary: null
---

# Version 4.0.61 Changelog (Thread Copy)

Changelog entries for version 4.0.61. Full history: [CHANGELOG.md](../../../../../../../CHANGELOG.md) at project root.

**Thread path:** `{LUPO_CHANNELS_DIR}/0/threads/VERSION_4.0.61/` → `database/lupopedia/channels/channels/0/threads/VERSION_4.0.61/`

---

## [4.0.61] — Dual-identity help integration and version thread (2026-03-06)

**Theme:** Dual-identity whoami/context in CLI help; session-file-first context; version tracking; auth/actor context for Antigravity; user-friendly help; VERSION_4.0.61 thread (config path: LUPO_CHANNELS_DIR).

### Summary

- **CLI help:** Main `help`, subtopics `help whoami` / `help context`; HelpRenderer (menu, quick ref, topic help, context tips, exit codes, `switch` alias). New commands: `version`, `doctor`, `docs`, `auth`/`who`, `actor-context`.
- **Session file first:** ContextResolver uses `database/session.md` first, then DB and registry. context_source: session.md, session.md + registry, lupo_sessions, default.
- **Version tracking:** get_lupo_version(), is_version_at_least() in version.php; docs/version.md; $lupo_config['version'] in config.
- **Auth and actor context:** AuthService (getUserByAuthUserId, getUserByActorId, getUserByActorName); ActorService (getAuthUserIdForActor, getActorContext); AntigravityContext; agents/antigravity/context.php; CLI auth, actor-context; docs/auth.md.
- **Documentation:** docs/HELP.md, docs/CLI.md, docs/auth.md; thread at LUPO_CHANNELS_DIR/0/threads/VERSION_4.0.61/.

---

**End of 4.0.61 changelog thread copy.**
