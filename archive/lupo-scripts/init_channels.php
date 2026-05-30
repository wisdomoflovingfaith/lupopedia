<?php
/**
lupopedia.headers:
  when_updated: "20260324175911"
  file_path_from_root: "lupo-scripts/init_channels.php"
  questions_toon: null
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
lupopedia.footer:
  last_verified: "20260324175911"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
*/
/**
lupopedia.headers:
  when_updated: "20260324175617"
  file_path_from_root: "lupo-scripts/init_channels.php"
  questions_toon: null
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
lupopedia.footer:
  last_verified: "20260324175617"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
*/
/**
 * Initializer for Lupopedia Channels Directory Structure
 * Doctrine: PHP 5.3 Compatible, uses LUPO_PREFIX.
 */
require_once dirname(__FILE__) . '/../lupopedia-config.php';

function init_lupo_channels() {
    $base_dir = ABSPATH . LUPO_CHANNELS_DIR;
    $channels = array(0, 1, 42, 666);
    $subdirs = array('threads', 'rolls', 'tasks/active', 'tasks/pending', 'tasks/completed');
    $version = '4.0.x';

    if (!is_dir($base_dir)) {
        mkdir($base_dir, 0755, true);
        echo "Created base channels directory: $base_dir\n";
    }

    foreach ($channels as $channel_id) {
        $channel_path = $base_dir . '/' . $channel_id;
        foreach ($subdirs as $subdir) {
            $path = $channel_path . '/' . $subdir;
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
                echo "Created directory: $path\n";
            }
        }
        
        // Create versioned thread container
        $version_path = $channel_path . '/threads/' . $version;
        if (!is_dir($version_path)) {
            mkdir($version_path, 0755, true);
            echo "Created versioned thread directory: $version_path\n";
        }
    }
}

init_lupo_channels();
echo "Channel initialization complete.\n";
