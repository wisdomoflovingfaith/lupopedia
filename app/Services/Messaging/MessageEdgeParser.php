<?php
/**
 * MessageEdgeParser
 *
 * Pure deterministic parser:
 * - no DB access
 * - no side effects
 * - same input -> same output
 *
 * Output ordering is fixed:
 * thread -> actor -> artifact -> task -> commands
 */
class MessageEdgeParser
{
    public function parse($messageText, $sourceType, $sourceId)
    {
        $text = (string) $messageText;

        $threadEdges = $this->parseThreadReferences($text);
        $actorEdges = $this->parseActorMentions($text);
        $artifactEdges = $this->parseArtifactLinks($text);
        $taskEdges = $this->parseTaskReferences($text);
        $commandEdges = $this->parseCommands($text);

        $edges = array();
        $edges = array_merge($edges, $threadEdges);
        $edges = array_merge($edges, $actorEdges);
        $edges = array_merge($edges, $artifactEdges);
        $edges = array_merge($edges, $taskEdges);
        $edges = array_merge($edges, $commandEdges);

        return $edges;
    }

    private function parseThreadReferences($text)
    {
        $edges = array();
        $seen = array();
        // Thread references are hash tokens without dot/path suffixes.
        if (preg_match_all('/#([A-Za-z0-9_-]+)(?![A-Za-z0-9_.-])/', $text, $matches)) {
            foreach ($matches[1] as $targetId) {
                $key = 'thread|reference|' . $targetId;
                if (isset($seen[$key])) {
                    continue;
                }
                $seen[$key] = true;
                $edges[] = $this->buildEdge('thread', $targetId, 'reference', 'both');
            }
        }
        return $edges;
    }

    private function parseActorMentions($text)
    {
        $edges = array();
        $seen = array();
        if (preg_match_all('/@([A-Za-z0-9_-]+)/', $text, $matches)) {
            foreach ($matches[1] as $targetId) {
                $key = 'actor|reference|' . $targetId;
                if (isset($seen[$key])) {
                    continue;
                }
                $seen[$key] = true;
                $edges[] = $this->buildEdge('actor', $targetId, 'reference', 'both');
            }
        }
        return $edges;
    }

    private function parseArtifactLinks($text)
    {
        $edges = array();
        $seen = array();
        if (preg_match_all('/\[[^\]]+\]\(([^)]+)\)/', $text, $matches)) {
            foreach ($matches[1] as $targetId) {
                $targetId = trim((string) $targetId);
                if ($targetId === '') {
                    continue;
                }
                $key = 'artifact|reference|' . $targetId;
                if (isset($seen[$key])) {
                    continue;
                }
                $seen[$key] = true;
                $edges[] = $this->buildEdge('artifact', $targetId, 'reference', 'both');
            }
        }
        return $edges;
    }

    private function parseTaskReferences($text)
    {
        $edges = array();
        $seen = array();
        if (preg_match_all('/\b(TG-[0-9]+)\b/', $text, $matches)) {
            foreach ($matches[1] as $targetId) {
                $key = 'task|reference|' . $targetId;
                if (isset($seen[$key])) {
                    continue;
                }
                $seen[$key] = true;
                $edges[] = $this->buildEdge('task', $targetId, 'reference', 'both');
            }
        }
        return $edges;
    }

    private function parseCommands($text)
    {
        $edges = array();
        $edges = array_merge($edges, $this->parseAssignCommands($text));
        $edges = array_merge($edges, $this->parseDependsCommands($text));
        $edges = array_merge($edges, $this->parseProducesCommands($text));
        $edges = array_merge($edges, $this->parseBlockCommands($text));
        return $edges;
    }

    private function parseAssignCommands($text)
    {
        $edges = array();
        $seen = array();
        if (preg_match_all('/\/assign\s+@([A-Za-z0-9_-]+)/i', $text, $matches)) {
            foreach ($matches[1] as $targetId) {
                $key = 'actor|implements|' . $targetId;
                if (isset($seen[$key])) {
                    continue;
                }
                $seen[$key] = true;
                $edges[] = $this->buildEdge('actor', $targetId, 'implements', 'fwd');
            }
        }
        return $edges;
    }

    private function parseDependsCommands($text)
    {
        $edges = array();
        $seen = array();
        if (preg_match_all('/\/depends\s+#([A-Za-z0-9_-]+)/i', $text, $matches)) {
            foreach ($matches[1] as $targetId) {
                $key = 'thread|dependency|' . $targetId;
                if (isset($seen[$key])) {
                    continue;
                }
                $seen[$key] = true;
                $edges[] = $this->buildEdge('thread', $targetId, 'dependency', 'fwd');
            }
        }
        return $edges;
    }

    private function parseProducesCommands($text)
    {
        $edges = array();
        $seen = array();
        if (preg_match_all('/\/produces\s+#([A-Za-z0-9_.\/-]+)/i', $text, $matches)) {
            foreach ($matches[1] as $targetId) {
                $key = 'artifact|contains|' . $targetId;
                if (isset($seen[$key])) {
                    continue;
                }
                $seen[$key] = true;
                $edges[] = $this->buildEdge('artifact', $targetId, 'contains', 'fwd');
            }
        }
        return $edges;
    }

    private function parseBlockCommands($text)
    {
        $edges = array();
        $seen = array();
        if (preg_match_all('/\/block\s+@([A-Za-z0-9_-]+)/i', $text, $matches)) {
            foreach ($matches[1] as $targetId) {
                $key = 'actor|contradiction|' . $targetId;
                if (isset($seen[$key])) {
                    continue;
                }
                $seen[$key] = true;
                $edges[] = $this->buildEdge('actor', $targetId, 'contradiction', 'both');
            }
        }
        return $edges;
    }

    private function buildEdge($targetType, $targetId, $edgeType, $direction)
    {
        return array(
            'target_type' => (string) $targetType,
            'target_id' => (string) $targetId,
            'edge_type' => (string) $edgeType,
            'direction' => (string) $direction,
            'metadata_json' => '{}'
        );
    }
}
