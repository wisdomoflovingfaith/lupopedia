<?php
/**
 * Terminal AI Routes
 * 
 * Defines HTTP routes for Terminal AI subsystem.
 * 
 * @package Routes
 * @version 4.0.101
 * @author Captain Wolfie
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TerminalAIController;

Route::post('/terminal/execute', [TerminalAIController::class, 'execute']);

Route::get('/terminal/utc', function () {
    $service = new \App\TerminalAI\Services\TerminalAIService();
    return response()->json([
        "utc" => $service->utc()
    ]);
});
