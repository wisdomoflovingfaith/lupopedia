---
lupopedia.headers:
  header_format_version: "4.1.0"
  file_path_from_root: "lupo-docs/CLOUDFLARE_INTEGRATION.md"
  web_path: "http://www.lupopedia.com/CLOUDFLARE_INTEGRATION"
  status: ""
  when_updated: null
  trust_tier: null
  questions_toon: null
  memory_toon: null
  atoms_toon: null
  transcript_jsonl: null
  artifact_type: documentation
  artifact_kind: reference
  channel_key: null
  federation_node_id: null
  thread_id: ""
  content_id: null
  pk_id: null
  pk_slug: ""
  parent_pk_id: ""
  lupopedia.schema: documentation
  title: "CLOUDFLARE INTEGRATION"
  summary: ""
---
# file: CLOUDFLARE INTEGRATION (Lupopedia) — delegation: cursor:root — web_path: http://www.lupopedia.com/CLOUDFLARE_INTEGRATION

# Cloudflare Integration (Lupopedia)

This document describes how **Cloudflare** (the CDN/WAF) is integrated with Lupopedia’s actor system for real client IP, geolocation, logging, and optional security. It is separate from **FLARE** (Lupopedia’s file-level metadata protocol). See [CLOUDFLARE_VS_FLARE.md](doctrine/CLOUDFLARE_VS_FLARE.md).

## Summary

- **Cloudflare headers** used: `CF-Connecting-IP`, `CF-IPCountry`, `CF-Ray`, and optionally `CF-Threat-Score`.
- **Handler:** `lupo-includes/classes/CloudflareRequestHandler.php` runs from `bootstrap.php` after security headers. It normalizes `REMOTE_ADDR` from `CF-Connecting-IP` (with optional proxy IP validation) and defines `LUPO_CLIENT_IP`, `LUPO_CLIENT_COUNTRY`, `LUPO_CF_RAY`, `LUPO_CF_THREAT_SCORE`, `LUPO_REQUEST_VIA_CLOUDFLARE`.
- **Config:** Optional defines in `lupopedia-config.php` enable validation, threat threshold, and country blocking.
- **agents.php:** Example entry point that uses these constants for logging and safe, path-resolved .md delivery for actor/federation paths.

## Cloudflare Headers (Reference)

| Header | Purpose |
|--------|--------|
| `CF-Connecting-IP` | Real visitor IP (origin sees this instead of Cloudflare’s IP). |
| `CF-IPCountry` | Two-letter country code (ISO 3166-1 alpha-2); `T1` = Tor, `XX` = unknown. |
| `CF-Ray` | Request ID for support/debugging. |
| `CF-Threat-Score` | 0–100 bot/threat score (when enabled; may be Enterprise). |

Only requests that go through Cloudflare to your origin will have these headers. Direct hits to the origin will not.

## Configuration (lupopedia-config.php)

Add or uncomment as needed when the site is behind Cloudflare:

```php
define('LUPO_CLOUDFLARE_ENABLED', true);
define('LUPO_CLOUDFLARE_TRUST_PROXY', true);   // Use CF-Connecting-IP without validating that REMOTE_ADDR is a Cloudflare IP
// define('LUPO_CLOUDFLARE_VALIDATE_IP', true); // If true, REMOTE_ADDR must be in LUPO_CLOUDFLARE_IPS_FILE list before trusting CF-Connecting-IP
// define('LUPO_CLOUDFLARE_IPS_FILE', ABSPATH . 'lupo-database/cloudflare-ips-v4.txt');
// define('LUPO_CLOUDFLARE_THREAT_MAX', 14);   // Block if CF-Threat-Score > this (-1 = disable)
// define('LUPO_CLOUDFLARE_BLOCKED_COUNTRIES', 'T1'); // Comma-separated country codes to block
```

- **LUPO_CLOUDFLARE_TRUST_PROXY true:** Use `CF-Connecting-IP` without checking Cloudflare IPs (simple, less secure if someone can reach origin and send fake headers).
- **LUPO_CLOUDFLARE_VALIDATE_IP true:** Only trust `CF-Connecting-IP` when `REMOTE_ADDR` is in the file at `LUPO_CLOUDFLARE_IPS_FILE` (one CIDR per line from [Cloudflare IPv4 list](https://www.cloudflare.com/ips-v4/)).

## IP Validation File

To use `LUPO_CLOUDFLARE_VALIDATE_IP`:

1. Download IPv4 list: `https://www.cloudflare.com/ips-v4`
2. Save as e.g. `lupo-database/cloudflare-ips-v4.txt` (one CIDR per line).
3. Set `LUPO_CLOUDFLARE_IPS_FILE` to that path and keep the file updated periodically.

No Composer or vendor is required; the handler is plain PHP 5.3+.

## Routing and agents.php

- **agents.php** is an optional entry point for actor/federation-node–based content.
- Query: `actor_id` (required), `what` (e.g. `federation_node_id=0`), optional `file` (e.g. `readme.md`).
- Path resolution uses `LUPO_DATABASE_DIR` and `LUPO_ACTORS_DIR` to build a candidate path; `file` is only served if it is under that path and has a safe `.md` name.
- Logging and response can use `LUPO_CLIENT_IP`, `LUPO_CLIENT_COUNTRY`, `LUPO_CF_RAY` (and in code, `CloudflareRequestHandler::getThreatScore()` etc.).

Example:

- `agents.php?actor_id=0&what=federation_node_id=0` → path like `lupo-database/.../federation_node_id_0/actor_id/0/` or `lupo-actors/0/`.
- With `&file=readme.md` and that file present under the resolved path, returns the Markdown content.

## Access Control and Threat Score

- If `LUPO_CLOUDFLARE_THREAT_MAX` is set and `CF-Threat-Score` is present and above the threshold, the handler returns 403 before app logic runs.
- If `LUPO_CLOUDFLARE_BLOCKED_COUNTRIES` lists a country (e.g. `T1` for Tor), requests from that country are 403’d.
- You can extend this in your own code (e.g. restrict certain `actor_id` or admin routes by country) using `LUPO_CLIENT_COUNTRY` and `LUPO_CLIENT_IP`.

## Edge Cases

| Case | Behavior |
|------|----------|
| No Cloudflare in front | No CF-* headers; `LUPO_CLIENT_IP` = `REMOTE_ADDR`, `LUPO_REQUEST_VIA_CLOUDFLARE` = false. |
| Bypass / spoofed CF headers | With `LUPO_CLOUDFLARE_VALIDATE_IP` and a valid IP file, `CF-Connecting-IP` is only trusted when `REMOTE_ADDR` is a Cloudflare IP. Without validation, spoofed headers could override IP (use validation in production when possible). |
| Invalid actor_id | agents.php returns 400 with JSON error. |
| Missing directory | agents.php returns JSON with `exists: false` and does not serve files. |
| file= with path traversal | Only `basename()` and a safe `.md` pattern are used; path is checked with `realpath()` against the base path. |

## Deployment (No Composer)

1. Ensure `CloudflareRequestHandler.php` is under `lupo-includes/classes/` and bootstrap is unchanged (it already requires and runs `CloudflareRequestHandler::process()`).
2. If using IP validation, create `lupo-database/cloudflare-ips-v4.txt` and set `LUPO_CLOUDFLARE_IPS_FILE`.
3. In `lupopedia-config.php`, set `LUPO_CLOUDFLARE_ENABLED` and optionally other Cloudflare defines.
4. Point Cloudflare to your origin; traffic will then send CF-* headers to the origin.
5. Optional: add a cron or script to refresh `cloudflare-ips-v4.txt` from Cloudflare’s list.

## Tests (Manual or Script)

- **Without Cloudflare:** Request any page; in debug log or a small test script, confirm `LUPO_CLIENT_IP` equals `REMOTE_ADDR` and `LUPO_REQUEST_VIA_CLOUDFLARE` is false.
- **With Cloudflare:** After going live behind Cloudflare, confirm `LUPO_CLIENT_IP` matches the real client IP and `LUPO_CLIENT_COUNTRY` is set.
- **agents.php:** Call `agents.php?actor_id=0` and `agents.php?actor_id=0&what=federation_node_id=0&file=README.md` (adjust path so the file exists); expect JSON or Markdown and correct headers.
- **Blocking:** Set `LUPO_CLOUDFLARE_BLOCKED_COUNTRIES` to your country temporarily; expect 403.

Lupopedia does not use Composer; all of this is implemented with plain PHP and optional static files.
