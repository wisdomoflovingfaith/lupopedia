---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260408133230"
  file_path_from_root: "lupo-docs/versions/4.0.96/status/SESSIONS_RESEARCH.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/versions/4.0.96/status/SESSIONS_RESEARCH.md"
  last_modified_utc: "20260408133230"
  federation_node_id: 0
  channel_id: 42
  actor_id: 116
  actor_name: "Claude Code"
  delegation_chain: "claude_code:root"
  artifact_type: research
  artifact_kind: status_report
  purpose: "Research on session identity system for multi-browser support (Q3 concurrent sessions)"
  tags:
    - research
    - sessions
    - identity
    - multi-browser
    - 4.0.96
lupopedia.footer:
  last_verified: "20260408133230"
  verified_by:
    identity_type: actor
    actor_id: 116
    agent_name_identity: "Claude Code"
  orchestrator: "claude_code:root"
---

# Session Identity System Research

## Executive Summary

The current system **already correctly separates Chrome and Edge sessions** on
the same computer — browser cookies are browser-scoped by the OS, so each browser
stores a different random `session_id` cookie and gets a different DB row. This
works without any IP or user-agent logic. However, `ip_hash` and `ua_hash` are
stored as **audit columns only** — they are never validated on session load, never
used as a lookup key, and are hashed without a salt. The recommended
`session_identity_hash` column (Class C + UA formula) would add a **secondary
identity signal** useful for anomaly detection and same-network visitor
correlation, but it is not required for multi-browser correctness. Three gaps
need addressing: unsalted hashes, full IP (not Class C), and
`untrustedFingerprintSources()` ignoring Cloudflare/proxy headers that
`CloudflareRequestHandler` already resolves.

---

## Files Read

| File | Role |
|------|------|
| `app/auth/Session.php` | Canonical session class (Model A, DB-backed) |
| `lupo-includes/classes/AuthSessionManager.php` | Actor mapping + legacy helpers |
| `lupo-includes/classes/SessionManager.php` | Idle timeout management |
| `lupo-includes/classes/CloudflareRequestHandler.php` | Proxy IP extraction |
| `lupo-database/lupopedia/mysql/install/install_new_lupopedia.sql` | Schema (lupo_sessions table) |
| `craftysyntax-reference/functions.php` | Legacy Class C + UA identity (reference only) |
| `lupo-database/lupopedia/content/lupo-app/Services/CraftySyntax/LegacySessionIdentity.php` | Legacy session bridge |

---

## 1. Current Session Identity

**The session lookup key is a single random token — not a composite identity.**

```php
// app/auth/Session.php  createEmbedSession() lines 302–309
$session_id = bin2hex(random_bytes(32));   // 64-char hex; capped at 128 chars in DB
if (strlen($session_id) > 128) {
    $session_id = substr($session_id, 0, 128);
}
$fp      = self::untrustedFingerprintSources();
$hashes  = self::hashFingerprint($fp['ip'], $fp['user_agent']);
$ip_hash = $hashes['ip_hash'];   // stored, never validated
$ua_hash = $hashes['ua_hash'];   // stored, never validated
```

**Session load (`loadById`) queries by `session_id` alone:**

```php
// app/auth/Session.php  loadById() line 192
$row = $db->fetchRow(
    "SELECT session_id, actor_id, … FROM $t WHERE session_id = :sid LIMIT 1",
    array('sid' => $session_id)
);
```

**`ip_hash` and `ua_hash` are loaded into the object but never compared to the
current request's fingerprint.** They exist for audit and forensics only.

### fingerprint extraction

```php
// app/auth/Session.php  untrustedFingerprintSources() lines 140–147
private static function untrustedFingerprintSources()
{
    $srv = self::untrustedServerArray();
    return array(
        'ip'         => isset($srv['REMOTE_ADDR']) ? (string) $srv['REMOTE_ADDR'] : '',
        'user_agent' => isset($srv['HTTP_USER_AGENT']) ? (string) $srv['HTTP_USER_AGENT'] : '',
    );
}
```

**Gap:** reads `REMOTE_ADDR` directly — does NOT check `LUPO_CLIENT_IP` set by
`CloudflareRequestHandler`. Behind Cloudflare, `REMOTE_ADDR` is the Cloudflare
edge node IP, not the visitor's real IP. See §5.

### hashing

```php
// app/auth/Session.php  hashFingerprint() lines 154–166
private static function hashFingerprint($ip, $user_agent)
{
    if (function_exists('hash') && … in_array('sha256', hash_algos())) {
        return array(
            'ip_hash' => hash('sha256', $ip),        // no salt
            'ua_hash' => hash('sha256', $user_agent), // no salt
        );
    }
    return array(
        'ip_hash' => md5($ip),
        'ua_hash' => md5($user_agent),
    );
}
```

**Two gaps:** no salt; full IP (not Class C).

---

## 2. Class C IP Support

**Current system: hashes the full IP. No Class C extraction exists in Session.php.**

Full IP → SHA256. Example: `192.168.1.100` is hashed as-is.

The legacy system (CraftySyntax) used Class C for matching:

```php
// craftysyntax-reference/functions.php  get_identitystring() lines 27–36
function get_identitystring($ipaddress, $sessionname = "SESSIONID") {
    $hostip_array   = explode(".", $ipaddress);
    $identitystring = "";
    if (!empty($hostip_array[0])) { $identitystring .= $hostip_array[0] . "."; }
    if (!empty($hostip_array[1])) { $identitystring .= $hostip_array[1] . "."; }
    if (!empty($hostip_array[2])) { $identitystring .= $hostip_array[2]; }
    $identitystring .= "-" . $sessionname;
    return $identitystring;  // e.g. "192.168.1-PHPSESSID"
}
```

And Class C matching in session lookup:

```php
// craftysyntax-reference/functions.php  lines 838–841
$hostip_array = explode(".", get_ipaddress());
$classc       = "$hostip_array[0].$hostip_array[1].$hostip_array[2]";
$sqlquery = "SELECT sessionid FROM livehelp_users
             WHERE sessionid = '…' AND ipaddress LIKE '" . $classc . "%' LIMIT 1";
```

**Recommendation:** Add Class C extraction as a utility function and use it when
computing `ip_hash` and any future `session_identity_hash`. This reduces IP
precision intentionally — a visitor whose DHCP lease changes from `.100` to `.101`
stays on the same Class C and their session remains valid; a visitor from a
completely different /24 (different subnet) gets a different hash. See §6.

### IPv6 handling

Neither Session.php nor the legacy code handles IPv6. For IPv6 addresses, the
equivalent of "Class C" is the first 64 bits (the network prefix). Recommended
approach: detect IPv6 via `strpos($ip, ':') !== false`, then keep only the first
four colon-separated groups (e.g. `2001:db8:85a3:0` from `2001:db8:85a3:0::8a2e`).

---

## 3. User Agent Extraction

**UA is extracted and hashed. It IS part of `ip_hash`/`ua_hash` storage but NOT
part of the session key.**

```php
// Session.php untrustedFingerprintSources() line 145
'user_agent' => isset($srv['HTTP_USER_AGENT']) ? (string) $srv['HTTP_USER_AGENT'] : '',
```

Full raw UA string → SHA256(UA) → stored in `ua_hash`. Never validated again after
session creation.

**What this means for multi-browser:** Chrome and Edge send different
`User-Agent` headers → different `ua_hash` values stored. But since `ua_hash` is
never re-checked on load, a session cookie stolen and replayed from a different
browser would still validate. The `ua_hash` column is currently a forensic record,
not an active guard.

**Bots / curl / CLI:** No special handling. An empty or missing UA becomes
`hash('sha256', '')` — a valid constant hash. All curl requests from the same
IP get the same `ua_hash`. This is acceptable for audit purposes.

---

## 4. Multi-Browser Test

**Q: If Chrome and Edge are open on the same computer with the same IP, do they
get different sessions right now?**

**Answer: Yes — correctly, by default, via OS cookie isolation.**

Browsers do not share cookies across browser vendors on the same OS. Chrome
stores cookies in its own profile; Edge in a separate Edge profile. When each
browser makes its first request:

1. No `session_id` cookie exists in that browser.
2. The server generates a new cryptographically random `session_id`.
3. The server sends `Set-Cookie: session_id=<random>` in the response.
4. Each browser stores its own cookie → each has a different `session_id` → each
   hits a different row in `lupo_sessions`.

The `ua_hash` values also differ (Chrome UA ≠ Edge UA), which is a useful audit
signal, but it is not the mechanism producing the separation.

**Same browser, two tabs:** Both tabs share the same cookie jar. Tab 1 and Tab 2
send the same `session_id` cookie → same session row → same actor. This is the
correct "same browser = same session" behaviour.

**No code change is needed** to achieve the desired multi-browser separation. It
already works. The requested `session_identity_hash` formula would be an
*additional* signal for anomaly detection, not a prerequisite for correctness.

---

## 5. Privacy

**Current: SHA256(full IP) — privacy risk.**

The IPv4 address space is only ~4 billion entries. An attacker with the `ip_hash`
value can enumerate all possible IPs and find a match in seconds.
`hash('sha256', '192.168.1.100')` is trivially reversible via rainbow table.

**Gaps:**

| Issue | Current behaviour | Recommended |
|-------|------------------|-------------|
| Full IP hashed | SHA256(192.168.1.100) | SHA256(192.168.1 + salt) — Class C only |
| No salt | SHA256(ip) directly | SHA256(ip + server_secret) |
| MD5 fallback | Used on hosts without sha256 | Remove fallback; sha256 is available everywhere since PHP 5.1.2 |
| Cloudflare blind spot | Reads raw REMOTE_ADDR | Should read LUPO_CLIENT_IP if set |

---

## 6. Schema Gaps

### Current `lupo_sessions` table

```sql
CREATE TABLE {{prefix}}sessions (
  session_id           varchar(128) NOT NULL,   -- cryptographic random, the ONLY key
  actor_id             bigint       NOT NULL,
  actor_name           varchar(64)  DEFAULT NULL,
  federation_node_id   bigint       NOT NULL DEFAULT 0,
  ip_hash              varchar(128) DEFAULT NULL,  -- SHA256(full IP), no salt
  ua_hash              varchar(255) DEFAULT NULL,  -- SHA256(full UA), no salt
  csrf_token           varchar(128) DEFAULT NULL,
  last_activity_ymdhis bigint       NOT NULL,
  created_ymdhis       bigint       NOT NULL,
  updated_ymdhis       bigint       NOT NULL,
  last_seen_ymdhis     bigint       DEFAULT NULL,
  expires_ymdhis       bigint       DEFAULT NULL,
  name_key             varchar(100) DEFAULT NULL,
  is_named             tinyint      NOT NULL DEFAULT 0,
  metadata             json         DEFAULT NULL,
  is_active            tinyint      NOT NULL DEFAULT 1,
  is_expired           tinyint      NOT NULL DEFAULT 0,
  is_revoked           tinyint      NOT NULL DEFAULT 0,
  is_deleted           tinyint      NOT NULL DEFAULT 0,
  security_level       varchar(64)  DEFAULT NULL,
  system_context       varchar(64)  DEFAULT NULL,
  status               varchar(32)  DEFAULT NULL,
  PRIMARY KEY (session_id)
);
```

**What is missing for the desired `session_identity_hash` formula:**

| Gap | Detail |
|-----|--------|
| No `session_identity_hash` column | The proposed SHA256(classC + UA + salt) is not stored |
| No `ip_class_c` column | Stored hash uses full IP; Class C form not preserved |
| `ip_hash` / `ua_hash` unsalted | Vulnerable to rainbow-table reversal |
| No `ip_version` column | IPv4 and IPv6 hashes cannot be distinguished |
| `createEmbedSession()` uses `gmdate()` | Doctrine violation D-003; should use `timestamp_ymdhis::now()` |

---

## 7. Recommendations

### R1. Session identity formula (additive — new column, no breaking change)

Add a `session_identity_hash` column to `lupo_sessions` and populate it on
session creation. The `session_id` remains the authoritative lookup key;
`session_identity_hash` is for anomaly detection and same-network visitor
correlation.

```php
// Proposed helper — add to Session.php or a new SessionIdentity utility class

/**
 * Extract Class C prefix from an IP address.
 * IPv4: first three octets ("192.168.1" from "192.168.1.100").
 * IPv6: first four groups ("2001:db8:85a3:0" from "2001:db8:85a3:0::8a2e").
 * Falls back to the full IP if parsing fails.
 *
 * @param string $ip
 * @return string
 */
private static function classC(string $ip): string
{
    if (strpos($ip, ':') !== false) {
        // IPv6 — keep first 64 bits (four colon-separated groups).
        $groups = explode(':', $ip);
        $prefix = array_slice($groups, 0, 4);
        return implode(':', $prefix);
    }
    $parts = explode('.', $ip);
    if (count($parts) >= 3) {
        return $parts[0] . '.' . $parts[1] . '.' . $parts[2];
    }
    return $ip; // IPv6 or malformed — use as-is
}

/**
 * Compute the composite session identity hash.
 *
 * $salt should be a per-installation secret stored in config (e.g. LUPO_SESSION_SALT).
 * Falls back to a constant if not defined — operators MUST set this in production.
 *
 * @param string $ip         Resolved client IP (use LUPO_CLIENT_IP if set)
 * @param string $userAgent  Raw HTTP_USER_AGENT
 * @param string $salt       Per-installation secret
 * @return string SHA256 hex string
 */
public static function computeIdentityHash(string $ip, string $userAgent, string $salt): string
{
    $classC = self::classC($ip);
    return hash('sha256', $classC . '|' . $userAgent . '|' . $salt);
}
```

**Usage in `createEmbedSession()` and the main `create()` path:**

```php
$salt      = defined('LUPO_SESSION_SALT') ? LUPO_SESSION_SALT : 'CHANGE_ME_IN_CONFIG';
$clientIp  = defined('LUPO_CLIENT_IP') ? LUPO_CLIENT_IP : $fp['ip'];  // prefer Cloudflare-resolved
$idHash    = self::computeIdentityHash($clientIp, $fp['user_agent'], $salt);

$data['session_identity_hash'] = $idHash;
```

### R2. Fix `ip_hash` and `ua_hash` to use salt

```php
// Replace hashFingerprint() with a salted version:
private static function hashFingerprint(string $ip, string $user_agent): array
{
    $salt = defined('LUPO_SESSION_SALT') ? LUPO_SESSION_SALT : '';
    $classC = self::classC($ip);  // Class C, not full IP
    return array(
        'ip_hash' => hash('sha256', $classC . '|' . $salt),
        'ua_hash' => hash('sha256', $user_agent . '|' . $salt),
    );
}
```

### R3. Fix Cloudflare blind spot in `untrustedFingerprintSources()`

```php
private static function untrustedFingerprintSources(): array
{
    $srv = self::untrustedServerArray();
    // Prefer the Cloudflare-resolved IP set by CloudflareRequestHandler::process().
    $ip = defined('LUPO_CLIENT_IP') && LUPO_CLIENT_IP !== ''
        ? LUPO_CLIENT_IP
        : (isset($srv['REMOTE_ADDR']) ? (string) $srv['REMOTE_ADDR'] : '');
    return array(
        'ip'         => $ip,
        'user_agent' => isset($srv['HTTP_USER_AGENT']) ? (string) $srv['HTTP_USER_AGENT'] : '',
    );
}
```

### R4. Fix doctrine violation in `createEmbedSession()` (D-003)

```php
// Current (wrong — gmdate is forbidden for persistence paths):
$now = (int) gmdate('YmdHis');

// Correct:
$now = (int) timestamp_ymdhis::now();
```

### R5. Add `session_identity_hash` column (additive migration)

```sql
ALTER TABLE {{prefix}}sessions
  ADD COLUMN session_identity_hash varchar(128) DEFAULT NULL,
  ADD INDEX {{prefix}}sessions_idx_identity_hash (session_identity_hash);
```

Or add it to `install_new_lupopedia.sql` for fresh installs.

---

## 8. Open Questions

1. **Should `ua_hash` / `session_identity_hash` ever be validated on `loadById()`?**
   If yes, a session cookie presented from a different browser (e.g. stolen cookie)
   would be rejected. The UX trade-off: a user who upgrades their browser mid-session
   would be logged out. Opt-in via config flag `LUPO_SESSION_VALIDATE_UA`.

2. **What is `LUPO_SESSION_SALT`?** Does it already exist in `lupopedia-config.php`?
   If not, it must be added with a generated random value per install. The install
   wizard should generate it; the Softaculous installer must populate it.

3. **`ip_hash` column width** — SHA256 produces 64 hex characters. The column is
   `varchar(128)` which is fine. `session_identity_hash` can also be `varchar(64)`.

4. **Should `ua_hash` normalise the UA string before hashing?**
   Browser minor version bumps (e.g. Chrome 120.0.1 → 120.0.2) change the raw UA
   and would produce a different `ua_hash` even for the same browser. Normalising
   to major version only (e.g. strip patch version) would reduce churn but complicates
   the hash function. Leave raw for now; revisit if session churn is observed.

5. **`X-Forwarded-For` in non-Cloudflare deployments** — `CloudflareRequestHandler`
   handles Cloudflare. What handles generic `X-Forwarded-For` (nginx proxy, Docker)?
   `untrustedFingerprintSources()` currently trusts only `REMOTE_ADDR`. A config flag
   `LUPO_TRUST_PROXY_HEADERS` would allow the resolver to check `X-Real-IP` and
   `X-Forwarded-For` when not behind Cloudflare.
