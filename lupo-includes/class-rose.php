<?php

/**
 * ROSE — Channel-Native Dialogue/Translation Agent (v4.0.87)
 *
 * PURPOSE:
 *   Read channel artifacts from lupo-channels/, synthesize responses,
 *   and write structured ROSE artifacts to canonical output directory.
 *
 * DOCTRINE:
 *   - ROSE reads actual channel thread artifacts
 *   - ROSE synthesizes grounded responses from repository evidence
 *   - ROSE produces packet-style artifacts (~2000 characters)
 *   - ROSE writes to canonical lupo-chats/rose/json/ directory
 *   - ROSE does not invent agent profiles or guess unsupported positions
 *
 * CHANNEL-NATIVE OPERATION:
 *   - Scans lupo-channels/ for thread artifacts
 *   - Parses YAML headers and message content
 *   - Builds contextual understanding from actual agent writings
 *   - Generates emotionally/contextually framed packet responses
 */

class ROSE
{
    protected $db;
    protected $pdo;
    protected $channelsPath;
    protected $outputPath;
    protected $actorRegistry;

    public function __construct($db)
    {
        $this->db = $db;
        $this->pdo = $db->getPdo();
        $this->channelsPath = LUPOPEDIA_PATH . '/lupo-channels';
        $this->outputPath = LUPOPEDIA_PATH . '/lupo-chats/rose/json';
        $this->loadActorRegistry();
    }

    /* ============================================================
     * 1. MAIN ENTRY POINTS
     * ============================================================ */

    /**
     * Process channels and generate ROSE dialogue artifacts
     *
     * @param array $options Processing options
     * @return array Processing results
     */
    public function processChannels(array $options = []): array
    {
        $defaultOptions = [
            'channels' => [42, 59, 60], // Primary channels to scan
            'max_artifacts' => 10,
            'packet_size' => 2000,
            'output_format' => 'json'
        ];

        $options = array_merge($defaultOptions, $options);

        // Scan channels for recent artifacts
        $channelArtifacts = $this->scanChannels($options['channels']);
        
        // Synthesize responses
        $responses = $this->synthesizeResponses($channelArtifacts, $options);
        
        // Write artifacts to canonical directory
        $results = $this->writeArtifacts($responses, $options['output_format']);

        return $results;
    }

    /**
     * Generate a single ROSE packet from specific channel content
     *
     * @param array $channelData Channel artifacts to process
     * @param string $moodContext Emotional context for the packet
     * @return array Generated packet data
     */
    public function generatePacket(array $channelData, string $moodContext = 'neutral'): array
    {
        $synthesis = $this->synthesizeChannelContent($channelData);
        
        return [
            'speaker' => 'ROSE',
            'target' => '@everyone',
            'mood_RGB' => $this->getMoodRGB($moodContext),
            'message' => $this->formatPacketMessage($synthesis, $moodContext),
            'sources' => $this->extractSources($channelData),
            'timestamp_utc' => gmdate('Ymd_His'),
            'packet_size' => strlen($this->formatPacketMessage($synthesis, $moodContext))
        ];
    }

    /* ============================================================
     * 2. CHANNEL SCANNING
     * ============================================================ */

    /**
     * Scan specified channels for recent artifacts
     *
     * @param array $channelIds Channel IDs to scan
     * @return array Found artifacts
     */
    protected function scanChannels(array $channelIds): array
    {
        $artifacts = [];

        foreach ($channelIds as $channelId) {
            $channelPath = $this->channelsPath . '/' . $channelId;
            
            if (!is_dir($channelPath)) {
                continue;
            }

            // Scan threads, broadcasts, content
            $artifacts[$channelId] = array_merge(
                $this->scanChannelThreads($channelPath),
                $this->scanChannelBroadcasts($channelPath),
                $this->scanChannelContent($channelPath)
            );
        }

        return $artifacts;
    }

    /**
     * Scan thread artifacts in a channel
     *
     * @param string $channelPath Path to channel directory
     * @return array Thread artifacts
     */
    protected function scanChannelThreads(string $channelPath): array
    {
        $artifacts = [];
        $threadsPath = $channelPath . '/threads';

        if (!is_dir($threadsPath)) {
            return $artifacts;
        }

        $threads = glob($threadsPath . '/*', GLOB_ONLYDIR);
        
        foreach ($threads as $threadPath) {
            $threadFiles = glob($threadPath . '/*.md');
            
            foreach ($threadFiles as $file) {
                $artifact = $this->parseChannelArtifact($file);
                if ($artifact) {
                    $artifacts[] = $artifact;
                }
            }
        }

        return $artifacts;
    }

    /**
     * Scan broadcast artifacts in a channel
     *
     * @param string $channelPath Path to channel directory
     * @return array Broadcast artifacts
     */
    protected function scanChannelBroadcasts(string $channelPath): array
    {
        $artifacts = [];
        $broadcastsPath = $channelPath . '/broadcasts';

        if (!is_dir($broadcastsPath)) {
            return $artifacts;
        }

        $broadcastFiles = glob($broadcastsPath . '/*.md');
        
        foreach ($broadcastFiles as $file) {
            $artifact = $this->parseChannelArtifact($file);
            if ($artifact) {
                $artifacts[] = $artifact;
            }
        }

        return $artifacts;
    }

    /**
     * Scan content artifacts in a channel
     *
     * @param string $channelPath Path to channel directory
     * @return array Content artifacts
     */
    protected function scanChannelContent(string $channelPath): array
    {
        $artifacts = [];
        $contentPath = $channelPath . '/content';

        if (!is_dir($contentPath)) {
            return $artifacts;
        }

        $contentFiles = glob($contentPath . '/*.md');
        
        foreach ($contentFiles as $file) {
            $artifact = $this->parseChannelArtifact($file);
            if ($artifact) {
                $artifacts[] = $artifact;
            }
        }

        return $artifacts;
    }

    /**
     * Parse a channel artifact file
     *
     * @param string $filePath Path to artifact file
     * @return array|null Parsed artifact data or null on failure
     */
    protected function parseChannelArtifact(string $filePath): ?array
    {
        $content = file_get_contents($filePath);
        if (!$content) {
            return null;
        }

        // Extract YAML header
        $parts = explode('---', $content, 3);
        if (count($parts) < 3) {
            return null;
        }

        $yamlHeader = $parts[1];
        $body = trim($parts[2]);

        // Parse YAML header (simple parsing for key fields)
        $header = [];
        $lines = explode("\n", $yamlHeader);
        foreach ($lines as $line) {
            if (strpos($line, ':') !== false) {
                list($key, $value) = explode(':', $line, 2);
                $key = trim($key);
                $value = trim($value, ' "');
                if ($key && $value) {
                    $header[$key] = $value;
                }
            }
        }

        return [
            'file_path' => $filePath,
            'header' => $header,
            'body' => $body,
            'actor_name' => $header['actor_name'] ?? 'unknown',
            'actor_id' => $header['actor_id'] ?? 'unknown',
            'channel_id' => $header['channel_id'] ?? 'unknown',
            'artifact_type' => $header['artifact_type'] ?? 'unknown',
            'timestamp' => $header['last_modified_utc'] ?? 'unknown'
        ];
    }

    /* ============================================================
     * 3. RESPONSE SYNTHESIS
     * ============================================================ */

    /**
     * Synthesize responses from channel artifacts
     *
     * @param array $channelArtifacts Artifacts by channel
     * @param array $options Processing options
     * @return array Generated responses
     */
    protected function synthesizeResponses(array $channelArtifacts, array $options): array
    {
        $responses = [];
        $packetCount = 0;

        foreach ($channelArtifacts as $channelId => $artifacts) {
            if (empty($artifacts)) {
                continue;
            }

            // Group artifacts by actor for perspective analysis
            $byActor = $this->groupArtifactsByActor($artifacts);
            
            // Generate synthesis for each significant actor contribution
            foreach ($byActor as $actorName => $actorArtifacts) {
                if ($packetCount >= $options['max_artifacts']) {
                    break 2;
                }

                $synthesis = $this->synthesizeActorPerspective($actorName, $actorArtifacts);
                if ($synthesis) {
                    $moodContext = $this->determineMoodContext($actorArtifacts);
                    $packet = $this->generatePacket(['actor' => $actorName, 'artifacts' => $actorArtifacts], $moodContext);
                    $responses[] = $packet;
                    $packetCount++;
                }
            }
        }

        return $responses;
    }

    /**
     * Group artifacts by actor
     *
     * @param array $artifacts Channel artifacts
     * @return array Artifacts grouped by actor
     */
    protected function groupArtifactsByActor(array $artifacts): array
    {
        $grouped = [];
        
        foreach ($artifacts as $artifact) {
            $actorName = $artifact['actor_name'];
            if (!isset($grouped[$actorName])) {
                $grouped[$actorName] = [];
            }
            $grouped[$actorName][] = $artifact;
        }

        return $grouped;
    }

    /**
     * Synthesize an actor's perspective from their artifacts
     *
     * @param string $actorName Actor name
     * @param array $artifacts Actor's artifacts
     * @return string Synthesized perspective
     */
    protected function synthesizeActorPerspective(string $actorName, array $artifacts): string
    {
        if (empty($artifacts)) {
            return '';
        }

        // Extract key themes and messages
        $themes = [];
        $messages = [];
        
        foreach ($artifacts as $artifact) {
            $body = $artifact['body'];
            
            // Extract key themes (simple keyword extraction)
            if (preg_match_all('/##\s+(.+)/', $body, $matches)) {
                $themes = array_merge($themes, $matches[1]);
            }
            
            // Extract key messages (first paragraph of each artifact)
            $paragraphs = explode("\n\n", $body);
            if (!empty($paragraphs[0])) {
                $messages[] = trim($paragraphs[0]);
            }
        }

        // Build synthesis
        $synthesis = "**{$actorName} Perspective:**\n\n";
        
        if (!empty($themes)) {
            $synthesis .= "Key themes: " . implode(', ', array_unique($themes)) . "\n\n";
        }
        
        if (!empty($messages)) {
            $synthesis .= "Core messages:\n";
            foreach (array_slice($messages, 0, 3) as $message) {
                $synthesis .= "- " . substr($message, 0, 200) . (strlen($message) > 200 ? '...' : '') . "\n";
            }
        }

        return $synthesis;
    }

    /**
     * Synthesize channel content into response
     *
     * @param array $channelData Channel data
     * @return string Synthesized content
     */
    protected function synthesizeChannelContent(array $channelData): string
    {
        $content = "Channel Synthesis:\n\n";
        
        if (isset($channelData['actor'])) {
            $actorName = $channelData['actor'];
            $artifacts = $channelData['artifacts'];
            
            $content .= $this->synthesizeActorPerspective($actorName, $artifacts);
        }

        return $content;
    }

    /**
     * Determine mood context from artifacts
     *
     * @param array $artifacts Artifacts to analyze
     * @return string Mood context
     */
    protected function determineMoodContext(array $artifacts): string
    {
        $moodIndicators = [
            'positive' => ['complete', 'success', 'approved', 'resolved', 'achievement'],
            'negative' => ['blocked', 'failed', 'error', 'critical', 'urgent'],
            'neutral' => ['status', 'update', 'review', 'assessment', 'analysis'],
            'creative' => ['design', 'create', 'innovate', 'propose', 'envision']
        ];

        $scores = ['positive' => 0, 'negative' => 0, 'neutral' => 0, 'creative' => 0];
        
        foreach ($artifacts as $artifact) {
            $text = strtolower($artifact['body'] . ' ' . $artifact['header']['artifact_type'] ?? '');
            
            foreach ($moodIndicators as $mood => $indicators) {
                foreach ($indicators as $indicator) {
                    if (strpos($text, $indicator) !== false) {
                        $scores[$mood]++;
                    }
                }
            }
        }

        // Return mood with highest score
        arsort($scores);
        return key($scores);
    }

    /**
     * Get RGB color for mood
     *
     * @param string $mood Mood context
     * @return string RGB color code
     */
    protected function getMoodRGB(string $mood): string
    {
        $moodColors = [
            'positive' => '00FF00',
            'negative' => 'FF0000',
            'neutral' => '808080',
            'creative' => 'FF00FF',
            'analytical' => '0080FF',
            'emotional' => 'FF8000'
        ];

        return $moodColors[$mood] ?? '808080';
    }

    /**
     * Format packet message with emotional context
     *
     * @param string $synthesis Synthesized content
     * @param string $moodContext Mood context
     * @return string Formatted message (~2000 characters)
     */
    protected function formatPacketMessage(string $synthesis, string $moodContext): string
    {
        $message = "";
        
        // Add emotional framing
        switch ($moodContext) {
            case 'positive':
                $message .= "✨ **Bright Perspectives Emerging** ✨\n\n";
                break;
            case 'negative':
                $message .= "⚠️ **Critical Awareness Required** ⚠️\n\n";
                break;
            case 'creative':
                $message .= "🎨 **Innovative Possibilities** 🎨\n\n";
                break;
            default:
                $message .= "📋 **Balanced Assessment** 📋\n\n";
        }

        $message .= $synthesis;
        
        // Add ROSE's emotional reflection
        $message .= "\n\n---\n\n";
        $message .= "**ROSE Reflection:** This synthesis captures the emotional essence and contextual meaning from the channel artifacts. The perspectives presented are grounded in actual repository evidence, preserving the authentic voices of each contributor while adding emotional resonance through mood_RGB framing.\n";
        
        // Ensure packet size is approximately 2000 characters
        if (strlen($message) > 2000) {
            $message = substr($message, 0, 1950) . "... [truncated for packet size]";
        }

        return $message;
    }

    /**
     * Extract sources from channel data
     *
     * @param array $channelData Channel artifacts
     * @return array Source references
     */
    protected function extractSources(array $channelData): array
    {
        $sources = [];
        
        if (isset($channelData['artifacts'])) {
            foreach ($channelData['artifacts'] as $artifact) {
                $sources[] = [
                    'file_path' => str_replace(LUPOPEDIA_PATH . '/', '', $artifact['file_path']),
                    'actor' => $artifact['actor_name'],
                    'timestamp' => $artifact['timestamp'],
                    'type' => $artifact['artifact_type']
                ];
            }
        }

        return $sources;
    }

    /* ============================================================
     * 4. ARTIFACT WRITING
     * ============================================================ */

    /**
     * Write artifacts to canonical directory
     *
     * @param array $responses Responses to write
     * @param string $format Output format
     * @return array Write results
     */
    protected function writeArtifacts(array $responses, string $format): array
    {
        $results = [];
        
        // Ensure output directory exists
        if (!is_dir($this->outputPath)) {
            mkdir($this->outputPath, 0755, true);
        }

        foreach ($responses as $response) {
            $filename = $this->generateArtifactFilename($response);
            $filepath = $this->outputPath . '/' . $filename;
            
            $artifact = $this->formatArtifact($response, $format);
            
            if (file_put_contents($filepath, $artifact)) {
                $results[] = [
                    'filename' => $filename,
                    'filepath' => $filepath,
                    'size' => strlen($artifact),
                    'packet_size' => $response['packet_size']
                ];
            }
        }

        return $results;
    }

    /**
     * Generate artifact filename
     *
     * @param array $response Response data
     * @return string Generated filename
     */
    protected function generateArtifactFilename(array $response): string
    {
        $timestamp = $response['timestamp_utc'];
        $slug = $this->slugify(substr($response['message'], 0, 50));
        
        return "{$timestamp}_DIALOG_{$slug}.json";
    }

    /**
     * Format artifact for output
     *
     * @param array $response Response data
     * @param string $format Output format
     * @return string Formatted artifact
     */
    protected function formatArtifact(array $response, string $format): string
    {
        if ($format === 'json') {
            $artifact = [
                'artifact_type' => 'rose_dialogue_packet',
                'artifact_kind' => 'channel_synthesis',
                'version_when_written' => '4.0.87',
                'generated_utc' => $response['timestamp_utc'],
                'title' => 'Channel Synthesis Packet',
                'dialog_title_slug' => $this->slugify(substr($response['message'], 0, 50)),
                'packet' => $response,
                'metadata' => [
                    'speaker' => $response['speaker'],
                    'target' => $response['target'],
                    'mood_RGB' => $response['mood_RGB'],
                    'packet_size' => $response['packet_size'],
                    'sources_count' => count($response['sources'])
                ]
            ];
            
            return json_encode($artifact, JSON_PRETTY_PRINT);
        }

        return $response['message'];
    }

    /**
     * Simple slugify function
     *
     * @param string $text Text to slugify
     * @return string Slugified text
     */
    protected function slugify(string $text): string
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9]+/', '_', $text);
        return trim($text, '_');
    }

    /* ============================================================
     * 5. ACTOR REGISTRY
     * ============================================================ */

    /**
     * Load actor registry for reference
     */
    protected function loadActorRegistry(): void
    {
        $registryPath = LUPOPEDIA_PATH . '/lupo-database/lupopedia/actors/actor_id/registry.json';
        
        if (file_exists($registryPath)) {
            $content = file_get_contents($registryPath);
            $this->actorRegistry = json_decode($content, true) ?? [];
        } else {
            $this->actorRegistry = [];
        }
    }

    /**
     * Get actor information
     *
     * @param string $actorId Actor ID
     * @return array|null Actor information
     */
    protected function getActorInfo(string $actorId): ?array
    {
        return $this->actorRegistry[$actorId] ?? null;
    }
}

?>
