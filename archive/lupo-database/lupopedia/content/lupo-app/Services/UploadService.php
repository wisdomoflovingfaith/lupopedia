<?php

namespace App\Services;

/**
 * Upload service — wraps LupoUploadHandler (singleton). Factory + upload facade.
 */
class UploadService
{
    /** @var \LupoUploadHandler|null */
    private static $handler = null;

    /**
     * Get the upload handler instance (loads upload-handler.php if needed).
     *
     * @return \LupoUploadHandler
     */
    public function getHandler(): \LupoUploadHandler
    {
        if (self::$handler === null) {
            $path = defined('LUPOPEDIA_PATH') ? LUPOPEDIA_PATH : (defined('ABSPATH') ? rtrim(ABSPATH, '/') : dirname(__DIR__, 2));
            $file = $path . '/lupo-includes/functions/upload-handler.php';
            if (file_exists($file)) {
                require_once $file;
            }
            if (!class_exists('LupoUploadHandler')) {
                throw new \RuntimeException('LupoUploadHandler not found. Ensure lupo-includes/functions/upload-handler.php is loadable.');
            }
            self::$handler = new \LupoUploadHandler();
        }
        return self::$handler;
    }

    /**
     * Upload a file (delegates to handler).
     *
     * @param array $file $_FILES element
     * @param string $entityType 'agent', 'channel', or 'content'
     * @param int $entityId
     * @param string $fileType
     * @return array
     */
    public function upload(array $file, string $entityType, int $entityId, string $fileType = 'document'): array
    {
        return $this->getHandler()->upload($file, $entityType, $entityId, $fileType);
    }
}
