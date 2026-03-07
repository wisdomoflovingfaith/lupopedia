<?php
/**
 * wolfie.headers: {
 *   file_path_from_root: "lupo-includes/classes/SkillService.php",
 *   system_version: "4.0.64",
 *   channel_id: 42,
 *   actor_id: 42,
 *   purpose: "Manages actor modular capabilities (skills) stored in the 'skills' subdirectory.",
 *   last_modified_utc: "20260307"
 * }
 */

class SkillService
{
    /** @var string Relative path to skills subdir in actor root */
    private $skillsSubdir;

    public function __construct()
    {
        $this->skillsSubdir = defined('LUPO_SKILLS_SUBDIR') ? LUPO_SKILLS_SUBDIR : 'skills';
    }

    /**
     * List all skills available for a given actor.
     * Skills are subdirectories under the actor's 'skills/' folder.
     *
     * @param string $actorPath Full real path to actor directory
     * @return array List of skill names (slugs)
     */
    public function listSkills($actorPath)
    {
        $skillsPath = rtrim($actorPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $this->skillsSubdir;
        if (!is_dir($skillsPath)) {
            return array();
        }

        $skills = array();
        $files = scandir($skillsPath);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..')
                continue;
            if (is_dir($skillsPath . DIRECTORY_SEPARATOR . $file)) {
                $skills[] = $file;
            }
        }
        return $skills;
    }

    /**
     * Get detail of a specific skill for an actor.
     * Looks for SKILL.json or SKILL.md in the skill subdirectory.
     *
     * @param string $actorPath Full real path to actor directory
     * @param string $skill     Skill name (slug)
     * @return array|null Skill info or null
     */
    public function getSkillInfo($actorPath, $skill)
    {
        $skillPath = rtrim($actorPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $this->skillsSubdir . DIRECTORY_SEPARATOR . $skill;
        if (!is_dir($skillPath)) {
            return null;
        }

        $info = array(
            'skill' => $skill,
            'path' => $skillPath,
        );

        $jsonFile = $skillPath . DIRECTORY_SEPARATOR . 'SKILL.json';
        if (is_file($jsonFile)) {
            $raw = file_get_contents($jsonFile);
            $data = json_decode($raw, true);
            if (is_array($data)) {
                $info = array_merge($info, $data);
            }
        }

        return $info;
    }
}
