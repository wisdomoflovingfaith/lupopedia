<?php

// Default color palette for the modern template
if (empty($midbackcolor_value)) { $midbackcolor_value = "#FFFFFF"; }
if (empty($midbackground_value)) { $midbackground_value = "images/blank.gif"; }
$midbackground_types = array(
  'images/blank.gif',
  'themes/vanilla/patch.png',
  'themes/vanilla/patchyellow.png',
  'themes/classic/softblueomi.png'
);

if (empty($botbackcolor_value)) { $botbackcolor_value = "#F5F7FA"; }
if (empty($botbackground_value)) { $botbackground_value = "images/blank.gif"; }
$botbackground_types = array(
  'images/blank.gif',
  'themes/vanilla/patch.png',
  'themes/vanilla/patchyellow.png'
);

if (empty($UNTRUSTED['winwidth'])) { $winwidth = 640; }
if (empty($UNTRUSTED['winheight'])) { $winheight = 520; }

$topframeheight_value = 82;

?>*** End Patch***

