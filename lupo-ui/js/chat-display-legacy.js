/**
 * chat-display-legacy.js — ES3-safe transport helpers for channel message polling.
 *
 * Use this (or patterns herein) for ActiveX / image-digit / iframe buffer tiers.
 * Modern chat UI may use fetch + JSON; do not mix ES2015+ syntax in this file
 * (no const/let, arrows, optional chaining) so IE-era fallbacks stay parseable.
 *
 * Canonical JSON API: GET/POST api/lupo-channels/{channelId}/messages
 *   (see lupo-includes/modules/api/channels-api.php)
 * format=buffer — text/plain JSON body for hidden iframe reads
 * format=image&whatplace=hundreds|tens|ones — GIF digit for activity fingerprint
 *
 * Related legacy reference: livehelp_js.php (XHR/ActiveX probe), image.php (digit GIFs),
 * lupo-ui/images/digit0.gif … digit9.gif
 */
/*global window, document, ActiveXObject */

/**
 * Build messages URL under LUPOPEDIA_PUBLIC_PATH (pass from PHP, no hardcoded subdir).
 * @param {string} publicPath e.g. "/lupopedia" or ""
 * @param {number} channelId
 * @param {string} since 14-digit YmdHis or ""
 * @param {string} format "json"|"buffer"|"image"
 * @return {string}
 */
function lupoChatMessagesUrl(publicPath, channelId, since, format, threadId) {
    var base = publicPath || "";
    if (base.charAt(base.length - 1) === "/") {
        base = base.substring(0, base.length - 1);
    }
    var u = base + "/api/lupo-channels/" + channelId + "/messages?since=" + encodeURIComponent(since || "");
    if (threadId && parseInt(threadId, 10) > 0) {
        u += "&thread_id=" + parseInt(threadId, 10);
    }
    if (format && format !== "json") {
        u += "&format=" + encodeURIComponent(format);
    }
    return u;
}

/**
 * XMLHttpRequest or ActiveX MSXML (IE6–9).
 * @return {Object|null}
 */
function lupoGetHttpRequest() {
    if (typeof XMLHttpRequest !== "undefined") {
        try {
            return new XMLHttpRequest();
        } catch (e1) {
        }
    }
    if (typeof ActiveXObject !== "undefined") {
        try {
            return new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e2) {
        }
        try {
            return new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e3) {
        }
    }
    return null;
}

/**
 * GET url; on 200 calls onDone(text).
 * @param {string} url
 * @param {function(string)} onDone
 * @param {function()=} onFail
 */
function lupoHttpGetText(url, onDone, onFail) {
    var xhr = lupoGetHttpRequest();
    if (!xhr) {
        if (onFail) {
            onFail();
        }
        return;
    }
    xhr.open("GET", url, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState !== 4) {
            return;
        }
        if (xhr.status === 200) {
            onDone(xhr.responseText || "");
        } else if (onFail) {
            onFail();
        }
    };
    try {
        xhr.send(null);
    } catch (e) {
        if (onFail) {
            onFail();
        }
    }
}

/**
 * Poll fingerprint 0–999 from three image requests (Crafty-style digit chain).
 * publicPath + channelId + since must match an authenticated session (cookies).
 * @param {string} publicPath
 * @param {number} channelId
 * @param {string} since
 * @param {function(number)} onNumber receives 0–999
 * @param {function()=} onFail
 */
function lupoChannelImageFingerprint(publicPath, channelId, since, onNumber, onFail, threadId) {
    var places = ["hundreds", "tens", "ones"];
    var digits = [];
    var i = 0;

    function next() {
        if (i >= places.length) {
            onNumber(digits[0] * 100 + digits[1] * 10 + digits[2]);
            return;
        }
        var url = lupoChatMessagesUrl(publicPath, channelId, since, "image", threadId) + "&whatplace=" + places[i];
        url += "&rand=" + String(Math.random()).substring(2);
        var img = new Image();
        img.onload = function () {
            var src = img.src || "";
            var m = src.match(/digit(\d)\.gif/i);
            if (m) {
                digits.push(parseInt(m[1], 10));
            } else {
                digits.push(0);
            }
            i += 1;
            next();
        };
        img.onerror = function () {
            if (onFail) {
                onFail();
            }
        };
        img.src = url;
    }

    next();
}

if (typeof window !== "undefined") {
    window.lupoChatMessagesUrl = lupoChatMessagesUrl;
    window.lupoGetHttpRequest = lupoGetHttpRequest;
    window.lupoHttpGetText = lupoHttpGetText;
    window.lupoChannelImageFingerprint = lupoChannelImageFingerprint;
}
