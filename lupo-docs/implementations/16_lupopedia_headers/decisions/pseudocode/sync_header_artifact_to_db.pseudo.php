<?php
/*
---
lupopedia.headers:
  header_format_version: 2
  lupopedia.schema: documentation
  when_updated: "20260405204205"
  file_path_from_root: "lupo-docs/implementations/16_lupopedia_headers/decisions/pseudocode/sync_header_artifact_to_db.pseudo.php"
  web_path: "http://www.lupopedia.com/lupopedia/lupo-docs/implementations/16_lupopedia_headers/decisions/pseudocode/sync_header_artifact_to_db.pseudo.php"
  last_modified_utc: "20260405204205"
  federation_node_id: 0
  channel_id: 42
  thread_id: "16-lupopedia-headers-pseudocode"
  author:
    type: "actor"
    id: 102
    name: "CURSOR"
  actor_id: 102
  actor_name: "CURSOR"
  delegation_chain: "cursor:root"
  artifact_type: "documentation"
  artifact_kind: "guide"
  purpose: "Pseudocode mirror of header_db_sync.sync_header_artifact_to_db; not runtime"
  tags:
    - "pseudocode"
    - "prd_16"
    - "header_db_sync"
lupopedia.edges:
  outbound_edges:
    - to: "lupo-scripts/lib/header_db_sync.py"
      type: references
      weight: 1.0
      reason: "Python source of truth"
    - to: "lupo-docs/prd/16_lupopedia_headers.md"
      type: references
      weight: 1.0
      reason: "Header applicability and PRD 16"
---
*/
/**
 * PSEUDOCODE — mirrors intent of lupo-scripts/lib/header_db_sync.py
 * sync_header_artifact_to_db(cursor, table_prefix, yaml_data, content_id, now_ymdhis)
 *
 * NOT loaded by the application. PHP 7.4-shaped comments only.
 *
 * Spec: lupo-docs/prd/16_lupopedia_headers.md
 * Python source of truth: lupo-scripts/lib/header_db_sync.py
 */

/**
 * Design notes:
 * - Preconditions: lupo_contents row already exists for content_id (import_content upsert first).
 * - All IDs for new metadata/edges rows must be explicit allocator pattern (no AUTO_INCREMENT
 *   per constitutional rules — the real Python uses COALESCE(MAX)+1; DB layer must match project).
 * - edge_category is literal 'lupopedia_header' for YAML-driven edges.
 */
function sync_header_artifact_to_db_pseudo($cursor, $table_prefix, $yaml_data, $content_id, $now_ymdhis)
{
    // 1. Extract nested lupopedia.headers key/value map (flat header fields).
    $headers = isset($yaml_data['lupopedia.headers']) ? $yaml_data['lupopedia.headers'] : array();
    if (empty($headers)) {
        throw new Exception('lupopedia.headers block missing');
    }

    // 2. Remove prior sync snapshot for this content (same class_name, entity_type=content).
    // DELETE FROM lupo_metadata WHERE entity_type='content' AND entity_id=? AND class_name='lupopedia_header_sync'
    delete_sync_metadata_for_content($cursor, $table_prefix, $content_id);

    // 3. Persist each header field as metadata property_key 'hdr.<field>' (HDR_PREFIX in Python).
    foreach ($headers as $key => $val) {
        $property_key = 'hdr.' . $key;
        $property_value = serialize_header_value_for_metadata($val);
        insert_metadata_row($cursor, $table_prefix, $content_id, $property_key, $property_value, $now_ymdhis);
    }

    // 4. Optional footer: lupopedia.footer → keys stored as 'ftr.<key>'.
    if (isset($yaml_data['lupopedia.footer']) && is_array($yaml_data['lupopedia.footer'])) {
        foreach ($yaml_data['lupopedia.footer'] as $key => $val) {
            $property_key = 'ftr.' . $key;
            $property_value = serialize_header_value_for_metadata($val);
            insert_metadata_row($cursor, $table_prefix, $content_id, $property_key, $property_value, $now_ymdhis);
        }
    }

    // 5. Any other lupopedia.* block except headers/footer/edges/history → 'block.<name>'.
    $skip = array(
        'lupopedia.headers' => 1,
        'lupopedia.footer' => 1,
        'lupopedia.edges' => 1,
        'lupopedia.history' => 1,
    );
    foreach ($yaml_data as $block_key => $blk) {
        if (!is_string($block_key) || strpos($block_key, 'lupopedia.') !== 0) {
            continue;
        }
        if (isset($skip[$block_key])) {
            continue;
        }
        $property_key = 'block.' . $block_key;
        insert_metadata_row($cursor, $table_prefix, $content_id, $property_key, serialize_header_value_for_metadata($blk), $now_ymdhis);
    }

    // 6. Edges: replace snapshot for this content_id + edge_category.
    soft_delete_existing_header_edges($cursor, $table_prefix, $content_id, $now_ymdhis);

    $edges_block = isset($yaml_data['lupopedia.edges']) ? $yaml_data['lupopedia.edges'] : null;
    $outbound = parse_outbound_edges($edges_block);
    $actor_id_int = coerce_int(isset($headers['actor_id']) ? $headers['actor_id'] : null);
    $channel_id_int = coerce_int(isset($headers['channel_id']) ? $headers['channel_id'] : null);

    foreach ($outbound as $ed) {
        $to_path = isset($ed['to']) ? trim(strval($ed['to'])) : '';
        if ($to_path === '') {
            continue;
        }
        $edge_type = isset($ed['type']) ? strval($ed['type']) : 'references';
        $weight = normalize_weight(isset($ed['weight']) ? $ed['weight'] : 0.5);
        $reason = truncate_reason(isset($ed['reason']) ? $ed['reason'] : null);

        // Resolve path → (right_object_type, right_object_id) via lupo_contents or lupo_reference_objects.
        list($rt, $rid) = resolve_edge_right($cursor, $table_prefix, $to_path, $now_ymdhis);

        insert_edge_row(
            $cursor,
            $table_prefix,
            array(
                'left_object_type' => 'content',
                'left_object_id' => $content_id,
                'right_object_type' => $rt,
                'right_object_id' => $rid,
                'edge_type' => $edge_type,
                'edge_category' => 'lupopedia_header',
                'channel_id' => $channel_id_int,
                'actor_id' => $actor_id_int,
                'weight_score' => weight_to_score($weight),
                'semantic_weight' => $weight,
                'flare_reason' => $reason,
                'now' => $now_ymdhis,
            )
        );
    }

    // 7. History: optional lupopedia.history list → JSON on lupo_contents.revision_history.
    if (array_key_exists('lupopedia.history', $yaml_data)) {
        $hist = $yaml_data['lupopedia.history'];
        $rev_json = json_encode($hist);
        update_contents_revision_history($cursor, $table_prefix, $content_id, $rev_json, $now_ymdhis);
    } else {
        touch_contents_updated_only($cursor, $table_prefix, $content_id, $now_ymdhis);
    }
}

// Stubs — real behavior lives in Python header_db_sync.py
function serialize_header_value_for_metadata($val) { return ''; }
function delete_sync_metadata_for_content($cursor, $table_prefix, $content_id) { }
function insert_metadata_row($cursor, $table_prefix, $content_id, $property_key, $property_value, $now) { }
function soft_delete_existing_header_edges($cursor, $table_prefix, $content_id, $now) { }
function parse_outbound_edges($edges_block) { return array(); }
function coerce_int($v) { return null; }
function normalize_weight($w) { return 0.5; }
function truncate_reason($r) { return null; }
function resolve_edge_right($cursor, $table_prefix, $path, $now) { return array('content', 0); }
function insert_edge_row($cursor, $table_prefix, $fields) { }
function update_contents_revision_history($cursor, $table_prefix, $content_id, $json, $now) { }
function touch_contents_updated_only($cursor, $table_prefix, $content_id, $now) { }
function weight_to_score($w) { return 50; }
