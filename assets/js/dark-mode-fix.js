/**
 * Dark Mode Toggle Fix
 * 
 * Fixes issues with dark mode toggle functionality
 * 
 * @package My_News
 */

(function($) {
    'use strict';
    
    // Variables
    const STORAGE_KEY = 'mynews_dark_mode';
    const DARK_MODE_CLASS = 'data-theme';
    const DARK_MODE_VALUE = 'dark';
    
    /**
     * Initialize dark mode functionality
     */
    function initDarkMode() {
        const toggle = $('#dark-mode-toggle');
        
        if (toggle.length === 0) {
            console.log('Dark mode toggle not found');
            return;
        }
        
        const darkModeEnabled = loadDarkModePreference();
        
        // Set initial state based on saved preference or system preference
        if (darkModeEnabled) {
            enableDarkMode();
            toggle.prop('checked', true);
        }
        
        // Toggle dark mode on click
        toggle.on('change', function() {
            if ($(this).is(':checked')) {
                enableDarkMode();
                saveDarkModePreference(true);
            } else {
                disableDarkMode();
                saveDarkModePreference(false);
            }
        });
        
        // Ensure toggle is visible
        toggle.css('opacity', '1');
        $('.dark-mode-toggle').css('visibility', 'visible');
    }
    
    /**
     * Enable dark mode
     */
    function enableDarkMode() {
        document.documentElement.setAttribute(DARK_MODE_CLASS, DARK_MODE_VALUE);
        
        // Dispatch event for other scripts that might need to know about dark mode
        document.dispatchEvent(new CustomEvent('darkModeChange', { 
            detail: { darkMode: true } 
        }));
    }
    
    /**
     * Disable dark mode
     */
    function disableDarkMode() {
        document.documentElement.removeAttribute(DARK_MODE_CLASS);
        
        // Dispatch event for other scripts that might need to know about dark mode
        document.dispatchEvent(new CustomEvent('darkModeChange', { 
            detail: { darkMode: false } 
        }));
    }
    
    /**
     * Save dark mode preference to localStorage
     *
     * @param {boolean} enabled - Whether dark mode is enabled
     */
    function saveDarkModePreference(enabled) {
        if (typeof localStorage !== 'undefined') {
            try {
                localStorage.setItem(STORAGE_KEY, JSON.stringify(enabled));
            } catch (e) {
                console.error('Failed to save dark mode preference:', e);
            }
        }
    }
    
    /**
     * Load dark mode preference from localStorage or system preference
     *
     * @return {boolean} Whether dark mode should be enabled
     */
    function loadDarkModePreference() {
        // Check localStorage first
        if (typeof localStorage !== 'undefined') {
            try {
                const savedPreference = localStorage.getItem(STORAGE_KEY);
                if (savedPreference !== null) {
                    return JSON.parse(savedPreference);
                }
            } catch (e) {
                console.error('Failed to load dark mode preference:', e);
            }
        }
        
        // If no saved preference, check system preference
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            return true;
        }
        
        // Default to light mode
        return false;
    }
    
    // Initialize on document ready and also when DOM content is fully loaded
    $(document).ready(function() {
        initDarkMode();
    });
    
    // Backup initialization in case jQuery ready fails
    document.addEventListener('DOMContentLoaded', function() {
        initDarkMode();
    });
    
    // Also listen for system preference changes
    if (window.matchMedia) {
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            // Only update if user hasn't set a preference
            if (typeof localStorage !== 'undefined') {
                try {
                    if (localStorage.getItem(STORAGE_KEY) === null) {
                        const darkModeEnabled = e.matches;
                        if (darkModeEnabled) {
                            enableDarkMode();
                            $('#dark-mode-toggle').prop('checked', true);
                        } else {
                            disableDarkMode();
                            $('#dark-mode-toggle').prop('checked', false);
                        }
                    }
                } catch (e) {
                    console.error('Failed to check dark mode preference:', e);
                }
            }
        });
    }
    
    // Update toggle button state when dark mode changes from other sources
    document.addEventListener('darkModeChange', function(event) {
        $('#dark-mode-toggle').prop('checked', event.detail.darkMode);
    });
    
})(jQuery);
