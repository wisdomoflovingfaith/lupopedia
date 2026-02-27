#!/bin/bash
# Lupopedia Actor Import Script v4.0.47
# Usage: sh scripts/import_actor.sh <actor_id>

ACTOR_ID=$1

if [ -z "$ACTOR_ID" ]; then
    echo "Usage: $0 <actor_id>"
    exit 1
fi

if [ ! -d "actors/$ACTOR_ID" ]; then
    echo "Error: Directory actors/$ACTOR_ID not found."
    exit 1
fi

echo "Importing data for actor $ACTOR_ID into database..."

# Example of parsing profile.json with jq
if [ -f "actors/$ACTOR_ID/profile.json" ]; then
    NAME=$(cat actors/$ACTOR_ID/profile.json | jq -r '.name')
    echo "Updating profile for $NAME ($ACTOR_ID)..."
    # Placeholder for DB upsert
    # php bin/upsert_actor.php --id=$ACTOR_ID --data="$(cat actors/$ACTOR_ID/profile.json)"
fi

# Example of importing logs (NDJSON)
if [ -f "actors/$ACTOR_ID/logs/activity.ndjson" ]; then
    echo "Importing activity logs..."
    # Placeholder for log import
    # cat actors/$ACTOR_ID/logs/activity.ndjson | while read line; do 
    #   php bin/import_log.php "$line"
    # done
fi

echo "Import complete for actor $ACTOR_ID."
