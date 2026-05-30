#!/bin/bash
# PRD 86 Immune System Checkpoint Gate
# Blocks commits that violate header doctrine

echo "[IMMUNE SYSTEM] Checkpoint Gate Active"
echo "======================================"

# 1. Run regression tests first
echo "[INFO] Running header regression tests..."
python scripts/test_canonical_22_field_validation.py
TEST_EXIT_CODE=$?

if [ $TEST_EXIT_CODE -ne 0 ]; then
    echo ""
    echo "[BLOCKED] Regression tests failed"
    echo "Header doctrine violations detected!"
    exit 1
fi

echo "[PASS] Regression tests passed"

# 2. Run strict validator on all staged files
echo ""
echo "[INFO] Running strict validator on staged files..."
STAGED_FILES=$(git diff --cached --name-only --diff-filter=ACMR | grep -E '\.(md|py|php|js)$')

if [ -n "$STAGED_FILES" ]; then
    echo "Files to validate: $STAGED_FILES"
    python scripts/validate_lupopedia_headers_universal.py --strict $STAGED_FILES
    VALIDATOR_EXIT_CODE=$?
    
    if [ $VALIDATOR_EXIT_CODE -ne 0 ]; then
        echo ""
        echo "[BLOCKED] Header validation failed"
        echo "Strict-mode violations detected in staged files!"
        exit 1
    fi
    
    echo "[PASS] All staged files passed strict validation"
else
    echo "No header files staged for validation"
fi

echo ""
echo "[PASS] All validations successful"
echo "[IMMUNE SYSTEM] Repository protected"
exit 0
