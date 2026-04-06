@echo off
REM Pre-commit hook for Lupopedia validation
REM Runs comprehensive validation before allowing commits

echo 🔍 Running Lupopedia pre-commit validation...

REM Run Python validation script
python lupo-hooks\pre_commit_validate.py

REM Check exit code
if %ERRORLEVEL% neq 0 (
    echo.
    echo ❌ COMMIT BLOCKED
    echo Validation failed. Please fix the issues above before committing.
    echo Run 'python lupo-scripts\validate_lupopedia_headers_universal.py' for manual validation
    exit /b 1
)

echo ✅ All validation passed
exit /b 0
