# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-tests/adversarial/README.md"
  file_hash: "06a5556ba99fd09b19e4b56e24b890ea2e0a20f43c01c68c6982d542f95581a3"
  last_updated_utc: "20260228155738"
  system_version: "4.0.51"
  channel_id: 1
  actor_id: 1002
  delegation_chain: "1002:10000"
  artifact_type: "documentation"
  artifact_kind: "documentation"
  purpose: "Documentation file with LUPOPEDIA HEADERS applied"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.51"]
  tags: ["documentation", "flare_applied"]
  lupo_agent: "windsurf"

lupopedia.edges:
  outbound_edges:
    - { to: "CHANGELOG.md", type: "references", weight: 1.0 }
    - { to: "lupo-docs/doctrine/", type: "references", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260228155738"
  last_verified_by: "windsurf"
---

# FLARE Header (aliases: Wolfie, FLIP, FLP, FLPH, CROP)

---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  lupopedia.edges: []
  file_path_from_root: "lupo-tests\adversarial\README.md"
  file_hash: "a1dda475544d3820ddd45d0ca65e4e84e76a45debc486f7bd52e52e868e8ada8"
  file_path_from_root: "lupo-tests\adversarial\README.md"
  file_hash: "6c6cf868589cc30b86d7aa257ed402d65275a5069d5555851d3a4e043460daf1"
  last_updated_utc: "20260228"
  system_version: "4.0.50"
  channel_id: 1
  actor_id: 1002
  delegation_chain: null
  artifact_type: "guide"
  artifact_kind: "documentation"
  purpose: "Adversarial Test Harness (4.0.20 T4)"
  mood_rgb: "4169E1"
  traits: ["flare", "indexed", "v4.0.50"]
  tags: ["tests", "adversarial", "readmemd"]
  lupo_agent: "windsurf"

  needs_review: ["delegation_chain"]
  system_version: "4.0.50"
  last_updated_utc: "20260228"
lupopedia.footer:
  last_verified: "20260228"
  last_verified_by: "windsurf"
---

# Adversarial Test Harness (4.0.20 T4)

Red-team style probes for admin and auth. **Local-only, safe, non-destructive.** All tests use HTTP (curl) only; no schema or app logic changes.

## Quick start

1. Start the app (e.g. `http://localhost/lupopedia`).
2. From repo root:
   ```bash
   php lupo-tests/adversarial/run.php
   # Or with base URL:
   php lupo-tests/adversarial/run.php http://localhost/lupopedia
   ```
3. Or use the script:
   ```bash
   sh lupo-scripts/run_adversarial_tests.sh . http://localhost/lupopedia
   ```
4. Results are appended to `lupo-tests/adversarial/results/YYYY-MM-DD.jsonl`.

## Sanity test

Verifies one vector (missing CSRF → 403):

```bash
php lupo-tests/adversarial/sanity_test.php [BASE_URL]
```

Exit 0: 403 received or server unreachable (skip). Exit 1: unexpected response.

## Attack vectors

| Vector | Description | Expected |
|--------|-------------|----------|
| csrf_missing | POST to admin without CSRF token | 403 |
| csrf_invalid | POST with wrong CSRF token | 403 |
| unauthorized_access | GET admin while logged out | 302 / 403 / 200 |
| privilege_escalation | Admin action with low-priv or no session | 403 |
| malformed_long_param | Very long cookie | No 500 |
| sql_injection_probe | `' OR '1'='1` in query param | No 500 |
| xss_probe | `<script>` in query param | No 500, output escaped |
| session_tamper | Request with fake session cookie | 302 / 403 |
| rate_limit_test | Several rapid GETs | No 500 |

## Results format

Each run appends one JSON line per vector to `results/YYYY-MM-DD.jsonl`:

```json
{
  "timestamp": "2026-02-19T20:00:00+00:00",
  "vector": "csrf_bypass_attempt",
  "expected": 403,
  "actual": 403,
  "passed": true,
  "details": { "url": "...", "method": "POST", "payload": "..." }
}
```

## Adding new vectors

1. In `StonedWolfieHarness.php`, add a new `case 'vector_name':` in `runVector()`.
2. Use `$this->makeRequest()` (and optionally `$this->getSessionCookie()`).
3. Call `$this->logResult($vector, $expected, $actual, $passed, $details)`.
4. Return `array('vector'=>, 'expected'=>, 'actual'=>, 'passed'=>)`.
5. Append the vector name to the `$vectors` array in `runAll()`.

## Requirements

- PHP 5.3+ with **curl extension**. If curl is not loaded, `run.php` and `sanity_test.php` exit 0 with a SKIP message.
- Server running at BASE_URL (default `http://localhost/lupopedia`) for meaningful results. If the server is down, requests will fail and vectors may report failure.
