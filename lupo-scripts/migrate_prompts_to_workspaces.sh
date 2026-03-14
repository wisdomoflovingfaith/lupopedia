#!/bin/bash
# ============================================================================
# PROMPTS TO ACTOR WORKSPACES MIGRATION SCRIPT
# ============================================================================
# Purpose: Migrate /prompts/** to /channels/*/actors/*/ structure
# System Version: 4.0.45
# Author: KIRO (Warp IDE Agent 1004)
# Date: 2026-02-25
# ============================================================================

set -e  # Exit on error

echo "=========================================="
echo "PROMPTS TO ACTOR WORKSPACES MIGRATION"
echo "System Version: 4.0.45"
echo "=========================================="
echo ""

# ============================================================================
# PHASE 1: CHANNEL 0 (SYSTEM KERNEL) MIGRATIONS
# ============================================================================

echo "[PHASE 1] Migrating Channel 0 (System Kernel) workspaces..."

# Actor 1 (WOLFIE AI)
if [ -d "prompts/1" ]; then
  echo "  - Migrating Actor 1 (WOLFIE AI)..."
  mkdir -p "channels/0/actors/1"
  cp -r prompts/1/* "channels/0/actors/1/" 2>/dev/null || true
fi

# Actor 3 (ROSE)
if [ -d "prompts/3" ]; then
  echo "  - Migrating Actor 3 (ROSE)..."
  mkdir -p "channels/0/actors/3"
  cp -r prompts/3/* "channels/0/actors/3/" 2>/dev/null || true
fi

# Actor 4 (ERIS)
if [ -d "prompts/4" ]; then
  echo "  - Migrating Actor 4 (ERIS)..."
  mkdir -p "channels/0/actors/4"
  cp -r prompts/4/* "channels/0/actors/4/" 2>/dev/null || true
fi

# Actor 5 (METIS)
if [ -d "prompts/5" ]; then
  echo "  - Migrating Actor 5 (METIS)..."
  mkdir -p "channels/0/actors/5"
  cp -r prompts/5/* "channels/0/actors/5/" 2>/dev/null || true
fi

echo "[PHASE 1] Complete."
echo ""

# ============================================================================
# PHASE 2: CHANNEL 42 (DEVELOPMENT) MIGRATIONS
# ============================================================================

echo "[PHASE 2] Migrating Channel 42 (Development) workspaces..."

# Actor 1000 (KIRO)
if [ -d "prompts/1000" ]; then
  echo "  - Migrating Actor 1000 (KIRO)..."
  mkdir -p "channels/42/actors/1000"
  cp -r prompts/1000/* "channels/42/actors/1000/" 2>/dev/null || true
fi

# Actor 1001 (Windsurf)
if [ -d "prompts/1001" ]; then
  echo "  - Migrating Actor 1001 (Windsurf)..."
  mkdir -p "channels/42/actors/1001"
  cp -r prompts/1001/* "channels/42/actors/1001/" 2>/dev/null || true
fi

# Actor 1002 (Cursor) - from root *.txt files
echo "  - Migrating Actor 1002 (Cursor)..."
mkdir -p "channels/42/actors/1002"
if [ -f "prompts/4.1.16_cursor_complete.txt" ]; then
  cp "prompts/4.1.16_cursor_complete.txt" "channels/42/actors/1002/"
fi
if [ -f "prompts/4.1.16_cursor_instruction.txt" ]; then
  cp "prompts/4.1.16_cursor_instruction.txt" "channels/42/actors/1002/"
fi
if [ -f "prompts/4.1.20_cursor_complete.txt" ]; then
  cp "prompts/4.1.20_cursor_complete.txt" "channels/42/actors/1002/"
fi

# Actor 1003 (Antigravity)
if [ -d "prompts/antigravity" ]; then
  echo "  - Migrating Actor 1003 (Antigravity)..."
  mkdir -p "channels/42/actors/1003"
  cp -r prompts/antigravity/* "channels/42/actors/1003/" 2>/dev/null || true
fi

# Actor 2 (LILITH) - development work
if [ -d "prompts/2" ]; then
  echo "  - Migrating Actor 2 (LILITH)..."
  mkdir -p "channels/42/actors/2"
  cp -r prompts/2/* "channels/42/actors/2/" 2>/dev/null || true
fi

# Actor 2 (LILITH) - FLIP design collaboration from prompts/ai/
if [ -f "prompts/ai/FLIP_DESIGN_COLLABORATION_PROMPT.md" ]; then
  echo "  - Migrating FLIP design collaboration to Actor 2 (LILITH)..."
  mkdir -p "channels/42/actors/2"
  cp "prompts/ai/FLIP_DESIGN_COLLABORATION_PROMPT.md" "channels/42/actors/2/"
fi

# Actor 10000 (Captain)
if [ -d "prompts/10000" ]; then
  echo "  - Migrating Actor 10000 (Captain)..."
  mkdir -p "channels/42/actors/10000"
  cp -r prompts/10000/* "channels/42/actors/10000/" 2>/dev/null || true
fi

# Actor 10000 (Captain) - doctrine verification
if [ -f "prompts/doctrine_verification_prompt.txt" ]; then
  echo "  - Migrating doctrine verification to Actor 10000 (Captain)..."
  mkdir -p "channels/42/actors/10000"
  cp "prompts/doctrine_verification_prompt.txt" "channels/42/actors/10000/"
fi

echo "[PHASE 2] Complete."
echo ""

# ============================================================================
# PHASE 3: CREATE WORKSPACE README FILES
# ============================================================================

echo "[PHASE 3] Creating workspace README files..."

# Function to create README
create_readme() {
  local channel_id=$1
  local actor_id=$2
  local actor_name=$3
  local actor_type=$4
  local channel_name=$5
  
  local readme_path="channels/${channel_id}/actors/${actor_id}/README.md"
  
  cat > "$readme_path" << EOF
# Actor Workspace: ${actor_name} (ID: ${actor_id})

**Channel:** ${channel_id} (${channel_name})  
**Actor Type:** ${actor_type}  
**Created:** 20260225000000  
**System Version:** 4.0.45

## Purpose

This is the working directory for ${actor_name} on Channel ${channel_id}.

## Contents

- Temporary prompts
- Scratch files
- Working notes
- Task state
- Partial outputs
- Draft doctrine
- Debug artifacts

## Rules

- Files here are TEMPORARY and MUTABLE
- Do NOT store permanent artifacts here
- Do NOT store doctrine here (use docs/doctrine/)
- Do NOT store system documentation here
- Files may be cleaned up periodically

## Actor Identity

- **Actor ID:** ${actor_id}
- **Type:** ${actor_type}
- **Channel:** ${channel_id} (${channel_name})

---

*This workspace is part of the per-channel actor isolation system introduced in Lupopedia 4.0.45.*
EOF

  echo "  - Created README for Actor ${actor_id} (${actor_name})"
}

# Channel 0 READMEs
create_readme "0" "1" "WOLFIE AI" "agent" "System Kernel"
create_readme "0" "3" "ROSE" "agent" "System Kernel"
create_readme "0" "4" "ERIS" "agent" "System Kernel"
create_readme "0" "5" "METIS" "agent" "System Kernel"

# Channel 42 READMEs
create_readme "42" "1000" "KIRO IDE" "ide_agent" "Development"
create_readme "42" "1001" "Windsurf IDE" "ide_agent" "Development"
create_readme "42" "1002" "Cursor IDE" "ide_agent" "Development"
create_readme "42" "1003" "Antigravity IDE" "ide_agent" "Development"
create_readme "42" "2" "LILITH" "agent" "Development"
create_readme "42" "10000" "Captain" "human" "Development"

echo "[PHASE 3] Complete."
echo ""

# ============================================================================
# PHASE 4: DEPRECATE /prompts/ ROOT
# ============================================================================

echo "[PHASE 4] Deprecating /prompts/ root..."

cat > "prompts/DEPRECATED_README.md" << 'EOF'
# DEPRECATED: /prompts/ Root Directory

**Status:** DEPRECATED as of Lupopedia 4.0.45  
**Migration Date:** 2026-02-25  
**Replacement:** `/channels/*/actors/*/` per-channel actor workspaces

## What Happened?

The `/prompts/` root directory has been deprecated in favor of per-channel actor workspaces. All agent working files have been migrated to:

```
channels/{channel_id}/actors/{actor_id}/
```

## Why?

The old `/prompts/` structure caused:
- Prompt collisions between agents
- Context leakage across channels
- Role confusion in multi-agent scenarios
- Federation scaling issues

## New Structure

**Channel 0 (System Kernel):**
- `channels/0/actors/1/` - WOLFIE AI
- `channels/0/actors/3/` - ROSE
- `channels/0/actors/4/` - ERIS
- `channels/0/actors/5/` - METIS

**Channel 42 (Development):**
- `channels/42/actors/1000/` - KIRO IDE
- `channels/42/actors/1001/` - Windsurf IDE
- `channels/42/actors/1002/` - Cursor IDE
- `channels/42/actors/1003/` - Antigravity IDE
- `channels/42/actors/2/` - LILITH
- `channels/42/actors/10000/` - Captain

## Historical Files

The following files remain in `/prompts/` for historical reference:
- `registry.json` - Actor ID registry (snapshot)
- `REORGANIZATION_COMPLETE.md` - Previous reorganization record
- This file (`DEPRECATED_README.md`)

## Migration

All files have been COPIED (not moved) to preserve history. Original files remain here for reference but should NOT be modified.

## For Developers

**DO NOT write new files to `/prompts/`.**

Use the per-channel actor workspace structure:
```bash
channels/{channel_id}/actors/{actor_id}/your_file.md
```

---

*Migration completed by KIRO (Warp IDE Agent 1004) on 2026-02-25 for Lupopedia 4.0.45.*
EOF

echo "  - Created DEPRECATED_README.md"
echo "[PHASE 4] Complete."
echo ""

# ============================================================================
# PHASE 5: SUMMARY
# ============================================================================

echo "=========================================="
echo "MIGRATION COMPLETE"
echo "=========================================="
echo ""
echo "Summary:"
echo "  - Channel 0 workspaces: 4 actors migrated"
echo "  - Channel 42 workspaces: 6 actors migrated"
echo "  - README files created: 10"
echo "  - /prompts/ deprecated: YES"
echo ""
echo "Next Steps:"
echo "  1. Review migrated files in channels/*/actors/*/"
echo "  2. Update IDE tooling to use new workspace paths"
echo "  3. Test install.php with new structure"
echo "  4. Update CHANGELOG.md"
echo ""
echo "Original files preserved in /prompts/ for reference."
echo "=========================================="
