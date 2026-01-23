<?php
/**
 * Terminal AI Controller
 * 
 * HTTP controller for Terminal AI command execution endpoints.
 * 
 * @package App\Http\Controllers
 * @version 4.0.101
 * @author Captain Wolfie
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TerminalAI\Services\TerminalAIService;

class TerminalAIController extends Controller
{
    public function execute(Request $request)
    {
        $service = new TerminalAIService();
        return response()->json([
            "output" => $service->execute($request->input("command", ""))
        ]);
    }
}
