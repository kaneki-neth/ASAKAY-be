# Module: Roles

## Purpose
Defines and manages organizational roles that aggregate permissions and are assigned to users.

## Features
- List all roles with their associated permissions.
- View detailed information for a specific role.
- Create new roles with unique names.
- Update role names and descriptions.
- Delete roles.

## Business Rules
- Role names must be unique in the `roles` table.
- Roles can have a description to clarify their purpose.

## APIs
- `GET /api/roles`: List roles.
- `GET /api/roles/{role}`: Show role.
- `POST /api/roles`: Store role.
- `PUT/PATCH /api/roles/{role}`: Update role.
- `DELETE /api/roles/{role}`: Destroy role.

## Implementation Details
- **Controller:** `App\Http\Controllers\RoleController`
- **Model:** `App\Models\Role`
- **RBAC Orchestration:** Handled via `RoleAssignmentController` and `RolePermissionController`.
