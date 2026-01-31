<?php
/**
 * Channels Controller
 *
 * Handles channel display and operator interface.
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. ChannelsController.php cannot be called directly.");
}

class ChannelsController {
    /**
     * Display a specific channel
     *
     * @param int $channel_id Channel ID to display
     * @return void
     */
    public function show($channel_id) {
        // For now, no database logic.
        // Just pass the channel_id to the view.
        $channel_id = intval($channel_id);

        // Define view path
        $view_path = LUPOPEDIA_PATH . '/lupopedia/app/views/channels/show.php';

        // Capture view output
        if (file_exists($view_path)) {
            ob_start();
            include $view_path;
            $page_body = ob_get_clean();
        } else {
            $page_body = '<h2>Channel ' . htmlspecialchars($channel_id) . '</h2><p>channel interface goes here</p>';
        }

        // Load render_main_layout function
        $content_renderer = LUPOPEDIA_PATH . '/lupo-includes/modules/content/renderers/content-renderer.php';
        if (file_exists($content_renderer)) {
            require_once $content_renderer;
        }

        // Render with layout
        $context = [
            'page_body' => $page_body,
            'page_title' => 'Channel ' . $channel_id
        ];

        if (function_exists('render_main_layout')) {
            echo render_main_layout($context);
        } else {
            echo $page_body;
        }
    }
}
