<?php
/**
 * Color Protocol - Big Rock 3: Color Protocol Integration
 * 
 * Maps emotional geometry to color schemes for enhanced visual communication.
 * Provides color-coded responses and interface elements based on emotional context.
 * Integrates with DialogHistoryManager for emotionally intelligent visual feedback.
 * 
 * @package Lupopedia
 * @version 4.0.66
 * @author GLOBAL_CURRENT_AUTHORS
 */

class ColorProtocol {
    
    private $colorSchemes;
    private $emotionalMappings;
    private $eraColors;
    private $sensitivityColors;
    
    /**
     * Constructor - Initialize color protocol mappings
     */
    public function __construct() {
        $this->initializeColorSchemes();
        $this->initializeEmotionalMappings();
        $this->initializeEraColors();
        $this->initializeSensitivityColors();
    }
    
    /**
     * Get color scheme for emotional context
     */
    public function getColorScheme(array $emotionalContext): array {
        $scheme = [
            'primary' => '#4A90E2', // Default blue
            'secondary' => '#7B68EE',
            'accent' => '#FF6B6B',
            'background' => '#F8F9FA',
            'text' => '#2C3E50',
            'border' => '#E1E8ED',
            'metadata' => []
        ];
        
        // Map emotional axes to colors
        if (isset($emotionalContext['emotional_geometry'])) {
            $geometry = $emotionalContext['emotional_geometry'];
            
            // Creative Axis Colors
            if (isset($geometry['creative_axis'])) {
                $scheme['primary'] = $this->getCreativeAxisColor($geometry['creative_axis']);
            }
            
            // Growth Axis Colors
            if (isset($geometry['growth_axis'])) {
                $scheme['secondary'] = $this->getGrowthAxisColor($geometry['growth_axis']);
            }
            
            // Foundation Axis Colors
            if (isset($geometry['foundation_axis'])) {
                $scheme['accent'] = $this->getFoundationAxisColor($geometry['foundation_axis']);
            }
        }
        
        // Apply era-specific colors
        if (isset($emotionalContext['era'])) {
            $eraColors = $this->eraColors[$emotionalContext['era']] ?? [];
            $scheme = array_merge($scheme, $eraColors);
        }
        
        // Apply sensitivity colors
        if (isset($emotionalContext['sensitivity'])) {
            $sensitivityColors = $this->getSensitivityColors($emotionalContext['sensitivity']);
            $scheme = array_merge($scheme, $sensitivityColors);
        }
        
        return $scheme;
    }
    
    /**
     * Get color for creative axis
     */
    private function getCreativeAxisColor(array $creativeAxis): string {
        $keywords = isset($creativeAxis['items']) ? $creativeAxis['items'] : [];
        
        // Map creative keywords to colors
        $colorMap = [
            'innovation' => '#9B59B6', // Purple
            'creativity' => '#E74C3C', // Red
            'design' => '#3498DB', // Blue
            'artistic' => '#F39C12', // Orange
            'imagination' => '#1ABC9C', // Teal
            'originality' => '#E67E22', // Dark Orange
            'inspiration' => '#8E44AD', // Violet
            'expression' => '#16A085' // Green Teal
        ];
        
        foreach ($keywords as $keyword) {
            $keyword = strtolower($keyword);
            if (isset($colorMap[$keyword])) {
                return $colorMap[$keyword];
            }
        }
        
        return '#9B59B6'; // Default purple for creativity
    }
    
    /**
     * Get color for growth axis
     */
    private function getGrowthAxisColor(array $growthAxis): string {
        $keywords = isset($growthAxis['items']) ? $growthAxis['items'] : [];
        
        // Map growth keywords to colors
        $colorMap = [
            'learning' => '#27AE60', // Green
            'development' => '#2ECC71', // Emerald
            'mastery' => '#16A085', // Green Teal
            'progress' => '#138D75', // Dark Green
            'advancement' => '#1F618D', // Dark Blue
            'improvement' => '#2874A6', // Medium Blue
            'expansion' => '#5499C7', // Sky Blue
            'leadership' => '#5D6D7E' // Gray Blue
        ];
        
        foreach ($keywords as $keyword) {
            $keyword = strtolower($keyword);
            if (isset($colorMap[$keyword])) {
                return $colorMap[$keyword];
            }
        }
        
        return '#27AE60'; // Default green for growth
    }
    
    /**
     * Get color for foundation axis
     */
    private function getFoundationAxisColor(array $foundationAxis): string {
        $keywords = isset($foundationAxis['items']) ? $foundationAxis['items'] : [];
        
        // Map foundation keywords to colors
        $colorMap = [
            'stability' => '#34495E', // Dark Gray
            'reliability' => '#566573', // Medium Gray
            'structure' => '#7F8C8D', // Light Gray
            'foundation' => '#95A5A6', // Lighter Gray
            'architecture' => '#BDC3C7', // Silver
            'patterns' => '#D5DBDB', // Light Silver
            'quality' => '#E8E8E8', // Very Light Gray
            'excellence' => '#F4F6F7' // Ultra Light Gray
        ];
        
        foreach ($keywords as $keyword) {
            $keyword = strtolower($keyword);
            if (isset($colorMap[$keyword])) {
                return $colorMap[$keyword];
            }
        }
        
        return '#34495E'; // Default dark gray for foundation
    }
    
    /**
     * Get sensitivity-based colors
     */
    private function getSensitivityColors(array $sensitivity): array {
        $colors = [];
        
        switch ($sensitivity['level']) {
            case 'high':
                $colors = [
                    'primary' => '#E74C3C', // Soft red for high sensitivity
                    'background' => '#FADBD8', // Very light red background
                    'border' => '#E6B0AA', // Light red border
                    'text' => '#A93226', // Dark red text
                    'accent' => '#C0392B', // Medium red accent
                ];
                break;
                
            case 'medium':
                $colors = [
                    'primary' => '#F39C12', // Orange for medium sensitivity
                    'background' => '#FBEED6', // Light orange background
                    'border' => '#F7DC6F', // Light orange border
                    'text' => '#B9770E', // Dark orange text
                    'accent' => '#E67E22', // Medium orange accent
                ];
                break;
                
            case 'low':
            default:
                $colors = [
                    'primary' => '#27AE60', // Green for low sensitivity
                    'background' => '#D5F4E6', // Light green background
                    'border' => '#A9DFBF', // Light green border
                    'text' => '#196F3D', // Dark green text
                    'accent' => '#229954', // Medium green accent
                ];
                break;
        }
        
        return $colors;
    }
    
    /**
     * Generate CSS for color scheme
     */
    public function generateCSS(array $colorScheme): string {
        $css = "
        :root {
            --color-primary: {$colorScheme['primary']};
            --color-secondary: {$colorScheme['secondary']};
            --color-accent: {$colorScheme['accent']};
            --color-background: {$colorScheme['background']};
            --color-text: {$colorScheme['text']};
            --color-border: {$colorScheme['border']};
        }
        
        .color-primary { color: var(--color-primary); }
        .color-secondary { color: var(--color-secondary); }
        .color-accent { color: var(--color-accent); }
        .bg-primary { background-color: var(--color-primary); }
        .bg-secondary { background-color: var(--color-secondary); }
        .bg-accent { background-color: var(--color-accent); }
        .bg-background { background-color: var(--color-background); }
        .border-primary { border-color: var(--color-border); }
        
        .emotional-response {
            background: linear-gradient(135deg, var(--color-background), var(--color-primary));
            border-left: 4px solid var(--color-primary);
            color: var(--color-text);
            padding: 20px;
            border-radius: 8px;
            margin: 15px 0;
        }
        
        .sensitive-notice {
            background: var(--color-background);
            border: 2px solid var(--color-accent);
            color: var(--color-text);
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        
        .cross-reference {
            background: var(--color-background);
            border: 1px solid var(--color-border);
            color: var(--color-text);
            padding: 10px 15px;
            margin: 5px 0;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .cross-reference:hover {
            background: var(--color-secondary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        ";
        
        return $css;
    }
    
    /**
     * Get color-coded response data
     */
    public function getColorCodedResponse(array $dialogResponse, array $colorScheme): array {
        $colorCoded = $dialogResponse;
        
        // Add color scheme to metadata
        $colorCoded['metadata']['color_scheme'] = $colorScheme;
        $colorCoded['metadata']['css'] = $this->generateCSS($colorScheme);
        
        // Add color-coded emotional state
        $colorCoded['emotional_state_color'] = $colorScheme['primary'];
        
        // Add color-coded era indicator
        $colorCoded['era_color'] = $this->getEraColor($dialogResponse['era']);
        
        // Add sensitivity indicator
        $colorCoded['sensitivity_indicator'] = [
            'level' => $colorCoded['metadata']['sensitivity_level'] ?? 'low',
            'color' => $this->getSensitivityIndicatorColor($colorCoded['metadata']['sensitivity_level'] ?? 'low'),
            'notice' => $this->getSensitivityNotice($colorCoded['metadata']['sensitivity_level'] ?? 'low')
        ];
        
        return $colorCoded;
    }
    
    /**
     * Get era color
     */
    private function getEraColor(string $era): string {
        $eraColors = [
            'active_development' => '#3498DB', // Blue
            'hiatus' => '#95A5A6', // Gray
            'resurgence' => '#27AE60', // Green
            'unknown' => '#7F8C8D' // Default gray
        ];
        
        return $eraColors[$era] ?? $eraColors['unknown'];
    }
    
    /**
     * Get sensitivity indicator color
     */
    private function getSensitivityIndicatorColor(string $level): string {
        $colors = [
            'high' => '#E74C3C',
            'medium' => '#F39C12',
            'low' => '#27AE60'
        ];
        
        return $colors[$level] ?? $colors['low'];
    }
    
    /**
     * Get sensitivity notice
     */
    private function getSensitivityNotice(string $level): string {
        $notices = [
            'high' => 'This content contains sensitive topics and is handled with care.',
            'medium' => 'This content may contain sensitive topics.',
            'low' => 'This content is suitable for general audiences.'
        ];
        
        return $notices[$level] ?? $notices['low'];
    }
    
    /**
     * Initialize color schemes
     */
    private function initializeColorSchemes(): void {
        $this->colorSchemes = [
            'creative' => [
                'primary' => '#9B59B6',
                'secondary' => '#8E44AD',
                'accent' => '#E74C3C',
                'background' => '#F8F9FA'
            ],
            'growth' => [
                'primary' => '#27AE60',
                'secondary' => '#2ECC71',
                'accent' => '#16A085',
                'background' => '#F8F9FA'
            ],
            'foundation' => [
                'primary' => '#34495E',
                'secondary' => '#566573',
                'accent' => '#7F8C8D',
                'background' => '#F8F9FA'
            ],
            'sensitive' => [
                'primary' => '#E74C3C',
                'secondary' => '#F39C12',
                'accent' => '#C0392B',
                'background' => '#FADBD8'
            ]
        ];
    }
    
    /**
     * Initialize emotional mappings
     */
    private function initializeEmotionalMappings(): void {
        $this->emotionalMappings = [
            'creative_axis' => [
                'innovation' => '#9B59B6',
                'creativity' => '#E74C3C',
                'design' => '#3498DB',
                'artistic' => '#F39C12'
            ],
            'growth_axis' => [
                'learning' => '#27AE60',
                'development' => '#2ECC71',
                'mastery' => '#16A085',
                'progress' => '#138D75'
            ],
            'foundation_axis' => [
                'stability' => '#34495E',
                'reliability' => '#566573',
                'structure' => '#7F8C8D',
                'quality' => '#95A5A6'
            ]
        ];
    }
    
    /**
     * Initialize era colors
     */
    private function initializeEraColors(): void {
        $this->eraColors = [
            'active_development' => [
                'primary' => '#3498DB',
                'background' => '#EBF5FB',
                'border' => '#AED6F1',
                'text' => '#1B4F72'
            ],
            'hiatus' => [
                'primary' => '#95A5A6',
                'background' => '#F8F9FA',
                'border' => '#D5DBDB',
                'text' => '#566573'
            ],
            'resurgence' => [
                'primary' => '#27AE60',
                'background' => '#D5F4E6',
                'border' => '#A9DFBF',
                'text' => '#196F3D'
            ]
        ];
    }
    
    /**
     * Initialize sensitivity colors
     */
    private function initializeSensitivityColors(): void {
        $this->sensitivityColors = [
            'high' => [
                'primary' => '#E74C3C',
                'background' => '#FADBD8',
                'border' => '#E6B0AA',
                'text' => '#A93226'
            ],
            'medium' => [
                'primary' => '#F39C12',
                'background' => '#FBEED6',
                'border' => '#F7DC6F',
                'text' => '#B9770E'
            ],
            'low' => [
                'primary' => '#27AE60',
                'background' => '#D5F4E6',
                'border' => '#A9DFBF',
                'text' => '#196F3D'
            ]
        ];
    }
    
    /**
     * Export color protocol configuration
     */
    public function exportConfiguration(): array {
        return [
            'color_schemes' => $this->colorSchemes,
            'emotional_mappings' => $this->emotionalMappings,
            'era_colors' => $this->eraColors,
            'sensitivity_colors' => $this->sensitivityColors,
            'version' => '4.0.66',
            'exported_at' => date('YmdHis')
        ];
    }
}
