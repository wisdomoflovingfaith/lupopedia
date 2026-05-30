<?php
/**
 * Channel66IngestionLogger
 * Append one JSONL record per file to logs/admin/.
 */

class Channel66IngestionLogger
{
    /** @var string */
    private $logDir;

    /** @var string */
    private $logType;

    public function __construct($logDir = null, $logType = 'channel66_header_ingest_p0')
    {
        if ($logDir === null) {
            $this->logDir = defined('LUPOPEDIA_PATH')
                ? LUPOPEDIA_PATH . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'admin'
                : dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'admin';
        } else {
            $this->logDir = $logDir;
        }
        $this->logType = $logType;
    }

    /**
     * @param array $entry Structured data
     */
    public function log($entry)
    {
        if (!is_dir($this->logDir)) {
            @mkdir($this->logDir, 0777, true);
        }

        $logfile = $this->logDir . DIRECTORY_SEPARATOR . date('Y-m-d') . '.jsonl';
        $entry['type'] = $this->logType;
        $entry['timestamp'] = gmdate('c');
        $line = json_encode($entry, JSON_UNESCAPED_SLASHES) . "\n";
        @file_put_contents($logfile, $line, FILE_APPEND | LOCK_EX);
    }
}

