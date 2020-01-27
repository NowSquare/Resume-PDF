@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../jjcarmu/wkhtmltopdf-symfony4/bin/wkhtmltoimage
php "%BIN_TARGET%" %*
