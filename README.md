# E-Voting System Documentation

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
