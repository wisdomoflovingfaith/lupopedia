#!/bin/bash
# Pre-commit hook for Lupopedia validation
# Runs comprehensive validation before allowing commits

echo "🔍 Running Lupopedia pre-commit validation..."

# Run immune system header validation first (PRD 86 Immune System Mode)
echo "�️ IMMUNE SYSTEM: Checking header compliance..."
python scripts/validate_lupopedia_headers_universal.py --reject-legacy-fields $(git diff --cached --name-only --diff-filter=ACMR | grep -E '\.(md|py|php|js)$' | head -20)
HEADER_EXIT_CODE=$?

# Additional immune system check: validate ALL staged files for removed fields
echo "🛡️ IMMUNE SYSTEM: Scanning for removed fields in all staged files..."
for file in $(git diff --cached --name-only --diff-filter=ACMR | grep -E '\.(md|py|php|js)$'); do
    if [ -f "$file" ]; then
        # Quick check for removed fields without full validation
        if grep -q -E "^\s*(content_slug|pk_slug|prd_slug):" "$file"; then
            echo "[IMMUNE SYSTEM] BLOCKED: Removed field detected in $file"
            echo "[IMMUNE SYSTEM] content_slug, pk_slug, and prd_slug are forbidden in v4.1.4 headers"
            HEADER_EXIT_CODE=1
            break
        fi
    fi
done

# Run implementation validation
python hooks/pre_commit_validate.py
IMPL_EXIT_CODE=$?

# Check exit codes
if [ $HEADER_EXIT_CODE -ne 0 ] || [ $IMPL_EXIT_CODE -ne 0 ]; then
    echo ""
    echo "❌ COMMIT BLOCKED"
    echo "Validation failed. Please fix the issues above before committing."
    if [ $HEADER_EXIT_CODE -ne 0 ]; then
        echo "Header validation failed - strict mode enforcement active for PRD 16 v4.1.4"
    fi
    if [ $IMPL_EXIT_CODE -ne 0 ]; then
        echo "Implementation validation failed"
    fi
    echo "Run 'python scripts/validate_lupopedia_headers_universal.py --reject-legacy-fields' for manual validation"
    exit 1
fi

echo "✅ All validation passed (strict-mode header enforcement active)"
exit 0
