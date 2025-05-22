# Asset Management Dashboard

This is a web-based **Asset Management Dashboard** designed to manage assets, tasks, and employees effectively. The project uses PHP, MySQL, and a variety of front-end tools including Bootstrap, Tailwind CSS, and Toastify.js.

---

## Features

* Overview dashboard showing total assets, tasks, and employees.
* Individual management sections for assets, tasks, and employees.
* Toast notifications for user feedback.
* Responsive design using Tailwind CSS and Bootstrap.

---

## Prerequisites

Before setting up this project, ensure you have the following installed:

1. **PHP** (Version 7.4 or later)
2. **MySQL/MariaDB**
3. **Composer** (for dependency management)
4. **Apache/Nginx** (for serving the PHP application)
5. **Node.js & npm** (for managing front-end dependencies)
6. **Git** (for cloning the repository)

---

## Setup Instructions

### Step 1: Clone the Repository

```bash
$ git clone <repository-url>
$ cd Logistics
```

---

### Step 2: Configure Environment

1. Create a `.env` file in the `config/` directory with the following content:

   ```env
   DB_HOST=127.0.0.1
   DB_NAME=logistics_db
   DB_USER=root
   DB_PASSWORD=
   DB_PORT=3306
   ```

2. Replace the placeholder values (`DB_USER`, `DB_PASSWORD`, etc.) with your database configuration.

---

### Step 3: Set Up the Database

1. Import the database schema:

   ```bash
   $ mysql -u root -p logistics_db < /path/to/schema.sql
   ```

2. Replace `/path/to/schema.sql` with the path to your SQL schema file.

---

### Step 4: Install PHP Dependencies

Run the following command to install PHP dependencies using **Composer**:

```bash
$ composer install
```

---

### Step 5: Install Front-End Dependencies

Install the required front-end packages using npm:

```bash
$ npm install
```

---

### Step 6: Configure Apache/Nginx

#### Apache Configuration

Add the following configuration to your Apache virtual host file:

```apache
<VirtualHost *:80>
    ServerName logistics.local
    DocumentRoot /path/to/Logistics/public

    <Directory /path/to/Logistics/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/logistics_error.log
    CustomLog ${APACHE_LOG_DIR}/logistics_access.log combined
</VirtualHost>
```

#### Nginx Configuration

```nginx
server {
    listen 80;
    server_name logistics.local;

    root /path/to/Logistics/public;
    index index.php index.html index.htm;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

Restart your web server after adding the configuration.

---

### Step 7: Run the Project

Access the project via your browser at:

```
http://logistics.local
```

---

## Additional Configuration

### Toastify.js Integration

Toastify.js is used for toast notifications. Ensure you include its CSS and JS files in your project:

```html
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
```

You can customize the toast notifications in `main.js` or inline JavaScript.

### Tailwind CSS Integration

Tailwind CSS is used for styling. Ensure the CDN link is included in the `<head>` section:

```html
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
```

---

## Troubleshooting

### Common Issues

* **Database Connection Errors:**

  * Check your `.env` file for the correct database credentials.

* **Permission Denied Errors:**

  * Ensure the `public/` directory has the correct permissions.

* **CSS/JS Not Loading:**

  * Verify that your CDN links are correct and accessible.

---

## License

This project is licensed under the MIT License. See the `LICENSE` file for more information.

---

## Contributors

* **Project Lead:** Your Name
* **Developers:** Your Team
* **Designer:** Your Designer

Feel free to contribute to the project by creating issues or submitting pull requests.

---

## Feedback

If you encounter issues or have suggestions for improvement, please create an issue in this repository or contact the project maintainers.
