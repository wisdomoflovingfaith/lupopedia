<?php
/**
---
wolfie.headers.version: "4.0.5"
dialog:
  speaker: Wolfie
  target: truth-page-template
  message: "Created TRUTH page template: renders question, answers with evidence and sources. Returns only the content block, wrapped by main layout."
  mood: "336699"
tags:
  categories: ["template", "truth", "ui"]
  collections: ["core-modules"]
  channels: ["dev"]
file:
  title: "TRUTH Page Template"
  description: "Template for TRUTH question pages: question, answers, evidence, sources."
  version: "4.0.5"
  status: active
  author: "Eric Robin Gerdes"
---
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. truth-page.php cannot be called directly.");
}

/**
 * ---------------------------------------------------------
 * TRUTH Page Template - Content Block Only
 * ---------------------------------------------------------
 * 
 * This template returns ONLY the content block, without <html>, <head>, CSS, or JS.
 * It is designed to be wrapped by a main layout template.
 */

// Extract question fields with defaults
$question_id = isset($question['truth_question_id']) ? $question['truth_question_id'] : 0;
$qtype = isset($question['qtype']) ? $question['qtype'] : 'unknown';
$question_text = isset($question['question_text']) ? $question['question_text'] : '';
$format = isset($question['format']) ? $question['format'] : 'text';
$format_override = isset($question['format_override']) ? $question['format_override'] : null;
$view_count = isset($question['view_count']) ? $question['view_count'] : 0;
$answer_count = isset($question['answer_count']) ? $question['answer_count'] : 0;
$is_featured = isset($question['is_featured']) && $question['is_featured'] == 1;
$is_verified = isset($question['is_verified']) && $question['is_verified'] == 1;

// Render question text
require_once __DIR__ . '/../truth-render.php';
$rendered_question = truth_render_question_text($question_text, $format, $format_override);

?>
<div class="truth-page">
    <header class="truth-header">
        <div class="question-meta">
            <span class="question-type"><?= strtoupper(htmlspecialchars($qtype)) ?></span>
            <?php if ($is_featured): ?>
                <span class="featured-badge">⭐ Featured</span>
            <?php endif; ?>
            <?php if ($is_verified): ?>
                <span class="verified-badge">✓ Verified</span>
            <?php endif; ?>
        </div>
        <h1 class="question-title"><?= $rendered_question ?></h1>
        <div class="question-stats">
            <span class="view-count">Views: <?= number_format($view_count) ?></span>
            <span class="answer-count">Answers: <?= number_format($answer_count) ?></span>
        </div>
    </header>

    <main class="truth-content">
        <?php if (!empty($answers)): ?>
            <section class="answers-section">
                <h2>Answers</h2>
                <?php foreach ($answers as $answer): ?>
                    <?= truth_render_answer($answer) ?>
                <?php endforeach; ?>
            </section>
        <?php else: ?>
            <div class="no-answers">
                <p>No answers yet for this question.</p>
            </div>
        <?php endif; ?>
    </main>
</div>
