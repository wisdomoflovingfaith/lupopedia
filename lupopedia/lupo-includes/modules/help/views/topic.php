<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($topic['title']); ?> - Help - Lupopedia</title>
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
        .help-header .category {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .help-header .back-link {
            display: inline-block;
            margin-top: 15px;
            color: #4a90e2;
            text-decoration: none;
        }
        .help-header .back-link:hover {
            text-decoration: underline;
        }
        .help-content {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .help-content h2 {
            font-size: 24px;
            margin: 20px 0 15px 0;
            color: #333;
        }
        .help-content h3 {
            font-size: 20px;
            margin: 18px 0 12px 0;
            color: #333;
        }
        .help-content p {
            margin-bottom: 15px;
        }
        .help-content ul, .help-content ol {
            margin-left: 30px;
            margin-bottom: 15px;
        }
        .help-content li {
            margin-bottom: 8px;
        }
        .help-content code {
            background: #f5f5f5;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
            font-size: 14px;
        }
        .help-content pre {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 4px;
            overflow-x: auto;
            margin-bottom: 15px;
        }
        .help-content pre code {
            background: none;
            padding: 0;
        }
    </style>
</head>
<body>
    <div class="help-container">
        <div class="help-header">
            <?php if (!empty($topic['category'])): ?>
            <div class="category"><?php echo htmlspecialchars($topic['category']); ?></div>
            <?php endif; ?>
            <h1><?php echo htmlspecialchars($topic['title']); ?></h1>
            <a href="<?php echo (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . '/help'; ?>" class="back-link">← Back to Help</a>
        </div>
        
        <div class="help-content">
            <?php echo $topic['content_html']; ?>
        </div>
    </div>
</body>
</html>
