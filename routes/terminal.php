<?php

/**
 * DEPRECATED: Laravel route file. This project does not use Laravel.
 *
 * Use the plain PHP route map instead:
 *   $routes = require __DIR__ . '/terminal_routes.php';
 *
 * Wire POST /terminal/execute to TerminalAIController::execute(array $input).
 * Wire GET /terminal/utc to TerminalAIService::utc() and return ['utc' => ...].
 *
 * Do not use Illuminate\Support\Facades\Route.
 */

trigger_error('routes/terminal.php is deprecated; use terminal_routes.php and your own router', E_USER_DEPRECATED);

return require __DIR__ . '/terminal_routes.php';
