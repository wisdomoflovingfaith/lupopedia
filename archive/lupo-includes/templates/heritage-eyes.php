<?php
/**
 * Heritage WOLFIE Eye stack — opt-in template partial (sprites + self-contained JS).
 *
 * Include only when LUPOPEDIA_PUBLIC_PATH is defined (after config), e.g.:
 *   require LUPOPEDIA_ABSPATH . 'lupo-includes/templates/heritage-eyes.php';
 *
 * Requires static assets under web root: lupo-images/ (GIF/PNG sprites per WOLFIE).
 * Behavior: lupo-includes/js/lupo-heritage-eyes.js (no DynLayer, no lupo-layers.js).
 *
 * Master stage (#lupo-heritage-master-stage): one position:fixed full-viewport wrapper with a
 * single high z-index and pointer-events:none so the page stays clickable; interactive pieces
 * (live help, close) use pointer-events:auto. Inner layers use z-index 1–8 only (local order).
 * Optional override: $heritage_eyes_stage_z_index (default 10050) if host UI stacks higher.
 *
 * Optional overrides before include:
 *   $heritage_eyes_delay_ms  — default 12000 (first appearance)
 *   $heritage_eyes_department — default 1 (livehelp.php?department=)
 *
 * @package Lupopedia
 */

if (!defined('LUPOPEDIA_PUBLIC_PATH')) {
    return;
}

$pub = rtrim((string) LUPOPEDIA_PUBLIC_PATH, '/');
if ($pub !== '' && $pub[0] !== '/') {
    $pub = '/' . $pub;
}
$imgBase = $pub . '/lupo-images/';
$heritageJsSrc = $pub . '/lupo-includes/js/lupo-heritage-eyes.js';

$stageZ = 10050;
if (isset($heritage_eyes_stage_z_index)) {
    $stageZ = (int) $heritage_eyes_stage_z_index;
}
if ($stageZ < 1) {
    $stageZ = 10050;
}

$dept = 1;
if (isset($heritage_eyes_department)) {
    $dept = (int) $heritage_eyes_department;
}
if ($dept < 1) {
    $dept = 1;
}
$livehelpPopupUrl = $pub . '/livehelp.php?department=' . $dept;

$delayMs = 12000;
if (isset($heritage_eyes_delay_ms)) {
    $delayMs = (int) $heritage_eyes_delay_ms;
}
if ($delayMs < 0) {
    $delayMs = 0;
}

$jsImgBase = json_encode($imgBase);
$jsLivehelpUrl = json_encode($livehelpPopupUrl);
$jsHeritageSrc = htmlspecialchars($heritageJsSrc, ENT_QUOTES, 'UTF-8');
$stageZOut = (int) $stageZ;
?>
<!-- start WOLFIE EYES (heritage template — opt-in) -->
<div id="lupo-heritage-master-stage" class="lupo-heritage-master-stage" style="position:fixed;left:0;top:0;width:100%;height:100%;margin:0;padding:0;border:0;z-index:<?php echo $stageZOut; ?>;pointer-events:none;overflow:visible;">
<div id="eyesnewsDiv" style="position:absolute;z-index:8;left:20px;top:160px;">
<img src="<?php echo htmlspecialchars($imgBase . 'blank.gif', ENT_QUOTES, 'UTF-8'); ?>" id="lupo-heritage-tempeyes" name="tempeyes" width="5" height="2" border="0" alt="live help">
</div>
<div id="closedblockDiv" style="position:absolute;z-index:5;left:15px;top:416px;width:346px;height:94px;visibility:hidden;">
<img src="<?php echo htmlspecialchars($imgBase . 'closed2.gif', ENT_QUOTES, 'UTF-8'); ?>" id="lupo-heritage-closed" width="346" height="94" alt="closed eyes" name="closedeyes">
</div>
<div id="backblockDiv" style="position:absolute;z-index:1;left:580px;top:210px;width:369px;height:152px;visibility:hidden;">
<img src="<?php echo htmlspecialchars($imgBase . 'right4.gif', ENT_QUOTES, 'UTF-8'); ?>" id="lupo-heritage-whites" height="152" width="369" alt="eye whites" name="eyewhites">
</div>
<div id="lefteyeblockDiv" style="position:absolute;z-index:2;left:230px;top:110px;width:38px;height:38px;visibility:hidden;">
<img src="<?php echo htmlspecialchars($imgBase . 'blueeye.gif', ENT_QUOTES, 'UTF-8'); ?>" id="lupo-heritage-eyetwo" height="38" width="38" border="0" name="eyetwo" alt="right">
</div>
<div id="righteyeblockDiv" style="position:absolute;z-index:3;left:547px;top:321px;width:38px;height:38px;visibility:hidden;">
<img src="<?php echo htmlspecialchars($imgBase . 'blueeye.gif', ENT_QUOTES, 'UTF-8'); ?>" id="lupo-heritage-eyeone" height="38" width="38" border="0" name="eyeone" alt="eye">
</div>
<div id="lidsblockDiv" style="position:absolute;z-index:4;left:715px;top:406px;width:346px;height:94px;visibility:hidden;">
<img src="<?php echo htmlspecialchars($imgBase . 'lids2.gif', ENT_QUOTES, 'UTF-8'); ?>" id="lupo-heritage-lids" width="346" height="94" alt="lids" name="lids">
</div>

<div id="livehelpblockDiv" style="position:absolute;z-index:6;left:-150px;top:-150px;width:160px;height:140px;visibility:hidden;pointer-events:auto;">
<a href="#" onclick="openchatwindow(); return false;"><img src="<?php echo htmlspecialchars($imgBase . 'livehelp_float.gif', ENT_QUOTES, 'UTF-8'); ?>" width="160" height="140" alt="livehelp" border="0"></a>
</div>

<div id="eye-close-btn" style="position:absolute;z-index:7;cursor:pointer;visibility:hidden;pointer-events:auto;" onclick="close_all_eye_divs();">X</div>
</div>

<script type="text/javascript">
window.LUPO_HERITAGE_EYES = {
    imgBase: <?php echo $jsImgBase; ?>,
    livehelpUrl: <?php echo $jsLivehelpUrl; ?>,
    delayMs: <?php echo (int) $delayMs; ?>
};
</script>
<script type="text/javascript" src="<?php echo $jsHeritageSrc; ?>"></script>
<!-- end WOLFIE EYES (heritage template) -->
