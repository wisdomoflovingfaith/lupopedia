<?php
/**
 * DEPRECATED: Use flare-header.php instead.
 */
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Optional: Redirect or just print message
echo json_encode(array(
    'error' => 'The FLIP API is deprecated. Please use flare-header.php for the new 3-part FLARE protocol headers.',
    'status' => 'deprecated',
    'successor' => 'flare-header.php'
));
exit;
