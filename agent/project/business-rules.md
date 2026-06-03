# Business Rules

## Identity & Access
1. **Unique Identity:** Users must have a unique email address.
2. **Secure Passwords:** All passwords must be hashed using industry-standard algorithms (Bcrypt/Argon2).
3. **JWT Expiry:** Access tokens have a defined TTL (Time To Live).

## RBAC (Role-Based Access Control)
1. **Many-to-Many Roles:** A user can be assigned multiple roles.
2. **Many-to-Many Permissions:** A role can have multiple permissions assigned to it.
3. **Permission Granularity:** Permissions are atomic (e.g., `view_users`, `create_roles`).
4. **Hierarchical Access:** (To be verified if roles have hierarchies).

## User Management
1. **Creation:** New users can be created with initial credentials.
2. **Password Updates:** Users can update their passwords; the system must re-hash the new password.
3. **Deletion:** Users can be removed from the system.

## Administrative Rules
1. **Dashboard Access:** Only users with administrative privileges can access dashboard metrics.
2. **Role Assignment:** Only authorized users can assign or revoke roles from other users.
