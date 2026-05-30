<?php
  include_once("class/Browser.php");
  //  if(empty($user_info['useragent']))
      $client_agent = ( !empty($_SERVER['HTTP_USER_AGENT']) ) ? $_SERVER['HTTP_USER_AGENT'] : ( ( !empty($_ENV['HTTP_USER_AGENT']) ) ? $_ENV['HTTP_USER_AGENT'] : $HTTP_USER_AGENT );
  //  else
    //  $client_agent = $user_info['useragent'];

    $b = new Browser($client_agent);
print "user" . $client_agent;
print "<br>";
print "<b>User Agent :</b>" .  $b->getUserAgent()  ."<br>"; // STRING - USER_AGENT_STRING
print "<b>Browser :</b>" . $b->getBrowser()  ."<br>"; // STRING - USER_AGENT_STRING
print  "<b>Browser_Version :</b>". $b->getVersion()   ."<br>"; // STRING - USER_AGENT_STRING 
?>