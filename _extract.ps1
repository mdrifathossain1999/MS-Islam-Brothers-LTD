$base = "G:\xampp\htdocs\Sumon-Enterprise-V2\app\views"
$files = Get-ChildItem -Path $base -Recurse -Filter "*.php" | Where-Object { !$_.FullName.Contains('layouts\main.php') }
$results = @()

foreach ($file in $files) {
    $relPath = $file.FullName.Replace($base, "").TrimStart("\")
    $content = Get-Content $file.FullName -Raw
    
    # page titles
    if ($content -match '\$pageTitle\s*=\s*\"([^\"]+)\"') {
        $results += "$relPath|PAGE_TITLE|$($matches[1])"
    }
    
    # h1-h6
    $headings = [regex]::Matches($content, '<h[1-6][^>]*>(?:<[^>]+>)*\s*([^<>\n]{2,100}?)\s*(?:<\/[^>]+>)*\s*<\/h[1-6]>')
    foreach ($h in $headings) {
        $text = $h.Groups[1].Value.Trim()
        if ($text.Length -gt 1) { $results += "$relPath|HEADING|$text" }
    }
    
    # button/link text
    $btns = [regex]::Matches($content, '<(?:button|a)[^>]*>(?:<[^>]+>)*\s*([^<>\n]{2,100}?)\s*(?:<\/[^>]+>)*\s*<\/(?:button|a)>')
    foreach ($b in $btns) {
        $text = $b.Groups[1].Value.Trim()
        if ($text.Length -gt 1 -and !$text.Contains('<?') -and !$text.Contains('echo ')) { $results += "$relPath|BUTTON_LINK|$text" }
    }
    
    # labels
    $labels = [regex]::Matches($content, '<label[^>]*>(?:<[^>]+>)*\s*([^<>\n]{2,80}?)\s*(?:<\/[^>]+>)*\s*<\/label>')
    foreach ($l in $labels) {
        $text = $l.Groups[1].Value.Trim()
        if ($text.Length -gt 1 -and !$text.Contains('<?') -and !$text.Contains('echo ')) { $results += "$relPath|LABEL|$text" }
    }
    
    # th headers
    $ths = [regex]::Matches($content, '<th[^>]*>(?:<[^>]+>)*\s*([^<>\n]{2,60}?)\s*(?:<\/[^>]+>)*\s*<\/th>')
    foreach ($t in $ths) {
        $text = $t.Groups[1].Value.Trim()
        if ($text.Length -gt 1 -and !$text.Contains('<?') -and !$text.Contains('echo ')) { $results += "$relPath|TABLE_HEADER|$text" }
    }
    
    # placeholders
    $ph = [regex]::Matches($content, 'placeholder="([^"]+)"')
    foreach ($p in $ph) {
        $text = $p.Groups[1].Value.Trim()
        if ($text.Length -gt 1) { $results += "$relPath|PLACEHOLDER|$text" }
    }
    
    # option text
    $opts = [regex]::Matches($content, '<option[^>]*>\s*([^<>\n]{2,100}?)\s*<\/option>')
    foreach ($o in $opts) {
        $text = $o.Groups[1].Value.Trim()
        if ($text.Length -gt 1 -and !$text.Contains('<?') -and !$text.Contains('echo ') -and !$text.Contains('$') -and !$text.Contains('selected') -and !$text.Contains('value=')) { $results += "$relPath|OPTION|$text" }
    }
    
    # plain text messages like "No records found"
    $msgs = [regex]::Matches($content, '>(No [^<]{3,100}?)<')
    foreach ($m in $msgs) {
        $text = $m.Groups[1].Value.Trim()
        if ($text.Length -gt 3 -and !$text.Contains('<?') -and !$text.Contains('echo ')) { $results += "$relPath|MESSAGE|$text" }
    }
}

$results | Sort-Object | Get-Unique | Out-File -FilePath "G:\xampp\htdocs\Sumon-Enterprise-V2\_extracted_strings.csv" -Encoding UTF8
Write-Output "Total unique strings: $($results | Sort-Object | Get-Unique | Measure-Object | Select-Object -ExpandProperty Count)"
