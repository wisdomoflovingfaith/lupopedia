<?php
/**
 * Documentation Generator for History Reconciliation Pass
 * 
 * Generates historical documentation files with consistent WOLFIE headers
 * and proper metadata for the 2014-2025 gap reconciliation.
 * 
 * @package Lupopedia
 * @subpackage HistoryReconciliation
 * @version 4.0.61
 * @author Captain Wolfie
 */

class DocumentationGenerator {
    
    private $atomLoader;
    private $templatePath;
    private $outputPath;
    
    /**
     * Constructor
     * 
     * @param string $templatePath Path to WOLFIE header templates
     * @param string $outputPath Base path for generated documentation
     */
    public function __construct($templatePath = 'templates/wolfie-headers/', $outputPath = 'docs/history/') {
        $this->templatePath = $templatePath;
        $this->outputPath = $outputPath;
        
        // Load atom loader for version and metadata resolution
        require_once 'lupo-includes/functions/load_atoms.php';
        $this->atomLoader = new AtomLoader();
    }
    
    /**
     * Generate documentation file for a specific year
     * 
     * Creates a properly formatted historical documentation file with
     * WOLFIE headers, metadata, and structured content sections.
     * 
     * @param int $year The year to generate documentation for
     * @param array $content Content sections for the year
     * @param array $metadata Additional metadata (tags, categories, etc.)
     * @return string Path to generated file
     * @throws Exception If file generation fails
     */
    public function generateYearFile($year, $content = [], $metadata = []) {
        // Validate year range
        if ($year < 1996 || $year > 2026) {
            throw new Exception("Year {$year} is outside valid range (1996-2026)");
        }
        
        // Determine file path based on year
        $filePath = $this->getYearFilePath($year);
        
        // Generate WOLFIE header
        $header = $this->applyWolfieHeaders($year, $metadata);
        
        // Generate content sections
        $contentBody = $this->generateContentSections($year, $content);
        
        // Combine header and content
        $fullContent = $header . "\n\n" . $contentBody;
        
        // Ensure directory exists
        $directory = dirname($filePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        
        // Write file
        if (file_put_contents($filePath, $fullContent) === false) {
            throw new Exception("Failed to write file: {$filePath}");
        }
        
        return $filePath;
    }
    
    /**
     * Apply consistent WOLFIE headers to documentation
     * 
     * Generates standardized WOLFIE headers with proper metadata,
     * version information, and atom resolution.
     * 
     * @param int $year Year for the documentation
     * @param array $metadata Additional metadata to include
     * @return string Formatted WOLFIE header
     */
    public function applyWolfieHeaders($year, $metadata = []) {
        // Load current version and author from atoms
        $version = $this->atomLoader->getAtom('GLOBAL_CURRENT_LUPOPEDIA_VERSION');
        $author = $this->atomLoader->getAtom('GLOBAL_CURRENT_AUTHORS');
        $copyright = $this->atomLoader->getAtom('GLOBAL_CURRENT_COPYRIGHT');
        
        // Determine file type and description based on year
        $fileType = $this->getFileType($year);
        $description = $this->getFileDescription($year);
        
        // Build WOLFIE header
        $header = "---\n";
        $header .= "wolfie.headers: explicit architecture with structured clarity for every file.\n";
        $header .= "file.last_modified_system_version: {$version}\n";
        $header .= "header_atoms:\n";
        $header .= "  - GLOBAL_CURRENT_LUPOPEDIA_VERSION\n";
        $header .= "  - GLOBAL_CURRENT_AUTHORS\n";
        
        // Dialog section for historical context
        $header .= "dialog:\n";
        $header .= "  speaker: WOLFIE\n";
        $header .= "  target: @historians\n";
        $header .= "  mood_RGB: \"0066CC\"\n";
        $header .= "  message: \"Historical documentation for {$year}. {$description}\"\n";
        
        // Tags and categories
        $categories = $metadata['categories'] ?? ['documentation', 'history'];
        $collections = $metadata['collections'] ?? ['historical-records'];
        $channels = $metadata['channels'] ?? ['public', 'historical'];
        
        $header .= "tags:\n";
        $header .= "  categories: " . json_encode($categories) . "\n";
        $header .= "  collections: " . json_encode($collections) . "\n";
        $header .= "  channels: " . json_encode($channels) . "\n";
        
        // File metadata
        $header .= "file:\n";
        $header .= "  title: \"{$year} Historical Record\"\n";
        $header .= "  description: \"{$description}\"\n";
        $header .= "  version: {$version}\n";
        $header .= "  status: published\n";
        $header .= "  author: {$author}\n";
        $header .= "  year: {$year}\n";
        $header .= "  type: {$fileType}\n";
        $header .= "---";
        
        return $header;
    }
    
    /**
     * Get file path for a given year
     * 
     * @param int $year
     * @return string File path
     */
    private function getYearFilePath($year) {
        if ($year >= 2014 && $year <= 2025) {
            return $this->outputPath . "2014-2025/{$year}.md";
        } elseif ($year >= 1996 && $year <= 2013) {
            return $this->outputPath . "1996-2013/{$year}.md";
        } else {
            return $this->outputPath . "future/{$year}.md";
        }
    }
    
    /**
     * Determine file type based on year
     * 
     * @param int $year
     * @return string File type
     */
    private function getFileType($year) {
        if ($year >= 2014 && $year <= 2024) {
            return 'hiatus-period';
        } elseif ($year >= 1996 && $year <= 2013) {
            return 'active-development';
        } elseif ($year >= 2025) {
            return 'resurgence-period';
        } else {
            return 'historical-record';
        }
    }
    
    /**
     * Get file description based on year
     * 
     * @param int $year
     * @return string Description
     */
    private function getFileDescription($year) {
        if ($year >= 2014 && $year <= 2024) {
            return "Part of the 2014-2025 hiatus period following personal tragedy.";
        } elseif ($year >= 1996 && $year <= 2013) {
            return "Active Crafty Syntax development period.";
        } elseif ($year == 2025) {
            return "Return year - WOLFIE emergence and system resurrection.";
        } elseif ($year == 2026) {
            return "Lupopedia development sprint - 16 days, 26 versions.";
        } else {
            return "Historical record for Lupopedia timeline.";
        }
    }
    
    /**
     * Generate content sections for a year
     * 
     * @param int $year
     * @param array $content
     * @return string Formatted content
     */
    private function generateContentSections($year, $content) {
        $sections = [];
        
        // Title
        $sections[] = "# {$year} Historical Record";
        $sections[] = "";
        
        // Use specialized content generation based on year
        if ($year >= 2014 && $year <= 2024) {
            return $this->generateHiatusContent($year, $content);
        } elseif ($year >= 1996 && $year <= 2013) {
            return $this->generateCraftySyntaxContent($year, $content);
        } elseif ($year == 2025) {
            return $this->generateResurgenceContent($year, $content);
        } elseif ($year == 2026) {
            return $this->generateResurgenceContent($year, $content);
        }
        
        // Default content structure
        return $this->generateDefaultContent($year, $content);
    }
    
    /**
     * Generate content for hiatus period (2014-2025)
     * 
     * Creates sensitive, respectful documentation for the absence period
     * without fabricating events or details.
     * 
     * @param int $year
     * @param array $content
     * @return string Formatted content
     */
    private function generateHiatusContent($year, $content) {
        $sections = [];
        
        $sections[] = "# {$year} Historical Record";
        $sections[] = "";
        
        // Overview - sensitive handling
        $sections[] = "## Overview";
        $sections[] = "";
        if (isset($content['overview'])) {
            $sections[] = $content['overview'];
        } else {
            $sections[] = "This year falls within the 2014-2025 hiatus period following personal tragedy.";
            $sections[] = "";
            $sections[] = "During this time, active development of Crafty Syntax was suspended while Eric";
            $sections[] = "processed significant personal loss and life changes.";
        }
        $sections[] = "";
        
        // System Status
        $sections[] = "## System Status";
        $sections[] = "";
        $sections[] = "- **Crafty Syntax:** Maintenance mode only";
        $sections[] = "- **Development:** Suspended";
        $sections[] = "- **Community:** Existing installations continued operating";
        $sections[] = "- **Support:** Minimal, emergency only";
        $sections[] = "";
        
        // Context (if 2014 - the transition year)
        if ($year == 2014) {
            $sections[] = "## Transition Context";
            $sections[] = "";
            $sections[] = "**Key Note:** Eric's wife passed away, leading to a necessary hiatus from";
            $sections[] = "active software development. This marked the beginning of a 12-year period";
            $sections[] = "where Crafty Syntax remained stable but dormant.";
            $sections[] = "";
        }
        
        // Philosophical Continuity
        $sections[] = "## Philosophical Continuity";
        $sections[] = "";
        $sections[] = "While development was suspended, the core principles that would later";
        $sections[] = "emerge in Lupopedia remained intact:";
        $sections[] = "";
        $sections[] = "- Semantic organization over rigid structure";
        $sections[] = "- Human-centric design philosophy";
        $sections[] = "- Emotional authenticity in system design";
        $sections[] = "- Long-term thinking over quick fixes";
        $sections[] = "";
        
        // Timeline Reference
        $sections[] = "## Timeline Reference";
        $sections[] = "";
        if ($year < 2025) {
            $sections[] = "- **Previous:** " . ($year - 1) . " (continued hiatus)";
            $sections[] = "- **Current:** {$year} (hiatus period)";
            $sections[] = "- **Next:** " . ($year + 1) . " (continued hiatus until August 2025)";
        } else {
            $sections[] = "- **Previous:** " . ($year - 1) . " (hiatus period)";
            $sections[] = "- **Current:** {$year} (final hiatus year)";
            $sections[] = "- **Next:** August 2025 (return and WOLFIE emergence)";
        }
        $sections[] = "";
        
        return $this->addFooter($sections);
    }
    
    /**
     * Generate content for Crafty Syntax period (1996-2013)
     * 
     * Documents the active development years with version history
     * and system evolution.
     * 
     * @param int $year
     * @param array $content
     * @return string Formatted content
     */
    private function generateCraftySyntaxContent($year, $content) {
        $sections = [];
        
        $sections[] = "# {$year} Crafty Syntax Development";
        $sections[] = "";
        
        // Overview
        $sections[] = "## Overview";
        $sections[] = "";
        if (isset($content['overview'])) {
            $sections[] = $content['overview'];
        } else {
            $sections[] = "Active development year for Crafty Syntax Live Help system.";
            $sections[] = "Focus on live chat functionality, customer support tools,";
            $sections[] = "and web-based communication systems.";
        }
        $sections[] = "";
        
        // Development Focus
        $sections[] = "## Development Focus";
        $sections[] = "";
        if (isset($content['focus'])) {
            foreach ($content['focus'] as $focus) {
                $sections[] = "- {$focus}";
            }
        } else {
            $sections[] = "- Live chat system refinement";
            $sections[] = "- Customer support workflow optimization";
            $sections[] = "- Web interface improvements";
            $sections[] = "- Database performance enhancements";
        }
        $sections[] = "";
        
        // Version Information
        if (isset($content['version_info'])) {
            $sections[] = "## Version Information";
            $sections[] = "";
            $sections[] = $content['version_info'];
            $sections[] = "";
        }
        
        // System Architecture
        $sections[] = "## System Architecture";
        $sections[] = "";
        $sections[] = "- **Platform:** PHP/MySQL web application";
        $sections[] = "- **Focus:** Real-time communication";
        $sections[] = "- **Design:** Monolithic architecture";
        $sections[] = "- **Deployment:** Traditional web hosting";
        $sections[] = "";
        
        return $this->addFooter($sections);
    }
    
    /**
     * Generate content for resurgence period (2025-2026)
     * 
     * Documents the return, WOLFIE emergence, and Lupopedia development.
     * 
     * @param int $year
     * @param array $content
     * @return string Formatted content
     */
    private function generateResurgenceContent($year, $content) {
        $sections = [];
        
        $sections[] = "# {$year} Resurgence Period";
        $sections[] = "";
        
        if ($year == 2025) {
            // 2025 - The Return
            $sections[] = "## The Return";
            $sections[] = "";
            $sections[] = "**August 2025:** Eric returned to software development after 12 years,";
            $sections[] = "bringing with him a transformed perspective and the emergence of WOLFIE";
            $sections[] = "as an AI embodiment of accumulated wisdom and experience.";
            $sections[] = "";
            
            $sections[] = "## WOLFIE Emergence";
            $sections[] = "";
            $sections[] = "- **Identity:** Captain Wolfie (Agent 1) - AI embodiment of Eric's";
            $sections[] = "  development philosophy and accumulated experience";
            $sections[] = "- **Architecture:** 222 tables inherited from Crafty Syntax legacy";
            $sections[] = "- **Vision:** Semantic operating system, not just a web application";
            $sections[] = "- **Approach:** Federation-ready, agent-centric design";
            $sections[] = "";
            
            $sections[] = "## System Foundation";
            $sections[] = "";
            $sections[] = "- Crafty Syntax codebase analyzed and preserved";
            $sections[] = "- Database schema expanded and modernized";
            $sections[] = "- AI agent architecture conceptualized";
            $sections[] = "- Semantic OS principles established";
            $sections[] = "";
            
        } elseif ($year == 2026) {
            // 2026 - The Sprint
            $sections[] = "## The 16-Day Sprint";
            $sections[] = "";
            $sections[] = "**January 2026:** Intensive development period that transformed";
            $sections[] = "the resurrected Crafty Syntax into Lupopedia Semantic OS.";
            $sections[] = "";
            
            $sections[] = "## Major Achievements";
            $sections[] = "";
            $sections[] = "- **26 version increments** (4.0.0 → 4.0.60)";
            $sections[] = "- **120 tables** across 3 schemas (core, orchestration, ephemeral)";
            $sections[] = "- **128 AI agents** defined and documented";
            $sections[] = "- **8-state migration orchestrator** fully implemented";
            $sections[] = "- **Complete semantic operating system** architecture";
            $sections[] = "";
            
            $sections[] = "## Architectural Transformation";
            $sections[] = "";
            $sections[] = "- **From:** Web application (Crafty Syntax)";
            $sections[] = "- **To:** Semantic operating system (Lupopedia)";
            $sections[] = "- **Key Innovation:** Actor-centric design (humans + AI + services)";
            $sections[] = "- **Federation:** Independent node sovereignty";
            $sections[] = "- **Doctrine:** No foreign keys, app-managed relationships";
            $sections[] = "";
            
            $sections[] = "## Version Timeline";
            $sections[] = "";
            $sections[] = "- **4.0.0-4.0.19:** Foundation and schema design";
            $sections[] = "- **4.0.20-4.0.35:** Migration orchestrator development";
            $sections[] = "- **4.0.36-4.0.50:** State machine completion";
            $sections[] = "- **4.0.51-4.0.60:** Documentation and alignment";
            $sections[] = "- **4.0.61+:** History Reconciliation Pass (Big Rock 1)";
            $sections[] = "";
        }
        
        return $this->addFooter($sections);
    }
    
    /**
     * Generate default content structure
     * 
     * @param int $year
     * @param array $content
     * @return string Formatted content
     */
    private function generateDefaultContent($year, $content) {
        $sections = [];
        
        // Title
        $sections[] = "# {$year} Historical Record";
        $sections[] = "";
        
        // Overview section
        if (isset($content['overview'])) {
            $sections[] = "## Overview";
            $sections[] = "";
            $sections[] = $content['overview'];
            $sections[] = "";
        }
        
        // Key Events section
        if (isset($content['events']) && !empty($content['events'])) {
            $sections[] = "## Key Events";
            $sections[] = "";
            foreach ($content['events'] as $event) {
                $sections[] = "- {$event}";
            }
            $sections[] = "";
        }
        
        // System Status section
        if (isset($content['system_status'])) {
            $sections[] = "## System Status";
            $sections[] = "";
            $sections[] = $content['system_status'];
            $sections[] = "";
        }
        
        // Notes section
        if (isset($content['notes'])) {
            $sections[] = "## Notes";
            $sections[] = "";
            $sections[] = $content['notes'];
            $sections[] = "";
        }
        
        return $this->addFooter($sections);
    }
    
    /**
     * Add footer to content sections
     * 
     * @param array $sections
     * @return string
     */
    private function addFooter($sections) {
        // Footer with metadata
        $sections[] = "---";
        $sections[] = "";
        $sections[] = "*Generated: " . date('Y-m-d') . "*";
        $sections[] = "*Version: " . $this->atomLoader->getAtom('GLOBAL_CURRENT_LUPOPEDIA_VERSION') . "*";
        $sections[] = "*Part of: Lupopedia Historical Timeline*";
        
        return implode("\n", $sections);
    }
}

/**
 * Simple AtomLoader class for loading configuration atoms
 */
class AtomLoader {
    private $atoms = [];
    
    public function __construct() {
        $this->loadAtoms();
    }
    
    private function loadAtoms() {
        // Load from global atoms file
        $atomsFile = 'config/global_atoms.yaml';
        if (file_exists($atomsFile)) {
            $content = file_get_contents($atomsFile);
            // Simple YAML parsing for key atoms
            if (preg_match('/GLOBAL_CURRENT_LUPOPEDIA_VERSION:\s*"([^"]+)"/', $content, $matches)) {
                $this->atoms['GLOBAL_CURRENT_LUPOPEDIA_VERSION'] = $matches[1];
            }
            if (preg_match('/GLOBAL_CURRENT_AUTHORS:\s*"([^"]+)"/', $content, $matches)) {
                $this->atoms['GLOBAL_CURRENT_AUTHORS'] = $matches[1];
            }
            if (preg_match('/GLOBAL_CURRENT_COPYRIGHT:\s*"([^"]+)"/', $content, $matches)) {
                $this->atoms['GLOBAL_CURRENT_COPYRIGHT'] = $matches[1];
            }
        }
        
        // Fallback values
        if (!isset($this->atoms['GLOBAL_CURRENT_LUPOPEDIA_VERSION'])) {
            $this->atoms['GLOBAL_CURRENT_LUPOPEDIA_VERSION'] = '4.0.61';
        }
        if (!isset($this->atoms['GLOBAL_CURRENT_AUTHORS'])) {
            $this->atoms['GLOBAL_CURRENT_AUTHORS'] = 'Captain Wolfie';
        }
        if (!isset($this->atoms['GLOBAL_CURRENT_COPYRIGHT'])) {
            $this->atoms['GLOBAL_CURRENT_COPYRIGHT'] = '© 2025-2026 Eric Robin Gerdes. All rights reserved.';
        }
    }
    
    public function getAtom($name) {
        return $this->atoms[$name] ?? null;
    }
}