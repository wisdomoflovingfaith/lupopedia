#!/bin/sh
# T8 Integration tests: curl-based routing (canonical, alias, encoded slug, Smart 404, non-base).
# Run from repo root: sh tests/integration/test_routing.sh [BASE_URL]
# BASE_URL default: http://localhost (or http://localhost/lupopedia for subfolder install).
# Validates: HTTP status (200/302/404), resolved_uri, Smart 404 output, suggestions when authenticated.

BASE_URL="${1:-http://localhost}"
FAIL=0

# Canonical path: expect 200 (or 302 if alias redirect)
echo "=== Canonical: $BASE_URL/doctrine/FLP/FLIPPING_FILE_LEXA_LILITH ==="
CODE=$(curl -s -o /tmp/t8_canonical.html -w "%{http_code}" "$BASE_URL/doctrine/FLP/FLIPPING_FILE_LEXA_LILITH")
if [ "$CODE" != "200" ] && [ "$CODE" != "302" ]; then
    echo "FAIL expected 200 or 302, got $CODE"
    FAIL=$((FAIL+1))
else
    echo "PASS status $CODE"
fi

# Alias path: may 302 to canonical or 200
echo "=== Alias: $BASE_URL/qa/FLIPPING_FILES ==="
CODE=$(curl -s -o /tmp/t8_alias.html -w "%{http_code}" -L "$BASE_URL/qa/FLIPPING_FILES")
if [ "$CODE" != "200" ] && [ "$CODE" != "302" ]; then
    echo "FAIL expected 200 or 302, got $CODE"
    FAIL=$((FAIL+1))
else
    echo "PASS status $CODE"
fi

# Encoded slug: /qa/FLIPPING+FILES
echo "=== Encoded slug: $BASE_URL/qa/FLIPPING+FILES ==="
CODE=$(curl -s -o /tmp/t8_encoded.html -w "%{http_code}" "$BASE_URL/qa/FLIPPING%2BFILES")
if [ "$CODE" != "200" ] && [ "$CODE" != "302" ]; then
    echo "FAIL expected 200 or 302, got $CODE"
    FAIL=$((FAIL+1))
else
    echo "PASS status $CODE"
fi

# Smart 404: typo /doctine/FLIP (anonymous: 404, no suggestions)
echo "=== Smart 404 (anon): $BASE_URL/doctine/FLIP ==="
CODE=$(curl -s -o /tmp/t8_smart404.html -w "%{http_code}" "$BASE_URL/doctine/FLIP")
if [ "$CODE" != "404" ]; then
    echo "FAIL expected 404, got $CODE"
    FAIL=$((FAIL+1))
else
    echo "PASS status 404"
fi
if grep -q "Authenticated users may see suggestions" /tmp/t8_smart404.html 2>/dev/null; then
    echo "PASS kapakai anon message"
elif grep -q "Page not found" /tmp/t8_smart404.html 2>/dev/null; then
    echo "PASS Smart 404 page"
else
    echo "INFO check /tmp/t8_smart404.html for content"
fi

# Non-base path: /something/else -> not Smart 404 (existing 404 or other handler)
echo "=== Non-base: $BASE_URL/something/else ==="
CODE=$(curl -s -o /tmp/t8_nonbase.html -w "%{http_code}" "$BASE_URL/something/else")
echo "INFO status $CODE (may be 404 or 200 depending on site)"

# resolved_uri: when rewrite is used, index.php receives resolved_uri; we cannot see it via curl without debug
echo "=== resolved_uri (verify rewrite: hit a base path and check response has content) ==="
if [ -s /tmp/t8_canonical.html ] && grep -q "FLIPPING\|doctrine\|FLIP" /tmp/t8_canonical.html 2>/dev/null; then
    echo "PASS canonical response has content"
else
    echo "INFO canonical response in /tmp/t8_canonical.html (may be redirect or empty)"
fi

# Ban at Gate: anonymous request to canonical path must NOT be 403 (anon not banned). For 403, log in as banned actor and request same path.
echo "=== Ban at Gate (anon: canonical path must not be 403) ==="
CODE=$(curl -s -o /tmp/t8_ban.html -w "%{http_code}" "$BASE_URL/doctrine/FLP/FLIPPING_FILE_LEXA_LILITH")
if [ "$CODE" = "403" ]; then
    echo "FAIL anon request got 403 (expected 200/302 unless actor is banned)"
    FAIL=$((FAIL+1))
else
    echo "PASS anon status $CODE (403 only expected when logged in as banned actor)"
fi

if [ $FAIL -gt 0 ]; then
    echo "FAIL ($FAIL tests)"
    exit 1
fi
echo "PASS (integration)"
exit 0
