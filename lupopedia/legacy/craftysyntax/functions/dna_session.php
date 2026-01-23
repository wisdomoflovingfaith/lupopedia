<?php
/**
 * ============================================================================
 * Crafty Syntax 3.8.0 - Dynamic DNA String Building for Sessions
 * ============================================================================
 * 
 * Purpose: Build DNA strings dynamically from user prompts during conversation
 * 
 * DNA String Format: channel-agent_name-DNA_bases
 * Example: "007-wolfie-ATCG 001-unknown-TTAA"
 * 
 * Phase 1 (MVP): Default DNA sequence is 'ATCG' unless explicitly specified
 * 
 * Author: Captain WOLFIE (Eric Robin Gerdes)
 * Date: 2025-11-19
 * ============================================================================
 */

/**
 * Phase 1 (MVP): Determine DNA bases from prompt
 * Defaults to 'ATCG' unless explicitly specified
 * 
 * @param string $prompt User prompt text
 * @return string DNA base sequence (e.g., 'ATCG', 'ACTG')
 */
function determineDNAFromPrompt($prompt) {
    // Check for explicit DNA specification first
    if (preg_match('/DNA\s+([ATCG]+)/i', $prompt, $matches)) {
        return strtoupper($matches[1]);  // User-specified DNA
    }
    
    // Phase 1: Default sequence for MVP
    // A (Actions) + T (Tactics) + C (Context) + G (Governance)
    return 'ATCG';
}

/**
 * Parse prompt and extract channel, agent, and DNA sequence
 * 
 * @param string $prompt User prompt text
 * @return string DNA sequence string (format: channel-agent_name-DNA_bases)
 */
function parsePromptForDNA($prompt) {
    // Extract channel (default: 001)
    $channel = 1;
    if (preg_match('/channel\s+(\d{1,3})/i', $prompt, $matches)) {
        $channel = (int)$matches[1];
        // Validate range (0-999)
        if ($channel < 0 || $channel > 999) {
            $channel = 1;
        }
    } elseif (isset($_SESSION['default_channel'])) {
        $channel = $_SESSION['default_channel'];
    }
    
    // Extract agent (default: unknown)
    $agent = 'unknown';
    if (preg_match('/as\s+(\w+)/i', $prompt, $matches)) {
        $agent = strtolower(trim($matches[1]));
    } elseif (isset($_SESSION['current_agent'])) {
        $agent = $_SESSION['current_agent'];
    }
    
    // Determine DNA sequence (Phase 1: default ATCG)
    $dna = determineDNAFromPrompt($prompt);
    
    // Format: 007-wolfie-ATCG
    return sprintf("%03d-%s-%s", $channel, $agent, $dna);
}

/**
 * Add DNA sequence to session from user prompt
 * 
 * STRATEGY: Append NEW sequence each time (never overwrite)
 * This preserves complete conversation history and enables multi-agent coordination.
 * 
 * Example evolution:
 * - Initial: ''
 * - Prompt 1: "007-wolfie-ATCG"
 * - Prompt 2: "007-wolfie-ATCG 001-unknown-TTAA"
 * - Prompt 3: "007-wolfie-ATCG 001-unknown-TTAA 007-wolfie-ATCC"
 * 
 * @param string $prompt User prompt text
 * @param int $max_sequences Optional: Maximum sequences to keep (0 = unlimited)
 * @return string Updated current DNA string
 */
function addDNAFromPrompt($prompt, $max_sequences = 0) {
    // Initialize session variable if not set
    if (!isset($_SESSION['current_dna'])) {
        $_SESSION['current_dna'] = '';
    }
    
    // Parse prompt and generate DNA sequence
    $new_sequence = parsePromptForDNA($prompt);
    
    // Append with space separator (NEW sequence each time - never overwrite)
    if (!empty($_SESSION['current_dna'])) {
        $_SESSION['current_dna'] .= ' ';
    }
    $_SESSION['current_dna'] .= $new_sequence;
    
    // Optional: Limit total sequences kept (archive older ones if limit exceeded)
    if ($max_sequences > 0) {
        $sequences = explode(' ', trim($_SESSION['current_dna']));
        if (count($sequences) > $max_sequences) {
            // Keep only last N sequences, archive older ones
            $archived = array_slice($sequences, 0, -$max_sequences);
            $kept = array_slice($sequences, -$max_sequences);
            
            $_SESSION['current_dna'] = implode(' ', $kept);
            
            // Store archived sequences
            if (!isset($_SESSION['archived_dna'])) {
                $_SESSION['archived_dna'] = array();
            }
            $_SESSION['archived_dna'] = array_merge($_SESSION['archived_dna'], $archived);
        }
    }
    
    // Track history for completeness (always store full history)
    if (!isset($_SESSION['dna_history'])) {
        $_SESSION['dna_history'] = array();
    }
    $_SESSION['dna_history'][] = array(
        'timestamp' => date('Y-m-d H:i:s'),
        'sequence' => $new_sequence,
        'prompt' => substr($prompt, 0, 255)  // Store first 255 chars of prompt
    );
    
    return $_SESSION['current_dna'];
}

/**
 * Get current DNA string from session
 * 
 * @return string Current DNA string
 */
function getCurrentDNA() {
    return isset($_SESSION['current_dna']) ? $_SESSION['current_dna'] : '';
}

/**
 * Clear DNA string from session
 * 
 * @return bool True on success
 */
function clearDNA() {
    $_SESSION['current_dna'] = '';
    if (isset($_SESSION['dna_history'])) {
        $_SESSION['dna_history'] = array();
    }
    return true;
}

/**
 * Manually append DNA sequence
 * 
 * @param int $channel Channel ID (0-999)
 * @param string $agent Agent name
 * @param string $dna_bases DNA base sequence (e.g., 'ATCG')
 * @return string Updated current DNA string
 */
function appendDNA($channel, $agent, $dna_bases) {
    // Initialize session variable if not set
    if (!isset($_SESSION['current_dna'])) {
        $_SESSION['current_dna'] = '';
    }
    
    // Validate inputs
    $channel = (int)$channel;
    if ($channel < 0 || $channel > 999) {
        $channel = 1;
    }
    
    $agent = strtolower(trim($agent));
    if (empty($agent)) {
        $agent = 'unknown';
    }
    
    $dna_bases = strtoupper(trim($dna_bases));
    // Validate DNA bases (only A, T, C, G allowed)
    $dna_bases = preg_replace('/[^ATCG]/', '', $dna_bases);
    if (empty($dna_bases)) {
        $dna_bases = 'ATCG';  // Default if invalid
    }
    
    // Format: 007-wolfie-ATCG
    $new_sequence = sprintf("%03d-%s-%s", $channel, $agent, $dna_bases);
    
    // Append with space separator
    if (!empty($_SESSION['current_dna'])) {
        $_SESSION['current_dna'] .= ' ';
    }
    $_SESSION['current_dna'] .= $new_sequence;
    
    return $_SESSION['current_dna'];
}

/**
 * Get DNA history from session
 * 
 * @return array Array of DNA history entries (each with timestamp, sequence, prompt)
 */
function getDNAHistory() {
    return isset($_SESSION['dna_history']) ? $_SESSION['dna_history'] : array();
}

/**
 * Get archived DNA sequences (if length limiting is enabled)
 * 
 * @return array Array of archived DNA sequences
 */
function getArchivedDNA() {
    return isset($_SESSION['archived_dna']) ? $_SESSION['archived_dna'] : array();
}

/**
 * Get count of sequences in current DNA string
 * 
 * @return int Number of sequences
 */
function getDNASequenceCount() {
    $dna = getCurrentDNA();
    if (empty($dna)) {
        return 0;
    }
    return count(explode(' ', trim($dna)));
}

/**
 * Parse DNA string into array of sequences
 * 
 * @param string $dna_string DNA string (e.g., "007-wolfie-ATCG 001-unknown-TTAA")
 * @return array Array of parsed sequences
 */
function parseDNAString($dna_string) {
    $sequences = array();
    $parts = explode(' ', trim($dna_string));
    
    foreach ($parts as $part) {
        if (preg_match('/^(\d{3})-(\w+)-([ATCG]+)$/i', $part, $matches)) {
            $sequences[] = array(
                'channel' => (int)$matches[1],
                'agent' => $matches[2],
                'dna_bases' => strtoupper($matches[3])
            );
        }
    }
    
    return $sequences;
}

?>

