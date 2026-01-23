<html>
<head>
	<title>Crafty Syntax Mobile</title>
  <link type="text/css" rel="stylesheet" href="css/base.css" />
  <meta name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" /> 

<script type="text/javascript" src="iscroll.js?v=3.0b4"></script>


</head>

  <body  >
  <?php if ($selectedtab == "live"){ ?>
     <a href=live.php><img src=images/chats-b.jpg width=85 height=25 border=0></a>
  <?php } else { ?>
     <a href=live.php><img src=images/chats-lb.jpg width=85 height=25 border=0></a>  
  <?php } ?>  
  
    <?php if ($selectedtab == "visitors"){ ?>
     <a href=visitors.php><img src=images/visitors-b.jpg width=85 height=25 border=0></a>
  <?php } else { ?>
    <a href=visitors.php><img src=images/visitors-lb.jpg width=85 height=25 border=0></a>
  <?php } ?>  
  
    <?php if ($selectedtab == "requests"){ ?>
     <a href=requests.php><img src=images/requests-db.jpg width=85 height=25 border=0 name="requestsbut" id="requestsbut"></a>
  <?php } else { ?>
     <a href=requests.php><img src=images/requests-lb.jpg width=85 height=25 border=0 name="requestsbut" id="requestsbut"></a>
  <?php } ?>  
  
    <?php if ($selectedtab == "settings"){ ?>
     <a href=settings.php><img src=images/settings-b.jpg width=35 height=25 border=0></a>
  <?php } else { ?>
     <a href=settings.php><img src=images/settings-lb.jpg width=35 height=25 border=0></a>
  <?php } ?>  
  
     
     
     
 