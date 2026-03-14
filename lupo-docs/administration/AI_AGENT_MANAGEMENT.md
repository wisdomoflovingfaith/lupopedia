# LUPOPEDIA HEADERS (replaces FLARE)
---
lupopedia.headers:
  lupopedia.version: "4.0.73"
  lupopedia.schema: "documentation"
  file_path_from_root: "lupo-docs/administration/AI_AGENT_MANAGEMENT.md"
  system_version: "4.0.53"
  last_updated_utc: "20260301"
  channel_id: 1
  actor_id: 1006
  delegation_chain: "1:10000"
  artifact_type: "guide"
  artifact_kind: "administration_manual"
  purpose: "Guidelines for managing and monitoring AI agents in the Lupopedia federation"
  mood_rgb: "4682B4"  # SteelBlue
  traits: ["ai_management", "monitoring", "boot_process", "v4.0.53"]
  tags: ["gemini", "ai_agents", "administration", "maintenance"]
  lupo_agent: "gemini"

lupopedia.edges:
  outbound_edges:
    - { to: "QUICKSTART.md", type: "references", weight: 0.8 }
    - { to: "lupo-bin/boot_system_agent.php", type: "related_script", weight: 1.0 }
    - { to: "lupo-api/v1/health.php", type: "monitoring_endpoint", weight: 1.0 }

lupopedia.footer:
  last_verified: "20260301"
  last_verified_by: "gemini"
---

# 🤖 AI AGENT MANAGEMENT GUIDE

This guide provides system administrators with the necessary instructions to manage, monitor, and troubleshoot the core AI agents in the Lupopedia federation.

## 🚀 1. SYSTEM BOOT & INITIALIZATION

The system agent boot process is the primary way to activate AI agents. It ensures that all required agents have active sessions and are ready to process tasks.

### Boot Command
To perform a full system boot with AI agent initialization:
```bash
php lupo-bin/boot_system_agent.php --ai-startup
```

### Options:
- `--ai-startup`: Starts core agents (LILITH, SYSTEM, CAPTAIN WOLFIE, ANUBIS).
- `--crafty-upgrade`: Checks for and executes legendary Crafty Syntax migrations.
- `--debug`: Enables detailed logging during the boot process.

---

## 📊 2. MONITORING AI AGENT STATUS

AI agent health is monitored via the **System Health Dashboard** and the **Health API**.

### REST API Monitoring
You can check the real-time status of all agents via the health endpoint:
```bash
GET /api/v1/health.php
```

**Example Response:**
```json
{
  "status": "ok",
  "checks": {
    "ai_agents": {
      "status": "ok",
      "message": "AI Agents: 4/4 running",
      "agents": {
        "0": { "name": "SYSTEM", "status": "running" },
        "2": { "name": "LILITH", "status": "running" },
        "1": { "name": "CAPTAIN WOLFIE", "status": "running" },
        "19": { "name": "ANUBIS", "status": "running" }
      }
    }
  }
}
```

### Log Review
- **Boot Lifecycles**: View `lupo_channel_boot_lifecycle` for full boot history.
- **Boot Details**: View `lupo_channel_boot_detail_lifecycle` for individual channel and agent activation logs.
- **System Logs**: Core AI events are logged to `lupo_channel_logs` (Channel 0).

---

## 🛠️ 3. TROUBLESHOOTING & RECOVERY

### Common Failure Scenarios

#### 1. Agent Fails to Initialize
If an agent shows as `offline` or `Failed to initialize` during boot:
- **Check Actor Status**: Ensure the actor is active and not deleted in `lupo_actors`.
  ```sql
  SELECT is_active, is_deleted FROM lupo_actors WHERE actor_id = [AGENT_ID];
  ```
- **Session Cleanup**: If an old "ghost" session is preventing activation, the system usually handles heartbeats, but you can manually expire sessions if needed.

#### 2. Schema Mismatch
If SYSTEM AI reports a schema mismatch:
- Review the error logs in the boot lifecycle.
- Verify against the latest TOON files in `lupo-database/lupopedia/toon/`.
- Run schema verification scripts if available.

### Recovery Procedures

1. **Re-activate Actor**: If an actor was accidentally deactivated, set `is_active = 1`.
2. **Re-run Boot**: Execute the boot script again. The process is idempotent for already running agents.
3. **Manual Session Creation**: In extreme cases, a session can be manually created via the `ensureActorActive` helper function.

---

## 🔒 4. SECURITY & PERMISSIONS

AI agents operate under the same security constraints as human actors:
- They must have valid `L-lupo` sessions.
- Their actions are limited by their registered roles in `lupo_actor_channel_roles`.
- Kernel agents (ID < 2000) have specialized system-level permissions.

---

**Last Updated**: 2026-03-01  
**Version**: 4.0.53  
**Maintained By**: Gemini (1006)
