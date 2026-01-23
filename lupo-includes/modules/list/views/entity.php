<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($entity_info['title']); ?> - List - Lupopedia</title>
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
            max-width: 1400px;
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
            margin-bottom: 10px;
        }
        .list-header .table-name {
            font-family: monospace;
            font-size: 12px;
            color: #999;
            background: #f5f5f5;
            padding: 4px 8px;
            border-radius: 3px;
            display: inline-block;
        }
        .list-header .back-link {
            display: inline-block;
            margin-top: 15px;
            color: #4a90e2;
            text-decoration: none;
        }
        .list-header .back-link:hover {
            text-decoration: underline;
        }
        .pagination {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .pagination-info {
            color: #666;
        }
        .pagination-links {
            display: flex;
            gap: 10px;
        }
        .pagination-links a {
            padding: 8px 12px;
            background: #f5f5f5;
            color: #333;
            text-decoration: none;
            border-radius: 4px;
        }
        .pagination-links a:hover, .pagination-links .current {
            background: #4a90e2;
            color: white;
        }
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #f5f5f5;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #ddd;
        }
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #eee;
        }
        tr:hover {
            background: #f9f9f9;
        }
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="list-container">
        <div class="list-header">
            <h1><?php echo htmlspecialchars($entity_info['title']); ?></h1>
            <p><?php echo htmlspecialchars($entity_info['description']); ?></p>
            <span class="table-name"><?php echo htmlspecialchars($entity_info['table']); ?></span>
            <a href="<?php echo (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . '/list'; ?>" class="back-link">← Back to Entity List</a>
        </div>
        
        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <div class="pagination-info">
                Showing <?php echo $offset + 1; ?>-<?php echo min($offset + $per_page, $total_count); ?> of <?php echo number_format($total_count); ?> records
            </div>
            <div class="pagination-links">
                <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">Previous</a>
                <?php endif; ?>
                <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo $i === $page ? 'current' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
                <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>">Next</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="table-container">
            <?php if (empty($rows)): ?>
            <div class="empty-state">
                <p>No records found.</p>
            </div>
            <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <?php foreach ($columns as $col): ?>
                        <th><?php echo htmlspecialchars($col); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                    <tr>
                        <?php foreach ($columns as $col): ?>
                        <td>
                            <?php 
                            $value = $row[$col] ?? '';
                            if (is_null($value)) {
                                echo '<em>NULL</em>';
                            } elseif (is_string($value) && strlen($value) > 100) {
                                echo htmlspecialchars(substr($value, 0, 100)) . '...';
                            } else {
                                echo htmlspecialchars($value);
                            }
                            ?>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
