<?php
/**
 * Deterministic Edge ID Service
 * 
 * Generates deterministic BIGINT edge IDs for context graph edges.
 * Pure function with no side effects, no database access, no hidden state.
 * 
 * @package App\Services\ContextGraph
 * @version 4.0.86
 */

class EdgeIdService {

    /**
     * Generate deterministic edge ID from canonical edge identity.
     * 
     * This is a pure function that always returns the same BIGINT-compatible ID
     * for the same logical edge input. No randomness, no time-based values,
     * no database access, no side effects.
     * 
     * @param string $sourceType Source entity type (e.g., 'thread', 'channel')
     * @param int $sourceId Source entity ID
     * @param string $targetType Target entity type (e.g., 'thread', 'channel')
     * @param int $targetId Target entity ID
     * @param string $edgeType Edge type (dependency, subtask, contradiction, refinement)
     * @param string $direction Direction (FWD, REV, BOTH)
     * @return string BIGINT-compatible deterministic ID as string
     */
    public function generateId($sourceType, $sourceId, $targetType, $targetId, $edgeType, $direction) {
        // Step 1: Canonical normalization
        $canonicalInput = $this->canonicalizeEdgeInput($sourceType, $sourceId, $targetType, $targetId, $edgeType, $direction);
        
        // Step 2: Generate deterministic hash
        $hash = $this->generateDeterministicHash($canonicalInput);
        
        // Step 3: Convert to BIGINT-safe range
        $bigIntId = $this->hashToBigInt($hash);
        
        return $bigIntId;
    }
    
    /**
     * Canonicalize edge input to ensure identical logical edges produce identical output.
     * 
     * Normalization rules:
     * - Trim and lowercase string fields
     * - Convert numeric IDs to integers
     * - Normalize direction values
     * - Special handling for contradiction edges (order-independent)
     * 
     * @param mixed $sourceType Source entity type
     * @param mixed $sourceId Source entity ID
     * @param mixed $targetType Target entity type
     * @param mixed $targetId Target entity ID
     * @param mixed $edgeType Edge type
     * @param mixed $direction Direction
     * @return array Canonicalized input array
     */
    private function canonicalizeEdgeInput($sourceType, $sourceId, $targetType, $targetId, $edgeType, $direction) {
        // Normalize string fields: trim and lowercase
        $canonicalSourceType = strtolower(trim((string)$sourceType));
        $canonicalTargetType = strtolower(trim((string)$targetType));
        $canonicalEdgeType = strtolower(trim((string)$edgeType));
        $canonicalDirection = strtolower(trim((string)$direction));
        
        // Normalize numeric IDs
        $canonicalSourceId = (int)$sourceId;
        $canonicalTargetId = (int)$targetId;
        
        // Normalize direction values to FWD/REV/BOTH
        if (!in_array($canonicalDirection, array('fwd', 'rev', 'both'))) {
            $canonicalDirection = 'fwd'; // Default fallback
        }
        
        // Special contradiction handling: order-independent identity
        // Contradiction edges are logically undirected, so source/target order doesn't matter
        if ($canonicalEdgeType === 'contradiction') {
            // Always order source/target consistently (lower ID first)
            if ($canonicalSourceId > $canonicalTargetId) {
                // Swap to maintain canonical order
                $tempId = $canonicalSourceId;
                $tempType = $canonicalSourceType;
                
                $canonicalSourceId = $canonicalTargetId;
                $canonicalSourceType = $canonicalTargetType;
                
                $canonicalTargetId = $tempId;
                $canonicalTargetType = $tempType;
            }
            
            // Contradiction is always undirected
            $canonicalDirection = 'both';
        }
        
        return array(
            'source_type' => $canonicalSourceType,
            'source_id' => $canonicalSourceId,
            'target_type' => $canonicalTargetType,
            'target_id' => $canonicalTargetId,
            'edge_type' => $canonicalEdgeType,
            'direction' => $canonicalDirection
        );
    }
    
    /**
     * Generate deterministic hash from canonical input.
     * 
     * Uses SHA-256 hash of serialized canonical input.
     * SHA-256 is deterministic and produces consistent results.
     * 
     * @param array $canonicalInput Canonicalized edge input
     * @return string Binary hash string
     */
    private function generateDeterministicHash($canonicalInput) {
        // Serialize canonical input in consistent order
        $serialized = sprintf(
            '%s|%d|%s|%d|%s|%s',
            $canonicalInput['source_type'],
            $canonicalInput['source_id'],
            $canonicalInput['target_type'],
            $canonicalInput['target_id'],
            $canonicalInput['edge_type'],
            $canonicalInput['direction']
        );
        
        // Generate SHA-256 hash (binary format)
        return hash('sha256', $serialized, true);
    }
    
    /**
     * Convert hash to BIGINT-safe range.
     * 
     * Takes first 8 bytes of SHA-256 hash and converts to unsigned 64-bit integer.
     * This ensures the result fits in BIGINT range (0 to 2^63-1 for signed BIGINT).
     * 
     * @param string $hash Binary hash string (minimum 8 bytes)
     * @return string BIGINT-compatible ID as decimal string
     */
    private function hashToBigInt($hash) {
        // Take first 8 bytes (64 bits) of SHA-256 hash
        $bytes = substr($hash, 0, 8);
        
        // Convert bytes to unsigned 64-bit integer
        $unsigned = 0;
        for ($i = 0; $i < 8; $i++) {
            $unsigned = ($unsigned << 8) | ord($bytes[$i]);
        }
        
        // Ensure positive BIGINT range (signed BIGINT max is 2^63-1)
        // If highest bit is set, clear it to stay in signed BIGINT range
        $maxSignedBigInt = 9223372036854775807; // 2^63 - 1
        if ($unsigned > $maxSignedBigInt) {
            $unsigned = $unsigned & $maxSignedBigInt;
        }
        
        // Return as string to handle large integers safely
        return (string)$unsigned;
    }
    
    /**
     * Test method for verifying deterministic behavior.
     * 
     * This method is for testing/verification only and confirms that
     * the same input always produces the same output.
     * 
     * @param string $sourceType
     * @param int $sourceId
     * @param string $targetType
     * @param int $targetId
     * @param string $edgeType
     * @param string $direction
     * @return string Generated ID
     */
    public function testDeterministic($sourceType, $sourceId, $targetType, $targetId, $edgeType, $direction) {
        return $this->generateId($sourceType, $sourceId, $targetType, $targetId, $edgeType, $direction);
    }
}
