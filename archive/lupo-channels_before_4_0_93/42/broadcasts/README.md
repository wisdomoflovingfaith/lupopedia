# Broadcast Messages

All messages to all channel members.

## Purpose
Broadcast messages are sent to all members of Channel 42 and are used for:
- System-wide announcements
- Policy updates
- Release notifications
- Critical alerts

## Format
Follow the standard filename convention:
`YYYYMMDD_HHIISS_{actor}_broadcast_{purpose}.md`

## Database Reference
Broadcast messages correspond to `lupo_dialog_messages` with `to_actor_id = NULL`.
