# Technical Debt

## Observed Inconsistencies
- **Inline Validation:** Some controllers use `$request->validate()` or `Validator::make()` inline instead of dedicated `FormRequest` classes.
- **Manual Exception Handling:** Repeating `try-catch` blocks in every controller method instead of utilizing Laravel's Global Exception Handler for consistent error formatting.
- **Inconsistent Password Hashing:** Some controllers hash passwords manually before saving (`Hash::make()`), while Laravel models could handle this via casts or observers.

## Refactoring Opportunities
- **FormRequests:** Move validation logic to `app/Http/Requests`.
- **Global Exception Handler:** Centralize API error response formatting.
- **Service Layer:** Abstract business logic from controllers into dedicated Service classes (e.g., `UserService`, `RoleService`).
- **Resource Classes:** Use Laravel API Resources for transforming models into JSON to decouple the DB schema from the API response.

## Security Considerations
- **Password Policies:** Implement stronger password complexity requirements.
- **Rate Limiting:** Ensure API endpoints are rate-limited to prevent brute-force attacks.
