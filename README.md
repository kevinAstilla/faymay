# Laravel News Aggregator API

This is a Laravel-based backend application that aggregates news articles from multiple external APIs and allows users to retrieve articles based on their preferences, including categories, authors, and sources. The application also includes authentication using Laravel Sanctum.

---

## Features
- **Article Management**:
  - Fetch articles from external APIs and store them in the database.
  - Support for categories, authors, and sources with many-to-many relationships.
- **User Preferences**:
  - Store user-specific preferences for categories, authors, and sources.
  - Retrieve articles based on user preferences.
- **Authentication**:
  - Token-based authentication using Laravel Sanctum.
- **Filtering**:
  - Filter articles by category, author, or source.

---

## Installation

### 1. Prerequisites
- PHP >= 8.0
- Composer
- Laravel Sail (for Docker-based development)
- Docker and Docker Compose

### 2. Clone the Repository
```bash
git clone <repository-url>
cd <project-folder>
```

### 3. Install Dependencies
```bash
composer install
```

### 4. Configure the Environment
Copy the `.env.example` file to `.env` and configure your database and other settings:
make sure to provide api keys named NEWS_API_API_KEY THE_GUARDIAN_API_KEY, and NEW_YORK_TIMES_API_KEY
```bash
cp .env.example .env
```

### 5. Start the Docker Containers
Using Laravel Sail:
```bash
./vendor/bin/sail up -d
```

### 6. Run Migrations and Seeders
```bash
./vendor/bin/sail artisan migrate --seed
```

---

## Usage

### News Aggregator command
```bash
./vendor/bin/sail artisan app:fetch-articles
```
### Authentication
1. Register a new user:
   ```http
   POST /api/register
   Body: {
       "name": "John Doe",
       "email": "johndoe@example.com",
       "password": "password",
       "password_confirmation": "password"
   }
   ```

2. Login and obtain a token:
   ```http
   POST /api/login
   Body: {
       "email": "johndoe@example.com",
       "password": "password"
   }
   ```

3. Use the token for authenticated requests:
   Add the `Authorization` header:
   ```
   Authorization: Bearer <your-token>
   ```

---

### Endpoints

#### Articles
- **Fetch Articles by Category**:
  ```http
  GET /api/articles?category=Technology
  ```

- **Fetch Articles by User Preferences**:
  ```http
  GET /api/articles/user-preferences
  Requires Authentication
  ```

#### Preferences
- **Update User Preferences**:
  ```http
  POST /api/preferences
  Body: {
      "categories": ["Technology", "Health"],
      "authors": ["John Doe", "Jane Smith"],
      "sources": ["BBC", "CNN"]
  }
  ```

---

## Development



### Scheduler
The Laravel scheduler is set up to fetch and update articles every minute. For development, use:
```bash
./vendor/bin/sail artisan schedule:work
```

---

## Technical Details

### Database Structure
- **Articles**:
  - `id`, `title`, `external_link`, `published_date`, `data`, `created_at`, `updated_at`
- **Categories**:
  - `id`, `name`, `created_at`, `updated_at`
- **Pivot Table** (`article_category`):
  - `article_id`, `category_id`
- **Preferences**:
  - `id`, `user_id`, `key`, `value`

### Relationships
- `Article` ↔ `Category`: Many-to-Many
- `User` ↔ `Preference`: One-to-Many

## License
This project is open-source and available under the [MIT License](LICENSE).

