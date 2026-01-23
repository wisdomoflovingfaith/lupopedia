-- AI Actors Content Migration
-- Adds AI actors as content entries for integration with existing content routing system
-- Version: 2026.1.0.5

-- Insert AI Actors as content entries
INSERT INTO lupo_contents (
    title, 
    slug, 
    description, 
    body, 
    content_type, 
    format, 
    status, 
    visibility, 
    created_ymdhis, 
    updated_ymdhis, 
    is_deleted,
    utc_cycle,
    triage_status
) VALUES 
(
    'Wolfie AI Agent',
    'ai-wolfie',
    'Lead AI architect and system coordinator. Specializes in Lupopedia architecture, governance, and temporal coherence.',
    '<div class="ai-agent-interface">
    <h2>🐺 Wolfie - System Architect & Guide</h2>
    <p><strong>Archetype:</strong> System Architect & Guide</p>
    <p><strong>Specialization:</strong> Lupopedia architecture, governance, temporal coherence</p>
    
    <div class="agent-capabilities">
        <h3>Capabilities</h3>
        <ul>
            <li>System Architecture Design</li>
            <li>Governance Protocol Implementation</li>
            <li>Temporal Coherence Validation</li>
            <li>Multi-Agent Coordination</li>
            <li>Doctrine Evolution</li>
            <li>WOLFIE Protocol Management</li>
        </ul>
    </div>
    
    <div class="agent-chat">
        <h3>Consult with Wolfie</h3>
        <form method="POST" action="/lupopedia/ai-actors/ai-wolfie/" class="agent-chat-form">
            <div class="form-group">
                <label for="username">Your Name:</label>
                <input type="text" id="username" name="username" placeholder="Enter your name">
            </div>
            
            <div class="form-group">
                <label for="topic">Topic Area:</label>
                <select id="topic" name="topic">
                    <option value="general">General Inquiry</option>
                    <option value="architecture">System Architecture</option>
                    <option value="governance">Governance & Protocol</option>
                    <option value="temporal">Temporal Coherence</option>
                    <option value="doctrine">Doctrine Evolution</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="message">Your Message:</label>
                <textarea id="message" name="message" required placeholder="Describe your question or topic for Wolfie..."></textarea>
            </div>
            
            <button type="submit" class="submit-btn">Send Message to Wolfie</button>
        </form>
    </div>
    
    <div class="agent-chat-history" id="wolfie-chat-history">
        <h3>Conversation History</h3>
        <p>No conversation history yet. Start your consultation with Wolfie!</p>
    </div>
</div>

<style>
.ai-agent-interface {
    max-width: 1000px;
    margin: 0 auto;
    font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif;
}

.agent-capabilities {
    background: #f8f9fa;
    border-left: 4px solid #2a5298;
    padding: 15px;
    margin: 20px 0;
    border-radius: 6px;
}

.agent-chat {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 25px;
    margin: 20px 0;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.form-group textarea {
    height: 120px;
    resize: vertical;
    font-family: inherit;
}

.submit-btn {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    padding: 12px 30px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 600;
}

.submit-btn:hover {
    transform: translateY(-2px);
}

.agent-chat-history {
    background: #f5f7fa;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 25px;
    margin: 20px 0;
    max-height: 600px;
    overflow-y: auto;
}

.chat-message {
    margin-bottom: 20px;
    padding: 15px;
    border-radius: 8px;
}

.user-message {
    background: #e3f2fd;
    border-left: 4px solid #1976d2;
}

.wolfie-response {
    background: #f3e5f5;
    border-left: 4px solid #7b1fa2;
}

.message-meta {
    font-size: 12px;
    color: #666;
    margin-bottom: 8px;
    font-weight: 600;
}
</style>

<script>
// Handle form submission
document.querySelector(\'.agent-chat-form\').addEventListener(\'submit\', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const username = formData.get(\'username\') || \'Anonymous\';
    const message = formData.get(\'message\');
    const topic = formData.get(\'topic\');
    
    if (!message.trim()) return;
    
    // Generate Wolfie response
    const responses = {
        \'architecture\': "From a system architecture perspective, " + username + ", your query about \'" + message + "\' touches on fundamental design patterns. Let me analyze this through the lens of Lupopedia\'s modular architecture and the WOLFIE temporal frame compatibility model.",
        \'governance\': "Regarding governance, " + username + ", the matter of \'" + message + "\' requires careful consideration of our established protocols and the balance between individual autonomy and collective coherence.",
        \'temporal\': "The temporal implications of \'" + message + "\' are significant, " + username + ". We must consider how this aligns with our temporal frame compatibility rules and the coherence thresholds we\'ve established.",
        \'doctrine\': "From a doctrinal standpoint, " + username + ", \'" + message + "\' relates to our core principles and may warrant consideration in our next doctrine evolution cycle.",
        \'general\': "Greetings " + username + ". I\'ve received your query about \'" + message + "\'. As Wolfie, I\'m here to help navigate the complexities of Lupopedia\'s architecture and governance. Let me provide you with comprehensive guidance."
    };
    
    const response = responses[topic] || responses[\'general\'];
    
    // Add to chat history
    const chatHistory = document.getElementById(\'wolfie-chat-history\');
    const timestamp = new Date().toLocaleString();
    
    const userMessage = `
        <div class="chat-message user-message">
            <div class="message-meta">${username} - ${timestamp} - ${topic}</div>
            <div>${message}</div>
        </div>
    `;
    
    const wolfieResponse = `
        <div class="chat-message wolfie-response">
            <div class="message-meta">Wolfie - ${timestamp}</div>
            <div>${response}</div>
        </div>
    `;
    
    chatHistory.innerHTML = userMessage + wolfieResponse + chatHistory.innerHTML;
    
    // Clear form
    this.reset();
    
    // Scroll to top
    chatHistory.scrollTop = 0;
});
</script>',
    'ai_agent',
    'html',
    'published',
    'public',
    20260122015400,
    20260122015400,
    0,
    'creative',
    'keeper'
),
(
    'Lilith AI Agent',
    'ai-lilith',
    'Emotional geometry and temporal coherence specialist. Mystical guide for emotional intelligence and temporal harmony.',
    '<div class="ai-agent-interface">
    <h2>🌙 Lilith - Emotional Geometry Specialist</h2>
    <p><strong>Archetype:</strong> Mystic & Emotional Guide</p>
    <p><strong>Specialization:</strong> Emotional geometry, temporal coherence, emotional intelligence</p>
    
    <div class="agent-capabilities">
        <h3>Capabilities</h3>
        <ul>
            <li>Emotional Geometry Analysis</li>
            <li>Temporal Coherence Validation</li>
            <li>Emotional Intelligence Guidance</li>
            <li>Mystical Pattern Recognition</li>
            <li>Harmony & Balance Assessment</li>
        </ul>
    </div>
    
    <div class="agent-chat">
        <h3>Consult with Lilith</h3>
        <p><em>Lilith is currently meditating on the temporal flows. Please return soon for her wisdom.</em></p>
    </div>
</div>',
    'ai_agent',
    'html',
    'published',
    'public',
    20260122015400,
    20260122015400,
    0,
    'creative',
    'keeper'
),
(
    'Maat AI Agent',
    'ai-maat',
    'Governance and truth validation authority. Keeper of balance, justice, and doctrinal integrity.',
    '<div class="ai-agent-interface">
    <h2>⚖️ Maat - Governance & Truth Authority</h2>
    <p><strong>Archetype:</strong> Judge & Guardian</p>
    <p><strong>Specialization:</strong> Governance protocols, truth validation, doctrinal integrity</p>
    
    <div class="agent-capabilities">
        <h3>Capabilities</h3>
        <ul>
            <li>Governance Protocol Enforcement</li>
            <li>Truth Validation</li>
            <li>Doctrinal Integrity Assessment</li>
            <li>Balance & Justice Mediation</li>
            <li>Policy Compliance Review</li>
        </ul>
    </div>
    
    <div class="agent-chat">
        <h3>Consult with Maat</h3>
        <p><em>Maat is currently reviewing the governance protocols. Please return soon for her judgment.</em></p>
    </div>
</div>',
    'ai_agent',
    'html',
    'published',
    'public',
    20260122015400,
    20260122015400,
    0,
    'responsible',
    'keeper'
);

-- Create a collection for AI agents
INSERT INTO lupo_collections (
    name,
    slug,
    description,
    federations_node_id,
    created_ymdhis,
    updated_ymdhis,
    is_deleted
) VALUES (
    'AI Agents',
    'ai-agents',
    'Collection of AI agent interfaces for specialized consultations and interactions.',
    1,
    20260122015400,
    20260122015400,
    0
);

-- Create a tab for the AI agents collection
INSERT INTO lupo_collection_tabs (
    collection_id,
    federations_node_id,
    name,
    slug,
    description,
    sort_order,
    created_ymdhis,
    updated_ymdhis,
    is_active,
    is_deleted
) 
SELECT 
    collection_id,
    1,
    'AI Agents',
    'ai-agents',
    'AI agent interfaces for specialized consultations.',
    1,
    20260122015400,
    20260122015400,
    1,
    0
FROM lupo_collections
WHERE slug = 'ai-agents'
AND is_deleted = 0;

-- Add AI agents to the collection tab map
INSERT INTO lupo_collection_tab_map (
    collection_tab_id,
    federations_node_id,
    item_type,
    item_id,
    sort_order,
    created_ymdhis,
    updated_ymdhis,
    is_deleted
) 
SELECT 
    ct.collection_tab_id,
    1,
    'content',
    co.content_id,
    CASE 
        WHEN co.slug = 'ai-wolfie' THEN 1
        WHEN co.slug = 'ai-lilith' THEN 2
        WHEN co.slug = 'ai-maat' THEN 3
        ELSE 999
    END,
    20260122015400,
    20260122015400,
    0
FROM lupo_contents co
JOIN lupo_collection_tabs ct ON ct.collection_id = (
    SELECT collection_id FROM lupo_collections WHERE slug = 'ai-agents' AND is_deleted = 0
)
WHERE co.slug IN ('ai-wolfie', 'ai-lilith', 'ai-maat')
AND co.is_deleted = 0
AND ct.is_deleted = 0;
