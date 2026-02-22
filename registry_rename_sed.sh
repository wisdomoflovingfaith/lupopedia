#!/bin/bash

# Registry Table Renaming Script
# FINAL TABLE RENAMES:
#   unified_registry       → registry
#   unified_unregistry     → registry_open
#   unified_import_registry → registry_import

echo "Starting registry table renaming..."

# Update SQL files
find . -type f -name "*.sql" -exec sed -i 's/lupo_unified_registry/lupo_registry/g' {} \;
find . -type f -name "*.sql" -exec sed -i 's/lupo_unified_unregistry/lupo_registry_open/g' {} \;
find . -type f -name "*.sql" -exec sed -i 's/lupo_unified_import_registry/lupo_registry_import/g' {} \;

# Update PHP files
find . -type f -name "*.php" -exec sed -i 's/lupo_unified_registry/lupo_registry/g' {} \;
find . -type f -name "*.php" -exec sed -i 's/lupo_unified_unregistry/lupo_registry_open/g' {} \;
find . -type f -name "*.php" -exec sed -i 's/lupo_unified_import_registry/lupo_registry_import/g' {} \;

# Update TOON files
find . -type f -name "*.json" -path "*/toons/*" -exec sed -i 's/lupo_unified_registry/lupo_registry/g' {} \;
find . -type f -name "*.json" -path "*/toons/*" -exec sed -i 's/lupo_unified_unregistry/lupo_registry_open/g' {} \;
find . -type f -name "*.json" -path "*/toons/*" -exec sed -i 's/lupo_unified_import_registry/lupo_registry_import/g' {} \;

# Update loader scripts
find . -type f -name "*.py" -exec sed -i 's/lupo_unified_registry/lupo_registry/g' {} \;
find . -type f -name "*.py" -exec sed -i 's/lupo_unified_unregistry/lupo_registry_open/g' {} \;
find . -type f -name "*.py" -exec sed -i 's/lupo_unified_import_registry/lupo_registry_import/g' {} \;

# Update any other files that might contain references
find . -type f \( -name "*.js" -o -name "*.ts" -o -name "*.md" \) -exec sed -i 's/lupo_unified_registry/lupo_registry/g' {} \;
find . -type f \( -name "*.js" -o -name "*.ts" -o -name "*.md" \) -exec sed -i 's/lupo_unified_unregistry/lupo_registry_open/g' {} \;
find . -type f \( -name "*.js" -o -name "*.ts" -o -name "*.md" \) -exec sed -i 's/lupo_unified_import_registry/lupo_registry_import/g' {} \;

echo "Registry table renaming script created successfully!"
echo "Commands to run manually:"
echo "  chmod +x registry_rename_sed.sh"
echo "  ./registry_rename_sed.sh"
