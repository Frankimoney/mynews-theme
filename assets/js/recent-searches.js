/**
 * Recent searches tracker for mobile search
 * 
 * Tracks search terms and stores them in a cookie
 * to display in the mobile search modal
 */

(function($) {
    'use strict';
    
    /**
     * Track search terms when a search is performed
     */
    function trackSearches() {
        // Only track searches if the feature is enabled
        if (!myNewsSearchTracker.enabled) {
            return;
        }
        
        // Handle search form submissions
        $('.search-form').on('submit', function() {
            var searchTerm = $(this).find('input[type="search"]').val().trim();
            
            if (searchTerm.length > 0) {
                addSearchTerm(searchTerm);
            }
        });
    }
    
    /**
     * Add a search term to the recent searches cookie
     */
    function addSearchTerm(term) {
        // Get existing searches
        var searches = getRecentSearches();
        
        // Add new term if it doesn't exist already
        if (searches.indexOf(term) === -1) {
            // Add to the beginning
            searches.unshift(term);
            
            // Keep only the most recent searches
            searches = searches.slice(0, myNewsSearchTracker.maxTerms);
            
            // Save to cookie
            saveRecentSearches(searches);
        }
    }
    
    /**
     * Get recent searches from cookie
     */
    function getRecentSearches() {
        var searches = getCookie('mynews_recent_searches');
        
        if (searches) {
            try {
                return JSON.parse(searches);
            } catch(e) {
                return [];
            }
        }
        
        return [];
    }
    
    /**
     * Save recent searches to cookie
     */
    function saveRecentSearches(searches) {
        setCookie('mynews_recent_searches', JSON.stringify(searches), 30);
    }
    
    /**
     * Set a cookie
     */
    function setCookie(name, value, days) {
        var expires = '';
        
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = '; expires=' + date.toUTCString();
        }
        
        document.cookie = name + '=' + encodeURIComponent(value) + expires + '; path=/';
    }
    
    /**
     * Get a cookie value
     */
    function getCookie(name) {
        var nameEQ = name + '=';
        var ca = document.cookie.split(';');
        
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) === ' ') {
                c = c.substring(1, c.length);
            }
            if (c.indexOf(nameEQ) === 0) {
                return decodeURIComponent(c.substring(nameEQ.length, c.length));
            }
        }
        
        return null;
    }
    
    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        trackSearches();
    });
    
})(jQuery);
