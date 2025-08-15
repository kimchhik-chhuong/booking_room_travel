# Booking Travel App - Setup Guide

This guide will help you run your Flutter travel booking app on both **Chrome** (web) and **real phones** (Android/iOS).

## Quick Start (Recommended)

### 1. Automated Setup
Run the automated setup script that configures everything for you:

```bash
# In your project root directory
start-dev.bat
```

This script will:
- ✅ Auto-detect your computer's IP address
- ✅ Configure Flutter `.env` file with the correct API URL
- ✅ Set up Laravel CORS for mobile devices
- ✅ Start Laravel backend server (accessible from any device on your network)
- ✅ Start Flutter app

### 2. Verify Setup
After running the script:

1. **Check Laravel Server**: Open `http://[your-ip]:8000` in your browser
2. **Test API**: Visit `http://[your-ip]:8000/api/login` (should show method not allowed - this is normal)
3. **Run Flutter**: The script automatically starts Flutter, or run manually:
   ```bash
   cd booking_travel_flutter
   flutter run
   ```

## Manual Setup (If Automated Setup Fails)

### Step 1: Find Your Computer's IP Address

**Windows:**
```cmd
ipconfig
```
Look for your Wi-Fi adapter's IPv4 address (usually starts with 192.168.x.x)

**Example:** `192.168.1.100`

### Step 2: Configure Flutter Environment

Edit `booking_travel_flutter/.env`:
```env
# Replace with YOUR computer's IP address
API_BASE_URL=http://192.168.1.100:8000
IMAGE_BASE_URL=http://192.168.1.100:8000
DEBUG_MODE=true
```

### Step 3: Start Laravel Backend

```bash
cd booking_travel_api

# Start server accessible from any IP (important for real devices)
php artisan serve --host=0.0.0.0 --port=8000
```

### Step 4: Run Flutter App

**For Chrome:**
```bash
cd booking_travel_flutter
flutter run -d chrome
```

**For Real Device:**
```bash
cd booking_travel_flutter
flutter run
```

## Platform-Specific Instructions

### 🌐 Chrome (Web)
- ✅ Works with both `localhost` and IP addresses
- ✅ No additional setup required
- ✅ Best for development and testing

### 📱 Real Android/iOS Device

**Requirements:**
1. ✅ Your phone must be on the **same Wi-Fi network** as your computer
2. ✅ Use your computer's **IP address** (not localhost) in `.env` file
3. ✅ Laravel server must bind to `0.0.0.0` (not just localhost)

**Setup Steps:**
1. Connect your phone to the same Wi-Fi as your computer
2. Enable Developer Options and USB Debugging (Android)
3. Connect phone via USB cable
4. Run: `flutter devices` to verify device is detected
5. Run: `flutter run` and select your device

### 🔧 Android Emulator
If using Android emulator, use this special IP in `.env`:
```env
API_BASE_URL=http://10.0.2.2:8000
IMAGE_BASE_URL=http://10.0.2.2:8000
```

## Troubleshooting

### ❌ "Connection Refused" Error
**Cause:** Laravel server is not running
**Solution:**
```bash
cd booking_travel_api
php artisan serve --host=0.0.0.0 --port=8000
```

### ❌ Real Device Can't Connect
**Cause:** Using localhost instead of IP address
**Solutions:**
1. Update `.env` with your computer's IP address
2. Ensure both devices are on same Wi-Fi network
3. Check Windows Firewall (allow port 8000)

### ❌ CORS Errors
**Cause:** Laravel not configured for cross-origin requests
**Solution:** The setup script creates `config/cors.php` automatically, or create manually:

```php
<?php
// booking_travel_api/config/cors.php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['*'],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
```

### ❌ Flutter Build Errors
**Solution:**
```bash
cd booking_travel_flutter
flutter clean
flutter pub get
flutter run
```

## Network Configuration

### Find Your IP Address
**Windows Command Prompt:**
```cmd
ipconfig | findstr IPv4
```

**PowerShell:**
```powershell
Get-NetIPAddress -AddressFamily IPv4 | Where-Object {$_.IPAddress -like "192.168.*"}
```

### Test Network Connectivity
From your phone's browser, visit: `http://[your-computer-ip]:8000`
You should see the Laravel welcome page.

## Development Workflow

### Daily Development
1. **Start Backend:** Run `start-dev.bat` or manually start Laravel
2. **Verify Network:** Check that `http://[your-ip]:8000` is accessible
3. **Run Flutter:** Choose your target platform:
   - Chrome: `flutter run -d chrome`
   - Real device: `flutter run` (select device)
   - All platforms: `flutter run -d all`

### Testing on Multiple Devices
The beauty of using your IP address is that ANY device on your network can access the app:
- ✅ Your computer (Chrome)
- ✅ Your phone (real device)
- ✅ Other team members' devices
- ✅ Tablets, other computers, etc.

## Security Notes

- 🔒 This setup is for **development only**
- 🔒 Don't use `allowed_origins => ['*']` in production
- 🔒 The server binds to `0.0.0.0` for development convenience
- 🔒 In production, use proper domain names and SSL certificates

## Success Indicators

When everything is working correctly, you should see:
- ✅ Laravel server running on `http://[your-ip]:8000`
- ✅ Flutter app connecting successfully
- ✅ Debug logs showing correct IP addresses
- ✅ Login functionality working on both Chrome and real device
- ✅ Images loading properly from the backend

## Need Help?

If you're still having issues:
1. Check the debug logs in your Flutter console
2. Verify your IP address hasn't changed
3. Ensure Windows Firewall isn't blocking port 8000
4. Try restarting both Laravel server and Flutter app
5. Make sure your phone and computer are on the same Wi-Fi network

Happy coding! 🚀
