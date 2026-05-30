<?php
/**
 * lupopedia.headers:
 *   header_format_version: "4.1.2"
 *   file_path_from_root: "lupo-bin/bump-version.php"
 *   web_path: "https://www.lupopedia.com/lupopedia/lupo-bin/bump-version.php"
 *   status: "deprecated"
 *   when_updated: "20260415180000"
 *   trust_tier: "canonical"
 *   questions_toon: null
 *   memory_toon: "lupo-memory/development/canonical/1026/04/bump-version-deprecation.toon"
 *   atoms_toon: "lupo-memory/atoms/lupopedia_global_constants.atom.toon"
 *   transcript_jsonl: "0/development/bump-version-deprecation"
 *   artifact_type: implementation
 *   artifact_kind: tool
 *   channel_key: "development"
 *   federation_node_id: 0
 *   thread_id: ""
 *   content_id: null
 *   content_parent_id: "40"
 *   content_slug: "bump-version-deprecation"
 *   default_collection_id: null
 *   lupopedia.schema: implementation
 *   title: "bump-version.php (deprecated stub)"
 *   summary: "Deprecation stub; product version is edited in global constants atom with global_atoms.yaml as legacy mirror."
 */
if (php_sapi_name() !== 'cli') {
    die("This script can only be run from the command line.\n");
}

$msg = <<<TXT

================================================================================
DEPRECATED: lupo-bin/bump-version.php (4.0.99+)
================================================================================
Automated version bumping via this script is removed. The previous implementation
predated v4.0.99, used dirname(__DIR__) instead of LUPOPEDIA_PATH, updated
multiple files heuristically (including a hardcoded CHANGELOG baseline), and
is not aligned with header-driven patch bumps on the 4.0.x line.

Single source of truth: lupo-memory/atoms/lupopedia_global_constants.atom.toon
  - constants.versioning.current_lupopedia_version
Legacy mirror: lupo-config/global_atoms.yaml
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
  - version

Manual workflow:
  1. Read lupo-docs/doctrine/VERSIONING_DOCTRINE.md (especially sections 3 and 10).
  2. Bump the product patch only when a LUPOPEDIA Header System change requires it.
  3. Update the global constants atom version and keep legacy mirrors in sync.
  4. Append CHANGELOG entries per project rules; do not rely on this CLI.

Legacy copy (same behavior): lupo-bin/legacy/bump-version.php
Further context: lupo-bin/legacy/README.md
================================================================================

TXT;
fwrite(STDERR, $msg);
fwrite(STDOUT, "Exit 3 (deprecated).\n");
exit(3);
