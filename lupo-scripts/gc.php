#!/usr/bin/env php
<?php
//===========================================================================
//* --    ~~                LUPOPEDIA Garbage Collection                ~~ -- *
//===========================================================================
//           URL:   https://lupopedia.com/    EMAIL: support@lupopedia.com
//         Copyright (C) 2003-2026 Eric Gerdes   (https://lupopedia.com )
//===========================================================================

// This script can be run from CLI or cron
if (php_sapi_name() !== 'cli') {
    die("This script can only be run from the command line\n");
}

require_once(__DIR__ . '/../lupo-config.php');
require_once(__DIR__ . '/../lupo-includes/classes/GarbageCollector.php');

echo "[" . date('Y-m-d H:i:s') . "] Starting Garbage Collection...\n";

$gc = new GarbageCollector();
$gc->run();

echo "[" . date('Y-m-d H:i:s') . "] Garbage Collection completed\n";
