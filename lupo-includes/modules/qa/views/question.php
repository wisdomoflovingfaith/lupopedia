<?php
if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
	die('Config not loaded. qa/question.php cannot be called directly.');
}

$question_text = isset($question['question_text']) ? $question['question_text'] : 'Question';
$question_slug = isset($question['slug']) ? $question['slug'] : $slug;
?>

<div class="qa-question-container">
	<div class="qa-breadcrumb">
		<a href="<?php echo htmlspecialchars(LUPOPEDIA_PUBLIC_PATH); ?>/qa/">Q/A</a>
		<span>/</span>
		<span><?php echo htmlspecialchars($question_slug); ?></span>
	</div>

	<h1 class="qa-question-title"><?php echo htmlspecialchars($question_text); ?></h1>

	<?php if (!empty($answers)): ?>
		<div class="qa-answer-list">
			<?php foreach ($answers as $answer): ?>
				<article class="qa-answer-item">
					<div class="qa-answer-body"><?php echo nl2br(htmlspecialchars(isset($answer['answer_text']) ? $answer['answer_text'] : '')); ?></div>
				</article>
			<?php endforeach; ?>
		</div>
	<?php else: ?>
		<p class="qa-no-answers">No answers are available for this question yet.</p>
	<?php endif; ?>
</div>

<style>
.qa-question-container {
	max-width: 900px;
	margin: 0 auto;
	padding: 20px;
}

.qa-breadcrumb {
	margin-bottom: 16px;
	color: #555;
}

.qa-breadcrumb a {
	color: #252f32;
	text-decoration: none;
}

.qa-breadcrumb span {
	margin: 0 6px;
}

.qa-question-title {
	color: #252f32;
	margin-bottom: 20px;
}

.qa-answer-list {
	display: flex;
	flex-direction: column;
	gap: 14px;
}

.qa-answer-item {
	background: #f8f9fa;
	border: 1px solid #ddd;
	border-left: 4px solid #252f32;
	border-radius: 8px;
	padding: 16px;
}

.qa-answer-body {
	color: #222;
	line-height: 1.6;
}

.qa-no-answers {
	color: #666;
	font-style: italic;
}
</style>
