<?php

/**
 * Terminal AI route map — plain PHP. No Laravel.
 * Returns [method => path => [controllerClass, methodName]].
 * Controllers receive array input; return array; caller sends JSON.
 */

return [
    'POST' => [
        '/terminal/execute' => [\App\Http\Controllers\TerminalAIController::class, 'execute'],
    ],
    'GET' => [
        '/terminal/utc' => null, // TerminalAIService::utc() -> return ['utc' => ...]
    ],
];
