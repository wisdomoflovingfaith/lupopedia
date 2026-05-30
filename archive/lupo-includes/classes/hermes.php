<?php

/**
 * HERMES — Message Routing & Communication Infrastructure
 *
 * ROLE:
 *   - Receives a message packet from Dialog Manager
 *   - Reads to_actor, actor_id, directive_dialog_id, and mood_vector
 *   - Computes CADUCEUS currents (lightweight signal layer)
 *   - Applies routing bias based on CADUCEUS
 *   - Returns the agent_id for the next hop
 *
 * CADUCEUS:
 *   - NOT an agent
 *   - NOT a subsystem
 *   - NOT a database table
 *   - It is a tiny helper located in:
 *         lupo-includes/classes/caduceus.php
 *   - It converts mood_vector → routing currents
 */

require_once __DIR__ . '/classes/caduceus.php';

class HERMES
{
    protected $db;
    protected $pdo;

    public function __construct($db)
    {
        $this->db  = $db;
        $this->pdo = $db->getPdo();
    }

    /**
     * Main routing entry point.
     *
     * @param array $packet  The message packet from Dialog Manager:
     *                       [
     *                         'actor_id' => int,
     *                         'to_actor' => int,
     *                         'mood_vector' => 'RRGGBB',
     *                         'directive_dialog_id' => int,
     *                         'content' => string
     *                       ]
     *
     * @return int  The agent_id to route to next.
     */
    public function route(array $packet)
    {
        $toActor  = $packet['to_actor'] ?? null;
        $moodVector  = $packet['mood_vector'] ?? '666666'; // neutral fallback
        $dialogId = $packet['directive_dialog_id'] ?? 0;

        // ---------------------------------------------------------
        // CADUCEUS: Compute routing currents from mood_vector
        // ---------------------------------------------------------
        //
        // Caduceus::computeCurrents() returns:
        //   ['left' => float, 'right' => float]
        //
        // left_current  = analytical / structured bias
        // right_current = creative / emotional bias
        //
        $currents = Caduceus::computeCurrents($moodVector);

        // ---------------------------------------------------------
        // BASE ROUTING: If to_actor is explicit, honor it
        // ---------------------------------------------------------
        if (!empty($toActor) && $toActor > 0) {
            return (int)$toActor;
        }

        // ---------------------------------------------------------
        // CADUCEUS BIAS:
        // If no explicit to_actor, use currents to choose a path.
        //
        // left  > right  → analytical agent
        // right > left   → creative/emotional agent
        // ---------------------------------------------------------
        if ($currents['left'] > $currents['right']) {
            return $this->chooseAnalyticalAgent($packet);
        } else {
            return $this->chooseCreativeAgent($packet);
        }
    }

    /**
     * Choose an analytical agent.
     *
     * TODO: Replace with real logic later.
     */
    protected function chooseAnalyticalAgent(array $packet)
    {
        // Example: THOTH (Verifier)
        return 22; // placeholder agent_id
    }

    /**
     * Choose a creative/emotional agent.
     *
     * TODO: Replace with real logic later.
     */
    protected function chooseCreativeAgent(array $packet)
    {
        // Example: LILITH (Creative)
        return 33; // placeholder agent_id
    }
}

?>
