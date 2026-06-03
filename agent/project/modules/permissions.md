# Module: Permissions

## Purpose
Provides granular control over system actions by defining specific permissions that can be attached to roles.

## Features
- List all available permissions.
- Create new atomic permissions.
- Update permission details.
- Delete permissions.

## Business Rules
- Permission names must be unique.
- Permissions are typically mapped to specific controller actions or UI features.

## APIs
- `GET /api/permissions`: List permissions.
- `POST /api/permissions`: Store permission.
- `PUT/PATCH /api/permissions/{permission}`: Update permission.
- `DELETE /api/permissions/{permission}`: Destroy permission.

## Implementation Details
- **Controller:** `App\Http\Controllers\PermissionController`
- **Model:** `App\Models\Permission`
