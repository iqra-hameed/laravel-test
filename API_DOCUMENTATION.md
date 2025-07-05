# Laravel Authentication System API Documentation

## Overview
This is a comprehensive authentication system built with Laravel 12 and JWT authentication. It includes user registration, login, profile management, and role-based access control.

## Base URL
```
http://127.0.0.1:8000/api
```

## Authentication
All protected endpoints require a Bearer token in the Authorization header:
```
Authorization: Bearer <your-jwt-token>
```

## Default Test Accounts
- **Admin**: admin@example.com / password123
- **User**: user@example.com / password123

---

## Authentication Endpoints

### Register User
**POST** `/auth/register`

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "phone": "+1234567890",
    "bio": "Software developer",
    "avatar": "https://example.com/avatar.jpg"
}
```

**Response (201):**
```json
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "user",
            "phone": "+1234567890",
            "bio": "Software developer",
            "avatar": "https://example.com/avatar.jpg",
            "created_at": "2025-07-05T13:50:00.000000Z",
            "updated_at": "2025-07-05T13:50:00.000000Z"
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
        "token_type": "bearer",
        "expires_in": 3600
    }
}
```

### Login User
**POST** `/auth/login`

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "user"
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
        "token_type": "bearer",
        "expires_in": 3600
    }
}
```

### Get Current User
**GET** `/auth/me`

**Headers:** `Authorization: Bearer <token>`

**Response (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user",
        "phone": "+1234567890",
        "bio": "Software developer",
        "avatar": "https://example.com/avatar.jpg"
    }
}
```

### Logout User
**POST** `/auth/logout`

**Headers:** `Authorization: Bearer <token>`

**Response (200):**
```json
{
    "success": true,
    "message": "Successfully logged out"
}
```

### Refresh Token
**POST** `/auth/refresh`

**Headers:** `Authorization: Bearer <token>`

**Response (200):**
```json
{
    "success": true,
    "data": {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
        "token_type": "bearer",
        "expires_in": 3600
    }
}
```

---

## Profile Management Endpoints

### Get Current User Profile
**GET** `/profile`

**Headers:** `Authorization: Bearer <token>`

**Response (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user",
        "phone": "+1234567890",
        "bio": "Software developer",
        "avatar": "https://example.com/avatar.jpg"
    }
}
```

### Update Current User Profile
**PUT** `/profile`

**Headers:** `Authorization: Bearer <token>`

**Request Body:**
```json
{
    "name": "John Smith",
    "email": "johnsmith@example.com",
    "phone": "+1234567891",
    "bio": "Senior software developer",
    "avatar": "https://example.com/new-avatar.jpg",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Profile updated successfully",
    "data": {
        "id": 1,
        "name": "John Smith",
        "email": "johnsmith@example.com",
        "role": "user",
        "phone": "+1234567891",
        "bio": "Senior software developer",
        "avatar": "https://example.com/new-avatar.jpg"
    }
}
```

---

## User Management Endpoints (Admin Only)

### List All Users
**GET** `/users`

**Headers:** `Authorization: Bearer <admin-token>`

**Query Parameters:**
- `per_page` (optional): Number of users per page (default: 15)

**Response (200):**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "name": "John Doe",
                "email": "john@example.com",
                "role": "user",
                "phone": "+1234567890",
                "bio": "Software developer",
                "avatar": "https://example.com/avatar.jpg",
                "created_at": "2025-07-05T13:50:00.000000Z",
                "updated_at": "2025-07-05T13:50:00.000000Z"
            }
        ],
        "per_page": 15,
        "total": 1
    }
}
```

### Create User
**POST** `/users`

**Headers:** `Authorization: Bearer <admin-token>`

**Request Body:**
```json
{
    "name": "Jane Doe",
    "email": "jane@example.com",
    "password": "password123",
    "role": "user",
    "phone": "+1234567890",
    "bio": "Designer",
    "avatar": "https://example.com/avatar.jpg"
}
```

**Response (201):**
```json
{
    "success": true,
    "message": "User created successfully",
    "data": {
        "id": 2,
        "name": "Jane Doe",
        "email": "jane@example.com",
        "role": "user",
        "phone": "+1234567890",
        "bio": "Designer",
        "avatar": "https://example.com/avatar.jpg"
    }
}
```

### Get User by ID
**GET** `/users/{id}`

**Headers:** `Authorization: Bearer <token>`

**Note:** Users can only view their own profile unless they're admin

**Response (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user",
        "phone": "+1234567890",
        "bio": "Software developer",
        "avatar": "https://example.com/avatar.jpg"
    }
}
```

### Update User
**PUT** `/users/{id}`

**Headers:** `Authorization: Bearer <token>`

**Note:** Users can only update their own profile unless they're admin

**Request Body:**
```json
{
    "name": "John Smith",
    "email": "johnsmith@example.com",
    "phone": "+1234567891",
    "bio": "Senior software developer",
    "avatar": "https://example.com/new-avatar.jpg",
    "role": "admin",
    "password": "newpassword123"
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "User updated successfully",
    "data": {
        "id": 1,
        "name": "John Smith",
        "email": "johnsmith@example.com",
        "role": "admin",
        "phone": "+1234567891",
        "bio": "Senior software developer",
        "avatar": "https://example.com/new-avatar.jpg"
    }
}
```

### Delete User
**DELETE** `/users/{id}`

**Headers:** `Authorization: Bearer <admin-token>`

**Note:** Admin only. Cannot delete own account.

**Response (200):**
```json
{
    "success": true,
    "message": "User deleted successfully"
}
```

---

## Dashboard Endpoint

### Get Dashboard Data
**GET** `/dashboard`

**Headers:** `Authorization: Bearer <token>`

**Response (200):**
```json
{
    "success": true,
    "message": "Welcome to your dashboard!",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "user"
        },
        "dashboard_stats": {
            "total_users": 5,
            "user_role": "user",
            "last_login": "2025-07-05T13:50:00.000000Z"
        }
    }
}
```

---

## Security Features

### Rate Limiting
- **Login attempts**: 10 attempts per IP per 5 minutes
- **Registration attempts**: 5 attempts per IP per 5 minutes

### Password Requirements
- Minimum 8 characters
- Must be confirmed during registration and profile updates

### Role-Based Access Control
- **Admin**: Full access to all endpoints
- **User**: Limited access to own profile and dashboard

### JWT Token Security
- Tokens expire after 1 hour (configurable)
- Tokens can be refreshed
- Tokens are invalidated on logout

---

## Error Responses

### Validation Error (422)
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password field is required."]
    }
}
```

### Authentication Error (401)
```json
{
    "success": false,
    "message": "Invalid credentials"
}
```

### Authorization Error (403)
```json
{
    "success": false,
    "message": "Unauthorized. Admin access required."
}
```

### Not Found Error (404)
```json
{
    "success": false,
    "message": "User not found"
}
```

### Rate Limit Error (429)
```json
{
    "success": false,
    "message": "Too many login attempts. Please try again later."
}
```

### Server Error (500)
```json
{
    "success": false,
    "message": "Internal server error",
    "error": "Detailed error message"
}
```

---

## Testing with cURL

### Register a new user:
```bash
curl -X POST http://127.0.0.1:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Login:
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password123"
  }'
```

### Get dashboard (replace TOKEN with actual token):
```bash
curl -X GET http://127.0.0.1:8000/api/dashboard \
  -H "Authorization: Bearer TOKEN"
```

---

## Frontend Dashboard

The system includes a beautiful, responsive frontend dashboard accessible at:
- **URL**: http://127.0.0.1:8000/
- **Features**:
  - User registration and login
  - Profile management
  - Role-based UI elements
  - Admin user management (for admin users)
  - Real-time error and success notifications
  - Mobile-responsive design

The frontend is built with vanilla JavaScript and Tailwind CSS for optimal performance and modern UI/UX.
