<?php

/**
 * CADUCEUS — Lightweight Mood Signal Helper
 *
 * NOT an agent.
 * NOT a subsystem.
 * NOT a database table.
 *
 * PURPOSE:
 *   Convert mood_vector (RRGGBB) into two routing currents:
 *
 *     left_current  = analytical / structured bias
 *     right_current = creative / emotional bias
 *
 * These currents are used by HERMES to bias routing decisions.
 *
 * DOCTRINE:
 *   R = strife / intensity
 *   G = harmony / balance
 *   B = memory depth / introspection
 *
 *   left_current  is influenced by (G + B)
 *   right_current is influenced by (R + B)
 *
 *   Both are normalized to 0.0–1.0
 */

class Caduceus
{
    /**
     * Convert mood_vector (RRGGBB) into routing currents.
     *
     * @param string $moodVector 6-char hex string (e.g. "88FF88")
     * @return array ['left' => float, 'right' => float]
     */
    public static function computeCurrents(string $moodVector): array
    {
        // Sanitize input
        $moodVector = strtoupper(trim($moodVector));
        if (!preg_match('/^[0-9A-F]{6}$/', $moodVector)) {
            // fallback to neutral
            $moodVector = '666666';
        }

        // Extract RGB components
        $r = hexdec(substr($moodVector, 0, 2)); // strife
        $g = hexdec(substr($moodVector, 2, 2)); // harmony
        $b = hexdec(substr($moodVector, 4, 2)); // memory depth

        // ---------------------------------------------------------
        // Compute raw currents
        // ---------------------------------------------------------
        //
        // left_current  = harmony + memory depth
        // right_current = strife  + memory depth
        //
        // This gives both sides access to B (introspection),
        // but biases left toward stability and right toward intensity.
        //
        $leftRaw  = $g + $b;
        $rightRaw = $r + $b;

        // Avoid division by zero
        $sum = max($leftRaw + $rightRaw, 1);

        // ---------------------------------------------------------
        // Normalize to 0.0–1.0
        // ---------------------------------------------------------
        $left  = $leftRaw  / $sum;
        $right = $rightRaw / $sum;

        return [
            'left'  => $left,
            'right' => $right
        ];
    }
}

?>
