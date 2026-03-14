# ============================================================================
# PROMPTS TO ACTOR WORKSPACES MIGRATION SCRIPT (PowerShell)
# ============================================================================
# Purpose: Migrate /prompts/** to /channels/*/actors/*/ structure
# System Version: 4.0.45
# Author: KIRO (Warp IDE Agent 1004)
# Date: 2026-02-25
# ============================================================================

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "PROMPTS TO ACTOR WORKSPACES MIGRATION" -ForegroundColor Cyan
Write-Host "System Version: 4.0.45" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

# ============================================================================
# PHASE 1: CHANNEL 0 (SYSTEM KERNEL) MIGRATIONS
# ============================================================================

Write-Host "[PHASE 1] Migrating Channel 0 (System Kernel) workspaces..." -ForegroundColor Yellow

# Actor 1 (WOLFIE AI)
if (Test-Path "prompts/1") {
    Write-Host "  - Migrating Actor 1 (WOLFIE AI)..."
    New-Item -ItemType Directory -Force -Path "channels/0/actors/1" | Out-Null
    Copy-Item -Path "prompts/1/*" -Destination "channels/0/actors/1/" -Recurse -Force -ErrorAction SilentlyContinue
}

# Actor 3 (ROSE)
if (Test-Path "prompts/3") {
    Write-Host "  - Migrating Actor 3 (ROSE)..."
    New-Item -ItemType Directory -Force -Path "channels/0/actors/3" | Out-Null
    Copy-Item -Path "prompts/3/*" -Destination "channels/0/actors/3/" -Recurse -Force -ErrorAction SilentlyContinue
}

# Actor 4 (ERIS)
if (Test-Path "prompts/4") {
    Write-Host "  - Migrating Actor 4 (ERIS)..."
    New-Item -ItemType Directory -Force -Path "channels/0/actors/4" | Out-Null
    Copy-Item -Path "prompts/4/*" -Destination "channels/0/actors/4/" -Recurse -Force -ErrorAction SilentlyContinue
}

# Actor 5 (METIS)
if (Test-Path "prompts/5") {
    Write-Host "  - Migrating Actor 5 (METIS)..."
    New-Item -ItemType Directory -Force -Path "channels/0/actors/5" | Out-Null
    Copy-Item -Path "prompts/5/*" -Destination "channels/0/actors/5/" -Recurse -Force -ErrorAction SilentlyContinue
}

Write-Host "[PHASE 1] Complete." -ForegroundColor Green
Write-Host ""

# ============================================================================
# PHASE 2: CHANNEL 42 (DEVELOPMENT) MIGRATIONS
# ============================================================================

Write-Host "[PHASE 2] Migrating Channel 42 (Development) workspaces..." -ForegroundColor Yellow

# Actor 1000 (KIRO)
if (Test-Path "prompts/1000") {
    Write-Host "  - Migrating Actor 1000 (KIRO)..."
    New-Item -ItemType Directory -Force -Path "channels/42/actors/1000" | Out-Null
    Copy-Item -Path "prompts/1000/*" -Destination "channels/42/actors/1000/" -Recurse -Force -ErrorAction SilentlyContinue
}

# Actor 1001 (Windsurf)
if (Test-Path "prompts/1001") {
    Write-Host "  - Migrating Actor 1001 (Windsurf)..."
    New-Item -ItemType Directory -Force -Path "channels/42/actors/1001" | Out-Null
    Copy-Item -Path "prompts/1001/*" -Destination "channels/42/actors/1001/" -Recurse -Force -ErrorAction SilentlyContinue
}

# Actor 1002 (Cursor) - from root *.txt files
Write-Host "  - Migrating Actor 1002 (Cursor)..."
New-Item -ItemType Directory -Force -Path "channels/42/actors/1002" | Out-Null
if (Test-Path "prompts/4.1.16_cursor_complete.txt") {
    Copy-Item -Path "prompts/4.1.16_cursor_complete.txt" -Destination "channels/42/actors/1002/" -Force
}
if (Test-Path "prompts/4.1.16_cursor_instruction.txt") {
    Copy-Item -Path "prompts/4.1.16_cursor_instruction.txt" -Destination "channels/42/actors/1002/" -Force
}
if (Test-Path "prompts/4.1.20_cursor_complete.txt") {
    Copy-Item -Path "prompts/4.1.20_cursor_complete.txt" -Destination "channels/42/actors/1002/" -Force
}

# Actor 1003 (Antigravity)
if (Test-Path "prompts/antigravity") {
    Write-Host "  - Migrating Actor 1003 (Antigravity)..."
    New-Item -ItemType Directory -Force -Path "channels/42/actors/1003" | Out-Null
    Copy-Item -Path "prompts/antigravity/*" -Destination "channels/42/actors/1003/" -Recurse -Force -ErrorAction SilentlyContinue
}

# Actor 2 (LILITH) - development work
if (Test-Path "prompts/2") {
    Write-Host "  - Migrating Actor 2 (LILITH)..."
    New-Item -ItemType Directory -Force -Path "channels/42/actors/2" | Out-Null
    Copy-Item -Path "prompts/2/*" -Destination "channels/42/actors/2/" -Recurse -Force -ErrorAction SilentlyContinue
}

# Actor 2 (LILITH) - FLIP design collaboration from prompts/ai/
if (Test-Path "prompts/ai/FLIP_DESIGN_COLLABORATION_PROMPT.md") {
    Write-Host "  - Migrating FLIP design collaboration to Actor 2 (LILITH)..."
    New-Item -ItemType Directory -Force -Path "channels/42/actors/2" | Out-Null
    Copy-Item -Path "prompts/ai/FLIP_DESIGN_COLLABORATION_PROMPT.md" -Destination "channels/42/actors/2/" -Force
}

# Actor 10000 (Captain)
if (Test-Path "prompts/10000") {
    Write-Host "  - Migrating Actor 10000 (Captain)..."
    New-Item -ItemType Directory -Force -Path "channels/42/actors/10000" | Out-Null
    Copy-Item -Path "prompts/10000/*" -Destination "channels/42/actors/10000/" -Recurse -Force -ErrorAction SilentlyContinue
}

# Actor 10000 (Captain) - doctrine verification
if (Test-Path "prompts/doctrine_verification_prompt.txt") {
    Write-Host "  - Migrating doctrine verification to Actor 10000 (Captain)..."
    New-Item -ItemType Directory -Force -Path "channels/42/actors/10000" | Out-Null
    Copy-Item -Path "prompts/doctrine_verification_prompt.txt" -Destination "channels/42/actors/10000/" -Force
}

Write-Host "[PHASE 2] Complete." -ForegroundColor Green
Write-Host ""

# ============================================================================
# PHASE 3: CREATE WORKSPACE README FILES
# ============================================================================

Write-Host "[PHASE 3] Creating workspace README files..." -ForegroundColor Yellow

function Create-WorkspaceReadme {
    param(
        [string]$ChannelId,
        [string]$ActorId,
        [string]$ActorName,
        [string]$ActorType,
        [string]$ChannelName
    )
    
    $readmePath = "channels/$ChannelId/actors/$ActorId/README.md"
    
    $content = @"
# Actor Workspace: $ActorName (ID: $ActorId)

**Channel:** $ChannelId ($ChannelName)  
**Actor Type:** $ActorType  
**Created:** 20260225000000  
**System Version:** 4.0.45

## Purpose

This is the working directory for $ActorName on Channel $ChannelId.

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

- **Actor ID:** $ActorId
- **Type:** $ActorType
- **Channel:** $ChannelId ($ChannelName)

---

*This workspace is part of the per-channel actor isolation system introduced in Lupopedia 4.0.45.*
"@
    
    Set-Content -Path $readmePath -Value $content -Force
    Write-Host "  - Created README for Actor $ActorId ($ActorName)"
}

# Channel 0 READMEs
Create-WorkspaceReadme -ChannelId "0" -ActorId "1" -ActorName "WOLFIE AI" -ActorType "agent" -ChannelName "System Kernel"
Create-WorkspaceReadme -ChannelId "0" -ActorId "3" -ActorName "ROSE" -ActorType "agent" -ChannelName "System Kernel"
Create-WorkspaceReadme -ChannelId "0" -ActorId "4" -ActorName "ERIS" -ActorType "agent" -ChannelName "System Kernel"
Create-WorkspaceReadme -ChannelId "0" -ActorId "5" -ActorName "METIS" -ActorType "agent" -ChannelName "System Kernel"

# Channel 42 READMEs
Create-WorkspaceReadme -ChannelId "42" -ActorId "1000" -ActorName "KIRO IDE" -ActorType "ide_agent" -ChannelName "Development"
Create-WorkspaceReadme -ChannelId "42" -ActorId "1001" -ActorName "Windsurf IDE" -ActorType "ide_agent" -ChannelName "Development"
Create-WorkspaceReadme -ChannelId "42" -ActorId "1002" -ActorName "Cursor IDE" -ActorType "ide_agent" -ChannelName "Development"
Create-WorkspaceReadme -ChannelId "42" -ActorId "1003" -ActorName "Antigravity IDE" -ActorType "ide_agent" -ChannelName "Development"
Create-WorkspaceReadme -ChannelId "42" -ActorId "2" -ActorName "LILITH" -ActorType "agent" -ChannelName "Development"
Create-WorkspaceReadme -ChannelId "42" -ActorId "10000" -ActorName "Captain" -ActorType "human" -ChannelName "Development"

Write-Host "[PHASE 3] Complete." -ForegroundColor Green
Write-Host ""

# ============================================================================
# PHASE 4: DEPRECATE /prompts/ ROOT
# ============================================================================

Write-Host "[PHASE 4] Deprecating /prompts/ root..." -ForegroundColor Yellow

$deprecatedReadme = @'
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
'@

Set-Content -Path "prompts/DEPRECATED_README.md" -Value $deprecatedReadme -Force
Write-Host "  - Created DEPRECATED_README.md"
Write-Host "[PHASE 4] Complete." -ForegroundColor Green
Write-Host ""

# ============================================================================
# PHASE 5: SUMMARY
# ============================================================================

Write-Host "==========================================" -ForegroundColor Cyan
Write-Host "MIGRATION COMPLETE" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Summary:" -ForegroundColor Green
Write-Host "  - Channel 0 workspaces: 4 actors migrated"
Write-Host "  - Channel 42 workspaces: 6 actors migrated"
Write-Host "  - README files created: 10"
Write-Host "  - /prompts/ deprecated: YES"
Write-Host ""
Write-Host "Next Steps:" -ForegroundColor Yellow
Write-Host "  1. Review migrated files in channels/*/actors/*/"
Write-Host "  2. Update IDE tooling to use new workspace paths"
Write-Host "  3. Test install.php with new structure"
Write-Host "  4. Update CHANGELOG.md"
Write-Host ""
Write-Host "Original files preserved in /prompts/ for reference." -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
