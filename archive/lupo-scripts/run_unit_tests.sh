#!/bin/sh
# T8: Run all unit tests under lupo-tests/unit/*.php
# Run from repo root: sh lupo-scripts/run_unit_tests.sh
# Exit 0 if all pass, non-zero if any fail.

REPO_ROOT="${1:-.}"
if [ ! -d "$REPO_ROOT/lupo-tests/unit" ]; then
    echo "Unit tests directory not found: $REPO_ROOT/lupo-tests/unit"
    exit 1
fi
cd "$REPO_ROOT" || exit 1

# Run channel artifact validation if channel tree is touched
if [ -d "lupo-channels" ]; then
    echo "Validating channel artifacts..."
    python lupo-scripts/validate_channel_artifacts.py --repo-root . --channel 42 --mode enforce
    VALIDATION_RESULT=$?
    if [ $VALIDATION_RESULT -ne 0 ]; then
        echo "Channel validation failed"
        exit 1
    fi
fi

FAIL=0
PASS=0
for f in lupo-tests/unit/*.php; do
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
