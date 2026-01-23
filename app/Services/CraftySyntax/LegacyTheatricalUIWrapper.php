<?php
//===========================================================================
//* --    ~~                CRAFTY SYNTAX Theatrical UI Wrapper   ~~    -- *
//===========================================================================
//           URL:   https://lupopedia.com/    EMAIL: livehelp@lupopedia.com
//         Copyright (C) 2003-2023 Eric Gerdes   (https://lupopedia.com )
// ----------------------------------------------------------------------------

// NOTICE: This is a LEGACY PRESERVATION wrapper file from Crafty Syntax Live Help
// Migrated to Lupopedia structure under HERITAGE-SAFE MODE
// DO NOT MODIFY - PRESERVE ALL ORIGINAL BEHAVIOR
// Reference: CRAFTY_SYNTAX_UI_THEATRICAL_DOCTRINE.md

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// Theatrical UI Wrapper - LEGACY PRESERVATION
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

/**
 * Legacy Theatrical UI Loader
 * Loads original dynlayer.js and supporting libraries for theatrical UI
 * LEGACY FUNCTION - DO NOT MODIFY
 * Reference: CRAFTY_SYNTAX_UI_THEATRICAL_DOCTRINE.md
 */
function loadLegacyTheatricalUI() {
    // Load original Dan Steinman dynlayer.js
    require_once(__DIR__ . '/js/legacy_dynlayer.js');
    
    // Load supporting libraries
    require_once(__DIR__ . '/js/legacy_xlayer.js');
    require_once(__DIR__ . '/js/legacy_xmouse.js');
    require_once(__DIR__ . '/js/legacy_staticmenu.js');
    
    // Initialize browser detection (from original dynlayer.js)
    echo '<script type="text/javascript">' . "\n";
    echo '// Browser detection from original dynlayer.js' . "\n";
    echo 'var is = new BrowserCheck();' . "\n";
    echo '</script>' . "\n";
}

/**
 * Check if legacy theatrical UI should be loaded
 * LEGACY FUNCTION - DO NOT MODIFY
 */
function shouldUseLegacyTheatricalUI() {
    // Use legacy UI for older browsers or when explicitly requested
    return (!isset($_SERVER['HTTP_USER_AGENT']) || 
            strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false ||
            strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape') !== false);
}

?>
