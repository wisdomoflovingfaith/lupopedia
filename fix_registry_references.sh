#!/bin/bash
# Fix all registry_id references to registry_id
# Run this script to clean up legacy column naming

echo "====================================================================="
echo "FIXING registry_id → registry_id IN ALL FILES"
echo "====================================================================="

# Backup critical files first
echo "Creating backups..."
cp install_wizard_classes.php install_wizard_classes.php.backup_registry_fix
cp api/flip-header.php api/flip-header.php.backup_registry_fix
cp install.php install.php.backup_registry_fix

# Fix PHP files
echo "Fixing PHP files..."
find . -type f -name "*.php" -exec sed -i 's/registry_id/registry_id/g' {} +

# Fix Python files  
echo "Fixing Python files..."
find . -type f -name "*.py" -exec sed -i 's/registry_id/registry_id/g' {} +

# Fix SQL files (but NOT install_new_lupopedia.sql which is already correct in schema)
echo "Fixing SQL seed files..."
find ./database/migrations -type f -name "*.sql" ! -name "install_new_lupopedia.sql" -exec sed -i 's/registry_id/registry_id/g' {} +

# Fix documentation (MD files)
echo "Fixing documentation..."
find ./docs -type f -name "*.md" -exec sed -i 's/registry_id/registry_id/g' {} +
find ./messages -type f -name "*.md" -exec sed -i 's/registry_id/registry_id/g' {} +

# Fix root README
sed -i 's/registry_id/registry_id/g' README.md
sed -i 's/registry_id/registry_id/g' CHANGELOG.md

echo "====================================================================="
echo "COMPLETE: All registry_id references replaced with registry_id"
echo "====================================================================="
echo ""
echo "Backups created:"
echo "  - install_wizard_classes.php.backup_registry_fix"
echo "  - api/flip-header.php.backup_registry_fix"
echo "  - install.php.backup_registry_fix"
echo ""
echo "Files modified:"
echo "  - All .php files"
echo "  - All .py files"
echo "  - All .sql files (except install_new_lupopedia.sql)"
echo "  - All .md files"
echo ""
echo "IMPORTANT: Test installation after this change!"
