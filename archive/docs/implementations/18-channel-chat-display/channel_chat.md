---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: implementation
  when_updated: "20260401000400"
  file_path_from_root: "docs/implementations/18_channel_chat_display/channel_chat.md"
  web_path: "http://www.lupopedia.com/lupopedia/docs/implementations/18_channel_chat_display/channel_chat.md"
  questions_toon: null
  federation_node_id: 1
  channel_key: null
  trust_tier: null
  memory_key: null
  artifact_type: implementation_notes
  artifact_kind: technical_documentation
  thread_id: "channel-chat-implementation"
  content_id: null
  pk_id: null
  pk_slug: ""
  title: ""
  status: ""
  parent_pk_id: "18_channel_chat_display"
  summary: ""
  module: null
  dialog_transcript: null
---
# Channel Chat Implementation Notes

## API Path
- Endpoint: `/api/channels/{id}/messages` 
- Router: `includes/modules/api/channels-api.php` 
- Thread support: `?thread_id={id}` filters by `dialog_thread_id` 

## URL Routing
| URL | Handler |
|-----|---------|
| `/channel-chat/{id}/` | `channel.php?channel_id={id}` |
| `/channel-chat/{id}/thread/{tid}/` | `channel.php?channel_id={id}&thread_id={tid}` |
| `/channels/{id}/` | Existing channel cockpit (unchanged) |

## Image Fallback
- GIF assets in `ui/images/digit0.gif` - `digit9.gif` 
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
