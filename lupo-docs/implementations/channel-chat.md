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
