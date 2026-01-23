<?php

/**
 * DIALOG MANAGER — Central Dispatcher for Lupopedia
 *
 * ROLE:
 *   - Accept incoming user/agent messages
 *   - Insert into dialog_messages table
 *   - Invoke HERMES for routing
 *   - Invoke IRIS for LLM reasoning
 *   - Store memory via WOLFMIND
 *   - Return final response packet
 *
 * This is the beating heart of the OS.
 */

require_once __DIR__ . '/class-hermes.php';
require_once __DIR__ . '/class-caduceus.php';
require_once __DIR__ . '/class-wolfmind.php';
require_once __DIR__ . '/class-iris.php';

class DialogManager
{
    protected $db;
    protected $pdo;
    protected $hermes;
    protected $wolfmind;
    protected $iris;

    public function __construct($db)
    {
        $this->db       = $db;
        $this->pdo      = $db->getPdo();
        $this->hermes   = new HERMES($db);
        $this->wolfmind = new WOLFMIND($db);
        $this->iris     = new IRIS($db);
    }

    /**
     * Main entry point for all messages.
     *
     * @param array $packet
     *   [
     *     'actor_id' => int,
     *     'to_actor' => int|null,
     *     'content' => string,
     *     'mood_rgb' => 'RRGGBB',
     *     'thread_id' => int|null
     *   ]
     *
     * @return array  Final response packet
     */
    public function handleMessage(array $packet): array
    {
        // ---------------------------------------------------------
        // 1. Insert incoming message into dialog_messages
        // ---------------------------------------------------------
        $msgId = $this->insertDialogMessage($packet);

        // Attach the new message ID to the packet
        $packet['dialog_message_id'] = $msgId;

        // ---------------------------------------------------------
        // 2. Route using HERMES
        // ---------------------------------------------------------
        $targetAgent = $this->hermes->route($packet);

        // ---------------------------------------------------------
        // 3. Invoke IRIS (LLM faucet)
        // ---------------------------------------------------------
        $responseText = $this->iris->invokeAgent($targetAgent, $packet);

        // ---------------------------------------------------------
        // 4. Store memory (WOLFMIND)
        // ---------------------------------------------------------
        $this->wolfmind->storeMemoryEvent(
            $targetAgent,
            'dialog_response',
            $responseText,
            ['source_message_id' => $msgId]
        );

        // ---------------------------------------------------------
        // 5. Insert response into dialog_messages
        // ---------------------------------------------------------
        $responseId = $this->insertDialogMessage([
            'actor_id' => $targetAgent,
            'to_actor' => $packet['actor_id'],
            'content'  => $responseText,
            'mood_rgb' => '88FF88', // default positive
            'thread_id'=> $packet['thread_id']
        ]);

        // ---------------------------------------------------------
        // 6. Return final response packet
        // ---------------------------------------------------------
        return [
            'response_message_id' => $responseId,
            'response_text'       => $responseText,
            'from_actor'          => $targetAgent,
            'to_actor'            => $packet['actor_id']
        ];
    }

    /**
     * Insert a dialog message into lupo_dialog_messages table.
     */
    protected function insertDialogMessage(array $packet): int
    {
        $now = gmdate('YmdHis');

        $data = [
            'from_actor_id'   => $packet['actor_id'],
            'to_actor_id'     => $packet['to_actor'] ?? null,
            'message_text'    => $packet['content'],
            'dialog_thread_id'=> $packet['thread_id'] ?? null,
            'mood_rgb'        => $packet['mood_rgb'] ?? '666666',
            'message_type'    => $packet['message_type'] ?? 'text',
            'created_ymdhis'  => $now,
            'updated_ymdhis'  => $now,
            'is_deleted'      => 0
        ];

        // Add metadata_json if provided
        if (isset($packet['metadata_json'])) {
            $data['metadata_json'] = is_string($packet['metadata_json']) 
                ? $packet['metadata_json'] 
                : json_encode($packet['metadata_json']);
        }

        // Add directive_dialog_id to metadata if provided
        if (isset($packet['directive_dialog_id'])) {
            $metadata = isset($data['metadata_json']) 
                ? json_decode($data['metadata_json'], true) 
                : [];
            $metadata['directive_dialog_id'] = $packet['directive_dialog_id'];
            $data['metadata_json'] = json_encode($metadata);
        }

        return (int)$this->db->insert('lupo_dialog_doctrine', $data);
    }
}

?>