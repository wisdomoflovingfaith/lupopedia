@echo off
REM Pre-commit hook to validate architect field in documentation headers
REM Blocks commits if files referencing Captain Wolfie lack architect field

echo 🔍 Validating architect field in documentation headers...

REM Run validation script
php scripts/validate_doc_headers.php

REM Check exit code
if %ERRORLEVEL% neq 0 (
    echo.
    echo ❌ COMMIT BLOCKED
    echo Documentation files referencing Captain Wolfie must include 'architect: Captain Wolfie' field
    echo Run: php scripts/add_architect_to_docs.php to fix missing fields
    exit /b 1
)

echo ✅ Architect field validation passed
exit /b 0
