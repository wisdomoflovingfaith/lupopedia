<?php
/**
 * Dialog History Manager - Enhanced for Big Rock 3: Color Protocol Integration
 * 
 * Core component for dialog-based historical navigation and exploration
 * Integrates with TimelineGenerator, ContinuityValidator, MetadataExtractor, and ColorProtocol
 * Provides emotional intelligence for sensitive content handling with color-coded visual feedback
 * Enhanced with metadata extraction, cross-reference intelligence, and color protocol integration
 * 
 * @package Lupopedia
 * @version 4.0.66
 * @author GLOBAL_CURRENT_AUTHORS
 */

require_once __DIR__ . '/TimelineGenerator.php';
require_once __DIR__ . '/ContinuityValidator.php';
require_once __DIR__ . '/MetadataExtractor.php';
require_once __DIR__ . '/ColorProtocol.php';

class DialogHistoryManager {
    
    private $timelineGenerator;
    private $continuityValidator;
    private $metadataExtractor;
    private $colorProtocol;
    private $emotionalResponder;
    private $crossReferenceIntelligence;
    private $conversationContext;
    private $extractedMetadata;
    
    /**
     * Constructor - Enhanced with ColorProtocol integration
     * 
     * @param string $historyPath Base path to history documents
     */
    public function __construct($historyPath = null) {
        $this->timelineGenerator = new TimelineGenerator($historyPath);
        $this->continuityValidator = new ContinuityValidator($historyPath);
        $this->metadataExtractor = new MetadataExtractor();
        $this->colorProtocol = new ColorProtocol();
        $this->emotionalResponder = new EmotionalHistoryResponder();
        $this->crossReferenceIntelligence = new DialogCrossReferenceIntelligence();
        $this->conversationContext = new ConversationContext();
        
        $this->initializeHistoricalData();
        $this->extractMetadata();
    }
    
    /**
     * Extract and cache metadata from all .md files
     */
    private function extractMetadata(): void {
        $this->extractedMetadata = $this->metadataExtractor->extractAllMetadata();
        
        // Load metadata into cross-reference intelligence if method exists
        if (method_exists($this->crossReferenceIntelligence, 'loadMetadata')) {
            $this->crossReferenceIntelligence->loadMetadata($this->extractedMetadata);
        }
    }
    
    /**
     * Process user query and generate appropriate response - Enhanced with Color Protocol
     * 
     * @param string $query User's natural language query
     * @param array $context Current conversation context
     * @return array Structured response with content, metadata, and color scheme
     */
    public function processQuery($query, $context = []) {
        // Update conversation context
        $this->conversationContext->update($context);
        
        // Analyze query type and intent
        $queryAnalysis = $this->analyzeQuery($query);
        
        // Determine historical context with metadata enhancement
        $historicalContext = $this->determineHistoricalContext($queryAnalysis);
        
        // Enhance historical context with extracted metadata
        $enhancedContext = $this->enhanceContextWithMetadata($historicalContext, $queryAnalysis);
        
        // Generate base response
        $baseResponse = $this->generateBaseResponse($queryAnalysis, $enhancedContext);
        
        // Apply emotional intelligence with metadata-aware sensitivity
        $emotionalResponse = $this->emotionalResponder->enhanceResponse(
            $baseResponse, 
            $enhancedContext, 
            $this->conversationContext
        );
        
        // Add smart cross-references using metadata
        $crossReferences = $this->crossReferenceIntelligence->generateSuggestions(
            $queryAnalysis, 
            $enhancedContext, 
            $this->conversationContext
        );
        
        // Validate historical accuracy
        $validatedResponse = $this->validateResponseAccuracy($emotionalResponse);
        
        // Apply color protocol
        $colorScheme = $this->colorProtocol->getColorScheme($enhancedContext);
        $colorCodedResponse = $this->colorProtocol->getColorCodedResponse($validatedResponse, $colorScheme);
        
        return [
            'content' => $colorCodedResponse['content'],
            'tone' => $colorCodedResponse['tone'],
            'era' => $colorCodedResponse['era'],
            'emotional_state' => $colorCodedResponse['emotional_state'],
            'emotional_state_color' => $colorCodedResponse['emotional_state_color'],
            'era_color' => $colorCodedResponse['era_color'],
            'cross_references' => $crossReferences,
            'follow_up_questions' => $this->generateFollowUpQuestions($queryAnalysis, $enhancedContext),
            'conversation_context' => $this->conversationContext->getState(),
            'sensitivity_indicator' => $colorCodedResponse['sensitivity_indicator'],
            'metadata' => [
                'query_type' => $queryAnalysis['type'],
                'confidence' => $validatedResponse['confidence'],
                'sources' => $validatedResponse['sources'],
                'emotional_intelligence_applied' => $validatedResponse['emotional_intelligence_applied'],
                'metadata_enhanced' => true,
                'extracted_metadata_count' => count($this->extractedMetadata),
                'sensitivity_level' => $enhancedContext['sensitivity']['level'] ?? 'low',
                'color_protocol_applied' => true,
                'color_scheme' => $colorScheme,
                'css' => $colorCodedResponse['metadata']['css']
            ]
        ];
    }
    
    /**
     * Enhance historical context with extracted metadata
     */
    private function enhanceContextWithMetadata(array $historicalContext, array $queryAnalysis): array {
        $enhancedContext = $historicalContext;
        
        // Add metadata-based information
        if (isset($queryAnalysis['year'])) {
            $year = $queryAnalysis['year'];
            
            // Get emotional context for the year
            $emotionalContext = $this->metadataExtractor->getEmotionalContext($year);
            $enhancedContext['emotional_geometry'] = $emotionalContext['emotional_geometry'];
            $enhancedContext['sensitivity'] = $emotionalContext['sensitivity'];
            
            // Get cross-reference suggestions
            $suggestions = $this->metadataExtractor->getCrossReferenceSuggestions($year);
            $enhancedContext['metadata_suggestions'] = $suggestions;
            
            // Add file-specific metadata
            foreach ($this->extractedMetadata as $filePath => $metadata) {
                if (strpos($filePath, $year . '.md') !== false) {
                    $enhancedContext['file_metadata'] = $metadata;
                    break;
                }
            }
        }
        
        // Add era-specific metadata
        $era = $historicalContext['era'] ?? 'unknown';
        $enhancedContext['era_metadata'] = $this->getEraMetadata($era);
        
        return $enhancedContext;
    }
    
    /**
     * Get metadata for specific era
     */
    private function getEraMetadata(string $era): array {
        $eraMetadata = [
            'era' => $era,
            'files' => [],
            'total_files' => 0,
            'sensitive_files' => 0,
            'key_themes' => []
        ];
        
        foreach ($this->extractedMetadata as $filePath => $metadata) {
            if ($metadata['era'] === $era) {
                $eraMetadata['files'][] = [
                    'path' => $filePath,
                    'title' => $metadata['content']['title'] ?? '',
                    'sensitivity' => $metadata['sensitivity']
                ];
                $eraMetadata['total_files']++;
                
                if ($metadata['sensitivity']['handling_required']) {
                    $eraMetadata['sensitive_files']++;
                }
                
                // Extract key themes
                if (isset($metadata['content']['key_events'])) {
                    foreach ($metadata['content']['key_events'] as $event) {
                        $eraMetadata['key_themes'][] = $event['category'];
                    }
                }
            }
        }
        
        $eraMetadata['key_themes'] = array_unique($eraMetadata['key_themes']);
        
        return $eraMetadata;
    }
    
    /**
     * Get conversation starter suggestions
     * 
     * @return array Suggested conversation starters
     */
    public function getConversationStarters() {
        return [
            'era_exploration' => [
                'Tell me about the Crafty Syntax era',
                'What happened during the hiatus period?',
                'How did Lupopedia emerge in 2025?',
                'Show me the complete journey from 1996 to 2026'
            ],
            'specific_events' => [
                'When was Crafty Syntax created?',
                'What led to the 11-year hiatus?',
                'How was WOLFIE architecture developed?',
                'What were the major achievements in 2026?'
            ],
            'personal_journey' => [
                'What was the creative journey like?',
                'How did personal experiences shape the project?',
                'What inspired the return in 2025?',
                'How has the vision evolved over time?'
            ],
            'technical_evolution' => [
                'How did the technology evolve?',
                'What were the major technical milestones?',
                'How does the current system compare to early versions?',
                'What innovations were introduced?'
            ]
        ];
    }
    
    /**
     * Get era overview for quick navigation
     * 
     * @return array Era overviews with key information
     */
    public function getEraOverviews() {
        $timeline = $this->timelineGenerator->generateCompleteTimeline();
        
        return [
            'crafty_syntax' => [
                'period' => '1996-2013',
                'title' => 'Crafty Syntax Era',
                'description' => 'Live help system development and innovation',
                'key_events' => ['1996: Project beginnings', '2002: Crafty Syntax creation', '2013: Version 3.7.5'],
                'tone' => 'technical, innovative, developmental',
                'conversation_starters' => [
                    'What was Crafty Syntax?',
                    'How did the live help system evolve?',
                    'What were the key innovations?'
                ]
            ],
            'hiatus' => [
                'period' => '2014-2025',
                'title' => 'Hiatus Period',
                'description' => 'Personal recovery and reflection',
                'key_events' => ['2014: Personal tragedy', '2014-2025: Recovery journey', '2025: Healing complete'],
                'tone' => 'sensitive, respectful, empathetic',
                'conversation_starters' => [
                    'What happened during this time?',
                    'How was the recovery journey?',
                    'What led to the return?'
                ],
                'sensitivity_note' => 'This period involves personal loss and is handled with care'
            ],
            'resurgence' => [
                'period' => '2025-2026',
                'title' => 'Lupopedia Resurgence',
                'description' => 'Semantic operating system development',
                'key_events' => ['2025: Return and WOLFIE emergence', '2026: 16-day development sprint', '2026: System completion'],
                'tone' => 'energetic, ambitious, transformative',
                'conversation_starters' => [
                    'What inspired the return?',
                    'How was Lupopedia developed so quickly?',
                    'What makes it a semantic OS?'
                ]
            ]
        ];
    }
    
    /**
     * Private methods for query processing
     */
    
    private function initializeHistoricalData() {
        // Load timeline data for quick access
        $this->historicalData = $this->timelineGenerator->generateCompleteTimeline();
        
        // Validate data integrity
        $validation = $this->continuityValidator->validateContinuity();
        if ($validation['status'] !== 'passed') {
            error_log('DialogHistoryManager: Historical data validation failed');
        }
    }
    
    private function analyzeQuery($query) {
        $analysis = [
            'original_query' => $query,
            'type' => $this->determineQueryType($query),
            'intent' => $this->determineQueryIntent($query),
            'entities' => $this->extractEntities($query),
            'time_references' => $this->extractTimeReferences($query),
            'emotional_indicators' => $this->detectEmotionalIndicators($query),
            'complexity' => $this->assessQueryComplexity($query)
        ];
        
        return $analysis;
    }
    
    private function determineQueryType($query) {
        $query = strtolower($query);
        
        if (preg_match('/(what|tell me about|describe|explain)/', $query)) {
            return 'informational';
        } elseif (preg_match('/(when|what year|what time)/', $query)) {
            return 'temporal';
        } elseif (preg_match('/(how|why|what led to)/', $query)) {
            return 'causal';
        } elseif (preg_match('/(show me|display|list)/', $query)) {
            return 'navigational';
        } elseif (preg_match('/(compare|difference|versus)/', $query)) {
            return 'comparative';
        } elseif (preg_match('/(personal|journey|experience)/', $query)) {
            return 'personal';
        } else {
            return 'general';
        }
    }
    
    private function determineQueryIntent($query) {
        $query = strtolower($query);
        
        if (preg_match('/(crafty syntax|live help)/', $query)) {
            return 'crafty_syntax_exploration';
        } elseif (preg_match('/(hiatus|break|absence)/', $query)) {
            return 'hiatus_exploration';
        } elseif (preg_match('/(lupopedia|wolfie|resurgence)/', $query)) {
            return 'resurgence_exploration';
        } elseif (preg_match('/(timeline|journey|history)/', $query)) {
            return 'timeline_overview';
        } elseif (preg_match('/(personal|you|your)/', $query)) {
            return 'personal_journey';
        } else {
            return 'general_exploration';
        }
    }
    
    private function extractEntities($query) {
        $entities = [];
        
        // Year entities
        if (preg_match('/\b(19|20)\d{2}\b/', $query, $matches)) {
            $entities['years'] = $matches;
        }
        
        // Era entities
        if (preg_match('/(crafty syntax|hiatus|resurgence)/', $query, $matches)) {
            $entities['eras'] = $matches;
        }
        
        // Technology entities
        if (preg_match('/(live help|semantic os|wolfie|migration)/', $query, $matches)) {
            $entities['technologies'] = $matches;
        }
        
        return $entities;
    }
    
    private function extractTimeReferences($query) {
        $timeRefs = [];
        
        // Specific years
        if (preg_match_all('/\b(19|20)\d{2}\b/', $query, $matches)) {
            $timeRefs['years'] = $matches[0];
        }
        
        // Time periods
        if (preg_match('/(1990s|2000s|2010s|2020s)/', $query, $matches)) {
            $timeRefs['decades'] = $matches[0];
        }
        
        // Relative time
        if (preg_match('/(early|mid|late)/', $query, $matches)) {
            $timeRefs['modifiers'] = $matches[0];
        }
        
        return $timeRefs;
    }
    
    private function detectEmotionalIndicators($query) {
        $indicators = [];
        
        // Sensitivity indicators
        if (preg_match('/(tragedy|loss|death|sad|difficult)/', $query)) {
            $indicators['sensitivity'] = 'high';
        }
        
        // Curiosity indicators
        if (preg_match('/(curious|interested|wonder)/', $query)) {
            $indicators['curiosity'] = 'present';
        }
        
        // Empathy indicators
        if (preg_match('/(sorry|empathy|understand|feel)/', $query)) {
            $indicators['empathy'] = 'present';
        }
        
        return $indicators;
    }
    
    private function assessQueryComplexity($query) {
        $complexity = 0;
        
        // Length factor
        $complexity += min(strlen($query) / 100, 2);
        
        // Question complexity
        if (preg_match('/(why|how|compare)/', $query)) {
            $complexity += 1;
        }
        
        // Multiple entities
        if (preg_match('/\b(and|or|but)\b/', $query)) {
            $complexity += 0.5;
        }
        
        // Time complexity
        if (preg_match('/\b(when|during|before|after)\b/', $query)) {
            $complexity += 0.5;
        }
        
        return min($complexity, 3); // Cap at 3 for simplicity
    }
    
    private function determineHistoricalContext($queryAnalysis) {
        $context = [
            'era' => 'unknown',
            'time_period' => null,
            'sensitivity_level' => 'normal',
            'emotional_state' => 'neutral',
            'topic_focus' => 'general'
        ];
        
        // Determine era from entities
        if (isset($queryAnalysis['entities']['eras'])) {
            $era = $queryAnalysis['entities']['eras'][0];
            $context['era'] = $this->mapEntityToEra($era);
        }
        
        // Determine time period from years
        if (isset($queryAnalysis['time_references']['years'])) {
            $year = $queryAnalysis['time_references']['years'][0];
            $context['time_period'] = $year;
            $context['era'] = $this->getEraFromYear($year);
        }
        
        // Determine sensitivity
        if (isset($queryAnalysis['emotional_indicators']['sensitivity'])) {
            $context['sensitivity_level'] = 'high';
            if ($context['era'] === 'hiatus') {
                $context['emotional_state'] = 'empathetic_required';
            }
        }
        
        // Determine topic focus
        $context['topic_focus'] = $queryAnalysis['intent'];
        
        return $context;
    }
    
    private function mapEntityToEra($entity) {
        $mapping = [
            'crafty syntax' => 'crafty_syntax',
            'hiatus' => 'hiatus',
            'resurgence' => 'resurgence',
            'lupopedia' => 'resurgence',
            'wolfie' => 'resurgence'
        ];
        
        return $mapping[strtolower($entity)] ?? 'unknown';
    }
    
    private function getEraFromYear($year) {
        $year = (int)$year;
        
        if ($year >= 1996 && $year <= 2013) {
            return 'crafty_syntax';
        } elseif ($year >= 2014 && $year <= 2025) {
            return 'hiatus';
        } elseif ($year >= 2025 && $year <= 2026) {
            return 'resurgence';
        }
        
        return 'unknown';
    }
    
    private function generateBaseResponse($queryAnalysis, $historicalContext) {
        $response = [
            'content' => '',
            'tone' => 'neutral',
            'sources' => [],
            'confidence' => 0.5
        ];
        
        switch ($historicalContext['era']) {
            case 'crafty_syntax':
                $response = $this->generateCraftySyntaxResponse($queryAnalysis, $historicalContext);
                break;
            case 'hiatus':
                $response = $this->generateHiatusResponse($queryAnalysis, $historicalContext);
                break;
            case 'resurgence':
                $response = $this->generateResurgenceResponse($queryAnalysis, $historicalContext);
                break;
            default:
                $response = $this->generateGeneralResponse($queryAnalysis, $historicalContext);
        }
        
        return $response;
    }
    
    private function generateCraftySyntaxResponse($queryAnalysis, $historicalContext) {
        $responses = [
            'informational' => "Crafty Syntax was a live help system created in 2002 that revolutionized customer support through real-time chat. It evolved through multiple versions, culminating in version 3.7.5 in 2013.",
            'temporal' => "Crafty Syntax was actively developed from 2002 to 2013, with the final version 3.7.5 released before the hiatus period began.",
            'causal' => "Crafty Syntax emerged from the need to provide immediate customer support on websites, replacing slower email-based support systems with real-time interaction.",
            'personal' => "The Crafty Syntax era represents 18 years of active creative development, establishing the technical foundation that would later inform Lupopedia's architecture."
        ];
        
        $responseType = $queryAnalysis['type'];
        $content = $responses[$responseType] ?? $responses['informational'];
        
        return [
            'content' => $content,
            'tone' => 'technical, innovative',
            'sources' => ['1996-2013/2002.md', '1996-2013/2013.md'],
            'confidence' => 0.9
        ];
    }
    
    private function generateHiatusResponse($queryAnalysis, $historicalContext) {
        $responses = [
            'informational' => "The hiatus period from 2014-2025 was a deliberate pause in development due to profound personal loss. This time was focused on healing and recovery, with no active computer-based work occurring.",
            'temporal' => "The hiatus began in 2014 and continued for 11 years, ending with the return to creative work in 2025.",
            'personal' => "This period represents a journey of personal recovery and reflection. While difficult, it provided the space and perspective that would eventually inform the renewed creative vision of Lupopedia.",
            'sensitive' => "I understand this involves sensitive topics. The hiatus was a time for personal healing after a significant loss. This period is handled with care and respect for privacy."
        ];
        
        $responseType = $queryAnalysis['type'];
        $content = $responses[$responseType] ?? $responses['informational'];
        
        // Apply sensitivity for personal queries
        if ($historicalContext['sensitivity_level'] === 'high') {
            $content = $responses['sensitive'];
        }
        
        return [
            'content' => $content,
            'tone' => 'empathetic, respectful',
            'sources' => ['2014-2025/hiatus.md', '2014-2025/2014.md'],
            'confidence' => 0.8,
            'emotional_intelligence_applied' => true
        ];
    }
    
    private function generateResurgenceResponse($queryAnalysis, $historicalContext) {
        $responses = [
            'informational' => "The resurgence began in 2025 with the return after 11-year absence. WOLFIE architecture emerged with 222 tables, leading to the rapid development of Lupopedia as a semantic operating system.",
            'temporal' => "The resurgence period spans 2025-2026, with remarkable achievements including a 16-day development sprint in January 2026 that produced 26 version increments.",
            'causal' => "The resurgence was driven by renewed creative energy and the accumulated wisdom from the previous 18 years of work, combined with fresh perspective gained during the hiatus.",
            'technical' => "Lupopedia represents a complete semantic operating system with 120 tables across 3 schemas, 128 AI agents, and an 8-state migration orchestrator - all developed with unprecedented speed and clarity."
        ];
        
        $responseType = $queryAnalysis['type'];
        $content = $responses[$responseType] ?? $responses['informational'];
        
        return [
            'content' => $content,
            'tone' => 'energetic, ambitious',
            'sources' => ['2025-2026/2025.md', '2025-2026/2026.md'],
            'confidence' => 0.95
        ];
    }
    
    private function generateGeneralResponse($queryAnalysis, $historicalContext) {
        $content = "Lupopedia's history spans 30 years from 1996 to 2026, encompassing three distinct eras: the Crafty Syntax development period (1996-2013), a hiatus for personal recovery (2014-2025), and the current resurgence with semantic operating system development (2025-2026).";
        
        return [
            'content' => $content,
            'tone' => 'informative, neutral',
            'sources' => ['TIMELINE_1996_2026.md', 'HISTORY_INDEX.md'],
            'confidence' => 0.7
        ];
    }
    
    private function validateResponseAccuracy($response) {
        // Basic validation - in production, this would be more sophisticated
        $validated = $response;
        
        // Ensure sources exist
        if (isset($validated['sources'])) {
            $validated['sources'] = array_filter($validated['sources'], function($source) {
                return file_exists($this->timelineGenerator->historyPath . '/../' . $source);
            });
        }
        
        // Ensure confidence is reasonable
        $validated['confidence'] = max(0.1, min(1.0, $validated['confidence']));
        
        return $validated;
    }
    
    private function generateFollowUpQuestions($queryAnalysis, $historicalContext) {
        $questions = [];
        
        switch ($historicalContext['era']) {
            case 'crafty_syntax':
                $questions = [
                    'Would you like to know more about specific Crafty Syntax features?',
                    'How did the live help system influence later development?',
                    'What were the major technical challenges?'
                ];
                break;
            case 'hiatus':
                $questions = [
                    'Would you like to understand what led to the return?',
                    'How did this period influence the current vision?',
                    'What lessons were learned during this time?'
                ];
                break;
            case 'resurgence':
                $questions = [
                    'Would you like to explore the WOLFIE architecture?',
                    'How were 128 AI agents developed so quickly?',
                    'What makes this a semantic operating system?'
                ];
                break;
        }
        
        return array_slice($questions, 0, 3); // Limit to 3 questions
    }
}

/**
 * Conversation Context Manager
 */
class ConversationContext {
    
    private $context = [];
    private $history = [];
    private $currentEra = null;
    private $sensitivityLevel = 'normal';
    
    public function update($newContext) {
        $this->context = array_merge($this->context, $newContext);
        $this->history[] = $newContext;
        
        // Update derived context
        if (isset($newContext['era'])) {
            $this->currentEra = $newContext['era'];
        }
        
        if (isset($newContext['sensitivity_level'])) {
            $this->sensitivityLevel = $newContext['sensitivity_level'];
        }
    }
    
    public function getState() {
        return [
            'current_era' => $this->currentEra,
            'sensitivity_level' => $this->sensitivityLevel,
            'conversation_length' => count($this->history),
            'context_summary' => $this->context
        ];
    }
}

/**
 * Emotional History Responder
 */
class EmotionalHistoryResponder {
    
    public function enhanceResponse($response, $historicalContext, $conversationContext) {
        $enhanced = $response;
        
        // Apply emotional intelligence based on context
        if ($historicalContext['era'] === 'hiatus') {
            $enhanced = $this->applyHiatusEmotionalIntelligence($enhanced);
        }
        
        // Adjust tone based on conversation context
        $enhanced['tone'] = $this->adjustToneForContext($enhanced['tone'], $conversationContext);
        
        // Add emotional state if needed
        if (!isset($enhanced['emotional_state'])) {
            $enhanced['emotional_state'] = $this->determineEmotionalState($historicalContext);
        }
        
        $enhanced['emotional_intelligence_applied'] = true;
        
        return $enhanced;
    }
    
    private function applyHiatusEmotionalIntelligence($response) {
        $response['tone'] = 'empathetic, respectful, gentle';
        $response['emotional_state'] = 'supportive';
        
        // Add emotional support if appropriate
        if (strpos($response['content'], 'loss') !== false || strpos($response['content'], 'tragedy') !== false) {
            $response['content'] .= " This period is handled with care and understanding.";
        }
        
        return $response;
    }
    
    private function adjustToneForContext($tone, $conversationContext) {
        // Adjust tone based on conversation history and sensitivity
        if ($conversationContext->getState()['sensitivity_level'] === 'high') {
            return 'empathetic, supportive';
        }
        
        return $tone;
    }
    
    private function determineEmotionalState($historicalContext) {
        $states = [
            'crafty_syntax' => 'creative_energy',
            'hiatus' => 'reflective_support',
            'resurgence' => 'enthusiastic_clarity'
        ];
        
        return $states[$historicalContext['era']] ?? 'neutral';
    }
}

/**
 * Dialog Cross-Reference Intelligence
 */
class DialogCrossReferenceIntelligence {
    
    public function generateSuggestions($queryAnalysis, $historicalContext, $conversationContext) {
        $suggestions = [];
        
        // Era-based suggestions
        switch ($historicalContext['era']) {
            case 'crafty_syntax':
                $suggestions = [
                    [
                        'type' => 'era_transition',
                        'title' => 'Learn about the transition to hiatus',
                        'description' => 'Understand what led to the 11-year break',
                        'target' => 'hiatus_exploration'
                    ],
                    [
                        'type' => 'technical_detail',
                        'title' => 'Explore technical innovations',
                        'description' => 'Deep dive into Crafty Syntax features',
                        'target' => 'technical_exploration'
                    ]
                ];
                break;
            case 'hiatus':
                $suggestions = [
                    [
                        'type' => 'era_transition',
                        'title' => 'Discover the return story',
                        'description' => 'Learn about the 2025 resurgence',
                        'target' => 'resurgence_exploration'
                    ],
                    [
                        'type' => 'personal_journey',
                        'title' => 'Understand the recovery journey',
                        'description' => 'Explore the healing process',
                        'target' => 'personal_exploration'
                    ]
                ];
                break;
            case 'resurgence':
                $suggestions = [
                    [
                        'type' => 'technical_detail',
                        'title' => 'Explore WOLFIE architecture',
                        'description' => 'Understand the semantic OS design',
                        'target' => 'technical_exploration'
                    ],
                    [
                        'type' => 'era_comparison',
                        'title' => 'Compare with Crafty Syntax era',
                        'description' => 'See how things evolved',
                        'target' => 'comparative_analysis'
                    ]
                ];
                break;
        }
        
        return $suggestions;
    }
}
