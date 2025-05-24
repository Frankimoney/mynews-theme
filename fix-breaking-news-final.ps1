# Script to apply the fixed breaking news ticker JS version

Write-Host "Fixing the breaking news ticker to show all items..."

# Backup the original file
$jsPath = "c:\xampp\htdocs\mynews\wp-content\themes\mynews\assets\js\breaking-news-ticker.js"
$backupPath = "c:\xampp\htdocs\mynews\wp-content\themes\mynews\assets\js\breaking-news-ticker.js.bak"

if (-Not (Test-Path $backupPath)) {
    Write-Host "Creating backup of original JS file..."
    Copy-Item -Path $jsPath -Destination $backupPath
}

# Copy the fixed file over the original
Write-Host "Replacing with fixed version..."
Copy-Item -Path "c:\xampp\htdocs\mynews\wp-content\themes\mynews\assets\js\breaking-news-ticker-fixed2.js" -Destination $jsPath -Force

Write-Host "Fix applied! Please refresh your browser with Ctrl+F5 to see all ticker items scrolling."
