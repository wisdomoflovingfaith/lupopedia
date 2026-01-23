<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>CRAFTY SYNTAX Live Help</title>
<link rel="stylesheet" type="text/css" href="themes/2025_modern/chatframe.css">
<link rel="stylesheet" type="text/css" href="themes/2025_modern/chatbubble.css">
</head>
<body class="chat-body" style="background-color: <?php echo $botbackcolor; ?>; background-image: url('<?php echo $botbackground; ?>');">
<div class="chat-shell">
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

  <?php if ($CSLH_Config['showoperator'] == "Y") { $chatwidth = "470"; } else { $chatwidth = "560"; } ?>

  <div class="chat-main <?php if ($CSLH_Config['showoperator'] == "Y") { echo 'chat-main--with-operator'; } ?>">
    <div class="chat-panel" style="max-width: <?php echo $chatwidth; ?>px;">
      <iframe
        height="260"
        width="<?php echo $chatwidth; ?>"
        frameborder="0"
        src="<?php echo $urlforchat; ?>"
        name="chatwindow"
        class="chat-panel__iframe"
        scrolling="auto"
        marginheight="0"
        marginwidth="0"
        NORESIZE="NORESIZE"></iframe>
    </div>

    <?php if ($CSLH_Config['showoperator'] == "Y") { ?>
      <aside class="chat-operator-card">
        <img src="<?php echo $urlforoperator; ?>" alt="Operator" width="90" height="90" class="chat-operator-card__image" border="0">
      </aside>
    <?php } ?>
  </div>

  <section class="chat-footer">
    <form action="javascript:nothingtodo()" method="post" name="chatter" class="chat-form">
      <input type="hidden" name="channel" value="<?php echo $channel; ?>">
      <input type="hidden" name="department" value="<?php echo $department; ?>">
      <input type="hidden" name="myid" value="<?php echo $myid; ?>">
      <input type="hidden" name="typing" value="no">
      <?php
      if ($UNTRUSTED['convertsmile'] == "ON") {
        print '<input type="hidden" name="convertsmile" value="ON">';
      } else {
        print '<input type="hidden" name="convertsmile" value="OFF">';
      }
      ?>

      <div class="chat-form__row">
        <label for="comment" class="chat-form__label"><?php echo $lang['ask']; ?></label>
        <textarea id="comment" name="comment" rows="2" class="chat-form__textarea" onkeydown="return microsoftKeyPress()"></textarea>
      </div>

      <div class="chat-form__actions">
        <?php
        if ($smiles != "N") {
          echo '<div class="chat-form__smiles">';
          if ($UNTRUSTED['convertsmile'] == "ON") {
            echo '<a href="javascript:showsmile()" class="chat-form__smiles-link"><img src="images/icon_smile.gif" border="0" alt=""> '.$lang['view'].'</a>';
            echo '<span class="chat-form__smiles-toggle"><span class="chat-form__badge chat-form__badge--on">'.$lang['ON'].'</span> / <a href="livehelp.php?convertsmile=OFF&amp;department='.$department.'">'.$lang['OFF'].'</a></span>';
          } else {
            echo '<span class="chat-form__smiles-link chat-form__smiles-link--disabled"><img src="images/icon_nosmile.gif" border="0" alt=""> '.$lang['view'].'</span>';
            echo '<span class="chat-form__smiles-toggle"><a href="livehelp.php?department='.$department.'">'.$lang['ON'].'</a> / <span class="chat-form__badge chat-form__badge--off">'.$lang['OFF'].'</span></span>';
          }
          echo '</div>';
        }
        ?>

        <div class="chat-form__buttons">
          <button type="button" class="chat-button chat-button--primary" onclick="safeSubmit()"><?php echo $lang['SAY']; ?></button>
          <button type="button" class="chat-button chat-button--ghost" onclick="exitchat()"><?php echo $lang['exit']; ?></button>
        </div>
      </div>
    </form>
  </section>
</div>
</body>
</html>


