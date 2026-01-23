<?php
/**
---
wolfie.headers.version: "4.0.12"
header_atoms:
  - GLOBAL_CURRENT_LUPOPEDIA_VERSION
dialog:
  - speaker: CURSOR
    target: @everyone
    message: "Version 4.0.12: Version bump for hierarchical tab structure implementation. No logic changes to truth-render.php in this version."
    mood: "00FF00"
  - speaker: CURSOR
    target: @everyone
    message: "Version 4.0.11: Fixed markdown rendering in truth_render_content_item() - ensures render_markdown() is loaded before use and outputs HTML directly (not escaped). Added tab content list and content item rendering functions (truth_render_tab_content_list, truth_render_content_item)."
    mood: "00FF00"
  - speaker: CURSOR
    target: @everyone
    message: "TRUTH Module Integration Phase 2.5: Added assertion and evidence input forms (truth_render_assertion_form, truth_render_evidence_form). Forms are procedural, handle empty fields gracefully, use POST, and display submission confirmation messages. No JavaScript, no dynamic includes, no inference logic - just input scaffolding."
    mood: "00FF00"
  - speaker: CURSOR
    target: @everyone
    message: "TRUTH Module Integration Phase 2: Expanded renderer with structured UI blocks (header, content summary, references, links, tags, collection, questions, answers, evidence placeholder, semantic context summary). All blocks render from view model data. No inference logic, no truth evaluation, no evidence weighting - just structured display."
    mood: "00FF00"
tags:
  categories: ["module", "truth", "renderer", "phase2.5", "phase2"]
  collections: ["core-modules"]
  channels: ["dev"]
file:
  title: "TRUTH Renderer"
  description: "Rendering layer for TRUTH subsystem: outputs questions, answers, evidence, and sources. Phase 2.5: Input forms. Phase 2: Structured UI blocks."
  version: GLOBAL_CURRENT_LUPOPEDIA_VERSION
  status: active
  author: GLOBAL_CURRENT_AUTHORS
---
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. truth-render.php cannot be called directly.");
}

require_once __DIR__ . '/truth-model.php';

/**
 * ---------------------------------------------------------
 * TRUTH Renderer - Rendering Functions
 * ---------------------------------------------------------
 */

/**
 * Render question text based on format
 * 
 * @param string $text Question text
 * @param string $format Format type (html, markdown, text, bbcode)
 * @param string $format_override Override format if provided
 * @return string Rendered HTML
 */
function truth_render_question_text($text, $format = 'text', $format_override = null) {
    if (!empty($format_override)) {
        $format = $format_override;
    }
    
    switch ($format) {
        case 'html':
            return $text;
        case 'markdown':
            if (function_exists('render_markdown')) {
                return render_markdown($text);
            }
            return '<pre>' . htmlspecialchars($text) . '</pre>';
        case 'bbcode':
            // Basic BBCode support (can be enhanced)
            $text = htmlspecialchars($text);
            $text = preg_replace('/\[b\](.*?)\[\/b\]/i', '<strong>$1</strong>', $text);
            $text = preg_replace('/\[i\](.*?)\[\/i\]/i', '<em>$1</em>', $text);
            $text = preg_replace('/\[url=(.*?)\](.*?)\[\/url\]/i', '<a href="$1">$2</a>', $text);
            return nl2br($text);
        case 'text':
        default:
            return nl2br(htmlspecialchars($text));
    }
}

/**
 * Render answer text
 * 
 * @param string $text Answer text
 * @return string Rendered HTML
 */
function truth_render_answer_text($text) {
    return nl2br(htmlspecialchars($text));
}

/**
 * Render evidence item
 * 
 * @param array $evidence Evidence row
 * @return string HTML for evidence
 */
function truth_render_evidence($evidence) {
    $evidence_text = htmlspecialchars($evidence['evidence_text']);
    $evidence_type = htmlspecialchars($evidence['evidence_type']);
    $weight_score = isset($evidence['weight_score']) ? floatval($evidence['weight_score']) : 0.00;
    
    $html = '<div class="truth-evidence" data-evidence-id="' . htmlspecialchars($evidence['truth_evidence_id']) . '">';
    $html .= '<div class="evidence-header">';
    $html .= '<span class="evidence-type">' . $evidence_type . '</span>';
    if ($weight_score > 0) {
        $html .= '<span class="evidence-weight">Weight: ' . number_format($weight_score, 2) . '</span>';
    }
    $html .= '</div>';
    $html .= '<div class="evidence-text">' . nl2br($evidence_text) . '</div>';
    
    // Render sources if available
    if (isset($evidence['sources']) && !empty($evidence['sources'])) {
        $html .= '<div class="evidence-sources">';
        $html .= '<strong>Sources:</strong>';
        $html .= '<ul class="source-list">';
        foreach ($evidence['sources'] as $source) {
            $html .= truth_render_source($source);
        }
        $html .= '</ul>';
        $html .= '</div>';
    }
    
    $html .= '</div>';
    return $html;
}

/**
 * Render source item
 * 
 * @param array $source Source row
 * @return string HTML for source (as list item)
 */
function truth_render_source($source) {
    $source_title = htmlspecialchars($source['source_title']);
    $source_url = isset($source['source_url']) ? htmlspecialchars($source['source_url']) : '';
    $source_type = htmlspecialchars($source['source_type']);
    $reliability_score = isset($source['reliability_score']) ? floatval($source['reliability_score']) : 0.00;
    
    $html = '<li class="truth-source" data-source-id="' . htmlspecialchars($source['truth_sourc_id']) . '">';
    
    if (!empty($source_url)) {
        $html .= '<a href="' . $source_url . '" target="_blank" rel="noopener noreferrer" class="source-link">';
        $html .= '<span class="source-title">' . $source_title . '</span>';
        $html .= '</a>';
    } else {
        $html .= '<span class="source-title">' . $source_title . '</span>';
    }
    
    if (!empty($source_type)) {
        $html .= ' <span class="source-type">(' . $source_type . ')</span>';
    }
    
    if ($reliability_score > 0) {
        $html .= ' <span class="source-reliability">Reliability: ' . number_format($reliability_score, 2) . '</span>';
    }
    
    $html .= '</li>';
    return $html;
}

/**
 * Render answer with evidence and sources
 * 
 * @param array $answer Answer row (with evidence array attached)
 * @return string HTML for answer
 */
function truth_render_answer($answer) {
    $answer_text = truth_render_answer_text($answer['answer_text']);
    $confidence_score = isset($answer['confidence_score']) ? floatval($answer['confidence_score']) : 0.00;
    $evidence_score = isset($answer['evidence_score']) ? floatval($answer['evidence_score']) : 0.00;
    $contradiction_flag = isset($answer['contradiction_flag']) && $answer['contradiction_flag'] == 1;
    
    $html = '<div class="truth-answer" data-answer-id="' . htmlspecialchars($answer['truth_answer_id']) . '">';
    $html .= '<div class="answer-header">';
    
    if ($confidence_score > 0) {
        $html .= '<span class="confidence-score">Confidence: ' . number_format($confidence_score, 2) . '</span>';
    }
    if ($evidence_score > 0) {
        $html .= '<span class="evidence-score">Evidence: ' . number_format($evidence_score, 2) . '</span>';
    }
    if ($contradiction_flag) {
        $html .= '<span class="contradiction-flag">⚠ Contradiction Detected</span>';
    }
    
    $html .= '</div>';
    $html .= '<div class="answer-text">' . $answer_text . '</div>';
    
    // Render evidence if available
    if (isset($answer['evidence']) && !empty($answer['evidence'])) {
        $html .= '<div class="answer-evidence">';
        $html .= '<strong>Evidence:</strong>';
        foreach ($answer['evidence'] as $evidence) {
            $html .= truth_render_evidence($evidence);
        }
        $html .= '</div>';
    }
    
    $html .= '</div>';
    return $html;
}

/**
 * Render question page block (content only, no layout)
 * 
 * @param array $question Question row
 * @param array $answers Array of answer rows (with evidence attached)
 * @return string Content block HTML
 */
function render_truth_page($question, $answers) {
    ob_start();
    include __DIR__ . '/templates/truth-page.php';
    return ob_get_clean();
}

/**
 * ---------------------------------------------------------
 * TRUTH Phase 1 Renderer Functions (Structural Layer Only)
 * ---------------------------------------------------------
 * 
 * These functions accept view models and render placeholder UI.
 * No inference logic, no assertion model, no evidence system.
 * Just the structural rendering layer.
 */

/**
 * Render TRUTH view page
 * 
 * Phase 2: Renders structured UI blocks using view model
 * - TRUTH header
 * - Content summary
 * - References block
 * - Links block
 * - Tags block
 * - Collection block
 * - Questions block
 * - Answers block
 * - Evidence block (placeholder)
 * - Semantic context summary
 * 
 * @param array $viewModel TRUTH view model with slug, title, content, references, links, tags, collection, questions, answers, evidence, semantic_context
 * @return string Content block HTML
 */
function truth_render_view($viewModel) {
    $slug = isset($viewModel['slug']) ? htmlspecialchars($viewModel['slug']) : '';
    $title = isset($viewModel['title']) ? htmlspecialchars($viewModel['title']) : 'TRUTH View';
    $content = isset($viewModel['content']) ? $viewModel['content'] : '';
    $references = isset($viewModel['references']) ? $viewModel['references'] : [];
    $links = isset($viewModel['links']) ? $viewModel['links'] : [];
    $tags = isset($viewModel['tags']) ? $viewModel['tags'] : [];
    $collection = isset($viewModel['collection']) ? $viewModel['collection'] : null;
    $questions = isset($viewModel['questions']) ? $viewModel['questions'] : [];
    $answers = isset($viewModel['answers']) ? $viewModel['answers'] : [];
    $evidence = isset($viewModel['evidence']) ? $viewModel['evidence'] : [];
    $semanticContext = isset($viewModel['semantic_context']) ? $viewModel['semantic_context'] : [];
    
    $html = '<div class="truth-view-container">';
    
    // TRUTH Header
    $html .= truth_render_header($title, $slug);
    
    // Content Summary
    if (!empty($content)) {
        $html .= truth_render_content_summary($content);
    }
    
    // References Block
    if (!empty($references)) {
        $html .= truth_render_references_block($references);
    }
    
    // Links Block
    if (!empty($links)) {
        $html .= truth_render_links_block($links);
    }
    
    // Tags Block
    if (!empty($tags)) {
        $html .= truth_render_tags_block($tags);
    }
    
    // Collection Block
    if ($collection) {
        $html .= truth_render_collection_block($collection);
    }
    
    // Questions Block
    if (!empty($questions)) {
        $html .= truth_render_questions_block($questions);
    }
    
    // Answers Block
    if (!empty($answers)) {
        $html .= truth_render_answers_block($answers);
    }
    
    // Evidence Block (placeholder)
    if (!empty($evidence)) {
        $html .= truth_render_evidence_block($evidence);
    }
    
    // Semantic Context Summary
    if (!empty($semanticContext)) {
        $html .= truth_render_semantic_context_summary($semanticContext);
    }
    
    $html .= '</div>';
    
    return $html;
}

/**
 * Render TRUTH assert page
 * 
 * Phase 2: Renders assertion interface with structured UI blocks
 * 
 * @param array $viewModel TRUTH view model with slug, title, content, references, links, tags, collection, questions, answers, evidence, semantic_context, mode
 * @return string Content block HTML
 */
function truth_render_assert($viewModel) {
    $slug = isset($viewModel['slug']) ? htmlspecialchars($viewModel['slug']) : '';
    $title = isset($viewModel['title']) ? htmlspecialchars($viewModel['title']) : 'TRUTH Assert';
    $content = isset($viewModel['content']) ? $viewModel['content'] : '';
    $questions = isset($viewModel['questions']) ? $viewModel['questions'] : [];
    $answers = isset($viewModel['answers']) ? $viewModel['answers'] : [];
    
    $html = '<div class="truth-assert-container">';
    
    // TRUTH Header
    $html .= truth_render_header($title, $slug);
    
    // Content Summary
    if (!empty($content)) {
        $html .= truth_render_content_summary($content);
    }
    
    // Assertion Interface
    $html .= '<div class="truth-assert-interface">';
    $html .= '<h2>Assertion Interface</h2>';
    $html .= '<p class="truth-phase-note">Phase 2.5: Input scaffolding only. Assertion validation logic will be implemented in later phases.</p>';
    
    // Submission message (Phase 2.5)
    if (isset($viewModel['submission_message']) && !empty($viewModel['submission_message'])) {
        $html .= $viewModel['submission_message'];
    }
    
    // Assertion Input Form (Phase 2.5)
    $html .= truth_render_assertion_form($slug);
    
    // Questions Block (for context)
    if (!empty($questions)) {
        $html .= truth_render_questions_block($questions);
    }
    
    // Answers Block (for context)
    if (!empty($answers)) {
        $html .= truth_render_answers_block($answers);
    }
    
    $html .= '</div>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Render TRUTH evidence view page
 * 
 * Phase 2: Renders evidence interface with structured UI blocks
 * 
 * @param array $viewModel TRUTH view model with slug, title, content, references, links, tags, collection, questions, answers, evidence, semantic_context, mode
 * @return string Content block HTML
 */
function truth_render_evidence_view($viewModel) {
    $slug = isset($viewModel['slug']) ? htmlspecialchars($viewModel['slug']) : '';
    $title = isset($viewModel['title']) ? htmlspecialchars($viewModel['title']) : 'TRUTH Evidence';
    $content = isset($viewModel['content']) ? $viewModel['content'] : '';
    $evidence = isset($viewModel['evidence']) ? $viewModel['evidence'] : [];
    
    $html = '<div class="truth-evidence-container">';
    
    // TRUTH Header
    $html .= truth_render_header($title, $slug);
    
    // Content Summary
    if (!empty($content)) {
        $html .= truth_render_content_summary($content);
    }
    
    // Evidence Input Form (Phase 2.5)
    $html .= '<div class="truth-evidence-interface">';
    $html .= '<h2>Evidence Interface</h2>';
    $html .= '<p class="truth-phase-note">Phase 2.5: Input scaffolding only. Evidence weighting and scoring will be implemented in later phases.</p>';
    
    // Submission message (Phase 2.5)
    if (isset($viewModel['submission_message']) && !empty($viewModel['submission_message'])) {
        $html .= $viewModel['submission_message'];
    }
    
    $html .= truth_render_evidence_form($slug);
    $html .= '</div>';
    
    // Evidence Block (placeholder structure)
    if (!empty($evidence)) {
        $html .= truth_render_evidence_block($evidence);
    }
    
    $html .= '</div>';
    
    return $html;
}

/**
 * ---------------------------------------------------------
 * TRUTH Phase 2 UI Block Renderers
 * ---------------------------------------------------------
 * 
 * Structured UI block renderers for TRUTH view model data.
 * No logic, no inference, just display.
 */

/**
 * Render TRUTH header block
 * 
 * @param string $title Page title
 * @param string $slug Content slug
 * @return string HTML for header block
 */
function truth_render_header($title, $slug) {
    $html = '<div class="truth-header-block">';
    $html .= '<h1 class="truth-title">' . $title . '</h1>';
    $html .= '<p class="truth-slug">Slug: <code>' . htmlspecialchars($slug) . '</code></p>';
    $html .= '</div>';
    return $html;
}

/**
 * Render content summary block
 * 
 * @param string $content Content body
 * @return string HTML for content summary
 */
function truth_render_content_summary($content) {
    // Truncate for summary (first 500 chars)
    $summary = mb_substr(strip_tags($content), 0, 500);
    if (mb_strlen($content) > 500) {
        $summary .= '...';
    }
    
    $html = '<div class="truth-content-summary-block">';
    $html .= '<h2>Content Summary</h2>';
    $html .= '<div class="truth-content-summary">' . nl2br(htmlspecialchars($summary)) . '</div>';
    $html .= '</div>';
    return $html;
}

/**
 * Render references block
 * 
 * @param array $references Array of reference content rows
 * @return string HTML for references block
 */
function truth_render_references_block($references) {
    $html = '<div class="truth-references-block">';
    $html .= '<h2>References</h2>';
    $html .= '<ul class="truth-references-list">';
    
    foreach ($references as $ref) {
        $refTitle = htmlspecialchars($ref['title'] ?? 'Untitled');
        $refSlug = htmlspecialchars($ref['slug'] ?? '');
        $refUrl = $refSlug ? '/' . $refSlug : '#';
        
        $html .= '<li class="truth-reference-item">';
        $html .= '<a href="' . $refUrl . '" class="truth-reference-link">' . $refTitle . '</a>';
        $html .= '</li>';
    }
    
    $html .= '</ul>';
    $html .= '</div>';
    return $html;
}

/**
 * Render links block
 * 
 * @param array $links Array of linked content rows
 * @return string HTML for links block
 */
function truth_render_links_block($links) {
    $html = '<div class="truth-links-block">';
    $html .= '<h2>Linked Content</h2>';
    $html .= '<ul class="truth-links-list">';
    
    foreach ($links as $link) {
        $linkTitle = htmlspecialchars($link['title'] ?? 'Untitled');
        $linkSlug = htmlspecialchars($link['slug'] ?? '');
        $linkUrl = $linkSlug ? '/' . $linkSlug : '#';
        
        $html .= '<li class="truth-link-item">';
        $html .= '<a href="' . $linkUrl . '" class="truth-link-link">' . $linkTitle . '</a>';
        $html .= '</li>';
    }
    
    $html .= '</ul>';
    $html .= '</div>';
    return $html;
}

/**
 * Render tags block
 * 
 * @param array $tags Array of tag strings
 * @return string HTML for tags block
 */
function truth_render_tags_block($tags) {
    $html = '<div class="truth-tags-block">';
    $html .= '<h2>Tags</h2>';
    $html .= '<div class="truth-tags-list">';
    
    foreach ($tags as $tag) {
        $html .= '<span class="truth-tag">' . htmlspecialchars($tag) . '</span>';
    }
    
    $html .= '</div>';
    $html .= '</div>';
    return $html;
}

/**
 * Render collection block
 * 
 * @param array $collection Collection row
 * @return string HTML for collection block
 */
function truth_render_collection_block($collection) {
    $name = htmlspecialchars($collection['name'] ?? 'Unknown Collection');
    $description = isset($collection['description']) ? htmlspecialchars($collection['description']) : '';
    $slug = isset($collection['slug']) ? htmlspecialchars($collection['slug']) : '';
    $url = $slug ? '/collections/' . $slug : '#';
    
    $html = '<div class="truth-collection-block">';
    $html .= '<h2>Collection</h2>';
    $html .= '<div class="truth-collection-info">';
    $html .= '<h3><a href="' . $url . '" class="truth-collection-link">' . $name . '</a></h3>';
    if ($description) {
        $html .= '<p class="truth-collection-description">' . $description . '</p>';
    }
    $html .= '</div>';
    $html .= '</div>';
    return $html;
}

/**
 * Render questions block
 * 
 * @param array $questions Array of question rows
 * @return string HTML for questions block
 */
function truth_render_questions_block($questions) {
    $html = '<div class="truth-questions-block">';
    $html .= '<h2>Related Questions</h2>';
    $html .= '<ul class="truth-questions-list">';
    
    foreach ($questions as $question) {
        $qText = htmlspecialchars($question['question_text'] ?? 'Untitled Question');
        $qType = htmlspecialchars($question['qtype'] ?? '');
        $qSlug = htmlspecialchars($question['slug'] ?? '');
        $qUrl = ($qType && $qSlug) ? '/' . $qType . '/' . $qSlug : '#';
        
        $html .= '<li class="truth-question-item">';
        $html .= '<a href="' . $qUrl . '" class="truth-question-link">' . $qText . '</a>';
        if ($qType) {
            $html .= ' <span class="truth-question-type">(' . $qType . ')</span>';
        }
        $html .= '</li>';
    }
    
    $html .= '</ul>';
    $html .= '</div>';
    return $html;
}

/**
 * Render answers block
 * 
 * @param array $answers Array of answer rows
 * @return string HTML for answers block
 */
function truth_render_answers_block($answers) {
    $html = '<div class="truth-answers-block">';
    $html .= '<h2>Related Answers</h2>';
    $html .= '<ul class="truth-answers-list">';
    
    foreach ($answers as $answer) {
        $aText = htmlspecialchars($answer['answer_text'] ?? '');
        // Truncate long answers
        if (mb_strlen($aText) > 200) {
            $aText = mb_substr($aText, 0, 200) . '...';
        }
        
        $html .= '<li class="truth-answer-item">';
        $html .= '<div class="truth-answer-text">' . nl2br($aText) . '</div>';
        $html .= '</li>';
    }
    
    $html .= '</ul>';
    $html .= '</div>';
    return $html;
}

/**
 * Render evidence block (placeholder structure)
 * 
 * Phase 2: No scoring, no weighting, just display
 * 
 * @param array $evidence Array of evidence placeholder structures
 * @return string HTML for evidence block
 */
function truth_render_evidence_block($evidence) {
    $html = '<div class="truth-evidence-block">';
    $html .= '<h2>Evidence</h2>';
    $html .= '<p class="truth-phase-note">Phase 2: Structural placeholder. Evidence weighting and scoring will be implemented in later phases.</p>';
    
    if (empty($evidence)) {
        $html .= '<p class="truth-empty">No evidence found.</p>';
    } else {
        $html .= '<ul class="truth-evidence-list">';
        
        foreach ($evidence as $ev) {
            $source = htmlspecialchars($ev['source'] ?? 'unknown');
            $type = htmlspecialchars($ev['type'] ?? 'reference');
            $evidenceText = htmlspecialchars($ev['evidence_text'] ?? '');
            
            $html .= '<li class="truth-evidence-item">';
            $html .= '<div class="truth-evidence-source">Source: <code>' . $source . '</code></div>';
            $html .= '<div class="truth-evidence-type">Type: ' . $type . '</div>';
            if ($evidenceText) {
                $html .= '<div class="truth-evidence-text">' . nl2br($evidenceText) . '</div>';
            }
            $html .= '</li>';
        }
        
        $html .= '</ul>';
    }
    
    $html .= '</div>';
    return $html;
}

/**
 * Render semantic context summary
 * 
 * @param array $semanticContext Semantic context from ConnectionsService
 * @return string HTML for semantic context summary
 */
function truth_render_semantic_context_summary($semanticContext) {
    $atoms = isset($semanticContext['atoms']) ? $semanticContext['atoms'] : [];
    $relatedContent = isset($semanticContext['related_content']) ? $semanticContext['related_content'] : [];
    
    if (empty($atoms) && empty($relatedContent)) {
        return '';
    }
    
    $html = '<div class="truth-semantic-context-block">';
    $html .= '<h2>Semantic Context</h2>';
    
    if (!empty($atoms)) {
        $html .= '<div class="truth-atoms-summary">';
        $html .= '<h3>Connected Atoms</h3>';
        $html .= '<ul class="truth-atoms-list">';
        foreach ($atoms as $atom) {
            $atomLabel = htmlspecialchars($atom['atom_label'] ?? $atom['label'] ?? 'Atom');
            $html .= '<li class="truth-atom-item">' . $atomLabel . '</li>';
        }
        $html .= '</ul>';
        $html .= '</div>';
    }
    
    if (!empty($relatedContent)) {
        $html .= '<div class="truth-related-content-summary">';
        $html .= '<h3>Related Content</h3>';
        $html .= '<ul class="truth-related-content-list">';
        foreach ($relatedContent as $related) {
            $relatedTitle = htmlspecialchars($related['title'] ?? 'Untitled');
            $relatedId = isset($related['content_id']) ? (int)$related['content_id'] : 0;
            $relatedUrl = $relatedId > 0 ? '/content.php?id=' . $relatedId : '#';
            $html .= '<li class="truth-related-item"><a href="' . $relatedUrl . '">' . $relatedTitle . '</a></li>';
        }
        $html .= '</ul>';
        $html .= '</div>';
    }
    
    $html .= '</div>';
    return $html;
}

/**
 * ---------------------------------------------------------
 * TRUTH Phase 2.5 Input Forms
 * ---------------------------------------------------------
 * 
 * Input scaffolding for assertions and evidence.
 * No logic, no scoring, no inference - just forms.
 */

/**
 * Render assertion input form
 * 
 * Phase 2.5: Procedural form with no JavaScript, no dynamic includes, no inference logic
 * 
 * @param string $slug Current content slug
 * @return string HTML for assertion form
 */
function truth_render_assertion_form($slug) {
    $formAction = '/truth/assert/' . htmlspecialchars($slug);
    $currentSlug = htmlspecialchars($slug);
    
    $html = '<div class="truth-assertion-form">';
    $html .= '<form method="POST" action="' . $formAction . '" class="truth-form">';
    $html .= '<input type="hidden" name="truth_action" value="assert">';
    $html .= '<input type="hidden" name="content_slug" value="' . $currentSlug . '">';
    
    // Assertion text (required)
    $html .= '<div class="truth-form-group">';
    $html .= '<label for="assertion_text" class="truth-form-label">Assertion Text <span class="truth-required">*</span></label>';
    $html .= '<textarea id="assertion_text" name="assertion_text" class="truth-form-textarea" rows="6" required></textarea>';
    $html .= '<small class="truth-form-help">Enter the assertion you want to make about this content.</small>';
    $html .= '</div>';
    
    // Optional source slug
    $html .= '<div class="truth-form-group">';
    $html .= '<label for="source_slug" class="truth-form-label">Source Slug (optional)</label>';
    $html .= '<input type="text" id="source_slug" name="source_slug" class="truth-form-input" placeholder="content-slug">';
    $html .= '<small class="truth-form-help">Optional: Reference another content item as the source.</small>';
    $html .= '</div>';
    
    // Optional evidence summary
    $html .= '<div class="truth-form-group">';
    $html .= '<label for="evidence_summary" class="truth-form-label">Evidence Summary (optional)</label>';
    $html .= '<textarea id="evidence_summary" name="evidence_summary" class="truth-form-textarea" rows="4"></textarea>';
    $html .= '<small class="truth-form-help">Optional: Brief summary of evidence supporting this assertion.</small>';
    $html .= '</div>';
    
    // Submit button
    $html .= '<div class="truth-form-group">';
    $html .= '<button type="submit" class="truth-form-submit">Submit Assertion</button>';
    $html .= '</div>';
    
    $html .= '</form>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Render evidence input form
 * 
 * Phase 2.5: Procedural form with no JavaScript, no dynamic includes, no inference logic
 * 
 * @param string $slug Current content slug
 * @return string HTML for evidence form
 */
function truth_render_evidence_form($slug) {
    $formAction = '/truth/evidence/' . htmlspecialchars($slug);
    $currentSlug = htmlspecialchars($slug);
    
    $html = '<div class="truth-evidence-form">';
    $html .= '<form method="POST" action="' . $formAction . '" class="truth-form">';
    $html .= '<input type="hidden" name="truth_action" value="evidence">';
    $html .= '<input type="hidden" name="content_slug" value="' . $currentSlug . '">';
    
    // Evidence type dropdown
    $html .= '<div class="truth-form-group">';
    $html .= '<label for="evidence_type" class="truth-form-label">Evidence Type</label>';
    $html .= '<select id="evidence_type" name="evidence_type" class="truth-form-select" required>';
    $html .= '<option value="reference">Reference</option>';
    $html .= '<option value="link">Link</option>';
    $html .= '<option value="tag">Tag</option>';
    $html .= '<option value="manual">Manual</option>';
    $html .= '</select>';
    $html .= '<small class="truth-form-help">Select the type of evidence.</small>';
    $html .= '</div>';
    
    // Evidence source slug
    $html .= '<div class="truth-form-group">';
    $html .= '<label for="evidence_source" class="truth-form-label">Evidence Source Slug</label>';
    $html .= '<input type="text" id="evidence_source" name="evidence_source" class="truth-form-input" placeholder="content-slug" required>';
    $html .= '<small class="truth-form-help">Enter the slug of the content that provides this evidence.</small>';
    $html .= '</div>';
    
    // Evidence summary
    $html .= '<div class="truth-form-group">';
    $html .= '<label for="evidence_summary" class="truth-form-label">Evidence Summary</label>';
    $html .= '<textarea id="evidence_summary" name="evidence_summary" class="truth-form-textarea" rows="6" required></textarea>';
    $html .= '<small class="truth-form-help">Describe the evidence and how it relates to this content.</small>';
    $html .= '</div>';
    
    // Submit button
    $html .= '<div class="truth-form-group">';
    $html .= '<button type="submit" class="truth-form-submit">Submit Evidence</button>';
    $html .= '</div>';
    
    $html .= '</form>';
    $html .= '</div>';
    
    return $html;
}

/**
 * ---------------------------------------------------------
 * TRUTH Version 4.0.11: Tab Content Rendering
 * ---------------------------------------------------------
 * 
 * Functions to render content lists and individual content items.
 */

/**
 * Render content list for a tab
 * 
 * Version 4.0.11: Renders a list of content items mapped to a tab.
 * 
 * @param array $tab Tab row
 * @param array $contentItems Array of content rows
 * @return string HTML for content list
 */
function truth_render_tab_content_list($tab, $contentItems) {
    $tabName = htmlspecialchars($tab['name'] ?? 'Unknown Tab');
    $tabSlug = htmlspecialchars($tab['slug'] ?? '');
    $collectionId = isset($tab['collection_id']) ? (int)$tab['collection_id'] : 0;
    
    $html = '<div class="truth-tab-content-list">';
    $html .= '<h1 class="truth-tab-title">' . $tabName . '</h1>';
    
    if (empty($contentItems)) {
        $html .= '<p class="truth-empty-message">No content items found for this tab.</p>';
    } else {
        $html .= '<ul class="truth-content-list">';
        
        foreach ($contentItems as $item) {
            $title = htmlspecialchars($item['title'] ?? 'Untitled');
            $slug = htmlspecialchars($item['slug'] ?? '');
            $excerpt = isset($item['excerpt']) ? htmlspecialchars($item['excerpt']) : '';
            
            // Build content URL using LUPOPEDIA_PUBLIC_PATH
            $contentUrl = (defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '') . '/collection/' . $collectionId . '/content/' . $slug;
            
            $html .= '<li class="truth-content-item">';
            $html .= '<h2><a href="' . $contentUrl . '" class="truth-content-link">' . $title . '</a></h2>';
            if ($excerpt) {
                $html .= '<p class="truth-content-excerpt">' . $excerpt . '</p>';
            }
            $html .= '</li>';
        }
        
        $html .= '</ul>';
    }
    
    $html .= '</div>';
    
    return $html;
}

/**
 * Render markdown content item
 * 
 * Version 4.0.11: Renders a content item with markdown rendering.
 * 
 * @param array $content Content row
 * @return string HTML for content display
 */
function truth_render_content_item($content) {
    // Ensure markdown renderer is loaded
    if (!function_exists('render_markdown')) {
        $markdown_path = LUPOPEDIA_PATH . '/lupo-includes/modules/content/renderers/render-markdown.php';
        if (file_exists($markdown_path)) {
            require_once $markdown_path;
        }
    }
    
    $title = htmlspecialchars($content['title'] ?? 'Untitled');
    $body = $content['body'] ?? '';
    $format = isset($content['format']) ? strtolower($content['format']) : 'markdown';
    $content_type = isset($content['content_type']) ? strtolower($content['content_type']) : '';
    
    // Determine if content is markdown - check both format and content_type fields
    $is_markdown = ($format === 'markdown' || $content_type === 'markdown');
    
    $html = '<div class="truth-content-item-view">';
    $html .= '<h1 class="truth-content-title">' . $title . '</h1>';
    
    // Debug: Always show visible debug box
    $html .= '<div style="background: #e3f2fd; padding: 10px; margin: 10px 0; border: 2px solid #2196F3; font-size: 12px;">';
    $html .= '<strong>DEBUG: Content Rendering</strong><br>';
    $html .= 'Format: ' . htmlspecialchars($format) . '<br>';
    $html .= 'Content Type: ' . htmlspecialchars($content_type) . '<br>';
    $html .= 'Is Markdown: ' . ($is_markdown ? 'YES' : 'NO') . '<br>';
    $html .= 'Body Length: ' . strlen($body) . ' bytes<br>';
    $html .= 'render_markdown() exists: ' . (function_exists('render_markdown') ? 'YES' : 'NO') . '<br>';
    $html .= '</div>';
    
    // Debug: Show format info in HTML (can be removed later)
    if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
        $html .= '<!-- DEBUG: format=' . htmlspecialchars($format) . ', content_type=' . htmlspecialchars($content_type) . ', body_length=' . strlen($body) . ' -->';
    }
    
    $html .= '<div class="truth-content-body">';
    
    // Debug: Log format detection
    if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
        error_log('TRUTH render: format=' . $format . ', content_type=' . $content_type . ', is_markdown=' . ($is_markdown ? 'true' : 'false') . ', body_length=' . strlen($body));
    }
    
    if ($is_markdown && !empty($body)) {
        // Use markdown renderer if available
        if (function_exists('render_markdown')) {
            // Convert literal \r\n and \n strings to actual newlines
            // This handles content stored with SQL-escaped newlines
            $body_normalized = str_replace(['\\r\\n', '\\n', '\\r'], ["\n", "\n", "\n"], $body);
            // Also handle actual \r\n sequences
            $body_normalized = str_replace("\r\n", "\n", $body_normalized);
            // Normalize to Unix line endings
            $body_normalized = preg_replace('/\r\n|\r/', "\n", $body_normalized);
            
            // render_markdown returns HTML, so we output it directly (not escaped)
            $rendered_html = render_markdown($body_normalized);
            
            // Debug: Log if rendering succeeded
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log('TRUTH render: Markdown rendered, output length: ' . strlen($rendered_html));
            }
            
            $html .= $rendered_html;
        } else {
            // Fallback: basic markdown-like rendering
            if (defined('LUPOPEDIA_DEBUG') && LUPOPEDIA_DEBUG) {
                error_log('TRUTH render: WARNING - render_markdown function not found, using fallback');
            }
            $html .= '<pre>' . htmlspecialchars($body) . '</pre>';
        }
    } else if (!empty($body)) {
        // HTML or other formats - output directly (assumed to be safe HTML)
        $html .= $body;
    } else {
        $html .= '<p><em>No content available.</em></p>';
    }
    
    $html .= '</div>';
    $html .= '</div>';
    
    return $html;
}

?>
