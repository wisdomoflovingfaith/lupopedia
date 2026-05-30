<?php
//===========================================================================
//* --    ~~                CRAFTY SYNTAX Live Help                ~~    -- *
//===========================================================================
//           URL:   https://lupopedia.com/    EMAIL: livehelp@lupopedia.com
//         Copyright (C) 2003-2023 Eric Gerdes   (https://lupopedia.com )
// ----------------------------------------------------------------------------
// Please check https://lupopedia.com/ or REGISTER your program for updates
// --------------------------------------------------------------------------
// NOTICE: Do NOT remove the copyright and/or license information any files. 
//         doing so will automatically terminate your rights to use program.
//         If you change the program you MUST clause your changes and note
//         that the original program is CRAFTY SYNTAX Live help or you will 
//         also be terminating your rights to use program and any segment 
//         of it.        
// --------------------------------------------------------------------------
// LICENSE:
//     This program is free software; you can redistribute it and/or
//     modify it under the terms of the GNU General Public License
//     as published by the Free Software Foundation; 
//     This program is distributed in the hope that it will be useful,
//     but WITHOUT ANY WARRANTY; without even the implied warranty of
//     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//     GNU General Public License for more details.
//
//     You should have received a copy of the GNU General Public License
//     along with this program in a file named LICENSE.txt .
//===========================================================================
require_once("admin_common.php");
validate_session($identity);

// get the info of this user.. 
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";	
$people = $mydatabase->query($query);
$people = $people->fetchRow(DB_FETCHMODE_ASSOC);
$myid = $people['user_id'];
$channel = $people['onchannel'];
$isadminsetting = $people['isadmin'];

$lastaction = date("Ymdhis");
$startdate =  date("Ymd");


// if no admin rights then user can not clear or remove data: 
$query = "SELECT * FROM livehelp_users WHERE sessionid='".$identity['SESSIONID']."'";
$data = $mydatabase->query($query);
$row = $data->fetchRow(DB_FETCHMODE_ASSOC);
$isadminsetting = $row['isadmin'];

// update scratch
if(isset($UNTRUSTED['new_scratch_space'])){
  $query = "UPDATE livehelp_config 
            SET scratch_space='".filter_sql(htmlspecialchars(strip_tags($UNTRUSTED['new_scratch_space'])))."'";
  $mydatabase->query($query);  
  $CSLH_Config['scratch_space'] = htmlspecialchars(strip_tags($UNTRUSTED['new_scratch_space']));
}

if(!(isset($UNTRUSTED['editbox']))){
  ?>

<SCRIPT type="text/javascript" SRC="javascript/hideshow.js"></SCRIPT>
<SCRIPT type="text/javascript">
function currentstatus(){
   	h = document.getElementById("security").height;
   	w = document.getElementById("security").width;
  if(h == 150){
   makeVisible('currentreg');
  }    
  if(w == 500){
   makeVisible('updatestuff');
  }    
} 
</SCRIPT>
<body marginheight=0 marginwidth=0 leftmargin=0 topmargin=0 bgcolor="#FFFFFF"  onload="currentstatus();">
 
<center><br><br>
<?php
   // Display any Security Warnings, Updates, Notices etc..
   // ! * Do not remove this block of code * !. 
   // There are Security holes discovered
   // every day in Open source programs and not knowing about them
   // could be fatal to your website so this is *VERY* important:
   if(empty($CSLH_Config['directoryid'])) 
     $CSLH_Config['directoryid'] = 0; 
     print "<a href=https://lupopedia.com/security/updates/?v=" . $CSLH_Config['version'] . "&d=" . $CSLH_Config['directoryid'] . "&h=" . $_SERVER['HTTP_HOST'] . " target=_blank>";
   if(empty($_SERVER['HTTPS'])) $_SERVER['HTTPS'] = "no";
   if($_SERVER['HTTPS'] == "on") 
     print "<img src=https://lupopedia.com/security/?p=scratch&randu=".date("YmdHis")."&format=image&v=". $CSLH_Config['version'] . "&d=". $CSLH_Config['directoryid'] . "&h=" . $_SERVER['HTTP_HOST'] . "  name=security id=security  border=0></a><br>";
   else
     print "<img src=https://lupopedia.com/security/?p=scratch&randu=".date("YmdHis")."&format=image&v=". $CSLH_Config['version'] . "&d=". $CSLH_Config['directoryid'] . "&h=" . $_SERVER['HTTP_HOST'] . "&e=" . $CSLH_Config['owner_email'] . "  name=security id=security  border=0></a><br>";
 } else { print "<body>"; }
?>
<br>


<DIV id=currentreg STYLE="display:none;">
<form action=registerit.php method=post name=register>
<input type=hidden value=now name=goforit>
<b>Registration ID:</b><input type=text name=directoryid size=32 MAXSIZE=32 value="<?php echo $CSLH_Config['directoryid']; ?>"><input type=submit value=UPDATE><br>
<?php
     print "<a href=https://lupopedia.com/security/updates/?v=" . $CSLH_Config['version'] . "&d=" . $CSLH_Config['directoryid'] . "&h=" . $_SERVER['HTTP_HOST']   . "  target=_blank>";
     print " How do I Register the program? (CLICK HERE)</a>";
?>
</form>
</DIV>
<!-- If there is a security issue or problem -->
<DIV id=updatestuff  STYLE="display:none;">
  <?php
       $url = "https://lupopedia.com/security/remote_scratch.php?v=" . $CSLH_Config['version'] . "&d=" . $CSLH_Config['directoryid'] . "&m=" . $CSLH_Config['membernum'] . "&h=" . $_SERVER['HTTP_HOST'];          
       print "<iframe src=$url width=90% height=250 scroll=AUTO border=0></iframe>";
   ?><br><br>
</DIV>

<DIV STYLE="width:90%; background:#FFFFFF; border:1px dotted;">
<?php 
if(isset($UNTRUSTED['editbox'])){
  ?>
<form action=scratch.php name=chatter method=post>
<input type=hidden name=whattodo value=update_scratch_space>
<textarea cols=65 rows=20 ID="Content" name=new_scratch_space><?php echo htmlspecialchars($CSLH_Config['scratch_space']); ?></textarea><br><br>
<input type=submit value=UPDATE>
</FORM>
  <?php
} else {
   // show edit button:
   if ( ($isadminsetting!="R") && ($isadminsetting!="L") ) 
     print "<a href=\"scratch.php?editbox=1\" style=\"float:right;\">EDIT NOTES</a><br/>";
 
   // Display Scratch space from configuration table.
   print nl2br($CSLH_Config['scratch_space']);
} ?>
</DIV>




<div style="margin:16px 0;padding:14px 18px;border:1px solid #2563eb;background:#eff6ff;color:#1e3a8a;border-radius:10px;line-height:1.7;">
 
<strong>Notice:</strong> CRAFTY SYNTAX ships with LUPOPEDIA&nbsp;1.0.0 exactly as the 2012-era baseline. The 3.7.5 work  
  only patched bugs and security issues—no cosmetic overhaul, no layout changes—because thousands of installs still depend on the original structure. 
  <em>Why aren’t we using upgraded CSS or modern frameworks yet?</em> Because freezing the interface at this known snapshot (yes, the same one that’s been running 
  while we took a “short” 11‑year vacation—<a href="https://lupopedia.com/what_was_crafty_syntax.php">full story here</a>) lets the migration agent diff your install 
    against the baseline, spot every custom stylesheet, Laravel helper, or template tweak, and reapply those differences automatically. Customize all you want;
     the preserved core just makes it easy for the agent (and for you) to see what changed and keep your look-and-feel when the upgrade lands.

  We’re not stuck in the past—LUPOPEDIA 1.0.0 will ship with modern front-end tooling, progressive enhancement fallbacks, and an AI migration path that learns 
  from the thousands of tweaks developers have made over the last decade. When you opt in, the agent can capture your customizations, map them into reusable 
  templates, and even offer them as optional install profiles for other sites. That’s the roadmap: lock the baseline so migrations stay precise, then let 
  every upgrade build on the best ideas the community has already proven.
<br><br>
    <strong>Heads up:</strong> CRAFTY SYNTAX 3.7.x ships unbranded by default—no extra license or upsell required.  
    We’re channeling that energy into <strong>LUPOPEDIA&nbsp;0.1.x</strong>, the next-generation live help + multi-agent platform.  
    Keep an eye on <a href="https://lupopedia.com/" target="_blank" style="color:#2563eb;font-weight:600;">lupopedia.com</a> to see when
     the release goes live and how the migration steward (PORTUNUS) can move your site forward.
     The migration steward (PORTUNUS) is increadably advanced and will be able to not only migrate your site forward but also 
     using comparisons with the baseline to make sure that all customizations are preserved to make it look and feel like your site.
 
            <h3 style="margin-top:0; margin-bottom:10px; font-size:1.2rem;">Roadmap Highlights for next version of Crafty Syntax 3.8.0</h3>
            <ul style="margin:0; padding-left:22px; line-height:1.7;">
                <li><strong>Laravel Adapter Overlay:</strong> Wrap the legacy stack with optional Laravel routes, controllers, and Eloquent models while keeping direct PHP entry points online for shared hosting.</li>
                <li><strong>AI-Orchestrated Support:</strong> Expose `/api/wolfie/` endpoints so WOLFIE agents (GROK, GEMINI, ROSE, THALIA) can triage chats, hand off to humans, and log outcomes into LUPOPEDIA collections.</li>
                <li><strong>Resilience Toolchain:</strong> Enforce WOLFIE headers, PHPStan/Rector analysis, OWASP scans, and automated regression suites so every patch ships with audit trails and rollback plans.</li>
                <li><strong>Performance & Accessibility:</strong> Add WebSockets with image fallbacks, optional Redis/pgvector layers, WCAG 2.2 UI upgrades, and expanded localization (20+ languages including Hawaiian Pidgin).</li>
            </ul>
            <p style="margin:14px 0 0 0;">
                Follow progress and contribute via the <a href="https://lupopedia.com/planfor_next_version_craftysyntax.php" style="color:#2563EB; font-weight:600;">Crafty Syntax 3.8.0 TODO plan</a>.
            </p>
        </div>
   

