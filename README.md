# Blog API

A RESTful API for a blogging platform built with Laravel, featuring token-based authentication, article and comment management, and full authorization control.

## Features

- **Authentication** – Token-based auth via Laravel Sanctum (register, login, logout)
- **Articles** – Create, read, update, and delete articles
- **Comments** – Comment on articles, with full CRUD support
- **Authorization** – Users can only edit or delete their own articles and comments, enforced via Laravel Policies
- **Validation** – Request validation handled through dedicated Form Request classes
- **Consistent API responses** – Structured JSON output via API Resources
- **Automated tests** – Feature tests covering authentication, ownership rules, and validation for both articles and comments

## Tech Stack

- **Backend:** PHP, Laravel 12
- **Authentication:** Laravel Sanctum
- **Database:** MySQL (SQLite in-memory for automated tests)
- **Testing:** PHPUnit

## API Endpoints

### Auth
| Method | Endpoint       | Description         | Auth required |
|--------|----------------|----------------------|----------------|
| POST   | `/api/register` | Register a new user | No |
| POST   | `/api/login`     | Log in and receive a token | No |
| POST   | `/api/logout`    | Revoke the current token | Yes |

### Articles
| Method | Endpoint                | Description             | Auth required |
|--------|--------------------------|--------------------------|----------------|
| GET    | `/api/articles`          | List all articles        | No |
| GET    | `/api/articles/{id}`     | Show a single article with its comments | No |
| POST   | `/api/articles`          | Create an article        | Yes |
| PUT    | `/api/articles/{id}`     | Update your own article  | Yes (owner only) |
| DELETE | `/api/articles/{id}`     | Delete your own article  | Yes (owner only) |

### Comments
| Method | Endpoint                          | Description             | Auth required |
|--------|------------------------------------|--------------------------|----------------|
| GET    | `/api/articles/{id}/comments`     | List comments on an article | No |
| GET    | `/api/comments/{id}`              | Show a single comment    | No |
| POST   | `/api/articles/{id}/comments`     | Add a comment to an article | Yes |
| PUT    | `/api/comments/{id}`              | Update your own comment  | Yes (owner only) |
| DELETE | `/api/comments/{id}`              | Delete your own comment  | Yes (owner only) |

## Installation

1. Clone the repository
   ```bash
   git clone https://github.com/shabani17/blog-api.git
   cd blog-api
   ```

2. Install dependencies
   ```bash
   composer install
   ```

3. Set up environment file
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Configure your database credentials in `.env`

5. Run migrations
   ```bash
   php artisan migrate
   ```

6. Serve the application
   ```bash
   php artisan serve
   ```

## Running Tests

The project includes a full suite of feature tests covering authentication, ownership-based authorization, and validation rules. Tests run against an in-memory SQLite database, so no extra setup is needed.

```bash
php artisan test
```

## Authorization Rules

- Anyone can view articles and comments.
- Only authenticated users can create articles and comments.
- Only the original author can update or delete their own article or comment — enforced via `ArticlePolicy` and `CommentPolicy`.

## Project Structure

- `app/Http/Controllers` – Request handling (Auth, Article, Comment)
- `app/Http/Requests` – Form validation and authorization checks
- `app/Http/Resources` – JSON response formatting
- `app/Policies` – Ownership-based authorization rules
- `app/Models` – Eloquent models (User, Article, Comment)
- `tests/Feature` – End-to-end API tests

## License

This project is open for educational and portfolio purposes.
