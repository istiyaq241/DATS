# DIU Fleet Management System 🚌

A DBMS project for managing university bus schedules dynamically.

## Features
- **Admin Console:** Secure interface to Add/Delete buses (PIN Protected).
- **Student Board:** Public dashboard that auto-sorts buses (To Campus / From Campus).
- **Database:** MySQL backend with efficient filtering.

## How to Install
1. Create a WordPress site (or use XAMPP).
2. Import `dats_db.sql` into your PHPMyAdmin.
3. Install the "WPCode" plugin.
4. Create two snippets and paste the code from `admin-console.php` and `student-noticeboard.php`.
5. Use shortcodes `[wpcode id="39"]` and `[wpcode id="16"]` on your pages.