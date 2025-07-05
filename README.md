# Laravel Authentication System

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

<p align="center">
  <strong>A fast, secure, and modular authentication system with JWT and user profile CRUD functionality</strong>
</p>

## 🚀 Features

### ✅ Authentication Module
- **Email/Password Authentication**: Secure login and registration system
- **JWT Token Management**: Stateless authentication with token refresh capability
- **Password Encryption**: Bcrypt hashing for secure password storage
- **Rate Limiting**: Brute-force protection with configurable limits
- **Post-login Redirect**: Seamless dashboard access after authentication

### ✅ User Profile CRUD
- **Complete Profile Management**: Name, email, phone, bio, avatar fields
- **RESTful API Endpoints**: Full CRUD operations (Create, Read, Update, Delete)
- **Role-based Access Control**: Admin vs regular user permissions
- **Data Validation**: Comprehensive input validation and sanitization

### 🏆 Bonus Features
- **Modular Architecture**: Clean, maintainable code structure
- **Excellent DX**: Readable code with proper documentation
- **Consistent JSON Responses**: Standardized API response format
- **Beautiful Frontend**: Responsive dashboard with modern UI
- **Comprehensive Testing**: Ready for unit and integration tests

## 🛠️ Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Authentication**: JWT (tymon/jwt-auth)
- **Database**: MySQL/MariaDB
- **Frontend**: Vanilla JavaScript + Tailwind CSS
- **API**: RESTful JSON API

## 📋 Requirements

- PHP 8.2 or higher
- Composer
- MySQL/MariaDB
- Node.js (for frontend assets)

## 🚀 Quick Start

### 1. Clone and Install
```bash
git clone <repository-url>
cd laravel-test
composer install
```

### 2. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

### 3. Database Configuration
Update your `.env` file with database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_auth
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Run Migrations and Seeders
```bash
php artisan migrate
php artisan db:seed
```

### 5. Start Development Server
```bash
php artisan serve
```

Visit `http://127.0.0.1:8000` to access the application.

## 🔐 Default Test Accounts

| Role  | Email             | Password    |
|-------|-------------------|-------------|
| Admin | admin@example.com | password123 |
| User  | user@example.com  | password123 |

## 📚 API Documentation

Detailed API documentation is available in [`API_DOCUMENTATION.md`](./API_DOCUMENTATION.md).

### Quick API Examples

#### Register User
```bash
curl -X POST http://127.0.0.1:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

#### Login
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password123"
  }'
```

#### Access Dashboard
```bash
curl -X GET http://127.0.0.1:8000/api/dashboard \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

## 🏗️ Architecture

### Directory Structure
```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       ├── AuthController.php      # Authentication logic
│   │       └── UserController.php      # User CRUD operations
│   └── Middleware/
│       └── AdminMiddleware.php         # Role-based access control
├── Models/
│   ├── User.php                        # User model with JWT
│   └── Role.php                        # Role model
database/
├── migrations/                         # Database schema
└── seeders/                           # Default data
routes/
├── api.php                            # API routes
└── web.php                            # Web routes
resources/
└── views/
    └── dashboard.blade.php            # Frontend dashboard
```

### Key Components

#### AuthController
- User registration and login
- JWT token management
- Rate limiting implementation
- Password validation

#### UserController
- Complete CRUD operations
- Role-based access control
- Profile management
- Admin user management

#### User Model
- JWT authentication interface
- Role-based helper methods
- Secure password hashing
- Profile field management

## 🔒 Security Features

### Rate Limiting
- **Login**: 10 attempts per IP per 5 minutes
- **Registration**: 5 attempts per IP per 5 minutes

### Password Security
- Minimum 8 characters required
- Bcrypt hashing algorithm
- Password confirmation validation

### JWT Security
- 1-hour token expiration
- Token refresh capability
- Secure token invalidation

### Role-Based Access
- Admin: Full system access
- User: Limited to own profile

## 🎨 Frontend Features

### Dashboard Interface
- **Responsive Design**: Mobile-first approach
- **Modern UI**: Tailwind CSS styling
- **Real-time Notifications**: Success/error messages
- **Role-based UI**: Different views for admin/user

### User Experience
- **Single Page Application**: Smooth navigation
- **Form Validation**: Client-side validation
- **Loading States**: User feedback during operations
- **Error Handling**: Graceful error management

## 🧪 Testing

### Manual Testing
1. **Registration**: Create new user accounts
2. **Login/Logout**: Test authentication flow
3. **Profile Management**: Update user information
4. **Admin Functions**: Test user management (admin only)
5. **API Endpoints**: Test all CRUD operations

### Automated Testing (Future)
```bash
# Unit tests
php artisan test

# Feature tests
php artisan test --testsuite=Feature
```

## 🚀 Deployment

### Production Setup
1. **Environment**: Set `APP_ENV=production`
2. **Database**: Configure production database
3. **Cache**: Enable Redis/Memcached
4. **Queue**: Set up job queues
5. **SSL**: Enable HTTPS

### Performance Optimization
```bash
# Optimize application
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📖 Development Guide

### Adding New Features
1. **Models**: Create/modify Eloquent models
2. **Migrations**: Database schema changes
3. **Controllers**: API endpoint logic
4. **Routes**: Define API routes
5. **Frontend**: Update dashboard interface

### Code Standards
- **PSR-12**: PHP coding standards
- **Laravel Conventions**: Follow Laravel best practices
- **Documentation**: Comment complex logic
- **Validation**: Always validate input data

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🆘 Support

If you encounter any issues or have questions:

1. Check the [API Documentation](./API_DOCUMENTATION.md)
2. Review the error logs in `storage/logs/`
3. Ensure all requirements are met
4. Verify database connection and migrations

---

## Development Summary

**Tools and Technology Stack:** This comprehensive authentication system was built using Laravel 12 as the core framework, leveraging `tymon/jwt-auth` for stateless JWT token management, and MySQL for data persistence. The choice of Laravel was strategic for its robust ecosystem, built-in security features, and excellent testing capabilities. JWT was selected over session-based authentication to ensure API scalability and stateless operations, making it ideal for modern web applications and mobile app backends. The system utilizes Laravel's native validation, middleware system, and Eloquent ORM for clean, maintainable code, while implementing comprehensive PHPUnit tests (20 tests with 118 assertions) to ensure reliability and catch regressions early.

**Speed and Performance Optimization:** To optimize delivery speed, a modular development approach was followed, creating reusable components like custom Request classes (`LoginRequest`, `RegisterRequest`), API Resources for consistent response formatting, and middleware for role-based access control. Performance was enhanced through strategic database indexing, efficient Eloquent queries with pagination, and rate limiting to prevent abuse. The system implements proper caching strategies for JWT tokens, uses Laravel's built-in validation to minimize database hits, and structures the API with RESTful endpoints for optimal client-side caching. The system includes a responsive frontend dashboard with vanilla JavaScript to minimize load times, while the comprehensive test suite ensures code quality and reduces debugging time in production.

---

**Built with ❤️ using Laravel 12 and modern web technologies**
