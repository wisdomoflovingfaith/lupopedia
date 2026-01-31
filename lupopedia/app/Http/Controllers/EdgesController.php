<?php
/**
 * Edges Controller
 *
 * Handles edge display and interface.
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. EdgesController.php cannot be called directly.");
}

class EdgesController {
    /**
     * Display a specific edge
     *
     * @param int $edge_id Edge ID to display
     * @return void
     */
    public function show($edge_id) {
        // For now, no database logic.
        // Just pass the edge_id to the view.
        $edge_id = intval($edge_id);

        // Define view path
        $view_path = LUPOPEDIA_PATH . '/lupopedia/app/views/edges/show.php';

        // Capture view output
        if (file_exists($view_path)) {
            ob_start();
            include $view_path;
            $page_body = ob_get_clean();
        } else {
            $page_body = '<h2>Edge ' . htmlspecialchars($edge_id) . '</h2><p>edges go here</p>';
        }

        // Load render_main_layout function
        $content_renderer = LUPOPEDIA_PATH . '/lupo-includes/modules/content/renderers/content-renderer.php';
        if (file_exists($content_renderer)) {
            require_once $content_renderer;
        }

        // Render with layout
        $context = [
            'page_body' => $page_body,
            'page_title' => 'Edge ' . $edge_id
        ];

        if (function_exists('render_main_layout')) {
            echo render_main_layout($context);
        } else {
            echo $page_body;
        }
    }
}
