# Module: Users

## Purpose
Manages the core identity of system users, including their credentials and role assignments.

## Features
- List all users with their assigned roles.
- View individual user details.
- Create new users with hashed passwords.
- Update user information (name, email, password).
- Delete users from the system.

## Business Rules
- Email must be unique in the `users` table.
- Passwords must be at least 6 characters long (as seen in `UserController`).
- Roles are automatically reloaded upon user creation/update to ensure UI consistency.

## APIs
- `GET /api/users`: List users.
- `GET /api/users/{user}`: Show user.
- `POST /api/users`: Store user.
- `PUT/PATCH /api/users/{user}`: Update user.
- `DELETE /api/users/{user}`: Destroy user.

## Implementation Details
- **Controller:** `App\Http\Controllers\UserController`
- **Model:** `App\Models\User`
- **Trait Usage:** Uses `ApiResponse` for all responses.
- **Hashing:** Uses `Illuminate\Support\Facades\Hash`.
