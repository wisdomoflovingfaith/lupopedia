# Direct Messages

Messages to specific actors.

## Purpose
Direct messages are sent to specific actors and are used for:
- Private communications
- Sensitive coordination
- Personal assignments
- Confidential information

## Format
Follow the standard filename convention:
`YYYYMMDD_HHIISS_{actor}_direct_{purpose}.md`

## Location
Direct messages are placed in actor-specific subdirectories:
- `lupo-channels/42/direct/{actor_id}/`

## Database Reference
Direct messages correspond to `lupo_dialog_messages` with specific `to_actor_id`.
