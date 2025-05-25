<?php
/**
 * Dark Mode Troubleshooting Tool
 * 
 * A simple script to diagnose and fix dark mode issues in the MyNews theme.
 * 
 * Usage instructions:
 * 1. Add this file to your theme's inc/ directory
 * 2. Access it via: yourdomain.com/?dark_mode_troubleshoot=1 
 * 3. Follow the on-screen instructions
 * 
 * @package My_News
 */

// Security check - only run this when explicitly requested
if (isset($_GET['dark_mode_troubleshoot'])) {
    add_action('wp_head', 'mynews_dark_mode_troubleshoot');
}

function mynews_dark_mode_troubleshoot() {
    // Only run for logged in administrators
    if (!current_user_can('administrator')) {
        return;
    }
    
    ?>
    <style>
        .dark-mode-troubleshoot {
            position: fixed;
            top: 32px;
            right: 0;
            width: 400px;
            max-width: 100%;
            background: #fff;
            border-left: 4px solid #00a0d2;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            z-index: 99999;
            padding: 20px;
            overflow: auto;
            max-height: calc(100vh - 32px);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
        }
        
        .dark-mode-troubleshoot h2 {
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .dark-mode-troubleshoot .status {
            padding: 10px;
            border-left: 4px solid;
            margin-bottom: 10px;
        }
        
        .dark-mode-troubleshoot .status.error {
            border-color: #dc3545;
            background: #f8d7da;
        }
        
        .dark-mode-troubleshoot .status.success {
            border-color: #28a745;
            background: #d4edda;
        }
        
        .dark-mode-troubleshoot .status.warning {
            border-color: #ffc107;
            background: #fff3cd;
        }
        
        .dark-mode-troubleshoot .status.info {
            border-color: #17a2b8;
            background: #d1ecf1;
        }
        
        .dark-mode-troubleshoot .fix-button {
            background: #0073aa;
            border: none;
            color: #fff;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
            margin: 5px 0;
        }
        
        .dark-mode-troubleshoot .fix-button:hover {
            background: #005177;
        }
        
        .dark-mode-troubleshoot .close-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #aaa;
        }
        
        .dark-mode-troubleshoot .close-button:hover {
            color: #333;
        }
    </style>
    
    <div class="dark-mode-troubleshoot">
        <button class="close-button" onclick="this.parentElement.remove()">×</button>
        <h2>Dark Mode Troubleshooter</h2>
        
        <div id="dark-mode-diagnostics"></div>
        
        <div class="status info">
            <p><strong>Toggle Status:</strong> <span id="toggle-status"></span></p>
            <p><strong>Dark Mode Status:</strong> <span id="dark-mode-status"></span></p>
            <p><strong>LocalStorage Status:</strong> <span id="local-storage-status"></span></p>
        </div>
        
        <h3>Quick Actions</h3>
        <button class="fix-button" onclick="forceDarkMode()">Force Dark Mode</button>
        <button class="fix-button" onclick="forceLightMode()">Force Light Mode</button>
        <button class="fix-button" onclick="resetDarkMode()">Reset Dark Mode</button>
        <button class="fix-button" onclick="reloadResources()">Reload Resources</button>
        
        <h3>Debug Info</h3>
        <textarea id="debug-info" style="width: 100%; height: 100px; font-family: monospace;" readonly></textarea>
    </div>
    
    <script>
        function runDiagnostics() {
            const diagnosticsElement = document.getElementById('dark-mode-diagnostics');
            const toggleStatusElement = document.getElementById('toggle-status');
            const darkModeStatusElement = document.getElementById('dark-mode-status');
            const localStorageStatusElement = document.getElementById('local-storage-status');
            const debugInfoElement = document.getElementById('debug-info');
            
            // Clear previous diagnostics
            diagnosticsElement.innerHTML = '';
            
            // Array to store issues
            const issues = [];
            
            // Check dark mode toggle
            const toggle = document.getElementById('dark-mode-toggle');
            if (!toggle) {
                issues.push({
                    type: 'error',
                    message: 'Dark mode toggle not found in the DOM. Check if the toggle is properly added to the header.php file.'
                });
                toggleStatusElement.textContent = 'Not Found';
            } else {
                toggleStatusElement.textContent = toggle.checked ? 'ON' : 'OFF';
            }
            
            // Check if dark mode is active
            const isDarkMode = document.documentElement.hasAttribute('data-theme');
            darkModeStatusElement.textContent = isDarkMode ? 'Active' : 'Inactive';
            
            // Check if toggle state matches dark mode state
            if (toggle && toggle.checked !== isDarkMode) {
                issues.push({
                    type: 'warning',
                    message: 'Dark mode toggle state does not match the actual dark mode state.'
                });
            }
            
            // Check localStorage
            try {
                localStorage.setItem('test', 'test');
                localStorage.removeItem('test');
                localStorageStatusElement.textContent = 'Functional';
                
                // Check if dark mode preference is saved
                const darkModePreference = localStorage.getItem('mynews_dark_mode');
                if (darkModePreference === null) {
                    issues.push({
                        type: 'info',
                        message: 'No dark mode preference found in localStorage.'
                    });
                } else {
                    try {
                        const preference = JSON.parse(darkModePreference);
                        if (typeof preference !== 'boolean') {
                            issues.push({
                                type: 'warning',
                                message: 'Dark mode preference is not a boolean value.'
                            });
                        }
                    } catch (e) {
                        issues.push({
                            type: 'error',
                            message: 'Failed to parse dark mode preference from localStorage.'
                        });
                    }
                }
            } catch (e) {
                localStorageStatusElement.textContent = 'Non-functional';
                issues.push({
                    type: 'error',
                    message: 'LocalStorage is not available. Dark mode preferences cannot be saved.'
                });
            }
            
            // Check CSS resources
            const darkModeStylesheet = Array.from(document.styleSheets).find(sheet => sheet.href && sheet.href.includes('dark-mode.css'));
            if (!darkModeStylesheet) {
                issues.push({
                    type: 'error',
                    message: 'Dark mode stylesheet not found. Check if dark-mode.css is properly enqueued.'
                });
            }
            
            // Check JavaScript resources
            if (!window.jQuery) {
                issues.push({
                    type: 'error',
                    message: 'jQuery not found. Dark mode functionality requires jQuery.'
                });
            }
            
            // Display issues
            if (issues.length === 0) {
                diagnosticsElement.innerHTML = '<div class="status success"><p>No issues detected with dark mode functionality.</p></div>';
            } else {
                issues.forEach(issue => {
                    const issueElement = document.createElement('div');
                    issueElement.className = `status ${issue.type}`;
                    issueElement.innerHTML = `<p>${issue.message}</p>`;
                    diagnosticsElement.appendChild(issueElement);
                });
            }
            
            // Display debug info
            const debugInfo = {
                'User Agent': navigator.userAgent,
                'Window Width': window.innerWidth,
                'Window Height': window.innerHeight,
                'Dark Mode State': isDarkMode,
                'Toggle State': toggle ? toggle.checked : 'N/A',
                'Stylesheets': Array.from(document.styleSheets).filter(sheet => sheet.href).map(sheet => sheet.href),
                'Scripts': Array.from(document.scripts).filter(script => script.src).map(script => script.src),
                'LocalStorage Available': typeof localStorage !== 'undefined',
                'System Dark Mode': window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
            };
            
            debugInfoElement.value = JSON.stringify(debugInfo, null, 2);
        }
        
        function forceDarkMode() {
            document.documentElement.setAttribute('data-theme', 'dark');
            const toggle = document.getElementById('dark-mode-toggle');
            if (toggle) {
                toggle.checked = true;
            }
            
            try {
                localStorage.setItem('mynews_dark_mode', 'true');
            } catch (e) {
                console.error('Failed to save dark mode preference:', e);
            }
            
            runDiagnostics();
        }
        
        function forceLightMode() {
            document.documentElement.removeAttribute('data-theme');
            const toggle = document.getElementById('dark-mode-toggle');
            if (toggle) {
                toggle.checked = false;
            }
            
            try {
                localStorage.setItem('mynews_dark_mode', 'false');
            } catch (e) {
                console.error('Failed to save dark mode preference:', e);
            }
            
            runDiagnostics();
        }
        
        function resetDarkMode() {
            try {
                localStorage.removeItem('mynews_dark_mode');
            } catch (e) {
                console.error('Failed to remove dark mode preference:', e);
            }
            
            // Check system preference
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.setAttribute('data-theme', 'dark');
                const toggle = document.getElementById('dark-mode-toggle');
                if (toggle) {
                    toggle.checked = true;
                }
            } else {
                document.documentElement.removeAttribute('data-theme');
                const toggle = document.getElementById('dark-mode-toggle');
                if (toggle) {
                    toggle.checked = false;
                }
            }
            
            runDiagnostics();
        }
        
        function reloadResources() {
            // Force reload CSS
            Array.from(document.styleSheets).forEach(sheet => {
                if (sheet.href) {
                    const link = document.createElement('link');
                    link.rel = 'stylesheet';
                    link.href = sheet.href + '?cachebust=' + new Date().getTime();
                    document.head.appendChild(link);
                }
            });
            
            // Force reload page after a short delay
            setTimeout(() => {
                window.location.reload(true);
            }, 500);
        }
        
        // Run diagnostics on load
        document.addEventListener('DOMContentLoaded', function() {
            runDiagnostics();
        });
    </script>
    <?php
}
