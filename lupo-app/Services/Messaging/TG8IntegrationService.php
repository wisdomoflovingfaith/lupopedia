<?php
/**
 * TG8IntegrationService
 *
 * Best-effort integration layer:
 * message text -> MessageEdgeParser -> EdgeService::createEdge
 *
 * Guarantees:
 * - never throws for edge creation failures
 * - deterministic processing order (parser output order)
 * - no direct SQL writes in this layer
 */
class TG8IntegrationService
{
    private $parser;
    private $edgeService;
    private $logger;

    public function __construct($parser = null, $edgeService = null, $logger = null)
    {
        if ($parser === null) {
            if (!class_exists('MessageEdgeParser')) {
                require_once __DIR__ . DIRECTORY_SEPARATOR . 'MessageEdgeParser.php';
            }
            $parser = new MessageEdgeParser();
        }
        if ($edgeService === null) {
            if (!class_exists('EdgeService')) {
                require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'ContextGraph' . DIRECTORY_SEPARATOR . 'EdgeService.php';
            }
            $edgeService = new EdgeService();
        }

        $this->parser = $parser;
        $this->edgeService = $edgeService;
        $this->logger = $logger;
    }

    public function processMessage($messageText, $messageId)
    {
        $result = array(
            'message_id' => (string) $messageId,
            'parsed_count' => 0,
            'created_count' => 0,
            'failed_count' => 0,
            'errors' => array()
        );

        $edges = $this->parser->parse($messageText, 'message', $messageId);
        if (!is_array($edges)) {
            $result['errors'][] = 'Parser returned non-array edge payload.';
            $result['failed_count'] = 1;
            $this->log('TG8 parser output invalid for message_id=' . (string) $messageId);
            return $result;
        }

        $result['parsed_count'] = count($edges);

        foreach ($edges as $index => $edge) {
            if (!$this->isValidEdgeDefinition($edge)) {
                $result['failed_count']++;
                $result['errors'][] = 'Invalid edge definition at index ' . (string) $index . '.';
                $this->log(
                    'TG8 invalid edge definition at index=' . (string) $index .
                    ' message_id=' . (string) $messageId
                );
                continue;
            }

            try {
                $this->edgeService->createEdge(
                    'message',
                    $messageId,
                    $edge['target_type'],
                    $edge['target_id'],
                    $edge['edge_type'],
                    $edge['direction'],
                    $edge['metadata_json']
                );
                $result['created_count']++;
            } catch (Exception $exception) {
                $result['failed_count']++;
                $result['errors'][] = $this->buildEdgeError($index, $edge, $exception->getMessage());
                $this->log(
                    'TG8 edge create failed at index=' . (string) $index .
                    ' message_id=' . (string) $messageId .
                    ' error=' . $exception->getMessage()
                );
            }
        }

        return $result;
    }

    private function isValidEdgeDefinition($edge)
    {
        if (!is_array($edge)) {
            return false;
        }

        $required = array('target_type', 'target_id', 'edge_type', 'direction', 'metadata_json');
        foreach ($required as $key) {
            if (!array_key_exists($key, $edge)) {
                return false;
            }
            if (!is_string($edge[$key])) {
                return false;
            }
            if ($key !== 'metadata_json' && trim($edge[$key]) === '') {
                return false;
            }
        }

        return true;
    }

    private function buildEdgeError($index, $edge, $message)
    {
        $targetType = isset($edge['target_type']) ? (string) $edge['target_type'] : '';
        $targetId = isset($edge['target_id']) ? (string) $edge['target_id'] : '';
        $edgeType = isset($edge['edge_type']) ? (string) $edge['edge_type'] : '';

        return 'edge_index=' . (string) $index .
            ', target_type=' . $targetType .
            ', target_id=' . $targetId .
            ', edge_type=' . $edgeType .
            ', error=' . (string) $message;
    }

    private function log($message)
    {
        if ($this->logger && is_callable($this->logger)) {
            call_user_func($this->logger, $message);
            return;
        }
        error_log($message);
    }
}
