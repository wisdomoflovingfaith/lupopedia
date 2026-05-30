<?php
/**
 * wolfie.header.identity: qa-index
 * wolfie.header.placement: /includes/modules/qa/views/index.php
 * wolfie.header.version: 4.0.29
 * wolfie.header.dialog:
 *   speaker: CASCADE
 *   target: qa-index
 *   message: "Created proper QA index page with navigation, search, and question listing. Fixed CSS image paths for proper rendering."
 * wolfie.header.mood.label: helpful
 * wolfie.header.mood.rgb: "336699"
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded. qa/index.php cannot be called directly.");
}

// Load database
$db = isset($GLOBALS['mydatabase']) ? $GLOBALS['mydatabase'] : null;
$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : 'lupo_';

function qa_format_ymdhis_to_date($ymdhis)
{
    $raw = preg_replace('/[^0-9]/', '', (string) $ymdhis);
    if (strlen($raw) < 8) {
        return 'Unknown date';
    }
    $y = (int) substr($raw, 0, 4);
    $m = (int) substr($raw, 4, 2);
    $d = (int) substr($raw, 6, 2);
    if ($m < 1 || $m > 12 || $d < 1 || $d > 31) {
        return 'Unknown date';
    }
    static $months = null;
    if ($months === null) {
        $months = array(
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
        );
    }
    return $months[$m] . ' ' . $d . ', ' . $y;
}

// Get recent questions
$recent_questions = array();
if ($db) {
    try {
        $stmt = $db->prepare("SELECT * FROM {$table_prefix}questions WHERE is_deleted = 0 ORDER BY created_ymdhis DESC, question_id DESC LIMIT 10");
        $stmt->execute();
        $recent_questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        // Handle database error gracefully
        $recent_questions = array();
    }
}

?>
<div class="qa-container">
    <div class="qa-header">
        <h1>Questions & Answers</h1>
        <p class="qa-subtitle">Browse and search the Lupopedia knowledge base</p>
    </div>
    
    <div class="qa-search">
        <form method="GET" action="<?php echo LUPOPEDIA_PUBLIC_PATH; ?>/qa/search">
            <input type="text" name="q" placeholder="Search questions..." class="qa-search-input">
            <button type="submit" class="qa-search-button">Search</button>
        </form>
    </div>
    
    <div class="qa-content">
        <div class="qa-section">
            <h2>Recent Questions</h2>
            <?php if (!empty($recent_questions)): ?>
                <div class="qa-question-list">
                    <?php foreach ($recent_questions as $question): ?>
                        <div class="qa-question-item">
                            <h3><a href="<?php echo LUPOPEDIA_PUBLIC_PATH; ?>/qa/<?php echo htmlspecialchars($question['slug']); ?>"><?php echo htmlspecialchars($question['question_text']); ?></a></h3>
                            <p class="qa-question-meta">
                                Asked on <?php echo qa_format_ymdhis_to_date(isset($question['created_ymdhis']) ? $question['created_ymdhis'] : ''); ?>
                                <?php if (!empty($question['category'])): ?>
                                    in <?php echo htmlspecialchars($question['category']); ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="qa-empty">No questions found in the database.</p>
            <?php endif; ?>
        </div>
        
        <div class="qa-section">
            <h2>Question Categories</h2>
            <div class="qa-categories">
                <div class="qa-category">
                    <h3><a href="<?php echo LUPOPEDIA_PUBLIC_PATH; ?>/qa/category/what">What</a></h3>
                    <p>Questions about definitions and descriptions</p>
                </div>
                <div class="qa-category">
                    <h3><a href="<?php echo LUPOPEDIA_PUBLIC_PATH; ?>/qa/category/who">Who</a></h3>
                    <p>Questions about people and identities</p>
                </div>
                <div class="qa-category">
                    <h3><a href="<?php echo LUPOPEDIA_PUBLIC_PATH; ?>/qa/category/where">Where</a></h3>
                    <p>Questions about locations and places</p>
                </div>
                <div class="qa-category">
                    <h3><a href="<?php echo LUPOPEDIA_PUBLIC_PATH; ?>/qa/category/when">When</a></h3>
                    <p>Questions about time and chronology</p>
                </div>
                <div class="qa-category">
                    <h3><a href="<?php echo LUPOPEDIA_PUBLIC_PATH; ?>/qa/category/why">Why</a></h3>
                    <p>Questions about reasons and causes</p>
                </div>
                <div class="qa-category">
                    <h3><a href="<?php echo LUPOPEDIA_PUBLIC_PATH; ?>/qa/category/how">How</a></h3>
                    <p>Questions about processes and methods</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.qa-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.qa-header {
    text-align: center;
    margin-bottom: 40px;
}

.qa-header h1 {
    color: #252f32;
    font-size: 2.5rem;
    margin-bottom: 10px;
}

.qa-subtitle {
    color: #666;
    font-size: 1.2rem;
}

.qa-search {
    text-align: center;
    margin-bottom: 40px;
}

.qa-search-input {
    width: 300px;
    padding: 12px;
    border: 2px solid #ddd;
    border-radius: 6px;
    font-size: 16px;
}

.qa-search-button {
    padding: 12px 24px;
    background-color: #252f32;
    color: white;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    margin-left: 10px;
}

.qa-search-button:hover {
    background-color: #3a4548;
}

.qa-section {
    margin-bottom: 40px;
}

.qa-section h2 {
    color: #252f32;
    border-bottom: 2px solid #252f32;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.qa-question-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.qa-question-item {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #252f32;
}

.qa-question-item h3 {
    margin-bottom: 10px;
}

.qa-question-item h3 a {
    color: #252f32;
    text-decoration: none;
}

.qa-question-item h3 a:hover {
    text-decoration: underline;
}

.qa-question-meta {
    color: #666;
    font-size: 0.9rem;
}

.qa-categories {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.qa-category {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.qa-category h3 {
    margin-bottom: 10px;
}

.qa-category h3 a {
    color: #252f32;
    text-decoration: none;
}

.qa-category h3 a:hover {
    text-decoration: underline;
}

.qa-category p {
    color: #666;
}

.qa-empty {
    text-align: center;
    color: #666;
    font-style: italic;
}
</style>
