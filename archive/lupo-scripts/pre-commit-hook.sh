#!/bin/bash
# Pre-commit hook to validate architect field in documentation headers
# Blocks commits if files referencing Captain Wolfie lack architect field

echo "🔍 Validating architect field in documentation headers..."

# Run validation script
php scripts/validate_doc_headers.php

# Check exit code
if [ $? -ne 0 ]; then
    echo ""
    echo "❌ COMMIT BLOCKED"
    echo "Documentation files referencing Captain Wolfie must include 'architect: Captain Wolfie' field"
    echo "Run: php scripts/add_architect_to_docs.php to fix missing fields"
    exit 1
fi

echo "✅ Architect field validation passed"
exit 0
