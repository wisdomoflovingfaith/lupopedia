/**
 * Admin intro: 7 chips (80×60) center → radius bloom + rotate → flush top row (gap 0) → scroll mid expands.
 * Chip layout must match CSS --lupo-admin-nav-chip-w / --lupo-admin-nav-chip-h and GAP (0 = no seam).
 * Vanilla JS (no GSAP). Runs once per tab until cleared (logout.php removes the key so the next login replays).
 */
/* global window, document */
(function (window, document) {
    'use strict';

    var STORAGE_KEY = 'lupo_admin_scroll_intro_v1';
    var RADIUS = 200;
    var CHIP_W = 80;
    var GAP = 0;
    var DURATION_BLOOM_MS = 1400;
    var DURATION_ROW_MS = 1000;
    var PAUSE_BEFORE_SCROLL_MS = 400;

    function prefersReducedMotion() {
        if (!window.matchMedia) {
            return false;
        }
        return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    }

    function getSquares() {
        return document.querySelectorAll('#lupo-admin-intro-overlay .lupo-admin-intro-sq');
    }

    function navSlotLeft(i, ww, n) {
        var count = n > 0 ? n : 7;
        var total = count * CHIP_W + (count - 1) * GAP;
        var start = (ww - total) / 2;
        return start + i * (CHIP_W + GAP);
    }

    function runBloom(squares) {
        var i;
        var n = squares.length;
        var step = n > 0 ? 360 / n : 51.428571;
        for (i = 0; i < squares.length; i++) {
            var el = squares[i];
            var deg = ((i * step) * Math.PI) / 180;
            var tx = Math.cos(deg) * RADIUS;
            var ty = Math.sin(deg) * RADIUS;
            el.style.transition = 'transform ' + (DURATION_BLOOM_MS / 1000) + 's cubic-bezier(0.22, 1, 0.36, 1)';
            el.style.transform = 'translate(' + tx + 'px,' + ty + 'px) rotate(360deg)';
        }
    }

    function runRow(squares) {
        var i;
        var ww = window.innerWidth || document.documentElement.clientWidth || 800;
        var n = squares.length;
        for (i = 0; i < squares.length; i++) {
            var el = squares[i];
            var left = navSlotLeft(i, ww, n);
            el.style.transition =
                'left ' +
                DURATION_ROW_MS / 1000 +
                's cubic-bezier(0.34, 1.3, 0.64, 1), top ' +
                DURATION_ROW_MS / 1000 +
                's cubic-bezier(0.34, 1.3, 0.64, 1), margin ' +
                DURATION_ROW_MS / 1000 +
                's ease-out, transform ' +
                DURATION_ROW_MS / 1000 +
                's ease-out';
            el.style.left = left + 'px';
            el.style.top = '0px';
            el.style.marginLeft = '0';
            el.style.marginTop = '0';
            el.style.transform = 'translate(0,0) rotate(0deg)';
        }
    }

    function finishIntro(app, overlay, shell) {
        overlay.classList.add('lupo-admin-intro--hidden');
        app.classList.add('lupo-admin-app--scroll-mode');
        app.classList.add('lupo-admin-app--shell-ready');

        window.setTimeout(function () {
            app.classList.add('lupo-admin-app--mid-expanded');
        }, PAUSE_BEFORE_SCROLL_MS);

        window.setTimeout(function () {
            if (overlay.parentNode) {
                overlay.parentNode.removeChild(overlay);
            }
        }, 600);

        try {
            window.sessionStorage.setItem(STORAGE_KEY, '1');
        } catch (e1) {
            /* ignore */
        }
    }

    function skipToFinal(app, overlay, shell) {
        overlay.parentNode.removeChild(overlay);
        app.classList.add('lupo-admin-app--scroll-mode');
        app.classList.add('lupo-admin-app--shell-ready');
        app.classList.add('lupo-admin-app--mid-expanded');
    }

    function start() {
        var app = document.getElementById('lupo-admin-app');
        var overlay = document.getElementById('lupo-admin-intro-overlay');
        var shell = document.getElementById('lupo-admin-scroll-shell');
        if (!app || !overlay || !shell) {
            return;
        }

        if (app.getAttribute('data-admin-intro') === '0') {
            skipToFinal(app, overlay, shell);
            return;
        }

        var skipStorage = false;
        try {
            skipStorage = window.sessionStorage.getItem(STORAGE_KEY) === '1';
        } catch (e2) {
            skipStorage = false;
        }

        if (skipStorage) {
            skipToFinal(app, overlay, shell);
            return;
        }

        if (prefersReducedMotion()) {
            skipToFinal(app, overlay, shell);
            try {
                window.sessionStorage.setItem(STORAGE_KEY, '1');
            } catch (e3) {
                /* ignore */
            }
            return;
        }

        var squares = getSquares();
        if (!squares || squares.length === 0) {
            skipToFinal(app, overlay, shell);
            return;
        }

        window.setTimeout(function () {
            runBloom(squares);
        }, 50);

        window.setTimeout(function () {
            runRow(squares);
        }, 50 + DURATION_BLOOM_MS + 350);

        window.setTimeout(function () {
            finishIntro(app, overlay, shell);
        }, 50 + DURATION_BLOOM_MS + 350 + DURATION_ROW_MS + 500);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', start, false);
    } else {
        window.setTimeout(start, 0);
    }
})(window, document);
