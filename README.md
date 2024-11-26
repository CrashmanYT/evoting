# E-Voting System Documentation

## Table of Contents
- [Overview](#overview)
- [System Requirements](#system-requirements)
- [Key Features](#key-features)
  - [Authentication System](#authentication-system)
  - [Admin Dashboard](#admin-dashboard)
  - [Participant Management](#participant-management)
  - [Candidate Management](#candidate-management)
  - [Voting System](#voting-system)
  - [Real-time Results](#real-time-results)
- [Project Structure](#project-structure)
  - [Routes](#routes)
  - [Controllers](#controllers)
  - [Models](#models)
- [Security Features](#security-features)
- [Installation Guide](#installation-guide)
  - [Using Laragon](#using-laragon)
  - [Installing Composer and NPM](#installing-composer-and-npm-if-not-detected-in-laragon)
  - [Using XAMPP](#using-xampp)
- [Troubleshooting](#troubleshooting-common-installation-issues)
  - [PHP ZIP Extension Error](#php-zip-extension-error)
  - [Database Connection Error](#database-connection-error)
  - [Table Already Exists Error](#table-already-exists-error)
- [Usage Guide](#usage-guide)

## Overview
This is a web-based e-voting system built with Laravel, designed to facilitate secure and efficient electronic voting. The system includes both admin and voter interfaces, with features for managing candidates, participants, and real-time voting results.

## System Requirements
- PHP >= 8.0
- Laravel 10.x
- MySQL/MariaDB
- Node.js and NPM
- Composer

## Key Features
1. **Authentication System**
   - Admin authentication with email verification
   - Secure login and password reset functionality
   - Profile management for administrators

2. **Admin Dashboard**
   - Overview of voting statistics
   - System settings management
   - Voting limit configuration
   - Profile management

3. **Participant Management**
   - Create, read, update, and delete participants
   - Bulk import participants
   - Track voting status
   - Participant verification

4. **Candidate Management**
   - Add and manage election candidates
   - Edit candidate information
   - Remove candidates from the election

5. **Voting System**
   - Secure voting interface
   - One-time voting enforcement
   - Real-time vote counting
   - Vote verification system

6. **Real-time Results**
   - Live vote counting
   - Result visualization
   - Voting statistics

## Project Structure

### Routes
1. **Public Routes**
   - `/` - Welcome page
   - `/vote` - Voting interface
   - `/login` - Admin login

2. **Admin Routes** (Requires Authentication)
   - `/admin` - Admin dashboard
   - `/admin/settings` - System settings
   - `/admin/participants` - Participant management
   - `/admin/candidates` - Candidate management
   - `/profile` - Admin profile management

### Controllers
1. **AdminController**
   - Manages admin dashboard
   - Handles system settings
   - Controls voting limits

2. **ParticipantController**
   - Manages voter participants
   - Handles participant import
   - Controls participant verification

3. **CandidateController**
   - Manages election candidates
   - Handles candidate information

4. **VoteController**
   - Processes voting actions
   - Validates votes
   - Records voting activity

5. **ProfileController**
   - Handles admin profile updates
   - Manages account settings

6. **Auth Controllers**
   - AuthenticatedSessionController - Handles login/logout
   - EmailVerificationController - Manages email verification
   - PasswordController - Handles password updates

### Models
1. **Admin**
   - Represents administrator users
   - Handles authentication
   - Manages admin permissions

2. **Participant**
   - Represents voters
   - Tracks voting status
   - Manages voter information

3. **Candidate**
   - Stores candidate information
   - Manages vote counts
   - Handles candidate status

## Security Features
- Admin authentication with email verification
- Rate limiting on authentication attempts
- CSRF protection
- Session security
- One-time voting enforcement
- Secure password handling

## Installation Guide

### Using Laragon
1. Install Laragon from [https://laragon.org/download/](https://laragon.org/download/)
2. Start Laragon and ensure Apache and MySQL services are running
3. Clone the repository to `C:/laragon/www/evoting`:
   ```bash
   git clone [repository-url] C:/laragon/www/evoting
   ```
4. Open Laragon Terminal (Alt+T) and navigate to project:
   ```bash
   cd C:/laragon/www/evoting
   ```
5. Install PHP dependencies:
   ```bash
   composer install
   ```
6. Install JavaScript dependencies:
   ```bash
   npm install
   ```
7. Configure environment:
   - Copy `.env.example` to `.env`
   - Configure database settings:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=evoting
     DB_USERNAME=root
     DB_PASSWORD=
     ```
8. Create database named 'evoting' using HeidiSQL (included in Laragon)
9. Generate application key:
   ```bash
   php artisan key:generate
   ```
10. Run database migrations:
    ```bash
    php artisan migrate
    ```
11. Build frontend assets:
    ```bash
    npm run build
    ```
12. Access the application at [http://evoting.test](http://evoting.test)

### Installing Composer and NPM if Not Detected in Laragon

#### Installing Composer
If Composer is not detected in Laragon:
1. Download Composer installer from [https://getcomposer.org/Composer-Setup.exe](https://getcomposer.org/Composer-Setup.exe)
2. Run the installer
3. During installation:
   - When prompted for PHP path, select PHP from Laragon: `C:/laragon/bin/php/php-[version]/php.exe`
   - Check the "Developer Mode" option
4. After installation:
   - Close and reopen Laragon
   - Press Alt+T to open terminal and verify by running:
     ```bash
     composer --version
     ```

#### Installing Node.js and NPM
If NPM is not detected in Laragon:
1. Download Node.js installer from [https://nodejs.org/](https://nodejs.org/) (LTS version recommended)
2. Run the installer
3. During installation:
   - Accept the default settings
   - Ensure "Add to PATH" is checked
4. After installation:
   - Close and reopen Laragon
   - Press Alt+T to open terminal and verify by running:
     ```bash
     node --version
     npm --version
     ```

After installing both tools, proceed with the regular installation steps above.

### Using XAMPP
1. Install XAMPP from [https://www.apachefriends.org/download.html](https://www.apachefriends.org/download.html)
2. Start XAMPP Control Panel and start Apache and MySQL services
3. Clone the repository to `C:/xampp/htdocs/evoting`:
   ```bash
   git clone [repository-url] C:/xampp/htdocs/evoting
   ```
4. Open Command Prompt and navigate to project:
   ```bash
   cd C:/xampp/htdocs/evoting
   ```
5. Install PHP dependencies:
   ```bash
   composer install
   ```
6. Install JavaScript dependencies:
   ```bash
   npm install
   ```
7. Configure environment:
   - Copy `.env.example` to `.env`
   - Configure database settings:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=evoting
     DB_USERNAME=root
     DB_PASSWORD=
     ```
8. Create database named 'evoting' using phpMyAdmin (http://localhost/phpmyadmin)
9. Generate application key:
   ```bash
   php artisan key:generate
   ```
10. Run database migrations:
    ```bash
    php artisan migrate
    ```
11. Build frontend assets:
    ```bash
    npm run build
    ```
12. Access the application at [http://localhost/evoting/public](http://localhost/evoting/public)

### Virtual Host Configuration (XAMPP)
To access the application using a custom domain like evoting.test:

1. Edit hosts file (`C:/Windows/System32/drivers/etc/hosts`):
   ```
   127.0.0.1 evoting.test
   ```

2. Configure Apache Virtual Host (`C:/xampp/apache/conf/extra/httpd-vhosts.conf`):
   ```apache
   <VirtualHost *:80>
       DocumentRoot "C:/xampp/htdocs/evoting/public"
       ServerName evoting.test
       <Directory "C:/xampp/htdocs/evoting/public">
           Options Indexes FollowSymLinks MultiViews
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

3. Restart Apache from XAMPP Control Panel
4. Access the application at [http://evoting.test](http://evoting.test)

## Troubleshooting Common Installation Issues

#### PHP ZIP Extension Error
If you encounter this error during `composer install`:
```
Your lock file does not contain a compatible set of packages. Please run composer update.
Problem 1
  - phpoffice/phpspreadsheet requires ext-zip * -> it is missing from your system. Install or enable PHP's zip extension.
```

Follow these steps to resolve:

1. Enable PHP ZIP Extension in Laragon:
   - Right-click on Laragon tray icon
   - Select `PHP > Extensions > php_zip`
   - Restart Laragon

2. If the extension is not available:
   - Right-click on Laragon tray icon
   - Go to `PHP > Version` and note your PHP version
   - Open Laragon Terminal (Alt+T)
   - Run these commands:
     ```bash
     cd C:\laragon\bin\php\php-[your-version]
     copy php.ini-development php.ini
     ```
   - Open `php.ini` and find `;extension=zip`
   - Remove the semicolon to make it `extension=zip`
   - Save the file and restart Laragon

3. After enabling the extension:
   ```bash
   composer update
   ```

4. If you still encounter issues:
   - Delete the following files:
     ```bash
     rm composer.lock
     rm -rf vendor/
     ```
   - Then run:
     ```bash
     composer install --ignore-platform-reqs
     ```

Note: If using `--ignore-platform-reqs` flag, make sure to properly enable the ZIP extension later as it's required for proper functionality of the spreadsheet features.

#### Database Connection Error
If you encounter this error during migration:
```
Illuminate\Database\QueryException 
SQLSTATE[HY000] [2002] No connection could be made because the target machine actively refused it
```

This error occurs when Laravel cannot connect to MySQL. Follow these steps to resolve:

1. Check MySQL Service:
   - Open Laragon
   - Verify that MySQL is running (should be green)
   - If not running:
     - Click "Start" in Laragon
     - If MySQL fails to start, right-click Laragon > MySQL > Service > Restart

2. Verify Database Configuration:
   - Open `.env` file and check these settings:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=evoting
     DB_USERNAME=root
     DB_PASSWORD=
     ```
   - For Laragon default installation, password should be empty

3. Check Database Existence:
   - Open HeidiSQL (Right-click Laragon > MySQL > HeidiSQL)
   - Create database if not exists:
     ```sql
     CREATE DATABASE IF NOT EXISTS evoting;
     ```

4. Clear Laravel Cache:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

5. If still having issues:
   - Try using `localhost` instead of `127.0.0.1`:
     ```
     DB_HOST=localhost
     ```
   - Clear config again:
     ```bash
     php artisan config:clear
     ```
   - Restart Laragon completely

6. Check Port Conflicts:
   - Open Command Prompt as Administrator
   - Check if port 3306 is in use:
     ```bash
     netstat -ano | findstr :3306
     ```
   - If another service is using port 3306:
     - Right-click Laragon > MySQL > Configuration > my.ini
     - Change the port number (e.g., to 3307)
     - Update `.env` file with new port
     - Restart Laragon

After fixing the connection, run migrations again:
```bash
php artisan migrate
```

#### Table Already Exists Error
If you encounter this error during migration:
```
Illuminate\Database\QueryException
SQLSTATE[42S01]: Base table or view already exists: 1050 Table 'admins' already exists
```

This error occurs when trying to create tables that already exist in the database. Here are several ways to resolve this:

1. Reset All Migrations:
   ```bash
   php artisan migrate:reset
   php artisan migrate
   ```

2. If reset doesn't work, drop all tables and migrate:
   ```bash
   php artisan db:wipe
   php artisan migrate
   ```

3. If you want to keep existing data:
   - Refresh migrations (this will clear all data):
     ```bash
     php artisan migrate:refresh
     ```
   - Or refresh with seeding if you have seeders:
     ```bash
     php artisan migrate:refresh --seed
     ```

4. If above commands fail:
   - Open HeidiSQL from Laragon
   - Connect to your database
   - Execute these SQL commands:
     ```sql
     DROP DATABASE evoting;
     CREATE DATABASE evoting;
     ```
   - Then run migration:
     ```bash
     php artisan migrate
     ```

5. If you need to force the migration:
   ```bash
   php artisan migrate --force
   ```

Note: Be careful with these commands in a production environment as they will delete existing data. Always backup your database before running these commands.

## Usage Guide
1. **Admin Access**
   - Login at `/login`
   - Complete email verification
   - Configure system settings
   - Manage participants and candidates

2. **Managing Participants**
   - Add participants individually or via import
   - Edit participant information
   - Monitor voting status
   - Remove participants if needed

3. **Managing Candidates**
   - Add election candidates
   - Update candidate information
   - Monitor vote counts
   - Remove candidates if necessary

4. **Monitoring Votes**
   - View real-time voting results
   - Track participation statistics
   - Export voting data if needed

## Contributing
1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License
This project is licensed under the MIT License.
