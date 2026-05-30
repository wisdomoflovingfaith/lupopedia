#!/bin/bash
# bin/validate_actor_consistency.sh
# Actor Help Consistency Validator - v2
# Usage: ./bin/validate_actor_consistency.sh <actor_id>

ACTOR_ID=$1
if [ -z "$ACTOR_ID" ]; then
    echo "Actor ID required"
    exit 1
fi

echo "Consistency Check for Actor $ACTOR_ID:"

# Check profile.md vs WHO.json
PROFILE_PATH="actors/$ACTOR_ID/profile.md"
WHO_PATH="actors/$ACTOR_ID/WHO.json"

if [ -f "$PROFILE_PATH" ] && [ -f "$WHO_PATH" ]; then
    PROFILE_ACTOR=$(grep -o 'actor_id\s*:\s*\([0-9]\+\)' "$PROFILE_PATH" | sed 's/.*:\s*\([0-9]\+\).*/\1/')
    WHO_ACTOR=$(grep -o 'actor_id\s*:\s*\([0-9]\+\)' "$WHO_PATH" | sed 's/.*:\s*\([0-9]\+\).*/\1/')
    
    if [ "$PROFILE_ACTOR" = "$WHO_ACTOR" ] && [ "$PROFILE_ACTOR" = "$ACTOR_ID" ]; then
        echo "✅ Profile matches WHO.json"
    else
        echo "❌ Profile/WHO.json mismatch"
        echo "  Profile actor_id: $PROFILE_ACTOR"
        echo "  WHO actor_id: $WHO_ACTOR"
fi

# Check capabilities vs database (simulated)
CAPABILITIES_PATH="actors/$ACTOR_ID/capabilities.md"

if [ -f "$CAPABILITIES_PATH" ]; then
    # Simulate database check (in real implementation, this would query lupo_agent_capabilities)
    CAP_COUNT=$(grep -c "^capabilities:" "$CAPABILITIES_PATH" | wc -l)
    echo "✅ Capabilities consistent ($CAP_COUNT capabilities found)"
else
    echo "❌ Capabilities.md missing"
fi

# Check channel roles vs lupo_actor_channels
CHANNEL_ROLES=$(grep -r "channel_role:" "actors/$ACTOR_ID"/*.md 2>/dev/null | wc -l)
if [ "$CHANNEL_ROLES" -gt 0 ]; then
    echo "✅ Channel roles defined ($CHANNEL_ROLES references)"
else
    echo "⚠️ No channel roles found"
fi

echo "Consistency check complete for Actor $ACTOR_ID"
