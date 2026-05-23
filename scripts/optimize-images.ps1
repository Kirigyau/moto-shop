# Запуск сжатия загруженных фото (нужен PHP с GD).
$artisan = Join-Path $PSScriptRoot '..' 'artisan'
Set-Location (Join-Path $PSScriptRoot '..')
php $artisan images:optimize @args
