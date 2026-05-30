<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entity List - Lupopedia</title>
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
        .list-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .list-header {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .list-header h1 {
            font-size: 32px;
            margin-bottom: 10px;
            color: #333;
        }
        .list-header p {
            color: #666;
            font-size: 16px;
        }
        .entities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .entity-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: box-shadow 0.2s;
        }
        .entity-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .entity-card h3 {
            font-size: 20px;
            margin-bottom: 10px;
        }
        .entity-card h3 a {
            color: #4a90e2;
            text-decoration: none;
        }
        .entity-card h3 a:hover {
            text-decoration: underline;
        }
        .entity-card p {
            color: #666;
            font-size: 14px;
            margin-bottom: 8px;
        }
        .entity-card .table-name {
            font-family: monospace;
            font-size: 12px;
            color: #999;
            background: #f5f5f5;
            padding: 4px 8px;
            border-radius: 3px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="list-container">
        <div class="list-header">
            <h1>Entity List</h1>
            <p>Browse all Lupopedia entities and data</p>
        </div>
        
        <div class="entities-grid">
            <?php foreach ($entities as $slug => $info): ?>
            <div class="entity-card">
                <h3>
                    <a href="<?php echo (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . '/list/' . htmlspecialchars($slug); ?>">
                        <?php echo htmlspecialchars($info['title']); ?>
                    </a>
                </h3>
                <p><?php echo htmlspecialchars($info['description']); ?></p>
                <span class="table-name"><?php echo htmlspecialchars($info['table']); ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
