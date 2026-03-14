#!/bin/sh
# 4.0.67: Regenerate TOON files and report canonical table count.
# Run from repo root before DDL changes or count-sensitive assertions.
# Table-count truth comes from generated TOONs; do not hardcode counts in docs.
#
# Usage: sh scripts/validate_schema_toons.sh [REPO_ROOT]

REPO_ROOT="${1:-.}"
cd "$REPO_ROOT" || exit 1

echo "=== Regenerating TOON files (canonical schema representation) ==="
if ! python lupo-scripts/generate_toon_files.py; then
    echo "TOON generation failed. Fix errors and re-run." 1>&2
    exit 1
fi

TOON_DIR="$REPO_ROOT/lupo-database/lupopedia/toon"
if [ -d "$TOON_DIR" ]; then
    COUNT=$(find "$TOON_DIR" -maxdepth 1 -name "*.toon" -type f 2>/dev/null | wc -l)
    echo ""
    echo "Canonical table count (from TOON files): $COUNT"
    echo "Use this count in docs; do not hardcode."
else
    echo "TOON directory not found: $TOON_DIR" 1>&2
    exit 1
fi
exit 0
