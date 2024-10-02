
# Laravel Blog API

This is a simple Laravel blog API that includes user registration, authentication, post creation, and job queueing. Users receive a welcome email upon registration, and there is also a command to manually dispatch the welcome email job.

## Requirements

Before you begin, ensure you have met the following requirements:

- **PHP**: >= 8.0
- **Composer**: Latest version
- **Database**: MySQL or any other supported database

## Setup Instructions

Follow these steps to set up the project locally:

### 1. Clone the Repository

```bash
git clone https://github.com/ashshofar/juicebox
cd juicebox
```

### 2. Install PHP Dependencies

Run the following command to install the Laravel dependencies:

```bash
composer install
```

### 3. Set Up the Environment

1. Copy the `.env.example` file to `.env`:

   ```bash
   cp .env.example .env
   ```

2. Open the `.env` file and set up the necessary environment variables, including database credentials and application URL:

   ```bash
   APP_NAME=Laravel
   APP_ENV=local
   APP_KEY=
   APP_DEBUG=true
   APP_URL=http://localhost

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=laravel_blog
   DB_USERNAME=root
   DB_PASSWORD=

   QUEUE_CONNECTION=database
   ```

3. Generate the application key:

   ```bash
   php artisan key:generate
   ```

### 4. Set Up the Database

1. Create a new database (for example, `laravel_blog`).

   For MySQL:
   ```sql
   CREATE DATABASE laravel_blog;
   ```

2. Run the database migrations and seed the database:

   ```bash
   php artisan migrate --seed
   ```

### 5. Run the Development Server

To run the application on a local development server, use the following command:

```bash
php artisan serve
```

The application will be accessible at [http://localhost:8000](http://localhost:8000).

---

## Running the Queue Worker

To process queued jobs, such as sending welcome emails, you need to run a queue worker.

### Step 1: Set Up the Queue

Ensure that the queue is configured in your `.env` file by setting the `QUEUE_CONNECTION` variable to `database` (or your preferred connection).

```bash
QUEUE_CONNECTION=database
```

### Step 2: Run the Queue Worker

Start the queue worker to process jobs:

```bash
php artisan queue:work
```

This will keep the worker running and processing any queued jobs, such as sending the welcome email after a user registers.

### Step 3: Run the Queue Table Migration (Optional)

If you haven’t yet set up the queue database table, you can generate and run the migration:

```bash
php artisan queue:table
php artisan migrate
```

This will create a `jobs` table in your database to store queued jobs.

---

## Manually Dispatching the Welcome Email Job

You can manually dispatch the welcome email job for a user using an Artisan command.

### Step 1: Create the Artisan Command

The project includes an Artisan command to send a welcome email to a user by their ID. The command's signature is:

```bash
php artisan send:welcome-email {user_id}
```

### Step 2: Run the Command

To send a welcome email to a specific user, run the following command, replacing `{user_id}` with the ID of the user:

```bash
php artisan send:welcome-email 1
```

This will manually dispatch the welcome email job for the user with the given ID.

---

## Testing the Application

You can run the test suite using PHPUnit. To do this, use the following command:

```bash
php artisan test
```

Make sure that your `.env.testing` file is set up to use a testing database, such as an SQLite in-memory database:

```bash
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

---

## API Documentation

The project uses Swagger/OpenAPI for API documentation.

1. Install L5-Swagger (if not installed):

   ```bash
   composer require darkaonline/l5-swagger
   ```

2. Generate the Swagger documentation:

   ```bash
   php artisan l5-swagger:generate
   ```

You can access the API documentation at [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation).

---

## Contact

For any inquiries or issues, feel free to contact [shofar.ikhsan@gmail.com](mailto:shofar.ikhsan@gmail.com).

---
