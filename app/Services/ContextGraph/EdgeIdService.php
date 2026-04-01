<?php
/**
 * Deterministic Edge ID Service
 *
 * Generates deterministic BIGINT edge IDs for context graph edges.
 * Pure function with no side effects, no database access, no hidden state.
 */

class EdgeIdService
{
    public function generateId($sourceType, $sourceId, $targetType, $targetId, $edgeType, $direction)
    {
        $canonicalInput = $this->canonicalizeEdgeInput($sourceType, $sourceId, $targetType, $targetId, $edgeType, $direction);
        $hash = $this->generateDeterministicHash($canonicalInput);
        return $this->hashToBigInt($hash);
    }

    private function canonicalizeEdgeInput($sourceType, $sourceId, $targetType, $targetId, $edgeType, $direction)
    {
        $canonicalSourceType = strtolower(trim((string) $sourceType));
        $canonicalTargetType = strtolower(trim((string) $targetType));
        $canonicalEdgeType = strtolower(trim((string) $edgeType));
        $canonicalDirection = strtolower(trim((string) $direction));
        $canonicalSourceId = (int) $sourceId;
        $canonicalTargetId = (int) $targetId;

        if (!in_array($canonicalDirection, array('fwd', 'rev', 'both'))) {
            $canonicalDirection = 'fwd';
        }

        if ($canonicalEdgeType === 'contradiction') {
            if ($canonicalSourceId > $canonicalTargetId) {
                $tempId = $canonicalSourceId;
                $tempType = $canonicalSourceType;
                $canonicalSourceId = $canonicalTargetId;
                $canonicalSourceType = $canonicalTargetType;
                $canonicalTargetId = $tempId;
                $canonicalTargetType = $tempType;
            }
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

    private function generateDeterministicHash($canonicalInput)
    {
        $serialized = sprintf(
            '%s|%d|%s|%d|%s|%s',
            $canonicalInput['source_type'],
            $canonicalInput['source_id'],
            $canonicalInput['target_type'],
            $canonicalInput['target_id'],
            $canonicalInput['edge_type'],
            $canonicalInput['direction']
        );

        return hash('sha256', $serialized, true);
    }

    private function hashToBigInt($hash)
    {
        $bytes = substr($hash, 0, 8);
        $unsigned = 0;
        $index = 0;

        for ($index = 0; $index < 8; $index++) {
            $unsigned = ($unsigned << 8) | ord($bytes[$index]);
        }

        if ($unsigned < 0) {
            $unsigned = abs($unsigned);
        }

        return sprintf('%u', $unsigned);
    }
}