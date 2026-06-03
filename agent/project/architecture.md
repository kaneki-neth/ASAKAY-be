# System Architecture

## Backend Architecture
- **Framework:** Laravel 12.x (PHP 8.2+).
- **API Style:** RESTful JSON API.
- **Authentication:** Stateless JWT Authentication (`tymon/jwt-auth`).
- **Authorization:** Role-Based Access Control (RBAC) implemented via custom models and middleware.

## Core Components
- **Models:** Eloquent ORM models (`User`, `Role`, `Permission`).
- **Controllers:** Organized into standard Laravel controllers and a nested `Api/` namespace for auth.
- **Routing:** Split into modular route files in `routes/api/` (users, roles, permissions, admin).
- **Middleware:** `CheckRole` and standard `auth:api` for securing endpoints.
- **Trait-based Responses:** `ApiResponse` trait ensures consistent JSON output (`status`, `message`, `data`).

## Data Patterns
- **Database:** Relational database (likely MySQL/PostgreSQL based on Laravel defaults).
- **Relationships:**
    - `User` belongsToMany `Role` (`role_user` pivot).
    - `Role` belongsToMany `Permission` (`permission_role` pivot).
- **Soft Deletes:** (To be verified, but standard in such systems).

## Authentication Flow
1. `POST /api/login` → `AuthController@login` → Returns JWT.
2. `GET /api/me` (Protected) → Returns authenticated user details.
3. `POST /api/logout` (Protected) → Invalidates token.

## Reporting & Dashboard
- `AdminDashboardController` provides aggregated metrics (Total Users, Roles, Permissions).
