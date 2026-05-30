<?php
/**
 * Thread review body contract (LILITH / CHANNEL_ARTIFACT_ROUTING §7).
 */
require_once __DIR__ . '/../../lupo-includes/classes/Lupo_Channel_Artifact_Validator.php';

$failed = 0;
function ok($cond, $msg) {
    global $failed;
    if (!$cond) {
        echo "FAIL: $msg\n";
        $failed++;
    } else {
        echo "PASS: $msg\n";
    }
}

$long = str_repeat("x", 520) . "\n## A\n\nx\n## B\n\nx\n## C\n\nx\n";
ok(Lupo_Channel_Artifact_Validator::validateThreadReviewBody($long, 'review', null) === null, 'review + 500+ chars + 3 ##');

ok(Lupo_Channel_Artifact_Validator::validateThreadReviewBody('short', 'review', null) !== null, 'review too short rejected');

ok(Lupo_Channel_Artifact_Validator::validateThreadReviewBody($long, 'thread', null) === null, 'thread type not enforced');

$meta = json_encode(array('artifact_kind' => 'review'));
$long2 = str_repeat("y", 520) . "\n## One\n## Two\n## Three\n";
ok(Lupo_Channel_Artifact_Validator::validateThreadReviewBody($long2, 'thread', $meta) === null, 'meta artifact_kind review enforced OK');

$badSections = str_repeat("p", 500);
ok(Lupo_Channel_Artifact_Validator::validateThreadReviewBody($badSections, 'review', null) !== null, 'review without ## rejected');

$yamlShort = "---\nartifact_kind: review\n---\n\n## A\n## B\n## C\nshort";
ok(Lupo_Channel_Artifact_Validator::validateThreadReviewBody($yamlShort, 'review', null) !== null, 'review with short body after YAML rejected');

$helpMeta = json_encode(array('artifact_kind' => 'help_response'));
$helpOk = "# Title\n\n" . str_repeat("a", 180) . "\n## One\nx\n## Two\nx\n## Three\nx\n";
ok(Lupo_Channel_Artifact_Validator::validateThreadHelpResponseBody($helpOk, 'thread', $helpMeta) === null, 'help_response via meta OK');
ok(Lupo_Channel_Artifact_Validator::validateThreadHelpResponseBody('short', 'thread', $helpMeta) !== null, 'help_response short rejected');
ok(Lupo_Channel_Artifact_Validator::validateThreadPostBody($helpOk, 'thread', $helpMeta) === null, 'validateThreadPostBody help_response OK');

require_once __DIR__ . '/../../lupo-includes/classes/ChannelArtifactValidator.php';
$lilithPath = __DIR__ . '/../../lupo-channels/42/threads/1001/20260317_232500_lilith_channel-system-help-response.md';
if (is_readable($lilithPath)) {
    ok(ChannelArtifactValidator::validateThreadArtifact($lilithPath) === null, 'LILITH help_response artifact path validates');
}

echo $failed ? "channel_thread_review_body_test: FAILED ($failed)\n" : "channel_thread_review_body_test: OK\n";
exit($failed ? 1 : 0);
