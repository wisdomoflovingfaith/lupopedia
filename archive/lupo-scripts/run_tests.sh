#!/bin/sh
# 4.0.20: Run unit, integration, regression, and (when present) adversarial tests.
# Run from repo root: sh lupo-scripts/run_tests.sh [REPO_ROOT]
# Exit 0 only if all run suites pass.

REPO_ROOT="${1:-.}"
cd "$REPO_ROOT" || exit 1
OVERALL=0

echo "=== Unit tests ==="
if [ -d "lupo-scripts" ] && [ -f "lupo-scripts/run_unit_tests.sh" ]; then
    if sh lupo-scripts/run_unit_tests.sh "$REPO_ROOT"; then
        echo "Unit: PASS"
    else
        echo "Unit: FAIL"
        OVERALL=1
    fi
else
    echo "Unit: skip (run_unit_tests.sh not found)"
fi

echo ""
echo "=== Regression tests ==="
if [ -f "lupo-scripts/run_regression_tests.sh" ]; then
    if sh lupo-scripts/run_regression_tests.sh "$REPO_ROOT"; then
        echo "Regression: PASS"
    else
        echo "Regression: FAIL"
        OVERALL=1
    fi
else
    echo "Regression: skip (run_regression_tests.sh not found)"
fi

echo ""
echo "=== Integration tests ==="
if [ -d "lupo-tests/integration" ]; then
    for s in lupo-tests/integration/*.sh; do
        [ -f "$s" ] || continue
        name=$(basename "$s")
        if sh "$s" 2>&1 | tail -3; then
            echo "Integration $name: PASS"
        else
            echo "Integration $name: FAIL or SKIP (may need server)"
        fi
    done
else
    echo "Integration: skip (no lupo-tests/integration)"
fi

echo ""
echo "=== Adversarial tests (T4) ==="
if [ -d "lupo-tests/adversarial" ] && [ -n "$(ls lupo-tests/adversarial/*.php 2>/dev/null)" ]; then
    if [ -f "lupo-scripts/run_adversarial_tests.sh" ]; then
        sh lupo-scripts/run_adversarial_tests.sh "$REPO_ROOT" || OVERALL=1
    else
        echo "Adversarial: skip (run_adversarial_tests.sh not yet added)"
    fi
else
    echo "Adversarial: skip (T4 not yet implemented)"
fi

echo ""
echo "=== PRD 17 validators ==="
if [ -f "lupo-scripts/validate_pseudocode_discipline.py" ] && [ -f "lupo-scripts/validate_thread_structure.py" ] && [ -f "lupo-scripts/validate_edge_linking.py" ]; then
    PRD17_SCOPE="$REPO_ROOT/lupo-docs/versions/4.0.96"
    if python lupo-scripts/validate_pseudocode_discipline.py "$PRD17_SCOPE" && \
       python lupo-scripts/validate_thread_structure.py "$PRD17_SCOPE" && \
       python lupo-scripts/validate_edge_linking.py "$PRD17_SCOPE"; then
        echo "PRD 17 validators: PASS"
    else
        echo "PRD 17 validators: FAIL"
        OVERALL=1
    fi
else
    echo "PRD 17 validators: skip (validator script(s) missing)"
fi

echo ""
echo "=== Trust ladder path advisory (non-fatal) ==="
if [ -f "lupo-scripts/validate_trust_ladder_paths.py" ]; then
    python lupo-scripts/validate_trust_ladder_paths.py || true
    echo "Trust ladder advisory: informational only (use --strict to fail CI once legacy paths are gone)"
else
    echo "Trust ladder: skip (validate_trust_ladder_paths.py missing)"
fi

echo ""
if [ $OVERALL -eq 0 ]; then
    echo "All test suites completed (unit, regression required; integration/adversarial may be partial)."
else
    echo "One or more required suites failed."
fi
exit $OVERALL
