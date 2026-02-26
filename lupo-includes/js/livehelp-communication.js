/**
 * Livehelp Communication System
 * Modern replacement for legacy xmlhttp.js with WebSocket support and Fetch API fallback
 */

class LivehelpCommunication {
    constructor(channelId, sessionId) {
        this.channelId = channelId;
        this.sessionId = sessionId;
        this.websocket = null;
        this.pollingInterval = null;
        this.messageQueue = [];
        this.isConnected = false;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
    }

    async connect() {
        try {
            // Try WebSocket first
            const wsUrl = `wss://${window.location.host}/lupopedia/ws/channels/${this.channelId}`;
            this.websocket = new WebSocket(wsUrl);
            this.setupWebSocketEvents();
            
            console.log(`Attempting WebSocket connection to ${wsUrl}`);
        } catch (error) {
            console.warn('WebSocket connection failed, falling back to HTTP polling:', error);
            this.startPolling();
        }
    }

    setupWebSocketEvents() {
        this.websocket.onopen = () => {
            this.isConnected = true;
            this.reconnectAttempts = 0;
            this.flushMessageQueue();
            console.log('WebSocket connected successfully');
            
            // Send initial connection message
            this.sendMessage({
                type: 'connection_status',
                status: 'connected',
                timestamp: Date.now()
            });
        };

        this.websocket.onmessage = (event) => {
            try {
                const data = JSON.parse(event.data);
                this.routeMessage(data);
            } catch (error) {
                console.error('Error parsing WebSocket message:', error);
            }
        };

        this.websocket.onclose = (event) => {
            this.isConnected = false;
            console.log('WebSocket closed, attempting reconnection...');
            
            // Attempt reconnection with exponential backoff
            if (this.reconnectAttempts < this.maxReconnectAttempts) {
                const delay = Math.pow(2, this.reconnectAttempts) * 1000;
                setTimeout(() => this.connect(), delay);
                this.reconnectAttempts++;
            } else {
                console.log('Max reconnection attempts reached, switching to polling');
                this.startPolling();
            }
        };

        this.websocket.onerror = (error) => {
            console.error('WebSocket error:', error);
            this.isConnected = false;
        };
    }

    startPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
        }
        
        console.log('Starting HTTP polling fallback');
        this.pollingInterval = setInterval(() => {
            this.pollForMessages();
        }, 3000); // Poll every 3 seconds
        
        // Send initial poll to establish connection
        this.pollForMessages();
    }

    async pollForMessages() {
        try {
            const response = await fetch(`/channels/${this.channelId}/admin_chat_xmlhttp.php?action=get_messages&offset=0&csrf_token=${this.getCsrfToken()}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                if (data.status === 'success') {
                    this.processMessages(data.data);
                }
            }
        } catch (error) {
            console.error('Polling error:', error);
        }
    }

    async sendMessage(message) {
        const messageData = {
            ...message,
            timestamp: Date.now(),
            channel_id: this.channelId,
            session_id: this.sessionId
        };

        if (this.isConnected && this.websocket) {
            try {
                this.websocket.send(JSON.stringify(messageData));
                console.log('Message sent via WebSocket:', messageData);
            } catch (error) {
                console.error('Error sending WebSocket message:', error);
                this.queueMessage(messageData);
            }
        } else {
            this.queueMessage(messageData);
            await this.sendViaHTTP(messageData);
        }
    }

    queueMessage(message) {
        this.messageQueue.push(message);
        console.log('Message queued:', message);
    }

    flushMessageQueue() {
        while (this.messageQueue.length > 0) {
            const message = this.messageQueue.shift();
            try {
                this.websocket.send(JSON.stringify(message));
                console.log('Queued message sent:', message);
            } catch (error) {
                console.error('Error sending queued message:', error);
            }
        }
    }

    async sendViaHTTP(messageData) {
        try {
            const response = await fetch(`/channels/${this.channelId}/admin_chat_xmlhttp.php?action=send_message`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(messageData)
            });
            
            if (response.ok) {
                const result = await response.json();
                if (result.status !== 'success') {
                    console.error('HTTP message send failed:', result);
                }
            }
        } catch (error) {
            console.error('HTTP message send error:', error);
        }
    }

    routeMessage(data) {
        switch (data.type) {
            case 'new_message':
                this.handleNewMessage(data);
                break;
            case 'user_update':
                this.handleUserUpdate(data);
                break;
            case 'connection_change':
                this.handleConnectionChange(data);
                break;
            case 'typing_indicator':
                this.handleTypingIndicator(data);
                break;
            case 'system_message':
                this.handleSystemMessage(data);
                break;
            default:
                console.log('Unknown message type:', data.type);
        }
    }

    handleNewMessage(data) {
        // Send to chat iframe
        this.sendToIframe('chat', {
            type: 'new_message',
            data: data
        });
    }

    handleUserUpdate(data) {
        // Send to users iframe
        this.sendToIframe('users', {
            type: 'user_update',
            data: data
        });
    }

    handleConnectionChange(data) {
        // Send to connection iframe
        this.sendToIframe('connection', {
            type: 'connection_change',
            data: data
        });
    }

    handleTypingIndicator(data) {
        // Send to chat iframe
        this.sendToIframe('chat', {
            type: 'typing_indicator',
            data: data
        });
    }

    handleSystemMessage(data) {
        // Broadcast to all iframes
        ['chat', 'users', 'connection'].forEach(iframe => {
            this.sendToIframe(iframe, {
                type: 'system_message',
                data: data
            });
        });
    }

    sendToIframe(targetIframe, message) {
        // This will be handled by the IframeManager
        if (window.iframeManager) {
            window.iframeManager.sendMessage(targetIframe, message);
        }
    }

    getCsrfToken() {
        // Get CSRF token from meta tag or cookie
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        if (metaTag) {
            return metaTag.getAttribute('content');
        }
        
        // Fallback to cookie
        const cookies = document.cookie.split(';');
        for (let cookie of cookies) {
            const [name, value] = cookie.trim().split('=');
            if (name === 'csrf_token') {
                return decodeURIComponent(value);
            }
        }
        return null;
    }

    disconnect() {
        if (this.websocket) {
            this.websocket.close();
        }
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
        }
        this.isConnected = false;
        console.log('Livehelp communication disconnected');
    }
}

// Export for global access
window.LivehelpCommunication = LivehelpCommunication;
