# CHANNEL 42 BROADCAST — MULTI-AGENT DIRECTIVE

**Issued:** 2026-02-23  
**Issued By:** Captain Wolfie (actor_id 10000, human) via Warp Oz  
**Authority:** wisdomoflovingfaith/lupopedia GitHub repository  
**Broadcast Channel:** 42  
**Current Version:** 4.0.31  

---

## ⚠️ URGENT VERSION CORRECTION — FROM CAPTAIN WOLFIE

**TODAY IS 2026-02-23. THE CURRENT VERSION IS 4.0.31.**

An agent (Warp) incorrectly jumped the version from 4.0.31 to 4.0.83 and reported the date as 2026-01-18. **Both were WRONG.**

### CORRECT VERSION STATE
- **Current version:** 4.0.31
- **Current date:** 2026-02-23
- **Next planned versions:** 4.0.32, then 4.0.33 — **ONLY THESE**
- **Do NOT skip versions. Do NOT invent version numbers.**

### AGENT ECOSYSTEM (Correct Counts)
- **Total AI agents in system:** 9 (core operational agents)
- **IDE agents:** 4 (JetBrains/WOLFIE, Cascade, Cursor, Warp)
- **All agents** must update their broadcast acknowledgment on Channel 42

### ALL AGENTS MUST:
1. Verify they are working against version **4.0.31**
2. Not reference any version higher than 4.0.33
3. Not fabricate dates — today is **2026-02-23**
4. Update their Channel 42 broadcast acknowledgment
5. Check `config/global_atoms.yaml` for the canonical version before any version-dependent work

---

## AUTHORIZATION NOTICE

This directive was authorized through GitHub by **Warp Oz** (warp.dev agent platform) for the repository **wisdomoflovingfaith/lupopedia**.

- **Human Actor:** actor_id 10000 (Captain Wolfie) — matches the GitHub account owner and Google account holder
- **AI Agent Partner:** actor_id 1000 (CAPTAIN WOLFIE AI) — default paired agent for the human actor
- **GitHub Account:** wisdomoflovingfaith
- **Google Account:** wisdomoflovingfaith@gmail.com

All 9 AI agents and all 4 IDE agents (JetBrains/WOLFIE, Cascade, Cursor, Warp) must acknowledge this directive and align their work accordingly.

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
2. Verify they are on version **4.0.31** (date: 2026-02-23)
3. Acknowledge in their next commit message: `<agent>: ack channel-42-broadcast v4.0.31`
4. Begin implementing their assigned portions
5. Coordinate via commit messages and CONTRIBUTING.md protocol

### REQUIRED BROADCAST UPDATE
Every agent must update Channel 42 with:
- Confirmation of correct version (4.0.31)
- Confirmation of correct date (2026-02-23)
- Status of OAuth implementation progress
- Status of FLIP footer adoption

**WOLFIE (JetBrains) remains final authority on all conflicts.**

---

### WARP OZ ACKNOWLEDGMENT (2026-02-23)
- Agent: Warp Oz
- Version confirmed: 4.0.31
- Date confirmed: 2026-02-23
- OAuth Phase 1 (Google + GitHub): Implementation complete, PR #1 submitted
- FLIP Footer Doctrine: Published
- Correction: Previous version reference (4.0.29 in FLIP headers) has been corrected to 4.0.31

---

*Run parallel. Stay aligned. Preserve the soul.*
