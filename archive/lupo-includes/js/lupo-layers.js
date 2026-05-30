/*
 * Lineage — DynAPI Dynamic Layer (conceptual ancestry)
 * ----------------------------------------------------
 * Dynamic Layer Object
 * sophisticated layer/element targeting and animation object which provides the
 * core functionality needed in most DHTML applications
 * 19990604
 *
 * Copyright (C) 1999 Dan Steinman
 * Distributed under the terms of the GNU Library General Public License
 * Available at http://www.dansteinman.com/dynapi/
 *
 * updated 20011228 by Bob Clary <bc@bclary.com>
 * to support Gecko
 *
 * (Canonical in-tree predecessor: lupo-includes/js/dynapi/js/dynlayer.js)
 *
 * Lupopedia LLC — lupo-layers.js (this file)
 * updated 20260404 by Eric Gerdes <wisdomoflovingfaith@gmail.com>
 * ------------------------------------------
 * New implementation and API-compatible surface by Eric Gerdes ("Captain Wolfie"),
 * Lupopedia LLC: vanilla JavaScript, no eval(), CSS transition-based slides (optional),
 * stepped slide parity without setTimeout string eval, LupoLayerInit / window assignment
 * via bracket notation, clip/write/show/hide and DynLayerInit alias for migration.
 * This file is not a line-by-line copy of dynlayer.js; it replaces the eval-heavy
 * patterns for security and maintainability while honoring the original design lineage above.
 *
 * Constitutional: RULE 93.UI_LAYERS — lupo-docs/prd/00_root_constitutional_system_requirements.md §16
 */

/**
 * Lupopedia layer helper — DynLayer-style API without eval().
 *
 * Parity with dynapi/js/dynlayer.js (subset + safe extensions):
 * - Constructor:     new LupoLayer(id[, nestref, frame]) — nestref/frame ignored (DOM only).
 * - Position:        moveTo(x,y), moveBy(dx,dy) — null skips an axis (DynLayer-compatible).
 * - Visibility:      show(), hide().
 * - Slide (CSS):     slideTo(x, y) or slideTo(x, y, durationMS) when durationMS >= 50.
 * - Slide (legacy):  slideTo(x, y, inc, speed[, fn]) — step animation like DynLayer; fn must be a Function (strings are not eval'd).
 * - Slide:           slideBy(dx, dy, inc, speed[, fn]), slideStart(...), slideInit() no-op.
 * - Hooks:           onSlide, onSlideEnd — assign functions (not new Function() strings).
 * - Clip:            clipInit(), clipTo(t,r,b,l), clipBy(...), clipValues(which).
 * - Content:         write(html) — innerHTML on modern browsers.
 * - Z-index:         setZ(z) — extension; DynLayer used css() / style for z-index.
 * - Init:            LupoLayerInit([root]) — scans div ids ending in "Div", sets window[prefix]=new LupoLayer(id); no eval.
 * - Alias:           DynLayerInit === LupoLayerInit; DynLayer === LupoLayer (heritage `new DynLayer(id)`).
 *                    Do not load this file together with dynapi/js/dynlayer.js (duplicate globals).
 *
 * Not ported:        NS4 layers, document.write CSS helpers (css/writeCSS), frame/nestref targeting, eval-based globals from old constructor.
 */
/* global window, document */
(function (window) {
    'use strict';

    function LupoLayer(id, nestref, frame) {
        this.id = id;
        this.nestref = nestref != null ? nestref : null;
        this.frame = frame || window;
        this.elm = document.getElementById(id);
        this.event = this.elm;
        this.css = this.elm ? this.elm.style : null;
        this.doc = document;
        this.x = 0;
        this.y = 0;
        this.w = 0;
        this.h = 0;
        this.slideActive = false;
        this.onSlide = null;
        this.onSlideEnd = null;
        this._pixelAnchored = false;
        this._slideTimer = null;
        this._syncSize();
        if (this.elm) {
            this.x = this.elm.offsetLeft;
            this.y = this.elm.offsetTop;
        }
    }

    LupoLayer.prototype._syncSize = function () {
        if (!this.elm) {
            return;
        }
        this.w = this.elm.offsetWidth;
        this.h = this.elm.offsetHeight;
    };

    LupoLayer.prototype._cancelSlideTimer = function () {
        if (this._slideTimer) {
            window.clearTimeout(this._slideTimer);
            this._slideTimer = null;
        }
    };

    LupoLayer.prototype._preparePixelPosition = function () {
        if (!this.elm || this._pixelAnchored) {
            return;
        }
        var el = this.elm;
        var parent = el.offsetParent || document.body;
        var rect = el.getBoundingClientRect();
        var prect = parent.getBoundingClientRect();
        var left = rect.left - prect.left + (parent.scrollLeft || 0);
        var top = rect.top - prect.top + (parent.scrollTop || 0);
        el.style.marginLeft = '0';
        el.style.marginTop = '0';
        el.style.left = Math.round(left) + 'px';
        el.style.top = Math.round(top) + 'px';
        this.x = left;
        this.y = top;
        this._pixelAnchored = true;
    };

    LupoLayer.prototype.moveTo = function (x, y) {
        if (!this.css) {
            return;
        }
        if (x != null) {
            this.x = x;
            this.css.left = Math.floor(x) + 'px';
        }
        if (y != null) {
            this.y = y;
            this.css.top = Math.floor(y) + 'px';
        }
        this._syncSize();
    };

    LupoLayer.prototype.moveBy = function (dx, dy) {
        var nx = this.x;
        var ny = this.y;
        if (dx != null) {
            nx += dx;
        }
        if (dy != null) {
            ny += dy;
        }
        this.moveTo(nx, ny);
    };

    LupoLayer.prototype.show = function () {
        if (this.css) {
            this.css.visibility = 'visible';
        }
    };

    LupoLayer.prototype.hide = function () {
        if (this.css) {
            this.css.visibility = 'hidden';
        }
    };

    LupoLayer.prototype.setZ = function (z) {
        if (this.css) {
            this.css.zIndex = String(z);
        }
    };

    LupoLayer.prototype.slideInit = function () {
    };

    LupoLayer.prototype._slideCss = function (endx, endy, speedMS) {
        if (!this.css) {
            return;
        }
        var self = this;
        this.slideActive = false;
        this._preparePixelPosition();
        this._cancelSlideTimer();
        this.css.transition = '';
        this.css.webkitTransition = '';
        var ms = speedMS && speedMS > 0 ? speedMS : 600;
        var dur = ms + 'ms';
        var tprop = 'left ' + dur + ' ease-out, top ' + dur + ' ease-out';
        this.css.transition = tprop;
        this.css.webkitTransition = tprop;
        this.moveTo(endx, endy);
        this._slideTimer = window.setTimeout(function () {
            self._slideTimer = null;
            self.css.transition = '';
            self.css.webkitTransition = '';
            self._syncSize();
            if (typeof self.onSlideEnd === 'function') {
                self.onSlideEnd();
            }
        }, ms);
    };

    LupoLayer.prototype._slideLegacy = function (endx, endy, inc, speed, fn) {
        if (!this.css) {
            return;
        }
        this.slideActive = false;
        this._cancelSlideTimer();
        this.css.transition = '';
        this.css.webkitTransition = '';
        this._preparePixelPosition();
        if (endx == null) {
            endx = this.x;
        }
        if (endy == null) {
            endy = this.y;
        }
        if (!inc) {
            inc = 10;
        }
        if (!speed) {
            speed = 20;
        }
        var distx = endx - this.x;
        var disty = endy - this.y;
        var num = Math.sqrt(distx * distx + disty * disty) / inc;
        if (num === 0 || !isFinite(num)) {
            this.moveTo(endx, endy);
            if (typeof this.onSlideEnd === 'function') {
                this.onSlideEnd();
            }
            if (typeof fn === 'function') {
                fn();
            }
            return;
        }
        var dx = distx / num;
        var dy = disty / num;
        var self = this;
        var i = 1;
        var n = Math.ceil(num);
        this.slideActive = true;

        function step() {
            if (!self.slideActive) {
                self._slideTimer = null;
                return;
            }
            if (i < n) {
                self.moveBy(dx, dy);
                if (typeof self.onSlide === 'function') {
                    self.onSlide();
                }
                i++;
                self._slideTimer = window.setTimeout(step, speed);
            } else {
                self.slideActive = false;
                self._slideTimer = null;
                self.moveTo(endx, endy);
                if (typeof self.onSlide === 'function') {
                    self.onSlide();
                }
                if (typeof self.onSlideEnd === 'function') {
                    self.onSlideEnd();
                }
                if (typeof fn === 'function') {
                    fn();
                } else if (fn != null && typeof fn === 'string' && fn !== '') {
                    if (typeof console !== 'undefined' && console.warn) {
                        console.warn('LupoLayer: slide callback string ignored (no eval): ' + fn);
                    }
                }
            }
        }
        step();
    };

    /**
     * slideTo(endx, endy) — CSS transition, default 600ms.
     * slideTo(endx, endy, durationMS) — if durationMS >= 50, CSS transition duration.
     * slideTo(endx, endy, inc, speed[, fn]) — legacy stepped slide (inc < 50 uses legacy path when 3 args: inc as step, speed default 25).
     */
    LupoLayer.prototype.slideTo = function (endx, endy, inc, speed, fn) {
        var argc = arguments.length;
        if (argc <= 2) {
            this._slideCss(endx, endy, 600);
            return;
        }
        if (argc === 3) {
            if (typeof inc === 'number' && inc >= 50) {
                this._slideCss(endx, endy, inc);
            } else {
                this._slideLegacy(endx, endy, inc, 25, null);
            }
            return;
        }
        this._slideLegacy(endx, endy, inc, speed, fn);
    };

    LupoLayer.prototype.slideBy = function (distx, disty, inc, speed, fn) {
        var endx = this.x + (distx != null ? distx : 0);
        var endy = this.y + (disty != null ? disty : 0);
        this.slideStart(endx, endy, endx - this.x, endy - this.y, inc, speed, fn);
    };

    LupoLayer.prototype.slideStart = function (endx, endy, distx, disty, inc, speed, fn) {
        if (this.slideActive) {
            return;
        }
        if (!inc) {
            inc = 10;
        }
        if (!speed) {
            speed = 20;
        }
        this._slideLegacy(endx, endy, inc, speed, fn);
    };

    LupoLayer.prototype.slide = function (dx, dy, endx, endy, num, i, speed, fn) {
        var self = this;
        if (!this.slideActive) {
            return;
        }
        if (i < num) {
            this.moveBy(dx, dy);
            if (typeof this.onSlide === 'function') {
                this.onSlide();
            }
            this._slideTimer = window.setTimeout(function () {
                self.slide(dx, dy, endx, endy, num, i + 1, speed, fn);
            }, speed);
        } else {
            this.slideActive = false;
            this._slideTimer = null;
            this.moveTo(endx, endy);
            if (typeof this.onSlide === 'function') {
                this.onSlide();
            }
            if (typeof this.onSlideEnd === 'function') {
                this.onSlideEnd();
            }
            if (typeof fn === 'function') {
                fn();
            }
        }
    };

    LupoLayer.prototype.clipInit = function (clipTop, clipRight, clipBottom, clipLeft) {
        if (!this.css || !this.elm) {
            return;
        }
        if (arguments.length === 4) {
            this.clipTo(clipTop, clipRight, clipBottom, clipLeft);
        } else {
            this.clipTo(0, this.elm.offsetWidth, this.elm.offsetHeight, 0);
        }
    };

    LupoLayer.prototype.clipTo = function (t, r, b, l) {
        if (!this.css) {
            return;
        }
        if (t == null) {
            t = this.clipValues('t');
        }
        if (r == null) {
            r = this.clipValues('r');
        }
        if (b == null) {
            b = this.clipValues('b');
        }
        if (l == null) {
            l = this.clipValues('l');
        }
        this.css.clip = 'rect(' + t + 'px ' + r + 'px ' + b + 'px ' + l + 'px)';
    };

    LupoLayer.prototype.clipBy = function (t, r, b, l) {
        this.clipTo(
            this.clipValues('t') + t,
            this.clipValues('r') + r,
            this.clipValues('b') + b,
            this.clipValues('l') + l
        );
    };

    LupoLayer.prototype.clipValues = function (which) {
        if (!this.css || !this.elm) {
            return 0;
        }
        var c = this.css.clip;
        if (!c || c === 'auto' || String(c).indexOf('rect(') < 0) {
            var w = this.elm.offsetWidth;
            var h = this.elm.offsetHeight;
            if (which === 't') {
                return 0;
            }
            if (which === 'r') {
                return w;
            }
            if (which === 'b') {
                return h;
            }
            if (which === 'l') {
                return 0;
            }
            return 0;
        }
        var inner = String(c).split('rect(')[1].split(')')[0];
        var parts = inner.split('px');
        var nums = [];
        var i;
        for (i = 0; i < parts.length; i++) {
            var n = parseFloat(parts[i], 10);
            if (!isNaN(n)) {
                nums.push(n);
            }
        }
        if (nums.length < 4) {
            return 0;
        }
        if (which === 't') {
            return nums[0];
        }
        if (which === 'r') {
            return nums[1];
        }
        if (which === 'b') {
            return nums[2];
        }
        if (which === 'l') {
            return nums[3];
        }
        return 0;
    };

    LupoLayer.prototype.write = function (html) {
        if (this.elm) {
            this.elm.innerHTML = html;
            this._syncSize();
        }
    };

    function LupoLayerInit(root) {
        var doc = root && root.nodeType ? root : document;
        var nodes = doc.getElementsByTagName('div');
        var i;
        var divname;
        var index;
        var prefix;
        for (i = 0; i < nodes.length; i++) {
            divname = nodes[i].id;
            if (!divname) {
                continue;
            }
            index = divname.indexOf('Div');
            if (index > 0) {
                prefix = divname.substring(0, index);
                window[prefix] = new LupoLayer(divname);
            }
        }
        return true;
    }

    window.LupoLayer = LupoLayer;
    window.LupoLayerInit = LupoLayerInit;
    window.DynLayerInit = LupoLayerInit;
    window.DynLayer = LupoLayer;
}(typeof window !== 'undefined' ? window : this));
