<?php
/**
 * Semantic Navbar JS Generator (4.0.71)
 *
 * Outputs JavaScript to render a premium floating semantic navbar.
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die('Config not loaded.');
}

$base = defined('LUPOPEDIA_PUBLIC_PATH') ? LUPOPEDIA_PUBLIC_PATH : '/';
$slug = isset($_GET['slug']) ? (string)$_GET['slug'] : '';

header('Content-Type: application/javascript; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');
?>
(function() {
    const WEBPATH = "<?= addslashes(rtrim($base, '/')) ?>";
    const CURRENT_SLUG = "<?= addslashes($slug) ?>";

    const styles = `
        .lupo-navbar {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            background: rgba(20, 20, 20, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 40px;
            padding: 8px 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
            z-index: 999999;
            gap: 16px;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .lupo-navbar:hover {
            background: rgba(30, 30, 30, 0.95);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 12px 48px rgba(0, 0, 0, 0.7);
        }
        .lupo-nav-item {
            position: relative;
            cursor: pointer;
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 20px;
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .lupo-nav-item:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
        }
        .lupo-nav-item.active {
            color: #fff;
            background: linear-gradient(135deg, #6e8efb, #a777e3);
        }
        .lupo-popover {
            position: absolute;
            bottom: 60px;
            left: 50%;
            transform: translateX(-50%) translateY(10px);
            background: #1a1a1a;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            width: 300px;
            max-height: 400px;
            overflow-y: auto;
            display: none;
            flex-direction: column;
            padding: 12px;
            box-shadow: 0 16px 64px rgba(0, 0, 0, 0.8);
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
        }
        .lupo-nav-item:hover .lupo-popover {
            display: flex;
            opacity: 1;
            transform: translateX(-50%) translateY(0);
            pointer-events: auto;
        }
        .lupo-popover-header {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255, 255, 255, 0.4);
            margin-bottom: 8px;
            padding: 0 4px;
        }
        .lupo-popover-item {
            padding: 10px;
            border-radius: 8px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-size: 13px;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .lupo-popover-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        .lupo-loading-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-top-color: #fff;
            border-radius: 50%;
            animation: lupo-spin 0.8s linear infinite;
            margin: 20px auto;
        }
        @keyframes lupo-spin {
            to { transform: rotate(360deg); }
        }
    `;

    const injectStyles = () => {
        const styleTag = document.createElement('style');
        styleTag.textContent = styles;
        document.head.appendChild(styleTag);
    };

    const renderNavbar = () => {
        const nav = document.createElement('div');
        nav.className = 'lupo-navbar';
        
        const items = [
            { id: 'edges', label: 'Edges', icon: '🔗' },
            { id: 'contexts', label: 'Contexts', icon: '📦' },
            { id: 'folders', label: 'Folders', icon: '📁' },
            { id: 'hashtags', label: 'Tags', icon: '🏷️' },
            { id: 'qa', label: 'Q/A', icon: '❓' }
        ];

        items.forEach(item => {
            const el = document.createElement('div');
            el.className = 'lupo-nav-item';
            el.innerHTML = `<span>${item.icon} ${item.label}</span>`;
            
            const popover = document.createElement('div');
            popover.className = 'lupo-popover';
            popover.innerHTML = `
                <div class="lupo-popover-header">${item.label}</div>
                <div class="lupo-popover-content" id="popover-content-${item.id}">
                    <div class="lupo-loading-spinner"></div>
                </div>
            `;
            el.appendChild(popover);

            el.addEventListener('mouseenter', () => {
                fetchData(item.id, popover.querySelector('.lupo-popover-content'));
            });

            nav.appendChild(el);
        });

        document.body.appendChild(nav);
    };

    const fetchData = async (type, container) => {
        if (container.dataset.loaded === 'true') return;
        
        try {
            const response = await fetch(`${WEBPATH}/${type}/${CURRENT_SLUG}`);
            const result = await response.json();
            
            if (result.success && result.data.length > 0) {
                container.innerHTML = '';
                result.data.forEach(item => {
                    const div = document.createElement('a');
                    div.className = 'lupo-popover-item';
                    div.href = type === 'qa' ? '#' : (item.slug ? `${WEBPATH}/${item.slug}` : '#');
                    div.innerHTML = `
                        <span style="opacity: 0.6">${type === 'qa' ? '❔' : '📄'}</span>
                        <span>${item.name || item.label || item.question || item.title || 'Untitled'}</span>
                    `;
                    container.appendChild(div);
                });
                container.dataset.loaded = 'true';
            } else {
                container.innerHTML = '<div style="padding: 10px; color: rgba(255,255,255,0.4); font-size: 12px;">No items found.</div>';
            }
        } catch (e) {
            container.innerHTML = '<div style="padding: 10px; color: #ff4d4d; font-size: 12px;">Error loading data.</div>';
        }
    };

    injectStyles();
    renderNavbar();
})();
