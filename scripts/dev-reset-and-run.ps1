#requires -version 5.1
Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'

Write-Host 'Citizen ID • Clean, setup, migrate, and run' -ForegroundColor Cyan

function Remove-Safe {
  param(
    [Parameter(Mandatory)][string]$Path,
    [switch]$Recurse
  )
  if (Test-Path -LiteralPath $Path) {
    try {
      Remove-Item -LiteralPath $Path -Force -Recurse:$Recurse.IsPresent -ErrorAction Stop
      Write-Host "Removed: $Path" -ForegroundColor DarkGray
    } catch {
      Write-Warning "Could not remove $Path: $($_.Exception.Message)"
    }
  }
}

# 1) Cleanup (safe, ignores missing paths)
Write-Host 'Step 1/7: Cleaning build and cache artifacts...' -ForegroundColor Yellow
Remove-Safe -Path '.\\.xampp' -Recurse
Remove-Safe -Path '.\\vendor' -Recurse
Remove-Safe -Path '.\\node_modules' -Recurse
Remove-Safe -Path '.\\public\\assets' -Recurse
Remove-Safe -Path '.\\public\\storage' -Recurse
Remove-Safe -Path '.\\storage\\framework\\views' -Recurse
Remove-Safe -Path '.\\storage\\framework\\cache' -Recurse
Remove-Safe -Path '.\\storage\\framework\\sessions' -Recurse
Remove-Safe -Path '.\\storage\\logs' -Recurse
Remove-Safe -Path '.\\.env'

# 2) Ensure .env exists
Write-Host 'Step 2/7: Ensuring .env exists...' -ForegroundColor Yellow
if (-not (Test-Path -LiteralPath '.env')) {
  if (Test-Path -LiteralPath '.env.example') {
    Copy-Item -LiteralPath '.env.example' -Destination '.env' -Force
    Write-Host 'Created .env from .env.example' -ForegroundColor DarkGray
  } else {
    throw '.env and .env.example are missing.'
  }
}

# 3) Update .env key/values (MySQL, database-backed session/queue/cache)
Write-Host 'Step 3/7: Updating .env with standardized settings...' -ForegroundColor Yellow
$updates = [ordered]@{
  'APP_URL'          = 'http://127.0.0.1:8000'
  'DB_CONNECTION'    = 'mysql'
  'DB_HOST'          = '127.0.0.1'
  'DB_PORT'          = '3306'
  'DB_DATABASE'      = 'citizen_id'
  'DB_USERNAME'      = 'root'
  'DB_PASSWORD'      = ''
  'SESSION_DRIVER'   = 'database'
  'QUEUE_CONNECTION' = 'database'
  'CACHE_STORE'      = 'database'
}
$envPath = '.env'
$content = Get-Content -LiteralPath $envPath -Raw -Encoding UTF8
foreach ($k in $updates.Keys) {
  if ($content -match "^(?m)$([regex]::Escape($k))=.*") {
    $content = $content -replace "^(?m)$([regex]::Escape($k))=.*", "$k=$($updates[$k])"
  } else {
    $content += "`r`n$k=$($updates[$k])"
  }
}
Set-Content -LiteralPath $envPath -Value $content -Encoding UTF8

# 4) Install dependencies
Write-Host 'Step 4/7: Installing Composer and NPM dependencies...' -ForegroundColor Yellow
# Composer
if (Get-Command composer -ErrorAction SilentlyContinue) {
  composer install --no-interaction --prefer-dist | Write-Host
} else {
  throw 'Composer is not available on PATH.'
}
# NPM
if (Test-Path -LiteralPath 'package-lock.json') {
  npm ci | Write-Host
} else {
  npm install | Write-Host
}

# 5) App key, cache clears, and DB prep
Write-Host 'Step 5/7: Generating key, clearing caches, preparing database tables...' -ForegroundColor Yellow
php artisan key:generate --ansi | Write-Host
php artisan config:clear | Write-Host
php artisan cache:clear | Write-Host
php artisan view:clear | Write-Host

# Try to create MySQL database if mysql client exists
try {
  $mysql = Get-Command mysql -ErrorAction Stop
  & $mysql.Source -uroot -e "CREATE DATABASE IF NOT EXISTS citizen_id CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" | Out-Null
  Write-Host 'Ensured MySQL database exists: citizen_id' -ForegroundColor DarkGray
} catch {
  Write-Warning 'mysql client not found; ensure the database "citizen_id" exists or update .env accordingly.'
}

# Create tables for database-backed drivers
php artisan session:table | Write-Host
php artisan queue:table | Write-Host
php artisan cache:table | Write-Host
php artisan migrate --force | Write-Host
php artisan storage:link | Write-Host

# 6) Build frontend (optional; dev server also fine)
Write-Host 'Step 6/7: Building frontend assets (vite build)...' -ForegroundColor Yellow
npm run build | Write-Host

# 7) Start servers (new terminals)
Write-Host 'Step 7/7: Starting servers...' -ForegroundColor Yellow
Start-Process powershell -ArgumentList '-NoExit','-Command','php artisan serve --host=127.0.0.1 --port=8000'
Start-Process powershell -ArgumentList '-NoExit','-Command','npm run dev'

Write-Host 'All done. Visit: http://127.0.0.1:8000' -ForegroundColor Green
