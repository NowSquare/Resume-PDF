@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../jjcarmu/wkhtmltopdf-symfony4/bin/wkhtmltopdf
php "%BIN_TARGET%" %*
