---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: doctrine
  file_path_from_root: "lupo-docs/doctrine/LEARNED_FROM_WORDPRESS.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/doctrine/LEARNED_FROM_WORDPRESS.md"
  last_modified_utc: "20260406142956"
  when_updated: "20260406142956"
  when_updated_utc: "20260406142956"
  federation_node_id: 0
  channel_id: 42
  thread_id: "learned-from-wordpress"
  actor_id: 2
  actor_name: "lilith"
  delegation_chain: "lilith:audit"
  artifact_type: doctrine
  artifact_kind: reference
  purpose: "Canonical WordPress-derived patterns for multi-environment resilience. Read this instead of scanning lupo-archive/legacy/wordpress-reference/."
  status: active
  tags:
    - wordpress
    - patterns
    - multi-environment
    - fallback
    - detection
    - shared-hosting
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md"
      type: references
      weight: 1.0
      reason: "PRD 33 Section 14 — WordPress distribution study"
    - to: "lupo-docs/prd/00_root_constitutional_system_requirements.md"
      type: references
      weight: 1.0
      reason: "Section 15 — WordPress multi-environment patterns (constitutional)"
    - to: "lupo-install/InstallWizardHtaccessWriter.php"
      type: references
      weight: 1.0
      reason: "Marker merge and SERVER_SOFTWARE gating"
    - to: "lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/answers/20260404_061932_ANSWER_wordpress_distribution_patterns_lilith.md"
      type: references
      weight: 0.95
      reason: "LILITH product resolutions aligned with these patterns"
lupopedia.footer:
  last_verified: "20260406142956"
  verified_by:
    identity_type: actor
    actor_id: 2
    actor_name: lilith
  orchestrator: "cursor:root"
---

# file: LEARNED FROM WORDPRESS — doctrine — Lupopedia

# Learned from WordPress (pattern distillate)

## Purpose

This document records **specific, actionable patterns** observed in **WordPress** source under **`lupo-archive/legacy/wordpress-reference/`** (educational reference only; **GPL** — **do not** paste or ship WordPress code into Lupopedia).

**Checkout note:** **`lupo-archive/`** is **`.gitignore`d**; a fresh **git clone** may not include the WordPress tree. Place a local study copy under **`lupo-archive/legacy/wordpress-reference/`** when verifying line numbers.

**Why this file exists**

- IDE agents should **not** re-scan the entire **`lupo-archive/legacy/wordpress-reference/`** tree for routine work.
- This is the **single Lupopedia doctrine** surface for WordPress-derived resilience patterns.
- When you discover a **new** applicable pattern, **append** it here with a path + line reference into **`lupo-archive/legacy/wordpress-reference/`** (WordPress version in this checkout: **6.9.4** unless the tree is upgraded).

**Non-goals**

- Not a complete map of WordPress.
- Not a license to copy WordPress source into shipping code.
- Patterns **must** be adapted to **PDO_DB**, **PHP 7.4+** core compatibility, and Lupopedia installers.

**Primary WordPress files cited in this revision**

| WordPress path | Topics |
|----------------|--------|
| `lupo-archive/legacy/wordpress-reference/wp-admin/includes/class-wp-debug-data.php` | `SERVER_SOFTWARE`, extensions, `.htaccess` marker strip, writability |
| `lupo-archive/legacy/wordpress-reference/wp-includes/PHPMailer/SMTP.php` | `stream_socket_client` vs `fsockopen`, `set_error_handler` |
| `lupo-archive/legacy/wordpress-reference/wp-includes/version.php` | Required PHP version and extension list |
| `lupo-archive/legacy/wordpress-reference/wp-includes/load.php` | `is_ssl()`, server var fixes |
| `lupo-archive/legacy/wordpress-reference/wp-includes/functions.php` | `wp_normalize_path()`, `wp_raise_memory_limit()` |
| `lupo-archive/legacy/wordpress-reference/wp-includes/class-wpdb.php` | `prepare()` / placeholder discipline |

---

## Pattern 1: Server software field (no assumption)

### WordPress reference

**File:** `lupo-archive/legacy/wordpress-reference/wp-admin/includes/class-wp-debug-data.php`  
**Lines:** ~**374–377** — debug/site-health style field **`httpd_software`**: value and debug copy use **`isset( $_SERVER['SERVER_SOFTWARE'] ) ? $_SERVER['SERVER_SOFTWARE'] : …`** so missing SAPI strings do not fatal.

### The pattern

1. Read **`$_SERVER['SERVER_SOFTWARE']`** only when **`isset`**.
2. Treat empty or unknown values as **unknown**, not “Apache.”
3. Branch installer behavior (e.g. **`.htaccess`** writes) on **conservative** classification.

### Lupopedia implementation

**Shipped:** **`lupo-install/InstallWizardHtaccessWriter.php`** — **`isApacheHtaccessEnvironment()`** (Apache / LiteSpeed vs Nginx / IIS / Caddy; empty **`SERVER_SOFTWARE`** still attempts **`.htaccess`** write for dev/odd stacks).

**Illustrative probe (PHP 7.4+; use isset, not null coalesce):**

```php
$software = '';
if (isset($_SERVER['SERVER_SOFTWARE']) && is_string($_SERVER['SERVER_SOFTWARE'])) {
    $software = $_SERVER['SERVER_SOFTWARE'];
}
```

---

## Pattern 2: Extension and function probes (independent checks)

### WordPress reference

**File:** `lupo-archive/legacy/wordpress-reference/wp-admin/includes/class-wp-debug-data.php`  
**Lines:** ~**457**, **466–471** — **`extension_loaded( 'suhosin' )`**, **`extension_loaded( 'imagick' )`** for reporting.  
**Lines:** ~**705–706** — **`function_exists( 'gd_info' )`** then **`gd_info()`** for GD details.

### The pattern

1. Use **`extension_loaded()`** per extension; do not infer one from another.
2. Use **`function_exists()`** when capability depends on a specific function or drop-in.
3. Prefer **reporting** or **branching** over silent failure.

### Lupopedia implementation

- Align installer preflight and runtime gates with **PRD 33** / **§4** (e.g. **`pdo_mysql`**, **`json`**, optional **GD**).
- New core code: no bare assumption that **curl**, **gd**, **mbstring**, etc. exist without a check or documented requirement.

**Illustrative ladder (concept only):**

```php
if (extension_loaded('imagick')) {
    // prefer Imagick path
} elseif (function_exists('gd_info')) {
    // GD path
} else {
    // degrade or warn per product rules
}
```

---

## Pattern 3: Connection fallback ladder (`stream_socket_client` → `fsockopen`)

### WordPress reference

**File:** `lupo-archive/legacy/wordpress-reference/wp-includes/PHPMailer/SMTP.php`  
**Lines:** ~**412**, **422–439** — static **`$streamok`** from **`function_exists( 'stream_socket_client' )`**; primary connect via **`stream_socket_client`**, fallback comment and **`fsockopen`** path; logging when falling back.

### The pattern

1. **Cache** the capability probe (check once per process where appropriate).
2. Try the **richer** API first when available.
3. Fall back to the **wider** API with the **same** **`$errno` / `$errstr` / timeout** semantics where possible.
4. Preserve operator-visible or loggable error detail.

### Lupopedia implementation

- **In-tree PHPMailer** (if used for mail) should keep the same **spirit** of fallbacks; do not rip out **`fsockopen`** paths on shared hosts.
- Any new socket client in Lupopedia should follow the same **ladder** discipline (probe → primary → fallback → log).

**Illustrative shape (PHP 7.4+):**

```php
static $streamOk = null;
if ($streamOk === null) {
    $streamOk = function_exists('stream_socket_client');
}
// if $streamOk: stream_socket_client(...); else: fsockopen(...);
```

---

## Pattern 4: `.htaccess` marker strip (detect custom rules outside core block)

### WordPress reference

**File:** `lupo-archive/legacy/wordpress-reference/wp-admin/includes/class-wp-debug-data.php`  
**Lines:** ~**483–503** — if root **`.htaccess`** exists, **`file_get_contents`**, then **`preg_replace`** removes **`# BEGIN WordPress` … `# END WordPress`**; non-empty remainder implies **custom** rules for site-health messaging.

### The pattern

1. **`is_file`** before read.
2. Strip **one** marked block with a **multiline** regex.
3. **`trim`** remainder; **empty** means “core-only” inside that marker convention.

### Lupopedia implementation

**Shipped:** **`InstallWizardHtaccessWriter::insertWithMarkers()`** — writes **`# BEGIN LUPOPEDIA` / `# END LUPOPEDIA`** (root) and **`# BEGIN LUPOPEDIA_DB` / `# END LUPOPEDIA_DB`** (**`lupo-database/.htaccess`**).  
For **analysis** (admin/diagnostics), strip **both** marker names with the same **regex idea** as WordPress; **do not** ship WordPress’s regex string as copied code—reimplement the minimal pattern.

**Illustrative strip (root marker only; adapt for `LUPOPEDIA_DB`):**

```php
$marker = 'LUPOPEDIA';
$pattern = '/# BEGIN ' . preg_quote($marker, '/') . '\s*.*?# END ' . preg_quote($marker, '/') . '\s*/s';
$filtered = trim(preg_replace($pattern, '', $content));
$hasCustomOutside = ($filtered !== '');
```

---

## Pattern 5: PHP version and required extensions (central declaration)

### WordPress reference

**File:** `lupo-archive/legacy/wordpress-reference/wp-includes/version.php`  
**Lines:** **40–50** — **`$required_php_version`**, **`$required_php_extensions`** (e.g. **`json`**, **`hash`** in this tree).

**File:** `lupo-archive/legacy/wordpress-reference/wp-admin/includes/class-wp-debug-data.php`  
**Lines:** (elsewhere in file) PHP version string composition and **64-bit** note via **`PHP_INT_SIZE`**.

### The pattern

1. Declare **minimum PHP** and **required extensions** in one obvious place.
2. At bootstrap or installer: **`version_compare`** on **`PHP_VERSION`**.
3. Loop **`extension_loaded`** for required names; fail loud for installer, or soft-warn per product policy.

### Lupopedia implementation

- Lupopedia floor remains **PHP 7.4+** per root PRD (**not** WordPress 7.x); map **required** extensions to **`install.php`** / docs (**`pdo_mysql`**, **`json`**, etc.).
- Optional: report **64-bit** capability the same way as WordPress for support diagnostics (**`PHP_INT_SIZE * 8`**).

---

## Pattern 6: Temporary `set_error_handler` around risky I/O

### WordPress reference

**File:** `lupo-archive/legacy/wordpress-reference/wp-includes/PHPMailer/SMTP.php`  
**Lines:** ~**419–420**, **436–437**, **502–503**, **1532+** — **`set_error_handler`** with closure delegating to **`errorHandler`**, then **`restore_error_handler`** after connect/write paths.

### The pattern

1. Install a handler that **captures** warnings/notices that are not exceptions.
2. Run the risky operation.
3. **`restore_error_handler`** in all paths (success/failure).
4. Map captured data to **logs** or **user-safe** messages (no secrets).

### Lupopedia implementation

- Use sparingly in **new** code; prefer **`try`/`catch`** where **`PDOException`** or custom exceptions apply.
- When wrapping legacy **`fsockopen`** / **`stream_socket_client`**, this pattern is **valid** if handlers are always restored.

**Illustrative (PHP 7.4+; use `array()` for callbacks):**

```php
set_error_handler(array($this, 'captureError'));
try {
    // risky call
} finally {
    restore_error_handler();
}
```

---

## Pattern 7: Writability reporting (paths checked individually)

### WordPress reference

**File:** `lupo-archive/legacy/wordpress-reference/wp-admin/includes/class-wp-debug-data.php`  
**Lines:** ~**1698–1749** — multiple **`wp_is_writable( … )`** checks (**`ABSPATH`**, content, uploads, plugins, themes, etc.) surfaced as **Writable / Not writable** (no auto-chmod in this reporting).

**Note:** **`wp_is_writable()`** is defined in WordPress core **`wp-includes/functions.php`**; it encapsulates OS quirks. Lupopedia should use **`is_writable()`** plus explicit path checks and **parent `fileperms`** hints per **§15.3** / LILITH answers—not a blind chmod.

### The pattern

1. Check **each** sensitive path separately.
2. Report **boolean** writability for operators.
3. **Do not** auto-fix permissions in the same breath as detection.

### Lupopedia implementation

- **`InstallWizardHtaccessWriter::ensureRuntimeDirectories()`** — extend with **parent mode** hints on **`mkdir`** failure (backlog: **`wordpress_pattern_implementation_tasks_20260404.md`**).
- Installer sandbox (**root PRD §9.13**) already aligns with **probe → message**, not silent repair.

---

## Pattern 8: Protocol detection (HTTPS behind proxies; `is_ssl` parity)

### WordPress reference

**File:** `lupo-archive/legacy/wordpress-reference/wp-includes/load.php`  
**Lines:** ~**1659–1672** — **`is_ssl()`**: **`HTTPS`** (`on` / `1`) and **`SERVER_PORT` 443**.

**Note (this tree):** Core **`is_ssl()`** in this checkout does **not** read **`HTTP_X_FORWARDED_PROTO`**. Many shared hosts terminate TLS at a load balancer or Nginx edge; PHP may see **`HTTPS=off`** while the visitor is on HTTPS. Lupopedia still adopts the **proxy-aware** checks below as **constitutional hosting hygiene** (extends the spirit of WordPress’s probe, not a line copy).

### The pattern

1. Do not trust **`$_SERVER['HTTPS']`** alone on unknown stacks.
2. When present, treat **`$_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'`** (case-insensitive) as HTTPS.
3. Optionally align **`HTTP_X_FORWARDED_PORT`** with **443** when the proto header is missing but port is forwarded.
4. Use the resolved scheme when building **absolute** asset URLs, redirects, and **`web_path`**-class strings so **mixed content** (HTTP JS/CSS on an HTTPS page) does not break the operator UI.

### Lupopedia implementation

- Apply when emitting **fully qualified** or scheme-relative URLs for scripts, styles, or APIs — not inside **`LupoLayer`** itself (layers are DOM; scheme is a **URL builder** concern).
- Align with **root PRD §15** (multi-environment, no assumptions).

**Illustrative probe (PHP 7.4+; `isset` only):**

```php
function lupo_request_is_https() {
    if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && is_string($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
        if (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') {
            return true;
        }
    }
    if (isset($_SERVER['HTTPS'])) {
        if (strtolower((string) $_SERVER['HTTPS']) === 'on' || (string) $_SERVER['HTTPS'] === '1') {
            return true;
        }
    }
    if (isset($_SERVER['SERVER_PORT']) && (string) $_SERVER['SERVER_PORT'] === '443') {
        return true;
    }
    if (isset($_SERVER['HTTP_X_FORWARDED_PORT']) && (string) $_SERVER['HTTP_X_FORWARDED_PORT'] === '443') {
        return true;
    }
    return false;
}
```

---

## Pattern 9: Path normalization (Windows vs Linux parity)

### WordPress reference

**File:** `lupo-archive/legacy/wordpress-reference/wp-includes/functions.php`  
**Lines:** ~**2186+** — **`wp_normalize_path()`**: collapse duplicate slashes, normalize **`\\`** to **`/`**, strip trailing slash (drive-letter handling on Windows).

### The pattern

1. PHP on Windows accepts **`/`**; Linux does not treat **`\`** as a separator in all tooling.
2. After **`realpath()`**, **`__DIR__`**, or user-relative joins, normalize to **forward slashes** for comparisons, logs, and **`file_path_from_root`** style metadata.
3. Prevents “file not found” when moving a dev tree from Windows to Linux shared hosting.

### Lupopedia implementation

- Mandatory discipline for **LUPOPEDIA HEADERS** **`file_path_from_root`** and any cross-platform path equality checks in installers and validators.
- Do not invent a second incompatible normalizer per module; one helper or shared convention is enough.

**Illustrative (PHP 7.4+):**

```php
function lupo_normalize_fs_path($path) {
    if (!is_string($path) || $path === '') {
        return '';
    }
    $path = str_replace('\\', '/', $path);
    $path = preg_replace('#/+#', '/', $path);
    return $path;
}
```

---

## Pattern 10: Graceful `ini_set` (resource limits; `wp_raise_memory_limit` spirit)

### WordPress reference

**File:** `lupo-archive/legacy/wordpress-reference/wp-includes/functions.php`  
**Lines:** ~**7842–7850** — **`wp_raise_memory_limit()`** exits early if **`wp_is_ini_value_changeable( 'memory_limit' )`** is false; reads current limit via **`ini_get`** and **`wp_convert_hr_to_bytes`** before attempting a raise.

### The pattern

1. **`ini_set('memory_limit', …)`** and similar may be **ignored**, **disabled**, or **warn** on shared hosting.
2. **Probe** changeability (or catch failure) **before** assuming the new limit applies.
3. **Degrade**: skip optional heavy steps (large image work, big in-memory parses) rather than fatal; surface a **clear** operator message for installer/seed steps.

### Lupopedia implementation

- **Installer / seed** paths: never assume a raised limit succeeded; split large SQL or batch work if memory or **`max_execution_time`** is tight.
- Match **§15.2** (try/catch / explicit failure paths) for operations that might exhaust resources.

---

## Pattern 11: Application-side query discipline (the “bouncer”; `wpdb::prepare` parity)

### WordPress reference

**File:** `lupo-archive/legacy/wordpress-reference/wp-includes/class-wpdb.php`  
**Lines:** ~**1464+** — **`prepare()`**: query must contain **`%`** placeholders; arguments are formatted and escaped **before** the driver sees the final string (not foolproof, but blocks casual concatenation bugs).

### The pattern

1. With **no** foreign keys, triggers, or DB-side logic (**root PRD §1**), the **application** is solely responsible for **type and injection discipline**.
2. Never concatenate untrusted strings into SQL. Use **placeholders** and bound parameters (PDO style: **named** placeholders align with **`PDO_DB`** doctrine).
3. Cast IDs and numeric filters explicitly (**`(int)`**, **`(float)`**) when the schema expects scalars; align with **§3.2** (**`IdGenerator`**) so identifiers stay in-band.

### Lupopedia implementation

- **Mandatory:** **`DatabaseFactory::getConnection()`** / **`PDO_DB`** with **named** parameters — no raw value interpolation in SQL strings.
- **`wpdb::prepare`** is the WordPress **analogy** only; Lupopedia does not ship **`$wpdb`**.
- **Installer exception (constitutional carve-out):** **`install.php`** / wizard **may** use **`mysqli`** only as in **`lupo-docs/doctrine/DATABASE_DOCTRINE.md`** — **Runtime database access (PDO_DB) and installer exception**; runtime **must not** use **`mysqli`**.

---

## Summary table

| # | Pattern | WordPress pointer (6.9.4 tree) | Lupopedia anchor |
|---|---------|--------------------------------|------------------|
| 1 | **`SERVER_SOFTWARE`** + fallback | `class-wp-debug-data.php` ~374–377 | `InstallWizardHtaccessWriter::isApacheHtaccessEnvironment()` |
| 2 | **`extension_loaded` / `function_exists`** | `class-wp-debug-data.php` ~457, 466–471, 705–706 | `install.php` preflight + PRD 33 / §4 |
| 3 | Stream vs **`fsockopen`** ladder | `SMTP.php` ~412–439 | In-tree mail / future sockets — same ladder spirit |
| 4 | `.htaccess` marker strip | `class-wp-debug-data.php` ~483–503 | `insertWithMarkers()` + `LUPOPEDIA` / `LUPOPEDIA_DB` |
| 5 | Required PHP + extensions | `version.php` 40–50 | Installer + docs (7.4+ floor, not WP 7.x) |
| 6 | **`set_error_handler`** scope | `SMTP.php` ~419–503, 1532+ | Legacy I/O only; always restore |
| 7 | Per-path writability | `class-wp-debug-data.php` ~1698–1749 | Installer logs; no auto-chmod |
| 8 | HTTPS / proxy proto | `load.php` ~1659–1672 + **Lupopedia** `X-Forwarded-Proto` | Absolute URLs, mixed-content avoidance; **§15** |
| 9 | Path normalization | `functions.php` ~2186+ **`wp_normalize_path`** | Headers `file_path_from_root`; Win → Linux |
| 10 | Memory limit raise | `functions.php` ~7842+ **`wp_raise_memory_limit`** | Installer/seed degrade; check changeability |
| 11 | Query placeholders | `class-wpdb.php` ~1464+ **`prepare`** | **`PDO_DB`** named params; app is bouncer |

---

## How to use this file

**IDE agents**

1. Read this file first when the task mentions WordPress-style hosting, fallbacks, or **`lupo-archive/legacy/wordpress-reference/`**.
2. Open **`lupo-archive/legacy/wordpress-reference/`** only to **verify** line numbers or study a **new** pattern to add here.
3. **Do not** paste WordPress sources into Lupopedia shipping paths.

**Humans**

1. Keep entries **short**: pointer, pattern, Lupopedia mapping.
2. When upgrading **`lupo-archive/legacy/wordpress-reference/`**, re-spot-check cited line numbers.

---

## References (Lupopedia)

- **Constitutional:** `lupo-docs/prd/00_root_constitutional_system_requirements.md` — **§15**
- **Study / gate:** `lupo-docs/prd/33_softaculous_certification_4_1_0_gate.md` — **Section 14**
- **LILITH answers:** `lupo-docs/implementations/33_softaculous_certification_4_1_0_gate/answers/20260404_061932_ANSWER_wordpress_distribution_patterns_lilith.md`

This output complies with Lupopedia Constitutional Root Rules.
