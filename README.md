# ACLC Iriga Tabulation System - Landing Page

This repository contains the official **landing page** for the ACLC Tabulation System developed by ACLC College of Iriga. The page is designed to serve as a front-facing informational and entry point for event participants, judges, and organizers.

> ⚠️ This is **not** the tabulation system itself. It is a standalone landing page to introduce and link to the actual scoring platform.

## 🌐 Purpose

The landing page serves as:
- An introduction to the tabulation system
- A central access point for users (e.g., judges, admins, organizers)
- A tool to guide users to appropriate sections

## 🚀 Features

- Clean and responsive design
- Secure handling of access tokens or form requests (if integrated)

## 🛠️ Installation

To view or customize the landing page locally:

1. Download and install
   [XAMPP](https://www.apachefriends.org/download.html) and [Composer](https://getcomposer.org/),
   if you haven't already.

2. Start Apache and MySQL through XAMPP if not already running.

3. Clone or download this repository to your XAMPP **htdocs** folder.
   The final path should be `path_to/xampp/htdocs/aclc-tabulation-landing`.

4. Copy [**`app/config/database.example.php`**](app/config/database.example.php)
   to **`app/config/database.php`**, then modify the database connection settings in the new file.

5. Inside [phpMyAdmin](http://localhost/phpmyadmin),
   create a MySQL database named `aclc-tabulation-landing` and import [aclc-tabulation-landing.sql](aclc-tabulation.sql) into it.

6. Open the terminal and navigate to the project directory **aclc-tabulation-landing**.

7. Execute the following commands to install the required dependencies:
   ```sh
   composer install
   ```

8. Open your web browser and access <http://localhost/aclc-tabulation-landing/> to view the application.

## 📌 Requirements
- PHP 7.4+ (optional if no backend features used)
- Web browser

## 📝 License
This project is licensed under the **MIT License**.

## 👥 Acknowledgments
Developed by ACLC College Iriga students and educators for institutional events and competitions.