param(
    [switch]$Watch
)

$VAULT_PATH = "D:\Software\ObsidianVaults\nuevaweb"

function Sync-GraphAndObsidian {
    Write-Host "`n[Graphify] 🔄 Updating AST Knowledge Graph..." -ForegroundColor Cyan
    python -m graphify update .
    python -m graphify cluster-only . | Out-Null
    
    Write-Host "[Obsidian] 🚀 Exporting notes to $VAULT_PATH..." -ForegroundColor Green
    python -m graphify export obsidian --dir $VAULT_PATH
    
    Write-Host "[Success] ✨ Graphify & Obsidian are fully in sync!`n" -ForegroundColor Green
}

if ($Watch) {
    Write-Host "`n👀 [Live Watcher Active] Monitoring code changes in nuevaweb..." -ForegroundColor Yellow
    Write-Host "💡 Setiap file .php/.js yang lu simpan (Ctrl+S) akan otomatis ter-sync ke Obsidian."
    Write-Host "Tekan Ctrl+C untuk berhenti.`n"
    
    # Initial sync
    Sync-GraphAndObsidian
    
    $graphFile = Join-Path $PSScriptRoot "graphify-out\graph.json"
    $lastMtime = if (Test-Path $graphFile) { (Get-Item $graphFile).LastWriteTime } else { [DateTime]::MinValue }
    
    # Start graphify watch in background job or watcher loop
    $job = Start-Job -ScriptBlock {
        param($cwd)
        Set-Location $cwd
        python -m graphify watch .
    } -ArgumentList $PSScriptRoot

    try {
        while ($true) {
            Start-Sleep -Seconds 2
            if (Test-Path $graphFile) {
                $currMtime = (Get-Item $graphFile).LastWriteTime
                if ($currMtime -gt $lastMtime) {
                    $lastMtime = $currMtime
                    Write-Host "[Live Sync] ⚡ Graph updated, syncing notes to Obsidian..." -ForegroundColor Green
                    python -m graphify export obsidian --dir $VAULT_PATH | Out-Null
                    Write-Host "[Live Sync] ✅ Obsidian notes updated!" -ForegroundColor Green
                }
            }
        }
    } finally {
        Stop-Job $job -ErrorAction SilentlyContinue
        Remove-Job $job -ErrorAction SilentlyContinue
        Write-Host "`n[Live Watcher] Stopped." -ForegroundColor Yellow
    }
} else {
    Sync-GraphAndObsidian
}
