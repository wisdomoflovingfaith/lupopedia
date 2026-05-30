@echo off
setlocal EnableDelayedExpansion
REM Pre-commit hook for Lupopedia validation
REM Runs comprehensive validation before allowing commits

echo Running Lupopedia pre-commit validation...
echo.
echo [IMMUNE SYSTEM] Checking header compliance...
python scripts\validate_lupopedia_headers_universal.py --strict .
set HEADER_EXIT_CODE=!ERRORLEVEL!

if !HEADER_EXIT_CODE! neq 0 (
    echo.
    echo COMMIT BLOCKED
    echo Header validation failed - strict mode enforcement active for PRD 16 v4.1.4
    echo Run: python scripts\validate_lupopedia_headers_universal.py --strict .
    exit /b 1
)

echo.
echo Running implementation validation...
python hooks\pre_commit_validate.py
set IMPL_EXIT_CODE=!ERRORLEVEL!

if !IMPL_EXIT_CODE! neq 0 (
    echo.
    echo COMMIT BLOCKED
    echo Implementation validation failed
    exit /b 1
)

echo.
echo All validation passed
exit /b 0
