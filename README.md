# UserCandy Framework

UserCandy is a simple PHP/MySQL website framework with optional Node.js support. It provides a basic login system, routing, and default pages that can be customized without modifying the core.

## Requirements
- PHP 7.4+
- MySQL
- Node.js (optional for running `server.js`)

## Installation
1. Rename `app/default-config.php` to `app/config.php` and update your database credentials and OAuth settings.
2. Create a MySQL database and run the following to create the users table:
   ```sql
   CREATE TABLE users (
     id INT AUTO_INCREMENT PRIMARY KEY,
     email VARCHAR(255) UNIQUE NOT NULL,
     password VARCHAR(255) NOT NULL
   );
   ```
3. Serve the `public` directory as your web root so requests are handled by `public/index.php`.
4. (Optional) Run `npm install` and `node server.js` to start the Node server.

## Customization
Add or modify pages in the `app/pages` directory. Files in this folder override files in `pages` with the same name, allowing upgrades without overwriting custom code.

## OAuth Login
Enable Google, Discord, or Windows login by setting the appropriate flags and credentials in `app/config.php`.
You may also enable Google reCAPTCHA on the login and registration forms by
setting `enable_recaptcha` to `true` and providing your site and secret keys.

## Default Pages
- `/` – Home
- `/login` – Login page
- `/register` – Registration page
- `/dashboard` – Protected user dashboard
- `/profile/{id}` – Public user profile by ID

Enjoy building with UserCandy!
