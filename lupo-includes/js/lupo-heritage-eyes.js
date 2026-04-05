/*
 * LupoHeritageEyes — self-contained WOLFIE sprite eyes (no DynLayer / lupo-layers).
 * Expects window.LUPO_HERITAGE_EYES = { imgBase, livehelpUrl, delayMs } set before this file loads.
 * DOM: optional #lupo-heritage-master-stage (position:fixed, single z-index, pointer-events:none).
 * Layer ids: closedblockDiv, backblockDiv, lefteyeblockDiv, righteyeblockDiv, lidsblockDiv,
 *            livehelpblockDiv, eye-close-btn; img ids: lupo-heritage-* (see heritage-eyes.php).
 * Mouse uses clientX/clientY so pupil math matches the fixed viewport coordinate system.
 *
 * Constitutional: no string eval/setTimeout; function refs + requestAnimationFrame for the eye loop.
 */
/* global window, document */
(function (window, document) {
    'use strict';

    var state = {
        active: false,
        dismissed: false,
        xmouse: 0,
        ymouse: 0,
        speed: 1,
        blinks: 0,
        eyesColor: 'blue',
        ctrPosL: 0,
        ctrPosT: 0,
        init1x: 0,
        init1y: 0,
        init2x: 0,
        init2y: 0,
        current1x: 0,
        current1y: 0,
        current2x: 0,
        current2y: 0,
        rafId: null,
        imgs: {}
    };

    var els = {};

    function $(id) {
        return document.getElementById(id);
    }

    function setPos(el, x, y, withTransition) {
        if (!el || !el.style) {
            return;
        }
        if (withTransition) {
            el.style.transition = 'left 0.45s ease-out, top 0.45s ease-out';
        } else {
            el.style.transition = '';
        }
        el.style.left = Math.round(x) + 'px';
        el.style.top = Math.round(y) + 'px';
    }

    function showVis(el) {
        if (el && el.style) {
            el.style.visibility = 'visible';
        }
    }

    function hideVis(el) {
        if (el && el.style) {
            el.style.visibility = 'hidden';
        }
    }

    function preload(src) {
        var im = new Image();
        im.src = src;
        return im;
    }

    function readMouse(e) {
        var ev = e || window.event;
        if (!ev) {
            return;
        }
        if (typeof ev.clientX === 'number') {
            state.xmouse = ev.clientX;
            state.ymouse = ev.clientY;
        } else if (typeof ev.pageX === 'number') {
            state.xmouse = ev.pageX;
            state.ymouse = ev.pageY;
        }
    }

    function onMouseMove(e) {
        readMouse(e);
    }

    function onResize() {
        if (!state.active) {
            return;
        }
        computeCluster();
        state.current1x = state.init1x;
        state.current1y = state.init1y;
        state.current2x = state.init2x;
        state.current2y = state.init2y;
        layoutFace(true);
        placeCloseButton();
        flyLivehelpIn();
    }

    function applyClusterOrigin(L, T) {
        state.ctrPosL = L;
        state.ctrPosT = T;
        state.init1x = L + 255;
        state.init1y = T + 185 + 11;
        state.init2x = L + 440 + 10;
        state.init2y = T + 180 + 11;
        state.current1x = state.init1x;
        state.current1y = state.init1y;
        state.current2x = state.init2x;
        state.current2y = state.init2y;
    }

    function computeCluster() {
        var w = window.innerWidth || document.documentElement.clientWidth || 0;
        var h = window.innerHeight || document.documentElement.clientHeight || 0;
        applyClusterOrigin(w - 350, h - 370);
    }

    function computeClusterOffscreen() {
        var w = window.innerWidth || document.documentElement.clientWidth || 0;
        var h = window.innerHeight || document.documentElement.clientHeight || 0;
        applyClusterOrigin(w + 350, h + 370);
    }

    function layoutFace(transition) {
        var L = state.ctrPosL;
        var T = state.ctrPosT;
        setPos(els.closed, L + 195, T + 166, transition);
        setPos(els.lids, L + 195, T + 166, transition);
        setPos(els.back, L + 181, T + 150, transition);
        setPos(els.left, state.current1x, state.current1y, transition);
        setPos(els.right, state.current2x, state.current2y, transition);
    }

    function placeCloseButton() {
        if (!els.xbtn) {
            return;
        }
        setPos(els.xbtn, state.ctrPosL + 195, state.ctrPosT + 5, true);
    }

    function placeLivehelpOffscreen() {
        setPos(els.live, -150, -150, false);
    }

    function flyLivehelpIn() {
        var w = window.innerWidth || document.documentElement.clientWidth || 0;
        var h = window.innerHeight || document.documentElement.clientHeight || 0;
        setPos(els.live, w - 160, h - 240, true);
    }

    function flyLivehelpOut() {
        var w = window.innerWidth || document.documentElement.clientWidth || 0;
        var h = window.innerHeight || document.documentElement.clientHeight || 0;
        setPos(els.live, w + 160, h + 240, true);
    }

    function stepEyes() {
        var targetx1 = ((state.xmouse - state.init1x) / 30) + state.init1x;
        var targety1 = ((state.ymouse - state.init1y) / 30) + state.init1y;
        var targetx2 = ((state.xmouse - state.init2x) / 30) + state.init2x;
        var targety2 = ((state.ymouse - state.init2y) / 30) + state.init2y;

        if (targety1 > state.current1y && state.current1y < state.init1y + 13) {
            state.current1y += state.speed;
        }
        if (targety1 < state.current1y && state.current1y > state.init1y - 7) {
            state.current1y -= state.speed;
        }
        if (targetx1 > state.current1x && state.current1x < state.init1x + 11) {
            state.current1x += state.speed;
        }
        if (targetx1 < state.current1x && state.current1x > state.init1x - 14) {
            state.current1x -= state.speed;
        }
        if (targety2 > state.current2y && state.current2y < state.init2y + 13) {
            state.current2y += state.speed;
        }
        if (targety2 < state.current2y && state.current2y > state.init2y - 7) {
            state.current2y -= state.speed;
        }
        if (targetx2 > state.current2x && state.current2x < state.init2x + 20) {
            state.current2x += state.speed;
        }
        if (targetx2 < state.current2x && state.current2x > state.init2x - 10) {
            state.current2x -= state.speed;
        }

        setPos(els.left, state.current1x, state.current1y, false);
        setPos(els.right, state.current2x, state.current2y, false);
    }

    function eyeLoop() {
        if (!state.active) {
            state.rafId = null;
            return;
        }
        stepEyes();
        state.rafId = window.requestAnimationFrame(eyeLoop);
    }

    function startEyeLoop() {
        if (state.rafId) {
            window.cancelAnimationFrame(state.rafId);
        }
        state.rafId = window.requestAnimationFrame(eyeLoop);
    }

    function stopEyeLoop() {
        if (state.rafId) {
            window.cancelAnimationFrame(state.rafId);
            state.rafId = null;
        }
    }

    function closeeyes() {
        if (!state.active) {
            return;
        }
        state.blinks += 1;
        hideVis(els.right);
        hideVis(els.left);
        showVis(els.closed);
        var one = $('lupo-heritage-eyeone');
        var two = $('lupo-heritage-eyetwo');
        if ((state.blinks % 5) === 3 && one && two) {
            switch (state.eyesColor) {
            case 'blue':
                one.src = state.imgs.browneye.src;
                two.src = state.imgs.browneye.src;
                state.eyesColor = 'lblue';
                break;
            case 'lblue':
                one.src = state.imgs.lblueeye.src;
                two.src = state.imgs.lblueeye.src;
                state.eyesColor = 'brown';
                break;
            case 'brown':
                one.src = state.imgs.greeneye.src;
                two.src = state.imgs.greeneye.src;
                state.eyesColor = 'green';
                break;
            case 'green':
                one.src = state.imgs.redeye.src;
                two.src = state.imgs.redeye.src;
                state.eyesColor = 'red';
                break;
            case 'red':
                one.src = state.imgs.blueeye.src;
                two.src = state.imgs.blueeye.src;
                state.eyesColor = 'blue';
                break;
            default:
                one.src = state.imgs.blueeye.src;
                two.src = state.imgs.blueeye.src;
                state.eyesColor = 'blue';
                break;
            }
        }
    }

    function openeyes() {
        if (!state.active) {
            return;
        }
        showVis(els.right);
        showVis(els.left);
        showVis(els.lids);
        hideVis(els.closed);
    }

    function blinkTick() {
        if (!state.active) {
            return;
        }
        window.setTimeout(closeeyes, 2000);
        window.setTimeout(openeyes, 2300);
        window.setTimeout(function () {
            blinkTick();
        }, 5500);
    }

    function showthex() {
        if (els.xbtn) {
            els.xbtn.style.visibility = 'visible';
        }
    }

    function hidethex() {
        if (els.xbtn) {
            els.xbtn.style.visibility = 'hidden';
        }
    }

    function movetheeyes(cfg) {
        var base = cfg.imgBase;
        var elLids = $('lupo-heritage-lids');
        var elClosed = $('lupo-heritage-closed');
        var elWhite = $('lupo-heritage-whites');
        if (elLids) {
            elLids.src = base + 'lids3.png';
        }
        if (elClosed) {
            elClosed.src = base + 'closed3.png';
        }
        if (elWhite) {
            elWhite.src = base + 'right7.png';
        }

        computeCluster();
        state.active = true;
        state.dismissed = false;

        showVis(els.closed);
        showVis(els.back);
        showVis(els.left);
        showVis(els.right);
        showVis(els.lids);
        layoutFace(true);
        placeCloseButton();
        showthex();

        window.setTimeout(showthex, 2000);

        if (els.live) {
            showVis(els.live);
        }
        flyLivehelpIn();

        window.setTimeout(function () {
            startEyeLoop();
        }, 500);

        window.setTimeout(blinkTick, 200);

        if (document.addEventListener) {
            document.addEventListener('mousemove', onMouseMove, false);
            window.addEventListener('resize', onResize, false);
        } else if (document.attachEvent) {
            document.attachEvent('onmousemove', onMouseMove);
            window.attachEvent('onresize', onResize);
        }
    }

    function initPreload(cfg) {
        var base = cfg.imgBase;
        state.imgs.blank = preload(base + 'blank.gif');
        state.imgs.lblueeye = preload(base + 'blueeye2.gif');
        state.imgs.blueeye = preload(base + 'blueeye.gif');
        state.imgs.browneye = preload(base + 'browneye.gif');
        state.imgs.greeneye = preload(base + 'greeneye.gif');
        state.imgs.redeye = preload(base + 'redeye.gif');

        var te = $('lupo-heritage-tempeyes');
        if (te) {
            te.src = state.imgs.blank.src;
        }
    }

    function cacheEls() {
        els.closed = $('closedblockDiv');
        els.back = $('backblockDiv');
        els.left = $('lefteyeblockDiv');
        els.right = $('righteyeblockDiv');
        els.lids = $('lidsblockDiv');
        els.live = $('livehelpblockDiv');
        els.xbtn = $('eye-close-btn');
    }

    function openChat() {
        var c = window.LUPO_HERITAGE_EYES;
        var u = c && c.livehelpUrl ? c.livehelpUrl : '';
        if (u) {
            window.open(u, 'lupo_livehelp_chat', 'width=500,height=500,menubar=no,scrollbars=1,resizable=1');
        }
    }

    function closeAll() {
        state.active = false;
        stopEyeLoop();
        if (document.removeEventListener) {
            document.removeEventListener('mousemove', onMouseMove, false);
            window.removeEventListener('resize', onResize, false);
        } else if (document.detachEvent) {
            document.detachEvent('onmousemove', onMouseMove);
            window.detachEvent('onresize', onResize);
        }

        computeClusterOffscreen();
        layoutFace(true);
        flyLivehelpOut();
        hidethex();
        state.dismissed = true;
    }

    function start(cfg) {
        if (!cfg || !cfg.imgBase) {
            return;
        }
        cacheEls();
        initPreload(cfg);
        placeLivehelpOffscreen();
        movetheeyes(cfg);
    }

    function boot() {
        var cfg = window.LUPO_HERITAGE_EYES;
        if (!cfg || !cfg.imgBase) {
            return;
        }
        cacheEls();
        initPreload(cfg);
        placeLivehelpOffscreen();

        var delay = typeof cfg.delayMs === 'number' ? cfg.delayMs : 12000;
        if (delay < 0) {
            delay = 0;
        }

        window.setTimeout(hidethex, 2000);
        window.setTimeout(function () {
            if (!state.dismissed) {
                start(cfg);
            }
        }, delay);
    }

    window.LupoHeritageEyes = {
        start: start,
        openChat: openChat,
        closeAll: closeAll
    };

    window.openchatwindow = openChat;
    window.close_all_eye_divs = closeAll;

    function scheduleBoot() {
        var rs = document.readyState;
        if (rs === 'complete' || rs === 'interactive') {
            window.setTimeout(boot, 0);
        } else if (document.addEventListener) {
            document.addEventListener('DOMContentLoaded', boot, false);
        } else if (document.attachEvent) {
            document.attachEvent('onreadystatechange', function () {
                if (document.readyState === 'complete') {
                    boot();
                }
            });
        } else {
            window.setTimeout(boot, 0);
        }
    }

    scheduleBoot();
}(typeof window !== 'undefined' ? window : this, typeof document !== 'undefined' ? document : {}));
