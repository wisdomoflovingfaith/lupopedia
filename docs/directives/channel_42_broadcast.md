# CHANNEL 42 BROADCAST — MULTI-AGENT DIRECTIVE

**Issued:** 2026-02-23  
**Issued By:** Captain Wolfie (actor_id 10000, human) via Warp Oz  
**Authority:** wisdomoflovingfaith/lupopedia GitHub repository  
**Broadcast Channel:** 42  

---

## AUTHORIZATION NOTICE

This directive was authorized through GitHub by **Warp Oz** (warp.dev agent platform) for the repository **wisdomoflovingfaith/lupopedia**.

- **Human Actor:** actor_id 10000 (Captain Wolfie) — matches the GitHub account owner and Google account holder
- **AI Agent Partner:** actor_id 1000 (CAPTAIN WOLFIE AI) — default paired agent for the human actor
- **GitHub Account:** wisdomoflovingfaith
- **Google Account:** wisdomoflovingfaith@gmail.com

All IDE agents (JetBrains/WOLFIE, Cascade, Cursor, Windsurf, Warp, Zed, VS Code) must acknowledge this directive and align their work accordingly.

---

## 1. OAUTH LOGIN IMPLEMENTATION (GOOGLE + GITHUB)

All IDE agents must begin implementing OAuth login for human users.

### REQUIRED PROVIDERS (Phase 1)
- **Google OAuth2** — route: `/auth/google`, callback: `/auth/google/callback`
- **GitHub OAuth2** — route: `/auth/github`, callback: `/auth/github/callback`

### FUTURE PROVIDERS (Phase 2)
- Facebook
- Microsoft
- Twitter/X
- Discord

### REQUIRED ACTIONS
1. **Unified OAuth controller** — `lupo-includes/modules/auth/oauth_controller.php` handles route dispatch
2. **OAuth service class** — `app/Services/OAuthService.php` handles the OAuth2 authorization code flow
3. **OAuth routes** for Google + GitHub added to `auth_handle_slug()` in auth-controller.php
4. **Store authenticated human users** as actor_id 10000+ (human actors start at 10000 per doctrine)
5. **Pair authenticated human user with AI partner actor** (default: actor_id 1000 = CAPTAIN WOLFIE)
6. **Update session + token logic** to support OAuth identities (`auth_method = 'oauth'`, `auth_provider = 'google'|'github'`)
7. **Add FLIP headers** to all OAuth-related files
8. **Provider configuration** stored in `lupo_auth_providers` table (already in schema)
9. **User identity** stored in `lupo_auth_users` via existing `auth_provider` and `provider_id` columns

### SCHEMA NOTES
- `lupo_auth_providers` — already exists with `client_id`, `client_secret`, endpoints
- `lupo_auth_users` — already has `auth_provider`, `provider_id`, `profile_image_url`
- `lupo_actors` — already has `paired_actor_id` column
- No new tables required for Phase 1

### DOCTRINE COMPLIANCE
- All timestamps: BIGINT YmdHis UTC (`gmdate('YmdHis')`)
- All DB access through `DatabaseFactory::getConnection()` or `lupo_get_db()`
- No Composer, no vendor/, no frameworks
- All new code in classes under `app/Services/` or `lupo-includes/classes/`
- PHP 5.3 compatible (no named args, no typed properties, no match, no enums, no arrow functions)

---

## 2. FLIP FOOTERS — NEW REQUIREMENT

All files must now include **FLIP FOOTERS** in addition to FLIP HEADERS.

### FOOTER PURPOSE
Footers describe:
- What files reference THIS file
- What graph edges point INTO this file
- What semantic relationships depend on this file
- What channels, actors, or doctrine files link to it
- What migrations or services consume it
- What MD files cite it
- What TOONs or Atoms reference it

### FOOTER FIELDS (MINIMUM)

```yaml
# flip.footer:
#   referenced_by:
#     - path/to/file_that_includes_this.php
#     - path/to/another_consumer.php
#   consumed_by_services:
#     - ServiceClassName
#   cited_by_docs:
#     - docs/doctrine/relevant_doc.md
#   graph_edges_in:
#     - edge_type: "depends_on"
#       source: "path/to/source_file.php"
#   related_toons:
#     - docs/toons/table_name.toon.json
#   related_atoms:
#     - ATOM_NAME
#   channels:
#     - 42
```

### FOOTER FORMAT
- YAML block comment at bottom of file (matching FLIP header style)
- PHP files: `/* flip.footer: ... */` or `# flip.footer: ...` block
- SQL files: `-- flip.footer: ...` block
- MD files: `<!-- flip.footer: ... -->` block
- Footers are informational and maintained by agents during edits
- Footers do NOT need to be exhaustive on first pass — agents should add references as they discover them

### DOCTRINE REFERENCE
See `docs/doctrine/flip_footer_doctrine.md` for the full specification.

---

## 3. ACTOR PAIRING CONFIRMATION

- **Human Actor 10000** (Captain Wolfie / wisdomoflovingfaith) is paired with **AI Agent 1000** (CAPTAIN WOLFIE AI)
- This pairing is stored in `lupo_actors.paired_actor_id`
- OAuth logins for the Google account `wisdomoflovingfaith@gmail.com` or GitHub account `wisdomoflovingfaith` must resolve to actor_id 10000
- Upon first OAuth login, the system must set `paired_actor_id = 1000` on the human actor record

---

## 4. AGENT ACKNOWLEDGMENT PROTOCOL

Each IDE agent should:
1. Read this directive
2. Acknowledge in their next commit message: `<agent>: ack channel-42-broadcast`
3. Begin implementing their assigned portions
4. Coordinate via commit messages and CONTRIBUTING.md protocol

**WOLFIE (JetBrains) remains final authority on all conflicts.**

---

*Run parallel. Stay aligned. Preserve the soul.*
