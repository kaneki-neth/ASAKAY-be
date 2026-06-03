# Module: Authentication

## Purpose
Handles user identity verification and secure session management using JWT.

## Features
- User login with email and password.
- Secure logout (token invalidation).
- Retrieve currently authenticated user profile (`/me`).
- Token refresh (implied by JWT standard, to be verified in config).

## Business Rules
- Requires valid credentials for token issuance.
- Tokens must be included in the `Authorization: Bearer <token>` header for protected routes.

## APIs
- `POST /api/login`: Authenticate and receive token.
- `POST /api/logout`: Invalidate current token (requires auth).
- `GET /api/me`: Get authenticated user info (requires auth).

## Implementation Details
- **Controller:** `App\Http\Controllers\Api\AuthController`
- **Library:** `tymon/jwt-auth`
- **Config:** `config/jwt.php`
- **Model Integration:** `User` model implements `JWTSubject`.
