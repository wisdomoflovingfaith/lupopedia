#!/bin/bash
# Pre-commit hook for Lupopedia validation
# Runs comprehensive validation before allowing commits

echo "🔍 Running Lupopedia pre-commit validation..."

# Run Python validation script
python lupo-hooks/pre_commit_validate.py

# Check exit code
if [ $? -ne 0 ]; then
    echo ""
    echo "❌ COMMIT BLOCKED"
    echo "Validation failed. Please fix the issues above before committing."
    echo "Run 'python lupo-scripts/validate_lupopedia_headers_universal.py' for manual validation"
    exit 1
fi

echo "✅ All validation passed"
exit 0
