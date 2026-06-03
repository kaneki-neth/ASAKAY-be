# Project Overview: Asakay Backend

## System Purpose
The **Asakay Backend** is a specialized RESTful API built to provide a robust foundation for an enterprise-level management system. It focuses on secure authentication, fine-grained access control, and scalable user management.

## Business Domain
- **Core Domain:** User Identity and Access Management (IAM).
- **Secondary Domain:** Administrative Dashboard and System Orchestration.

## Major Modules
- **Authentication:** Secure login/logout using JWT (JSON Web Tokens).
- **User Management:** CRUD operations for system users.
- **Role Management:** Definition and management of organizational roles.
- **Permission Management:** Granular control over system actions.
- **RBAC Orchestration:** Mapping users to roles and roles to permissions.

## User Roles
- **Admin:** Full system access.
- **Standard User:** Restricted access based on assigned roles.

## Core Workflows
1. **Authentication Flow:** User submits credentials → API validates and returns JWT → User includes JWT in subsequent requests.
2. **Access Control Flow:** Middleware intercepts request → Checks user roles and permissions → Allows or denies access based on defined policies.
3. **Administration Flow:** Admin manages users, roles, and permissions through specialized endpoints.
