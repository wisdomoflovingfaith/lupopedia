<?php

class ReactActionDispatcher
{
    protected ReactActionValidator $validator;

    public function __construct(ReactActionValidator $validator)
    {
        $this->validator = $validator;
    }

    public function dispatch(ReactActionRequest $request): array
    {
        if (!$this->validator->validate($request)) {
            return [
                'success' => false,
                'error'   => 'Agent not authorized to call React actions.'
            ];
        }

        // This is where you push the action to your frontend.
        // Could be WebSocket, SSE, REST endpoint, etc.
        return [
            'success' => true,
            'action'  => $request->action,
            'payload' => $request->payload
        ];
    }
}

?>