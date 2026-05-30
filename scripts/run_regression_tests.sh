#!/bin/sh
# 4.0.20 T3: Run all regression tests under tests/regression/
# Run from repo root: sh scripts/run_regression_tests.sh [REPO_ROOT]
# Exit 0 if all pass. Outputs PASS count, FAIL count, SKIP count.

REPO_ROOT="${1:-.}"
if [ ! -d "$REPO_ROOT/tests/regression" ]; then
    echo "Regression tests directory not found: $REPO_ROOT/tests/regression"
    exit 1
fi
cd "$REPO_ROOT" || exit 1

PASS=0
FAIL=0
SKIP=0

# Run all PHP in tests/regression subdirs
for dir in tests/regression/admin tests/regression/auth tests/regression/session tests/regression/legacy tests/regression/csrf tests/regression/permissions tests/regression/installer; do
    [ -d "$dir" ] || continue
    for f in "$dir"/*.php; do
        [ -f "$f" ] || continue
        out=$(php "$f" 2>&1)
        ret=$?
        if [ $ret -eq 0 ]; then
            if echo "$out" | grep -q "SKIP"; then
                SKIP=$((SKIP+1))
            else
                PASS=$((PASS+1))
            fi
        else
            FAIL=$((FAIL+1))
            echo "FAILED: $f"
            echo "$out" | head -5
        fi
    done
done

echo ""
echo "Regression summary: PASS=$PASS FAIL=$FAIL SKIP=$SKIP"
if [ $FAIL -gt 0 ]; then
    exit 1
fi
exit 0
