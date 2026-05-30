---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/doctrine/SESSION_DOCTRINE.md"
  web_path: "http://www.lupopedia.com/docs/doctrine/SESSION_DOCTRINE"
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: doctrine
  artifact_kind: canonical
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: documentation
  title: ""
  summary: ""
---
# Session Doctrine — Binding and Ban Enforcement

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

 

## 4.0.18 Recommendation (Not Implemented in 4.0.17)

- Add ban checks to channel-send endpoints (and optionally router/bootstrap).
- Keep session binding behavior unchanged unless explicitly decided otherwise.
- Make bans operational at the gate instead of symbolic (ANUBIS-only).

---

*End of Session Doctrine.*

# PRD AMENDMENT: GPS-Enhanced Session Identity (Future)

## Document to Amend

`lupo-docs/doctrine/SESSION_DOCTRINE.md`

## Amendment Status

| Version | Status |
|---------|--------|
| 4.0.96 | ⏸️ NOT IMPLEMENTED (future) |
| 4.1.0+ | 📝 PLANNED |

**This is a forward-looking amendment. Do not implement in 4.0.96.**

---

## The Problem GPS Solves

Mobile sessions break when:
- IP changes (cellular tower handoff)
- VPN activates/deactivates
- User roams internationally
- Airplane mode toggles

**Current session identity:** `Class C IP + user_id + User Agent + Salt`

**CRITICAL — base hash exclusions:** `actor_id`, `auth_user_id`, and any other post-login identity fields MUST NOT be included in the base session hash. Pre-login/anonymous visitors do not yet have those values; the hash must be stable before and after login to identify the browser/device fingerprint. If `user_id` is unknown at hash time, use the deterministic placeholder `unknown`.

**Problem:** IP is unstable on mobile. User gets logged out when crossing a cell tower boundary.

**Solution:** Optional GPS coordinates as an additional identity factor.

---

## Amendment: Two-Tier Session Identity

### Tier 1: Visitor (Anonymous) — No Change

```
identity_hash = SHA256(ClassC_IP + '|' + user_id_or_unknown + '|' + Filtered_UA + '|' + SALT)
```

Note: `actor_id` and `auth_user_id` are NOT part of this hash. `user_id` is `unknown` for pre-login visitors.

- GPS never collected
- No consent needed
- Session lifetime: 45 minutes

### Tier 2: Actor (Logged In) — GPS Optional

**Without GPS consent (default):**
```
identity_hash = SHA256(ClassC_IP + '|' + user_id_or_unknown + '|' + Filtered_UA + '|' + SALT)
```

**With GPS consent (opt-in):**
```
identity_hash = SHA256(GPS_Coarse + '|' + ClassC_IP + '|' + user_id_or_unknown + '|' + Filtered_UA + '|' + SALT)
```

- GPS is **additional** factor, not replacement
- Falls back to IP if GPS unavailable
- User must explicitly opt in

---

## Database Schema Changes (Future)

### Current `lupo_sessions` Table

```sql
CREATE TABLE lupo_sessions (
    session_id VARCHAR(128) PRIMARY KEY,
    actor_id BIGINT NOT NULL,
    ip_hash VARCHAR(128),
    ua_hash VARCHAR(255),
    session_identity_hash VARCHAR(128),
    -- other columns...
);
```

### Future Columns to Add

```sql
ALTER TABLE lupo_sessions ADD COLUMN gps_lat_hash VARCHAR(128) DEFAULT NULL;
ALTER TABLE lupo_sessions ADD COLUMN gps_lon_hash VARCHAR(128) DEFAULT NULL;
ALTER TABLE lupo_sessions ADD COLUMN gps_consent_granted TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE lupo_sessions ADD COLUMN gps_consent_timestamp BIGINT DEFAULT NULL;
ALTER TABLE lupo_sessions ADD COLUMN gps_accuracy_hash VARCHAR(128) DEFAULT NULL;
```

### Column Purpose

| Column | Type | Purpose |
|--------|------|---------|
| `gps_lat_hash` | VARCHAR(128) | SHA256(rounded_lat + salt) |
| `gps_lon_hash` | VARCHAR(128) | SHA256(rounded_lon + salt) |
| `gps_consent_granted` | TINYINT(1) | 0 = no consent, 1 = consented |
| `gps_consent_timestamp` | BIGINT | When consent was granted (packed UTC) |
| `gps_accuracy_hash` | VARCHAR(128) | SHA256(accuracy_rounded + salt) for debugging |

**Note:** GPS coordinates are rounded to 2 decimal places (~1km) before hashing. Exact coordinates are never stored.

---

## GPS Consent Flow (Future)

### Browser Side (JavaScript)

```javascript
// Only for authenticated actors
if (userIsLoggedIn && !gpsConsentGranted) {
    const result = await navigator.permissions.query({ name: 'geolocation' });
    if (result.state === 'prompt') {
        showConsentDialog();
    }
}

async function requestGpsConsent() {
    try {
        const position = await navigator.geolocation.getCurrentPosition(
            (pos) => {
                // Send to server with consent flag
                fetch('/api/session/gps-consent', {
                    method: 'POST',
                    body: JSON.stringify({
                        consent: true,
                        lat: pos.coords.latitude,
                        lon: pos.coords.longitude,
                        accuracy: pos.coords.accuracy
                    })
                });
            },
            (err) => {
                // User denied or error
                fetch('/api/session/gps-consent', {
                    method: 'POST',
                    body: JSON.stringify({ consent: false })
                });
            }
        );
    } catch (e) {
        console.log('GPS not supported');
    }
}
```

### Server Side (PHP)

```php
// Future method in Session.php
public static function grantGpsConsent($db, $session_id, $lat, $lon, $accuracy)
{
    // Round to ~1km precision (2 decimal places)
    $rounded_lat = round($lat, 2);
    $rounded_lon = round($lon, 2);
    $rounded_accuracy = round($accuracy / 1000, 1); // meters to km
    
    $salt = self::sessionSalt();
    
    $data = [
        'gps_lat_hash' => hash('sha256', $rounded_lat . '|' . $salt),
        'gps_lon_hash' => hash('sha256', $rounded_lon . '|' . $salt),
        'gps_accuracy_hash' => hash('sha256', $rounded_accuracy . '|' . $salt),
        'gps_consent_granted' => 1,
        'gps_consent_timestamp' => self::nowYmdhis(),
        'updated_ymdhis' => self::nowYmdhis()
    ];
    
    $db->update('lupo_sessions', $data, 'session_id = :sid', ['sid' => $session_id]);
    
    // Recompute session_identity_hash with GPS
    $fp = self::untrustedFingerprintSources();
    $gps_string = $rounded_lat . '|' . $rounded_lon;
    $new_hash = self::computeIdentityHashWithGps($fp['ip'], $fp['user_agent'], $gps_string);
    
    $db->update('lupo_sessions', 
        ['session_identity_hash' => $new_hash], 
        'session_id = :sid', 
        ['sid' => $session_id]
    );
}
```

---

## Privacy & Compliance

### GDPR/CCPA Requirements

| Requirement | Implementation |
|-------------|----------------|
| Explicit consent | Browser geolocation prompt + server record |
| Right to withdraw | User can revoke in settings |
| Data minimization | Round to 1km, hash before storage |
| Purpose limitation | Only for session continuity, not tracking |
| Data deletion | Revoking consent deletes GPS hashes |

### User Settings UI (Future)

```
Settings > Privacy > Session Identity

☐ Use GPS to keep me signed in on mobile (recommended)
  Current status: [Not granted / Granted on YYYY-MM-DD]

[Grant Permission]  [Revoke Permission]

Note: GPS coordinates are rounded to 1km and hashed.
Your exact location is never stored.
```

---

## Session Identity Formula (With GPS)

### Without GPS (Default)
```
identity_hash = SHA256(
    ClassC_IP . '|' .
    user_id_or_unknown . '|' .
    Filtered_UA . '|' .
    SALT
)
```

Note: `actor_id` and `auth_user_id` MUST NOT appear in this formula. Use `user_id` (or `unknown` if pre-login).

### With GPS Consent
```
identity_hash = SHA256(
    Rounded_Lat . '|' .
    Rounded_Lon . '|' .
    ClassC_IP . '|' .
    user_id_or_unknown . '|' .
    Filtered_UA . '|' .
    SALT
)
```

**Why GPS is additional, not replacement:**
- GPS may be unavailable (indoors, airplane mode)
- User may revoke consent
- IP provides fallback continuity

---

## Implementation Timeline

| Phase | Version | Status |
|-------|---------|--------|
| Session system (IP + UA) | 4.0.96 | ✅ COMPLETED |
| GPS columns added to schema | 4.1.0 | 📝 PLANNED |
| GPS consent UI (browser) | 4.1.0 | 📝 PLANNED |
| GPS consent API endpoint | 4.1.0 | 📝 PLANNED |
| GPS-enhanced identity_hash | 4.1.0 | 📝 PLANNED |
| User settings UI | 4.1.0 | 📝 PLANNED |
| Privacy documentation | 4.1.0 | 📝 PLANNED |

---

## Constitutional Status

| Document | Status |
|----------|--------|
| This amendment | 📝 DRAFT (not yet approved) |
| Session Doctrine | ✅ APPROVED (without GPS) |
| GPS enhancement | 🔜 FUTURE (requires LILITH audit) |

**This amendment is informational only. Do not implement until 4.1.0+.**

---

## Summary for Agents

| Agent | Action |
|-------|--------|
| Cascade | Read and understand for future planning |
| Antigravity | Note the two-tier identity approach |
| Cursor | Do NOT implement in 4.0.96 |
| VS Code | File as future task for 4.1.0 |

**GPS session enhancement is OPT-IN, DEFAULT OFF, and FUTURE.**

**Current session system (4.0.96) is complete without GPS.**



### Amendment to Append

```markdown
---
## Amendment Y: GPS Mock Detection + IP Geolocation Fallback

**Status:** 📝 PLANNED (not implemented in 4.0.96)
**Target Version:** 4.1.0+
**Depends On:** Amendment X (GPS-Enhanced Session Identity)

### Problem

GPS can be mocked/spoofed by:
- Android mock location apps (detectable via `Location.isMock()`)
- iOS third-party spoofing tools (undetectable)
- Browser dev tools (undetectable)
- Rooted/jailbroken devices (undetectable)

When GPS is mocked, the client sends fake coordinates. The session system needs a fallback to verify or override the mocked location.

### Solution

**Two-part approach:**

1. **Detect mock location** when possible (Android only)
2. **Fallback to reverse IP geolocation** for cross-check and when mock is detected

### Mock Detection by Platform

| Platform | Detection | Method |
|----------|-----------|--------|
| Android (official) | ✅ Yes | `Location.isMock()` |
| Android (rooted) | ❌ No | Cannot trust OS |
| iOS (Xcode sim) | ✅ Yes | `isSimulatedBySoftware` |
| iOS (third-party) | ❌ No | No detection available |
| Web Geolocation API | ❌ No | No detection available |

**Rule:** If mock is detected (Android), reduce GPS trust level to LOW and prefer IP geolocation.

### Reverse IP Geolocation (Fallback)

Use MaxMind GeoIP2 or IP2Region to get approximate location from IP address:

```php
// IP geolocation — client cannot spoof (unless VPN)
function get_ip_geolocation($ip) {
    $reader = new GeoIp2\Database\Reader('/path/to/GeoLite2-City.mmdb');
    $record = $reader->city($ip);
    
    return [
        'lat' => $record->location->latitude,
        'lon' => $record->location->longitude,
        'city' => $record->city->name,
        'country' => $record->country->name,
        'accuracy_km' => 50  // IP geolocation is city-level
    ];
}
Note: The IP address used is the resolved client IP from resolvedClientIp() — which already walks through X-Forwarded-For, X-Real-IP, CF-Connecting-IP, etc.

Trust Levels
Scenario	GPS Trust	IP Geolocation Trust	Session Identity
GPS + IP agree	HIGH	HIGH	Use GPS
GPS only (no IP match)	MEDIUM	MEDIUM	Use both, log mismatch
Mock detected (Android)	LOW	HIGH	Prefer IP
No GPS (consent revoked)	N/A	MEDIUM	Use IP only
VPN/proxy detected	N/A	LOW	Fallback to Class C IP only
Session Identity Formula (with Fallback)
Priority order:

If GPS available AND trust HIGH: Use GPS + IP

If GPS available BUT trust LOW (mock detected): Use IP geolocation only

If GPS available BUT mismatch: Use both, log anomaly

If GPS unavailable: Use IP geolocation

If IP geolocation unavailable (VPN): Fallback to Class C IP only

php
function get_session_identity_factors($session) {
    $factors = [
        'user_agent' => $session->filtered_ua,
        'salt' => LUPO_SESSION_SALT
    ];
    
    // Priority 1: Trusted GPS
    if ($session->gps_consent_granted && !$session->gps_mock_detected) {
        $ip_geo = get_ip_geolocation($session->resolved_ip);
        $distance = haversine_distance(
            $session->gps_lat, $session->gps_lon,
            $ip_geo['lat'], $ip_geo['lon']
        );
        
        if ($distance < 50) {
            // GPS and IP agree — high trust
            $factors['location'] = $session->gps_lat . '|' . $session->gps_lon;
        } else {
            // Mismatch — log and use IP instead
            error_log("GPS/IP mismatch: distance={$distance}km, session={$session->session_id}");
            $factors['location'] = $ip_geo['lat'] . '|' . $ip_geo['lon'];
        }
    } 
    // Priority 2: IP geolocation (fallback)
    elseif ($session->gps_consent_granted && $session->gps_mock_detected) {
        $ip_geo = get_ip_geolocation($session->resolved_ip);
        $factors['location'] = $ip_geo['lat'] . '|' . $ip_geo['lon'];
    }
    // Priority 3: Class C IP only (last resort)
    else {
        $factors['location'] = $session->class_c_ip;
    }
    
    return hash('sha256', implode('|', $factors));
}
Database Schema Additions (Updated from Amendment X)
Add to lupo_sessions table (4.1.0+):

sql
-- From Amendment X (GPS base)
ALTER TABLE lupo_sessions ADD COLUMN gps_lat_hash VARCHAR(128) DEFAULT NULL;
ALTER TABLE lupo_sessions ADD COLUMN gps_lon_hash VARCHAR(128) DEFAULT NULL;
ALTER TABLE lupo_sessions ADD COLUMN gps_consent_granted TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE lupo_sessions ADD COLUMN gps_consent_timestamp BIGINT DEFAULT NULL;
ALTER TABLE lupo_sessions ADD COLUMN gps_accuracy_hash VARCHAR(128) DEFAULT NULL;

-- New for mock detection + IP fallback
ALTER TABLE lupo_sessions ADD COLUMN gps_mock_detected TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE lupo_sessions ADD COLUMN ip_geo_lat_hash VARCHAR(128) DEFAULT NULL;
ALTER TABLE lupo_sessions ADD COLUMN ip_geo_lon_hash VARCHAR(128) DEFAULT NULL;
ALTER TABLE lupo_sessions ADD COLUMN ip_geo_city_hash VARCHAR(128) DEFAULT NULL;
ALTER TABLE lupo_sessions ADD COLUMN ip_geo_country_hash VARCHAR(128) DEFAULT NULL;
ALTER TABLE lupo_sessions ADD COLUMN gps_ip_distance_km INT DEFAULT NULL;
Android Mock Detection Integration
When GPS consent is requested on Android:

kotlin
// Client-side (Android app)
if (location.isMock) {
    // Send mock flag to server
    api.grantGpsConsent(
        consent = true,
        lat = location.latitude,
        lon = location.longitude,
        accuracy = location.accuracy,
        isMock = true  // ← Flag from Android OS
    )
}
Server stores gps_mock_detected = 1 and falls back to IP geolocation.

IP Geolocation Database
Free option: MaxMind GeoLite2-City (updated Tuesday/Friday)

Download script (lupo-scripts/update_geoip_db.sh):

bash
#!/bin/bash
cd /path/to/lupopedia/data
wget -N https://cdn.jsdelivr.net/npm/geolite2-city/GeoLite2-City.mmdb.gz
gunzip -f GeoLite2-City.mmdb.gz
chmod 644 GeoLite2-City.mmdb
PHP integration:

php
// lupo-includes/classes/GeoIpService.php
class GeoIpService {
    private static $reader = null;
    
    public static function getLocation($ip) {
        if (!self::$reader) {
            $dbPath = LUPOPEDIA_PATH . '/data/GeoLite2-City.mmdb';
            self::$reader = new \GeoIp2\Database\Reader($dbPath);
        }
        
        try {
            $record = self::$reader->city($ip);
            return [
                'lat' => $record->location->latitude,
                'lon' => $record->location->longitude,
                'city' => $record->city->name,
                'country' => $record->country->name,
                'success' => true
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
Privacy Considerations
Data Point	Storage	Retention
GPS coordinates	Hashed + salted	Until consent revoked
IP geolocation (city/country)	Hashed + salted	Session lifetime
GPS/IP distance	Integer (km)	Session lifetime
Mock detection flag	Boolean	Session lifetime
No plain-text coordinates ever stored. No exact locations. No tracking across sessions.

Implementation Timeline
Phase	Version	Status
GPS base (Amendment X)	4.1.0	📝 PLANNED
Mock detection (Android)	4.1.0	📝 PLANNED
IP geolocation service	4.1.0	📝 PLANNED
GeoIP2 integration	4.1.0	📝 PLANNED
Trust level logic	4.1.0	📝 PLANNED
Privacy documentation	4.1.0	📝 PLANNED
Constitutional Status
Document	Status
This amendment	📝 DRAFT (not yet approved)
Amendment X (GPS base)	📝 DRAFT
Session Doctrine	✅ APPROVED (without GPS)
This amendment is informational only. Do not implement until 4.1.0+.

Summary for Agents
Agent	Action
All agents	Read and understand for future planning
None	Do NOT implement in 4.0.96
GPS mock detection + IP geolocation fallback is OPT-IN, DEFAULT OFF, and FUTURE.

Current session system (4.0.96) is complete without this.

text

---

## Success Criteria

- [ ] Amendment appended to the session PRD
- [ ] No existing content changed
- [ ] File saved

**Go. One agent. One file. One append.**


