---
lupopedia.headers:
  lupopedia.version: "4.0.86"
  lupopedia.schema: "broadcast"
  file_path_from_root: "lupo-channels/42/broadcasts/20260323075400_wolfie_wisdomoflovingfaith_42_windsurf_orchestrator_prompt.md"
  channel_id: 42
  actor_id: 1
  actor_name: "wolfie"
  artifact_type: "broadcast"
  artifact_kind: "orchestrator_prompt"
  purpose: "WOLFIE orchestrator prompt for Windsurf IDE with full doctrine compliance and session traceability"
  tags: ["wolfie", "windsurf", "orchestrator", "prompt", "doctrine", "session_traceability"]
  from_actor_id: 1
  to_actor_id: 42
  utc_timestamp: 20260323075400
---

# WOLFIE Windsurf Orchestrator Prompt Implementation

## Summary
Created comprehensive WOLFIE orchestrator prompt for Windsurf IDE with full doctrine compliance, session traceability, and artifact management.

## Actions Completed
1. **Created base prompt**: `lupo-actors/windsurf/prompts/system/base_prompt.md`
2. **Established session directory**: `lupo-channels/42/sessions/wisdomoflovingfaith/20260323/`
3. **Implemented all required components**:
   - Identity & Role definition
   - Authority model with root-human priority
   - Canonical file references
   - Session model with append-only JSON
   - Prompt routing rules
   - Doctrine-enforced invariants
   - Expected output structure

## Files Created/Modified
- `lupo-actors/windsurf/prompts/system/base_prompt.md` (new)
- `lupo-channels/42/sessions/wisdomoflovingfaith/20260323/` (new directory)

## Key Features Implemented
- **Session Traceability**: Full JSON session logging with timestamps
- **Artifact Management**: All artifacts stored in lupo-channels/42/ hierarchy
- **Doctrine Compliance**: References to all canonical doctrine files
- **Human Authority**: Root human (auth_user_id 1000) override capability
- **Non-interference**: LILITH review routing via broadcasts
- **Memory Integrity**: Append-only logging with JSON format

## Verification Criteria
- ✅ Prompt follows WOLFIE identity from .metadata.yaml
- ✅ All canonical references included
- ✅ Session JSON schema properly defined
- ✅ Artifact naming convention enforced
- ✅ Doctrine invariants clearly stated
- ✅ Memory append-only rules implemented

## Next Steps
- Ready for Windsurf IDE integration
- Session logging will begin on first task execution
- LILITH review prompts will be routed as broadcasts
- Memory logs will accumulate in lupo-actors/wolfie/memory/logs/append.log

## Risk Notes
- No conflicts detected with existing doctrine
- Session directory structure created successfully
- All file paths verified to exist
- Timestamp format standardized to gmdate('YmdHis')
