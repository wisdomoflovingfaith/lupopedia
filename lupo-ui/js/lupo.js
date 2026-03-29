/**
 * Lupopedia v4.0.91 State Mirror
 * Actor: ATHENA (12)
 * Doctrine: Dumb UI / Reactive Refraction
 */

const LupoState = {
    // The "Mirror" - only holds what is currently visible in the DOM
    activeContexts: new Map(),

    // Refraction Engine: Calculates UI state from DB Truth
    refract: function(message) {
        const actorId = BigInt(message.actor_id);
        const temporalIndex = Number(actorId % 7n); // 7-color rotation
        
        return {
            color: this.getTemporalColor(temporalIndex),
            isElevated: message.status === 'GOLD',
            timestamp: this.formatLupoTime(message.created_at)
        };
    },

    getTemporalColor: function(index) {
        const palette = ['#ffffff', '#fefdcd', '#cbcefe', '#e1fecd', '#fecdf7', '#fde0cd', '#cdfefd'];
        return palette[index] || '#ffffff';
    },

    formatLupoTime: function(ymdhis) {
        // Expects ymdhis as string: YYYYMMDDHHIISS
        if (!ymdhis || ymdhis.length !== 14) return '';
        return ymdhis.slice(0,4)+'-'+ymdhis.slice(4,6)+'-'+ymdhis.slice(6,8)+' '+ymdhis.slice(8,10)+':'+ymdhis.slice(10,12)+':'+ymdhis.slice(12,14);
    }
};

// No localStorage, IndexedDB, or mutation methods. LupoState is a pure reflector.

/**
 * Lupopedia v4.0.91 Semantic Monitor
 * Actor: ATHENA (12)
 * Purpose: Link UI Refractions to Context Edges.
 */

const SemanticMonitor = {
    lastAudit: Date.now(),

    audit: function(contextId) {
        // 1. Verify 63-bit Positive Integrity
        if (BigInt(contextId) < 0n) {
            this.triggerAlert("Signed-Bit Violation Detected!");
            return false;
        }

        // 2. Map the Edge (Link to Truth)
        const edge = LupoState.activeContexts.get(contextId);
        if (edge && edge.status === 'GOLD') {
            this.updateStatusBar(`Connection to Truth: ${contextId} (Verified)`);
        }
    },

    triggerAlert: function(msg) {
        console.error(`[LILITH DOCTRINE BREACH]: ${msg}`);
        document.body.classList.add('doctrine-fail');
    },

    updateStatusBar: function(msg) {
        let bar = document.getElementById('semantic-status-bar');
        if (!bar) {
            bar = document.createElement('div');
            bar.id = 'semantic-status-bar';
            bar.style.position = 'fixed';
            bar.style.bottom = '0';
            bar.style.left = '0';
            bar.style.width = '100%';
            bar.style.background = '#e1fecd';
            bar.style.color = '#222';
            bar.style.fontSize = '14px';
            bar.style.padding = '4px 12px';
            bar.style.zIndex = '9999';
            document.body.appendChild(bar);
        }
        bar.textContent = msg;
    }
};

// Monitor runs in requestIdleCallback to avoid paint-cycle tax
window.requestIdleCallback && requestIdleCallback(() => {
    for (const contextId of LupoState.activeContexts.keys()) {
        SemanticMonitor.audit(contextId);
    }
});

/**
 * Lupopedia v4.0.91 High-Density Scroller
 * Actor: ATHENA (12)
 * Purpose: 60fps Virtualization for Glass Bubbles.
 */

const HighDensityScroller = {
    viewportHeight: window.innerHeight,
    rowHeight: 64, // Standard high-density bubble height

    render: function(scrollTop) {
        const startIndex = Math.floor(scrollTop / this.rowHeight);
        const endIndex = startIndex + Math.ceil(this.viewportHeight / this.rowHeight);

        // Clear and Re-Refract the Viewport
        this.clearViewport();
        for (let i = startIndex; i <= endIndex; i++) {
            const data = LupoState.getTruthByIndex(i);
            if (data) {
                const bubble = LupoState.refract(data);
                this.injectBubble(bubble, i * this.rowHeight);
                SemanticMonitor.audit(data.context_id); // Monitor as we move
            }
        }
    },

    clearViewport: function() {
        let container = document.getElementById('lupo-bubble-container');
        if (container) container.innerHTML = '';
    },

    injectBubble: function(bubble, top) {
        let container = document.getElementById('lupo-bubble-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'lupo-bubble-container';
            container.style.position = 'relative';
            container.style.width = '100%';
            container.style.height = '100%';
            document.body.appendChild(container);
        }
        const div = document.createElement('div');
        div.className = 'lupo-bubble' + (bubble.isElevated ? ' context-elevated' : '');
        div.style.background = bubble.color;
        div.style.position = 'absolute';
        div.style.left = '0';
        div.style.right = '0';
        div.style.top = top + 'px';
        div.style.height = this.rowHeight + 'px';
        div.style.lineHeight = this.rowHeight + 'px';
        div.style.margin = '0 0 2px 0';
        div.textContent = `ID: ${bubble.timestamp} | Elevation: ${bubble.isElevated ? 'Gold' : 'Normal'}`;
        container.appendChild(div);
    }
};

// Helper for virtualization: get data by index (to be implemented by integration layer)
LupoState.getTruthByIndex = function(index) {
    // Placeholder: Should be replaced with actual data source logic
    if (!window.LUPO_MOCK_DATA) return null;
    return window.LUPO_MOCK_DATA[index] || null;
};

// Attach scroll event for virtualization
window.addEventListener('scroll', function() {
    HighDensityScroller.render(window.scrollY || window.pageYOffset);
});

// Initial render
window.addEventListener('DOMContentLoaded', function() {
    HighDensityScroller.render(window.scrollY || window.pageYOffset);
});

/**
 * Lupopedia v4.0.92 Semantic Search
 * Actor: ATHENA (12)
 * Doctrine: Edge-Weighted Retrieval
 */

const SemanticSearch = {
    // Traverse the State Mirror for keywords
    query: function(term) {
        const results = [];
        LupoState.activeContexts.forEach((data, id) => {
            if (data.text && data.text.includes(term)) {
                // Weighting: Truth (2) > Discussion (1)
                const weight = data.status === 'GOLD' ? 2 : 1;
                results.push({ id, weight });
            }
        });
        return results.sort((a, b) => b.weight - a.weight);
    },

    // Calculate scroll position for a 63-bit ID
    jumpToId: function(targetId) {
        const index = LupoState.getIndexById(targetId);
        if (index !== -1) {
            const top = index * HighDensityScroller.rowHeight;
            window.scrollTo({ top, behavior: 'smooth' });
            SemanticMonitor.audit(targetId); // Re-verify on landing
        }
    }
};

// Helper for SemanticSearch: get index by ID (to be implemented by integration layer)
LupoState.getIndexById = function(targetId) {
    if (!window.LUPO_MOCK_DATA) return -1;
    for (let i = 0; i < window.LUPO_MOCK_DATA.length; i++) {
        if (window.LUPO_MOCK_DATA[i].context_id === targetId) return i;
    }
    return -1;
};

/**
 * Lupopedia v4.0.92 Search UI
 * Actor: ATHENA (12)
 * Purpose: Semi-transparent legacy modal.
 */

const SearchUI = {
    visible: false,

    toggle: function() {
        this.visible = !this.visible;
        const modal = document.getElementById('lupo-search-modal');
        if (modal) {
            modal.style.display = this.visible ? 'block' : 'none';
            if (this.visible) document.getElementById('lupo-search-input').focus();
        }
    },

    handleInput: function(term) {
        const results = SemanticSearch.query(term).map(r => {
            // Add preview text for display
            const data = LupoState.activeContexts.get(r.id);
            return {
                ...r,
                preview: data && data.text ? data.text.slice(0, 48) : ''
            };
        });
        this.renderResults(results);
    },

    renderResults: function(results) {
        const list = document.getElementById('lupo-results-list');
        if (!list) return;
        list.innerHTML = results.map(r => `
            <li onclick="SemanticSearch.jumpToId('${r.id}')" class="${r.weight > 1 ? 'gold-result' : ''}">
                <span style='color:${r.weight > 1 ? '#FFD700' : '#888'};'>&#9679;</span> [${r.id}] - ${r.preview}...
            </li>
        `).join('');
    }
};

// Modal HTML/CSS injection (legacy glass style)
window.addEventListener('DOMContentLoaded', function() {
    if (!document.getElementById('lupo-search-modal')) {
        const modal = document.createElement('div');
        modal.id = 'lupo-search-modal';
        modal.style.display = 'none';
        modal.style.position = 'fixed';
        modal.style.top = '10%';
        modal.style.left = '50%';
        modal.style.transform = 'translateX(-50%)';
        modal.style.width = '480px';
        modal.style.background = 'rgba(255,255,255,0.92)';
        modal.style.border = '2px solid #cbcefe';
        modal.style.borderRadius = '16px';
        modal.style.boxShadow = '0 8px 32px rgba(0,0,0,0.18)';
        modal.style.zIndex = '10001';
        modal.style.padding = '24px 24px 12px 24px';
        modal.innerHTML = `
            <input id='lupo-search-input' type='text' placeholder='Search context...' style='width:100%;font-size:18px;padding:8px 12px;margin-bottom:12px;border-radius:8px;border:1px solid #ccc;'>
            <ul id='lupo-results-list' style='list-style:none;padding:0;margin:0;max-height:320px;overflow-y:auto;'></ul>
            <div style='text-align:right;margin-top:8px;'><button onclick='SearchUI.toggle()'>Close</button></div>
        `;
        document.body.appendChild(modal);
        document.getElementById('lupo-search-input').addEventListener('input', function(e) {
            SearchUI.handleInput(e.target.value);
        });
    }
});

// Gold result CSS
const style = document.createElement('style');
style.innerHTML = `.gold-result { font-weight:bold; background:rgba(255,215,0,0.08); }`;
document.head.appendChild(style);

// Ctrl+F interception
window.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
        e.preventDefault();
        SearchUI.toggle();
    }
});
