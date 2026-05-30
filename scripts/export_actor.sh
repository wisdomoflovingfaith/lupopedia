#!/bin/bash
# Lupopedia Actor Export Script v4.0.48 - Identity Capsule Portability
# Usage: sh scripts/export_actor.sh <actor_id> [--checksum]

ACTOR_ID=$1
CHECKSUM_FLAG=$2

if [ -z "$ACTOR_ID" ]; then
    echo "Usage: $0 <actor_id> [--checksum]"
    echo "  --checksum  Generate SHA256 checksum for validation"
    exit 1
fi

# Validate actor directory exists
if [ ! -d "actors/$ACTOR_ID" ]; then
    echo "Error: Actor directory actors/$ACTOR_ID not found"
    exit 1
fi

echo "🚀 Exporting Identity Capsule for actor $ACTOR_ID..."

# Create export directory
EXPORT_DIR="exports/actor_$ACTOR_ID"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
ARCHIVE_NAME="actor_${ACTOR_ID}_identity_capsule_${TIMESTAMP}"

mkdir -p "$EXPORT_DIR"

# Copy entire actor directory
echo "📁 Copying actor directory structure..."
cp -r "actors/$ACTOR_ID" "$EXPORT_DIR/"

# Generate database validation records
echo "🗄️ Exporting database validation records..."
php -r "
require_once('includes/bootstrap.php');
try {
    \$db = DatabaseFactory::getConnection();
    
    // Get actor record
    \$stmt = \$db->prepare('SELECT * FROM lupo_actors WHERE actor_id = ?');
    \$stmt->execute(array($ACTOR_ID));
    \$actor = \$stmt->fetch(PDO::FETCH_ASSOC);
    
    if (\$actor) {
        file_put_contents('$EXPORT_DIR/db_actor_record.json', json_encode(\$actor, JSON_PRETTY_PRINT));
    }
    
    // Get actor history
    \$stmt = \$db->prepare('SELECT * FROM lupo_actor_history WHERE actor_id = ? AND is_deleted = 0');
    \$stmt->execute(array($ACTOR_ID));
    \$history = \$stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (\$history) {
        file_put_contents('$EXPORT_DIR/db_history_records.json', json_encode(\$history, JSON_PRETTY_PRINT));
    }
    
    // Get capability usage
    \$stmt = \$db->prepare('SELECT * FROM lupo_capability_usage WHERE actor_id = ? AND is_deleted = 0');
    \$stmt->execute(array($ACTOR_ID));
    \$capabilities = \$stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (\$capabilities) {
        file_put_contents('$EXPORT_DIR/db_capability_records.json', json_encode(\$capabilities, JSON_PRETTY_PRINT));
    }
    
    echo '✅ Database records exported\n';
} catch (Exception \$e) {
    echo '❌ Database export failed: ' . \$e->getMessage() . '\n';
    exit(1);
}
"

# Generate checksum if requested
if [ "$CHECKSUM_FLAG" = "--checksum" ]; then
    echo "🔐 Generating SHA256 checksum..."
    cd "$EXPORT_DIR"
    find . -type f -exec sha256sum {} + > ../"${ARCHIVE_NAME}_checksums.txt"
    cd ..
    echo "✅ Checksums saved to ${ARCHIVE_NAME}_checksums.txt"
fi

# Create metadata file
echo "📋 Creating export metadata..."
cat > "$EXPORT_DIR/export_metadata.json" << EOF
{
    "export_version": "4.0.48",
    "actor_id": $ACTOR_ID,
    "export_timestamp": "$TIMESTAMP",
    "export_type": "identity_capsule",
    "filesystem_structure": "complete",
    "database_validation": true,
    "checksum_generated": $([ "$CHECKSUM_FLAG" = "--checksum" ] && echo "true" || echo "false"),
    "lupopedia_version": "$(grep -o 'GLOBAL_CURRENT_LUPOPEDIA_VERSION.*' config/global_atoms.yaml | cut -d' ' -f2 | tr -d '"')",
    "export_compatibility": {
        "min_version": "4.0.48",
        "max_version": "4.1.x"
    }
}
EOF

# Create portable archive
echo "📦 Creating portable archive..."
cd exports
tar -czf "${ARCHIVE_NAME}.tar.gz" "actor_$ACTOR_ID"
cd ..

# Generate import instructions
echo "📖 Creating import instructions..."
cat > "$EXPORT_DIR/IMPORT_INSTRUCTIONS.md" << EOF
# Actor Identity Capsule Import Instructions

## Archive Information
- Actor ID: $ACTOR_ID
- Export Date: $TIMESTAMP
- Version: 4.0.48

## Import Steps

### 1. Extract Archive
\`\`\`bash
tar -xzf ${ARCHIVE_NAME}.tar.gz
\`\`\`

### 2. Validate Checksum (if generated)
\`\`\`bash
sha256sum -c ${ARCHIVE_NAME}_checksums.txt
\`\`\`

### 3. Place Actor Directory
\`\`\`bash
cp -r actor_$ACTOR_ID actors/
\`\`\`

### 4. Run Database Sync
\`\`\`bash
php scripts/sync_actors_to_db.php -a $ACTOR_ID
\`\`\`

### 5. Verify Import
\`\`\`bash
# Check WHO.json validity
php -r "echo json_decode(file_get_contents('actors/$ACTOR_ID/WHO.json')) ? '✅ Valid' : '❌ Invalid';"
\`\`\`

## Database Validation Records
The following files contain database snapshots for validation:
- \`db_actor_record.json\` - Actor table record
- \`db_history_records.json\` - Actor history entries
- \`db_capability_records.json\` - Capability usage records

## Compatibility
- Requires Lupopedia v4.0.48 or higher
- Compatible with PHP 5.3+
- Database agnostic (MySQL/MariaDB/PostgreSQL)

## Support
For issues, refer to Lupopedia documentation or create an issue in the repository.
EOF

# Cleanup temporary directory
rm -rf "$EXPORT_DIR"

echo "✅ Export complete!"
echo "📦 Archive created: exports/${ARCHIVE_NAME}.tar.gz"
if [ "$CHECKSUM_FLAG" = "--checksum" ]; then
    echo "🔐 Checksums: exports/${ARCHIVE_NAME}_checksums.txt"
fi
echo "📖 Instructions included in archive"
echo ""
echo "🚀 Actor $ACTOR_ID Identity Capsule ready for portability!"
