/**
 * Live Mood Updates - 30-second polling
 *
 * Fetches current operator mood and updates UI smoothly
 */

(function() {
    'use strict';

    const POLL_INTERVAL = 30000; // 30 seconds
    let pollTimer = null;

    /**
     * Fetch current operator emotional data
     */
    async function fetchOperatorMood() {
        try {
            const response = await fetch(window.location.pathname + '?ajax=mood', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                console.warn('Live mood fetch failed:', response.status);
                return null;
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Live mood error:', error);
            return null;
        }
    }

    /**
     * Update UI with new mood data
     */
    function updateMoodUI(moodData) {
        if (!moodData) return;

        // Update System Activity panel emotional values
        const ponoElement = document.querySelector('.cs-value.cs-pono');
        const pilauElement = document.querySelector('.cs-value.cs-pilau');
        const kapakaiElement = document.querySelector('.cs-value.cs-kapakai');

        if (ponoElement && moodData.pono) {
            animateValueChange(ponoElement, moodData.pono);
        }

        if (pilauElement && moodData.pilau) {
            animateValueChange(pilauElement, moodData.pilau);
        }

        if (kapakaiElement && moodData.kapakai) {
            animateValueChange(kapakaiElement, moodData.kapakai);
        }

        // Update mood RGB if available
        if (moodData.mood_rgb) {
            const moodElements = document.querySelectorAll('.cs-mood-display');
            moodElements.forEach(el => {
                const newColor = '#' + moodData.mood_rgb;
                if (el.style.backgroundColor !== newColor) {
                    el.style.transition = 'background-color 1s ease';
                    el.style.backgroundColor = newColor;
                    el.textContent = moodData.mood_rgb;
                }
            });
        }
    }

    /**
     * Animate value change with smooth transition
     */
    function animateValueChange(element, newValue) {
        const oldValue = element.textContent.trim();
        if (oldValue !== newValue) {
            element.style.transition = 'opacity 0.3s ease';
            element.style.opacity = '0.5';

            setTimeout(() => {
                element.textContent = newValue;
                element.style.opacity = '1';
            }, 300);
        }
    }

    /**
     * Start polling
     */
    function startPolling() {
        // Initial fetch
        fetchOperatorMood().then(updateMoodUI);

        // Set up interval
        pollTimer = setInterval(async () => {
            const moodData = await fetchOperatorMood();
            updateMoodUI(moodData);
        }, POLL_INTERVAL);

        console.log('Live mood polling started (30s interval)');
    }

    /**
     * Stop polling
     */
    function stopPolling() {
        if (pollTimer) {
            clearInterval(pollTimer);
            pollTimer = null;
            console.log('Live mood polling stopped');
        }
    }

    /**
     * Initialize on page load
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startPolling);
    } else {
        startPolling();
    }

    /**
     * Cleanup on page unload
     */
    window.addEventListener('beforeunload', stopPolling);

})();
