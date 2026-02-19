#!/bin/sh
# T8: Run all unit tests under tests/unit/*.php
# Run from repo root: sh scripts/run_unit_tests.sh
# Exit 0 if all pass, non-zero if any fail.

REPO_ROOT="${1:-.}"
if [ ! -d "$REPO_ROOT/tests/unit" ]; then
    echo "Unit tests directory not found: $REPO_ROOT/tests/unit"
    exit 1
fi
cd "$REPO_ROOT" || exit 1

FAIL=0
PASS=0
for f in tests/unit/*.php; do
    [ -f "$f" ] || continue
    name=$(basename "$f")
    if php "$f" 2>&1; then
        PASS=$((PASS+1))
    else
        FAIL=$((FAIL+1))
        echo "FAILED: $name"
    fi
done

if [ $FAIL -gt 0 ]; then
    echo "Unit tests failed: $FAIL failed, $PASS passed"
    exit 1
fi
echo "All unit tests passed ($PASS)"
exit 0
