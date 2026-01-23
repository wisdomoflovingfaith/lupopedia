<?php
/**
 * Generates random salts for WordPress-like security keys and prints them to the screen.
 */

// Define the character set used for salts (matching WordPress standard)
$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|';
$chars_length = strlen($chars);

// List of keys
$keys = [
    'AUTH_KEY',
    'SECURE_AUTH_KEY',
    'LOGGED_IN_KEY',
    'NONCE_KEY',
    'AUTH_SALT',
    'SECURE_AUTH_SALT',
    'LOGGED_IN_SALT',
    'NONCE_SALT',
];

// Generate and print each define
foreach ($keys as $key) {
    $salt = '';
    for ($i = 0; $i < 64; $i++) {
        $salt .= $chars[random_int(0, $chars_length - 1)];
    }
    $padding = 17 - strlen($key);
    printf("define('%s',%s'%s');\n", $key, str_repeat(' ', $padding), $salt);
}
?>