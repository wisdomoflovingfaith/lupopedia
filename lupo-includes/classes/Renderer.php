<?php
/**
 * Final output for agents.php: JSON or Markdown with safe headers.
 *
 * @package Lupopedia
 */

class Renderer
{
    /**
     * Output Markdown content with optional response headers.
     *
     * @param string $content
     * @param int    $actor_id
     * @param string $cf_ray
     */
    public static function markdown($content, $actor_id = 0, $cf_ray = '')
    {
        if (!headers_sent()) {
            header('Content-Type: text/markdown; charset=utf-8');
            header('X-Lupo-Actor-Id: ' . (int) $actor_id);
            if ($cf_ray !== '') {
                header('X-Cf-Ray: ' . $cf_ray);
            }
        }
        echo $content;
    }

    /**
     * Output JSON response.
     *
     * @param array $data
     * @param int   $status 200, 400, etc.
     */
    public static function json($data, $status = 200)
    {
        if (!headers_sent()) {
            header('Content-Type: application/json');
            if ($status !== 200) {
                header('HTTP/1.1 ' . (int) $status . ' ' . ((int) $status === 400 ? 'Bad Request' : 'OK'));
            }
        }
        echo json_encode($data);
    }
}
