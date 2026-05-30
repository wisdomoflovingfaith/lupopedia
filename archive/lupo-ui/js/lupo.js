// lupo-ui/js/lupo.js - Modern The Eye widget

class LupoTheEye {
    constructor() {
        this.baseUrl = window.LUPOPEDIA_SUBDIRECTORY || '/';
        this.sessionId = this.getCookie('lupo_session');
        this.consent = this.getCookie('lupo_consent') === '1';
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        this.init();
    }
    
    getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
        return null;
    }
    
    async apiCall(action, data = {}, method = 'POST') {
        const url = `${this.baseUrl}lupopedia_ajax.php?action=${action}`;
        const options = {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': this.csrfToken
            },
            credentials: 'include'
        };
        
        if (method === 'POST' && data) {
            options.body = JSON.stringify(data);
        } else if (method === 'GET' && data) {
            const params = new URLSearchParams(data);
            return fetch(`${url}&${params}`, options);
        }
        
        const response = await fetch(url, options);
        return response.json();
    }
    
    async trackPage() {
        if (!this.consent) return;
        
        await this.apiCall('track', {
            page_url: window.location.href,
            referrer: document.referrer,
            title: document.title
        });
    }
    
    async loadGoldContexts() {
        const data = await this.apiCall('gold', {}, 'GET');
        if (data.success && data.contexts.length > 0) {
            this.renderGoldIndicator(data.contexts);
        }
    }
    
    async loadContextEdges(pageId) {
        const data = await this.apiCall('context', { page_id: pageId }, 'GET');
        if (data.success && data.edges.length > 0) {
            this.renderContextGraph(data.edges);
        }
    }
    
    renderGoldIndicator(contexts) {
        const goldElement = document.getElementById('lupo-gold-indicator');
        if (goldElement) {
            goldElement.innerHTML = `✨ ${contexts.length} GOLD contexts`;
            goldElement.style.display = 'block';
        }
    }
    
    async renderContextGraph(edges) {
        // Only load D3 if we have edges and D3 not loaded
        if (edges.length === 0) return;
        
        // Dynamic import of D3 (only when needed)
        if (typeof d3 === 'undefined') {
            await this.loadD3();
        }
        
        // Render graph using D3
        this.createGraph(edges);
    }
    
    loadD3() {
        return new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = this.baseUrl + 'assets/js/d3.v7.min.js';
            script.onload = resolve;
            script.onerror = reject;
            document.head.appendChild(script);
        });
    }
    
    createGraph(edges) {
        // D3 graph rendering logic here
        console.log('Rendering graph with', edges.length, 'edges');
        
        // Safety check for graph size
        const maxNodes = window.LUPO_MAX_GRAPH_NODES || 200;
        const maxEdges = window.LUPO_MAX_GRAPH_EDGES || 500;
        
        if (edges.length > maxEdges) {
            console.warn(`Graph too large: ${edges.length} edges (max: ${maxEdges})`);
            this.renderTableView(edges);
            return;
        }
        
        // Create SVG container if it doesn't exist
        let svg = d3.select('#lupo-context-graph');
        if (svg.empty()) {
            svg = d3.select('body').append('svg')
                .attr('id', 'lupo-context-graph')
                .attr('width', 800)
                .attr('height', 600);
        }
        
        // Clear previous content
        svg.selectAll('*').remove();
        
        // Create force simulation
        const simulation = d3.forceSimulation(edges)
            .force('link', d3.forceLink().id(d => d.edge_id))
            .force('charge', d3.forceManyBody().strength(-300))
            .force('center', d3.forceCenter(400, 300));
        
        // Create links
        const link = svg.append('g')
            .selectAll('line')
            .data(edges)
            .enter()
            .append('line')
            .attr('stroke', '#999')
            .attr('stroke-width', d => Math.max(1, d.semantic_weight * 5))
            .attr('stroke-opacity', 0.6);
        
        // Create nodes
        const node = svg.append('g')
            .selectAll('circle')
            .data(edges)
            .enter()
            .append('circle')
            .attr('r', 8)
            .attr('fill', d => {
                const weight = d.weight_score || d.semantic_weight || 0;
                return weight >= (window.LUPO_GOLD_CONTEXT_WEIGHT_MIN || 0.8) ? '#FFD700' : '#4169E1';
            })
            .call(simulation.drag);
        
        // Update positions on tick
        simulation.on('tick', () => {
            link
                .attr('x1', d => d.source.x)
                .attr('y1', d => d.source.y)
                .attr('x2', d => d.target.x)
                .attr('y2', d => d.target.y);
            
            node
                .attr('cx', d => d.x)
                .attr('cy', d => d.y);
        });
    }
    
    renderTableView(edges) {
        // Fallback table view for large graphs
        const table = document.getElementById('lupo-context-table') || this.createTable();
        table.style.display = 'block';
        
        const tbody = table.querySelector('tbody');
        tbody.innerHTML = '';
        
        edges.forEach(edge => {
            const row = tbody.insertRow();
            row.insertCell(0).textContent = edge.edge_type || 'context';
            row.insertCell(1).textContent = edge.semantic_weight || 0;
            row.insertCell(2).textContent = edge.weight_score || 0;
        });
    }
    
    createTable() {
        const table = document.createElement('table');
        table.id = 'lupo-context-table';
        table.style.cssText = `
            display: none;
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        `;
        
        table.innerHTML = `
            <thead>
                <tr>
                    <th>Edge Type</th>
                    <th>Semantic Weight</th>
                    <th>Weight Score</th>
                </tr>
            </thead>
            <tbody></tbody>
        `;
        
        document.body.appendChild(table);
        return table;
    }
    
    init() {
        // Track page view
        this.trackPage();
        
        // Load gold contexts
        this.loadGoldContexts();
        
        // Start heartbeat
        setInterval(() => {
            this.apiCall('heartbeat', {}, 'POST');
        }, 30000);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    window.LupoTheEye = new LupoTheEye();
});
