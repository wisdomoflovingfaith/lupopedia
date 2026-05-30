/**
 * chat-display.js — Channel chat UI with transport fallback chain.
 * ES3-compatible (no const/let, arrows, bind, optional chaining) for legacy tiers.
 *
 * API: GET/POST {LUPOPEDIA_PUBLIC_PATH}/api/channels/{id}/messages
 * See includes/modules/api/channels-api.php (format=json|buffer|image).
 */
/*global window, document, ActiveXObject, fetch */

function lupoTrim(s) {
    if (s === null || s === undefined) {
        return '';
    }
    s = String(s);
    return s.replace(/^\s+|\s+$/g, '');
}

/**
 * @param {Object} config
 * @param {HTMLElement} config.container
 * @param {number} config.channelId
 * @param {number} [config.threadId]
 * @param {number} [config.actorId]
 * @param {string} config.publicPath  LUPOPEDIA_PUBLIC_PATH (e.g. /lupopedia)
 * @param {string} [config.csrfToken]
 * @param {number} [config.pollingInterval]
 * @param {boolean} [config.autoScroll]
 * @param {number} [config.kairosTickIntervalMs] POST /api/kairos/tick on this interval (0 = off)
 * @param {number} [config.kairosDepartmentId] optional department filter for consolidation
 */
var ChatDisplay = function (config) {
    this.container = config.container;
    this.channelId = parseInt(config.channelId, 10) || 0;
    this.threadId = parseInt(config.threadId, 10) || 0;
    this.actorId = parseInt(config.actorId, 10) || 0;
    this.publicPath = config.publicPath || '';
    this.csrfToken = config.csrfToken || '';
    this.pollingInterval = config.pollingInterval || 2000;
    this.autoScroll = config.autoScroll !== false;
    this.kairosTickIntervalMs = parseInt(config.kairosTickIntervalMs, 10) || 0;
    this.kairosDepartmentId = parseInt(config.kairosDepartmentId, 10) || 0;
    this.lastSinceStr = '';
    this.pollTimer = null;
    this.kairosTimer = null;
    this.useMethod = null;
};

ChatDisplay.prototype.log = function (msg) {
    if (window.console && console.log) {
        console.log(msg);
    }
};

ChatDisplay.prototype.messagesUrl = function (format) {
    var base = lupoTrim(this.publicPath);
    if (base.length > 0 && base.charAt(base.length - 1) === '/') {
        base = base.substring(0, base.length - 1);
    }
    var u = base + '/api/channels/' + this.channelId + '/messages?since=' + encodeURIComponent(this.lastSinceStr || '');
    if (this.threadId > 0) {
        u += '&thread_id=' + this.threadId;
    }
    if (format === 'buffer') {
        u += '&format=buffer';
    } else if (format === 'image') {
        u += '&format=image';
    }
    return u;
};

ChatDisplay.prototype.createXhr = function () {
    if (typeof XMLHttpRequest !== 'undefined') {
        try {
            return new XMLHttpRequest();
        } catch (e1) {
        }
    }
    if (typeof ActiveXObject !== 'undefined') {
        try {
            return new ActiveXObject('Msxml2.XMLHTTP');
        } catch (e2) {
        }
        try {
            return new ActiveXObject('Microsoft.XMLHTTP');
        } catch (e3) {
        }
    }
    return null;
};

ChatDisplay.prototype.detectCapabilities = function () {
    if (typeof window.fetch !== 'undefined') {
        this.useMethod = 'fetch';
        this.log('[ChatDisplay] tier: fetch');
        return;
    }
    if (this.createXhr()) {
        this.useMethod = 'xhr';
        this.log('[ChatDisplay] tier: XMLHttpRequest');
        return;
    }
    if (typeof document !== 'undefined' && document.images) {
        this.useMethod = 'image';
        this.log('[ChatDisplay] tier: image/buffer (iframe for payload)');
        return;
    }
    this.useMethod = 'buffer';
    this.log('[ChatDisplay] tier: buffer');
};

ChatDisplay.prototype.parsePayload = function (text) {
    try {
        var data = typeof JSON.parse !== 'undefined' ? JSON.parse(text) : null;
        return data;
    } catch (e) {
        return null;
    }
};

ChatDisplay.prototype.applyMessages = function (data, doneFn) {
    var self = this;
    if (!data || data.success !== true || !data.messages) {
        if (doneFn) {
            doneFn();
        }
        return;
    }
    var list = data.messages;
    if (list.length > 0) {
        var last = list[list.length - 1];
        if (last && last.created_at !== undefined && last.created_at !== null) {
            self.lastSinceStr = String(last.created_at);
        }
        self.appendMessages(list);
    }
    if (doneFn) {
        doneFn();
    }
};

ChatDisplay.prototype.bufferRequest = function (url, callback) {
    var self = this;
    var iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    iframe.setAttribute('src', 'about:blank');
    var completed = false;
    function finish(data) {
        if (completed) {
            return;
        }
        completed = true;
        try {
            if (iframe.parentNode) {
                iframe.parentNode.removeChild(iframe);
            }
        } catch (eR) {
        }
        callback(data);
    }
    iframe.onload = function () {
        try {
            var doc = iframe.contentWindow && iframe.contentWindow.document;
            if (!doc || !doc.body) {
                finish(null);
                return;
            }
            var txt = doc.body.innerText || doc.body.textContent || '';
            var data = self.parsePayload(txt);
            finish(data);
        } catch (e) {
            self.log('[ChatDisplay] buffer parse failed');
            finish(null);
        }
    };
    document.body.appendChild(iframe);
    iframe.src = url;
    window.setTimeout(function () {
        if (!completed) {
            self.log('[ChatDisplay] buffer timeout');
            finish(null);
        }
    }, 15000);
};

ChatDisplay.prototype.getJson = function (url, callback) {
    var self = this;
    if (this.useMethod === 'fetch') {
        fetch(url, { credentials: 'same-origin' })
            .then(function (response) {
                return response.text();
            })
            .then(function (text) {
                callback(self.parsePayload(text));
            })
            .catch(function () {
                self.useMethod = 'xhr';
                self.getJson(url, callback);
            });
        return;
    }
    if (this.useMethod === 'buffer' || this.useMethod === 'image') {
        var bufUrl = url.indexOf('format=buffer') >= 0 ? url : (url + (url.indexOf('?') >= 0 ? '&' : '?') + 'format=buffer');
        this.bufferRequest(bufUrl, callback);
        return;
    }
    var xhr = this.createXhr();
    if (!xhr) {
        var bufUrl2 = url + (url.indexOf('?') >= 0 ? '&' : '?') + 'format=buffer';
        this.bufferRequest(bufUrl2, callback);
        return;
    }
    xhr.open('GET', url, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState !== 4) {
            return;
        }
        if (xhr.status === 200) {
            callback(self.parsePayload(xhr.responseText || ''));
        } else {
            callback(null);
        }
    };
    try {
        xhr.send(null);
    } catch (e) {
        callback(null);
    }
};

ChatDisplay.prototype.loadInitialMessages = function () {
    var self = this;
    this.lastSinceStr = '';
    var url = this.messagesUrl('json');
    this.getJson(url, function (data) {
        if (self.container) {
            self.container.innerHTML = '';
        }
        if (!data || data.success !== true) {
            if (self.container) {
                self.container.innerHTML = '<p class="loading-indicator">Unable to load messages.</p>';
            }
            self.updateStatus('Error', 'offline');
            return;
        }
        self.applyMessages(data, function () {
            self.updateStatus('Connected', 'online');
        });
    });
};

ChatDisplay.prototype.fetchNewMessages = function (callback) {
    var self = this;
    var url = this.messagesUrl('json');
    this.getJson(url, function (data) {
        self.applyMessages(data, function () {
            if (callback) {
                callback();
            }
        });
    });
};

ChatDisplay.prototype.startPolling = function () {
    var self = this;
    if (this.pollTimer) {
        window.clearInterval(this.pollTimer);
    }
    this.pollTimer = window.setInterval(function () {
        self.fetchNewMessages(null);
    }, this.pollingInterval);
};

ChatDisplay.prototype.kairosTickUrl = function () {
    var base = lupoTrim(this.publicPath);
    if (base.length > 0 && base.charAt(base.length - 1) === '/') {
        base = base.substring(0, base.length - 1);
    }
    return base + '/api/kairos/tick';
};

ChatDisplay.prototype.postJson = function (url, bodyObj, callback) {
    var xhr = this.createXhr();
    var self = this;
    if (!xhr) {
        if (callback) {
            callback(null);
        }
        return;
    }
    var body = '{}';
    if (typeof JSON !== 'undefined' && JSON.stringify) {
        body = JSON.stringify(bodyObj || {});
    }
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function () {
        if (xhr.readyState !== 4) {
            return;
        }
        if (xhr.status === 200) {
            if (callback) {
                callback(self.parsePayload(xhr.responseText || ''));
            }
        } else if (callback) {
            callback(null);
        }
    };
    try {
        xhr.send(body);
    } catch (eSend) {
        if (callback) {
            callback(null);
        }
    }
};

/**
 * Background KAIROS consolidation (same session as chat). Interval 0 disables.
 */
ChatDisplay.prototype.startKairosBackground = function () {
    var self = this;
    var ms = parseInt(this.kairosTickIntervalMs, 10) || 0;
    if (ms <= 0) {
        return;
    }
    if (this.kairosTimer) {
        window.clearInterval(this.kairosTimer);
    }
    function runTick() {
        var payload = {};
        if (self.kairosDepartmentId > 0) {
            payload.department_id = self.kairosDepartmentId;
        }
        self.postJson(self.kairosTickUrl(), payload, function (data) {
            if (data && window.console && console.log) {
                console.log('[KAIROS tick]', data);
            }
        });
    }
    window.setTimeout(runTick, 8000);
    this.kairosTimer = window.setInterval(runTick, ms);
};

ChatDisplay.prototype.appendMessages = function (messages) {
    if (!this.container || !messages || messages.length === 0) {
        return;
    }
    var html = '';
    var i;
    for (i = 0; i < messages.length; i++) {
        html += this.renderMessage(messages[i]);
    }
    if (html !== '') {
        this.container.innerHTML = this.container.innerHTML + html;
        if (this.autoScroll) {
            this.container.scrollTop = this.container.scrollHeight;
        }
    }
};

ChatDisplay.prototype.renderMessage = function (m) {
    var actorName = m.actor_name ? String(m.actor_name) : '';
    var actorId = parseInt(m.actor_id, 10) || 0;
    var body = m.body !== undefined && m.body !== null ? String(m.body) : '';
    var mid = m.message_id !== undefined && m.message_id !== null ? String(m.message_id) : '0';
    var ts = m.created_at !== undefined && m.created_at !== null ? m.created_at : '';
    var actorType = m.actor_type ? String(m.actor_type) : '';
    var color = this.getActorColor(actorId);
    var av = actorName.length > 0 ? actorName.charAt(0) : '?';
    var safeName = this.escapeHtml(actorName);
    var safeBody = this.renderMarkdown(this.escapeHtml(body));
    var safeRole = this.escapeHtml(actorType);
    return '<div class="chat-message" data-message-id="' + this.escapeHtml(mid) + '">' +
        '<div class="message-avatar" style="background-color:' + color + ';">' + this.escapeHtml(av) + '</div>' +
        '<div class="message-content"><div class="message-header">' +
        '<span class="actor-name" style="color:#fff;">' + safeName + '</span>' +
        '<span class="actor-role">(' + (safeRole || 'Actor') + ')</span>' +
        '<span class="message-timestamp">' + this.formatTimestamp(ts) + '</span></div>' +
        '<div class="message-body">' + safeBody + '</div></div></div>';
};

ChatDisplay.prototype.getActorColor = function (actorId) {
    var hue = (actorId * 137) % 360;
    return 'hsl(' + hue + ', 65%, 45%)';
};

ChatDisplay.prototype.formatTimestamp = function (timestamp) {
    if (timestamp === null || timestamp === undefined || timestamp === '') {
        return '';
    }
    var str = String(timestamp);
    if (str.length < 14) {
        return str;
    }
    return str.substring(0, 4) + '-' + str.substring(4, 6) + '-' + str.substring(6, 8) + ' ' +
        str.substring(8, 10) + ':' + str.substring(10, 12) + ':' + str.substring(12, 14);
};

ChatDisplay.prototype.escapeHtml = function (text) {
    if (text === null || text === undefined) {
        return '';
    }
    return String(text).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
};

ChatDisplay.prototype.renderMarkdown = function (text) {
    if (!text) {
        return '';
    }
    var t = text;
    t = t.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
    t = t.replace(/\*([^*]+)\*/g, '<em>$1</em>');
    t = t.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank" rel="noopener noreferrer">$1</a>');
    return t;
};

ChatDisplay.prototype.sendMessage = function (text) {
    var self = this;
    var xhr = this.createXhr();
    if (!xhr) {
        window.alert('Cannot send: no XMLHttpRequest in this browser.');
        return;
    }
    var base = lupoTrim(this.publicPath);
    if (base.length > 0 && base.charAt(base.length - 1) === '/') {
        base = base.substring(0, base.length - 1);
    }
    var url = base + '/api/channels/' + this.channelId + '/messages';
    var payloadObj = { body: text, routing_type: 'broadcast' };
    if (this.threadId > 0) {
        payloadObj.routing_type = 'thread';
        payloadObj.thread_id = this.threadId;
    }
    var payload = JSON.stringify(payloadObj);
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
    if (this.csrfToken) {
        xhr.setRequestHeader('X-CSRF-Token', this.csrfToken);
    }
    xhr.onreadystatechange = function () {
        if (xhr.readyState !== 4) {
            return;
        }
        if (xhr.status === 201 || xhr.status === 200) {
            var input = document.getElementById('message-input');
            if (input) {
                input.value = '';
            }
            self.fetchNewMessages(null);
        } else {
            var err = 'Send failed (HTTP ' + xhr.status + ')';
            try {
                var r = self.parsePayload(xhr.responseText || '');
                if (r && r.error && r.error.message) {
                    err = String(r.error.message);
                }
            } catch (e1) {
            }
            window.alert(err);
        }
    };
    try {
        xhr.send(payload);
    } catch (e) {
        window.alert('Send failed.');
    }
};

ChatDisplay.prototype.setupEventListeners = function () {
    var self = this;
    var input = document.getElementById('message-input');
    var sendButton = document.getElementById('send-button');
    if (input) {
        input.onkeypress = function (e) {
            if (!e) {
                e = window.event;
            }
            var code = e.keyCode !== undefined ? e.keyCode : (e.which !== undefined ? e.which : 0);
            if (code === 13 && !e.shiftKey) {
                if (e.preventDefault) {
                    e.preventDefault();
                } else {
                    e.returnValue = false;
                }
                var v = lupoTrim(input.value);
                if (v) {
                    self.sendMessage(v);
                }
            }
        };
    }
    if (sendButton) {
        sendButton.onclick = function () {
            if (input) {
                var v = lupoTrim(input.value);
                if (v) {
                    self.sendMessage(v);
                }
            }
        };
    }
};

ChatDisplay.prototype.updateStatus = function (text, statusClass) {
    var statusEl = document.getElementById('connection-status');
    if (statusEl) {
        statusEl.innerHTML = '<span class="status-dot ' + statusClass + '"></span> ' + this.escapeHtml(text);
        statusEl.className = 'chat-status ' + statusClass;
    }
};

ChatDisplay.prototype.init = function () {
    var self = this;
    this.detectCapabilities();
    this.loadInitialMessages();
    this.startPolling();
    this.startKairosBackground();
    this.setupEventListeners();
};

function lupoChatDisplayDomReady(fn) {
    if (document.addEventListener) {
        document.addEventListener('DOMContentLoaded', fn, false);
    } else if (document.attachEvent) {
        document.attachEvent('onreadystatechange', function () {
            if (document.readyState === 'complete') {
                fn();
            }
        });
    } else {
        window.onload = fn;
    }
}

if (typeof window !== 'undefined') {
    window.ChatDisplay = ChatDisplay;
    window.lupoChatDisplayDomReady = lupoChatDisplayDomReady;
}
