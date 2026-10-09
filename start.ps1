#!/usr/bin/env pwsh

Write-Host "╔════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║  BERCIANO TECH - Sistema iniciado ║" -ForegroundColor Cyan
Write-Host "╚════════════════════════════════════╝" -ForegroundColor Cyan
Write-Host ""

# Esperar 3 segundos para que los contenedores estén listos
Start-Sleep -Seconds 3

# Abrir el navegador
Write-Host "🚀 Abriendo BERCIANO TECH en http://localhost:8000/inicio.html" -ForegroundColor Green
Start-Process "http://localhost:8000/inicio.html"

Write-Host ""
Write-Host "✓ Contenedores disponibles:" -ForegroundColor Green
Write-Host "  • BERCIANO TECH (puerto 8000): http://localhost:8000/inicio.html" -ForegroundColor Yellow
Write-Host "  • PHP 2 (puerto 8001): http://localhost:8001/" -ForegroundColor Yellow
Write-Host "  • phpMyAdmin (puerto 8080): http://localhost:8080/" -ForegroundColor Yellow
Write-Host ""
