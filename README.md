# Cannabis Membership Portal

A comprehensive cannabis growing service portal that allows members to track their plants, manage subscriptions, and interact with administrators.

## Features

- User authentication with role-based access control (Admin/Member)
- Plant management with growth tracking
- Image upload and gallery functionality
- QR code generation for member identification
- Growth logs with detailed metrics
- Responsive design using Tailwind CSS

## Requirements

- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MySQL 5.7+ or PostgreSQL 9.6+
- GD or Imagick PHP extension

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd cannabis-app
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install NPM dependencies:
```bash
npm install
```

4. Create environment file:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Configure your database in `.env` file:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cannabis_app
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Run migrations:
```bash
php artisan migrate
```

8. Create storage link:
```bash
php artisan storage:link
```

9. Create an admin user:
```bash
php artisan app:create-admin
```

10. Build assets:
```bash
npm run build
```

11. Start the development server:
```bash
php artisan serve
```

## Development Setup

To set up test data for development:

```bash
php artisan db:seed
```

This will create:
- 1 admin user (admin@example.com / password)
- 5 test members (member1@example.com through member5@example.com / password)
- 3 plants per member
- 5 growth logs per plant

## Usage

### Admin Features

1. Log in as admin
2. Access the dashboard to view all plants and members
3. Create new plants for members
4. Manage plant status and details
5. View and manage growth logs
6. Handle member subscriptions

### Member Features

1. Log in as member
2. View assigned plants
3. Track plant growth through logs
4. Upload plant images
5. View subscription status

## Security

- All routes are protected by authentication
- Role-based access control for admin/member features
- Secure password hashing
- CSRF protection
- XSS prevention
- Input validation and sanitization

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.
