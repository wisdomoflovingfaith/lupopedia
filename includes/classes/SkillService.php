<?php
/**
 * Skill Service
 *
 * Resolves skills for actors from profile and skills/*.md files; provides skill-based capability checks.
 * Skills are declared via lupopedia.skills header blocks.
 *
 * @package Lupopedia
 * @version 4.0.68
 */

class SkillService
{
    /** @var PDO_DB|null */
    private $db;
    /** @var string */
    private $table_prefix;
    /** @var array actor_id => list of skill arrays */
    private $skills_cache = array();
    /** @var string base path for actors and skills */
    private $base_path;

    public function __construct($db = null)
    {
        $this->db = $db ? $db : (class_exists('DatabaseFactory') ? DatabaseFactory::getConnection() : null);
        $this->table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
        $this->base_path = defined('LUPOPEDIA_PATH') ? rtrim(LUPOPEDIA_PATH, '/\\') : (defined('ABSPATH') ? rtrim(ABSPATH, '/\\') : '');
        if ($this->base_path === '' && function_exists('getcwd')) {
            $this->base_path = getcwd();
        }
    }

    /**
     * Get actor directory path: try numeric id first, then slug (e.g. wolfie for actor 1).
     *
     * @param int $actor_id
     * @return string path to actor dir (no trailing slash) or empty
     */
    private function getActorDir($actor_id)
    {
        $actors_dir = $this->base_path . DIRECTORY_SEPARATOR . 'actors';
        $id_dir = $actors_dir . DIRECTORY_SEPARATOR . (int) $actor_id;
        if (is_dir($id_dir)) {
            return $id_dir;
        }
        $slug = $this->actorIdToSlug($actor_id);
        if ($slug !== '') {
            $slug_dir = $actors_dir . DIRECTORY_SEPARATOR . $slug;
            if (is_dir($slug_dir)) {
                return $slug_dir;
            }
        }
        return '';
    }

    /**
     * Resolve actor_id to slug for directory lookup (registry or DB, then static fallback).
     *
     * @param int $actor_id
     * @return string directory name (e.g. wolfie) for actors/{slug}
     */
    private function actorIdToSlug($actor_id)
    {
        $id = (int) $actor_id;
        if ($this->db) {
            $actors_table = $this->table_prefix . 'actors';
            $row = $this->db->fetch(
                'SELECT slug FROM ' . $this->db->quoteIdentifier($actors_table) . ' WHERE actor_id = :aid LIMIT 1',
                array('aid' => $id)
            );
            if ($row && isset($row['slug']) && $row['slug'] !== '') {
                return $row['slug'];
            }
        }
        $reg_path = $this->base_path . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'actors' . DIRECTORY_SEPARATOR . 'registry.json';
        if (is_file($reg_path) && is_readable($reg_path)) {
            $json = @file_get_contents($reg_path);
            if ($json !== false) {
                $data = json_decode($json, true);
                if (is_array($data) && isset($data['actors']) && is_array($data['actors'])) {
                    foreach ($data['actors'] as $key => $entry) {
                        if (!is_array($entry)) {
                            continue;
                        }
                        $aid = isset($entry['actor_id']) ? (int) $entry['actor_id'] : (isset($entry['id']) ? (int) $entry['id'] : null);
                        if ($aid === $id) {
                            if (isset($entry['dir']) && $entry['dir'] !== '') {
                                $parts = preg_split('#[/\\\\]#', trim($entry['dir']));
                                return end($parts);
                            }
                            if (isset($entry['slug']) && $entry['slug'] !== '') {
                                return $entry['slug'];
                            }
                            if (isset($entry['actor_name']) && $entry['actor_name'] !== '') {
                                return $entry['actor_name'];
                            }
                            return is_string($key) ? $key : '';
                        }
                    }
                }
            }
        }
        $map = array(0 => 'system', 1 => 'wolfie', 3 => 'rose', 4 => 'eris', 5 => 'metis', 19 => 'anubis', 25 => 'vishwakarma', 42 => 'antigravity', 1000 => 'kiro', 1001 => 'windsurf', 1003 => 'cursor', 1004 => 'warp', 1005 => 'cascade', 10000 => 'root', 2038 => 'lilith');
        return isset($map[$id]) ? $map[$id] : '';
    }

    /**
     * Get all skills for an actor (from profile and skills/*.md).
     *
     * @param int $actor_id
     * @return array list of skill arrays ('name' => string, 'proficiency' => string optional, 'version' => string optional)
     */
    public function getActorSkills($actor_id)
    {
        if (isset($this->skills_cache[$actor_id])) {
            return $this->skills_cache[$actor_id];
        }
        $skills = array();
        $actor_dir = $this->getActorDir($actor_id);
        if ($actor_dir === '') {
            $this->skills_cache[$actor_id] = array();
            return array();
        }
        $profile_file = $actor_dir . DIRECTORY_SEPARATOR . 'profile.md';
        if (file_exists($profile_file) && is_readable($profile_file)) {
            $content = file_get_contents($profile_file);
            $parsed = $this->parseSkillsFromContent($content);
            foreach ($parsed as $s) {
                $skills[] = $s;
            }
        }
        $skills_dir = $actor_dir . DIRECTORY_SEPARATOR . 'skills';
        if (is_dir($skills_dir)) {
            $files = glob($skills_dir . DIRECTORY_SEPARATOR . '*.md');
            if (is_array($files)) {
                foreach ($files as $file) {
                    if (!is_readable($file)) {
                        continue;
                    }
                    $content = file_get_contents($file);
                    $parsed = $this->parseSkillsFromContent($content);
                    foreach ($parsed as $s) {
                        $skills[] = $s;
                    }
                }
            }
        }
        $unique = array();
        foreach ($skills as $s) {
            $name = isset($s['name']) ? $s['name'] : '';
            if ($name !== '') {
                $unique[$name] = $s;
            }
        }
        $this->skills_cache[$actor_id] = array_values($unique);
        return $this->skills_cache[$actor_id];
    }

    /**
     * Parse lupopedia.skills YAML block from file content (tolerates whitespace and line endings).
     *
     * @param string $content
     * @return array list of skill arrays
     */
    private function parseSkillsFromContent($content)
    {
        $skills = array();
        $content = str_replace("\r\n", "\n", $content);
        $content = str_replace("\r", "\n", $content);
        if (!preg_match('/lupopedia\.skills\s*:\s*\n(.*?)(?=\n\s*\n|\n---|\n#|\n[a-zA-Z_][a-zA-Z0-9_]*\.|\Z)/s', $content, $block)) {
            return $skills;
        }
        $yaml = $block[1];
        $lines = explode("\n", $yaml);
        $current = array();
        foreach ($lines as $line) {
            $line = rtrim($line, " \t\r\n");
            if (preg_match('/^\s*-\s*name\s*:\s*["\']([^"\']*)["\']\s*$/', $line, $m)) {
                if (!empty($current) && isset($current['name'])) {
                    $skills[] = $current;
                }
                $current = array('name' => trim($m[1]));
            } elseif (preg_match('/^\s*-\s*name\s*:\s*([^\s#][^#]*?)\s*$/', $line, $m)) {
                if (!empty($current) && isset($current['name'])) {
                    $skills[] = $current;
                }
                $current = array('name' => trim($m[1]));
            } elseif (preg_match('/^\s+(\w+)\s*:\s*["\']([^"\']*)["\']\s*$/', $line, $m)) {
                $key = $m[1];
                $val = trim($m[2]);
                if ($key === 'name' && !isset($current['name'])) {
                    $current['name'] = $val;
                } elseif ($key === 'proficiency') {
                    $current['proficiency'] = $val;
                } elseif ($key === 'version') {
                    $current['version'] = $val;
                }
            } elseif (preg_match('/^\s+(\w+)\s*:\s*([^\s#][^#]*?)\s*$/', $line, $m)) {
                $key = $m[1];
                $val = trim($m[2]);
                if ($key === 'name' && !isset($current['name'])) {
                    $current['name'] = $val;
                } elseif ($key === 'proficiency') {
                    $current['proficiency'] = $val;
                } elseif ($key === 'version') {
                    $current['version'] = $val;
                }
            }
        }
        if (!empty($current) && isset($current['name'])) {
            $skills[] = $current;
        }
        return $skills;
    }

    /**
     * Check if actor has a specific skill, optionally at minimum proficiency.
     *
     * @param int $actor_id
     * @param string $skill_name
     * @param string|null $min_proficiency beginner|intermediate|expert|master or null for any
     * @return bool
     */
    public function hasSkill($actor_id, $skill_name, $min_proficiency = null)
    {
        $skills = $this->getActorSkills($actor_id);
        foreach ($skills as $skill) {
            $name = isset($skill['name']) ? $skill['name'] : '';
            if ($name !== $skill_name) {
                continue;
            }
            if ($min_proficiency === null) {
                return true;
            }
            $levels = array('beginner' => 1, 'intermediate' => 2, 'expert' => 3, 'master' => 4);
            $actor_level = isset($skill['proficiency']) ? $skill['proficiency'] : 'beginner';
            $actor_val = isset($levels[$actor_level]) ? $levels[$actor_level] : 0;
            $min_val = isset($levels[$min_proficiency]) ? $levels[$min_proficiency] : 0;
            return $actor_val >= $min_val;
        }
        return false;
    }

    /**
     * Get skill details from skills/{skill_name}/README.md.
     *
     * @param string $skill_name
     * @return array|null 'name', 'path', 'readme', 'version' or null
     */
    public function getSkillDetails($skill_name)
    {
        $skill_dir = $this->base_path . DIRECTORY_SEPARATOR . 'skills' . DIRECTORY_SEPARATOR . str_replace(array('/', '\\'), '', $skill_name);
        $readme = $skill_dir . DIRECTORY_SEPARATOR . 'README.md';
        if (!file_exists($readme) || !is_readable($readme)) {
            return null;
        }
        $details = array(
            'name' => $skill_name,
            'path' => $skill_dir,
            'readme' => $readme,
        );
        $content = file_get_contents($readme);
        if (preg_match('/skill_version:\s*["\']?([^"\'\s]+)["\']?/', $content, $m)) {
            $details['version'] = $m[1];
        }
        return $details;
    }
}
