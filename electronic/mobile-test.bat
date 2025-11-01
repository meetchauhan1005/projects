@echo off
echo ========================================
echo  Mahadev Electronic - Mobile Testing
echo ========================================
echo.
echo To test on your phone:
echo.
echo 1. Find your computer's IP address:
for /f "tokens=2 delims=:" %%a in ('ipconfig ^| findstr /c:"IPv4 Address"') do echo    IP Address: %%a
echo.
echo 2. On your phone, connect to same WiFi
echo 3. Open browser and go to:
for /f "tokens=2 delims=:" %%a in ('ipconfig ^| findstr /c:"IPv4 Address"') do echo    http://%%a/electronic/
echo.
echo ========================================
pause