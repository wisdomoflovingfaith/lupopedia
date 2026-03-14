<?php
/**
 * Live Help JavaScript generator — Lupopedia schema + PDO.
 * Replicates legacy/craftysyntax/livehelp_js.php behavior using new tables only.
 * No livehelp_* tables in runtime; uses lupo_actor_channel_roles, lupo_departments, etc.
 * All URLs use LUPOPEDIA_PUBLIC_PATH (subfolder-install doctrine). No /public folder.
 *
 * @package Lupopedia
 * @see lupo-database/lupopedia/toon/*.toon.json for canonical schema
 */

// Installation path: project root is document root for the app (subfolder-install doctrine)
if (!defined('LUPOPEDIA_PATH')) {
    define('LUPOPEDIA_PATH', __DIR__);
}
if (!defined('LUPOPEDIA_PUBLIC_PATH')) {
    define('LUPOPEDIA_PUBLIC_PATH', '/' . basename(__DIR__));
}

// PHP 5.3-safe random bytes (reuse installer pattern when not loaded)
if (!function_exists('lupo_random_bytes')) {
    function lupo_random_bytes($length) {
        if (function_exists('random_bytes')) {
            return random_bytes($length);
        }
        if (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($length);
            return $bytes !== false ? $bytes : lupo_random_bytes_fallback($length);
        }
        return lupo_random_bytes_fallback($length);
    }
    function lupo_random_bytes_fallback($length) {
        $bytes = '';
        for ($i = 0; $i < $length; $i++) {
            $bytes .= chr(mt_rand(0, 255));
        }
        return $bytes;
    }
}

// Config path: lupopedia-config.php first; config.php only if lupopedia-config does not exist (legacy)
if (!defined('LUPOPEDIA_CONFIG_PATH')) {
    $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : '';
    if (file_exists(dirname($docRoot) . '/lupopedia-config.php')) {
        define('LUPOPEDIA_CONFIG_PATH', dirname($docRoot) . '/lupopedia-config.php');
    } elseif (file_exists(dirname($docRoot) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php')) {
        define('LUPOPEDIA_CONFIG_PATH', dirname($docRoot) . LUPOPEDIA_PUBLIC_PATH . '/lupopedia-config.php');
    } elseif (@file_exists(LUPOPEDIA_PATH . '/lupopedia-config.php')) {
        define('LUPOPEDIA_CONFIG_PATH', LUPOPEDIA_PATH . '/lupopedia-config.php');
    } elseif (file_exists(dirname($docRoot) . '/config.php')) {
        define('LUPOPEDIA_CONFIG_PATH', dirname($docRoot) . '/config.php');
    } elseif (file_exists(dirname($docRoot) . LUPOPEDIA_PUBLIC_PATH . '/config.php')) {
        define('LUPOPEDIA_CONFIG_PATH', dirname($docRoot) . LUPOPEDIA_PUBLIC_PATH . '/config.php');
    } elseif (@file_exists(LUPOPEDIA_PATH . '/config.php')) {
        define('LUPOPEDIA_CONFIG_PATH', LUPOPEDIA_PATH . '/config.php');
    }
}
if (!defined('LUPOPEDIA_CONFIG_PATH') || !is_file(LUPOPEDIA_CONFIG_PATH)) {
    header('Content-Type: application/javascript; charset=utf-8');
    echo "console.error('Live Help: config not found');";
    exit;
}
require_once LUPOPEDIA_CONFIG_PATH;
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    define('LUPOPEDIA_CONFIG_LOADED', true);
}

require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'class-pdo_db.php';
require_once LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'class-DatabaseFactory.php';

try {
    $mydatabase = DatabaseFactory::getConnection();
} catch (Exception $e) {
    header('Content-Type: application/javascript; charset=utf-8');
    echo "console.error('Live Help: database unavailable');";
    exit;
}

$LUPO_TABLE_PREFIX = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';
$prefix = $LUPO_TABLE_PREFIX;

// UNTRUSTED = sanitized GET (same param names as legacy)
$UNTRUSTED = array(
    'website'    => isset($_GET['website']) ? (string) $_GET['website'] : '',
    'department' => isset($_GET['department']) ? (string) $_GET['department'] : '',
    'winwidth'   => isset($_GET['winwidth']) ? (string) $_GET['winwidth'] : '',
    'winheight'  => isset($_GET['winheight']) ? (string) $_GET['winheight'] : '',
    'creditline' => isset($_GET['creditline']) ? (string) $_GET['creditline'] : '',
    'usetable'   => isset($_GET['usetable']) ? (string) $_GET['usetable'] : '',
    'pingtimes'  => isset($_GET['pingtimes']) ? (string) $_GET['pingtimes'] : '',
    'frameparent' => isset($_GET['frameparent']) ? (string) $_GET['frameparent'] : '',
    'relative'   => isset($_GET['relative']) ? (string) $_GET['relative'] : '',
    'secure'     => isset($_GET['secure']) ? (string) $_GET['secure'] : '',
    'force'      => isset($_GET['force']) ? (string) $_GET['force'] : '',
    'what'       => isset($_GET['what']) ? (string) $_GET['what'] : '',
    'filter'     => isset($_GET['filter']) ? (string) $_GET['filter'] : '',
    'dynamic'    => isset($_GET['dynamic']) ? (string) $_GET['dynamic'] : '',
    'eo'         => isset($_GET['eo']) ? (string) $_GET['eo'] : '',
    'cmd'        => isset($_GET['cmd']) ? (string) $_GET['cmd'] : '',
);
if (!empty($UNTRUSTED['cmd'])) {
    $UNTRUSTED['what'] = $UNTRUSTED['cmd'];
}

// Identity (ghost session: no DB session created here)
$session_id = isset($_GET['cslhVISITOR']) ? (string) $_GET['cslhVISITOR'] : '';
if ($session_id === '' && !empty($_COOKIE['cslhVISITOR'])) {
    $session_id = (string) $_COOKIE['cslhVISITOR'];
}
if ($session_id === '') {
    $session_id = 'v' . bin2hex(lupo_random_bytes(12));
}
$identity = array(
    'SESSIONID'  => $session_id,
    'COOKIE_SET' => !empty($_COOKIE['cslhVISITOR']) ? 'Y' : 'N',
);

// WEBPATH from LUPOPEDIA_PUBLIC_PATH only (subfolder-install doctrine; no hardcoded paths)
$WEBPATH = defined('LUPOPEDIA_PUBLIC_PATH') ? (rtrim(LUPOPEDIA_PUBLIC_PATH, '/') . '/') : '/';
$CSLH_Config = array('floatxy' => '200|160');

$parentdot = !empty($UNTRUSTED['frameparent']) ? 'parent.' : '';

// Default department: first user-selectable lupo_departments row (exclude department 0 = system)
$defaultdepartment = 0;
$row2 = $mydatabase->fetchRow(
    "SELECT department_id FROM {$prefix}departments WHERE is_deleted = 0 AND department_id > 0 ORDER BY department_id ASC LIMIT 1"
);
if ($row2 !== null) {
    $defaultdepartment = (int) $row2['department_id'];
}
if (!empty($UNTRUSTED['website'])) {
    $UNTRUSTED['website'] = (string) (int) $UNTRUSTED['website'];
} else {
    $UNTRUSTED['website'] = '0';
}

// Department: lupo_departments (creditline/theme from settings_json)
$dept_id = !empty($UNTRUSTED['department']) ? (int) $UNTRUSTED['department'] : 0;
if ($dept_id === 0) {
    $dept_id = $defaultdepartment;
}
$UNTRUSTED['department'] = (string) $dept_id;

$creditline = 'Y';
$theme = 'default';
$department = $dept_id;
$data_d = null;

if ($dept_id > 0) {
    $data_d = $mydatabase->fetchRow(
        "SELECT department_id, name, settings_json FROM {$prefix}departments WHERE department_id = :id AND is_deleted = 0",
        array('id' => $dept_id)
    );
    if ($data_d !== null) {
        $department = (int) $data_d['department_id'];
        if (!empty($data_d['settings_json'])) {
            $json = is_string($data_d['settings_json']) ? json_decode($data_d['settings_json'], true) : $data_d['settings_json'];
            if (is_array($json)) {
                if (isset($json['creditline'])) {
                    $creditline = substr((string) $json['creditline'], 0, 1);
                }
                if (isset($json['theme'])) {
                    $theme = (string) $json['theme'];
                }
            }
        }
    }
}

if (!empty($UNTRUSTED['winwidth'])) {
    $winwidth = (int) $UNTRUSTED['winwidth'];
}
if (!empty($UNTRUSTED['winheight'])) {
    $winheight = (int) $UNTRUSTED['winheight'];
}
if (!empty($UNTRUSTED['creditline'])) {
    $creditline = substr($UNTRUSTED['creditline'], 0, 1);
}

$usetable = 'Y';
if (!empty($UNTRUSTED['usetable'])) {
    $usetable = (strtoupper($UNTRUSTED['usetable']) === 'Y') ? 'Y' : 'N';
}

if (empty($winwidth)) {
    $winwidth = 600;
}
if (empty($winheight)) {
    $winheight = 450;
}
if ($theme === 'bubble_window' && $winwidth < 650) {
    $winwidth = 650;
}
if ($theme === 'bubble_window' && $winheight < 480) {
    $winheight = 480;
}

// Query string passthrough for image.php / livehelp.php
$querystringadd = '&cslheg=1';
if (!empty($_GET['serversession'])) {
    $querystringadd .= '&serversession=1';
} else {
    $querystringadd .= '&serversession=0';
}
if (!empty($UNTRUSTED['relative'])) {
    $querystringadd .= '&relative=Y';
}
if (!empty($_GET['username'])) {
    $querystringadd .= '&username=' . rawurlencode((string) $_GET['username']);
}

$leaveamessage = 'YES';
if ($dept_id > 0 && $data_d !== null && !empty($data_d['settings_json'])) {
    $json = is_string($data_d['settings_json']) ? json_decode($data_d['settings_json'], true) : $data_d['settings_json'];
    if (is_array($json) && isset($json['leaveamessage'])) {
        $leaveamessage = (strtoupper((string) $json['leaveamessage']) === 'NO') ? 'NO' : 'YES';
    }
}
if (!empty($_GET['leaveamessage'])) {
    $leaveamessage = (strtoupper((string) $_GET['leaveamessage']) === 'NO') ? 'NO' : 'YES';
}

header('Content-Type: application/javascript; charset=utf-8');
?>var WEBPATH = "<?php echo addslashes($WEBPATH); ?>";
//-----------------------------------------------------------------
// File: livehelp.js : generated for department : <?php echo $department; ?>

//      - This is the client side Javascript file to control the 
//        image shown on the clients website. It should be called
//        on the clients HTML page as a javascript include such as:
//        script src="http://yourwebsite.com/livehelp/livehelp_js.php"
//        This js file will show the image of online.gif if an operator
//        is online otherwise it will show offline.gif . Also a 
//        second image is placed on the site as a control image 
//        where the width of the image controls the actions made by 
//        the operator to the poor little visitor..  
// 
//-----------------------------------------------------------------

// GLOBALS..
//------------
cscontrol_<?php echo $department; ?>= new Image;
popcontrol_<?php echo $department; ?>= new Image;
popcontrol_<?php echo $department; ?>2= new Image;
popcontrol_<?php echo $department; ?>3= new Image;
keyhundreds_<?php echo $department; ?>= new Image;
keytens_<?php echo $department; ?>= new Image;
keyones_<?php echo $department; ?>= new Image;
keyhundreds_<?php echo $department; ?>_value= 0;
keytens_<?php echo $department; ?>_value= 0;
keyones_<?php echo $department; ?>_value= 0;
place_<?php echo $department; ?> =1;

var csloaded_<?php echo $department; ?> = false;

<?php if (empty($UNTRUSTED['pingtimes'])) { $UNTRUSTED['pingtimes'] = '12'; } ?>
var csTimeout_<?php echo $department; ?> = <?php echo (int) $UNTRUSTED['pingtimes']; ?>;

var csID_<?php echo $department; ?> = null;

var openLiveHelpalready = false;
var openDHTMLalready = false;
var openDHTMLlayer = false;
var ismac = navigator.platform.indexOf('Mac');

<?php
$layerid = 0;
$defaultLayerRow = $mydatabase->fetchRow(
    "SELECT crafty_syntax_layer_invite_id FROM {$prefix}crafty_syntax_layer_invites WHERE is_active = 1 AND is_deleted = 0 LIMIT 1"
);
if ($defaultLayerRow !== null) {
    $layerid = (int) $defaultLayerRow['crafty_syntax_layer_invite_id'];
}
$visitorRow = $mydatabase->fetchRow(
    "SELECT actor_id, session_data FROM {$prefix}sessions WHERE session_id = :sid AND is_deleted = 0 LIMIT 1",
    array('sid' => $identity['SESSIONID'])
);
if ($visitorRow !== null && !empty($visitorRow['session_data'])) {
    $datapairs = explode('&', (string) $visitorRow['session_data']);
    foreach ($datapairs as $pair) {
        $dataset = explode('=', $pair, 2);
        if (isset($dataset[1]) && $dataset[0] === 'invite') {
            $layerid = (int) $dataset[1];
            break;
        }
    }
}
echo "\n var defaultlayer = " . (int) $layerid . "; \n";
?>

NS4 = (document.layers) ? 1 : 0; 
IE4 = (document.all) ? 1 : 0; 
W3C = (document.getElementById) ? 1 : 0;   
function makeVisible ( name ) { 
  var ele; 
  if ( W3C ) { ele = document.getElementById(name); } else if ( NS4 ) { ele = document.layers[name]; } else { ele = document.all[name]; } 
  if ( NS4 ) { ele.visibility = "show"; } else { ele.style.visibility = "visible"; ele.style.display = "inline"; } 
} 
function makeInvisible ( name ) { 
  if (W3C) { document.getElementById(name).style.visibility = "hidden"; document.getElementById(name).style.display = "none"; } 
  else if (NS4) { document.layers[name].visibility = "hide"; } else { document.all[name].style.visibility = "hidden"; document.all[name].style.style.display = "none"; } 
} 

var xmlhttp = false; 
var XMLHTTP_supported = false;
function gettHTTPreqobj(){
	try { xmlhttp = new XMLHttpRequest(); } catch (e1) { 
 	 try { xmlhttp = new ActiveXObject("Msxml2.XMLHTTP"); } catch (e2) { 
     try { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); } catch (e3) { xmlhttp = false; } 
  } } 
 return xmlhttp;
}
function loadXMLHTTP() { randu=Math.round(Math.random()*99); loadOK('xmlhttp.php?whattodo=ping&rand='+ randu); } 
function loadOK(fragment_url) {
	xmlhttp = gettHTTPreqobj();
    xmlhttp.open("GET", fragment_url, true); 
    xmlhttp.onreadystatechange = function() { 
     if (xmlhttp.readyState == 4 && xmlhttp.status == 200) { isok = xmlhttp.responseText; if(isok == "OK") XMLHTTP_supported = true; checkXMLHTTP(); } 
    }; 
    try { xmlhttp.send(null); } catch(whocares){}
}
 function oXMLHTTPStateHandler() { 
     if(typeof oXMLHTTP!='undefined') { 
        if( oXMLHTTP.readyState==4 ) { 
          if( oXMLHTTP.status==200 ) { 
               try { resultingtext = oXMLHTTP.responseText; } catch(e) { resultingtext ="error=1;"; }
               ExecRes(unescape(resultingtext)); delete oXMLHTTP; oXMLHTTP=false;
            } else { return false; }   
           }
         } 
      }  
 function PostForm(sURL, sPostData) { 
         oXMLHTTP = gettHTTPreqobj(); 
         if( typeof(oXMLHTTP)!="object" ) return false; 
         oXMLHTTP.onreadystatechange = oXMLHTTPStateHandler; 
         try { oXMLHTTP.open("POST", sURL, true); } catch(er) { return false; }    
         oXMLHTTP.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
         try { oXMLHTTP.send(sPostData); } catch(whocares){}
         return true; 
      }  
 function GETForm(sURL) { 
         oXMLHTTP = gettHTTPreqobj();          
         if( typeof(oXMLHTTP)!="object" ) return false;          
         oXMLHTTP.onreadystatechange = oXMLHTTPStateHandler; 
         try { oXMLHTTP.open("GET", sURL, true); } catch(er) { return false; }    
         try { oXMLHTTP.send(null); } catch(whocares){}
         return true; 
      }
xmlhttp = gettHTTPreqobj();

function wherecslhisdue_<?php echo $department; ?>(){
  var layerdoesnotexist_<?php echo $department; ?> = 0;
  var looking = 'tp://www.sales'+'synt'+'ax.n'+'et';
  var maccrap = '';
  var layerinvitecrap =  '<div id="layerinvite_<?php echo $department; ?>" style="position:absolute; z-index:99992; visibility:hidden; display:none; top:-400px; left:-400px; width:400px; height:400px;"></div>';

  var x_<?php echo $department; ?>=document.getElementById("craftysyntax_<?php echo $department; ?>");
  if(x_<?php echo $department; ?>){ } else {
    var x_<?php echo $department; ?>=document.getElementById("craftysyntax");
    if(!(x_<?php echo $department; ?>=document.getElementById("craftysyntax"))){ layerdoesnotexist_<?php echo $department; ?> = 1; x_<?php echo $department; ?> = 0; }
  }
  if(layerdoesnotexist_<?php echo $department; ?> != 1){ var y_<?php echo $department; ?> = x_<?php echo $department; ?>.innerHTML; } else { var y_<?php echo $department; ?> = looking; }

if (ismac > -1) {
	randu=Math.round(Math.random()*9999);
    maccrap = '<div id=imagesfordumbmac_<?php echo $department; ?> style=display:none><img id="imageformac_<?php echo $department; ?>" name="imageformac_<?php echo $department; ?>" src="' + WEBPATH + 'lupo-images/blank.gif" border="0"><img id="imageformac_<?php echo $department; ?>_one" name="imageformac_<?php echo $department; ?>_one" src="' + WEBPATH + 'lupo-images/blank.gif" border="0"><img id="imageformac_<?php echo $department; ?>_ten" name="imageformac_<?php echo $department; ?>_ten" src="' + WEBPATH + 'lupo-images/blank.gif" border="0"><img id="imageformac_<?php echo $department; ?>_hun" name="imageformac_<?php echo $department; ?>_hun" src="' + WEBPATH + 'lupo-images/blank.gif" border="0"></div>';
}

<?php if (empty($UNTRUSTED['dynamic'])) { ?> if (y_<?php echo $department; ?>.indexOf(looking)!=-1) { } else { <?php } ?>

<?php if ($usetable === 'Y') { ?>
 	   var newHTML =  '<table cellpadding=0 cellspacing=0 border=0><tr><td valign=top align=center><a name="chatRef" href="javascript:openLiveHelp(<?php echo (int) $UNTRUSTED['department']; ?>)" onclick="javascript:csTimeout_<?php echo $department; ?>=0;"><img name="csIcon" src="' + urltohelpimage_<?php echo $department; ?> + '" alt="Live Help" border="0"></a></td></tr>';
<?php if ($creditline !== 'N') { ?>	   newHTML =  newHTML + '<tr><td valign=top align=center><a href="https://lupopedia.com/?utm_source=poweredby&utm_campaign=poweredby" target="_blank" rel="noopener"><img src=' + urltocslhimage_<?php echo $department; ?> + ' alt="Powered by LUPOPEDIA" border=0 style="margin-top:4px;"></a></td></tr>';<?php } ?>
 	   newHTML = newHTML + '</table>'; 	   
<?php } else { ?>
 	    var newHTML =  '<a name="chatRef" href="javascript:openLiveHelp(<?php echo (int) $UNTRUSTED['department']; ?>)" onclick="javascript:csTimeout_<?php echo $department; ?>=0;"><img name="csIcon" src="' + urltohelpimage_<?php echo $department; ?> + '" alt="Live Help" border="0"></a>';
<?php if ($creditline !== 'N') { ?>     newHTML =  newHTML + '<br clear="both"><a href="https://lupopedia.com/?utm_source=poweredby&utm_campaign=poweredby" target="_blank" rel="noopener"><img src=' + urltocslhimage_<?php echo $department; ?> + ' alt="Powered by LUPOPEDIA" border=0 style="margin-top:4px;"></a>';<?php } ?>
<?php } ?>

   if(layerdoesnotexist_<?php echo $department; ?> != 1){ x_<?php echo $department; ?>.innerHTML = newHTML + layerinvitecrap + maccrap; }
 <?php if (empty($UNTRUSTED['dynamic'])) { ?>  }
 <?php } else { ?>
if(layerdoesnotexist_<?php echo $department; ?> == 1){ alert('Sales Sytnax Live Help Error: DIV layer with the id of: craftysyntax_<?php echo $department; ?> does not exist. This is needed order to put the live help icon on your page. please add <DIV id=craftysyntax_<?php echo $department; ?> > [[your javascript for your live help ]]  </div> around your live help code'); }
<?php } ?>
}

function csrepeat_<?php echo $department; ?>(){ 
     if (csTimeout_<?php echo $department; ?> < 1){ return; } else {
       csTimeout_<?php echo $department; ?>--;
       csgetimage_<?php echo $department; ?>();     
       setTimeout('csrepeat_<?php echo $department; ?>()', 10000);
     }
}	

function csgetimage_<?php echo $department; ?>(){	 
   csID_<?php echo $department; ?>=Math.round(Math.random()*9999);
   randu=Math.round(Math.random()*9999);
   cscontrol_<?php echo $department; ?> = new Image;
   locationvar = '' + <?php echo $parentdot; ?>document.location;
   locationvar = locationvar.replace(new RegExp("[^A-Za-z0-9_)\+\^{}~( ',\.\&\%=/\\?#:-]","g"),"");
   locationvar = locationvar.replace(new RegExp("=[a-z0-9]{32}","g"),"x=1");
   locationvar = locationvar.replace(new RegExp("[\.]","g"),"--dot--");
   locationvar = locationvar.replace(new RegExp("http://","g"),"");
   locationvar = locationvar.replace(new RegExp("https://","g"),"");   
   locationvar = locationvar.substr(0,250);
   var_title = '' + <?php echo $parentdot; ?>document.title;
   var_title = var_title.replace(new RegExp("[^A-Za-z0-9_)\+\^{}~( ',\.\&\%=/\\?#:-]","g"),"");
   var_title = var_title.substr(0,100);
   var_referrer = '' + <?php echo $parentdot; ?>document.referrer;
   var_referrer = var_referrer.replace(new RegExp("[^A-Za-z0-9_)\+\^{}~( ',\.\&\%=/\\?#:-]","g"),"");
   var_referrer = var_referrer.replace(new RegExp("=[a-z0-9]{32}","g"),"x=1"); 
   var_referrer = var_referrer.replace(new RegExp("[\.]","g"),"--dot--");
   var_referrer = var_referrer.replace(new RegExp("http://","g"),"");
   var_referrer = var_referrer.replace(new RegExp("https://","g"),"");      
   var_referrer = var_referrer.substr(0,250);
   <?php if (!empty($UNTRUSTED['filter'])) { ?>
   var locationvar_array=locationvar.split("?"); locationvar = locationvar_array[0];
   var var_referrer_array=var_referrer.split("?"); var_referrer = var_referrer_array[0]; var_title = "";
   <?php } ?>
	 var u_<?php echo $department; ?> = WEBPATH + 'image.php?' + 
					'what=userstat' + '&page=' + escape(locationvar) + '&randu=' + randu +
					'&pageid=' + csID_<?php echo $department; ?> +
					'&department=' + <?php echo (int) $UNTRUSTED['department']; ?> +
					'&cslhVISITOR=' + '<?php echo addslashes($identity['SESSIONID']); ?>' +
					'&title=' + escape(var_title) + '&referer=' + escape(var_referrer) + 					
					'<?php echo addslashes($querystringadd); ?>';
	 if (ismac > -1){
       document.getElementById("imageformac_<?php echo $department; ?>").src= u_<?php echo $department; ?>;
       document.getElementById("imageformac_<?php echo $department; ?>").onload = cslookatimage_<?php echo $department; ?>;
    } else {
       cscontrol_<?php echo $department; ?>.src = u_<?php echo $department; ?>;
       cscontrol_<?php echo $department; ?>.onload = cslookatimage_<?php echo $department; ?>;
    }      	
}

function cslookatimage_<?php echo $department; ?>(){
	if(typeof(cscontrol_<?php echo $department; ?>) == 'undefined' ){ return; }  
	if (ismac > -1){ w_<?php echo $department; ?> = document.getElementById("imageformac_<?php echo $department; ?>").width; } else { w_<?php echo $department; ?> = cscontrol_<?php echo $department; ?>.width; }
    if((ismac > -1) && (w_<?php echo $department; ?> == 0)){
      makeVisible('imagesfordumbmac_<?php echo $department; ?>');     
      w_<?php echo $department; ?> = document.getElementById("imageformac_<?php echo $department; ?>").width;
      makeInvisible('imagesfordumbmac_<?php echo $department; ?>');         
    }
    if ((w_<?php echo $department; ?> == 55) && (openLiveHelpalready != 1)) { openWantsToChat_<?php echo (int) $UNTRUSTED['department']; ?>(); openLiveHelpalready = 1; } 
    if ((w_<?php echo $department; ?> == 25) && !(openDHTMLalready)) { loadKey_<?php echo (int) $UNTRUSTED['department']; ?>(); } 	        	        
    delete cscontrol_<?php echo $department; ?>; cscontrol_<?php echo $department; ?> = new Image;
}

function openLiveHelp(department){
  if(openDHTMLlayer == 1){ makeInvisible('layerinvite_<?php echo (int) $UNTRUSTED['department']; ?>'); }  
  openDHTMLlayer = 0;  openDHTMLalready = true;  csTimeout_<?php echo $department; ?>=0; 
  <?php if ($identity['COOKIE_SET'] === 'Y') { ?>
    window.open(WEBPATH + 'livehelp.php?department=' + department + '&website=<?php echo (int) $UNTRUSTED['website']; ?>&cslhVISITOR=<?php echo addslashes($identity['SESSIONID']); ?><?php echo addslashes($querystringadd); ?>', 'chat54050872', 'width=<?php echo $winwidth; ?>,height=<?php echo $winheight; ?>,menubar=no,scrollbars=1,resizable=1');
  <?php } else { ?>
    window.open(WEBPATH + 'livehelp.php?department=' + department + '&website=<?php echo (int) $UNTRUSTED['website']; ?>&<?php echo addslashes($querystringadd); ?>', 'chat54050872', 'width=<?php echo $winwidth; ?>,height=<?php echo $winheight; ?>,menubar=no,scrollbars=1,resizable=1');
  <?php } ?>
}

function getKeys_<?php echo (int) $UNTRUSTED['department']; ?>(){
  	if (ismac > -1){
      w3_<?php echo $department; ?> = document.getElementById("imageformac_<?php echo $department; ?>_hun").width;
      w2_<?php echo $department; ?> = document.getElementById("imageformac_<?php echo $department; ?>_ten").width;
      w1_<?php echo $department; ?> = document.getElementById("imageformac_<?php echo $department; ?>_one").width;
      if(w1_<?php echo $department; ?> == 0){ makeVisible('imagesfordumbmac_<?php echo $department; ?>'); w1_<?php echo $department; ?> = document.getElementById("imageformac_<?php echo $department; ?>_one").width; makeInvisible('imagesfordumbmac_<?php echo $department; ?>'); }
      if(w2_<?php echo $department; ?> == 0){ makeVisible('imagesfordumbmac_<?php echo $department; ?>'); w2_<?php echo $department; ?> = document.getElementById("imageformac_<?php echo $department; ?>_ten").width; makeInvisible('imagesfordumbmac_<?php echo $department; ?>'); }
      if(w3_<?php echo $department; ?> == 0){ makeVisible('imagesfordumbmac_<?php echo $department; ?>'); w3_<?php echo $department; ?> = document.getElementById("imageformac_<?php echo $department; ?>_hun").width; makeInvisible('imagesfordumbmac_<?php echo $department; ?>'); }
    } else {
      w3_<?php echo $department; ?> = keyhundreds_<?php echo $department; ?>.width;  w2_<?php echo $department; ?> = keytens_<?php echo $department; ?>.width;  w1_<?php echo $department; ?> = keyones_<?php echo $department; ?>.width;              
    }      
    if(w1_<?php echo $department; ?><100) w1_<?php echo $department; ?> = 100;
    if(w2_<?php echo $department; ?><100) w2_<?php echo $department; ?> = 100;
    if(w3_<?php echo $department; ?><100) w3_<?php echo $department; ?> = 100;    
    total = ((w3_<?php echo $department; ?>-100)*100) + ((w2_<?php echo $department; ?>-100)*10) + (w1_<?php echo $department; ?>-100);
    openDHTML_<?php echo (int) $UNTRUSTED['department']; ?>(total);
}

function loadKey_<?php echo (int) $UNTRUSTED['department']; ?>(){  
	 randu=Math.round(Math.random()*9999);
	 if(place_<?php echo $department; ?> == 3){
	 var u3_<?php echo $department; ?> = WEBPATH + 'image.php?' + 'what=getlayerinvite&whatplace=hundreds' + '&randu=' + randu + '&department=' + <?php echo (int) $UNTRUSTED['department']; ?> + '&cslhVISITOR=' + '<?php echo addslashes($identity['SESSIONID']); ?>' + '<?php echo addslashes($querystringadd); ?>';
      if (ismac > -1){ document.getElementById("imageformac_<?php echo $department; ?>_hun").src= u3_<?php echo $department; ?>; document.getElementById("imageformac_<?php echo $department; ?>_hun").onload = getKeys_<?php echo (int) $UNTRUSTED['department']; ?>; } else { keyhundreds_<?php echo $department; ?>.src = u3_<?php echo $department; ?>; keyhundreds_<?php echo $department; ?>.onload = getKeys_<?php echo (int) $UNTRUSTED['department']; ?>; }
    }			
	 if(place_<?php echo $department; ?> == 2){ place_<?php echo $department; ?> = 3;
	 var u2_<?php echo $department; ?> = WEBPATH + 'image.php?' + 'what=getlayerinvite&whatplace=tens' + '&randu=' + randu + '&department=' + <?php echo (int) $UNTRUSTED['department']; ?> + '&cslhVISITOR=' + '<?php echo addslashes($identity['SESSIONID']); ?>' + '<?php echo addslashes($querystringadd); ?>';
      if (ismac > -1){ document.getElementById("imageformac_<?php echo $department; ?>_ten").src= u2_<?php echo $department; ?>; document.getElementById("imageformac_<?php echo $department; ?>_ten").onload = loadKey_<?php echo (int) $UNTRUSTED['department']; ?>; } else { keytens_<?php echo $department; ?>.src = u2_<?php echo $department; ?>; keytens_<?php echo $department; ?>.onload = loadKey_<?php echo (int) $UNTRUSTED['department']; ?>; }
    }    								   
	 if(place_<?php echo $department; ?> == 1){ place_<?php echo $department; ?> = 2;	
	    var u1_<?php echo $department; ?> = WEBPATH + 'image.php?' + 'what=getlayerinvite&whatplace=ones' + '&randu=' + randu + '&department=' + <?php echo (int) $UNTRUSTED['department']; ?> + '&cslhVISITOR=' + '<?php echo addslashes($identity['SESSIONID']); ?>' + '<?php echo addslashes($querystringadd); ?>';
      if (ismac > -1){ document.getElementById("imageformac_<?php echo $department; ?>_one").src= u1_<?php echo $department; ?>; document.getElementById("imageformac_<?php echo $department; ?>_one").onload = loadKey_<?php echo (int) $UNTRUSTED['department']; ?>; } else { keyones_<?php echo $department; ?>.src = u1_<?php echo $department; ?>; keyones_<?php echo $department; ?>.onload = loadKey_<?php echo (int) $UNTRUSTED['department']; ?>; }
    }
}

function openDHTML_<?php echo (int) $UNTRUSTED['department']; ?>(total){ 
  var html = '';
  <?php
$urlreplace = $WEBPATH . 'livehelp.php?department=' . (int) $UNTRUSTED['department'] . '&website=' . (int) $UNTRUSTED['website'] . '&resizewidth=500&resizeheight=350';
$layers = $mydatabase->fetchAll("SELECT crafty_syntax_layer_invite_id, image_name, image_map FROM {$prefix}crafty_syntax_layer_invites WHERE is_deleted = 0");
foreach ($layers as $invite) {
    $layerid_val = (int) $invite['crafty_syntax_layer_invite_id'];
    $imagename = $invite['image_name'];
    $imagemap = (string) (isset($invite['image_map']) ? $invite['image_map'] : '');
    echo "if (total == " . $layerid_val . ")\n";
    $imagemapof = str_replace('openLiveHelp()', 'openLiveHelp(' . (int) $UNTRUSTED['department'] . ')', $imagemap);
    $imagemapof = str_replace("'", '"', $imagemapof);
    $imagemapof = preg_replace("/\r\n/", '', $imagemapof);
    $imagemapof = preg_replace("/\n/", '', $imagemapof);
    $imagemapof = str_replace('openLiveHelp_force()', $urlreplace, $imagemapof);
    echo "    html = '<img src=' + WEBPATH + 'layer_invites/" . addslashes($imagename) . "  border=0 usemap=#myimagemap></a>" . addslashes($imagemapof) . "'\n";
}
?>
  makeVisible('layerinvite_<?php echo (int) $UNTRUSTED['department']; ?>');
  var w_<?php echo $department; ?>=document.getElementById('layerinvite_<?php echo (int) $UNTRUSTED['department']; ?>');
  w_<?php echo $department; ?>.innerHTML = html; 
  var u_<?php echo $department; ?> = WEBPATH + 'image.php?' + 'what=changestat' + '&towhat=invited' + '&cslhVISITOR=' + '<?php echo addslashes($identity['SESSIONID']); ?>' + '<?php echo addslashes($querystringadd); ?>';
  popcontrol_<?php echo $department; ?>2.src = u_<?php echo $department; ?>; stillopen = 1;
  setTimeout('moveDHTML_<?php echo (int) $UNTRUSTED['department']; ?>()', 9); openDHTMLalready = true; openDHTMLlayer = true;
}

function closeDHTML(){ 
	makeInvisible('layerinvite_<?php echo (int) $UNTRUSTED['department']; ?>'); openDHTMLlayer = 0; stillopen = 0;
	var u4_<?php echo $department; ?> = WEBPATH + 'image.php?' + 'what=changestat' + '&towhat=stopped' + '&cslhVISITOR=' + '<?php echo addslashes($identity['SESSIONID']); ?>' + '<?php echo addslashes($querystringadd); ?>';
    popcontrol_<?php echo $department; ?>3.src = u4_<?php echo $department; ?>;	 
}

function moveDHTML_<?php echo (int) $UNTRUSTED['department']; ?>(){ 
  if(stillopen==1){
   if(navigator.appName.indexOf("Netscape") != -1){ myWidth = window.pageXOffset; myHeight = window.pageYOffset } else { myWidth = document.body.scrollLeft; myHeight = document.body.scrollTop; }
   slidingDiv = document.getElementById('layerinvite_<?php echo (int) $UNTRUSTED['department']; ?>');
   gox = parseInt(slidingDiv.style.left); goy = parseInt(slidingDiv.style.top);
   <?php $floatxy = explode('|', $CSLH_Config['floatxy']); $floatx = isset($floatxy[0]) ? $floatxy[0] : '200'; $floaty = isset($floatxy[1]) ? $floatxy[1] : '160'; if ($floatx === '') $floatx = '200'; if ($floaty === '') $floaty = '160'; ?>
   if(gox < myWidth+<?php echo (int) $floatx; ?>){ gox++; } if(gox > myWidth+<?php echo (int) $floatx; ?>){ gox--; }         
   if(goy < myHeight+<?php echo (int) $floaty; ?>){ goy++; } if(goy > myHeight+<?php echo (int) $floaty; ?>){ goy--; }  
   if(gox < myWidth+<?php echo (int) $floatx; ?>){ gox++; } if(gox > myWidth+<?php echo (int) $floatx; ?>){ gox--; }   
   if(goy < myHeight+<?php echo (int) $floaty; ?>){ goy++; } if(goy > myHeight+<?php echo (int) $floaty; ?>){ goy--; } 
   if(gox < myWidth+<?php echo (int) $floatx; ?>){ gox++; } if(gox > myWidth+<?php echo (int) $floatx; ?>){ gox--; }   
   if(goy < myHeight+<?php echo (int) $floaty; ?>){ goy++; } if(goy > myHeight+<?php echo (int) $floaty; ?>){ goy--; }  
   slidingDiv.style.left = gox + "px"; slidingDiv.style.top = goy + "px";
   if(goy!= parseInt(myWidth+<?php echo (int) $floatx; ?>) ) setTimeout('moveDHTML_<?php echo (int) $UNTRUSTED['department']; ?>()', 7);   
   else setTimeout('moveDHTML_<?php echo (int) $UNTRUSTED['department']; ?>()', 9000);      
  }
}

function openWantsToChat_<?php echo (int) $UNTRUSTED['department']; ?>(){  
   locationvar = '' + <?php echo $parentdot; ?>document.location;
   locationvar = locationvar.replace(new RegExp("[^A-Za-z0-9_)\+\^{}~( ',\.\&\%=/\\?#:-]","g"),""); locationvar = locationvar.replace(new RegExp("=[a-z0-9]{32}","g"),"x=1");
   locationvar = locationvar.replace(new RegExp("[\.]","g"),"--dot--");   locationvar = locationvar.replace(new RegExp("http://","g"),""); locationvar = locationvar.replace(new RegExp("https://","g"),""); locationvar = locationvar.substr(0,250);
   var_title = '' + <?php echo $parentdot; ?>document.title; var_title = var_title.replace(new RegExp("[^A-Za-z0-9_)\+\^{}~( ',\.\&\%=/\\?#:-]","g"),""); var_title = var_title.substr(0,100);
   var_referrer = '' + <?php echo $parentdot; ?>document.referrer; var_referrer = var_referrer.replace(new RegExp("[^A-Za-z0-9_)\+\^{}~( ',\.\&\%=/\\?#:-]","g"),""); var_referrer = var_referrer.replace(new RegExp("=[a-z0-9]{32}","g"),"x=1"); var_referrer = var_referrer.replace(new RegExp("[\.]","g"),"--dot--");  var_referrer = var_referrer.replace(new RegExp("http://","g"),""); var_referrer = var_referrer.replace(new RegExp("https://","g"),""); var_referrer = var_referrer.substr(0,250);
   <?php if (!empty($UNTRUSTED['filter'])) { ?> var locationvar_array=locationvar.split("?"); locationvar = locationvar_array[0]; var var_referrer_array=var_referrer.split("?"); var_referrer = var_referrer_array[0]; var_title = ""; <?php } ?>   
  var u_<?php echo $department; ?> = WEBPATH + 'image.php?' + 'what=browse' + '&page=' + escape(locationvar) + '&title=' + escape(var_title) + '&referer=' + escape(var_referrer) + '&pageid=' + csID_<?php echo $department; ?> + '&department=' + <?php echo (int) $UNTRUSTED['department']; ?> + '&cslhVISITOR=' + '<?php echo addslashes($identity['SESSIONID']); ?>' + '<?php echo addslashes($querystringadd); ?>';
  cscontrol_<?php echo $department; ?>.src = u_<?php echo $department; ?>;  
  window.open(WEBPATH + 'livehelp.php?what=chatinsession&department=<?php echo (int) $UNTRUSTED['department']; ?>&website=<?php echo (int) $UNTRUSTED['website']; ?>&cslhVISITOR=<?php echo addslashes($identity['SESSIONID']); ?><?php echo addslashes($querystringadd); ?>', 'chat54050872', 'width=<?php echo $winwidth; ?>,height=<?php echo $winheight; ?>,menubar=no,scrollbars=1,resizable=1');
}

<?php
if (!isset($UNTRUSTED['what'])) { $UNTRUSTED['what'] = ''; }
$noonehome = true;
$cutoff_ymdhis = (string) gmdate('YmdHis', time() - 20 * 60);
if ((int) $UNTRUSTED['department'] !== 0) {
    $onlineRow = $mydatabase->fetchRow(
        "SELECT 1 FROM {$prefix}sessions s INNER JOIN {$prefix}actor_channel_roles r ON r.actor_id = s.actor_id AND r.is_deleted = 0 INNER JOIN {$prefix}channels c ON c.channel_id = r.channel_id AND c.is_deleted = 0 WHERE s.is_active = 1 AND s.is_expired = 0 AND s.last_seen_ymdhis >= :cutoff AND r.role_key IN ('captain','monitor','administrator') AND c.department_id = :dept LIMIT 1",
        array('cutoff' => $cutoff_ymdhis, 'dept' => (int) $UNTRUSTED['department'])
    );
    if ($onlineRow !== null) { $noonehome = false; }
} else {
    $onlineRow = $mydatabase->fetchRow(
        "SELECT 1 FROM {$prefix}sessions s INNER JOIN {$prefix}actor_channel_roles r ON r.actor_id = s.actor_id AND r.is_deleted = 0 INNER JOIN {$prefix}channels c ON c.channel_id = r.channel_id AND c.is_deleted = 0 WHERE s.is_active = 1 AND s.is_expired = 0 AND s.last_seen_ymdhis >= :cutoff AND r.role_key IN ('captain','monitor','administrator') LIMIT 1",
        array('cutoff' => $cutoff_ymdhis)
    );
    if ($onlineRow !== null) { $noonehome = false; }
}
if (empty($UNTRUSTED['force'])) { $urlreplace = 'javascript:openLiveHelp(' . (int) $UNTRUSTED['department'] . ')'; $target = ''; } else { $urlreplace = $WEBPATH . 'livehelp.php?department=' . (int) $UNTRUSTED['department'] . '&website=' . (int) $UNTRUSTED['website'] . '&resizewidth=500&resizeheight=350'; $target = ' target=_blank '; }
?>

   locationvar = '' + <?php echo $parentdot; ?>document.location;
   locationvar = locationvar.replace(new RegExp("[^A-Za-z0-9_)\+\^{}~( ',\.\&\%=/\\?#:-]","g"),""); locationvar = locationvar.replace(new RegExp("=[a-z0-9]{32}","g"),"x=1"); locationvar = locationvar.replace(new RegExp("[\.]","g"),"--dot--");   locationvar = locationvar.replace(new RegExp("http://","g"),""); locationvar = locationvar.replace(new RegExp("https://","g"),"");   locationvar = locationvar.substr(0,250);
   var_title = '' + <?php echo $parentdot; ?>document.title;   var_title = var_title.replace(new RegExp("[^A-Za-z0-9_)\+\^{}~( ',\.\&\%=/\\?#:-]","g"),""); var_title = var_title.substr(0,100);
   var_referrer = '' + <?php echo $parentdot; ?>document.referrer; var_referrer = var_referrer.replace(new RegExp("[^A-Za-z0-9_)\+\^{}~( ',\.\&\%=/\\?#:-]","g"),""); var_referrer = var_referrer.replace(new RegExp("=[a-z0-9]{32}","g"),"x=1"); var_referrer = var_referrer.replace(new RegExp("[\.]","g"),"--dot--");  var_referrer = var_referrer.replace(new RegExp("http://","g"),""); var_referrer = var_referrer.replace(new RegExp("https://","g"),"");         var_referrer = var_referrer.substr(0,250);
   <?php if (!empty($UNTRUSTED['filter'])) { ?> var locationvar_array=locationvar.split("?"); locationvar = locationvar_array[0]; var var_referrer_array=var_referrer.split("?"); var_referrer = var_referrer_array[0]; var_title = ""; <?php } ?>   

<?php if (empty($UNTRUSTED['what'])) { $UNTRUSTED['what'] = 'nada'; } if (empty($leaveamessage)) { $leaveamessage = 'YES'; } ?>

	var urltohelpimage_<?php echo $department; ?> = WEBPATH + 'image.php?what=getstate&department=<?php echo (int) $UNTRUSTED['department']; ?>&nowis=<?php echo gmdate('YmdHis'); ?>&cslhVISITOR=<?php echo addslashes($identity['SESSIONID']); ?>' + 
					'&page=' + escape(locationvar) + '&referer=' + escape(var_referrer) + '&title=' + escape(var_title) + 
<?php if ($UNTRUSTED['what'] === 'hidden') { print "					'&hide=Y' + \n"; } ?>
					'&leaveamessage=' + '<?php echo addslashes($leaveamessage); ?>' + '<?php echo addslashes($querystringadd); ?>';

  var urltocslhimage_<?php echo $department; ?> = WEBPATH + 'image.php?what=getcredit&department=<?php echo (int) $UNTRUSTED['department']; ?>&nowis=<?php echo gmdate('YmdHis'); ?>&cslhVISITOR=<?php echo addslashes($identity['SESSIONID']); ?>' + 
					'&xy=' + '<?php echo addslashes(substr($creditline, 0, 1)); ?>' + '&page=' + escape(locationvar) + '&referer=' + escape(var_referrer) + '&title=' + escape(var_title) + 
<?php if ($UNTRUSTED['what'] === 'hidden') { print "					'&hide=Y' + \n"; } ?>
					'&leaveamessage=' + '<?php echo addslashes($leaveamessage); ?>' + '<?php echo addslashes($querystringadd); ?>';
<?php
if (empty($UNTRUSTED['dynamic'])) {
    if (!empty($UNTRUSTED['eo'])) {
?>    document.write('<a name="chatRef" href="<?php echo addslashes($urlreplace); ?>" <?php echo $target; ?> onclick="javascript:csTimeout_<?php echo $department; ?>=0;"><img name="csIcon" src="' + urltohelpimage_<?php echo $department; ?> + '" alt="Live Help" border="0"></a>');
  <?php } else { ?>
  	<?php if ($usetable === 'Y') { ?>    document.write('<table border="0" cellspacing="0" cellpadding="0"><tr><td align="center" valign="top">');   <?php } ?>
    document.write('<a name="chatRef" href="<?php echo addslashes($urlreplace); ?>" <?php echo $target; ?> onclick="javascript:csTimeout_<?php echo $department; ?>=0;"><img name="csIcon" src="' + urltohelpimage_<?php echo $department; ?> + '" alt="Live Help" border="0"></a>');
  	<?php if ($usetable === 'Y') { ?>    document.write('</td></tr><tr><td align="center" valign="top">');   <?php } ?>
   document.write('<a href="https://lupopedia.com/?utm_source=poweredby&utm_campaign=poweredby" title="Crafty Syntax" target="_blank" rel="noopener"><img src='+ urltocslhimage_<?php echo $department; ?> +' border=0 style="margin-top:4px;"></a>');
   <?php if ($usetable === 'Y') { ?>    document.write('</td></tr></table>');   <?php } ?>    
  <?php }
} ?>

if (ismac > -1) {
	randu=Math.round(Math.random()*9999);
  document.write('<div id="imagesfordumbmac_<?php echo $department; ?>" style="position:absolute; display:none; visibility:hidden;">');
  document.write('<img id="imageformac_<?php echo $department; ?>" name="imageformac_<?php echo $department; ?>" src="' + WEBPATH + 'lupo-images/blank.gif" border="0"><img id="imageformac_<?php echo $department; ?>_one" name="imageformac_<?php echo $department; ?>_one" src="' + WEBPATH + 'lupo-images/blank.gif" border="0"><img id="imageformac_<?php echo $department; ?>_ten" name="imageformac_<?php echo $department; ?>_ten" src="' + WEBPATH + 'lupo-images/blank.gif" border="0"><img id="imageformac_<?php echo $department; ?>_hun" name="imageformac_<?php echo $department; ?>_hun" src="' + WEBPATH + 'lupo-images/blank.gif" border="0">');
  document.write('</div>');
}
  randu=Math.round(Math.random()*777);
  document.write('<div id="layerinvite_<?php echo $department; ?>" style="position:absolute; z-index:99992; visibility:hidden; display:none; top:-400px; left:-400px; width:400px; height:400px;"></div>');

<?php if ($noonehome) { ?>   setTimeout('csgetimage_<?php echo $department; ?>()', 4000); 	<?php } else { ?>   setTimeout('csrepeat_<?php echo $department; ?>()', 8000); <?php } ?>
setTimeout('csrepeat_<?php echo $department; ?>()', 8000);
 setTimeout('wherecslhisdue_<?php echo $department; ?>()', 2000);
