$url = "http://localhost/Sumon-Enterprise-V2"
$chrome = "$env:ProgramFiles\Google\Chrome\Application\chrome.exe"
$chromeX86 = "${env:ProgramFiles(x86)}\Google\Chrome\Application\chrome.exe"
$edge = "$env:ProgramFiles(x86)\Microsoft\Edge\Application\msedge.exe"

if (Test-Path $chrome) {
    Start-Process -FilePath $chrome -ArgumentList "--incognito", $url
} elseif (Test-Path $chromeX86) {
    Start-Process -FilePath $chromeX86 -ArgumentList "--incognito", $url
} elseif (Test-Path $edge) {
    Start-Process -FilePath $edge -ArgumentList "--inprivate", $url
} else {
    Start-Process $url
}
