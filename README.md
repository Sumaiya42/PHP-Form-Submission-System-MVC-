# Pure PHP MVC Application

This project implements a simple web application using **pure PHP** (no frameworks) following the **Model-View-Controller (MVC)** architectural pattern, **Object-Oriented Programming (OOP)** principles, **PSR-4 autoloading**, and **PSR-1 naming conventions**.

The application includes:
1.  **Authentication Module**: User sign-up and login with session management.
2.  **Data Submission Module**: A validated form submission system using AJAX.
3.  **Reporting Module**: A simple page to view and filter submissions.
4.  **Containerization**: The entire stack (PHP-FPM, Nginx, MySQL) is containerized using Docker.

## Project Architecture

The project is structured according to the MVC pattern:

| Directory | Role | Description |
| :--- | :--- | :--- |
| `public/` | **Front Controller** | Contains `index.php`, the single entry point for all requests. |
| `src/Controller/` | **Controller** | Handles user input, interacts with Models, and selects Views. |
| `src/Model/` | **Model** | Manages application data, logic, and database interactions (using PDO). |
| `src/View/` | **View** | Contains presentation logic (HTML/PHP templates). |
| `src/Core/` | **Core** | Contains core components like `Router`, `BaseController`, `Model`, and `Validator`. |
| `config/` | **Configuration** | Contains configuration files (e.g., `.env`). |
| `docker/` | **Docker Config** | Contains Nginx configuration for the container. |
| `vendor/` | **Dependencies** | Composer-managed dependencies (e.g., `vlucas/phpdotenv`). |

## Requirements

*   Docker and Docker Compose (or Docker Desktop)

## How to Run the Project

The project is fully containerized for easy setup.

1.  **Clone the repository (or download the files):**
    \`\`\`bash
    git clone
    cd pure_php_mvc
    \`\`\`
    **Alternative: Run using Docker Hub Pre-Built Image**
     \`\`\`bash
     docker pull sumaiya21/php-form-submission-system-mvc
    \`\`\`

2.  **Build and start the containers:**
    This command will build the PHP image, start the MySQL database, and start the Nginx web server. The `schema.sql` file will automatically initialize the database tables.
    \`\`\`bash
    docker compose up --build -d
    \`\`\`

3.  **Access the application:**
    The application will be available at `http://localhost:8080`.


4.  **Stop the containers:**
    \`\`\`bash
    docker compose down
    \`\`\`

## Database Schema (`schema.sql`)

The database is initialized with two tables: `users` and `submissions`.

### `users` Table (Authentication)

| Column | Type | Description |
| :--- | :--- | :--- |
| `id` | `bigint(20) UNSIGNED` | Primary Key, Auto Increment |
| `name` | `varchar(255)` | User's name |
| `email` | `varchar(255)` | Unique email address |
| `password` | `varchar(255)` | Hashed password |
| `created_at` | `datetime` | Timestamp of creation |

### `submissions` Table (Data Submission)

| Column | Type | Description | Constraints |
| :--- | :--- | :--- | :--- |
| `id` | `bigint(20) UNSIGNED` | Primary Key, Auto Increment | |
| `amount` | `int(10)` | Submission amount | **Required** |
| `buyer` | `varchar(255)` | Buyer's name | **Required**, Text/Spaces/Numbers, Max 20 chars |
| `receipt_id` | `varchar(20)` | Unique receipt ID | **Required**, Text only |
| `items` | `varchar(255)` | Comma-separated list of items | **Required** |
| `buyer_email` | `varchar(50)` | Buyer's email | **Required**, Valid email format |
| `buyer_ip` | `varchar(20)` | Buyer's IP address | Auto-filled from backend |
| `note` | `text` | Submission note | Max 30 words |
| `city` | `varchar(20)` | City | **Required**, Text/Spaces only |
| `phone` | `varchar(20)` | Phone number | **Required**, Numbers only (880 prepended on frontend) |
| `hash_key` | `varchar(255)` | SHA-512 hash of `receipt_id` + `APP_SALT` | Auto-filled from backend |
| `entry_at` | `date` | Submission date | Auto-filled from backend (local timezone) |
| `entry_by` | `int(10)` | User ID of the submitter | **Required** |
| `created_at` | `datetime` | Timestamp of creation | |

## Validation Logic

Both frontend (JavaScript) and backend (PHP `Validator` class) validation are implemented.

| Field | Frontend Validation (JS) | Backend Validation (PHP) |
| :--- | :--- | :--- |
| `amount` | Only numbers, > 0 | Only numbers, > 0 |
| `buyer` | Text, spaces, numbers, max 20 chars | Text, spaces, numbers, max 20 chars |
| `receipt_id` | Text and numbers only | Text and numbers only |
| `items` | Multiple items interface, at least one item | Required (non-empty string) |
| `buyer_email` | Valid email format | Valid email format |
| `note` | Max 30 words | Max 30 words |
| `city` | Text and spaces only | Text and spaces only |
| `phone` | Numbers only, prepends `880` on blur | Numbers only |
| `entry_by` | Positive number | Positive number |

---
## Links

- **GitHub Repository:** [PHP Form Submission System (MVC)](https://github.com/Sumaiya42/PHP-Form-Submission-System-MVC-)
- **Docker Hub Image:** [sumaiya21/php-form-submission-system-mvc](https://hub.docker.com/r/sumaiya21/php-form-submission-system-mvc)
