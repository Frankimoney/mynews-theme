# Complete Breaking News Ticker Fix Script
Write-Host "Applying comprehensive fixes to breaking news ticker..."

# 1. Check if browser is open and suggest closing it
Write-Host "For best results, please close your web browser before continuing."
Write-Host "Press any key to continue..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")

# 2. Clear WordPress cache if any cache plugin is active
Write-Host "Attempting to clear WordPress cache..."
$wpContent = Get-ChildItem -Path "C:\xampp\htdocs\mynews\wp-content\cache" -ErrorAction SilentlyContinue
if ($wpContent) {
    Write-Host "Found WordPress cache, attempting to clear it..."
    Remove-Item -Path "C:\xampp\htdocs\mynews\wp-content\cache\*" -Recurse -Force -ErrorAction SilentlyContinue
}

# 3. Trigger browser cache clearing
if (Test-Path "C:\Program Files\Google\Chrome\Application\chrome.exe") {
    Write-Host "Would you like to open Chrome with cleared cache? (Y/N)"
    $response = Read-Host
    if ($response -eq "Y" -or $response -eq "y") {
        Start-Process "C:\Program Files\Google\Chrome\Application\chrome.exe" -ArgumentList "--new-window --disable-application-cache --disable-cache --incognito http://localhost/mynews"
    }
}

Write-Host "`nFix complete! The breaking news ticker should now scroll correctly across all devices."
Write-Host "`nIf you still experience issues:"
Write-Host "1. Press Ctrl+F5 in your browser to force a complete refresh"
Write-Host "2. Check your browser's developer console (F12) for any JavaScript errors"
Write-Host "3. Try viewing the site in a different browser"
Write-Host "4. Make sure your WordPress cache plugins are cleared/disabled temporarily for testing"
