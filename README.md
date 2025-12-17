# Blog API

A simple RESTful API for a blog platform that allows users to register, log in, manage articles, and post comments.

## Features

-   **User Authentication**: Register, login, and logout using token-based authentication (Sanctum).
-   **Articles**:
    -   List all articles
    -   View a specific article with its comments
    -   Create, update, and delete articles (only by the author)
-   **Comments**:
    -   List comments for an article
    -   Add, edit, and delete comments (authenticated users)
-   **Authorization**: Only the owner of an article or comment can edit or delete it.

## Technologies

-   Laravel 10
-   MySQL
-   Laravel Sanctum for authentication

## API Endpoints

### Authentication

-   `POST /api/register` – Register a new user
-   `POST /api/login` – Login and receive token
-   `POST /api/logout` – Logout (requires auth token)

### Articles

-   `GET /api/articles` – List all articles
-   `GET /api/articles/{article}` – Get a specific article with comments
-   `POST /api/articles` – Create a new article (requires auth)
-   `PUT /api/articles/{article}` – Update an article (requires auth, owner only)
-   `DELETE /api/articles/{article}` – Delete an article (requires auth, owner only)

### Comments

-   `GET /api/articles/{article}/comments` – List comments of an article
-   `POST /api/articles/{article}/comments` – Add a comment (requires auth)
-   `PUT /api/comments/{comment}` – Update a comment (requires auth, owner only)
-   `DELETE /api/comments/{comment}` – Delete a comment (requires auth, owner only)

## Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/shabani17/blog-api.git
    ```
2. Install dependencies:
    ```bash
    composer install
    ```
3. Copy `.env.example` to `.env` and configure your database and other settings:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
4. Run migrations:
    ```bash
    php artisan migrate
    ```
5. Start the local server:
    ```bash
    php artisan serve
    ```

## Running Tests

This project includes automated feature and unit tests to ensure the API works correctly. All tests are located in `tests/Feature` and `tests/Unit`.

Run all tests using:

```bash
php artisan test
```
