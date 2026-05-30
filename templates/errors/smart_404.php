<?php
/**
 * Smart 404 template (4.0.18 T4). Renders when UrlResolver returns null for doctrine/qa/docs/flp paths.
 * $data: status, suggestions (array of path strings), requested. $data['authenticated']: bool for kapakai note.
 */
if (!isset($data) || !is_array($data)) {
    $data = array('status' => 'smart_404', 'suggestions' => array(), 'requested' => '', 'authenticated' => false);
}
$requested = isset($data['requested']) ? $data['requested'] : '';
$suggestions = isset($data['suggestions']) && is_array($data['suggestions']) ? $data['suggestions'] : array();
$authenticated = isset($data['authenticated']) ? (bool) $data['authenticated'] : false;
$base = defined('LUPOPEDIA_PUBLIC_PATH') ? rtrim(LUPOPEDIA_PUBLIC_PATH, '/') : '';
?>
<div class="lupopedia-404 lupopedia-smart-404">
    <h1>Page not found</h1>
    <p>The path <code><?php echo htmlspecialchars($requested); ?></code> was not found.</p>
    <?php if (!empty($suggestions)) : ?>
        <h2>Did you mean:</h2>
        <ul>
            <?php foreach ($suggestions as $path) : ?>
                <li><a href="<?php echo htmlspecialchars($base . '/' . $path); ?>"><?php echo htmlspecialchars($path); ?></a></li>
            <?php endforeach; ?>
        </ul>
        <?php if ($authenticated) : ?>
            <p class="lupopedia-kapakai">Try checking the spelling or browse the documentation.</p>
        <?php endif; ?>
    <?php else : ?>
        <p>No similar paths found.</p>
        <?php if (!$authenticated) : ?>
            <p class="lupopedia-kapakai">Authenticated users may see suggestions.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>
