---
wolfie.headers: explicit architecture with structured clarity for every file.
file.last_modified_system_version: 4.0.16
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - GLOBAL_CURRENT_AUTHORS
dialog:
  speaker: CURSOR
  target: @captain-wolfie
  mood_RGB: "00FF00"
  message: "Created channel_identity_block.template.md as the first WOLFIE template. Provides structured template with placeholders for channel identity configuration including channel_key, channel_name, dialog_output_file, default roles, agents, emotional poles, and fallback behavior."
tags:
  categories: ["documentation", "templates", "channels", "wolfie"]
  collections: ["core-docs"]
  channels: ["dev"]
file:
  title: "Channel Identity Block Template"
  description: "Template for Channel Identity Block configuration used in Channel Initialization Protocol. Contains placeholders for all channel identity components."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: published
  author: GLOBAL_CURRENT_AUTHORS
---

# Channel Identity Block Template

**Version:** GLOBAL_CURRENT_LUPOPEDIA_VERSION  
**Status:** Published  
**Agent:** WOLFIE (agent_id = 1)  
**Last Updated:** 2026-01-14

## Usage

This template is used by WOLFIE during channel initialization to create Channel Identity Blocks. Replace all `{{placeholder}}` values with actual channel configuration data.

---

## Channel Identity Block

```yaml
channel_identity:
  # Core Identity
  channel_key: "{{channel_key}}"                    # URL-friendly identifier (slug), e.g., "dev/documentation"
  channel_name: "{{channel_name}}"                  # Human-readable name, e.g., "Development Documentation Channel"
  channel_description: "{{channel_description}}"    # Channel purpose and scope
  
  # Dialog Configuration
  dialog_output_file: "{{dialog_output_file}}"      # Filesystem path, e.g., "dialogs/dev_documentation_dialog.md"
  
  # Default Participants
  default_roles:                                    # Default role assignments
    - role_key: "{{role_key_1}}"                    # e.g., "channel_admin", "participant", "observer"
      actor_ids: [{{actor_id_1}}, {{actor_id_2}}]  # List of actor IDs with this role
  
  default_agents:                                   # Default agent assignments
    - agent_id: {{agent_id_1}}                      # e.g., 1 (WOLFIE), 2 (CAPTAIN)
      role_key: "{{role_key}}"                      # Role for this agent in channel
  
  # Emotional Geometry
  emotional_poles:                                  # Polar agents for CADUCEUS
    pole_a:
      agent_id: {{pole_a_agent_id}}                 # First polar agent ID
      agent_code: "{{pole_a_agent_code}}"           # Agent code, e.g., "CURSOR"
    pole_b:
      agent_id: {{pole_b_agent_id}}                 # Second polar agent ID
      agent_code: "{{pole_b_agent_code}}"           # Agent code, e.g., "KIRO"
  
  # Fallback Behavior
  fallback_behavior:
    ide_agents:
      primary: "filesystem"                         # IDE agents write to filesystem dialog files
      fallback: "toon_files"                        # Optional: read from toon files
      required: "filesystem_write"                  # MUST be able to write to filesystem
    php_ai_terminal_agents:
      primary: "database"                           # php_ai_terminal agents use database tables
      fallback: "direct_query"                     # Direct database queries
      required: "database_write"                    # MUST be able to write to database
  
  # Metadata
  federation_node_id: {{federation_node_id}}        # Default: 1 (local installation)
  created_by_actor_id: {{created_by_actor_id}}      # Actor who created the channel
  default_actor_id: {{default_actor_id}}            # Default actor ID (usually 1)
  bgcolor: "{{bgcolor}}"                            # Background color (6-char hex, no #), default: "FFFFFF"
  status_flag: {{status_flag}}                      # 1=active, 0=inactive, default: 1
```

---

## Example: Development Documentation Channel

```yaml
channel_identity:
  channel_key: "dev/documentation"
  channel_name: "Development Documentation Channel"
  channel_description: "Channel for coordinating documentation work across IDE agents and php_ai_terminal agents."
  
  dialog_output_file: "dialogs/dev_documentation_dialog.md"
  
  default_roles:
    - role_key: "channel_admin"
      actor_ids: [1, 2]  # WOLFIE and CAPTAIN
    - role_key: "participant"
      actor_ids: [3, 4, 5]  # KIRO, CURSOR, CASCADE
  
  default_agents:
    - agent_id: 1
      role_key: "channel_admin"
    - agent_id: 3
      role_key: "participant"
  
  emotional_poles:
    pole_a:
      agent_id: 3
      agent_code: "KIRO"
    pole_b:
      agent_id: 4
      agent_code: "CURSOR"
  
  fallback_behavior:
    ide_agents:
      primary: "filesystem"
      fallback: "toon_files"
      required: "filesystem_write"
    php_ai_terminal_agents:
      primary: "database"
      fallback: "direct_query"
      required: "database_write"
  
  federation_node_id: 1
  created_by_actor_id: 1
  default_actor_id: 1
  bgcolor: "F7FAFF"
  status_flag: 1
```

---

## Template Variables

| Variable | Type | Description | Example |
|----------|------|-------------|---------|
| `{{channel_key}}` | String | URL-friendly slug | `"dev/documentation"` |
| `{{channel_name}}` | String | Human-readable name | `"Development Documentation Channel"` |
| `{{channel_description}}` | String | Channel purpose | `"Channel for documentation work"` |
| `{{dialog_output_file}}` | String | Filesystem path | `"dialogs/dev_documentation_dialog.md"` |
| `{{role_key}}` | String | Role identifier | `"channel_admin"` |
| `{{actor_id}}` | Integer | Actor ID | `1` |
| `{{agent_id}}` | Integer | Agent ID | `1` |
| `{{pole_a_agent_id}}` | Integer | First polar agent ID | `3` |
| `{{pole_a_agent_code}}` | String | First polar agent code | `"KIRO"` |
| `{{pole_b_agent_id}}` | Integer | Second polar agent ID | `4` |
| `{{pole_b_agent_code}}` | String | Second polar agent code | `"CURSOR"` |
| `{{federation_node_id}}` | Integer | Federation node ID | `1` |
| `{{created_by_actor_id}}` | Integer | Creator actor ID | `1` |
| `{{default_actor_id}}` | Integer | Default actor ID | `1` |
| `{{bgcolor}}` | String | Background color (hex) | `"F7FAFF"` |
| `{{status_flag}}` | Integer | Status flag | `1` |

---

## Related Documentation

- **[Channel Initialization Protocol](../../../docs/channels/agents/agent-1/doctrine/CHANNEL_INITIALIZATION_PROTOCOL.md)** — Complete CIP doctrine
- **[Channel Initialization Workflow](../../../docs/channels/agents/agent-1/workflows/channel_initialization.workflow.md)** — Step-by-step workflow

---

*Last Updated: January 14, 2026*  
*Version: GLOBAL_CURRENT_LUPOPEDIA_VERSION*  
*Status: Published*  
*Author: GLOBAL_CURRENT_AUTHORS*
