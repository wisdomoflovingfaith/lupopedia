#!/bin/sh
# 4.0.20 T4: Run adversarial harness (Stoned Wolfie). Requires server at BASE_URL.
# Run from repo root: sh scripts/run_adversarial_tests.sh [REPO_ROOT] [BASE_URL]
# Exit 0 if all vectors pass; 1 if any fail.

REPO_ROOT="${1:-.}"
BASE_URL="${2:-http://localhost/lupopedia}"
cd "$REPO_ROOT" || exit 1

if [ ! -f "lupo-tests/adversarial/run.php" ]; then
    echo "Adversarial run.php not found"
    exit 1
fi

echo "Running adversarial tests (base URL: $BASE_URL)..."
php lupo-tests/adversarial/run.php "$BASE_URL"
exit $?
