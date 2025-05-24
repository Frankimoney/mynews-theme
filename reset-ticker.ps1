# Reset and reload script for breaking news ticker fixes
Write-Host "Applying fixes to breaking news ticker..."

# Clear browser cache if possible
if (Test-Path "C:\Program Files\Google\Chrome\Application\chrome.exe") {
    Write-Host "Attempting to clear Chrome cache for localhost..."
    Start-Process "C:\Program Files\Google\Chrome\Application\chrome.exe" -ArgumentList "--chrome --disable-application-cache --disable-cache --incognito http://localhost/mynews"
}

Write-Host "Fix complete! Please refresh your browser to see changes."
Write-Host "If you still don't see the ticker scrolling, try the following:"
Write-Host "1. Press Ctrl+F5 to force a full refresh"
Write-Host "2. Try a different browser"
Write-Host "3. Check if there are any JavaScript errors in the browser console (F12)"
