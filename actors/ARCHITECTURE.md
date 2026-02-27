# Architecture Overview: Actor Directory Structure

This directory implements the actor-centric foundation for the Lupopedia Semantic OS. It is designed to be:
1. **Portable**: All data is stored in human-readable and machine-parseable formats (JSON, NDJSON, MD).
2. **Transferable**: Mapping layers (in `meta/schema.json`) allow for easy sync with relational database tables.
3. **Scalable**: Append-only logs (NDJSON) handle high-volume activity data without rewriting large files.
4. **Secure**: Sensitive data in `credentials.json` is encrypted at rest.
5. **Semantic**: Full integration with FLARE graph edges and FLIP headers for cross-actor relationship tracking.

## Key Design Principles
- **Actor as Root**: The `actor_id` is the primary key and the root of the actor's filesystem.
- **Dumb Storage, Smart Application**: The directory structure is a state snapshot; application logic (PHP) handles the transformation and query resolution.
- **Soft State**: `state/` and `resources/` provide runtime context that can be ephemeral or persistent.

## Metadata and Governance
All files include `schema_version` and metadata for validation. The `registry.json` at the root acts as the master index for the system.
