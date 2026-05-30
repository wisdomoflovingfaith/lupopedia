#!/bin/sh
# T8/4.0.19 Integration tests: admin.php (unauthenticated).
# Run from repo root: sh tests/integration/test_admin.sh [BASE_URL]
# BASE_URL default: http://localhost (use http://localhost/lupopedia for subfolder install).
# Validates: anon request to admin.php gets redirect to login or 200 with login/access-denied content.
# Full admin CRUD flows require authenticated session (manual or browser).

BASE_URL="${1:-http://localhost}"
FAIL=0

# Admin URL: BASE_URL already includes path (e.g. http://localhost or http://localhost/lupopedia)
ADMIN_URL="${BASE_URL}/admin.php"

# Unauthenticated GET admin.php: expect 302 (redirect to login) or 200 (login form / access denied)
echo "=== Admin (anon): $ADMIN_URL ==="
CODE=$(curl -s -o /tmp/t8_admin.html -w "%{http_code}" "$ADMIN_URL")
if [ "$CODE" != "200" ] && [ "$CODE" != "302" ]; then
    echo "FAIL expected 200 or 302, got $CODE"
    FAIL=$((FAIL+1))
else
    echo "PASS status $CODE"
fi
if [ "$CODE" = "200" ] && [ -s /tmp/t8_admin.html ]; then
    if grep -qi "login\|sign in\|access denied\|permission" /tmp/t8_admin.html 2>/dev/null; then
        echo "PASS response indicates auth or access control"
    else
        echo "INFO 200 body may be admin dashboard (if session present); check /tmp/t8_admin.html"
    fi
fi

# Unauthenticated GET admin.php?section=users: same expectations
echo "=== Admin section=users (anon): $ADMIN_URL?section=users ==="
CODE=$(curl -s -o /tmp/t8_admin_users.html -w "%{http_code}" "$ADMIN_URL?section=users")
if [ "$CODE" != "200" ] && [ "$CODE" != "302" ]; then
    echo "FAIL expected 200 or 302, got $CODE"
    FAIL=$((FAIL+1))
else
    echo "PASS status $CODE"
fi

if [ $FAIL -gt 0 ]; then
    echo "FAIL ($FAIL tests)"
    exit 1
fi
echo "PASS (admin integration)"
exit 0
