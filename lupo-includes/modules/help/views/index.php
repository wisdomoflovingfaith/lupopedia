<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help - Lupopedia</title>
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
            max-width: 1200px;
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
        .help-header p {
            color: #666;
            font-size: 16px;
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
        .help-search button:hover {
            background: #357abd;
        }
        .categories {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .categories h2 {
            font-size: 20px;
            margin-bottom: 15px;
        }
        .category-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .category-item {
            padding: 8px 16px;
            background: #f0f0f0;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }
        .category-item:hover, .category-item.active {
            background: #4a90e2;
            color: white;
        }
        .topics-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .topic-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: box-shadow 0.2s;
        }
        .topic-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .topic-card h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .topic-card h3 a {
            color: #4a90e2;
            text-decoration: none;
        }
        .topic-card h3 a:hover {
            text-decoration: underline;
        }
        .topic-card .category {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .topic-card .excerpt {
            color: #666;
            font-size: 14px;
        }
        .empty-state {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="help-container">
        <div class="help-header">
            <h1>Lupopedia Help</h1>
            <p>Documentation and guides for using Lupopedia</p>
        </div>
        
        <div class="help-search">
            <form method="GET" action="<?php echo (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . '/help/search'; ?>">
                <input type="text" name="q" placeholder="Search help topics..." value="">
                <button type="submit">Search</button>
            </form>
        </div>
        
        <?php if (!empty($categories)): ?>
        <div class="categories">
            <h2>Categories</h2>
            <div class="category-list">
                <a href="<?php echo (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . '/help'; ?>" 
                   class="category-item <?php echo empty($category) ? 'active' : ''; ?>">
                    All
                </a>
                <?php foreach ($categories as $cat): ?>
                <a href="<?php echo (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . '/help?category=' . urlencode($cat); ?>" 
                   class="category-item <?php echo $category === $cat ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($cat); ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (empty($topics)): ?>
        <div class="empty-state">
            <p>No help topics found.</p>
        </div>
        <?php else: ?>
        <div class="topics-list">
            <?php foreach ($topics as $topic): ?>
            <div class="topic-card">
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
                    <?php echo substr(strip_tags($topic['content_html']), 0, 150); ?>...
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
