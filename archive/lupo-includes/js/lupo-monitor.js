/*
 * Lupopedia visitor chrome + semantic page tracking (PRD 28 companion).
 * Requires window.LUPO_BOOTSTRAP (and/or window.LUPO_CONFIG alias) from lupopedia_js.php.
 * Tracking: lupo-ajax.php?action=csrf_token then ?action=track (JSON + CSRF) — not FormData api/track.php.
 * Chrome: Lupopedia system blue #0000FF (WOLFIE / blueeye.gif lineage); no glassmorphism.
 *
 * Browser baseline: prefers fetch (2020+ stacks). If fetch is missing, tracking uses XMLHttpRequest
 * (same-origin credentials) so heritage IE / old WebKit nodes still POST visits without a polyfill.
 * Command bar still needs adequate CSS/DOM (IE9+ realistic floor).
 *
 * Constitutional: RULE 93.UI_LAYERS — command bar uses LupoLayer; no eval / string timers.
 */
/* global window, document */
(function (window, document) {
    'use strict';

    function getBootstrap() {
        return window.LUPO_BOOTSTRAP || window.LUPO_CONFIG || null;
    }

    /**
     * Semantic shell: help_guide → tutorial edges; text/markdown → transcript edges (PRD 28).
     */
    function getSemanticEdgeFocus() {
        var el = document.querySelector('.resources-middle-center[data-lupo-edge-focus]');
        if (el && el.getAttribute) {
            var v = el.getAttribute('data-lupo-edge-focus');
            if (v) {
                return v;
            }
        }
        var el2 = document.querySelector('.resources-middle-center[data-lupo-artifact-type]');
        if (el2 && el2.getAttribute) {
            var at = el2.getAttribute('data-lupo-artifact-type') || '';
            if (at === 'help_guide') {
                return 'tutorial';
            }
            if (at === 'text/markdown') {
                return 'transcript';
            }
        }
        return '';
    }

    function injectChromeCss(colors) {
        if (document.getElementById('lupo-monitor-chrome-style')) {
            return;
        }
        var c = colors || {};
        var bg = c.chrome || '#0000FF';
        var bd = c.chromeDark || '#000080';
        var hi = c.chromeLight || '#3333FF';
        var tx = c.chromeText || '#ffffff';
        var style = document.createElement('style');
        style.id = 'lupo-monitor-chrome-style';
        style.type = 'text/css';
        style.appendChild(document.createTextNode(
            '#lupoVisitorCommandBarDiv{position:fixed;left:0;right:0;bottom:0;z-index:9998;margin:0;padding:8px 12px;' +
            'font-family:Segoe UI,Tahoma,Geneva,Verdana,sans-serif;pointer-events:none;}' +
            '#lupoVisitorCommandBarDiv .lupo-chrome-inner{pointer-events:auto;display:flex;flex-direction:row;align-items:center;' +
            'justify-content:center;gap:10px;max-width:720px;margin:0 auto;padding:8px 14px;border-radius:8px;' +
            'border:1px solid ' + bd + ';background:' + bg + ';box-shadow:0 -2px 12px rgba(0,0,0,0.25);}' +
            '#lupoVisitorCommandBarDiv .lupo-chrome-btn{min-width:40px;min-height:36px;padding:6px 10px;border-radius:6px;' +
            'border:1px solid ' + bd + ';background:' + hi + ';color:' + tx + ';cursor:pointer;font-size:16px;line-height:1.2;}' +
            '#lupoVisitorCommandBarDiv .lupo-chrome-btn:hover{background:' + bd + ';}' +
            '#lupoVisitorCommandBarDiv .lupo-chrome-btn:focus{outline:2px solid ' + tx + ';outline-offset:2px;}'
        ));
        (document.head || document.documentElement).appendChild(style);
    }

    function dispatchLupoCommand(action, detail) {
        var d = detail || {};
        d.action = action;
        if (d.edge_focus) {
            try {
                window.LUPO_SEMANTIC_EDGE_FOCUS = d.edge_focus;
            } catch (eF) {
                /* no-op */
            }
        }
        try {
            if (typeof window.CustomEvent === 'function') {
                window.dispatchEvent(new window.CustomEvent('lupo:command', { detail: d }));
                return;
            }
        } catch (e0) {
            /* continue */
        }
        if (document.createEvent) {
            try {
                var ev = document.createEvent('CustomEvent');
                if (ev.initCustomEvent) {
                    ev.initCustomEvent('lupo:command', false, false, d);
                } else {
                    ev.initEvent('lupo:command', false, false);
                }
                window.dispatchEvent(ev);
            } catch (e1) {
                /* no-op */
            }
        }
    }

    function wireCommandBar() {
        var bar = document.getElementById('lupoVisitorCommandBarDiv');
        if (!bar) {
            return;
        }
        bar.addEventListener('click', function (ev) {
            var t = ev.target;
            if (!t || !t.getAttribute) {
                return;
            }
            var action = t.getAttribute('data-lupo-action');
            if (!action) {
                return;
            }
            if (action === 'livehelp') {
                toggleLiveHelpLayer();
            } else if (action === 'paths') {
                dispatchLupoCommand('paths', { edge_focus: getSemanticEdgeFocus() });
            } else if (action === 'tags') {
                dispatchLupoCommand('tags', { edge_focus: getSemanticEdgeFocus() });
            } else if (action === 'comments') {
                dispatchLupoCommand('comments');
            }
        });
    }

    function toggleLiveHelpLayer() {
        var el = document.getElementById('livehelpblockDiv');
        if (!el) {
            dispatchLupoCommand('livehelp', { reason: 'no_livehelpblockDiv' });
            return;
        }
        if (typeof window.LupoLayer === 'undefined') {
            el.style.display = (el.style.display === 'none' || !el.style.display) ? 'block' : 'none';
            return;
        }
        var prefix = 'livehelpblock';
        var layer = window[prefix];
        if (!layer || typeof layer.show !== 'function') {
            layer = new window.LupoLayer('livehelpblockDiv');
            window[prefix] = layer;
        }
        if (typeof layer.css !== 'undefined' && layer.css && layer.css.display === 'none') {
            layer.show();
        } else if (el.style && el.style.display === 'none') {
            layer.show();
        } else if (typeof layer.hide === 'function') {
            layer.hide();
        }
    }

    function buildCommandBar() {
        var b = getBootstrap();
        if (!b || !b.commandBar) {
            return;
        }
        injectChromeCss(b.themeColors);
        if (document.getElementById('lupoVisitorCommandBarDiv')) {
            wireCommandBar();
            return;
        }
        var wrap = document.createElement('div');
        wrap.id = 'lupoVisitorCommandBarDiv';
        wrap.setAttribute('role', 'navigation');
        wrap.setAttribute('aria-label', 'Lupopedia quick actions');
        wrap.innerHTML =
            '<div class="lupo-chrome-inner">' +
            '<button type="button" class="lupo-chrome-btn" data-lupo-action="paths" title="Paths" aria-label="Paths">&#8592;</button>' +
            '<button type="button" class="lupo-chrome-btn" data-lupo-action="tags" title="Tags" aria-label="Tags">#</button>' +
            '<button type="button" class="lupo-chrome-btn" data-lupo-action="comments" title="Comments" aria-label="Comments">&#128172;</button>' +
            '<button type="button" class="lupo-chrome-btn" data-lupo-action="livehelp" title="Live help" aria-label="Live help">&#128065;</button>' +
            '</div>';
        document.body.appendChild(wrap);
        if (typeof window.LupoLayer !== 'undefined') {
            window.lupoVisitorCommandBar = new window.LupoLayer('lupoVisitorCommandBarDiv');
        }
        wireCommandBar();
    }

    function queryCampaignParams() {
        var out = {};
        try {
            var p = new URLSearchParams(window.location.search);
            var c = p.get('utm_campaign') || p.get('campaign');
            if (c) {
                out.campaign = c;
            }
        } catch (e1) {
            /* IE / very old engines: skip */
        }
        return out;
    }

    function buildTrackBody(extra) {
        var body = {
            page_url: window.location.href,
            referrer: document.referrer || ''
        };
        if (extra && extra.campaign) {
            body.campaign = extra.campaign;
        }
        var ef = getSemanticEdgeFocus();
        if (ef) {
            body.semantic_edge_focus = ef;
        }
        var elAt = document.querySelector('.resources-middle-center[data-lupo-artifact-type]');
        if (elAt && elAt.getAttribute) {
            var at2 = elAt.getAttribute('data-lupo-artifact-type');
            if (at2) {
                body.semantic_artifact_type = at2;
            }
        }
        return body;
    }

    /**
     * CSRF GET + JSON track POST for engines without fetch (IE / old WebKit).
     */
    function runTrackingWithXHR(ajax, extra) {
        if (typeof window.XMLHttpRequest === 'undefined') {
            return;
        }
        var csrfUrl = ajax + (ajax.indexOf('?') >= 0 ? '&' : '?') + 'action=csrf_token';
        var x1 = new XMLHttpRequest();
        x1.open('GET', csrfUrl, true);
        x1.withCredentials = true;
        x1.onreadystatechange = function () {
            if (x1.readyState !== 4) {
                return;
            }
            var token = '';
            if (x1.status >= 200 && x1.status < 300 && x1.responseText) {
                try {
                    var j = JSON.parse(x1.responseText);
                    if (j && j.csrf_token) {
                        token = j.csrf_token;
                    }
                } catch (eJ) {
                    /* silent */
                }
            }
            var body = buildTrackBody(extra);
            var x2 = new XMLHttpRequest();
            x2.open('POST', ajax + '?action=track', true);
            x2.withCredentials = true;
            try {
                x2.setRequestHeader('Content-Type', 'application/json');
                x2.setRequestHeader('X-CSRF-TOKEN', token);
            } catch (eH) {
                /* IE: ignore */
            }
            try {
                x2.send(JSON.stringify(body));
            } catch (eS) {
                /* silent */
            }
        };
        try {
            x1.send(null);
        } catch (e0) {
            /* silent */
        }
    }

    function runTracking() {
        var b = getBootstrap();
        var ajax = b && (b.ajaxUrl || b.apiUrl);
        if (!b || !b.configured || !b.tracking || !ajax) {
            return;
        }
        var extra = queryCampaignParams();
        if (typeof window.fetch !== 'undefined') {
            var csrfUrl = ajax + (ajax.indexOf('?') >= 0 ? '&' : '?') + 'action=csrf_token';
            window.fetch(csrfUrl, { credentials: 'same-origin', method: 'GET' })
                .then(function (r) {
                    return r.json();
                })
                .then(function (j) {
                    var token = j && j.csrf_token ? j.csrf_token : '';
                    var body = buildTrackBody(extra);
                    return window.fetch(ajax + '?action=track', {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify(body)
                    });
                })
                .catch(function () {
                    /* silent: hostile hosts / adblock / offline */
                });
            return;
        }
        runTrackingWithXHR(ajax, extra);
    }

    /**
     * If legacy WOLFIE eye globals exist (dynlayer heritage), keep anchor on resize — "jumping squirrel" mitigation.
     */
    function attachLegacyEyeResizeGuard() {
        if (typeof window.addEventListener === 'undefined') {
            return;
        }
        window.addEventListener('resize', function () {
            if (typeof window.wheretoX === 'undefined') {
                return;
            }
            try {
                var el = document.querySelector('.resources-middle-center');
                if (el && typeof el.getBoundingClientRect === 'function') {
                    var r = el.getBoundingClientRect();
                    var w = 520;
                    var h = 320;
                    window.wheretoX = Math.max(0, Math.round(r.left + r.width - w));
                    window.wheretoY = Math.max(0, Math.round(r.top + r.height - h));
                } else {
                    window.wheretoX = window.innerWidth - 450;
                    window.wheretoY = window.innerHeight - 300;
                }
                if (window.backblock && typeof window.backblock.moveTo === 'function') {
                    window.backblock.moveTo(window.wheretoX + 181, window.wheretoY + 150);
                }
            } catch (eR) {
                /* no-op */
            }
        });
    }

    function init() {
        try {
            var ef0 = getSemanticEdgeFocus();
            if (ef0) {
                window.LUPO_SEMANTIC_EDGE_FOCUS = ef0;
            }
        } catch (eInit) {
            /* no-op */
        }
        buildCommandBar();
        runTracking();
        attachLegacyEyeResizeGuard();
    }

    window.LupoSemanticMonitor = {
        init: init,
        buildCommandBar: buildCommandBar,
        runTracking: runTracking,
        attachLegacyEyeResizeGuard: attachLegacyEyeResizeGuard
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
}(typeof window !== 'undefined' ? window : this, typeof document !== 'undefined' ? document : {}));
