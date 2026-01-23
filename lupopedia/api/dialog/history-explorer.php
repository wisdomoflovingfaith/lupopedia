<?php
/**
 * History Explorer API Endpoint
 * 
 * Provides conversational responses to timeline queries with emotional intelligence
 * and smart cross-reference suggestions.
 * 
 * @package Lupopedia
 * @version 4.0.66
 * @author Captain Wolfie
 */

// Include required components
require_once '../../lupopedia-config.php';
require_once '../../lupo-includes/HistoryReconciliation/ContinuityValidator.php';
require_once '../../lupo-includes/HistoryReconciliation/TimelineManager.php';
require_once '../../lupo-includes/HistoryReconciliation/DocumentationGenerator.php';

// Set JSON response headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Initialize components
$validator = new ContinuityValidator();
$timelineManager = new TimelineManager();
$docGenerator = new DocumentationGenerator();

// Get query parameter
$query = isset($_GET['q']) ? trim($_GET['q']) : '';

if (empty($query)) {
    echo json_encode([
        'error' => 'No query provided',
        'usage' => 'Add ?q=your_question to the URL'
    ]);
    exit;
}

try {
    // Analyze query for sensitive topics
    $isSensitive = containsSensitiveTopics($query);
    
    // Generate response based on query
    $response = generateHistoryResponse($query, $validator, $timelineManager, $isSensitive);
    
    // Add cross-references and suggestions
    $response['cross_references'] = generateCrossReferences($query);
    $response['suggestions'] = generateSuggestions($query);
    $response['metadata'] = [
        'query' => $query,
        'sensitive_topic' => $isSensitive,
        'timestamp' => date('Y-m-d H:i:s'),
        'version' => '4.0.65'
    ];
    
    echo json_encode($response, JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    echo json_encode([
        'error' => 'Failed to process query',
        'message' => $e->getMessage(),
        'query' => $query
    ]);
}

/**
 * Check if query contains sensitive topics
 */
function containsSensitiveTopics($query) {
    $sensitiveKeywords = [
        'tragedy', 'death', 'died', 'wife', 'personal', 'loss', 'grief',
        '2014', 'hiatus', 'absence', 'pause', 'stopped', 'healing'
    ];
    
    $queryLower = strtolower($query);
    foreach ($sensitiveKeywords as $keyword) {
        if (strpos($queryLower, $keyword) !== false) {
            return true;
        }
    }
    
    return false;
}

/**
 * Generate response based on query analysis
 */
function generateHistoryResponse($query, $validator, $timelineManager, $isSensitive) {
    $queryLower = strtolower($query);
    
    // Analyze query type
    if (preg_match('/\b(2014|hiatus|absence|tragedy|pause)\b/i', $query)) {
        return generateHiatusResponse($query, $isSensitive);
    } elseif (preg_match('/\b(2025|2026|resurgence|return|wolfie|lupopedia)\b/i', $query)) {
        return generateResurgenceResponse($query);
    } elseif (preg_match('/\b(1996|1997|1998|1999|2000|2001|2002|2003|2004|2005|2006|2007|2008|2009|2010|2011|2012|2013|crafty|syntax|active)\b/i', $query)) {
        return generateActiveResponse($query);
    } elseif (preg_match('/\b(timeline|complete|overview|summary|all)\b/i', $query)) {
        return generateTimelineOverview($validator, $timelineManager);
    } else {
        return generateGeneralResponse($query, $validator);
    }
}

/**
 * Generate response for hiatus period queries
 */
function generateHiatusResponse($query, $isSensitive) {
    $response = [
        'type' => 'hiatus_period',
        'title' => '2014-2025: Hiatus Period',
        'content' => '',
        'tone' => 'respectful'
    ];
    
    if ($isSensitive) {
        $response['content'] = "The 2014-2025 period represents an 11-year hiatus following a profound personal tragedy in 2014 when Eric's wife passed away. This led to a complete pause in creative work while focusing on healing and personal recovery.\n\n";
        $response['content'] .= "During this time:\n";
        $response['content'] .= "• All development work was suspended\n";
        $response['content'] .= "• Systems remained dormant but preserved\n";
        $response['content'] .= "• Personal healing took priority\n";
        $response['content'] .= "• The foundation for future work remained intact\n\n";
        $response['content'] .= "This period, while born of tragedy, ultimately provided the distance and perspective that informed the mature, emotionally resonant architecture that emerged in the 2025-2026 resurgence.";
        
        $response['sensitivity_notice'] = "This topic involves personal tragedy and is handled with care and respect.";
    } else {
        $response['content'] = "The 2014-2025 hiatus period spans 11 years during which all creative development was suspended. This period is now 100% documented across individual year files, each maintaining respectful narrative and philosophical continuity.\n\n";
        $response['content'] .= "Key aspects:\n";
        $response['content'] .= "• Complete documentation coverage (11/11 years)\n";
        $response['content'] .= "• Consistent WOLFIE headers and metadata\n";
        $response['content'] .= "• Timeline references and cross-period continuity\n";
        $response['content'] .= "• Foundation preservation for 2025 return";
    }
    
    return $response;
}

/**
 * Generate response for resurgence period queries
 */
function generateResurgenceResponse($query) {
    return [
        'type' => 'resurgence_period',
        'title' => '2025-2026: The Resurgence',
        'content' => "The resurgence period marks Eric's return to software development after 12 years, bringing transformed perspective and the emergence of WOLFIE.\n\n" .
                    "**August 2025: The Return**\n" .
                    "• Eric returned with WOLFIE as AI embodiment of accumulated wisdom\n" .
                    "• 222 tables inherited from Crafty Syntax legacy\n" .
                    "• Vision: Semantic operating system, not just web application\n\n" .
                    "**January 2026: The 16-Day Sprint**\n" .
                    "• 26 version increments (4.0.0 → 4.0.65)\n" .
                    "• 120 tables across 3 schemas (core, orchestration, ephemeral)\n" .
                    "• 128 AI agents defined and documented\n" .
                    "• 8-state migration orchestrator fully implemented\n" .
                    "• Complete semantic operating system architecture\n\n" .
                    "This period demonstrates the power of returning to work with fresh perspective and accumulated life experience.",
        'tone' => 'triumphant'
    ];
}

/**
 * Generate response for active period queries
 */
function generateActiveResponse($query) {
    return [
        'type' => 'active_period',
        'title' => '1996-2013: Active Development Period',
        'content' => "The active development period spans 18 years of continuous creative work, establishing the foundation for all future development.\n\n" .
                    "**Key Achievements:**\n" .
                    "• 1996: Beginning of Eric's creative journey\n" .
                    "• 2002: Crafty Syntax Live Help created\n" .
                    "• 2003-2013: Continuous evolution (versions 2.0.19 → 3.7.5)\n" .
                    "• 2013: Crafty Syntax 3.7.5 (final version before hiatus)\n\n" .
                    "**Current Documentation Status:**\n" .
                    "• Partial coverage (2/18 years documented)\n" .
                    "• Remaining gaps in 1997-2001 and 2003-2013 periods\n" .
                    "• Foundation work that directly informs Lupopedia architecture\n\n" .
                    "This period established the design patterns, technical expertise, and philosophical foundation that would later emerge in Lupopedia's 2025-2026 development.",
        'tone' => 'foundational'
    ];
}

/**
 * Generate timeline overview response
 */
function generateTimelineOverview($validator, $timelineManager) {
    $report = $validator->generateValidationReport();
    $milestones = $timelineManager->generateTimelineMilestones();
    
    return [
        'type' => 'timeline_overview',
        'title' => 'Complete Timeline: 1996-2026 (30 Years)',
        'content' => "**Timeline Coverage: {$report['timeline_continuity']['coverage_percentage']}%**\n" .
                    "Years Documented: {$report['timeline_continuity']['years_documented']}/{$report['timeline_continuity']['total_years']}\n\n" .
                    "**Period Breakdown:**\n" .
                    "• **1996-2013 (Active):** {$report['timeline_continuity']['coverage']['periods']['active']['percent']}% documented ({$report['timeline_continuity']['coverage']['periods']['active']['documented']}/{$report['timeline_continuity']['coverage']['periods']['active']['total']} years)\n" .
                    "• **2014-2025 (Hiatus):** {$report['timeline_continuity']['coverage']['periods']['hiatus']['percent']}% documented ({$report['timeline_continuity']['coverage']['periods']['hiatus']['documented']}/{$report['timeline_continuity']['coverage']['periods']['hiatus']['total']} years) ✅\n" .
                    "• **2025-2026 (Resurgence):** {$report['timeline_continuity']['coverage']['periods']['resurgence']['percent']}% documented ({$report['timeline_continuity']['coverage']['periods']['resurgence']['documented']}/{$report['timeline_continuity']['coverage']['periods']['resurgence']['total']} years) ✅\n\n" .
                    "**Major Milestones:**\n" .
                    implode("\n", array_map(function($m) { return "• {$m['year']}: {$m['event']}"; }, $milestones['major_milestones'])),
        'tone' => 'comprehensive'
    ];
}

/**
 * Generate general response for other queries
 */
function generateGeneralResponse($query, $validator) {
    return [
        'type' => 'general',
        'title' => 'History Explorer Response',
        'content' => "I can help you explore the 30-year timeline from 1996-2026. The timeline is divided into three main periods:\n\n" .
                    "• **Active Development (1996-2013):** 18 years of continuous creative work\n" .
                    "• **Hiatus Period (2014-2025):** 11-year absence following personal tragedy\n" .
                    "• **Resurgence (2025-2026):** Return and intensive Lupopedia development\n\n" .
                    "Try asking about specific years, periods, or events. I handle sensitive topics with care and provide detailed cross-references.",
        'tone' => 'helpful'
    ];
}

/**
 * Generate cross-references based on query
 */
function generateCrossReferences($query) {
    $refs = [];
    
    if (preg_match('/\b(2014|hiatus|tragedy)\b/i', $query)) {
        $refs[] = ['file' => 'docs/history/2014-2025/hiatus.md', 'title' => 'Complete Hiatus Documentation'];
        $refs[] = ['file' => 'docs/history/2014-2025/2014.md', 'title' => '2014: The Pivot Point'];
    }
    
    if (preg_match('/\b(2025|2026|wolfie|lupopedia)\b/i', $query)) {
        $refs[] = ['file' => 'docs/history/future/2026.md', 'title' => '2026: The 16-Day Sprint'];
        $refs[] = ['file' => 'docs/history/2014-2025/2025.md', 'title' => '2025: The Return'];
    }
    
    $refs[] = ['file' => 'docs/history/TIMELINE_1996_2026.md', 'title' => 'Complete Timeline'];
    
    return $refs;
}

/**
 * Generate follow-up suggestions
 */
function generateSuggestions($query) {
    $suggestions = [
        "What were the major achievements during the 16-day sprint?",
        "How did the hiatus period influence Lupopedia's architecture?",
        "What is WOLFIE and how did it emerge?",
        "Show me the complete timeline with all milestones"
    ];
    
    // Remove current query from suggestions
    return array_filter($suggestions, function($s) use ($query) {
        return stripos($s, $query) === false;
    });
}
?>