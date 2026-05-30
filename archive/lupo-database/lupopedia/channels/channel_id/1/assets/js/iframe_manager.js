(function() {
    function IframeManager() {
        this.iframes = {};
        this.setupListener();
    }

    IframeManager.prototype.register = function(name, iframe) {
        if (name && iframe) {
            this.iframes[name] = iframe;
        }
    };

    IframeManager.prototype.navigate = function(name, url) {
        var iframe = this.iframes[name];
        if (iframe) {
            iframe.src = url;
        }
    };

    IframeManager.prototype.setupListener = function() {
        var self = this;
        window.addEventListener('message', function(event) {
            if (!event || !event.data) {
                return;
            }
            if (event.data.type === 'channel-admin-nav') {
                if (event.data.target && event.data.url) {
                    self.navigate(event.data.target, event.data.url);
                }
            }
        });
    };

    window.ChannelIframeManager = IframeManager;
})();
