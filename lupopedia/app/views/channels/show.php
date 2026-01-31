<style>
    .channel-container {
        display: flex;
        flex-direction: column;
        height: calc(100vh - 150px);
        width: 100%;
    }
    .channel-main {
        display: flex;
        flex: 1;
        overflow: hidden;
    }
    .channel-left-panel {
        width: 70%;
        border-right: 1px solid #ddd;
        overflow-y: auto;
        padding: 15px;
    }
    .channel-right-panel {
        width: 30%;
        overflow-y: auto;
        padding: 15px;
        background-color: #f9f9f9;
    }
    .channel-bottom-panel {
        border-top: 2px solid #ddd;
        padding: 15px;
        background-color: #fff;
        min-height: 150px;
    }
    .thread-item {
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: #fff;
    }
    .operators-section,
    .visitors-section {
        margin-bottom: 20px;
    }
    .section-title {
        font-weight: bold;
        margin-bottom: 10px;
        color: #333;
    }
    .thread-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }
    .thread-tab {
        padding: 8px 16px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: #f0f0f0;
        cursor: pointer;
    }
    .message-box-placeholder {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 10px;
        background-color: #fafafa;
        color: #999;
    }
</style>

<div class="channel-container">
    <!-- Top Section: Left Panel + Right Panel -->
    <div class="channel-main">
        <!-- Left Panel: Multi-Thread Area -->
        <div class="channel-left-panel">
            <h3>Threads Panel (all threads go here)</h3>

            <div class="thread-item">
                <strong>Thread 1 placeholder</strong>
                <p>Thread messages will appear here</p>
            </div>

            <div class="thread-item">
                <strong>Thread 2 placeholder</strong>
                <p>Thread messages will appear here</p>
            </div>

            <div class="thread-item">
                <strong>Thread 3 placeholder</strong>
                <p>Thread messages will appear here</p>
            </div>
        </div>

        <!-- Right Panel: Operators + Users + Visitors -->
        <div class="channel-right-panel">
            <div class="operators-section">
                <div class="section-title">Operators & Users on this Channel:</div>
                <ul>
                    <li>operator placeholders</li>
                    <li>operator placeholders</li>
                    <li>operator placeholders</li>
                </ul>
            </div>

            <div class="visitors-section">
                <div class="section-title">Visitors:</div>
                <ul>
                    <li>visitors go here</li>
                    <li>visitors go here</li>
                    <li>visitors go here</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Bottom Panel: Thread Tabs + Message Box -->
    <div class="channel-bottom-panel">
        <div class="thread-tabs">
            <div class="thread-tab">[Thread 1]</div>
            <div class="thread-tab">[Thread 2]</div>
            <div class="thread-tab">[Thread 3]</div>
        </div>

        <div class="message-box-placeholder">
            Message box goes here
        </div>
    </div>
</div>
