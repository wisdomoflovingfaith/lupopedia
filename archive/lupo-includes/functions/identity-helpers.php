<?php
/**
 * wolfie.header.identity: identity-helpers
 * wolfie.header.placement: /lupo-includes/functions/identity-helpers.php
 * wolfie.header.version: 3.0.0
 * wolfie.header.dialog:
 *   speaker: JETBRAINS
 *   target: @everyone
 *   message: "Added actor identity utilities for anonymous allocation, jsrn assignment, and merge behavior."
 *   mood: "00FF00"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. identity-helpers.php cannot be called directly.");
}

/**
 * Allocate the next available anonymous actor_id in [1000, 9999] (thin wrapper — logic in ActorService).
 *
 * @param PDO $db Database connection (unused when ActorService is available; kept for backward compatibility).
 * @return int|null Allocated actor_id or null if exhausted.
 */
function allocateAnonymousActorId($db) {
    $s = isset($GLOBALS['lupo_actor_service']) ? $GLOBALS['lupo_actor_service'] : null;
    return $s ? $s->allocateAnonymousActorId() : null;
}

/**
 * Get or allocate a JSRN for the given actor (thin wrapper — logic in ActorService).
 *
 * @param PDO $db Database connection (unused when ActorService is available; kept for backward compatibility).
 * @param int $actorId Actor ID to assign a jsrn to.
 * @return int Assigned jsrn.
 */
function getOrAllocateJsrnForActor($db, $actorId) {
    $s = isset($GLOBALS['lupo_actor_service']) ? $GLOBALS['lupo_actor_service'] : null;
    return $s ? $s->getOrAllocateJsrnForActor($actorId) : 0;
}

/**
 * Merge an anonymous actor into a real actor (thin wrapper — logic in ActorService).
 *
 * @param PDO $db Database connection (unused when ActorService is available; kept for backward compatibility).
 * @param int $tempActorId Anonymous actor_id (1000-9999).
 * @param int $realActorId Real actor_id (>= 10000).
 * @return void
 * @throws \Exception On DB error when delegated to ActorService.
 */
function mergeAnonymousActorIntoRealActor($db, $tempActorId, $realActorId) {
    $s = isset($GLOBALS['lupo_actor_service']) ? $GLOBALS['lupo_actor_service'] : null;
    if ($s) {
        $s->mergeAnonymousActorIntoRealActor($tempActorId, $realActorId);
    }
}

?>
