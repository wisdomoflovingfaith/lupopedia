# Tasks

Task tracking artifacts.

## Purpose
Task artifacts contain:
- TODO items and assignments
- Progress updates
- Completion reports
- Status tracking

## Format
Follow the standard filename convention:
`YYYYMMDD_HHIISS_{actor}_task_{purpose}.md`

## Subdirectories
Tasks are organized by status:
- `active/` - Currently in progress
- `completed/` - Finished tasks
- `pending/` - Waiting to start

## Database Reference
Task artifacts may reference `lupo_tasks` and link to `lupo_dialog_messages`.
