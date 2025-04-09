# Employee Management System

A web application that allows employees to manage their personal information and administrators to manage employee records.

## Overview

This application is a prototype for an employee management system with the following features:
- Employee login and profile management
- Administrator access for managing all employee records
- Secure authentication system
- RESTful API for data interaction

## Features

* **User Authentication**
  * Secure login for employees and administrators
  * Session management
  * Password protection

* **Employee Records Management**
  * View and edit employee information
  * Profile fields include:
    * First Name
    * Last Name
    * Date of Birth
    * Phone Number
    * Office Number
    * Employee Category (full time, part time, intern, contractor)
    * Email
  * Role-based permissions (admins can edit all records, employees can only edit their own)

## Technical Stack

* **Backend**
  * PHP 5.6+
  * MySQL database
  * PDO for database interaction
  * RESTful API architecture

* **Frontend**
  * HTML5
  * CSS3
  * JavaScript (vanilla)
  * Responsive design

## Installation and Setup

### Prerequisites

* PHP 5.6 or higher
* MySQL server
* Web server (Apache/Nginx) or PHP's built-in server

### Database Setup

1. Create a MySQL database:
   ```sql
   CREATE DATABASE es_code_challenge;
   ```

2. Import the database schema:
   ```bash
   mysql -u username -p es_code_challenge < es_code_challenge_db.sql
   ```

   Alternatively, use phpMyAdmin to import the SQL file.

### Configuration

1. Update the database connection settings in `config.php`:
   ```php
   $host = 'localhost';
   $dbname = 'es_code_challenge';
   $username = 'your_mysql_username';
   $password = 'your_mysql_password';
   ```

### Running the Application

#### Option 1: Using PHP's Built-in Server

```bash
cd /path/to/es_code_challenge
php -S localhost:8000
```

Access the application at `http://localhost:8000`

#### Option 2: Using Apache/XAMPP/MAMP/WAMP

1. Place the project files in your web server's document root:
  * XAMPP: `/xampp/htdocs/es_code_challenge`
  * MAMP: `/Applications/MAMP/htdocs/es_code_challenge`
  * WAMP: `C:/wamp/www/es_code_challenge`

2. Access the application through your web server:
  * XAMPP/WAMP: `http://localhost/es_code_challenge`
  * MAMP: `http://localhost:8888/es_code_challenge` (default port is 8888)

## Demo Credentials

The application comes with two pre-configured users:

### Admin User
- Email: admin@example.com
- Password: admin123

### Regular Employee
- Email: john@example.com
- Password: password123

## API Endpoints

The application provides a RESTful API with the following endpoints:

* **POST /api.php?endpoint=login** - Authenticate user
* **GET /api.php?endpoint=logout** - End user session
* **GET /api.php?endpoint=employee** - Get employee information
* **POST /api.php?endpoint=employee** - Update employee information
* **GET /api.php?endpoint=searchEmployee** - Search for employees
* **GET /api.php?endpoint=allEmployees** - Get list of all employees (admin only)

## Folder Structure