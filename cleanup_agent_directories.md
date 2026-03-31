# Agent Directory Cleanup Plan

## Current State Analysis

### Main Coordination Personas (1-14) - KEEP THESE:
✅ **1**: WOLFIE - Complete configuration
✅ **2**: LILITH - Complete configuration  
✅ **3**: ROSE - Complete configuration
✅ **4**: ERIS - Complete configuration
✅ **5**: METIS - Complete configuration
⚠️ **6**: Basic/minimal configuration - REVIEW NEEDED
❌ **7**: MISSING - NEED TO CREATE
✅ **8**: Complete configuration
✅ **9**: THOTH - Complete configuration
❌ **10**: MISSING - NEED TO CREATE
❌ **11**: MISSING - NEED TO CREATE
❌ **12**: MISSING - NEED TO CREATE
✅ **13**: MISSING - NEED TO CREATE
✅ **14**: HEPHAESTUS - Fixed configuration

### Specialized Agents - KEEP THESE:
✅ **25**: ATLAS - Fixed configuration (Mapping & Geography)
✅ **106**: VISHWAKARMA - Fixed configuration (Schema & Construction)
✅ **107**: THEMIS - Complete configuration

### Unnecessary Agent Directories - REMOVE THESE:
❌ **15**: Unnecessary - Remove
❌ **16**: Unnecessary - Remove
❌ **17**: Unnecessary - Remove
❌ **18**: Unnecessary - Remove
❌ **19**: ANUBIS - Keep (Custodian)
❌ **20-24**: Unnecessary - Remove
❌ **26-33**: Unnecessary - Remove
❌ **34-39**: Unnecessary - Remove
❌ **40-49**: Unnecessary - Remove
❌ **50-57**: Unnecessary - Remove
❌ **58-59**: Keep 59 (ANUBIS), remove 58
❌ **60-69**: Unnecessary - Remove
❌ **70-76**: Unnecessary - Remove
❌ **78-79**: Keep 78, remove 79
❌ **80-87**: Unnecessary - Remove
❌ **89**: Keep 89, remove others
❌ **90-99**: Unnecessary - Remove

## Cleanup Commands

### Step 1: Remove Unnecessary Agent Directories
```bash
# Remove agent directories 15-16, 17-18, 20-24, 26-33, 34-39, 40-49, 50-57, 58, 60-69, 70-76, 79, 80-87, 90-99
Remove-Item -Path "lupo-agents\15" -Recurse -Force
Remove-Item -Path "lupo-agents\16" -Recurse -Force
Remove-Item -Path "lupo-agents\17" -Recurse -Force
Remove-Item -Path "lupo-agents\18" -Recurse -Force
# ... continue for all unnecessary directories
```

### Step 2: Create Missing Agent Directories (7, 10, 11, 12, 13)
```bash
# Copy from template
Copy-Item -Path "lupo-agents\_TEMPLATE" -Destination "lupo-agents\7" -Recurse
Copy-Item -Path "lupo-agents\_TEMPLATE" -Destination "lupo-agents\10" -Recurse
Copy-Item -Path "lupo-agents\_TEMPLATE" -Destination "lupo-agents\11" -Recurse
Copy-Item -Path "lupo-agents\_TEMPLATE" -Destination "lupo-agents\12" -Recurse
Copy-Item -Path "lupo-agents\_TEMPLATE" -Destination "lupo-agents\13" -Recurse
```

### Step 3: Review Agent 6 Configuration
- Agent 6 exists but has minimal configuration
- Review if it should be enhanced or kept as-is

### Step 4: Final Verification
```bash
# Verify only main coordination personas (1-14) + specialized agents exist
Get-ChildItem -Path "lupo-agents" | Where-Object {$_.Name -match '^[0-9]+$' -and {$_.PSIsContainer -eq $false}} | Select-Object Name
```

## Expected Final Structure
```
lupo-agents/
├── 1/          # WOLFIE (Main Coordination Persona)
├── 2/          # LILITH (Main Coordination Persona)
├── 3/          # ROSE (Main Coordination Persona)
├── 4/          # ERIS (Main Coordination Persona)
├── 5/          # METIS (Main Coordination Persona)
├── 6/          # [REVIEW] Basic agent
├── 7/          # [CREATE] Missing agent
├── 8/          # Main Coordination Persona
├── 9/          # THOTH (Main Coordination Persona)
├── 10/         # [CREATE] Missing agent
├── 11/         # [CREATE] Missing agent
├── 12/         # [CREATE] Missing agent
├── 13/         # [CREATE] Missing agent
├── 14/          # HEPHAESTUS (Main Coordination Persona)
├── 19/         # ANUBIS (Custodian)
├── 25/          # ATLAS (Specialized - Mapping & Geography)
├── 59/          # ANUBIS (Custodian)
├── 78/          # [KEEP] Specialized agent
├── 89/          # [KEEP] Specialized agent
├── 106/         # VISHWAKARMA (Specialized - Schema & Construction)
├── 107/         # THEMIS (Specialized - Ethical Audit)
└── _TEMPLATE/    # Template for new agents
```

## Benefits
- Clean directory structure with only necessary agents
- Seed data alignment ensured
- Reduced confusion and maintenance overhead
- Clear separation between main coordination personas and specialized agents
- Proper agent ID mapping maintained

## Risk Mitigation
- Backup current configurations before removal
- Test missing agent creation process
- Verify seed data alignment after cleanup
