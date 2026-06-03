# Project Memory: ASAKAY

## Current Status
The project is in the early stages of infrastructure setup, focusing on the IAM (Identity and Access Management) foundation and preparing for transportation-specific modules.

## Recent Key Decisions
- **Project Domain:** Shifted from a generic IAM foundation to a public transport route-mapping platform (Jeepneys, Buses, Vans).
- **RBAC Convention:** Permissions now use a mandatory `can_` prefix (e.g., `can_delete_users`).
- **Authorization Pattern:** Controllers now use `authorizeResource` linked to Laravel Policies for all CRUD operations.
- **System Initialization:** A data migration `insert_initial_system_users_and_roles` is used to bootstrap core roles and admin users idempotently.

## Core Domain Entities (Planned/Active)
- **Users & Roles:** RBAC active.
- **Vehicles:** Active. Management of transport types (Jeepney, Bus, Van) with ownership-based authorization.
- **Routes:** (Planned) Geometry and mapping logic.
- **Stops:** (Planned) Stop point coordinates and metadata.

## Active Conventions
- Use `can_` prefix for all new permissions.
- Extend `App\Http\Controllers\Controller` which now includes `AuthorizesRequests` and extends `BaseController` for middleware support.
- All administrative emails should use the `@email.com` domain for default system accounts.
