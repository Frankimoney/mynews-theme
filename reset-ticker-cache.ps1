# Reset Ticker Cache Script
# This PowerShell script adds cache-busting query parameters to the Breaking News Ticker JS/CSS files

# Get current timestamp for cache busting
$timestamp = Get-Date -Format "yyyyMMddHHmmss"

# Functions file path
$functionsPath = "c:\xampp\htdocs\mynews\wp-content\themes\mynews\functions.php"

# Read functions.php 
$content = Get-Content $functionsPath -Raw

# Check if there's an existing MYNEWS_VERSION definition
if ($content -match "define\s*\(\s*'MYNEWS_VERSION'\s*,\s*['\"](.*?)['\"]\s*\)") {
    # Update the version with timestamp
    $newContent = $content -replace "define\s*\(\s*'MYNEWS_VERSION'\s*,\s*['\"](.*?)['\"]\s*\)", "define( 'MYNEWS_VERSION', '1.0.0-$timestamp' )"
    
    # Write the updated content back to functions.php
    $newContent | Set-Content $functionsPath -Force
    
    Write-Host "Successfully updated MYNEWS_VERSION with timestamp: $timestamp"
    Write-Host "Cache should now be cleared for ticker JavaScript and CSS files."
    Write-Host "Please refresh your browser with Ctrl+F5 to see changes."
} else {
    Write-Host "Couldn't find MYNEWS_VERSION in functions.php. No changes were made."
}
