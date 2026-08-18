<?php
/**
 * debug_loggedin.php
 * Post-login session verification page.
 * Shows what bootstrap resolved, what is in lupo_sessions, and traces error_log writes.
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

define('LUPOPEDIA_PATH', __DIR__);
require_once LUPOPEDIA_PATH . '/includes/classes/LupopediaConfigResolver.php';
define('LUPOPEDIA_PUBLIC_PATH', LupopediaConfigResolver::publicPathFromRequest(LUPOPEDIA_PATH));
$lupopediaConfigPath = LupopediaConfigResolver::resolve(LUPOPEDIA_PATH, LUPOPEDIA_PUBLIC_PATH);
if (!$lupopediaConfigPath) {
    die("Lupopedia config not found.");
}
define('LUPOPEDIA_CONFIG_PATH', $lupopediaConfigPath);
require_once LUPOPEDIA_CONFIG_PATH;

// bootstrap starts the session and sets $lupo_session
require_once LUPOPEDIA_PATH . '/includes/bootstrap.php';

// --- Gather data AFTER bootstrap ---

$db      = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
$php_sid = session_id();
$prefix  = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$table   = $prefix . 'sessions';

$ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

// Normalize user agent the same way Session class does
$normalized_ua = preg_replace('/[^A-Za-z0-9]/', '', $ua);
$normalized_ua = substr(strtolower($normalized_ua), 0, 200);

// Compute identity hash with correct 3-param call (user_id=null pre-login)
$ident_hash = '';
$hash_inputs = array();
if (class_exists('App\Auth\Session')) {
    $ident_hash = App\Auth\Session::computeIdentityHash($ip, $normalized_ua, null);
    $hash_inputs['class_c_ip'] = implode('.', array_slice(explode('.', $ip), 0, 3));
    $hash_inputs['user_id']    = 'unknown';
    $hash_inputs['salt_ok']    = defined('LUPO_SESSION_SALT') ? 'YES' : 'NO';
    $hash_inputs['hash']       = $ident_hash;
}

// Bootstrap session state
$bootstrap_actor_id   = null;
$bootstrap_session_id = null;
$bootstrap_db_row     = null;
if (isset($GLOBALS['lupo_session']) && $GLOBALS['lupo_session']) {
    $s = $GLOBALS['lupo_session'];
    $bootstrap_session_id = method_exists($s, 'getSessionId') ? $s->getSessionId() : null;
    $bootstrap_actor_id   = method_exists($s, 'getActorId')   ? $s->getActorId()   : null;
    if (isset($s->current)) {
        $bootstrap_db_row = $s->current;
    }
}

// AuthService state
$auth_current_user = null;
if (isset($GLOBALS['lupo_auth_service']) && $GLOBALS['lupo_auth_service']) {
    try {
        $auth_current_user = $GLOBALS['lupo_auth_service']->getCurrentUser();
    } catch (Exception $e) {
        $auth_current_user = array('_error' => $e->getMessage());
    }
}

// --- DB lookups: multiple strategies ---
$lookups = array();

if ($php_sid) {
    $lookups['PHPSESSID (session_id())'] = array(
        'value' => $php_sid,
        'col'   => 'session_id',
    );
}
if ($ident_hash) {
    $lookups['Computed Identity Hash'] = array(
        'value' => $ident_hash,
        'col'   => 'session_identity_hash',
    );
}
if (isset($_COOKIE['lupo_session'])) {
    $lookups['lupo_session cookie'] = array(
        'value' => $_COOKIE['lupo_session'],
        'col'   => 'session_id',
    );
}
foreach ($_COOKIE as $cname => $cval) {
    if ($cname === 'PHPSESSID' || $cname === 'lupo_session') continue;
    if (is_string($cval) && preg_match('/^[a-f0-9]{64}$/', $cval)) {
        $lookups["Cookie: {$cname} (64-char hex)"] = array(
            'value' => $cval,
            'col'   => 'session_id',
        );
    }
}

$lookup_results = array();
if ($db) {
    // Detect which columns actually exist to avoid query errors
    $known_cols = array('session_id', 'actor_id', 'session_identity_hash',
                        'last_activity_ymdhis', 'created_ymdhis', 'is_named',
                        'name_key', 'ip_hash', 'ua_hash', 'csrf_token', 'metadata');
    $select_cols = implode(', ', $known_cols);

    foreach ($lookups as $label => $info) {
        try {
            $row = $db->fetchRow(
                "SELECT {$select_cols} FROM {$table} WHERE {$info['col']} = :val LIMIT 1",
                array('val' => $info['value'])
            );
            $lookup_results[$label] = array(
                'value'  => $info['value'],
                'found'  => (bool) $row,
                'row'    => $row,
            );
        } catch (Exception $e) {
            $lookup_results[$label] = array('value' => $info['value'], 'error' => $e->getMessage());
        }
    }
}

// --- Recent sessions (last 10 minutes) ---
$recent_sessions = array();
$recent_err      = '';
if ($db) {
    try {
        require_once LUPOPEDIA_PATH . '/includes/classes/TimestampYmdhis.php';
        $cutoff = timestamp_ymdhis::subtractSeconds(timestamp_ymdhis::now(), 600);
        $recent_sessions = $db->fetchAll(
            "SELECT session_id, actor_id, last_activity_ymdhis, created_ymdhis,
                    session_identity_hash, is_named, name_key
             FROM {$table}
             WHERE last_activity_ymdhis >= :cutoff
             ORDER BY last_activity_ymdhis DESC
             LIMIT 15",
            array('cutoff' => $cutoff)
        );
    } catch (Exception $e) {
        $recent_err = $e->getMessage();
    }
}

// --- Error log ---
$error_log_path = ini_get('error_log');
$test_marker    = 'DEBUG_LOGGEDIN_' . date('His');
error_log('[debug_loggedin.php] ' . $test_marker . ' — bootstrap actor_id=' . var_export($bootstrap_actor_id, true)
    . ' session_id=' . substr((string)$bootstrap_session_id, 0, 16) . '...');
error_log('[debug_loggedin.php] SESSION_HASH_DEBUG: class_c_ip=' . ($hash_inputs['class_c_ip'] ?? '')
    . ' user_id=unknown hash=' . $ident_hash);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Debug Loggedin — Lupopedia</title>
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
        table { border-collapse: collapse; width: 100%; margin-top: 8px; }
        th, td { border: 1px solid #444; padding: 6px 10px; text-align: left; }
        th { background: #333; color: #9cdcfe; }
        tr.matched { background: #1e3a2a; }
        pre { background: #1e1e1e; padding: 10px; overflow-x: auto; color: #d4d4d4; font-size: 12px; margin: 0; }
        a   { color: #4ec9b0; }
    </style>
</head>
<body>
<h1>debug_loggedin.php — Post-Login Session Debug</h1>

<!-- ===== 1. ERROR LOG ===== -->
<div class="box">
    <h2>1. Error Log Path</h2>
    <p>
        <span class="key">error_log (php.ini):</span><br>
        <span class="val"><?php echo $error_log_path ? htmlspecialchars($error_log_path) : '<em class="warn">Not set — writing to stderr / web server log</em>'; ?></span>
    </p>
    <?php if ($error_log_path && file_exists($error_log_path)): ?>
        <p>Writable: <span class="<?php echo is_writable($error_log_path) ? 'ok' : 'bad'; ?>">
            <?php echo is_writable($error_log_path) ? 'YES' : 'NO'; ?></span>
        </p>
    <?php endif; ?>
    <p class="note">
        Test marker written: <strong><?php echo htmlspecialchars($test_marker); ?></strong><br>
        SESSION_HASH_DEBUG line also written. Search your error log for these strings.
    </p>
    <p class="note">
        Key prefixes to grep for in the error log:<br>
        <code>SESSION_HASH_DEBUG</code> — hash inputs and result<br>
        <code>SESSION_DEBUG</code> — AuthService + Session class trace<br>
        <code>SESSION_CREATE</code> — session row insert<br>
        <code>GOT HERE</code> — createEmbedSession entry point
    </p>
</div>

<!-- ===== 2. BOOTSTRAP SESSION STATE ===== -->
<div class="box">
    <h2>2. Bootstrap Session State</h2>
    <?php if (!isset($GLOBALS['lupo_session']) || !$GLOBALS['lupo_session']): ?>
        <p class="bad">$GLOBALS['lupo_session'] is null. Session object not created.</p>
        <p class="note">bootstrap.php requires $GLOBALS['mydatabase'] and the App\Auth\Session class.</p>
    <?php else: ?>
        <table>
            <tr>
                <td class="key">getSessionId()</td>
                <td class="val"><?php echo $bootstrap_session_id
                    ? htmlspecialchars($bootstrap_session_id)
                    : '<span class="bad">null — session was not started</span>'; ?></td>
            </tr>
            <tr>
                <td class="key">getActorId() (post-validateSession)</td>
                <td class="<?php echo $bootstrap_actor_id ? 'ok' : 'note'; ?>">
                    <?php echo $bootstrap_actor_id
                        ? 'actor_id = ' . (int)$bootstrap_actor_id . ' — LOGGED IN'
                        : 'null / 0 — NOT logged in (anonymous session)'; ?>
                </td>
            </tr>
            <?php if ($bootstrap_db_row): ?>
            <tr>
                <td class="key">session_identity_hash (DB)</td>
                <td class="val"><?php echo htmlspecialchars($bootstrap_db_row->session_identity_hash ?? 'null'); ?></td>
            </tr>
            <tr>
                <td class="key">Hash matches computed?</td>
                <td class="<?php echo ($bootstrap_db_row->session_identity_hash === $ident_hash) ? 'ok' : 'bad'; ?>">
                    <?php echo ($bootstrap_db_row->session_identity_hash === $ident_hash)
                        ? 'YES'
                        : 'NO — the hash in the DB row does not match the hash computed for this request'; ?>
                </td>
            </tr>
            <tr>
                <td class="key">last_activity_ymdhis</td>
                <td class="val"><?php echo htmlspecialchars($bootstrap_db_row->last_activity_ymdhis ?? 'null'); ?></td>
            </tr>
            <tr>
                <td class="key">is_named</td>
                <td class="val"><?php echo htmlspecialchars($bootstrap_db_row->is_named ?? '0'); ?></td>
            </tr>
            <?php else: ?>
            <tr><td colspan="2" class="warn">No DB row loaded into session object (session->current is null).</td></tr>
            <?php endif; ?>
        </table>
    <?php endif; ?>
</div>

<!-- ===== 3. AUTH SERVICE STATE ===== -->
<div class="box">
    <h2>3. AuthService::getCurrentUser()</h2>
    <?php if (!isset($GLOBALS['lupo_auth_service'])): ?>
        <p class="bad">$GLOBALS['lupo_auth_service'] not set. AuthService was not initialised by bootstrap.</p>
    <?php elseif ($auth_current_user === null): ?>
        <p class="note">getCurrentUser() returned null — not logged in.</p>
    <?php elseif (isset($auth_current_user['_error'])): ?>
        <p class="bad">getCurrentUser() threw: <?php echo htmlspecialchars($auth_current_user['_error']); ?></p>
    <?php else: ?>
        <p class="ok">Logged in. User data:</p>
        <pre><?php echo htmlspecialchars(print_r($auth_current_user, true)); ?></pre>
    <?php endif; ?>
</div>

<!-- ===== 4. SESSION HASH ===== -->
<div class="box">
    <h2>4. Session Identity Hash (this request)</h2>
    <table>
        <tr><th>Input</th><th>Value</th></tr>
        <tr>
            <td class="key">class_c_ip</td>
            <td class="val"><?php echo htmlspecialchars($hash_inputs['class_c_ip'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="key">user_id</td>
            <td class="val"><?php echo htmlspecialchars($hash_inputs['user_id'] ?? ''); ?>
                <span class="note">(null passed — pre-login)</span></td>
        </tr>
        <tr>
            <td class="key">user_agent</td>
            <td class="val"><?php echo htmlspecialchars($ua); ?></td>
        </tr>
        <tr>
            <td class="key">LUPO_SESSION_SALT</td>
            <td class="<?php echo ($hash_inputs['salt_ok'] ?? '') === 'YES' ? 'ok' : 'bad'; ?>">
                <?php echo htmlspecialchars($hash_inputs['salt_ok'] ?? 'unknown'); ?></td>
        </tr>
        <tr>
            <td class="key"><strong>computed hash</strong></td>
            <td class="val"><strong><?php echo htmlspecialchars($ident_hash); ?></strong></td>
        </tr>
    </table>
</div>

<!-- ===== 5. COOKIES + COOKIE PARAMS (AFTER LOGIN) ===== -->
<div class="box">
    <h2>5. Cookies &amp; session_get_cookie_params() — AFTER login</h2>

    <h3 style="color:#9cdcfe;margin-top:0;">session_get_cookie_params() on this request</h3>
    <p class="note">These are the params bootstrap set on this page load.  The PHPSESSID cookie that PHP set
        during login must use the same path or the browser will shadow it with the old anonymous cookie.</p>
    <?php
    $scp2 = session_get_cookie_params();
    $expected_path2 = (defined('LUPOPEDIA_PUBLIC_PATH') && LUPOPEDIA_PUBLIC_PATH !== '')
        ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') . '/'
        : '/';
    $path_ok2 = ($scp2['path'] === $expected_path2);
    ?>
    <table>
        <tr><th>Param</th><th>Value</th><th>Expected</th></tr>
        <tr>
            <td class="key">path</td>
            <td class="<?php echo $path_ok2 ? 'ok' : 'bad'; ?>"><?php echo htmlspecialchars($scp2['path']); ?></td>
            <td class="<?php echo $path_ok2 ? 'ok' : 'warn'; ?>"><?php echo htmlspecialchars($expected_path2); ?>
                <?php if (!$path_ok2) echo ' <span class="bad">MISMATCH — login cookie had different path</span>'; ?></td>
        </tr>
        <tr>
            <td class="key">domain</td>
            <td class="val"><?php echo htmlspecialchars($scp2['domain'] !== '' ? $scp2['domain'] : '(empty)'); ?></td>
            <td class="note"><?php echo htmlspecialchars($_SERVER['HTTP_HOST'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="key">lifetime</td>
            <td class="val"><?php echo htmlspecialchars($scp2['lifetime']); ?></td>
            <td class="note">0 (session)</td>
        </tr>
        <tr>
            <td class="key">secure</td>
            <td class="val"><?php echo $scp2['secure'] ? '<span class="ok">yes</span>' : 'no'; ?></td>
            <td class="note"></td>
        </tr>
        <tr>
            <td class="key">httponly</td>
            <td class="<?php echo $scp2['httponly'] ? 'ok' : 'warn'; ?>"><?php echo $scp2['httponly'] ? 'yes' : 'no'; ?></td>
            <td class="note">yes</td>
        </tr>
        <tr>
            <td class="key">samesite</td>
            <td class="val"><?php echo htmlspecialchars(isset($scp2['samesite']) ? ($scp2['samesite'] ?: '(not set)') : '(not set)'); ?></td>
            <td class="note">Lax</td>
        </tr>
    </table>

    <?php
    // Duplicate-cookie diagnostic: check if PHPSESSID value matches current session_id
    $phpsessid_cookie = isset($_COOKIE[session_name()]) ? $_COOKIE[session_name()] : null;
    $sid_match_cookie = ($phpsessid_cookie && $php_sid && $phpsessid_cookie === $php_sid);
    $sid_match_db     = ($phpsessid_cookie && $bootstrap_session_id && $phpsessid_cookie === $bootstrap_session_id);
    ?>
    <h3 style="color:#9cdcfe;margin-top:14px;">Cookie vs Session ID Alignment</h3>
    <table>
        <tr>
            <td class="key">PHPSESSID in $_COOKIE</td>
            <td class="val"><?php echo $phpsessid_cookie ? htmlspecialchars(substr($phpsessid_cookie, 0, 40)) . '...' : '<span class="bad">NOT PRESENT</span>'; ?></td>
        </tr>
        <tr>
            <td class="key">PHP session_id()</td>
            <td class="val"><?php echo $php_sid ? htmlspecialchars(substr($php_sid, 0, 40)) . '...' : '<span class="bad">EMPTY</span>'; ?></td>
        </tr>
        <tr>
            <td class="key">Cookie == session_id()?</td>
            <td class="<?php echo $sid_match_cookie ? 'ok' : 'bad'; ?>">
                <?php echo $sid_match_cookie ? 'YES -- aligned' : 'NO -- MISMATCH (stale/duplicate cookie in browser)'; ?></td>
        </tr>
        <tr>
            <td class="key">Cookie == bootstrap getSessionId()?</td>
            <td class="<?php echo $sid_match_db ? 'ok' : ($phpsessid_cookie ? 'bad' : 'note'); ?>">
                <?php echo $sid_match_db ? 'YES -- aligned' : ($phpsessid_cookie ? 'NO -- old cookie shadowing new session' : 'no cookie'); ?></td>
        </tr>
    </table>
    <?php if (!$sid_match_cookie && $phpsessid_cookie): ?>
    <p class="bad">Duplicate PHPSESSID detected: the cookie value the browser sent does not match
        the current PHP session. This means a stale cookie (likely path=/lupopedia/ from a previous
        anonymous session) is overriding the authenticated cookie (path=/).
        The Session::create() fix expires the old cookie before setting the new one.</p>
    <?php elseif ($sid_match_cookie): ?>
    <p class="ok">No duplicate: PHPSESSID cookie matches PHP session_id(). Cookie alignment is correct.</p>
    <?php endif; ?>

    <h3 style="color:#9cdcfe;margin-top:14px;">All Cookies Received</h3>
    <?php if (empty($_COOKIE)): ?>
        <p class="bad">No cookies received. Login cannot persist without cookies.</p>
    <?php else: ?>
        <table>
            <tr><th>Name</th><th>Value (first 64 chars)</th><th>Role</th></tr>
            <?php foreach ($_COOKIE as $cn => $cv): ?>
            <tr>
                <td class="key"><?php echo htmlspecialchars($cn); ?></td>
                <td class="val"><?php echo htmlspecialchars(substr($cv, 0, 64)); ?><?php echo strlen($cv) > 64 ? '...' : ''; ?></td>
                <td><?php
                    if ($cn === session_name())    echo '<span class="ok">PHP session cookie</span>';
                    elseif ($cn === 'lupo_session') echo '<span class="ok">lupo_session (Eye/Embed)</span>';
                    elseif (preg_match('/^[a-f0-9]{64}$/', $cv)) echo '<span class="warn">64-char hex -- possible session_id</span>';
                    else echo '<span class="note">--</span>';
                ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

<!-- ===== 6. DB LOOKUPS ===== -->
<div class="box">
    <h2>6. DB Lookups — lupo_sessions</h2>
    <?php if (!$db): ?>
        <p class="bad">No DB connection ($GLOBALS['mydatabase'] not set).</p>
    <?php elseif (empty($lookup_results)): ?>
        <p class="note">No identifiers to look up (no session_id and no computed hash).</p>
    <?php else: ?>
        <?php foreach ($lookup_results as $label => $res): ?>
        <div style="margin-bottom:14px; padding:10px; border:1px solid #444; border-radius:3px;">
            <strong class="key"><?php echo htmlspecialchars($label); ?></strong><br>
            <span class="note"><?php echo htmlspecialchars(substr($res['value'], 0, 72)); ?><?php echo strlen($res['value']) > 72 ? '…' : ''; ?></span>
            <?php if (isset($res['error'])): ?>
                <br><span class="bad">ERROR: <?php echo htmlspecialchars($res['error']); ?></span>
            <?php elseif ($res['found']): ?>
                <br><span class="ok">MATCH FOUND</span>
                <pre><?php echo htmlspecialchars(print_r($res['row'], true)); ?></pre>
            <?php else: ?>
                <br><span class="warn">No row found.</span>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- ===== 7. RECENT SESSIONS ===== -->
<div class="box">
    <h2>7. Recent Sessions (last 10 minutes)</h2>
    <?php if ($recent_err): ?>
        <p class="bad">Query error: <?php echo htmlspecialchars($recent_err); ?></p>
    <?php elseif (empty($recent_sessions)): ?>
        <p class="note">No sessions written in the last 10 minutes.
            <?php if (!$bootstrap_actor_id): ?>
            This means the anonymous session row was NOT inserted — check error_log for SESSION_DEBUG: createEmbedSession.
            <?php endif; ?>
        </p>
    <?php else: ?>
        <table>
            <tr>
                <th>session_id (first 20)</th>
                <th>actor_id</th>
                <th>last_activity_ymdhis</th>
                <th>created_ymdhis</th>
                <th>identity_hash (first 16)</th>
                <th>is_named</th>
                <th>name_key</th>
                <th>Status</th>
            </tr>
            <?php foreach ($recent_sessions as $rs):
                $sid_match  = ($rs['session_id'] === $php_sid)
                           || (isset($_COOKIE['lupo_session']) && $rs['session_id'] === $_COOKIE['lupo_session']);
                $hash_match = ($rs['session_identity_hash'] === $ident_hash);
                $row_class  = ($sid_match || $hash_match) ? 'matched' : '';
            ?>
            <tr class="<?php echo $row_class; ?>">
                <td><code><?php echo htmlspecialchars(substr($rs['session_id'], 0, 20)); ?>…</code></td>
                <td><?php echo htmlspecialchars($rs['actor_id'] ?? '0'); ?></td>
                <td><?php echo htmlspecialchars($rs['last_activity_ymdhis'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($rs['created_ymdhis'] ?? ''); ?></td>
                <td><code><?php echo htmlspecialchars(substr($rs['session_identity_hash'] ?? '', 0, 16)); ?>…</code></td>
                <td><?php echo htmlspecialchars($rs['is_named'] ?? '0'); ?></td>
                <td><?php echo htmlspecialchars($rs['name_key'] ?? ''); ?></td>
                <td>
                    <?php if ($sid_match)  echo '<span class="ok">SID MATCH</span> '; ?>
                    <?php if ($hash_match) echo '<span class="ok">HASH MATCH</span>'; ?>
                    <?php if (!$sid_match && !$hash_match) echo '<span class="note">—</span>'; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <p class="note">Highlighted rows (green) match either your PHPSESSID or your computed identity hash.</p>
    <?php endif; ?>
</div>

<!-- ===== 8. CONFIG ===== -->
<div class="box">
    <h2>8. Config Check</h2>
    <table>
        <tr>
            <td class="key">LUPOPEDIA_DEBUG</td>
            <td class="val"><?php echo defined('LUPOPEDIA_DEBUG')
                ? (LUPOPEDIA_DEBUG ? '<span class="ok">true — SESSION: logs active</span>' : '<span class="warn">false — SESSION: logs suppressed (set LUPOPEDIA_DEBUG=true to enable)</span>')
                : '<span class="bad">undefined</span>'; ?></td>
        </tr>
        <tr>
            <td class="key">LUPO_TABLE_PREFIX</td>
            <td class="val"><?php echo defined('LUPO_TABLE_PREFIX') ? htmlspecialchars(LUPO_TABLE_PREFIX) : '<span class="bad">undefined</span>'; ?></td>
        </tr>
        <tr>
            <td class="key">LUPO_SESSION_SALT defined?</td>
            <td class="<?php echo defined('LUPO_SESSION_SALT') ? 'ok' : 'bad'; ?>">
                <?php echo defined('LUPO_SESSION_SALT') ? 'YES' : 'NO — session hashes will be wrong'; ?></td>
        </tr>
        <tr>
            <td class="key">session.cookie_secure</td>
            <td class="val"><?php
                $cs = ini_get('session.cookie_secure');
                $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
                echo $cs ? 'ON' : 'OFF';
                if ($cs && $proto === 'http') {
                    echo ' <span class="bad">WARNING: cookie_secure=1 on HTTP — cookie will not be sent</span>';
                }
            ?></td>
        </tr>
        <tr>
            <td class="key">DB connected?</td>
            <td class="<?php echo $db ? 'ok' : 'bad'; ?>"><?php echo $db ? 'YES' : 'NO — $GLOBALS[mydatabase] not set'; ?></td>
        </tr>
    </table>
</div>

<!-- ===== 9. POST DATA ===== -->
<?php if (!empty($_POST)): ?>
<div class="box">
    <h2>9. POST Data Received</h2>
    <pre><?php echo htmlspecialchars(print_r($_POST, true)); ?></pre>
</div>
<?php endif; ?>

<!-- ===== NAVIGATION ===== -->
<div class="box">
    <a href="debug_login.php">Back to debug_login.php</a>
    &nbsp;|&nbsp;
    <a href="debug_loggedin.php">Reload this page</a>
    &nbsp;|&nbsp;
    <a href="login.php">Go to login.php</a>
</div>

</body>
</html>
