<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>CRAFTY SYNTAX Live Help</title>
<link rel="stylesheet" type="text/css" href="themes/2025_modern/chatframe.css">
</head>
<body class="chat-body" style="background-color: <?php echo $botbackcolor; ?>; background-image: url('<?php echo $botbackground; ?>');">
<div class="chat-shell chat-shell--large">
  <header class="chat-header" style="background-image: url('<?php echo $topbackground; ?>'); height: <?php echo $topframeheight; ?>px;">
    <div class="chat-header__meta">
      <?php echo $cslhdue; ?>
    </div>
  </header>

  <?php if (!(empty($td_list))) { ?>
    <nav class="chat-nav" role="navigation">
      <div class="chat-nav__inner">
        <?php echo $td_list; ?>
      </div>
    </nav>
  <?php } ?>

  <div class="chat-main chat-main--full">
    <div class="chat-panel chat-panel--full" style="background-color: <?php echo $midbackcolor; ?>; background-image: url('<?php echo $midbackground; ?>');">
      <?php if (!(empty($htmlcode))) { echo $htmlcode; } else { ?>
        <iframe
          height="100%"
          width="100%"
          frameborder="0"
          src="<?php echo $urlforchat; ?>"
          name="chatwindow"
          class="chat-panel__iframe chat-panel__iframe--full"
          scrolling="auto"
          marginheight="0"
          marginwidth="0"
          NORESIZE="NORESIZE"></iframe>
      <?php } ?>
    </div>
  </div>
</div>
</body>
</html>


