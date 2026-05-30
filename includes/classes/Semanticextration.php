<?php

class SemanticExtractionEngine
{
    protected $db;
    protected $domainId;
    protected $now;

    public function __construct(PDO $db, $domainId)
    {
        $this->db       = $db;
        $this->domainId = (int)$domainId;
        $this->now      = $this->utcNowBigint();
    }

    public function processTabPathRow(array $tabPathRow)
    {
        $contentId = (int)$tabPathRow['content_id'];
        $path      = $tabPathRow['path'];

        $segments = $this->splitPath($path);
        if (empty($segments)) {
            return;
        }

        $atoms = $this->ensureAtomsFromSegments($segments);

        $this->linkContentToAtoms($contentId, $atoms);
        $this->linkAtomsHierarchically($atoms);

        $this->logSemanticSignal($contentId, 'tab_path', [
            'path'     => $path,
            'segments' => $segments,
        ]);
    }

    protected function splitPath($path)
    {
        $parts = explode('/', $path);
        $segments = [];

        foreach ($parts as $part) {
            $part = trim($part);
            if ($part === '') {
                continue;
            }
            $segments[] = $part;
        }

        return $segments;
    }

    protected function normalizeSlug($segment)
    {
        $slug = strtolower($segment);
        $slug = preg_replace('/[^a-z0-9\s\-]/', '', $slug);
        $slug = preg_replace('/\s+/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        return $slug === '' ? null : $slug;
    }

    protected function humanLabelFromSlug($slug)
    {
        $label = str_replace('-', ' ', $slug);
        return ucwords($label);
    }

    protected function ensureAtomsFromSegments(array $segments)
    {
        $atoms = [];

        foreach ($segments as $segment) {
            $slug = $this->normalizeSlug($segment);
            if ($slug === null) {
                continue;
            }

            $atom = $this->findOrCreateAtom($slug);
            if ($atom) {
                $atoms[] = $atom;
            }
        }

        return $atoms;
    }

    protected function findOrCreateAtom($slug)
    {
        $sql = "SELECT atom_id, slug, label
                FROM atoms
                WHERE domain_id = :domain_id
                  AND slug = :slug
                  AND deleted_at IS NULL
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':domain_id' => $this->domainId,
            ':slug'      => $slug,
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $row;
        }

        $label = $this->humanLabelFromSlug($slug);

        $sql = "INSERT INTO atoms
                (domain_id, slug, label, created_at, updated_at)
                VALUES
                (:domain_id, :slug, :label, :created_at, :updated_at)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':domain_id' => $this->domainId,
            ':slug'      => $slug,
            ':label'     => $label,
            ':created_at'=> $this->now,
            ':updated_at'=> $this->now,
        ]);

        $atomId = (int)$this->db->lastInsertId();

        return [
            'atom_id' => $atomId,
            'slug'    => $slug,
            'label'   => $label,
        ];
    }

    protected function linkContentToAtoms($contentId, array $atoms)
    {
        foreach ($atoms as $atom) {
            $this->upsertEdge(
                'content',
                $contentId,
                'atom',
                (int)$atom['atom_id'],
                $this->getEdgeTypeId('tagged_as')
            );
        }
    }

    protected function linkAtomsHierarchically(array $atoms)
    {
        $edgeTypeId = $this->getEdgeTypeId('parent_of');

        for ($i = 0; $i < count($atoms) - 1; $i++) {
            $parent = (int)$atoms[$i]['atom_id'];
            $child  = (int)$atoms[$i + 1]['atom_id'];

            $this->upsertEdge(
                'atom',
                $parent,
                'atom',
                $child,
                $edgeTypeId
            );
        }
    }

    protected function upsertEdge($sourceType, $sourceId, $targetType, $targetId, $edgeTypeId)
    {
        $sql = "SELECT edge_id, weight
                FROM edges
                WHERE source_type = :source_type
                  AND source_id = :source_id
                  AND target_type = :target_type
                  AND target_id = :target_id
                  AND edge_type_id = :edge_type_id
                  AND deleted_at IS NULL
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':source_type' => $sourceType,
            ':source_id'   => $sourceId,
            ':target_type' => $targetType,
            ':target_id'   => $targetId,
            ':edge_type_id'=> $edgeTypeId,
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $sql = "UPDATE edges
                    SET weight = weight + 1,
                        updated_at = :updated_at
                    WHERE edge_id = :edge_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':updated_at' => $this->now,
                ':edge_id'    => (int)$row['edge_id'],
            ]);
            return;
        }

        $sql = "INSERT INTO edges
                (source_type, source_id, target_type, target_id,
                 edge_type_id, weight, confidence, created_at, updated_at)
                VALUES
                (:source_type, :source_id, :target_type, :target_id,
                 :edge_type_id, :weight, :confidence, :created_at, :updated_at)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':source_type' => $sourceType,
            ':source_id'   => $sourceId,
            ':target_type' => $targetType,
            ':target_id'   => $targetId,
            ':edge_type_id'=> $edgeTypeId,
            ':weight'      => 10,
            ':confidence'  => 1.0,
            ':created_at'  => $this->now,
            ':updated_at'  => $this->now,
        ]);
    }

    protected function getEdgeTypeId($slug)
    {
        static $cache = [];

        if (isset($cache[$slug])) {
            return $cache[$slug];
        }

        $sql = "SELECT edge_type_id
                FROM edge_types
                WHERE slug = :slug
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':slug' => $slug]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new Exception("Missing edge_type for slug: " . $slug);
        }

        $cache[$slug] = (int)$row['edge_type_id'];
        return $cache[$slug];
    }

    protected function logSemanticSignal($contentId, $type, array $payload)
    {
        $json = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $sql = "INSERT INTO semantic_signals
                (content_id, signal_type, payload, weight, created_at)
                VALUES
                (:content_id, :signal_type, :payload, :weight, :created_at)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':content_id'  => $contentId,
            ':signal_type' => $type,
            ':payload'     => $json,
            ':weight'      => 1,
            ':created_at'  => $this->now,
        ]);
    }

    protected function utcNowBigint()
    {
        return (int)gmdate('YmdHis');
    }
}
