<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Help - Lupopedia</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }
        .help-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        .help-header {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .help-header h1 {
            font-size: 32px;
            margin-bottom: 10px;
            color: #333;
        }
        .help-search {
            margin-bottom: 30px;
        }
        .help-search form {
            display: flex;
            gap: 10px;
        }
        .help-search input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .help-search button {
            padding: 12px 24px;
            background: #4a90e2;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .search-results {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .search-result-item {
            padding: 20px 0;
            border-bottom: 1px solid #eee;
        }
        .search-result-item:last-child {
            border-bottom: none;
        }
        .search-result-item h3 {
            font-size: 20px;
            margin-bottom: 8px;
        }
        .search-result-item h3 a {
            color: #4a90e2;
            text-decoration: none;
        }
        .search-result-item h3 a:hover {
            text-decoration: underline;
        }
        .search-result-item .category {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .search-result-item .excerpt {
            color: #666;
            font-size: 14px;
        }
        .empty-state {
            text-align: center;
            color: #666;
            padding: 40px;
        }
    </style>
</head>
<body>
    <div class="help-container">
        <div class="help-header">
            <h1>Search Help</h1>
        </div>
        
        <div class="help-search">
            <form method="GET" action="<?php echo (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . '/help/search'; ?>">
                <input type="text" name="q" placeholder="Search help topics..." value="<?php echo htmlspecialchars($query); ?>">
                <button type="submit">Search</button>
            </form>
        </div>
        
        <div class="search-results">
            <?php if (empty($query)): ?>
            <div class="empty-state">
                <p>Enter a search query to find help topics.</p>
            </div>
            <?php elseif (empty($results)): ?>
            <div class="empty-state">
                <p>No results found for "<?php echo htmlspecialchars($query); ?>".</p>
            </div>
            <?php else: ?>
            <h2>Results for "<?php echo htmlspecialchars($query); ?>" (<?php echo count($results); ?>)</h2>
            <?php foreach ($results as $topic): ?>
            <div class="search-result-item">
                <?php if (!empty($topic['category'])): ?>
                <div class="category"><?php echo htmlspecialchars($topic['category']); ?></div>
                <?php endif; ?>
                <h3>
                    <a href="<?php echo (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . '/help/' . htmlspecialchars($topic['slug']); ?>">
                        <?php echo htmlspecialchars($topic['title']); ?>
                    </a>
                </h3>
                <?php if (!empty($topic['content_html'])): ?>
                <div class="excerpt">
                    <?php echo substr(strip_tags($topic['content_html']), 0, 200); ?>...
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
