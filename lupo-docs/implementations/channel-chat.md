---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260401000400"
  file_path_from_root: "lupo-docs/implementations/channel-chat.md"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/channel-chat.md"
  last_modified_utc: "20260401000400"
  federation_node_id: 1
  channel_id: 42
  thread_id: "channel-chat-implementation"
  actor_id: 105
  actor_name: "cascade"
  delegation_chain: "cascade:implementation"
  artifact_type: "implementation_notes"
  artifact_kind: "technical_documentation"
  purpose: "Implementation notes for Channel Chat feature - API paths, routing, fallback chain, and browser support"
  tags:
  - "implementation"
  - "channel"
  - "chat"
  - "api"
  - "routing"
  - "fallback"
  - "browser_support"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-docs/prd/18_channel_chat_display.md"
      type: references
      weight: 1.0
      reason: "PRD for Channel Chat Display"
    - to: "lupo-includes/modules/api/channels-api.php"
      type: references
      weight: 1.0
      reason: "API implementation"
    - to: "channel.php"
      type: references
      weight: 0.8
      reason: "Main channel chat page"
lupopedia.footer:
  last_verified: "20260401000400"
  verified_by:
    identity_type: "agent"
    actor_id: 105
    agent_name_identity: "Cascade"
    department_id_delta: 0
  verified_via:
    type: "direct"
    faucet_slug: "cascade"
  orchestrator: "cascade:implementation"
  next_action:
    - "Update implementation notes as API evolves"
    - "Add performance metrics when available"
    - "Document additional fallback patterns if discovered"
---

# Channel Chat Implementation Notes

## API Path
- Endpoint: `/api/lupo-channels/{id}/messages` 
- Router: `lupo-includes/modules/api/channels-api.php` 
- Thread support: `?thread_id={id}` filters by `dialog_thread_id` 

## URL Routing
| URL | Handler |
|-----|---------|
| `/channel-chat/{id}/` | `channel.php?channel_id={id}` |
| `/channel-chat/{id}/thread/{tid}/` | `channel.php?channel_id={id}&thread_id={tid}` |
| `/channels/{id}/` | Existing channel cockpit (unchanged) |

## Image Fallback
- GIF assets in `lupo-ui/images/digit0.gif` - `digit9.gif` 
- Placeholders: 1x1 transparent GIFs
- Replace with actual digit GIFs for visual feedback
- Endpoint: `?format=image&image_metric=count` returns 302 redirect to digitN.gif

## Fallback Chain
1. fetch() - modern
2. XMLHttpRequest - standard AJAX
3. ActiveX - IE5-9 (legacy path)
4. Image polling - fingerprint/probe (digit GIFs)
5. Buffer loading - iframe + format=buffer

## Browser Support
- Chrome 1+ (2008)
- Firefox 1+ (2004)
- Safari 1.2+ (2004)
- IE5+ (1999)
- All mobile browsers
