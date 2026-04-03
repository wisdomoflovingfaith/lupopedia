---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/doctrine/SESSION_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/docs/doctrine/SESSION_DOCTRINE"
  last_updated_utc: "20260307"
  system_version: "4.0.64"
  channel_id: 1
  actor_id: 1003
  actor_name: "cursor"
  delegation_chain: "antigravity:cursor:captain"
  artifact_type: "doctrine"
  artifact_kind: "canonical"
  purpose: "Core doctrine for session binding, identity layers, and ban enforcement logic (v4.0.64 update)."
  mood_rgb: "4169E1"
  traits: ["canonical", "session", "identity", "context", "v4.0.64"]
  tags: ["session", "doctrine", "identity", "context", "ban_enforcement"]
  lupo_agent: "antigravity"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/lupopedia_whoami_readme.md", type: "references", weight: 0.9 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 0.8 }

    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: implements
      weight: 1.0
      reason: "Doctrine PRD lineage; constitutional audit 20260403"

lupopedia.footer:
  version: "4.0.64"
  last_verified: "20260307"
  last_verified_by: "antigravity"
---
# Session Doctrine — Binding and Ban Enforcement (4.0.17)

**Status:** Permanent.  
**Audience:** Contributors, system stewards, and agents.  
**Canonical:** Single source of truth for session binding and persona-ban enforcement behavior.

---

## Session Binding

Lupopedia sessions are bound **only** to possession of the session cookie and its expiry time.

The following are **not** used to validate a session:

- IP address (including VPN exit nodes)
- X-Forwarded-For headers
- User-Agent string
- Device fingerprint
- Geographic location
- Session rotation (IDs are not regenerated on login or network change)

This means a session created on one network (e.g., VPN exit A) will remain valid when the same cookie is presented from any other network (VPN exit B, public IP, etc.). Multiple concurrent sessions per actor are allowed.

---

## Ban Enforcement (4.0.17)

Persona bans (e.g., actor 999 in `lupo_banned_actors`) are currently enforced **only** in ANUBIS orphan adoption logic.

Bans are **not** checked at:

- Router/bootstrap
- Session validation
- Channel-send endpoints

Therefore, a banned actor with a valid session cookie can still post messages. Full ban enforcement is planned for 4.0.18 ("Ban at Gate").

---

## 4.0.18 Recommendation (Not Implemented in 4.0.17)

- Add ban checks to channel-send endpoints (and optionally router/bootstrap).
- Keep session binding behavior unchanged unless explicitly decided otherwise.
- Make bans operational at the gate instead of symbolic (ANUBIS-only).

---

*End of Session Doctrine.*
