/**
 * lupopedia.js — static site-wide JS bootstrap for Lupopedia layouts.
 *
 * Not the same as lupopedia_js.php (dynamic embed / PRD 28 bundle with LUPO_BOOTSTRAP).
 *
 * Loaded from main_layout, basic_layout, and admin_layout so later scripts can rely on:
 *   - window.LUPO_HDR.base  — public path prefix (no trailing slash), e.g. /lupopedia
 *   - window.LUPO_HDR.strings — optional i18n map (main_layout may merge via inline script first)
 *   - window.LUPOPEDIA_SUBDIRECTORY — legacy alias with trailing slash for older snippets (e.g. lupo-ui)
 */
(function (w) {
    'use strict';

    var base = '';
    if (w.LUPO_HDR && w.LUPO_HDR.base) {
        base = String(w.LUPO_HDR.base).replace(/\/+$/, '');
    } else {
        var scripts = document.getElementsByTagName('script');
        var i;
        var needle = '/lupo-includes/js/lupopedia.js';
        for (i = 0; i < scripts.length; i++) {
            var src = scripts[i].src || '';
            var pos = src.indexOf(needle);
            if (pos !== -1) {
                base = src.substring(0, pos).replace(/\/+$/, '');
                break;
            }
        }
    }

    w.LUPO_HDR = w.LUPO_HDR || { base: '', strings: {} };
    if (typeof w.LUPO_HDR.strings !== 'object' || w.LUPO_HDR.strings === null) {
        w.LUPO_HDR.strings = {};
    }
    if (base && !w.LUPO_HDR.base) {
        w.LUPO_HDR.base = base;
    }

    var b = w.LUPO_HDR.base ? String(w.LUPO_HDR.base) : '';
    w.LUPOPEDIA_SUBDIRECTORY = (b === '' || b === '/') ? '/' : (b.replace(/\/?$/, '/') );
}(typeof window !== 'undefined' ? window : this));
