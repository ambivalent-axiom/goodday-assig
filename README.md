# Blog and News Management System

A Laravel-based content management system with a public landing page for news and blog posts, plus an administrative interface for content and user management.

## Features

### Public Area
- Landing page with news and blog posts
- Toggle between news and blog content
- Responsive grid layout
- Pagination
- Author information display
- No authentication required for viewing

### Admin Dashboard
- Secure authentication system
- User permission management
- Content management for news and blog posts
- User management interface
- Role-based access control

## Tech Stack

- **Frontend:** Vue.js 3, Inertia.js
- **Backend:** Laravel 11
- **CSS Framework:** Tailwind CSS
- **Authentication:** Laravel Jetstream
- **Database:** MySQL/PostgreSQL

## Prerequisites

- PHP >= 8.1
- Node.js >= 18.x
- Composer
- MySQL/PostgreSQL

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/project-name.git
cd project-name
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install Node.js dependencies:
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
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Run database migrations:
```bash
php artisan migrate
```

8. Create a default admin user:
```bash
php artisan db:seed --class=AdminUserSeeder
```

9. Build assets:
```bash
npm run dev
```

10. Start the development server:
```bash
php artisan serve
```

## Project Structure

```
├── app
│   ├── Http
│   │   ├── Controllers
│   │   │   ├── Post
│   │   │   │   ├── PostLandingController.php
│   │   │   │   └── AdminPostController.php
│   │   ├── Middleware
│   ├── Models
│   │   ├── BlogPost.php
│   │   └── User.php
├── resources
│   ├── js
│   │   ├── Pages
│   │   │   ├── Landing.vue
│   │   │   └── Admin
│   │   └── Layouts
└── routes
    ├── web.php
    └── admin.php
```

## Routes

### Public Routes
- `/` - Landing page (default: news)
- `/blog` - Blog posts
- `/news` - News articles

### Admin Routes
- `/admin/login` - Admin login
- `/admin/dashboard` - Admin dashboard
- `/admin/users` - User management
- `/admin/posts` - Post management
- `/admin/permissions` - Permission management

## User Roles and Permissions

The system includes the following roles:
- **Super Admin:** Full system access
- **Admin:** Content and user management
- **Editor:** Content management only
- **Author:** Can create and edit own content

## Development

### Commands

```bash
# Run development server
php artisan serve

# Watch for frontend changes
npm run dev

# Build for production
npm run build

# Run tests
php artisan test
```

### Adding New Features

1. Create new migration:
```bash
php artisan make:migration create_new_feature_table
```

2. Create new model:
```bash
php artisan make:model NewFeature
```

3. Create new controller:
```bash
php artisan make:controller NewFeatureController
```

## Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --filter UserTest
```

## Deployment

1. Set up production environment
2. Configure web server (Apache/Nginx)
3. Set up SSL certificate
4. Configure environment variables
5. Run production build
6. Run database migrations

## Security

- All admin routes are protected with authentication
- CSRF protection enabled
- XSS protection
- SQL injection prevention
- Rate limiting on authentication attempts
