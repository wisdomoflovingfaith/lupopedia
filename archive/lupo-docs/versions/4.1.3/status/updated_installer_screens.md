---
lupopedia.headers:
  header_format_version: "4.1.3"
  file_path_from_root: "lupo-docs/versions/4.1.3/status/updated_installer_screens.md"
  web_path: "https://www.lupopedia.com/lupopedia/lupo-docs/versions/4.1.3/status/updated_installer_screens.md"
  status: "active"
  when_updated: "20260420080000"
  trust_tier: "canonical"
  questions_toon: null
  memory_toon: "lupo-memory/registry/status/1026/04/actor-registration-and-sync-report.toon"
  atoms_toon: null
  transcript_jsonl: "0/registry/actor-rebuild-installer-update"
  artifact_type: status
  artifact_kind: report
  channel_key: "registry"
  federation_node_id: 0
  thread_id: null
  content_id: null
  content_parent_id: "42"
  content_slug: "updated-installer-screens"
  default_collection_id: null
  lupopedia.schema: status
  title: "Updated Installer Screens for 4.1.3"
  summary: "HTML templates and UI specifications for new installer steps: actors, channels, memory configuration"
---

# Updated Installer Screens for 4.1.3

## Overview

This document provides the HTML templates and UI specifications for the new installer steps required in 4.1.3: actors registration, channels configuration, and memory path setup.

## New Step 5: Actors Registration

### HTML Template
```html
<!-- Step 5: actors -->
<?php if ($step === 'actors'): ?>
<div class="step-container">
    <h2>Register Filesystem Actors</h2>
    <p class="step-description">
        Scanning the lupo-actors/ directory to register all filesystem actors in the database.
        This enables channel-based coordination and proper actor management.
    </p>
    
    <div class="progress-container">
        <div class="progress-bar">
            <div class="progress-fill" id="actors-progress" style="width: 0%"></div>
        </div>
        <div class="progress-text" id="actors-progress-text">Initializing...</div>
    </div>
    
    <div class="actors-log" id="actors-log">
        <div class="log-entry">Starting actor registration...</div>
    </div>
    
    <div class="actors-summary" id="actors-summary" style="display: none;">
        <h3>Registration Summary</h3>
        <table class="summary-table">
            <tr>
                <td>Total Actors Found:</td>
                <td id="total-actors">0</td>
            </tr>
            <tr>
                <td>Successfully Registered:</td>
                <td id="registered-actors" class="success">0</td>
            </tr>
            <tr>
                <td>Failed:</td>
                <td id="failed-actors" class="error">0</td>
            </tr>
            <tr>
                <td>Skipped:</td>
                <td id="skipped-actors" class="warning">0</td>
            </tr>
        </table>
    </div>
    
    <div class="step-actions">
        <button type="button" id="start-actors-registration" class="btn btn-primary">
            Start Registration
        </button>
        <button type="button" id="retry-actors-registration" class="btn btn-secondary" style="display: none;">
            Retry Failed
        </button>
        <button type="submit" name="next_step" value="channels" class="btn btn-success" id="continue-to-channels" style="display: none;">
            Continue to Channel Configuration
        </button>
    </div>
</div>

<script>
$(document).ready(function() {
    let registrationInProgress = false;
    
    $('#start-actors-registration').click(function() {
        if (registrationInProgress) return;
        registrationInProgress = true;
        
        $(this).prop('disabled', true);
        $('#actors-log').html('<div class="log-entry">Starting actor registration...</div>');
        
        // Simulate registration process
        registerActors();
    });
    
    function registerActors() {
        const actors = [
            {id: 1, name: 'wolfie', type: 'system'},
            {id: 2, name: 'lilith', type: 'system'},
            {id: 3, name: 'rose', type: 'system'},
            {id: 9, name: 'anubis', type: 'system'},
            {id: 14, name: 'hermes', type: 'coordination'},
            {id: 13, name: 'iris', type: 'coordination'},
            // ... all 47 actors
        ];
        
        let registered = 0;
        let failed = 0;
        let skipped = 0;
        let total = actors.length;
        
        actors.forEach((actor, index) => {
            setTimeout(() => {
                const logEntry = `<div class="log-entry">Registering actor ${actor.name} (ID: ${actor.id})...</div>`;
                $('#actors-log').append(logEntry);
                
                // Simulate registration success/failure
                const success = Math.random() > 0.1; // 90% success rate
                
                if (success) {
                    registered++;
                    $('#actors-log').append(`<div class="log-entry success">✓ ${actor.name} registered successfully</div>`);
                } else {
                    failed++;
                    $('#actors-log').append(`<div class="log-entry error">✗ Failed to register ${actor.name}</div>`);
                }
                
                // Update progress
                const progress = ((index + 1) / total) * 100;
                $('#actors-progress').css('width', progress + '%');
                $('#actors-progress-text').text(`${Math.round(progress)}% - ${index + 1}/${total}`);
                
                // Check if complete
                if (index === total - 1) {
                    completeRegistration(registered, failed, skipped, total);
                }
            }, index * 100); // 100ms delay between actors
        });
    }
    
    function completeRegistration(registered, failed, skipped, total) {
        $('#total-actors').text(total);
        $('#registered-actors').text(registered);
        $('#failed-actors').text(failed);
        $('#skipped-actors').text(skipped);
        
        $('#actors-summary').show();
        $('#actors-progress-text').text('Registration complete');
        
        if (failed > 0) {
            $('#retry-actors-registration').show();
        }
        
        $('#continue-to-channels').show();
        registrationInProgress = false;
    }
});
</script>

<style>
.progress-container {
    margin: 20px 0;
    padding: 15px;
    background: #f5f5f5;
    border-radius: 5px;
}

.progress-bar {
    width: 100%;
    height: 20px;
    background: #ddd;
    border-radius: 10px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: #007bff;
    transition: width 0.3s ease;
}

.progress-text {
    text-align: center;
    margin-top: 10px;
    font-weight: bold;
}

.actors-log {
    height: 200px;
    overflow-y: auto;
    background: #000;
    color: #00ff00;
    padding: 10px;
    border-radius: 5px;
    font-family: monospace;
    font-size: 12px;
    margin: 20px 0;
}

.log-entry {
    margin: 2px 0;
}

.log-entry.success {
    color: #00ff00;
}

.log-entry.error {
    color: #ff0000;
}

.log-entry.warning {
    color: #ffff00;
}

.actors-summary {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    margin: 20px 0;
}

.summary-table {
    width: 100%;
    border-collapse: collapse;
}

.summary-table td {
    padding: 8px;
    border-bottom: 1px solid #ddd;
}

.summary-table td:first-child {
    font-weight: bold;
}

.success {
    color: #28a745;
    font-weight: bold;
}

.error {
    color: #dc3545;
    font-weight: bold;
}

.warning {
    color: #ffc107;
    font-weight: bold;
}
</style>
<?php endif; ?>
```

## New Step 6: Channels Configuration

### HTML Template
```html
<!-- Step 6: channels -->
<?php if ($step === 'channels'): ?>
<div class="step-container">
    <h2>Configure Channel-Based Coordination</h2>
    <p class="step-description">
        Assigning channel keys and configuring channel-based coordination for all actors.
        This enables proper message routing and actor communication.
    </p>
    
    <div class="channels-grid">
        <div class="channel-section">
            <h3>System Channels</h3>
            <div class="channel-card">
                <div class="channel-header">
                    <span class="channel-id">Channel 0</span>
                    <span class="channel-name">System Kernel</span>
                </div>
                <div class="channel-actors">
                    <div class="channel-actor">system (0)</div>
                    <div class="channel-actor">wolfie (1)</div>
                    <div class="channel-actor">anubis (9)</div>
                </div>
            </div>
            
            <div class="channel-card">
                <div class="channel-header">
                    <span class="channel-id">Channel 42</span>
                    <span class="channel-name">Protocol Development</span>
                </div>
                <div class="channel-actors">
                    <div class="channel-actor">wolfie (1)</div>
                    <div class="channel-actor">anubis (9)</div>
                    <div class="channel-actor">hermes (14)</div>
                    <div class="channel-actor">iris (13)</div>
                </div>
            </div>
            
            <div class="channel-card">
                <div class="channel-header">
                    <span class="channel-id">Channel 51</span>
                    <span class="channel-name">Doctrine Council</span>
                </div>
                <div class="channel-actors">
                    <div class="channel-actor">lilith (2)</div>
                    <div class="channel-actor">themis (17)</div>
                </div>
            </div>
            
            <div class="channel-card">
                <div class="channel-header">
                    <span class="channel-id">Channel 666</span>
                    <span class="channel-name">ANUBIS Quarantine</span>
                </div>
                <div class="channel-actors">
                    <div class="channel-actor">anubis (9)</div>
                </div>
            </div>
        </div>
        
        <div class="channel-section">
            <h3>Channel Key Assignments</h3>
            <div class="channel-keys-container">
                <table class="channel-keys-table">
                    <thead>
                        <tr>
                            <th>Actor ID</th>
                            <th>Actor Name</th>
                            <th>Channel Key</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="channel-keys-tbody">
                        <!-- Populated by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="channel-progress">
        <div class="progress-bar">
            <div class="progress-fill" id="channel-progress" style="width: 0%"></div>
        </div>
        <div class="progress-text" id="channel-progress-text">Ready to configure channels</div>
    </div>
    
    <div class="step-actions">
        <button type="button" id="configure-channels" class="btn btn-primary">
            Configure Channels
        </button>
        <button type="submit" name="next_step" value="config" class="btn btn-success" id="continue-to-config" style="display: none;">
            Continue to Configuration
        </button>
        <button type="submit" name="prev_step" value="actors" class="btn btn-secondary">
            Back
        </button>
    </div>
</div>

<script>
$(document).ready(function() {
    const actors = [
        {id: 0, name: 'system', channelKey: 'system'},
        {id: 1, name: 'wolfie', channelKey: 'captain'},
        {id: 2, name: 'lilith', channelKey: 'lilith'},
        {id: 3, name: 'rose', channelKey: 'rose'},
        {id: 9, name: 'anubis', channelKey: 'anubis'},
        {id: 14, name: 'hermes', channelKey: 'hermes'},
        {id: 13, name: 'iris', channelKey: 'iris'},
        // ... all actors
    ];
    
    // Populate channel keys table
    function populateChannelKeys() {
        const tbody = $('#channel-keys-tbody');
        tbody.empty();
        
        actors.forEach(actor => {
            const row = `
                <tr>
                    <td>${actor.id}</td>
                    <td>${actor.name}</td>
                    <td>${actor.channelKey}</td>
                    <td><span class="status-badge pending">Pending</span></td>
                </tr>
            `;
            tbody.append(row);
        });
    }
    
    $('#configure-channels').click(function() {
        $(this).prop('disabled', true);
        $('#channel-progress-text').text('Configuring channels...');
        
        let configured = 0;
        const total = actors.length;
        
        actors.forEach((actor, index) => {
            setTimeout(() => {
                // Update status
                const rows = $('#channel-keys-tbody tr');
                $(rows[index]).find('.status-badge')
                    .removeClass('pending')
                    .addClass('success')
                    .text('Configured');
                
                configured++;
                const progress = (configured / total) * 100;
                $('#channel-progress').css('width', progress + '%');
                $('#channel-progress-text').text(`${Math.round(progress)}% - ${configured}/${total} configured`);
                
                if (configured === total) {
                    $('#channel-progress-text').text('Channel configuration complete');
                    $('#continue-to-config').show();
                }
            }, index * 50);
        });
    });
    
    populateChannelKeys();
});
</script>

<style>
.channels-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin: 20px 0;
}

.channel-section h3 {
    margin-bottom: 15px;
    color: #333;
}

.channel-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.channel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.channel-id {
    background: #007bff;
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: bold;
}

.channel-name {
    font-weight: bold;
    color: #333;
}

.channel-actors {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
}

.channel-actor {
    background: #f8f9fa;
    padding: 2px 8px;
    border-radius: 4px;
    font-size: 12px;
    color: #666;
}

.channel-keys-container {
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.channel-keys-table {
    width: 100%;
    border-collapse: collapse;
}

.channel-keys-table th,
.channel-keys-table td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.channel-keys-table th {
    background: #f8f9fa;
    font-weight: bold;
    position: sticky;
    top: 0;
}

.status-badge {
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: bold;
}

.status-badge.pending {
    background: #ffc107;
    color: #000;
}

.status-badge.success {
    background: #28a745;
    color: #fff;
}

.status-badge.error {
    background: #dc3545;
    color: #fff;
}

.channel-progress {
    margin: 20px 0;
}
</style>
<?php endif; ?>
```

## New Step 9: Memory Configuration

### HTML Template
```html
<!-- Step 9: memory -->
<?php if ($step === 'memory'): ?>
<div class="step-container">
    <h2>Configure Memory System</h2>
    <p class="step-description">
        Setting up memory paths and handoff directories for all actors.
        This enables persistent memory storage and actor handoff functionality.
    </p>
    
    <div class="memory-config-container">
        <div class="memory-section">
            <h3>Memory Path Configuration</h3>
            <div class="memory-paths-grid">
                <div class="memory-path-card">
                    <div class="memory-path-header">
                        <i class="icon-memory"></i>
                        <span>Base Memory Path</span>
                    </div>
                    <div class="memory-path-value">
                        <code>lupo-memory/actors/</code>
                    </div>
                    <div class="memory-path-status">
                        <span class="status-badge success">Configured</span>
                    </div>
                </div>
                
                <div class="memory-path-card">
                    <div class="memory-path-header">
                        <i class="icon-handoff"></i>
                        <span>Base Handoff Path</span>
                    </div>
                    <div class="memory-path-value">
                        <code>lupo-handoffs/</code>
                    </div>
                    <div class="memory-path-status">
                        <span class="status-badge success">Configured</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="memory-section">
            <h3>Actor Memory Assignments</h3>
            <div class="actor-memory-list" id="actor-memory-list">
                <!-- Populated by JavaScript -->
            </div>
        </div>
        
        <div class="memory-section">
            <h3>Memory Quotas</h3>
            <div class="quota-config">
                <table class="quota-table">
                    <thead>
                        <tr>
                            <th>Actor Type</th>
                            <th>Default Quota</th>
                            <th>Count</th>
                            <th>Total Allocated</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>System Actors</td>
                            <td>500 MB</td>
                            <td>25</td>
                            <td>12.5 GB</td>
                        </tr>
                        <tr>
                            <td>IDE Agents</td>
                            <td>200 MB</td>
                            <td>15</td>
                            <td>3.0 GB</td>
                        </tr>
                        <tr>
                            <td>Specialized Agents</td>
                            <td>300 MB</td>
                            <td>9</td>
                            <td>2.7 GB</td>
                        </tr>
                        <tr>
                            <td>Meta Agents</td>
                            <td>100 MB</td>
                            <td>2</td>
                            <td>0.2 GB</td>
                        </tr>
                        <tr class="total-row">
                            <td><strong>Total</strong></td>
                            <td></td>
                            <td><strong>51</strong></td>
                            <td><strong>18.4 GB</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="memory-progress">
        <div class="progress-bar">
            <div class="progress-fill" id="memory-progress" style="width: 0%"></div>
        </div>
        <div class="progress-text" id="memory-progress-text">Ready to configure memory</div>
    </div>
    
    <div class="step-actions">
        <button type="button" id="configure-memory" class="btn btn-primary">
            Configure Memory
        </button>
        <button type="submit" name="next_step" value="complete" class="btn btn-success" id="continue-to-complete" style="display: none;">
            Complete Installation
        </button>
        <button type="submit" name="prev_step" value="api_keys" class="btn btn-secondary">
            Back
        </button>
    </div>
</div>

<script>
$(document).ready(function() {
    const actors = [
        {id: 0, name: 'system', memoryPath: 'lupo-memory/actors/0/', quota: 500},
        {id: 1, name: 'wolfie', memoryPath: 'lupo-memory/actors/1/', quota: 500},
        {id: 2, name: 'lilith', memoryPath: 'lupo-memory/actors/2/', quota: 500},
        // ... all actors
    ];
    
    // Populate actor memory list
    function populateActorMemory() {
        const container = $('#actor-memory-list');
        container.empty();
        
        actors.forEach(actor => {
            const card = `
                <div class="actor-memory-card" data-actor-id="${actor.id}">
                    <div class="actor-memory-header">
                        <span class="actor-name">${actor.name}</span>
                        <span class="actor-id">ID: ${actor.id}</span>
                    </div>
                    <div class="actor-memory-details">
                        <div class="memory-path">
                            <label>Memory Path:</label>
                            <code>${actor.memoryPath}</code>
                        </div>
                        <div class="handoff-path">
                            <label>Handoff Path:</label>
                            <code>lupo-handoffs/${actor.name}/</code>
                        </div>
                        <div class="memory-quota">
                            <label>Quota:</label>
                            <span>${actor.quota} MB</span>
                        </div>
                    </div>
                    <div class="actor-memory-status">
                        <span class="status-badge pending">Pending</span>
                    </div>
                </div>
            `;
            container.append(card);
        });
    }
    
    $('#configure-memory').click(function() {
        $(this).prop('disabled', true);
        $('#memory-progress-text').text('Configuring memory system...');
        
        let configured = 0;
        const total = actors.length;
        
        actors.forEach((actor, index) => {
            setTimeout(() => {
                // Update status
                const card = $(`.actor-memory-card[data-actor-id="${actor.id}"]`);
                card.find('.status-badge')
                    .removeClass('pending')
                    .addClass('success')
                    .text('Configured');
                
                configured++;
                const progress = (configured / total) * 100;
                $('#memory-progress').css('width', progress + '%');
                $('#memory-progress-text').text(`${Math.round(progress)}% - ${configured}/${total} configured`);
                
                if (configured === total) {
                    $('#memory-progress-text').text('Memory configuration complete');
                    $('#continue-to-complete').show();
                }
            }, index * 30);
        });
    });
    
    populateActorMemory();
});
</script>

<style>
.memory-config-container {
    display: grid;
    gap: 20px;
    margin: 20px 0;
}

.memory-section h3 {
    margin-bottom: 15px;
    color: #333;
}

.memory-paths-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.memory-path-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.memory-path-header {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-bottom: 10px;
    font-weight: bold;
    color: #333;
}

.memory-path-value {
    margin: 10px 0;
}

.memory-path-value code {
    background: #f8f9fa;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 14px;
}

.actor-memory-list {
    max-height: 400px;
    overflow-y: auto;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 10px;
}

.actor-memory-card {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 5px;
    padding: 15px;
    margin-bottom: 10px;
}

.actor-memory-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.actor-name {
    font-weight: bold;
    color: #333;
}

.actor-id {
    color: #666;
    font-size: 12px;
}

.actor-memory-details {
    display: grid;
    gap: 5px;
    margin-bottom: 10px;
}

.actor-memory-details label {
    font-weight: bold;
    color: #555;
    display: inline-block;
    width: 100px;
}

.actor-memory-details code {
    background: #e9ecef;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 12px;
}

.quota-table {
    width: 100%;
    border-collapse: collapse;
}

.quota-table th,
.quota-table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

.quota-table th {
    background: #f8f9fa;
    font-weight: bold;
}

.total-row {
    background: #f8f9fa;
    font-weight: bold;
}

.memory-progress {
    margin: 20px 0;
}

.icon-memory::before {
    content: "🧠";
    font-size: 20px;
}

.icon-handoff::before {
    content: "🔄";
    font-size: 20px;
}
</style>
<?php endif; ?>
```

## Enhanced Step 2: Credentials (Updated)

### Additions to existing credentials step
```html
<!-- Add to credentials step -->
<div class="form-section">
    <h3>Advanced Options</h3>
    <div class="form-group">
        <label>
            <input type="checkbox" name="create_redteam_user" id="create_redteam_user" value="1">
            Create Red-Team Auth User (ID: 420)
        </label>
        <small>Enable red-team security testing capabilities</small>
    </div>
    
    <div class="form-group">
        <label for="memory_base_path">Memory Base Path</label>
        <input type="text" id="memory_base_path" name="memory_base_path" 
               value="<?php echo LUPOPEDIA_PATH; ?>/lupo-memory" 
               class="form-control">
        <small>Base directory for actor memory storage</small>
    </div>
    
    <div class="form-group">
        <label for="handoff_base_path">Handoff Base Path</label>
        <input type="text" id="handoff_base_path" name="handoff_base_path" 
               value="<?php echo LUPOPEDIA_PATH; ?>/lupo-handoffs" 
               class="form-control">
        <small>Base directory for actor handoff storage</small>
    </div>
</div>
```

## Enhanced Step 8: API Keys (Updated)

### Additions to existing API keys step
```html
<!-- Enhanced API provider list -->
<div class="api-providers-grid">
    <?php
    $providers = [
        'openai' => 'OpenAI',
        'deepseek' => 'DeepSeek',
        'gemini' => 'Gemini',
        'grok' => 'Grok',
        'groq' => 'Groq',
        'anthropic' => 'Anthropic',
        'claude' => 'Claude',
        'perplexity' => 'Perplexity'
    ];
    
    foreach ($providers as $key => $name): ?>
    <div class="api-provider-card">
        <div class="provider-header">
            <img src="assets/icons/<?php echo $key; ?>.png" alt="<?php echo $name; ?>" class="provider-icon">
            <span class="provider-name"><?php echo $name; ?></span>
            <label class="provider-toggle">
                <input type="checkbox" name="api_providers[<?php echo $key; ?>][enabled]" value="1">
                <span class="toggle-slider"></span>
            </label>
        </div>
        <div class="provider-config">
            <input type="password" name="api_providers[<?php echo $key; ?>][api_key]" 
                   placeholder="API Key" class="form-control">
            <input type="text" name="api_providers[<?php echo $key; ?>][model]" 
                   placeholder="Model (optional)" class="form-control">
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Custom providers section -->
<div class="custom-providers-section">
    <h4>Custom Providers</h4>
    <div id="custom-providers-container">
        <div class="custom-provider">
            <input type="text" name="custom_providers[0][name]" placeholder="Provider Name" class="form-control">
            <input type="password" name="custom_providers[0][api_key]" placeholder="API Key" class="form-control">
            <input type="text" name="custom_providers[0][endpoint]" placeholder="API Endpoint" class="form-control">
        </div>
    </div>
    <button type="button" id="add-custom-provider" class="btn btn-secondary">Add Custom Provider</button>
</div>

<script>
$('#add-custom-provider').click(function() {
    const count = $('.custom-provider').length;
    const provider = $(`
        <div class="custom-provider">
            <input type="text" name="custom_providers[${count}][name]" placeholder="Provider Name" class="form-control">
            <input type="password" name="custom_providers[${count}][api_key]" placeholder="API Key" class="form-control">
            <input type="text" name="custom_providers[${count}][endpoint]" placeholder="API Endpoint" class="form-control">
            <button type="button" class="btn btn-danger remove-provider">Remove</button>
        </div>
    `);
    $('#custom-providers-container').append(provider);
});

$(document).on('click', '.remove-provider', function() {
    $(this).closest('.custom-provider').remove();
});
</script>

<style>
.api-providers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.api-provider-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.provider-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 15px;
}

.provider-icon {
    width: 24px;
    height: 24px;
    margin-right: 10px;
}

.provider-name {
    font-weight: bold;
    flex-grow: 1;
}

.provider-toggle {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
}

.provider-toggle input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 24px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .toggle-slider {
    background-color: #007bff;
}

input:checked + .toggle-slider:before {
    transform: translateX(26px);
}

.provider-config {
    display: grid;
    gap: 10px;
}

.custom-providers-section {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #ddd;
}

.custom-provider {
    display: grid;
    grid-template-columns: 2fr 2fr 2fr auto;
    gap: 10px;
    margin-bottom: 10px;
    align-items: center;
}
</style>
```

## Installation Complete Screen (Enhanced)

### Updated completion screen
```html
<!-- Enhanced complete step -->
<?php if ($step === 'complete'): ?>
<div class="step-container">
    <div class="success-header">
        <div class="success-icon">✓</div>
        <h2>Installation Complete!</h2>
        <p>Lupopedia 4.1.3 has been successfully installed with channel-based coordination.</p>
    </div>
    
    <div class="installation-summary">
        <h3>Installation Summary</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <span class="summary-label">Version:</span>
                <span class="summary-value">4.1.3</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Actors Registered:</span>
                <span class="summary-value">47</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Channels Configured:</span>
                <span class="summary-value">4</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">API Providers:</span>
                <span class="summary-value" id="api-provider-count">0</span>
            </div>
        </div>
    </div>
    
    <div class="new-features">
        <h3>New Features in 4.1.3</h3>
        <ul>
            <li>✓ Channel-based coordination system</li>
            <li>✓ 47 registered actors from filesystem</li>
            <li>✓ Memory path configuration</li>
            <li>✓ Extended API provider support</li>
            <li>✓ Red-team user capabilities</li>
            <li>✓ Enhanced security features</li>
        </ul>
    </div>
    
    <div class="next-steps">
        <h3>Next Steps</h3>
        <ol>
            <li><a href="index.php" class="btn btn-primary">Go to Lupopedia</a></li>
            <li><a href="admin/" class="btn btn-secondary">Admin Panel</a></li>
            <li><a href="docs/" class="btn btn-secondary">Documentation</a></li>
        </ol>
    </div>
    
    <div class="installation-log">
        <h3>Installation Log</h3>
        <div class="log-container">
            <pre><?php echo htmlspecialchars($install_log); ?></pre>
        </div>
    </div>
</div>

<style>
.success-header {
    text-align: center;
    margin-bottom: 30px;
}

.success-icon {
    font-size: 48px;
    color: #28a745;
    margin-bottom: 10px;
}

.installation-summary {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin: 20px 0;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    padding: 10px;
    background: white;
    border-radius: 5px;
    border: 1px solid #ddd;
}

.summary-label {
    font-weight: bold;
    color: #555;
}

.summary-value {
    color: #333;
}

.new-features {
    margin: 20px 0;
}

.new-features ul {
    list-style: none;
    padding: 0;
}

.new-features li {
    padding: 5px 0;
    border-bottom: 1px solid #eee;
}

.next-steps {
    margin: 20px 0;
}

.next-steps ol {
    padding-left: 20px;
}

.next-steps li {
    margin: 10px 0;
}

.installation-log {
    margin: 20px 0;
}

.log-container {
    background: #000;
    color: #00ff00;
    padding: 15px;
    border-radius: 5px;
    max-height: 200px;
    overflow-y: auto;
}

.log-container pre {
    margin: 0;
    font-family: monospace;
    font-size: 12px;
}
</style>
<?php endif; ?>
```

## CSS Additions

### Add to installer CSS
```css
/* Additional styles for 4.1.3 installer */
.step-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.form-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin: 20px 0;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-control {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    text-decoration: none;
    display: inline-block;
    margin-right: 10px;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-success {
    background: #28a745;
    color: white;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn:hover {
    opacity: 0.9;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
```

## Implementation Notes

### PHP Backend Changes Required
1. Update `InstallWizardSteps::getWizardSteps()` to include new steps
2. Add step handlers for `actors`, `channels`, and `memory`
3. Update session handling for new form fields
4. Add validation for new configuration options

### JavaScript Enhancements
1. Progress tracking for long-running operations
2. Real-time status updates
3. Dynamic form field generation
4. Client-side validation

### Security Considerations
1. Sanitize all filesystem paths
2. Validate actor IDs and names
3. Check directory permissions
4. Secure API key storage

These updated installer screens provide a comprehensive user interface for the 4.1.3 installation process, including actor registration, channel configuration, and memory system setup.
