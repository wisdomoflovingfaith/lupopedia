<?php
/**
---
wolfie.headers.version: "4.0.12"
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
dialog:
  - speaker: CURSOR
    target: @everyone
    message: "Version 4.0.12: Version bump for hierarchical tab structure implementation. No logic changes to truth-model.php in this version."
    mood: "00FF00"
  - speaker: CURSOR
    target: @everyone
    message: "Version 4.0.11: Added collection tab content loading functions (truth_get_content_for_tab, truth_get_tab_by_slug, truth_get_content_by_slug) for loading system documentation content mapped to Collection 0 tabs. Functions query lupo_collection_tab_map and lupo_contents tables."
    mood: "00FF00"
  - speaker: CURSOR
    target: @everyone
    message: "TRUTH Module Integration Phase 2.5: Added receiver functions (truth_receive_assertion, truth_receive_evidence) for logging assertion and evidence submissions. No DB writes, no inference, no scoring, no truth evaluation - just logging payloads to error_log. Input scaffolding only."
    mood: "00FF00"
  - speaker: CURSOR
    target: @everyone
    message: "TRUTH Module Integration Phase 2: Added helper functions (truth_get_questions_for_slug, truth_get_answers_for_slug, truth_get_evidence_for_slug, truth_get_related_content) for loading TRUTH-related data by content slug. No inference logic, no truth evaluation, no evidence weighting - just data loading and view model building."
    mood: "00FF00"
tags:
  categories: ["module", "truth", "model", "phase2.5", "phase2"]
  collections: ["core-modules"]
  channels: ["dev"]
file:
  title: "TRUTH Model"
  description: "Data-access layer for TRUTH subsystem: questions, answers, evidence, sources, topics, relations. Phase 2.5: Input receivers (logging only). Phase 2: Data loading by slug."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: active
  author: GLOBAL_CURRENT_AUTHORS
---
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. truth-model.php cannot be called directly.");
}

/**
 * ---------------------------------------------------------
 * TRUTH Model - Database Access Layer
 * ---------------------------------------------------------
 */

/**
 * Get question by slug and qtype
 * 
 * @param string $qtype Question type (who, what, where, when, why, how)
 * @param string $slug The question slug (without prefix)
 * @return array|null Question row or null if not found
 */
function truth_get_question_by_slug($qtype, $slug) {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase'])) {
        return null;
    }
    
    $db = $GLOBALS['mydatabase'];
    
    $sql = "SELECT * FROM {$table_prefix}truth_questions
            WHERE qtype = ?
              AND slug = ?
              AND status = 'active'
              AND is_deleted = 0
            LIMIT 1";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([$qtype, $slug]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    } catch (PDOException $e) {
        error_log('TRUTH model error: ' . $e->getMessage());
        return null;
    }
}

/**
 * Get answers for a question
 * 
 * @param int $question_id Question ID
 * @return array Array of answer rows
 */
function truth_get_answers($question_id) {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase'])) {
        return [];
    }
    
    $db = $GLOBALS['mydatabase'];
    
    $sql = "SELECT * FROM {$table_prefix}truth_answers
            WHERE truth_question_id = ?
              AND is_deleted = 0
            ORDER BY confidence_score DESC, evidence_score DESC, created_ymdhis DESC";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([$question_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('TRUTH get answers error: ' . $e->getMessage());
        return [];
    }
}

/**
 * Get evidence for an answer
 * 
 * @param int $answer_id Answer ID
 * @return array Array of evidence rows
 */
function truth_get_evidence($answer_id) {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase'])) {
        return [];
    }
    
    $db = $GLOBALS['mydatabase'];
    
    $sql = "SELECT * FROM {$table_prefix}truth_evidence
            WHERE truth_answer_id = ?
              AND is_deleted = 0
            ORDER BY weight_score DESC, created_ymdhis DESC";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([$answer_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('TRUTH get evidence error: ' . $e->getMessage());
        return [];
    }
}

/**
 * Get sources for evidence
 * 
 * @param int $evidence_id Evidence ID
 * @return array Array of source rows
 */
function truth_get_sources($evidence_id) {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase'])) {
        return [];
    }
    
    $db = $GLOBALS['mydatabase'];
    
    $sql = "SELECT * FROM {$table_prefix}truth_sources
            WHERE truth_evidence_id = ?
              AND is_deleted = 0
            ORDER BY reliability_score DESC, created_ymdhis DESC";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([$evidence_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('TRUTH get sources error: ' . $e->getMessage());
        return [];
    }
}

/**
 * Get topic by slug
 * 
 * @param string $slug Topic slug
 * @return array|null Topic row or null if not found
 */
function truth_get_topic_by_slug($slug) {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase'])) {
        return null;
    }
    
    $db = $GLOBALS['mydatabase'];
    
    $sql = "SELECT * FROM {$table_prefix}truth_topics
            WHERE slug = ?
              AND is_deleted = 0
            LIMIT 1";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([$slug]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    } catch (PDOException $e) {
        error_log('TRUTH get topic error: ' . $e->getMessage());
        return null;
    }
}

/**
 * Get questions by qtype
 * 
 * @param string $qtype Question type (who, what, where, when, why, how)
 * @param int $limit Limit number of results
 * @return array Array of question rows
 */
function truth_get_questions_by_type($qtype, $limit = 50) {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase'])) {
        return [];
    }
    
    $db = $GLOBALS['mydatabase'];
    
    $sql = "SELECT * FROM {$table_prefix}truth_questions
            WHERE qtype = ?
              AND status = 'active'
              AND is_deleted = 0
            ORDER BY is_featured DESC, answer_count DESC, created_ymdhis DESC
            LIMIT ?";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([$qtype, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('TRUTH get questions by type error: ' . $e->getMessage());
        return [];
    }
}

/**
 * Get all evidence and sources for an answer (with sources linked)
 * 
 * @param int $answer_id Answer ID
 * @return array Array of evidence rows, each with 'sources' key containing source array
 */
function truth_get_evidence_with_sources($answer_id) {
    $evidence_list = truth_get_evidence($answer_id);
    
    // Attach sources to each evidence item
    foreach ($evidence_list as &$evidence) {
        $evidence['sources'] = truth_get_sources($evidence['truth_evidence_id']);
    }
    
    return $evidence_list;
}

/**
 * Get all answers with evidence and sources for a question
 * 
 * @param int $question_id Question ID
 * @return array Array of answer rows, each with 'evidence' key containing evidence array (with sources)
 */
function truth_get_answers_with_evidence($question_id) {
    $answers = truth_get_answers($question_id);
    
    // Attach evidence (with sources) to each answer
    foreach ($answers as &$answer) {
        $answer['evidence'] = truth_get_evidence_with_sources($answer['truth_answer_id']);
    }
    
    return $answers;
}

/**
 * ---------------------------------------------------------
 * TRUTH Phase 2 Helper Functions (Data Loading by Slug)
 * ---------------------------------------------------------
 * 
 * Phase 2: Data loading and view model building (no logic)
 * These functions load TRUTH-related data for a content slug.
 * No inference, no truth evaluation, no evidence weighting.
 */

/**
 * Get TRUTH questions related to a content slug
 * 
 * Phase 2: Finds questions that reference content with this slug
 * 
 * @param string $slug Content slug
 * @return array Array of question rows
 */
function truth_get_questions_for_slug($slug) {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase']) || empty($slug)) {
        return [];
    }
    
    $db = $GLOBALS['mydatabase'];
    
    // Find questions that reference content with this slug
    // Uses lupo_truth_questions_map to link questions to content via object_type='content' and object_id
    // This is a simple lookup - no inference logic
    $sql = "SELECT DISTINCT tq.*
            FROM {$table_prefix}truth_questions tq
            LEFT JOIN {$table_prefix}truth_questions_map tqm ON tqm.truth_question_id = tq.truth_question_id
              AND tqm.object_type = 'content'
              AND tqm.is_deleted = 0
            LEFT JOIN {$table_prefix}contents c ON c.content_id = tqm.object_id
            WHERE (c.slug = ? OR tq.slug LIKE ?)
              AND tq.status = 'active'
              AND tq.is_deleted = 0
            ORDER BY tq.is_featured DESC, tq.answer_count DESC
            LIMIT 20";
    
    try {
        $slug_pattern = '%' . $slug . '%';
        $stmt = $db->prepare($sql);
        $stmt->execute([$slug, $slug_pattern]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('TRUTH get questions for slug error: ' . $e->getMessage());
        return [];
    }
}

/**
 * Get TRUTH answers related to a content slug
 * 
 * Phase 2: Finds answers for questions related to content with this slug
 * 
 * @param string $slug Content slug
 * @return array Array of answer rows
 */
function truth_get_answers_for_slug($slug) {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase']) || empty($slug)) {
        return [];
    }
    
    $db = $GLOBALS['mydatabase'];
    
    // Find answers for questions related to this slug
    // Uses lupo_truth_questions_map to link questions to content via object_type='content' and object_id
    $sql = "SELECT DISTINCT ta.*
            FROM {$table_prefix}truth_answers ta
            JOIN {$table_prefix}truth_questions tq ON tq.truth_question_id = ta.truth_question_id
            LEFT JOIN {$table_prefix}truth_questions_map tqm ON tqm.truth_question_id = tq.truth_question_id
              AND tqm.object_type = 'content'
              AND tqm.is_deleted = 0
            LEFT JOIN {$table_prefix}contents c ON c.content_id = tqm.object_id
            WHERE (c.slug = ? OR tq.slug LIKE ?)
              AND tq.status = 'active'
              AND tq.is_deleted = 0
              AND ta.is_deleted = 0
            ORDER BY ta.confidence_score DESC, ta.evidence_score DESC
            LIMIT 50";
    
    try {
        $slug_pattern = '%' . $slug . '%';
        $stmt = $db->prepare($sql);
        $stmt->execute([$slug, $slug_pattern]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('TRUTH get answers for slug error: ' . $e->getMessage());
        return [];
    }
}

/**
 * Get TRUTH evidence related to a content slug (placeholder)
 * 
 * Phase 2: Structural placeholder - no logic, no scoring
 * 
 * @param string $slug Content slug
 * @return array Array of evidence placeholder structures
 */
function truth_get_evidence_for_slug($slug) {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase']) || empty($slug)) {
        return [];
    }
    
    $db = $GLOBALS['mydatabase'];
    
    // Find evidence for answers related to this slug
    // Uses lupo_truth_questions_map to link questions to content via object_type='content' and object_id
    // Phase 2: Just structural data, no weighting logic
    $sql = "SELECT DISTINCT te.*
            FROM {$table_prefix}truth_evidence te
            JOIN {$table_prefix}truth_answers ta ON ta.truth_answer_id = te.truth_answer_id
            JOIN {$table_prefix}truth_questions tq ON tq.truth_question_id = ta.truth_question_id
            LEFT JOIN {$table_prefix}truth_questions_map tqm ON tqm.truth_question_id = tq.truth_question_id
              AND tqm.object_type = 'content'
              AND tqm.is_deleted = 0
            LEFT JOIN {$table_prefix}contents c ON c.content_id = tqm.object_id
            WHERE (c.slug = ? OR tq.slug LIKE ?)
              AND tq.status = 'active'
              AND tq.is_deleted = 0
              AND ta.is_deleted = 0
              AND te.is_deleted = 0
            ORDER BY te.created_ymdhis DESC
            LIMIT 50";
    
    try {
        $slug_pattern = '%' . $slug . '%';
        $stmt = $db->prepare($sql);
        $stmt->execute([$slug, $slug_pattern]);
        $evidence_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Phase 2: Return placeholder structure (no scoring logic)
        $evidence_placeholders = [];
        foreach ($evidence_list as $evidence) {
            $evidence_placeholders[] = [
                'source' => 'content:' . $slug,
                'type' => $evidence['evidence_type'] ?? 'reference',
                'weight' => null, // Phase 3+
                'summary' => null, // Phase 3+
                'evidence_id' => $evidence['truth_evidence_id'] ?? null,
                'evidence_text' => $evidence['evidence_text'] ?? '',
                'created_ymdhis' => $evidence['created_ymdhis'] ?? null
            ];
        }
        
        return $evidence_placeholders;
    } catch (PDOException $e) {
        error_log('TRUTH get evidence for slug error: ' . $e->getMessage());
        return [];
    }
}

/**
 * Get related content for a slug (optional helper)
 * 
 * Phase 2: Finds content related via semantic relationships
 * 
 * @param string $slug Content slug
 * @return array Array of related content rows
 */
function truth_get_related_content($slug) {
    // Use existing content model function if content exists
    $content = content_get_by_slug($slug);
    if (!$content || empty($content['content_id'])) {
        return [];
    }
    
    // Use ConnectionsService to get related content
    if (class_exists('ConnectionsService') && !empty($GLOBALS['mydatabase'])) {
        $domainId = isset($GLOBALS['domain_id']) ? (int)$GLOBALS['domain_id'] : 1;
        $connectionsService = new ConnectionsService($GLOBALS['mydatabase'], $domainId);
        $connections = $connectionsService->getConnectionsForContent($content['content_id']);
        
        return $connections['related_content'] ?? [];
    }
    
    return [];
}

/**
 * ---------------------------------------------------------
 * TRUTH Phase 2.5 Input Receivers
 * ---------------------------------------------------------
 * 
 * Receivers for assertion and evidence submissions.
 * Logging only - no DB writes, no inference, no scoring.
 */

/**
 * Receive assertion submission
 * 
 * Phase 2.5: Logs assertion payload only. No DB writes, no inference, no truth evaluation.
 * 
 * @param array $payload Assertion payload with keys: slug, assertion, source, summary, timestamp
 * @return bool True on success (always succeeds for logging)
 */
function truth_receive_assertion($payload) {
    // Phase 2.5 placeholder: logging only
    // Must NOT write to database
    // Must NOT evaluate truth
    // Must NOT store assertions
    // Must NOT modify schema
    
    if (empty($payload) || !is_array($payload)) {
        error_log("TRUTH ASSERTION RECEIVED: Invalid payload");
        return false;
    }
    
    // Log the assertion payload
    error_log("TRUTH ASSERTION RECEIVED: " . json_encode($payload));
    
    return true;
}

/**
 * Receive evidence submission
 * 
 * Phase 2.5: Logs evidence payload only. No DB writes, no inference, no weighting.
 * 
 * @param array $payload Evidence payload with keys: slug, type, source, summary, timestamp
 * @return bool True on success (always succeeds for logging)
 */
function truth_receive_evidence($payload) {
    // Phase 2.5 placeholder: logging only
    // Must NOT write to database
    // Must NOT evaluate truth
    // Must NOT store evidence
    // Must NOT modify schema
    // Must NOT perform weighting or scoring
    
    if (empty($payload) || !is_array($payload)) {
        error_log("TRUTH EVIDENCE RECEIVED: Invalid payload");
        return false;
    }
    
    // Log the evidence payload
    error_log("TRUTH EVIDENCE RECEIVED: " . json_encode($payload));
    
    return true;
}

/**
 * ---------------------------------------------------------
 * TRUTH Phase 4.0.11: Collection Tab Content Loading
 * ---------------------------------------------------------
 * 
 * Functions to load content items mapped to collection tabs.
 */

/**
 * Get content items mapped to a collection tab
 * 
 * Version 4.0.11: Loads content items from lupo_collection_tab_map
 * for a specific collection tab, ordered by sort_order.
 * 
 * @param int $collection_tab_id Collection tab ID
 * @return array Array of content rows with metadata
 */
function truth_get_content_for_tab($collection_tab_id) {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase']) || empty($collection_tab_id)) {
        return [];
    }
    
    $db = $GLOBALS['mydatabase'];
    
    $sql = "SELECT 
                c.content_id,
                c.title,
                c.slug,
                c.excerpt,
                c.body,
                c.content_type,
                c.format,
                c.created_ymdhis,
                c.updated_ymdhis,
                ctm.sort_order
            FROM {$table_prefix}collection_tab_map ctm
            JOIN {$table_prefix}contents c ON c.content_id = ctm.item_id
            WHERE ctm.collection_tab_id = :tab_id
              AND ctm.item_type = 'content'
              AND ctm.is_deleted = 0
              AND c.is_deleted = 0
              AND c.is_active = 1
            ORDER BY ctm.sort_order ASC, c.title ASC";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([':tab_id' => $collection_tab_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log('TRUTH get content for tab error: ' . $e->getMessage());
        return [];
    }
}

/**
 * Get collection tab by slug
 * 
 * Version 4.0.11: Gets collection tab information by slug.
 * 
 * @param int $collection_id Collection ID
 * @param string $tab_slug Tab slug
 * @return array|null Tab row or null if not found
 */
function truth_get_tab_by_slug($collection_id, $tab_slug) {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase']) || empty($collection_id) || empty($tab_slug)) {
        return null;
    }
    
    $db = $GLOBALS['mydatabase'];
    
    $sql = "SELECT *
            FROM {$table_prefix}collection_tabs
            WHERE collection_id = :collection_id
              AND slug = :slug
              AND is_active = 1
              AND is_deleted = 0
            LIMIT 1";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([':collection_id' => $collection_id, ':slug' => $tab_slug]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    } catch (PDOException $e) {
        error_log('TRUTH get tab by slug error: ' . $e->getMessage());
        return null;
    }
}

/**
 * Get content by slug
 * 
 * Version 4.0.11: Gets content item by slug for display.
 * 
 * @param string $slug Content slug
 * @return array|null Content row or null if not found
 */
function truth_get_content_by_slug($slug) {
    global $table_prefix;
    
    if (empty($GLOBALS['mydatabase']) || empty($slug)) {
        return null;
    }
    
    $db = $GLOBALS['mydatabase'];
    
    $sql = "SELECT *
            FROM {$table_prefix}contents
            WHERE slug = :slug
              AND is_deleted = 0
              AND is_active = 1
            LIMIT 1";
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute([':slug' => $slug]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    } catch (PDOException $e) {
        error_log('TRUTH get content by slug error: ' . $e->getMessage());
        return null;
    }
}

?>
