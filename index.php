<?php
/**
 * Lupopedia OS default page: cross-domain lineage (mock).
 *
 * This page does not color the domain it is installed on.
 * Local domain content coloring is inside live help (Content section).
 *
 * Live help login is a secondary route: livehelp/login.php
 * Slug routing (pages, APIs) still goes through route.php.
 *
 * Submit shows mock records only. No database write. HEX6 is never guessed.
 *
 * ?parent= is a path relative to the domain root (not /lupopedia/).
 * Example: ?parent=meaningoflife.htm → https://host/colorlex.com/meaningoflife.htm
 */

$needsRoute = (!empty($_GET['slug']) || !empty($_GET['route']) || !empty($_GET['resolved_uri']));
if ($needsRoute) {
    require __DIR__ . '/route.php';
    exit;
}

$scriptName = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', $_SERVER['SCRIPT_NAME']) : '/lupopedia/index.php';
$publicBase = rtrim(dirname($scriptName), '/');
if ($publicBase === '/' || $publicBase === '\\' || $publicBase === '.') {
    $publicBase = '';
}

$installHost = isset($_SERVER['HTTP_HOST']) ? strtolower((string) $_SERVER['HTTP_HOST']) : '';
if (strpos($installHost, ':') !== false) {
    $installHost = substr($installHost, 0, strpos($installHost, ':'));
}

// Domain root of the installed node (parent of /lupopedia/). Parent pages are not inside the OS directory.
$httpsOn = false;
if (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off') {
    $httpsOn = true;
} elseif (isset($_SERVER['REQUEST_SCHEME']) && strtolower((string) $_SERVER['REQUEST_SCHEME']) === 'https') {
    $httpsOn = true;
} elseif (isset($_SERVER['SERVER_PORT']) && (string) $_SERVER['SERVER_PORT'] === '443') {
    $httpsOn = true;
}
$scheme = $httpsOn ? 'https' : 'http';
$hostHeader = isset($_SERVER['HTTP_HOST']) && is_string($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] !== ''
    ? $_SERVER['HTTP_HOST']
    : 'localhost';
$osFolder = '/lupopedia';
if ($publicBase === $osFolder) {
    $domainPath = '';
} elseif (strlen($publicBase) > strlen($osFolder) && substr($publicBase, -strlen($osFolder)) === $osFolder) {
    $domainPath = substr($publicBase, 0, -strlen($osFolder));
} else {
    $domainPath = rtrim(dirname($publicBase), '/');
    if ($domainPath === '/' || $domainPath === '.' || $domainPath === '\\') {
        $domainPath = '';
    }
}
$domainRoot = $scheme . '://' . $hostHeader . ($domainPath === '' ? '/' : rtrim($domainPath, '/') . '/');

$parentPath = isset($_GET['parent']) ? trim((string) $_GET['parent']) : '';
$parentPath = str_replace('\\', '/', $parentPath);
if ($parentPath !== '' && (strpos($parentPath, '://') !== false || strpos($parentPath, '..') !== false || strpos($parentPath, "\0") !== false)) {
    $parentPath = '';
}
$parentPath = ltrim($parentPath, '/');
$parentPrefill = ($parentPath !== '') ? ($domainRoot . $parentPath) : '';

$groupColors = array(
    'BLACK', 'BLUE', 'BROWN', 'GOLD', 'GRAY',
    'GREEN', 'ORANGE', 'PINK', 'PURPLE', 'RED',
    'SILVER', 'WHITE', 'YELLOW',
);

$changeTypes = array(
    'copied_exactly' => 'Copied exactly (no changes)',
    'minor_edits' => 'Minor edits (grammar, formatting)',
    'major_rewrite' => 'Major rewrite (new structure, same topic)',
    'derived_work' => 'Derived work (new content based on original)',
    'translation' => 'Translation (language change)',
    'extraction' => 'Extraction (took part of the parent page)',
    'combination' => 'Combination (merged multiple sources)',
    'other' => 'Other (explain below)',
);

$changeIntents = array(
    'improve_clarity' => 'Improve clarity',
    'update_outdated' => 'Update outdated information',
    'add_new_information' => 'Add new information',
    'create_child_version' => 'Create a child version',
    'create_summary' => 'Create a summary',
    'create_detailed_expansion' => 'Create a detailed expansion',
    'adapt_new_audience' => 'Adapt for a new audience',
    'adapt_new_domain' => 'Adapt for a new domain',
    'fix_errors' => 'Fix errors',
    'other' => 'Other',
);

$submitted = false;
$errors = array();
$identity = null;
$lineage = null;
$relationship = null;
$federated = null;
$artifactLink = null;

/**
 * @param string $url
 * @return string
 */
function lupo_mock_url_host($url)
{
    $host = parse_url($url, PHP_URL_HOST);
    if (!is_string($host) || $host === '') {
        return '';
    }
    $host = strtolower($host);
    if (strpos($host, ':') !== false) {
        $host = substr($host, 0, strpos($host, ':'));
    }
    return $host;
}

/**
 * @param string $url
 * @return bool
 */
function lupo_mock_is_http_url($url)
{
    return (bool) preg_match('#^https?://#i', $url);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $group = strtoupper(trim(isset($_POST['group_color']) ? (string) $_POST['group_color'] : ''));
    $nickname = strtolower(trim(isset($_POST['color_nickname']) ? (string) $_POST['color_nickname'] : ''));
    $parentUrl = trim(isset($_POST['parent_url']) ? (string) $_POST['parent_url'] : '');
    $childUrl = trim(isset($_POST['child_url']) ? (string) $_POST['child_url'] : '');
    $changeType = isset($_POST['change_type']) ? (string) $_POST['change_type'] : '';
    $changeIntent = isset($_POST['change_intent']) ? (string) $_POST['change_intent'] : '';
    $changeExplanation = isset($_POST['change_explanation']) ? (string) $_POST['change_explanation'] : '';

    if ($changeType !== '' && !isset($changeTypes[$changeType])) {
        $changeType = '';
    }
    if ($changeIntent !== '' && !isset($changeIntents[$changeIntent])) {
        $changeIntent = '';
    }

    if (!in_array($group, $groupColors, true)) {
        $errors[] = 'Select a color group from the register.';
    }
    if ($nickname === '' || !preg_match('/^[a-z][a-z0-9]*$/', $nickname)) {
        $errors[] = 'Color nickname must be lowercase ASCII letters and digits, starting with a letter. No spaces or hyphens.';
    }
    if ($parentUrl === '') {
        $errors[] = 'Enter a Parent URL (original source).';
    } elseif (!lupo_mock_is_http_url($parentUrl)) {
        $errors[] = 'Parent URL must start with http:// or https://.';
    }
    if ($childUrl === '') {
        $errors[] = 'Enter a Child URL (derived or echoed page).';
    } elseif (!lupo_mock_is_http_url($childUrl)) {
        $errors[] = 'Child URL must start with http:// or https://.';
    }

    $parentHost = lupo_mock_url_host($parentUrl);
    $childHost = lupo_mock_url_host($childUrl);

    if ($parentUrl !== '' && $childUrl !== '' && strcasecmp($parentUrl, $childUrl) === 0) {
        $errors[] = 'Parent URL and Child URL must be different.';
    }

    if ($installHost !== '' && $parentHost === $installHost && $childHost === $installHost) {
        $errors[] = 'This homepage does not color the installed domain. Local pages are colored inside live help (Content). Use a parent and child on different domains, or at least one URL off this host.';
    }

    if (empty($errors)) {
        $submitted = true;
        $packed = gmdate('YmdHis');
        $identityId = 'id-' . $packed;
        $lineageId = 'lin-' . $packed;
        $relId = 'rel-' . $packed;
        $fedId = 'fed-' . $packed;
        $linkId = 'lnk-' . $packed;
        $handshake = 'lupopedia poweredby [' . $group . '] [' . $nickname . ']';
        $crossDomain = ($parentHost !== '' && $childHost !== '' && $parentHost !== $childHost);

        $identity = array(
            'id' => $identityId,
            'group_color' => $group,
            'color_name' => $nickname,
            'hex6' => 'pending (not guessed)',
            'handshake' => $handshake,
            'scope' => 'lineage pair (not the installed domain)',
            'packed_utc' => $packed,
            'storage' => 'mock only - no database write',
        );

        $lineage = array(
            'entry_id' => $lineageId,
            'parent_url' => $parentUrl,
            'child_url' => $childUrl,
            'parent_domain' => $parentHost,
            'child_domain' => $childHost,
            'change_type' => $changeType,
            'change_intent' => $changeIntent,
            'change_explanation' => $changeExplanation,
            'event' => 'lineage declared (mock)',
            'packed_utc' => $packed,
        );

        $relationship = array(
            'id' => $relId,
            'type' => $changeType !== '' ? $changeType : 'echo / derived-from',
            'intent' => $changeIntent !== '' ? $changeIntent : '',
            'from' => $parentUrl,
            'to' => $childUrl,
            'color' => $group . ' ' . $nickname,
            'note' => 'Child is a derived or echoed page of the parent source.',
        );

        $federated = array(
            'id' => $fedId,
            'declared_at_node' => $installHost !== '' ? $installHost : 'this install',
            'describes_install_domain' => 'no',
            'parent_domain' => $parentHost,
            'child_domain' => $childHost,
            'cross_domain' => $crossDomain ? 'yes' : 'same-domain lineage (not this install)',
            'handshake' => $handshake,
            'packed_utc' => $packed,
        );

        $artifactLink = array(
            'id' => $linkId,
            'kind' => 'cross-domain artifact link',
            'parent_artifact' => $parentUrl,
            'child_artifact' => $childUrl,
            'identity' => $identityId,
            'lineage' => $lineageId,
        );
    }
}

$livehelpLogin = $publicBase . '/livehelp/login.php';
$livehelpContent = $publicBase . '/admin.php?section=artifacts';

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupopedia Color Registry</title>
    <style>
        :root {
            --ink: #1a1a1a;
            --muted: #555;
            --line: #d4cfc4;
            --paper: #f7f4ee;
            --card: #fffdf8;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Georgia, "Times New Roman", serif;
            background: var(--paper);
            color: var(--ink);
            line-height: 1.55;
        }
        header {
            border-bottom: 1px solid var(--line);
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            gap: 1rem;
            flex-wrap: wrap;
        }
        header h1 {
            margin: 0;
            font-size: 1.35rem;
            font-weight: normal;
            letter-spacing: 0.04em;
        }
        header a {
            color: var(--muted);
            font-size: 0.9rem;
        }
        main {
            max-width: 42rem;
            margin: 0 auto;
            padding: 2rem 1.5rem 4rem;
        }
        p.lead { color: var(--muted); }
        .why {
            background: var(--card);
            border: 1px solid var(--line);
            padding: 1rem 1.15rem;
            margin: 0 0 1.75rem;
        }
        .why h2 {
            margin: 0 0 0.6rem;
            font-size: 1.05rem;
            font-weight: normal;
        }
        .why ul { margin: 0.4rem 0 0.8rem; padding-left: 1.2rem; }
        .why p { margin: 0.55rem 0 0; }
        .change-panel {
            border: 1px solid var(--line);
            padding: 0.85rem 1rem 1rem;
            margin: 1.25rem 0 0;
            background: var(--card);
        }
        .change-panel legend {
            padding: 0 0.35rem;
            font-size: 1rem;
        }
        .radio-list {
            margin: 0.35rem 0 0;
            padding: 0;
            list-style: none;
        }
        .radio-list li { margin: 0.35rem 0; }
        .radio-list label {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            margin: 0;
            font-size: 0.95rem;
            cursor: pointer;
        }
        .radio-list input[type="radio"] {
            width: auto;
            margin: 0.2rem 0 0;
            flex: 0 0 auto;
        }
        label {
            display: block;
            margin: 1rem 0 0.35rem;
            font-size: 0.95rem;
        }
        select, input[type="text"], input[type="url"], textarea {
            width: 100%;
            padding: 0.55rem 0.65rem;
            font: inherit;
            border: 1px solid var(--line);
            background: #fff;
        }
        textarea {
            resize: vertical;
            min-height: 7.5rem;
        }
        button {
            margin-top: 1.25rem;
            padding: 0.6rem 1.1rem;
            font: inherit;
            cursor: pointer;
            background: var(--ink);
            color: #fff;
            border: 0;
        }
        .errors {
            background: #f8ecec;
            border: 1px solid #e0c4c4;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
        }
        .card {
            background: var(--card);
            border: 1px solid var(--line);
            padding: 1rem 1.15rem;
            margin: 1.5rem 0;
        }
        .card h2 {
            margin: 0 0 0.75rem;
            font-size: 1.05rem;
            font-weight: normal;
        }
        dl { margin: 0; }
        dt {
            color: var(--muted);
            font-size: 0.8rem;
            margin-top: 0.65rem;
        }
        dd {
            margin: 0.1rem 0 0;
            font-family: Consolas, "Courier New", monospace;
            font-size: 0.92rem;
            word-break: break-word;
        }
        footer {
            margin-top: 2.5rem;
            font-size: 0.85rem;
            color: var(--muted);
        }
        code { font-family: Consolas, "Courier New", monospace; }
    </style>
</head>
<body>
    <header>
        <h1>Lupopedia Color Registry</h1>
        <a href="<?php echo htmlspecialchars($livehelpLogin, ENT_QUOTES, 'UTF-8'); ?>">Live help</a>
    </header>
    <main>
        <p class="lead">Declare lineage between URLs across domains. This page does not color the domain it is installed on. Local content coloring stays in live help, Content section.</p>

        <div class="why">
            <h2>Why fill this out</h2>
            <p>Right now the internet works like this: someone finds a page, copies it, pastes it onto their domain, changes some of it, and publishes. Nobody knows:</p>
            <ul>
                <li>where it came from</li>
                <li>what changed</li>
                <li>why it changed</li>
                <li>who changed it</li>
                <li>what the relationship is</li>
            </ul>
            <p>There is no lineage system. This page is how you declare one: a parent URL, a child URL, and a structured description of the change. You are not coloring this install. You are recording provenance so humans and machines can read it later.</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $err): ?>
                    <div><?php echo htmlspecialchars($err, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <label for="group_color">Color group</label>
            <select id="group_color" name="group_color" required>
                <option value="">Select a group</option>
                <?php
                $selectedGroup = isset($_POST['group_color']) ? strtoupper((string) $_POST['group_color']) : '';
                foreach ($groupColors as $gc):
                ?>
                    <option value="<?php echo htmlspecialchars($gc, ENT_QUOTES, 'UTF-8'); ?>"<?php echo $selectedGroup === $gc ? ' selected' : ''; ?>><?php echo htmlspecialchars($gc, ENT_QUOTES, 'UTF-8'); ?></option>
                <?php endforeach; ?>
            </select>

            <label for="color_nickname">Color nickname</label>
            <input type="text" id="color_nickname" name="color_nickname" required
                   value="<?php echo isset($_POST['color_nickname']) ? htmlspecialchars((string) $_POST['color_nickname'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                   placeholder="goldenwolf" autocomplete="off">

            <label for="parent_url">Parent URL (original source)</label>
            <input type="url" id="parent_url" name="parent_url" required
                   value="<?php echo htmlspecialchars(isset($_POST['parent_url']) ? (string) $_POST['parent_url'] : $parentPrefill, ENT_QUOTES, 'UTF-8'); ?>"
                   placeholder="https://source.example/page">

            <label for="child_url">Child URL (derived or echoed page)</label>
            <input type="url" id="child_url" name="child_url" required
                   value="<?php echo isset($_POST['child_url']) ? htmlspecialchars((string) $_POST['child_url'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                   placeholder="https://echo.example/page">

            <fieldset class="change-panel">
                <legend>How did you change it?</legend>
                <p class="lead" style="margin:0.35rem 0 0.5rem;">Pick a change type and an intent. The textarea is optional context, not the only record of what changed.</p>
                <?php $selectedChangeType = isset($_POST['change_type']) ? (string) $_POST['change_type'] : ''; ?>
                <ul class="radio-list">
                    <?php foreach ($changeTypes as $typeKey => $typeLabel): ?>
                    <li>
                        <label>
                            <input type="radio" name="change_type" value="<?php echo htmlspecialchars($typeKey, ENT_QUOTES, 'UTF-8'); ?>"<?php echo $selectedChangeType === $typeKey ? ' checked' : ''; ?>>
                            <span><?php echo htmlspecialchars($typeLabel, ENT_QUOTES, 'UTF-8'); ?></span>
                        </label>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <label for="change_intent">Change Intent</label>
                <select id="change_intent" name="change_intent">
                    <option value="">Select an intent</option>
                    <?php
                    $selectedIntent = isset($_POST['change_intent']) ? (string) $_POST['change_intent'] : '';
                    foreach ($changeIntents as $intentKey => $intentLabel):
                    ?>
                    <option value="<?php echo htmlspecialchars($intentKey, ENT_QUOTES, 'UTF-8'); ?>"<?php echo $selectedIntent === $intentKey ? ' selected' : ''; ?>><?php echo htmlspecialchars($intentLabel, ENT_QUOTES, 'UTF-8'); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="change_explanation">Explain What Changed</label>
                <textarea id="change_explanation" name="change_explanation" rows="6" cols="60"
                          placeholder="Describe how the child page differs from the parent page..."><?php echo isset($_POST['change_explanation']) ? htmlspecialchars((string) $_POST['change_explanation'], ENT_QUOTES, 'UTF-8') : ''; ?></textarea>
            </fieldset>

            <button type="submit">Create lineage record</button>
        </form>

        <?php if ($submitted && is_array($identity) && is_array($lineage) && is_array($relationship) && is_array($federated) && is_array($artifactLink)): ?>
            <div class="card">
                <h2>Color identity (mock)</h2>
                <dl>
                    <dt>Record id</dt><dd><?php echo htmlspecialchars($identity['id'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>GroupColor</dt><dd><?php echo htmlspecialchars($identity['group_color'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>ColorName</dt><dd><?php echo htmlspecialchars($identity['color_name'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>HEX6</dt><dd><?php echo htmlspecialchars($identity['hex6'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Handshake</dt><dd><?php echo htmlspecialchars($identity['handshake'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Scope</dt><dd><?php echo htmlspecialchars($identity['scope'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Packed UTC</dt><dd><?php echo htmlspecialchars($identity['packed_utc'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Storage</dt><dd><?php echo htmlspecialchars($identity['storage'], ENT_QUOTES, 'UTF-8'); ?></dd>
                </dl>
            </div>
            <div class="card">
                <h2>Lineage mapping (mock)</h2>
                <dl>
                    <dt>Entry id</dt><dd><?php echo htmlspecialchars($lineage['entry_id'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Parent URL</dt><dd><?php echo htmlspecialchars($lineage['parent_url'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Child URL</dt><dd><?php echo htmlspecialchars($lineage['child_url'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Change type</dt><dd><?php echo htmlspecialchars($lineage['change_type'] !== '' ? $lineage['change_type'] : '(none)', ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Change intent</dt><dd><?php echo htmlspecialchars($lineage['change_intent'] !== '' ? $lineage['change_intent'] : '(none)', ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Explain What Changed</dt><dd style="white-space:pre-wrap;"><?php echo $lineage['change_explanation'] !== '' ? htmlspecialchars($lineage['change_explanation'], ENT_QUOTES, 'UTF-8') : '(none)'; ?></dd>
                    <dt>Parent domain</dt><dd><?php echo htmlspecialchars($lineage['parent_domain'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Child domain</dt><dd><?php echo htmlspecialchars($lineage['child_domain'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Event</dt><dd><?php echo htmlspecialchars($lineage['event'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Packed UTC</dt><dd><?php echo htmlspecialchars($lineage['packed_utc'], ENT_QUOTES, 'UTF-8'); ?></dd>
                </dl>
            </div>
            <div class="card">
                <h2>Semantic relationship (mock)</h2>
                <dl>
                    <dt>Id</dt><dd><?php echo htmlspecialchars($relationship['id'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Type</dt><dd><?php echo htmlspecialchars($relationship['type'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Intent</dt><dd><?php echo htmlspecialchars($relationship['intent'] !== '' ? $relationship['intent'] : '(none)', ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>From</dt><dd><?php echo htmlspecialchars($relationship['from'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>To</dt><dd><?php echo htmlspecialchars($relationship['to'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Color</dt><dd><?php echo htmlspecialchars($relationship['color'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Note</dt><dd><?php echo htmlspecialchars($relationship['note'], ENT_QUOTES, 'UTF-8'); ?></dd>
                </dl>
            </div>
            <div class="card">
                <h2>Federated record (mock)</h2>
                <dl>
                    <dt>Id</dt><dd><?php echo htmlspecialchars($federated['id'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Declared at</dt><dd><?php echo htmlspecialchars($federated['declared_at_node'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Colors this install</dt><dd><?php echo htmlspecialchars($federated['describes_install_domain'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Parent domain</dt><dd><?php echo htmlspecialchars($federated['parent_domain'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Child domain</dt><dd><?php echo htmlspecialchars($federated['child_domain'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Cross-domain</dt><dd><?php echo htmlspecialchars($federated['cross_domain'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Handshake</dt><dd><?php echo htmlspecialchars($federated['handshake'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Packed UTC</dt><dd><?php echo htmlspecialchars($federated['packed_utc'], ENT_QUOTES, 'UTF-8'); ?></dd>
                </dl>
            </div>
            <div class="card">
                <h2>Cross-domain artifact link (mock)</h2>
                <dl>
                    <dt>Id</dt><dd><?php echo htmlspecialchars($artifactLink['id'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Kind</dt><dd><?php echo htmlspecialchars($artifactLink['kind'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Parent artifact</dt><dd><?php echo htmlspecialchars($artifactLink['parent_artifact'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Child artifact</dt><dd><?php echo htmlspecialchars($artifactLink['child_artifact'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Identity</dt><dd><?php echo htmlspecialchars($artifactLink['identity'], ENT_QUOTES, 'UTF-8'); ?></dd>
                    <dt>Lineage</dt><dd><?php echo htmlspecialchars($artifactLink['lineage'], ENT_QUOTES, 'UTF-8'); ?></dd>
                </dl>
            </div>
        <?php endif; ?>

        <footer>
            This homepage tracks lineage between URLs. It does not color this install.
            Color local pages in live help
            <a href="<?php echo htmlspecialchars($livehelpLogin, ENT_QUOTES, 'UTF-8'); ?>">login</a>
            then <a href="<?php echo htmlspecialchars($livehelpContent, ENT_QUOTES, 'UTF-8'); ?>">Content</a>.
            Color is not a LUP KEY token. HEX6 is stored without <code>#</code>. HEX5 is not a color.
        </footer>
    </main>
</body>
</html>
