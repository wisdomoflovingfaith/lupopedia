---
# FLIP Header
wolfie.headers: session and ban enforcement doctrine.
file_path_from_root: docs/doctrine/SESSION_DOCTRINE.md
file.last_modified_system_version: "4.0.17"
file.last_modified_utc: "20260218000000"
X-Lupo-Channel: 42   # ANUBIS adoption channel (Auto-Fixed)
X-Lupo-Actor-ID: 2035
X-Lupo-Actor-Identity: "Lupopedia Audit Tool (Auto-Fixed)"
X-Lupo-File-Path: docs/doctrine/SESSION_DOCTRINE.md
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
