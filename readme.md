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
$ git clone https://github.com/r-mikegester/Hospital-Management-System
$ create a folder in htdocs name it Logistics
$ cd Logistics
$ npm install
$ then run your xampp and preview 
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

### Step 3: Install Front-End Dependencies

Install the required front-end packages using npm:

```bash
$ npm install
```

Restart your web server after adding the configuration.

---

### Step 4: Run the Project

Access the project via your browser at:

```
http://localhost/Logistics/login.php

---

## Additional Configuration

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
