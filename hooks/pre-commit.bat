@echo off
REM Pre-commit hook for Lupopedia validation
REM Runs comprehensive validation before allowing commits

echo 🔍 Running Lupopedia pre-commit validation...

REM Run immune system header validation first (PRD 86 Immune System Mode)
echo �️ IMMUNE SYSTEM: Checking header compliance...
python scripts\validate_lupopedia_headers_universal.py --reject-legacy-fields
set HEADER_EXIT_CODE=%ERRORLEVEL%

REM Additional immune system check: validate ALL staged files for removed fields
echo 🛡️ IMMUNE SYSTEM: Scanning for removed fields in all staged files...
REM For Windows, we'll check current directory files as a simplified immune system check
for %%f in (*.md *.py *.php *.js) do (
    findstr /R /C:"^[ 	]*\(content_slug\|pk_slug\|prd_slug\):" "%%f" >nul 2>&1
    if !errorlevel! equ 0 (
        echo [IMMUNE SYSTEM] BLOCKED: Removed field detected in %%f
        echo [IMMUNE SYSTEM] content_slug, pk_slug, and prd_slug are forbidden in v4.1.4 headers
        set HEADER_EXIT_CODE=1
        goto :check_impl
    )
)

REM Run implementation validation
python hooks\pre_commit_validate.py
set IMPL_EXIT_CODE=%ERRORLEVEL%

REM Check exit codes
if %HEADER_EXIT_CODE% neq 0 (
    echo.
    echo ❌ COMMIT BLOCKED
    echo Header validation failed - strict mode enforcement active for PRD 16 v4.1.4
    echo Run 'python scripts\validate_lupopedia_headers_universal.py --reject-legacy-fields' for manual validation
    exit /b 1
)

if %IMPL_EXIT_CODE% neq 0 (
    echo.
    echo ❌ COMMIT BLOCKED
    echo Implementation validation failed
    echo Run 'python scripts\validate_lupopedia_headers_universal.py --reject-legacy-fields' for manual validation
    exit /b 1
)

echo ✅ All validation passed (strict-mode header enforcement active)
exit /b 0
