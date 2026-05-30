#!/usr/bin/env php
<?php
# ---------------------------------------------------------------------
# lupopedia.headers:
#   header_format_version: "4.0.99"
#   lupopedia.schema: implementation
#   when_updated: "20260412171224"
#   file_path_from_root: "lupo-scripts/validate_actor_id.php"
#   web_path: "https://www.lupopedia.com/lupopedia/lupo-scripts/validate_actor_id.php"
#   last_modified_utc: "20260412171224"
#   federation_node_id: 0
#   channel_key: "development"
#   trust_tier: "canonical"
#   memory_key: "lupo-memory/development/canonical/1026/04/validate-actor-id.toon"
#   artifact_type: implementation
#   artifact_kind: tool
#   thread_id: ""
#   content_id: null
#   pk_id: null
#   pk_slug: ""
#   title: "Actor ID Validator"
#   status: "complete"
#   parent_pk_id: "00"
#   summary: "Validates seed actor band (actor_id 1-999 ceiling) against lupo-database/lupopedia/actors/registry.json; optional --id=N check."
#   module: "tooling"
#   dialog_transcript: "0/development/validate-actor-id"
# ---------------------------------------------------------------------
/**
 * validate_actor_id.php — seed actor band checks (actor_id 1-999 ceiling).
 *
 * Usage:
 *   php lupo-scripts/validate_actor_id.php           (audit registry.json)
 *   php lupo-scripts/validate_actor_id.php --id=123  (single id check)
 */

$repoRoot = dirname(__DIR__);

/**
 * @param int|string $actorId
 * @param array      $registrySeedIds sorted unique ids in 1..999 from registry
 *
 * @return array array(bool ok, string message)
 */
function validate_actor_id_for_registry($actorId, array $registrySeedIds)
{
    $aid = (int) $actorId;
    if ($aid > 999) {
        return array(true, 'runtime band');
    }
    if ($aid < 1) {
        return array(false, 'actor_id must be >= 1');
    }
    if (!in_array($aid, $registrySeedIds, true)) {
        return array(false, 'actor_id ' . $aid . ' in seed band but not listed in registry.json actors');
    }

    return array(true, 'ok');
}

$path = $repoRoot . DIRECTORY_SEPARATOR . 'lupo-database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'actors' . DIRECTORY_SEPARATOR . 'registry.json';
if (!is_file($path)) {
    fwrite(STDERR, "registry.json not found: {$path}\n");
    exit(1);
}
$json = file_get_contents($path);
$data = json_decode($json, true);
if (!is_array($data) || !isset($data['actors']) || !is_array($data['actors'])) {
    fwrite(STDERR, "registry.json: missing actors array\n");
    exit(1);
}
$ids = array();
$seen = array();
foreach ($data['actors'] as $row) {
    if (!is_array($row) || !isset($row['actor_id'])) {
        continue;
    }
    $id = (int) $row['actor_id'];
    if (isset($seen[$id])) {
        fwrite(STDERR, "registry.json: duplicate actor_id {$id}\n");
        exit(1);
    }
    $seen[$id] = true;
    $ids[]      = $id;
}
$ids = array_values(array_unique($ids));
sort($ids, SORT_NUMERIC);
$seedIds = array();
foreach ($ids as $id) {
    if ($id >= 1 && $id <= 999) {
        $seedIds[] = $id;
    }
}
foreach ($argv as $arg) {
    if (strpos($arg, '--id=') === 0) {
        $v = (int) substr($arg, 5);
        $r = validate_actor_id_for_registry($v, $seedIds);
        if (!$r[0]) {
            fwrite(STDERR, $r[1] . "\n");
            exit(1);
        }
        echo $r[1] . "\n";
        exit(0);
    }
}
echo 'validate_actor_id: OK (seed band 1-999 entries=' . count($seedIds) . ', total registry ids=' . count($ids) . ')' . "\n";
exit(0);
