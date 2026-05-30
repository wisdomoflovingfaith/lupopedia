#!/bin/bash
# Lupopedia Actor Import Script v4.0.48 - Identity Capsule Portability
# Usage: sh scripts/import_actor.sh <archive_path> [--validate]

ARCHIVE_PATH=$1
VALIDATE_FLAG=$2

if [ -z "$ARCHIVE_PATH" ]; then
    echo "Usage: $0 <archive_path> [--validate]"
    echo "  --validate  Validate checksums before import"
    exit 1
fi

if [ ! -f "$ARCHIVE_PATH" ]; then
    echo "Error: Archive file $ARCHIVE_PATH not found"
    exit 1
fi

echo "🚀 Importing Actor Identity Capsule from $ARCHIVE_PATH..."

# Create temporary import directory
TEMP_DIR="temp_import_$(date +%s)"
mkdir -p "$TEMP_DIR"

# Extract archive
echo "📦 Extracting archive..."
tar -xzf "$ARCHIVE_PATH" -C "$TEMP_DIR"

# Find actor directory
ACTOR_DIR=$(find "$TEMP_DIR" -name "actors_*" -type d | head -1)
if [ -z "$ACTOR_DIR" ]; then
    echo "❌ Error: No actor directory found in archive"
    rm -rf "$TEMP_DIR"
    exit 1
fi

# Extract actor ID from directory name
ACTOR_ID=$(echo "$ACTOR_DIR" | sed 's/.*actors_\([0-9]*\).*/\1/')
echo "🎯 Detected Actor ID: $ACTOR_ID"

# Validate checksums if requested
if [ "$VALIDATE_FLAG" = "--validate" ]; then
    echo "🔐 Validating checksums..."
    CHECKSUM_FILE=$(find "$TEMP_DIR" -name "*_checksums.txt" | head -1)
    
    if [ -f "$CHECKSUM_FILE" ]; then
        cd "$TEMP_DIR"
        if sha256sum -c "$CHECKSUM_FILE" > /dev/null 2>&1; then
            echo "✅ Checksum validation passed"
        else
            echo "❌ Checksum validation failed"
            cd ..
            rm -rf "$TEMP_DIR"
            exit 1
        fi
        cd ..
    else
        echo "⚠️  No checksum file found, skipping validation"
    fi
fi

# Validate metadata
METADATA_FILE="$ACTOR_DIR/export_metadata.json"
if [ -f "$METADATA_FILE" ]; then
    echo "📋 Validating export metadata..."
    
    # Check version compatibility
    EXPORT_VERSION=$(php -r "echo json_decode(file_get_contents('$METADATA_FILE'))->export_version;")
    if [ "$EXPORT_VERSION" != "4.0.48" ]; then
        echo "⚠️  Export version $EXPORT_VERSION may not be compatible with current system"
    fi
    
    # Verify actor ID matches
    METADATA_ACTOR_ID=$(php -r "echo json_decode(file_get_contents('$METADATA_FILE'))->actor_id;")
    if [ "$METADATA_ACTOR_ID" != "$ACTOR_ID" ]; then
        echo "❌ Actor ID mismatch: metadata says $METADATA_ACTOR_ID, directory suggests $ACTOR_ID"
        rm -rf "$TEMP_DIR"
        exit 1
    fi
    
    echo "✅ Metadata validation passed"
else
    echo "⚠️  No metadata file found, proceeding with import"
fi

# Backup existing actor directory if it exists
if [ -d "actors/$ACTOR_ID" ]; then
    echo "💾 Backing up existing actor directory..."
    BACKUP_DIR="backups/actor_${ACTOR_ID}_$(date +%Y%m%d_%H%M%S)"
    mkdir -p backups
    cp -r "actors/$ACTOR_ID" "$BACKUP_DIR"
    echo "📦 Backup created: $BACKUP_DIR"
fi

# Import actor directory
echo "📁 Importing actor directory structure..."
cp -r "$ACTOR_DIR" "actors/$ACTOR_ID"

# Validate WHO.json
if [ -f "actors/$ACTOR_ID/WHO.json" ]; then
    echo "🔍 Validating WHO.json..."
    if php -r "echo json_decode(file_get_contents('actors/$ACTOR_ID/WHO.json')) ? 'valid' : 'invalid';" | grep -q "valid"; then
        echo "✅ WHO.json is valid"
    else
        echo "❌ WHO.json is invalid"
        rm -rf "$TEMP_DIR"
        exit 1
    fi
else
    echo "⚠️  No WHO.json found"
fi

# Run database sync
echo "🗄️ Syncing with database..."
if php scripts/sync_actors_to_db.php -a "$ACTOR_ID" > /dev/null 2>&1; then
    echo "✅ Database sync completed"
else
    echo "❌ Database sync failed"
    echo "🔧 Manual sync may be required: php scripts/sync_actors_to_db.php -a $ACTOR_ID"
fi

# Validate database records
echo "🔍 Validating database records..."
DB_ACTOR_FILE="$ACTOR_DIR/db_actor_record.json"
if [ -f "$DB_ACTOR_FILE" ]; then
    php -r "
require_once('includes/bootstrap.php');
try {
    \$db = DatabaseFactory::getConnection();
    \$stmt = \$db->prepare('SELECT * FROM lupo_actors WHERE actor_id = ?');
    \$stmt->execute(array($ACTOR_ID));
    \$current = \$stmt->fetch(PDO::FETCH_ASSOC);
    
    if (\$current) {
        echo '✅ Actor record found in database\n';
    } else {
        echo '❌ Actor record not found in database\n';
    }
} catch (Exception \$e) {
    echo '❌ Database validation failed: ' . \$e->getMessage() . '\n';
}
"
else
    echo "⚠️  No database validation records found"
fi

# Set proper permissions
echo "🔐 Setting permissions..."
chmod -R 755 "actors/$ACTOR_ID"
find "actors/$ACTOR_ID" -name "*.json" -exec chmod 644 {} \;
find "actors/$ACTOR_ID" -name "*.sh" -exec chmod 755 {} \;

# Cleanup
rm -rf "$TEMP_DIR"

echo "✅ Import complete!"
echo "🎯 Actor $ACTOR_ID successfully imported"
echo ""
echo "📋 Post-Import Checklist:"
echo "  □ Verify WHO.json contains correct identity"
echo "  □ Check database sync status"
echo "  □ Test actor capabilities"
echo "  □ Review imported history records"
echo ""
echo "🚀 Actor $ACTOR_ID Identity Capsule is now active!"
