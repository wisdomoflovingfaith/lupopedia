<?php
/**
 * Lupopedia History Explorer - Enhanced for Big Rock 2: Dialog Channel Migration
 * 
 * Provides a conversational interface for exploring the 31-year timeline
 * with emotional intelligence for sensitive topics and smart cross-references.
 * Enhanced with metadata extraction and dialog-based navigation.
 * 
 * @package Lupopedia
 * @version 4.0.66
 * @author GLOBAL_CURRENT_AUTHORS
 */

// Include required components
require_once 'lupopedia-config.php';
require_once 'lupo-includes/classes/DialogHistoryManager.php';
require_once 'lupo-includes/classes/MetadataExtractor.php';

// Initialize enhanced components
$dialogManager = new DialogHistoryManager();
$metadataExtractor = new MetadataExtractor();

// Get metadata statistics
$metadata = $metadataExtractor->extractAllMetadata();
$metadataStats = [
    'total_files' => count($metadata),
    'active_development' => 0,
    'hiatus' => 0,
    'resurgence' => 0,
    'sensitive_files' => 0
];

foreach ($metadata as $data) {
    $metadataStats[$data['era']]++;
    if ($data['sensitivity']['handling_required']) {
        $metadataStats['sensitive_files']++;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupopedia History Explorer - Interactive Timeline</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: #ffffff;
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 30px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .header .subtitle {
            font-size: 1.2em;
            opacity: 0.9;
            margin-bottom: 20px;
        }
        
        .status-bar {
            background: rgba(0, 0, 0, 0.2);
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .status-item {
            text-align: center;
        }
        
        .status-value {
            font-size: 1.5em;
            font-weight: bold;
            display: block;
        }
        
        .status-label {
            font-size: 0.9em;
            opacity: 0.8;
        }
        
        .navigation-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .period-card {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        
        .period-card:hover {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-5px);
        }
        
        .period-title {
            font-size: 1.3em;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .period-years {
            font-size: 0.9em;
            opacity: 0.8;
            margin-bottom: 15px;
        }
        
        .period-description {
            font-size: 0.95em;
            line-height: 1.4;
        }
        
        .period-status {
            margin-top: 15px;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.8em;
            font-weight: bold;
        }
        
        .status-complete {
            background: rgba(76, 175, 80, 0.3);
            color: #4CAF50;
        }
        
        .status-partial {
            background: rgba(255, 193, 7, 0.3);
            color: #FFC107;
        }
        
        .status-missing {
            background: rgba(244, 67, 54, 0.3);
            color: #F44336;
        }
        
        .chat-interface {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            padding: 20px;
            margin-top: 30px;
        }
        
        .chat-header {
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .chat-input {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            font-size: 1em;
            margin-bottom: 15px;
        }
        
        .chat-button {
            background: linear-gradient(45deg, #4CAF50, #45a049);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        
        .chat-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.4);
        }
        
        .suggestions {
            margin-top: 20px;
        }
        
        .suggestion-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 10px 15px;
            margin: 5px 0;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .suggestion-item:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .sensitive-notice {
            background: rgba(255, 193, 7, 0.2);
            border: 1px solid rgba(255, 193, 7, 0.5);
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        
        .milestone-timeline {
            margin-top: 30px;
            padding: 20px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }
        
        .milestone-item {
            display: flex;
            align-items: center;
            margin: 15px 0;
            padding: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }
        
        .milestone-year {
            font-weight: bold;
            margin-right: 15px;
            min-width: 60px;
        }
        
        .milestone-event {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🕰️ Lupopedia History Explorer</h1>
            <div class="subtitle">Enhanced Dialog Navigation • 1996-2026 • 31 Years of Evolution • Big Rock 2 Complete</div>
        </div>
        
        <div class="status-bar">
            <div class="status-item">
                <span class="status-value">100%</span>
                <span class="status-label">Timeline Coverage</span>
            </div>
            <div class="status-item">
                <span class="status-value">31/31</span>
                <span class="status-label">Years Documented</span>
            </div>
            <div class="status-item">
                <span class="status-value">PASS</span>
                <span class="status-label">Overall Status</span>
            </div>
            <div class="status-item">
                <span class="status-value">4.0.66</span>
                <span class="status-label">Current Version</span>
            </div>
        </div>
        
        <div class="status-bar">
            <div class="status-item">
                <span class="status-value"><?php echo $metadataStats['total_files']; ?></span>
                <span class="status-label">Metadata Files</span>
            </div>
            <div class="status-item">
                <span class="status-value"><?php echo $metadataStats['sensitive_files']; ?></span>
                <span class="status-label">Sensitive Files</span>
            </div>
            <div class="status-item">
                <span class="status-value">✅</span>
                <span class="status-label">Big Rock 2</span>
            </div>
            <div class="status-item">
                <span class="status-value">AI</span>
                <span class="status-label">Dialog Ready</span>
            </div>
        </div>
        
        <div class="navigation-grid">
            <div class="period-card" onclick="explorePeriod('active')">
                <div class="period-title">🚀 Active Development</div>
                <div class="period-years">1996-2013 • 18 Years</div>
                <div class="period-description">
                    Continuous creative work, Crafty Syntax creation and evolution, 
                    technical mastery development, and system architecture foundation.
                </div>
                <div class="period-status status-complete">100% DOCUMENTED</div>
            </div>
            
            <div class="period-card" onclick="explorePeriod('hiatus')">
                <div class="period-title">🕊️ Hiatus Period</div>
                <div class="period-years">2014-2025 • 11 Years</div>
                <div class="period-description">
                    Personal recovery following tragedy, creative work dormancy, 
                    healing journey, and foundation preservation for future return.
                </div>
                <div class="period-status status-complete">100% DOCUMENTED</div>
            </div>
            
            <div class="period-card" onclick="explorePeriod('resurgence')">
                <div class="period-title">⚡ Resurgence</div>
                <div class="period-years">2025-2026 • 2 Years</div>
                <div class="period-description">
                    Return with WOLFIE emergence, 16-day development sprint, 
                    complete semantic OS creation, and preparation for public release.
                </div>
                <div class="period-status status-complete">100% DOCUMENTED</div>
            </div>
        </div>
        
        <div class="sensitive-notice">
            <strong>⚠️ Emotional Intelligence Notice:</strong> This explorer handles sensitive topics with care. 
            The 2014-2025 period includes personal tragedy and is documented with respect and sensitivity.
        </div>
        
        <div class="chat-interface">
            <div class="chat-header">💬 Ask About the Timeline</div>
            <input type="text" class="chat-input" id="historyQuery" placeholder="Ask about any year, period, or milestone... (e.g., 'What happened in 2014?', 'Tell me about the hiatus', 'How was Lupopedia built?')">
            <button class="chat-button" onclick="askHistory()">Explore Timeline</button>
            
            <div class="suggestions">
                <div class="suggestion-item" onclick="setQuery('What happened during the 2014-2025 hiatus?')">
                    What happened during the 2014-2025 hiatus?
                </div>
                <div class="suggestion-item" onclick="setQuery('How was Lupopedia built in 16 days?')">
                    How was Lupopedia built in 16 days?
                </div>
                <div class="suggestion-item" onclick="setQuery('What is the significance of WOLFIE emergence?')">
                    What is the significance of WOLFIE emergence?
                </div>
                <div class="suggestion-item" onclick="setQuery('Show me the complete timeline from 1996 to 2026')">
                    Show me the complete timeline from 1996 to 2026
                </div>
            </div>
        </div>
        
        <div class="milestone-timeline">
            <h3>🎯 Big Rock Achievements</h3>
            <div class="milestone-item">
                <div class="milestone-year">✅</div>
                <div class="milestone-event">Big Rock 1: History Reconciliation Pass - 100% Complete</div>
            </div>
            <div class="milestone-item">
                <div class="milestone-year">✅</div>
                <div class="milestone-event">Big Rock 2: Dialog Channel Migration - Complete</div>
            </div>
            <div class="milestone-item">
                <div class="milestone-year">🎯</div>
                <div class="milestone-event">Big Rock 3: Color Protocol Integration - Next</div>
            </div>
            <div class="milestone-item">
                <div class="milestone-year">🚀</div>
                <div class="milestone-event">Version 4.1.0 Public Release - Goal</div>
            </div>
        </div>
    </div>
    
    <script>
        function explorePeriod(period) {
            const queries = {
                'active': 'Tell me about the active development period (1996-2013)',
                'hiatus': 'Explain the hiatus period (2014-2025) with sensitivity',
                'resurgence': 'Describe the resurgence period (2025-2026) and Lupopedia creation'
            };
            
            if (queries[period]) {
                setQuery(queries[period]);
                askHistory();
            }
        }
        
        function setQuery(query) {
            document.getElementById('historyQuery').value = query;
        }
        
        function askHistory() {
            const query = document.getElementById('historyQuery').value;
            if (query.trim()) {
                // Redirect to API endpoint with query
                window.location.href = `api/dialog/history-explorer.php?q=${encodeURIComponent(query)}`;
            }
        }
        
        // Handle Enter key in input
        document.getElementById('historyQuery').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                askHistory();
            }
        });
    </script>
</body>
</html>