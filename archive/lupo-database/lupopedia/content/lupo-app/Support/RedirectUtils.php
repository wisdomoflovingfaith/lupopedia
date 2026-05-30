<?php

namespace App\Support;

/**
 * Safe redirect with LUPOPEDIA_PUBLIC_PATH and headers-sent fallback.
 * No auth/session; pure HTTP redirect.
 */
class RedirectUtils
{
    /**
     * Redirect to URL. Prepends LUPOPEDIA_PUBLIC_PATH to relative URLs; sanitizes; uses header() or meta refresh + JS + link.
     *
     * @param string $url Relative or absolute URL
     * @param int $delay Seconds for meta refresh (default 3)
     * @param string|null $message Message to display
     * @return void Exits
     */
    public static function safeRedirect(string $url, int $delay = 3, ?string $message = null): void
    {
        if (strpos($url, 'http') !== 0) {
            if (defined('LUPOPEDIA_PUBLIC_PATH') && LUPOPEDIA_PUBLIC_PATH !== '/') {
                $hasPublicPath = (strpos($url, LUPOPEDIA_PUBLIC_PATH) === 0);
                if (!$hasPublicPath) {
                    $url = (strpos($url, '/') === 0) ? (LUPOPEDIA_PUBLIC_PATH . $url) : (LUPOPEDIA_PUBLIC_PATH . '/' . ltrim($url, '/'));
                }
            } else {
                if (strpos($url, '/') !== 0) {
                    $url = '/' . ltrim($url, '/');
                }
            }
        }
        $url = filter_var($url, FILTER_SANITIZE_URL);
        if (empty($url) || (strpos($url, 'http') === 0 && strpos($url, $_SERVER['HTTP_HOST'] ?? '') === false)) {
            $url = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH . '/' : '/';
        }
        if (!headers_sent()) {
            header('Location: ' . $url);
            exit;
        }
        $msg = $message ?: 'Redirecting...';
        $escUrl = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
        $escMsg = htmlspecialchars($msg, ENT_QUOTES, 'UTF-8');
        $delay = (int) $delay;
        echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta http-equiv="refresh" content="' . $delay . ';url=' . $escUrl . '"><title>' . $escMsg . '</title></head><body><p>' . $escMsg . '</p><a href="' . $escUrl . '">Click here if not redirected</a><script>setTimeout(function(){window.location.href=' . json_encode($url, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . ';},' . ($delay * 1000) . ');</script></body></html>';
        exit;
    }
}
