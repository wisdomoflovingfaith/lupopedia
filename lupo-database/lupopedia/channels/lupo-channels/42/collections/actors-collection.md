# Collection: Actors (Fallback Migration)

This collection documents the move of all actor-related artifacts into the `lupo-database/` directory.

## Migration Path
Original Path: `lupo-actors/`
Fallback Path: `lupo-database/lupopedia/actors/`

## Key Assets
- `lupo-database/lupopedia/actors/<actor_id>/WHO.json`: Primary identity file.
- `lupo-database/lupopedia/actors/<actor_id>/session.json`: Active session anchor.
- `lupo-database/lupopedia/actors/<actor_id>/help.md`: Actor documentation.
- `lupo-database/lupopedia/actors/<actor_id>/profile.png`: Actor avatar.

## Table Mappings
- `lupo_actors`
- `lupo_auth_users` (linked identities)
- `lupo_auth_groups`
- `lupo_actor_roles`

## Version
Created as part of Phase 2 for version 4.0.55.
