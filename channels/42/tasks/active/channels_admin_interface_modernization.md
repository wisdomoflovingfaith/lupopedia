---
task_id: "CH0-20260226-006"
channel_id: 42
assigned_to: [1001]
status: "pending"
priority: "medium"
created_utc: "20260226"
target_version: "4.0.49"
rolled_from: "4.0.48"
task_type: "ui_modernization"
---

# 🖥️ Channels Web Admin Interface Modernization

**Task ID:** CH0-20260226-006  
**Assigned:** Windsurf (1001)  
**Priority:** Medium  
**Status:** 📋 Pending  

## Objective
Modernization of the legacy Crafty Syntax channels/livehelp admin interface using modern iframe-based design and Tailwind CSS within Lupopedia's channel system.

## Scope
1. Replace legacy `<frameset>` with responsive CSS Grid/iframes.
2. Implement semantic HTML5 structure.
3. Integrate with Lupopedia's global header/nav.
4. Modernize `xmlhttp.js` to use Fetch API/WebSockets.

## Next Steps
- Implement `channels/1/index.php` template.
- Develop PHP-based secure iframe wrapper.
- Integrate with `lupo_sessions` for actor-based auth.
