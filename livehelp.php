<?php
/* ⧉ WOLFIE HEADER v2.4 ⧉
w3_views: mechanical | relational | mythic | docs

◈ w3_MECHANICAL (REQUIRED)
w3_created_day_utc: 2026-02-01T00:00:00Z
w3_modified_day_utc: 2026-02-01T00:00:00Z
w3_updated_by: cascade
w3_taxonomy_key: wolfie.header.taxonomy
w3_taxonomy_version: 2.4
w3_package: lupopedia
w3_subpackage: livehelp
w3_module: livehelp
w3_aspect: compatibility-bridge
w3_purpose: Legacy Crafty Syntax livehelp JavaScript endpoint bridge.
w3_mutation_notes: Upgraded from WOLFIE HEADER v2.2 to v2.4 format

◈ w3_RELATIONAL (RECOMMENDED, STRUCTURAL ONLY)
w3_nourishes→:
w3_nourished_by←:
w3_tensions↔:

◈ w3_MYTHIC (OPTIONAL)
w3_epoch: wolfie-winter-2026
w3_signature:

◈ w3_DOCS (OPTIONAL — ENHANCED DOCUMENTATION)
*/
// Legacy Crafty Syntax compatibility
header("Content-Type: application/javascript");
include __DIR__ . "/lupo-includes/js/livehelp_js.php";
?>
