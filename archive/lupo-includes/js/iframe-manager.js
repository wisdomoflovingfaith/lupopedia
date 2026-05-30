/**
 * Iframe Manager for Livehelp Channel Interface
 * Handles secure cross-iframe communication using postMessage API
 */

class IframeManager {
    constructor() {
        this.iframeWindows = new Map();
        this.allowedOrigins = new Set([window.location.origin]);
        this.setupMessageListener();
        this.setupErrorHandling();
    }

    registerIframe(name, iframeElement) {
        if (!iframeElement) {
            console.warn(`Iframe element not found for ${name}`);
            return;
        }
        
        // Wait for iframe to load before accessing contentWindow
        const waitForLoad = () => {
            if (iframeElement.contentWindow) {
                this.iframeWindows.set(name, iframeElement.contentWindow);
                console.log(`Registered iframe: ${name}`);
            } else {
                setTimeout(waitForLoad, 100);
            }
        };
        
        if (iframeElement.contentWindow) {
            waitForLoad();
        } else {
            iframeElement.addEventListener('load', waitForLoad);
        }
    }

    setupMessageListener() {
        window.addEventListener('message', (event) => {
            // Validate origin for security
            if (!this.allowedOrigins.has(event.origin)) {
                console.warn(`Rejected message from unauthorized origin: ${event.origin}`);
                return;
            }

            try {
                const data = typeof event.data === 'string' ? JSON.parse(event.data) : event.data;
                this.routeMessage(data, event.origin);
            } catch (error) {
                console.error('Error parsing iframe message:', error);
            }
        });
    }

    setupErrorHandling() {
        window.addEventListener('error', (event) => {
            if (event.target.tagName === 'IFRAME') {
                console.error('Iframe error:', event.target.src, event.message);
            }
        });
    }

    sendMessage(targetIframe, message) {
        const targetWindow = this.iframeWindows.get(targetIframe);
        if (targetWindow) {
            try {
                targetWindow.postMessage(JSON.stringify(message), window.location.origin);
                console.log(`Message sent to ${targetIframe}:`, message);
            } catch (error) {
                console.error(`Error sending message to ${targetIframe}:`, error);
            }
        } else {
            console.warn(`Target iframe not found: ${targetIframe}`);
        }
    }

    broadcastMessage(message) {
        // Send message to all registered iframes
        for (const [name, window] of this.iframeWindows) {
            this.sendMessage(name, message);
        }
    }

    routeMessage(data, origin) {
        // Log all messages for debugging
        console.log(`Received message from ${origin}:`, data);
        
        switch (data.type) {
            case 'user_update':
                this.handleUserUpdate(data);
                break;
            case 'connection_change':
                this.handleConnectionChange(data);
                break;
            case 'new_message':
                this.handleNewMessage(data);
                break;
            case 'typing_indicator':
                this.handleTypingIndicator(data);
                break;
            case 'system_message':
                this.handleSystemMessage(data);
                break;
            case 'ping':
                this.handlePing(data);
                break;
            case 'resize':
                this.handleResize(data);
                break;
            case 'scroll':
                this.handleScroll(data);
                break;
            case 'focus':
                this.handleFocus(data);
                break;
            case 'reload':
                this.handleReload(data);
                break;
            default:
                console.log('Unknown message type:', data.type);
        }
    }

    handleUserUpdate(data) {
        // Forward user updates to users iframe
        this.sendMessage('users', data);
    }

    handleConnectionChange(data) {
        // Forward connection status to connection iframe
        this.sendMessage('connection', data);
    }

    handleNewMessage(data) {
        // Forward new messages to chat iframe
        this.sendMessage('chat', data);
    }

    handleTypingIndicator(data) {
        // Forward typing indicators to chat iframe
        this.sendMessage('chat', data);
    }

    handleSystemMessage(data) {
        // Broadcast system messages to all iframes
        this.broadcastMessage(data);
    }

    handlePing(data) {
        // Respond to ping with pong
        this.sendMessage(data.source || 'chat', {
            type: 'pong',
            timestamp: Date.now(),
            original_data: data
        });
    }

    handleResize(data) {
        // Handle iframe resize requests
        const targetWindow = this.iframeWindows.get(data.target);
        if (targetWindow) {
            targetWindow.postMessage({
                type: 'resize_response',
                width: data.width,
                height: data.height
            }, window.location.origin);
        }
    }

    handleScroll(data) {
        // Handle iframe scroll requests
        const targetWindow = this.iframeWindows.get(data.target);
        if (targetWindow) {
            targetWindow.postMessage({
                type: 'scroll_response',
                x: data.x,
                y: data.y
            }, window.location.origin);
        }
    }

    handleFocus(data) {
        // Handle iframe focus requests
        const targetWindow = this.iframeWindows.get(data.target);
        if (targetWindow) {
            targetWindow.postMessage({
                type: 'focus_response',
                timestamp: Date.now()
            }, window.location.origin);
        }
    }

    handleReload(data) {
        // Handle iframe reload requests
        const targetWindow = this.iframeWindows.get(data.target);
        if (targetWindow) {
            targetWindow.postMessage({
                type: 'reload_request',
                reason: data.reason || 'manual'
            }, window.location.origin);
        }
    }

    unregisterIframe(name) {
        const iframeWindow = this.iframeWindows.get(name);
        if (iframeWindow) {
            this.iframeWindows.delete(name);
            console.log(`Unregistered iframe: ${name}`);
        }
    }

    getAllIframes() {
        return Array.from(this.iframeWindows.keys());
    }

    getIframeStatus() {
        const status = {};
        for (const [name, window] of this.iframeWindows) {
            status[name] = {
                registered: !!window,
                accessible: this.checkIframeAccessibility(window)
            };
        }
        return status;
    }

    checkIframeAccessibility(iframeWindow) {
        try {
            // Test if we can send a message
            const testMessage = { type: 'accessibility_test', timestamp: Date.now() };
            iframeWindow.postMessage(JSON.stringify(testMessage), window.location.origin);
            return true;
        } catch (error) {
            return false;
        }
    }

    // Utility method to validate message format
    validateMessage(message) {
        if (!message || typeof message !== 'object') {
            return false;
        }
        
        if (!message.type || typeof message.type !== 'string') {
            return false;
        }
        
        return true;
    }

    // Method to get iframe information for debugging
    getDebugInfo() {
        const info = {
            registeredIframes: this.getAllIframes(),
            iframeStatus: this.getIframeStatus(),
            allowedOrigins: Array.from(this.allowedOrigins),
            messageTypes: [
                'user_update', 'connection_change', 'new_message', 
                'typing_indicator', 'system_message', 'ping', 'resize', 
                'scroll', 'focus', 'reload'
            ]
        };
        
        console.log('Iframe Manager Debug Info:', info);
        return info;
    }
}

// Export for global access
window.IframeManager = IframeManager;
