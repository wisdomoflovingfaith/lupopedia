<?php
/**
 * Dialog System Test Script
 * 
 * Tests the dialog system API endpoint by sending a test message.
 * 
 * Usage: php test-dialog-send.php
 */

// Test configuration
$apiUrl = 'http://localhost/api/dialog/send-message.php';
$testActorId = 1; // Assuming actor_id 1 exists (human user)
$testContent = "Hello, this is a test message from the dialog system implementation.";

// Build test payload
$payload = [
    'actor_id' => $testActorId,
    'content' => $testContent,
    'mood_rgb' => '00FF00', // Green (positive)
    'message_type' => 'text'
];

// Initialize cURL
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json'
]);

// Execute request
echo "Sending test message...\n";
echo "URL: {$apiUrl}\n";
echo "Payload: " . json_encode($payload, JSON_PRETTY_PRINT) . "\n\n";

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

// Display results
echo "HTTP Code: {$httpCode}\n";
if ($error) {
    echo "cURL Error: {$error}\n";
}
echo "\nResponse:\n";
$responseData = json_decode($response, true);
if ($responseData) {
    echo json_encode($responseData, JSON_PRETTY_PRINT) . "\n";
    
    if (isset($responseData['success']) && $responseData['success']) {
        echo "\n✅ Test PASSED - Message sent successfully!\n";
        echo "Response Message ID: {$responseData['response_message_id']}\n";
        echo "Response Text: {$responseData['response_text']}\n";
        echo "From Actor: {$responseData['from_actor']}\n";
        echo "To Actor: {$responseData['to_actor']}\n";
    } else {
        echo "\n❌ Test FAILED\n";
        if (isset($responseData['error'])) {
            echo "Error: {$responseData['error']}\n";
        }
    }
} else {
    echo $response . "\n";
    echo "\n❌ Test FAILED - Invalid JSON response\n";
}

?>
