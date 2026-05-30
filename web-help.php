<?php
/**
 * Web help endpoint -- serves docs/HELP.md as the help hub.
 * Can be used when the router maps /help to this file (e.g. include when slug === 'help').
 * Otherwise the existing help module (lupo-includes/modules/help/) handles /help.
 *
 * @package Lupopedia
 * @version 4.0.61
 */
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}
$help_file = ABSPATH . 'docs/HELP.md';
$topic = isset($_GET['topic']) ? basename(trim($_GET['topic'])) : '';
if ($topic !== '') {
    $topic_file = ABSPATH . 'docs/' . $topic . '.md';
    if (file_exists($topic_file) && is_readable($topic_file)) {
        $help_file = $topic_file;
    }
}
if (file_exists($help_file) && is_readable($help_file)) {
    header('Content-Type: text/markdown; charset=utf-8');
    header('X-Lupopedia-Help: 1');
    readfile($help_file);
} else {
    header('HTTP/1.0 404 Not Found');
    header('Content-Type: text/plain');
    echo 'Help document not found.';
}
