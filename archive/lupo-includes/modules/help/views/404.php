<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Topic Not Found - Lupopedia</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .error-container {
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .error-container h1 {
            font-size: 48px;
            margin-bottom: 20px;
            color: #333;
        }
        .error-container p {
            font-size: 18px;
            color: #666;
            margin-bottom: 30px;
        }
        .error-container a {
            display: inline-block;
            padding: 12px 24px;
            background: #4a90e2;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .error-container a:hover {
            background: #357abd;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>404</h1>
        <p>Help topic not found.</p>
        <a href="<?php echo (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . '/help'; ?>">Back to Help</a>
    </div>
</body>
</html>
