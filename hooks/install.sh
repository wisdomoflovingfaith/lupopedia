#!/bin/bash
# Install Lupopedia pre-commit hook

echo "🔧 Installing Lupopedia pre-commit hook..."

# Check if we're in a git repository
if [ ! -d ".git" ]; then
    echo "❌ Error: Not in a Git repository"
    exit 1
fi

# Copy hook file
cp hooks/pre-commit.sh .git/hooks/pre-commit

# Make executable
chmod +x .git/hooks/pre-commit

echo "✅ Pre-commit hook installed successfully"
echo "📝 Hook will validate headers before each commit"
echo "🚫 To bypass temporarily: git commit --no-verify"
