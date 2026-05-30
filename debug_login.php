<?php
/**
 * debug_login.php
 * Pre-login session debug page.
 * Shows bootstrap state, session hash computation, error_log path, and cookie state.
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

define('LUPOPEDIA_PATH', __DIR__);
define('LUPOPEDIA_PUBLIC_PATH', '/' . basename(__DIR__));

require_once LUPOPEDIA_PATH . '/includes/classes/LupopediaConfigResolver.php';
$lupopediaConfigPath = LupopediaConfigResolver::resolve(LUPOPEDIA_PATH, LUPOPEDIA_PUBLIC_PATH);
if (!$lupopediaConfigPath) {
    die("Lupopedia config not found.");
}
define('LUPOPEDIA_CONFIG_PATH', $lupopediaConfigPath);
require_once LUPOPEDIA_CONFIG_PATH;

// bootstrap starts the session and sets $lupo_session
require_once LUPOPEDIA_PATH . '/includes/bootstrap.php';

// --- Gather debug data AFTER bootstrap ---

$php_sid     = session_id();
$error_log_path = ini_get('error_log');
$ip          = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
$ua          = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

// Compute identity hash - pass null for user_id (pre-login, no user_id yet)
$ident_hash  = '';
$hash_debug  = array();
if (class_exists('App\Auth\Session')) {
    ob_start();
    $ident_hash = App\Auth\Session::computeIdentityHash($ip, $ua, null);
    ob_end_clean();
    // Recompute manually to show the inputs without relying on error_log capture
    $hash_debug['class_c_ip'] = implode('.', array_slice(explode('.', $ip), 0, 3));
    $hash_debug['user_id']    = 'unknown';
    $hash_debug['user_agent'] = $ua;
    $salt = defined('LUPO_SESSION_SALT') ? LUPO_SESSION_SALT : '(LUPO_SESSION_SALT not defined)';
    $hash_debug['salt_defined'] = defined('LUPO_SESSION_SALT') ? 'YES' : 'NO — hash will be wrong';
    $hash_debug['input_string'] = $hash_debug['class_c_ip'] . '|' . $hash_debug['user_id'] . '|' . $ua . '|(salt)';
    $hash_debug['result'] = $ident_hash;
}

// Bootstrap session state
$bootstrap_actor_id = null;
$bootstrap_session_id = null;
$bootstrap_session_obj = null;
if (isset($GLOBALS['lupo_session']) && $GLOBALS['lupo_session']) {
    $s = $GLOBALS['lupo_session'];
    $bootstrap_session_id = method_exists($s, 'getSessionId') ? $s->getSessionId() : null;
    $bootstrap_actor_id   = method_exists($s, 'getActorId')   ? $s->getActorId()   : null;
    $bootstrap_session_obj = $s;
}

// Write a test entry to error_log so user can confirm the path works
$test_marker = 'DEBUG_LOGIN_TEST_' . date('His');
error_log('[debug_login.php] ' . $test_marker . ' — error_log path confirmation');
error_log('[debug_login.php] SESSION_HASH_DEBUG: class_c_ip=' . $hash_debug['class_c_ip']
    . ' user_id=unknown user_agent=' . substr($ua, 0, 80) . ' hash=' . $ident_hash);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Debug Login — Lupopedia</title>
    <style>
        body  { font-family: monospace; padding: 20px; background: #1a1a1a; color: #d4d4d4; line-height: 1.5; }
        h1    { color: #e8c97d; border-bottom: 2px solid #555; padding-bottom: 8px; }
        h2    { color: #9cdcfe; margin-top: 0; border-bottom: 1px solid #444; padding-bottom: 4px; }
        .box  { background: #252526; padding: 16px; border: 1px solid #444; margin-bottom: 16px; border-radius: 4px; }
        .ok   { color: #4ec9b0; font-weight: bold; }
        .warn { color: #f0a050; font-weight: bold; }
        .bad  { color: #f44747; font-weight: bold; }
        .key  { color: #9cdcfe; }
        .val  { color: #ce9178; word-break: break-all; }
        .note { color: #888; font-style: italic; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #444; padding: 6px 10px; text-align: left; }
        th { background: #333; color: #9cdcfe; }
        form input[type=text], form input[type=password] {
            background: #1e1e1e; border: 1px solid #555; color: #d4d4d4;
            padding: 6px 10px; font-family: monospace; width: 260px; }
        form button { background: #0e639c; color: #fff; border: none;
            padding: 8px 18px; cursor: pointer; font-family: monospace; }
        a { color: #4ec9b0; }
        pre { background: #1e1e1e; padding: 10px; overflow-x: auto; color: #d4d4d4; font-size: 12px; }
    </style>
</head>
<body>
<h1>debug_login.php — Pre-Login Session Debug</h1>

<!-- ===== 1. ERROR LOG ===== -->
<div class="box">
    <h2>1. Error Log</h2>
    <p>
        <span class="key">error_log path (php.ini):</span><br>
        <span class="val"><?php echo $error_log_path ? htmlspecialchars($error_log_path) : '<em class="warn">Not set — writing to stderr / web server log</em>'; ?></span>
    </p>
    <p>
        <span class="key">Writable?</span>
        <?php if ($error_log_path && file_exists($error_log_path)): ?>
            <span class="<?php echo is_writable($error_log_path) ? 'ok' : 'bad'; ?>">
                <?php echo is_writable($error_log_path) ? 'YES' : 'NO — error_log() calls will silently fail'; ?>
            </span>
        <?php elseif ($error_log_path): ?>
            <span class="warn">File does not exist yet (will be created on first write)</span>
        <?php else: ?>
            <span class="note">No file path — check your web server error log</span>
        <?php endif; ?>
    </p>
    <p class="note">
        A test entry was just written: <strong><?php echo htmlspecialchars($test_marker); ?></strong><br>
        Search your error log for that string to confirm the path is correct.
    </p>
    <p class="note">
        SESSION_HASH_DEBUG entries are also written on every call to computeIdentityHash().<br>
        Look for <code>[debug_login.php] SESSION_HASH_DEBUG:</code> in the same log.
    </p>
</div>

<!-- ===== 2. SESSION HASH COMPUTATION ===== -->
<div class="box">
    <h2>2. Session Identity Hash (computeIdentityHash)</h2>
    <p class="note">Base hash inputs — MUST NOT include actor_id or auth_user_id</p>
    <table>
        <tr><th>Input</th><th>Value</th></tr>
        <tr>
            <td class="key">class_c_ip</td>
            <td class="val"><?php echo htmlspecialchars($hash_debug['class_c_ip'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="key">user_id</td>
            <td class="val"><?php echo htmlspecialchars($hash_debug['user_id'] ?? ''); ?>
                <span class="note">(pre-login placeholder)</span></td>
        </tr>
        <tr>
            <td class="key">user_agent</td>
            <td class="val"><?php echo htmlspecialchars($hash_debug['user_agent'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="key">LUPO_SESSION_SALT defined?</td>
            <td class="<?php echo ($hash_debug['salt_defined'] ?? '') === 'YES' ? 'ok' : 'bad'; ?>">
                <?php echo htmlspecialchars($hash_debug['salt_defined'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="key">input string (salt redacted)</td>
            <td class="val"><?php echo htmlspecialchars($hash_debug['input_string'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="key"><strong>resulting hash</strong></td>
            <td class="val"><strong><?php echo htmlspecialchars($hash_debug['result'] ?? ''); ?></strong></td>
        </tr>
    </table>
    <p class="note">This hash is written to <code>lupo_sessions.session_identity_hash</code> on session creation.</p>
</div>

<!-- ===== 3. PHP SESSION + COOKIES ===== -->
<div class="box">
    <h2>3. PHP Session &amp; Cookies</h2>
    <table>
        <tr>
            <td class="key">PHP session_id()</td>
            <td class="val"><?php echo $php_sid ? htmlspecialchars($php_sid) : '<span class="bad">EMPTY — session not started</span>'; ?></td>
        </tr>
        <tr>
            <td class="key">session_name()</td>
            <td class="val"><?php echo htmlspecialchars(session_name()); ?></td>
        </tr>
        <tr>
            <td class="key">session.cookie_domain</td>
            <td class="val"><?php echo htmlspecialchars(ini_get('session.cookie_domain') ?: '(empty)'); ?></td>
        </tr>
        <tr>
            <td class="key">session.cookie_secure</td>
            <td class="val"><?php
                $cs = ini_get('session.cookie_secure');
                $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                echo htmlspecialchars($cs ? 'ON' : 'OFF');
                if ($cs && $proto === 'http') {
                    echo ' <span class="bad">WARNING: cookie_secure=1 but you are on HTTP — cookie will not be sent back</span>';
                }
            ?></td>
        </tr>
        <tr>
            <td class="key">session.cookie_samesite</td>
            <td class="val"><?php echo htmlspecialchars(ini_get('session.cookie_samesite') ?: '(not set)'); ?></td>
        </tr>
    </table>

    <h3 style="color:#9cdcfe;margin-top:14px;">session_get_cookie_params() — Active Params (BEFORE login)</h3>
    <p class="note">These are the params PHP will use when it sends the PHPSESSID cookie on this request.
        After login, check debug_loggedin.php section 3 to see what params were used then.</p>
    <?php
    $scp = session_get_cookie_params();
    $expected_path = (defined('LUPOPEDIA_PUBLIC_PATH') && LUPOPEDIA_PUBLIC_PATH !== '')
        ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') . '/'
        : '/';
    $path_ok = ($scp['path'] === $expected_path);
    ?>
    <table>
        <tr><th>Param</th><th>Value</th><th>Expected</th></tr>
        <tr>
            <td class="key">path</td>
            <td class="<?php echo $path_ok ? 'ok' : 'bad'; ?>"><?php echo htmlspecialchars($scp['path']); ?></td>
            <td class="<?php echo $path_ok ? 'ok' : 'warn'; ?>"><?php echo htmlspecialchars($expected_path); ?>
                <?php if (!$path_ok) echo ' <span class="bad">MISMATCH — duplicate cookie risk</span>'; ?></td>
        </tr>
        <tr>
            <td class="key">domain</td>
            <td class="val"><?php echo htmlspecialchars($scp['domain'] !== '' ? $scp['domain'] : '(empty — uses current host)'); ?></td>
            <td class="note"><?php echo htmlspecialchars($_SERVER['HTTP_HOST'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="key">lifetime</td>
            <td class="val"><?php echo htmlspecialchars($scp['lifetime']); ?> <?php echo ($scp['lifetime'] === 0) ? '<span class="note">(session cookie)</span>' : ''; ?></td>
            <td class="note">0 (session)</td>
        </tr>
        <tr>
            <td class="key">secure</td>
            <td class="val"><?php echo $scp['secure'] ? '<span class="ok">yes</span>' : 'no'; ?></td>
            <td class="note"></td>
        </tr>
        <tr>
            <td class="key">httponly</td>
            <td class="<?php echo $scp['httponly'] ? 'ok' : 'warn'; ?>"><?php echo $scp['httponly'] ? 'yes' : 'no — JS can read this cookie'; ?></td>
            <td class="note">yes (required)</td>
        </tr>
        <tr>
            <td class="key">samesite</td>
            <td class="val"><?php echo htmlspecialchars(isset($scp['samesite']) ? ($scp['samesite'] ?: '(not set)') : '(not set)'); ?></td>
            <td class="note">Lax</td>
        </tr>
    </table>

    <h3 style="color:#9cdcfe;margin-top:14px;">All Cookies Received</h3>
    <p class="note">PHP merges cookies with the same name regardless of Path; the browser sends the most-specific
        path first. If PHPSESSID exists for both <code>/lupopedia/</code> and <code>/</code>, PHP sees only the
        <code>/lupopedia/</code> value. Duplicate cookies mean login tokens can be silently shadowed.</p>
    <?php if (empty($_COOKIE)): ?>
        <p class="bad">No cookies. Either this is the first visit or cookies are being blocked.</p>
    <?php else: ?>
        <table>
            <tr><th>Name</th><th>Value (truncated)</th><th>Match?</th></tr>
            <?php foreach ($_COOKIE as $cn => $cv): ?>
                <tr>
                    <td class="key"><?php echo htmlspecialchars($cn); ?></td>
                    <td class="val"><?php echo htmlspecialchars(substr($cv, 0, 64)); ?><?php echo strlen($cv) > 64 ? '...' : ''; ?></td>
                    <td><?php
                        if ($cn === session_name()) echo '<span class="ok">PHPSESSID</span>';
                        elseif ($cn === 'lupo_session') echo '<span class="ok">lupo_session cookie</span>';
                        elseif (preg_match('/^[a-f0-9]{64}$/', $cv)) echo '<span class="warn">64-char hex (possible session_id)</span>';
                        else echo '<span class="note">--</span>';
                    ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <p class="note">Reload this page -- PHPSESSID should remain the same if cookies are working.</p>
</div>

<!-- ===== 4. BOOTSTRAP SESSION STATE ===== -->
<div class="box">
    <h2>4. Bootstrap Session State ($lupo_session after bootstrap.php)</h2>
    <?php if (!isset($GLOBALS['lupo_session']) || !$GLOBALS['lupo_session']): ?>
        <p class="bad">$GLOBALS['lupo_session'] is null — Session object was not created by bootstrap.</p>
        <p class="note">Check bootstrap.php: requires $GLOBALS['mydatabase'] to be set and App\Auth\Session class to exist.</p>
    <?php else: ?>
        <table>
            <tr>
                <td class="key">getSessionId()</td>
                <td class="val"><?php echo $bootstrap_session_id ? htmlspecialchars($bootstrap_session_id) : '<span class="bad">null</span>'; ?></td>
            </tr>
            <tr>
                <td class="key">getActorId() (after validateSession)</td>
                <td class="val">
                    <?php if ($bootstrap_actor_id): ?>
                        <span class="ok"><?php echo (int)$bootstrap_actor_id; ?> — session maps to an actor (already logged in)</span>
                    <?php else: ?>
                        <span class="note">null / 0 — anonymous session (not logged in)</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php if (isset($GLOBALS['lupo_session']->current) && $GLOBALS['lupo_session']->current): ?>
            <?php $cur = $GLOBALS['lupo_session']->current; ?>
            <tr>
                <td class="key">session_identity_hash (DB row)</td>
                <td class="val"><?php echo htmlspecialchars($cur->session_identity_hash ?? 'null'); ?></td>
            </tr>
            <tr>
                <td class="key">is_named</td>
                <td class="val"><?php echo htmlspecialchars($cur->is_named ?? '0'); ?></td>
            </tr>
            <tr>
                <td class="key">last_activity_ymdhis</td>
                <td class="val"><?php echo htmlspecialchars($cur->last_activity_ymdhis ?? 'null'); ?></td>
            </tr>
            <tr>
                <td class="key">computed hash matches DB hash?</td>
                <td class="<?php echo ($cur->session_identity_hash === $ident_hash) ? 'ok' : 'bad'; ?>">
                    <?php echo ($cur->session_identity_hash === $ident_hash) ? 'YES' : 'NO — mismatch (hash changed since session was created)'; ?>
                </td>
            </tr>
            <?php endif; ?>
        </table>
    <?php endif; ?>
</div>

<!-- ===== 5. LOGIN FORM (trace auth flow) ===== -->
<div class="box">
    <h2>5. Test Login (traces through login.php AuthService)</h2>
    <p class="note">Submit credentials to test the full auth flow. This posts to login.php — watch your error_log for SESSION_DEBUG entries.</p>
    <form action="login.php?redirect=debug_loggedin.php" method="POST">
        <p>
            <label class="key">Username:</label><br>
            <input type="text" name="username" autocomplete="username">
        </p>
        <p>
            <label class="key">Password:</label><br>
            <input type="password" name="password" autocomplete="current-password">
        </p>
        <button type="submit">Login via login.php</button>
    </form>
    <p class="note" style="margin-top:8px;">
        After login, you will be redirected to admin.php.<br>
        Then visit <a href="debug_loggedin.php">debug_loggedin.php</a> to verify the session was created in the DB.
    </p>
</div>

<!-- ===== 6. POST DIRECTLY (debug_loggedin trace) ===== -->
<div class="box">
    <h2>6. Direct POST to debug_loggedin.php</h2>
    <form action="debug_loggedin.php" method="POST">
        <input type="hidden" name="debug_post_test" value="1">
        <button type="submit">POST to debug_loggedin.php</button>
    </form>
</div>

<!-- ===== 7. CONFIG ===== -->
<div class="box">
    <h2>7. Config &amp; Environment</h2>
    <table>
        <tr>
            <td class="key">LUPOPEDIA_CONFIG_PATH</td>
            <td class="val"><?php echo htmlspecialchars(LUPOPEDIA_CONFIG_PATH); ?></td>
        </tr>
        <tr>
            <td class="key">LUPOPEDIA_DEBUG</td>
            <td class="val"><?php echo defined('LUPOPEDIA_DEBUG') ? (LUPOPEDIA_DEBUG ? '<span class="ok">true</span>' : '<span class="warn">false — SESSION: logs suppressed</span>') : '<span class="bad">undefined</span>'; ?></td>
        </tr>
        <tr>
            <td class="key">LUPO_TABLE_PREFIX</td>
            <td class="val"><?php echo defined('LUPO_TABLE_PREFIX') ? htmlspecialchars(LUPO_TABLE_PREFIX) : '<span class="bad">undefined</span>'; ?></td>
        </tr>
        <tr>
            <td class="key">HTTP_HOST</td>
            <td class="val"><?php echo htmlspecialchars($_SERVER['HTTP_HOST'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="key">HTTPS</td>
            <td class="val"><?php echo isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? '<span class="ok">yes</span>' : '<span class="note">no (http)</span>'; ?></td>
        </tr>
        <tr>
            <td class="key">REMOTE_ADDR</td>
            <td class="val"><?php echo htmlspecialchars($ip); ?></td>
        </tr>
        <tr>
            <td class="key">php_uname / PHP version</td>
            <td class="val"><?php echo htmlspecialchars(PHP_VERSION . ' — ' . PHP_SAPI); ?></td>
        </tr>
    </table>
</div>

<!-- ===== 8. INCLUDED FILES ===== -->
<div class="box">
    <h2>8. Included Files</h2>
    <pre><?php echo htmlspecialchars(implode("\n", get_included_files())); ?></pre>
</div>

</body>
</html>
