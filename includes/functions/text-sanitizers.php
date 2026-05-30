<?php
/**
 * Text Sanitizer Functions
 *
 * Provides ASCII-safe text sanitization for Lupopedia.
 * Ensures all output is ASCII-compatible and free of unicode corruption.
 *
 * @package Lupopedia
 * @version GLOBAL_CURRENT_LUPOPEDIA_VERSION
 * @author GLOBAL_CURRENT_AUTHORS
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. text-sanitizers.php cannot be called directly.");
}

/**
 * Sanitize text to ASCII-safe output
 *
 * Replaces known unicode offenders with ASCII equivalents,
 * then removes any remaining non-ASCII characters outside printable range.
 *
 * @param string $text Input text to sanitize
 * @return string ASCII-safe sanitized text
 */
function lupo_ascii_sanitize($text) {
    if ($text === null || $text === '') {
        return $text;
    }
    
    // Known unicode replacements
    $replacements = array(
        // Em dash and en dash
        "\u{2013}" => '-', // en dash
        "\u{2014}" => '-', // em dash
        
        // Smart quotes
        "\u{2018}" => "'", // left single quote
        "\u{2019}" => "'", // right single quote
        "\u{201C}" => '"', // left double quote
        "\u{201D}" => '"', // right double quote
        
        // Other quotes
        "\u{201B}" => "'", // single high-reversed-9 quote
        "\u{201F}" => '"', // double high-reversed-9 quote
        "\u{2039}" => "'", // single left-pointing angle quote
        "\u{203A}" => "'", // single right-pointing angle quote
        "\u{00AB}" => '"', // left-pointing double angle quote
        "\u{00BB}" => '"', // right-pointing double angle quote
        
        // Fraction slash and unicode slash variants
        "\u{2044}" => '/', // fraction slash
        "\u{2215}" => '/', // division slash
        "\u{29F5}" => '/', // reverse solidus operator
        
        // Ellipsis
        "\u{2026}" => '...', // horizontal ellipsis
        
        // Non-breaking spaces and other space variants
        "\u{00A0}" => ' ', // non-breaking space
        "\u{2000}" => ' ', // en quad
        "\u{2001}" => ' ', // em quad
        "\u{2002}" => ' ', // en space
        "\u{2003}" => ' ', // em space
        "\u{2004}" => ' ', // three-per-em space
        "\u{2005}" => ' ', // four-per-em space
        "\u{2006}" => ' ', // six-per-em space
        "\u{2007}" => ' ', // figure space
        "\u{2008}" => ' ', // punctuation space
        "\u{2009}" => ' ', // thin space
        "\u{200A}" => ' ', // hair space
        "\u{202F}" => ' ', // narrow no-break space
        "\u{205F}" => ' ', // medium mathematical space
        
        // Other common unicode punctuation
        "\u{2032}" => "'", // prime
        "\u{2033}" => '"', // double prime
        "\u{2034}" => "'''", // triple prime
        "\u{2035}" => "'", // reversed prime
        "\u{2036}" => '"', // reversed double prime
        "\u{2037}" => "'''", // reversed triple prime
        
        // Hyphen variants
        "\u{2010}" => '-', // hyphen
        "\u{2011}" => '-', // non-breaking hyphen
        "\u{2012}" => '-', // figure dash
        "\u{2212}" => '-', // minus sign
        
        // Bullet variants
        "\u{2022}" => '*', // bullet
        "\u{2023}" => '*', // triangular bullet
        "\u{25E6}" => '*', // white bullet
        "\u{2043}" => '-', // hyphen bullet
        "\u{204C}" => '*', // black leftwards bullet
        "\u{204D}" => '*', // black rightwards bullet
    );
    
    // Apply replacements
    $text = strtr($text, $replacements);
    
    // Remove remaining non-ASCII characters outside printable range (32-126)
    // Allow tab (9), newline (10), and carriage return (13) for document text
    $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);
    $text = preg_replace('/[\x80-\xFF]/', '', $text);
    
    return $text;
}

/**
 * Validate if text contains only ASCII-safe characters
 *
 * @param string $text Text to validate
 * @return bool True if ASCII-safe, false otherwise
 */
function lupo_validate_ascii_only($text) {
    if ($text === null || $text === '') {
        return true;
    }
    
    // Check for any non-ASCII characters (outside 0-127)
    // Allow tab, newline, carriage return for document text
    return !preg_match('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F-\xFF]/', $text);
}

/**
 * Strict ASCII validation for parser-critical fields
 *
 * For fields like prd_cluster that must be single-line, no tabs, no spaces
 *
 * @param string $text Text to validate
 * @return bool True if strictly ASCII-safe, false otherwise
 */
function lupo_validate_strict_ascii($text) {
    if ($text === null || $text === '') {
        return true;
    }
    
    // Only allow ASCII letters, numbers, and underscore
    return preg_match('/^[A-Za-z0-9_]*$/', $text);
}

?>
