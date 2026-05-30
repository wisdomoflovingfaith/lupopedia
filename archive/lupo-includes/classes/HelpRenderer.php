<?php
/**
 * HelpRenderer — User-friendly help output for Lupopedia CLI and web.
 * Provides categorized help menu, quick reference, topic help, and context-sensitive tips.
 *
 * @package Lupopedia
 * @version 4.0.93
 */

class HelpRenderer
{
    private $version;
    private $context;

    public function __construct($context = null)
    {
        $this->version = $this->getVersion();
        $this->context = $context;
    }

    /**
     * Get Lupopedia version for display (PHP 5.3 safe).
     *
     * @return string
     */
    private function getVersion()
    {
        if (function_exists('get_lupo_version')) {
            return get_lupo_version();
        }
        if (defined('LUPOPEDIA_VERSION')) {
            return LUPOPEDIA_VERSION;
        }
        return '4.0.61';
    }

    /**
     * Format topic help with version banner and separator.
     *
     * @param string $title Topic title (e.g. "whoami (dual-identity context)")
     * @param string $content Body text
     * @return string
     */
    protected function formatTopicHelp($title, $content)
    {
        $v = $this->version;
        return "Lupopedia CLI v" . $v . " — " . $title . "\n" . str_repeat("=", 60) . "\n\n" . $content;
    }

    /**
     * Show main help menu (box-drawn categories).
     */
    public function showMainHelp()
    {
        $v = $this->version;
        $line = str_repeat('=', 62);
        echo "\n";
        echo $line . "\n";
        echo "                    LUPOPEDIA HELP v" . $v . "\n";
        echo $line . "\n";
        echo "\n";
        echo "  GETTING STARTED\n";
        echo "     whoami              Who am I? (identity context)\n";
        echo "     context             Full execution context (JSON)\n";
        echo "     help                This help menu\n";
        echo "     help --quick        Quick reference card\n";
        echo "     help --web          Open web help in browser\n";
        echo "\n";
        echo "  ACTORS & IDENTITY\n";
        echo "     actors [type]       List registered actors\n";
        echo "     use <actor_id>      Switch local identity to an actor\n";
        echo "     switch <actor_id>   Alias for use\n";
        echo "     register <name> <type>  Register this environment as an actor\n";
        echo "\n";
        echo "  CHANNELS & MESSAGING\n";
        echo "     channels            List available channels\n";
        echo "     threads <channel_id>   List threads in a channel\n";
        echo "     join <channel_id>   Join a channel\n";
        echo "     messages <channel_id> [thread_id]  List last 20 messages\n";
        echo "     send <channel_id> <msg> [thread_id]  Send a message\n";
        echo "\n";
        echo "  SYSTEM & CONFIGURATION\n";
        echo "     version             Show version information\n";
        echo "     doctor              System health check\n";
        echo "     doctor-context      Context/identity stack check (session file, DB, registry, dual-identity)\n";
        echo "     docs                Show documentation hub path\n";
        echo "     docs [topic]        Show path to topic doc\n";
        echo "     auth / who          Show current authenticated user\n";
        echo "     actor-context       Show actor context with auth (Antigravity)\n";
        echo "\n";
        echo "  ADVANCED\n";
        echo "     nodes               List federation nodes\n";
        echo "     artifacts <node_id> List artifacts by federation node\n";
        echo "     tasks               List your active tasks\n";
        echo "     see <url>           Resolve canonical URL to repo .md file\n";
        echo "\n";
        echo "  TOPIC HELP\n";
        echo "     help whoami         Detailed help for whoami (dual-identity)\n";
        echo "     help context        Detailed help for context (JSON)\n";
        echo "     help actors         Help for actors command\n";
        echo "     help version        Help for version\n";
        echo "     help doctor         Help for doctor (health check)\n";
        echo "     help doctor-context Help for doctor-context (identity stack)\n";
        echo "     help see            Help for see (URL resolution)\n";
        echo "     help auth            Help for auth / actor-context\n";
        echo "     help list            TL;DR — HELP, FLAME, WOLFIE, routing, architecture\n";
        echo "\n";
        echo "  EXIT CODES\n";
        echo "     0  Success\n";
        echo "     1  General error\n";
        echo "     2  Invalid command\n";
        echo "\n";
        echo "  Documentation: docs/HELP.md\n";
        echo "  Version history: docs/version.md\n";
        echo "\n";
        $suggestions = $this->getContextualSuggestions();
        if (!empty($suggestions)) {
            echo "  TIPS FOR YOU:\n";
            foreach ($suggestions as $s) {
                echo "     * " . $s . "\n";
            }
            echo "\n";
        }
        echo $line . "\n\n";
    }

    /**
     * Show quick reference card (one-liner commands).
     */
    public function showQuickRef()
    {
        $line = str_repeat('=', 62);
        echo "\n";
        echo $line . "\n";
        echo "                 LUPOPEDIA QUICK REFERENCE\n";
        echo $line . "\n";
        echo "\n";
        echo "  whoami     Current identity (human + agent + session mode)\n";
        echo "  context    Full context (JSON)\n";
        echo "  actors     List actors\n";
        echo "  use <id>   Switch to actor by ID (alias: switch)\n";
        echo "  channels   List channels\n";
        echo "  threads N  List threads in channel N\n";
        echo "  join N     Join channel N\n";
        echo "  messages N [T]  List messages in channel N (optional thread T)\n";
        echo "  send N msg [T]  Send message to channel N\n";
        echo "  version    Show version\n";
        echo "  doctor     System health check\n";
        echo "  docs       Documentation hub\n";
        echo "  help       Full help\n";
        echo "\n";
        echo "  Invoke: php lupo-bin/lupo.php <command>\n";
        echo "  Web: http://www.lupopedia.com/help\n";
        echo "\n";
        echo $line . "\n\n";
    }

    /**
     * Show topic-specific help.
     *
     * @param string $topic Topic name (whoami, context, actors, workspace, flare, version)
     */
    public function showTopicHelp($topic)
    {
        $topic = strtolower(trim($topic));
        $topics = array(
            'whoami' => array($this, 'getWhoamiHelp'),
            'context' => array($this, 'getContextHelp'),
            'actors' => array($this, 'getActorsHelp'),
            'workspace' => array($this, 'getWorkspaceHelp'),
            'flare' => array($this, 'getFlareHelp'),
            'version' => array($this, 'getVersionHelp'),
            'doctor' => array($this, 'getDoctorHelp'),
            'doctor-context' => array($this, 'getDoctorContextHelp'),
            'see' => array($this, 'getSeeHelp'),
            'auth' => array($this, 'getAuthHelp'),
            'list' => array($this, 'getListHelp')
        );
        if (isset($topics[$topic])) {
            echo call_user_func($topics[$topic]);
        } else {
            echo "No help available for topic: " . $topic . "\n";
            echo "Available topics: whoami, context, actors, workspace, flare, version, doctor, doctor-context, see, auth, list\n";
            echo "Try: php lupo-bin/lupo.php help\n";
        }
    }

    /**
     * Get context-aware help suggestions based on session mode.
     *
     * @return array List of suggestion strings
     */
    public function getContextualSuggestions()
    {
        $suggestions = array();
        if (!$this->context || !is_array($this->context)) {
            return $suggestions;
        }
        $mode = isset($this->context['session_mode']) ? $this->context['session_mode'] : 'system';
        if ($mode === 'human_direct') {
            $suggestions[] = "Try 'use <actor_id>' to act as an agent (e.g. use 1003 for cursor).";
            $suggestions[] = "Use 'whoami' to confirm your identity.";
        } elseif ($mode === 'hybrid') {
            $human = isset($this->context['human_actor_name']) ? $this->context['human_actor_name'] : 'unknown';
            $suggestions[] = "You are acting for " . $human . ".";
            $suggestions[] = "Use 'whoami' to see human identity and active agent.";
        } elseif ($mode === 'autonomous_agent') {
            $suggestions[] = "You are running autonomously (no paired human).";
            $suggestions[] = "Session context can be set in lupo-database/session.md.";
        }
        return $suggestions;
    }

    /**
     * Get web help URL.
     *
     * @return string
     */
    public function getWebHelpUrl()
    {
        return 'http://www.lupopedia.com/help';
    }

    /**
     * Open web help in system browser (Windows / Darwin / Linux).
     * Logs failure and echoes URL if browser could not be opened.
     */
    public function openWebHelp()
    {
        $url = $this->getWebHelpUrl();
        $os = strtoupper(substr(PHP_OS, 0, 3));
        $command = '';
        if ($os === 'WIN') {
            $command = 'start "" ' . escapeshellarg($url);
        } elseif ($os === 'DAR') {
            $command = 'open ' . escapeshellarg($url);
        } else {
            $command = 'xdg-open ' . escapeshellarg($url) . ' 2>/dev/null';
        }
        $output = array();
        $return_var = 0;
        exec($command, $output, $return_var);
        if ($return_var !== 0) {
            error_log('Failed to open web help: ' . $command);
            echo "Could not open browser. Web help is at: " . $url . "\n";
        } else {
            echo "Opening web help: " . $url . "\n";
        }
    }

    protected function getWhoamiHelp()
    {
        $content = "WHOAMI displays the current execution context with three identity layers:\n\n";
        $content .= "  1. Effective Actor — The actor that owns the session (from lupo_sessions or session.md).\n";
        $content .= "  2. Human Identity — Derived from paired_actor_id when the effective actor is an agent.\n";
        $content .= "  3. Active Agent — The active agent persona; else 'none'.\n\n";
        $content .= "Session Mode: human_direct | hybrid | autonomous_agent | system\n\n";
        $content .= "Full reference: docs/lupopedia_whoami_readme.md\n";
        return $this->formatTopicHelp("whoami (dual-identity context)", $content);
    }

    protected function getContextHelp()
    {
        $content = "CONTEXT outputs a flat JSON object (same data as whoami --verbose).\n\n";
        $content .= "Resolution: session.md first, then lupo_sessions, then registry/defaults.\n";
        $content .= "context_source values: session.md, session.md + registry, lupo_sessions, default.\n\n";
        $content .= "Full reference: docs/lupopedia_whoami_readme.md\n";
        return $this->formatTopicHelp("context (JSON runtime context)", $content);
    }

    protected function getActorsHelp()
    {
        $content = "  actors [type]    List registered actors (optional type filter).\n";
        $content .= "  use <actor_id>   Switch local identity to an existing actor (writes .lupo_actor).\n";
        $content .= "  switch <actor_id>  Alias for use.\n";
        $content .= "  register <name> <type>  Register this environment as an actor.\n\n";
        $content .= "See docs/actors.md and docs/lupopedia_whoami_readme.md.\n";
        return $this->formatTopicHelp("actors — list and switch actors", $content);
    }

    protected function getWorkspaceHelp()
    {
        $content = "Each actor has a workspace: /lupo-actors/{actor_name}/\n";
        $content .= "Resolved from session or session.md. See docs/lupopedia_whoami_readme.md Section 6.\n";
        return $this->formatTopicHelp("workspace — actor workspace path", $content);
    }

    protected function getFlareHelp()
    {
        $content = "FLARE headers identify files (actor_name, channel_id, system_version, etc.).\n";
        $content .= "Required headers: docs/doctrine/required_flare_headers.md\n";
        $content .= "Doctrine: docs/doctrine/FLARE/FLARE_DOCTRINE.md (if present).\n";
        return $this->formatTopicHelp("FLARE — File-Level Inference Protocol", $content);
    }

    protected function getVersionHelp()
    {
        $content = "  version    Show current version and link to docs/version.md.\n";
        $content .= "  History and upgrade notes: docs/version.md\n";
        return $this->formatTopicHelp("version", $content);
    }

    protected function getDoctorHelp()
    {
        $content = "  doctor [--check-actors]   System health check (or via DOCTOR actor 1009 when present).\n\n";
        $content .= "Checks:\n";
        $content .= "  * Database connectivity\n";
        $content .= "  * Registry file (lupo-database/lupopedia/actors/registry.json or actor_id/registry.json)\n";
        $content .= "  * Session file (lupo-database/session.md, optional for CLI fallback)\n";
        $content .= "  * Context kernel: identity drift (split-brain, pairing)\n";
        $content .= "  * With --check-actors: actor workspace/namespace consistency (lupo_actors table)\n\n";
        $content .= "Fix any [FAIL] or [WARN]; if context kernel reports issues, run doctor-context [--repair].\n";
        $content .= "Full reference: docs/DOCTOR_HEALTH_CHECK.md\n";
        return $this->formatTopicHelp("doctor — system health check", $content);
    }

    protected function getDoctorContextHelp()
    {
        $content = "  doctor-context [--repair]   Validate identity stack via ContextKernel; optionally repair session.md drift.\n\n";
        $content .= "Uses ContextKernel (single resolution). Checks: session file, DB session, registry, resolved context (effective, human, agent, session_mode, context_source). Surfaces kernel validation issues (e.g. split-brain, paired actor).\n\n";
        $content .= "  --repair   When session file and DB conflict (or kernel reports drift), overwrite session.md with kernel/DB values so identity is consistent. Run after doctor-context shows warnings.\n\n";
        $content .= "When session file and DB disagree, context_source is 'lupo_sessions (session.md ignored due to conflict)'. Use doctor-context --repair to sync session.md to the canonical identity.\n";
        return $this->formatTopicHelp("doctor-context — identity stack check", $content);
    }

    protected function getSeeHelp()
    {
        $content = "  see <url>    Resolve canonical URL to local .md file (flame.see index).\n\n";
        $content .= "Resolves URLs to repository markdown paths. Uses flame.see index when available (e.g. artifacts/index/flame_see_index.json).\n";
        return $this->formatTopicHelp("see — resolve canonical URL to repo file", $content);
    }

    protected function getAuthHelp()
    {
        $content = "  auth            Show current authenticated user (from session / lupo_auth_service).\n";
        $content .= "  who             Alias for auth.\n";
        $content .= "  actor-context   Show full actor context with auth status (for Antigravity conflict resolution).\n\n";
        $content .= "Antigravity uses auth and actor context to decide conflict resolution authority (human vs paired agent vs autonomous). See docs/auth.md.\n";
        return $this->formatTopicHelp("auth — authenticated user and actor context", $content);
    }

    /**
     * TL;DR — HELP, FLAME, WOLFIE, routing, core architecture.
     *
     * @return string
     */
    protected function getListHelp()
    {
        $content = "  list            This topic: ultra-concise TL;DR of the system.\n\n";
        $content .= "Sections: (1) HELP — CLI entry, commands, HelpRenderer, docs/HELP.md. ";
        $content .= "(2) FLAME — flame.init / flame.close, pre_actions / post_actions, flame.see. ";
        $content .= "(3) WOLFIE — actor_id 1 = main governing agent; actor_id 0 = System. ";
        $content .= "(4) ROUTING — index.php → bootstrap → lupo_route_slug → ContextResolver. ";
        $content .= "(5) Big picture — actors, FLARE, dual-identity, channels, session-first, version.\n\n";
        $content .= "Full TL;DR (tables, search tips): docs/TLDR_LUPOPEDIA.md\n";
        $content .= "Thread copy: lupo-channels/0/threads/VERSION_4.0.61/tldr.md\n";
        return $this->formatTopicHelp("list — TL;DR (HELP • FLAME • WOLFIE • routing • architecture)", $content);
    }
}
