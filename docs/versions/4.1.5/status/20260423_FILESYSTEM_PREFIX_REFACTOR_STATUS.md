# Filesystem Prefix Refactor Status — 20260423

## Summary

Filesystem rename cleanup is in progress / near completion.

The `lupo-` filesystem prefix has been removed from active project directories.
Legacy directories still present after the rename pass are being treated as possible drift or leftovers, not as active canonical paths.

## Current Focus

Reviewing remaining legacy-prefixed directories such as:

- `lupo-scripts/`
- `lupo-agents/`

These are candidates for relocation into `archive/` if confirmed unused.

## Rule Being Applied

- Active filesystem paths must not use the `lupo-` prefix
- Database prefixing with `lupo_` remains valid and unchanged
- Legacy directories are to be quarantined into `archive/`, not deleted immediately

## Risk

If any active code still references a legacy-prefixed directory, moving it to `archive/` may expose missed path dependencies.

That is acceptable and preferred over leaving silent drift in place.

## Verification

- manual search/replace completed on active filesystem paths
- grep/search audit performed for prefix-related references
- remaining legacy-prefixed directories under review

## Status

In progress
