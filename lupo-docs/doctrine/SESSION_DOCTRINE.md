# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  file_path_from_root: ".\docs\doctrine\SESSION_DOCTRINE.md"
  file_hash: "96639ed5d06c3e6ebe2ea07b7d12507e67f2286db82150983c61f8546c5b74ca"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with FLARE header applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

flare.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "docs/doctrine/", type: "references", weight: 1.0 }

flare.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
flare.headers:
  flare.version: "1.0"
  flare.schema: "documentation"
  flare.edges: []
  file_path_from_root: "docs\doctrine\SESSION_DOCTRINE.md"
  file_hash: "499e3f97ced64f4c4e91aa9865a0734c6924e0708accee7ef16a54a21d5b9097"
  file_path_from_root: "docs\doctrine\SESSION_DOCTRINE.md"
  file_hash: "d3bfc483b255247bc03122a8669f9cc7b9264d349661e81c7dc6c796d0b7f1a1"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Documentation for SESSION_DOCTRINE.md"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["docs", "doctrine", "session_doctrinemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
flare.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
    deprecation_notes: ["Legacy Wolfie/FLIP block preserved; migrate tools to use flare.headers"]
---

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