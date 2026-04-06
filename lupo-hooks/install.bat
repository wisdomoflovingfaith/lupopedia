@echo off
REM Install Lupopedia pre-commit hook for Windows

echo 🔧 Installing Lupopedia pre-commit hook...

REM Check if we're in a git repository
if not exist ".git" (
    echo ❌ Error: Not in a Git repository
    exit /b 1
)

REM Copy hook file
copy "lupo-hooks\pre-commit.bat" ".git\hooks\pre-commit.bat" >nul

echo ✅ Pre-commit hook installed successfully
echo 📝 Hook will validate headers before each commit
echo 🚫 To bypass temporarily: git commit --no-verify
