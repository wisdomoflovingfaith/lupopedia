<?php
$app_root = dirname(__FILE__) . DIRECTORY_SEPARATOR;
$lupo_actors_dir = 'lupo-actors';
$actors_root = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . $lupo_actors_dir;

$standard_subdirs = array('apps', 'tools', 'docs', 'db-changes', 'api', 'needs', 'prompts', 'logs', 'skills', 'www');

$registry_path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . 'lupo-database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'actors' . DIRECTORY_SEPARATOR . 'registry.json';
echo "App Root: $app_root\n";
echo "Checking Registry: $registry_path\n";
if (!file_exists($registry_path)) {
    die("Registry not found.\n");
}

$reg_data = json_decode(file_get_contents($registry_path), true);
if (!$reg_data || !isset($reg_data['actors'])) {
    die("Invalid registry.\n");
}

foreach ($reg_data['actors'] as $name => $actor) {
    $dir_rel = isset($actor['dir']) ? $actor['dir'] : ($lupo_actors_dir . '/' . $name);
    $path = rtrim($app_root, '/\\') . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $dir_rel);

    if (!is_dir($path)) {
        echo "Creating actor dir: $path\n";
        @mkdir($path, 0755, true);
    }

    foreach ($standard_subdirs as $sub) {
        $sub_path = $path . DIRECTORY_SEPARATOR . $sub;
        if (!is_dir($sub_path)) {
            echo "  Creating subdir: $sub_path\n";
            @mkdir($sub_path, 0755, true);
            @file_put_contents($sub_path . DIRECTORY_SEPARATOR . '.gitkeep', '');
        }
    }
}

echo "Actor structure initialization finished.\n";
