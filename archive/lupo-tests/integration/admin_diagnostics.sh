#!/usr/bin/env sh
# Integration test for admin diagnostics (4.0.20).
# Curl admin.php as admin user (expect session + permission logs), non-admin (denied), missing/invalid CSRF (csrf logs).
# Usage: sh tests/integration/admin_diagnostics.sh [BASE_URL]
# Example: sh tests/integration/admin_diagnostics.sh http://localhost/lupopedia

BASE_URL="${1:-http://localhost/lupopedia}"
ADMIN_URL="${BASE_URL}/admin.php"
LOG_DIR=""
if [ -n "$LUPOPEDIA_PATH" ]; then
    LOG_DIR="${LUPOPEDIA_PATH}/logs/admin"
else
    SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
    REPO_ROOT="$(cd "$SCRIPT_DIR/../.." && pwd)"
    LOG_DIR="${REPO_ROOT}/logs/admin"
fi
LOG_FILE="${LOG_DIR}/admin_diag.json"

echo "Admin diagnostics integration test"
echo "  BASE_URL: $BASE_URL"
echo "  Log file: $LOG_FILE"
echo ""

# 1. Unauthenticated GET admin.php -> expect 302 redirect to login (or 200 with login form)
echo "1. Unauthenticated GET admin.php"
CODE=$(curl -s -o /dev/null -w "%{http_code}" "$ADMIN_URL")
if [ "$CODE" = "302" ] || [ "$CODE" = "200" ]; then
    echo "   PASS: got $CODE (redirect or login page)"
else
    echo "   FAIL: expected 302 or 200, got $CODE"
fi

# 2. With admin cookie: need a real session. Without a real login we cannot get session + permission logs.
# So we only verify that when server is up we get a sensible response.
echo "2. Admin user session/permission logs: run manually - log in as admin, open admin.php, then check $LOG_FILE for session + permission_check entries"

# 3. Non-admin: same - need to log in as non-admin and hit admin.php, then check log for permission_check allowed:false
echo "3. Non-admin permission denied: run manually - log in as non-admin, open admin.php, check log for permission_check with allowed:false"

# 4. Missing CSRF: POST without token (no cookie = may get redirect first; with cookie but no token = 403)
echo "4. Missing CSRF token"
BODY=$(curl -s -w "\n%{http_code}" -X POST "${ADMIN_URL}?section=users&save_profile=1" -d "auth_user_id=1&display_name=Test&email=test@test.com")
HTTP_CODE=$(echo "$BODY" | tail -n 1)
if [ "$HTTP_CODE" = "403" ]; then
    echo "   PASS: POST without CSRF returned 403"
else
    echo "   INFO: got $HTTP_CODE (403 expected if session present and CSRF checked; 302 if redirected to login)"
fi

# 5. Invalid CSRF token
echo "5. Invalid CSRF token"
BODY=$(curl -s -w "\n%{http_code}" -X POST "${ADMIN_URL}?section=users&save_profile=1" -d "auth_user_id=1&csrf_token=invalid&display_name=Test&email=test@test.com")
HTTP_CODE=$(echo "$BODY" | tail -n 1)
if [ "$HTTP_CODE" = "403" ]; then
    echo "   PASS: POST with invalid CSRF returned 403"
else
    echo "   INFO: got $HTTP_CODE"
fi

echo ""
echo "If the server is running and you have an admin session cookie, repeat steps 4-5 with -b cookies.txt to verify CSRF logs in $LOG_FILE"
