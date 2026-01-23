<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<?php $connecting_label = isset($lang['connecting']) ? $lang['connecting'] : 'Connecting'; ?>
<title><?php echo $connecting_label; ?></title>
<link rel="stylesheet" type="text/css" href="themes/2025_modern/style.css">
<style>
  body {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    background-color: #f5f7fa;
  }
  .connecting-card {
    max-width: 420px;
    width: 90%;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 18px 36px rgba(15, 23, 42, 0.14);
    padding: 32px;
    text-align: center;
    font-family: "Segoe UI", "Helvetica Neue", Arial, sans-serif;
    color: #1f2933;
  }
  .connecting-card h1 {
    margin: 0 0 12px;
    font-size: 1.4rem;
    font-weight: 700;
  }
  .connecting-card p {
    margin: 0 0 24px;
    color: #475569;
    line-height: 1.6;
  }
  .connecting-spinner {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: 4px solid rgba(29, 143, 225, 0.2);
    border-top-color: #1d8fe1;
    margin: 0 auto;
    animation: spin 1s ease-in-out infinite;
  }
  @keyframes spin {
    to { transform: rotate(360deg); }
  }
</style>
</head>
<body style="background-color: <?php echo $midbackcolor; ?>; background-image: url('<?php echo $midbackground; ?>');">
  <div class="connecting-card">
    <h1><?php echo $connecting_label; ?></h1>
    <p><?php echo isset($department_a['whilewait']) ? $department_a['whilewait'] : ''; ?></p>
    <div class="connecting-spinner" aria-hidden="true"></div>
  </div>
</body>
</html>

