<?php
/**
 * Department selection page (legacy choosedepartment.php equivalent).
 * Lists departments from lupo_departments; form posts to livehelp.php.
 * Uses lupo_operators for online/offline per department.
 * All URLs use LUPOPEDIA_PUBLIC_PATH.
 */
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded.');
}

$prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$db = $GLOBALS['mydatabase'] ?? null;
if (!$db) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<!DOCTYPE html><html><head><title>Choose Department</title></head><body><p>Service unavailable.</p></body></html>';
    exit;
}

$base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '';
$website = isset($_GET['website']) ? (int)$_GET['website'] : 0;

// Visitor session id for form (preserve or generate)
$session_id = isset($_GET['cslhVISITOR']) ? (string)$_GET['cslhVISITOR'] : '';
if ($session_id === '' && !empty($_COOKIE['cslhVISITOR'])) {
    $session_id = (string)$_COOKIE['cslhVISITOR'];
}
if ($session_id === '') {
    $session_id = 'v' . bin2hex(random_bytes(12));
}

// List departments (lupo_departments)
$stmt = $db->query("SELECT department_id, name FROM {$prefix}departments WHERE is_deleted = 0 ORDER BY name");
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// For each department, check if any operator is online (lupo_operators)
$online_by_dept = [];
try {
    $stmt = $db->query(
        "SELECT o.department_id FROM {$prefix}operators o " .
        "WHERE o.is_active = 1 AND (o.availability_status = 'online' OR EXISTS (" .
        "  SELECT 1 FROM {$prefix}operator_status os WHERE os.operator_id = o.operator_id AND os.status = 'online'" .
        "))"
    );
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $online_by_dept[(int)$row['department_id']] = true;
    }
} catch (Throwable $e) {
    $stmt = $db->query("SELECT department_id FROM {$prefix}operators WHERE is_active = 1 AND availability_status = 'online'");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $online_by_dept[(int)$row['department_id']] = true;
    }
}

$form_action = $base . 'livehelp.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Choose Department - Live Help</title>
    <style>
        body { font-family: sans-serif; max-width: 480px; margin: 2rem auto; padding: 0 1rem; }
        h2 { margin-top: 0; }
        select { width: 100%; padding: 0.5rem; font-size: 1rem; margin: 0.5rem 0; }
        input[type="submit"] { padding: 0.5rem 1.5rem; font-size: 1rem; cursor: pointer; }
        .dept-online { color: #2e7d32; }
        .dept-offline { color: #999; }
    </style>
</head>
<body>
    <h2>Choose Department</h2>
    <form action="<?= htmlspecialchars($form_action) ?>" method="get">
        <input type="hidden" name="cslhVISITOR" value="<?= htmlspecialchars($session_id) ?>">
        <?php if ($website !== 0) : ?>
        <input type="hidden" name="website" value="<?= (int)$website ?>">
        <?php endif; ?>
        <label for="department">Department:</label>
        <select id="department" name="department" required>
            <option value="">-- Select --</option>
            <?php foreach ($departments as $d) :
                $did = (int)$d['department_id'];
                $online = !empty($online_by_dept[$did]);
            ?>
            <option value="<?= $did ?>" class="<?= $online ? 'dept-online' : 'dept-offline' ?>">
                <?= htmlspecialchars($d['name']) ?> (<?= $online ? 'Online' : 'Offline' ?>)
            </option>
            <?php endforeach; ?>
        </select>
        <p><input type="submit" value="Continue to Live Help"></p>
    </form>
</body>
</html>
