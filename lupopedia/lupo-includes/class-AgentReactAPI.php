<?php

class AgentReactAPI
{
    protected ReactActionDispatcher $dispatcher;

    public function __construct(ReactActionDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function call(int $agentId, int $domainId, string $action, array $payload = []): array
    {
        $request = new ReactActionRequest($agentId, $domainId, $action, $payload);
        return $this->dispatcher->dispatch($request);
    }
}

?>