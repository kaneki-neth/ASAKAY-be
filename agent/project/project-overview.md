# Project Overview: ASAKAY

## System Purpose
**ASAKAY** is a web application that helps users discover how to travel to unfamiliar places using public transportation routes. It is a route-mapping platform that helps users find how to reach unfamiliar destinations using fixed-route public transportation such as jeepneys, buses, and vans. 

The backend provides the core API infrastructure for authentication, transport management, and route calculation.

## Business Domain
- **Primary Domain:** Public Transportation & Navigation.
- **Secondary Domain:** Identity and Access Management (IAM) for system administrators and users.

## Major Modules
- **Route Management:** Storage and retrieval of route geometry and geographic data.
- **Stop Point Management:** Definition of loading/unloading areas for public transport.
- **Vehicle Management:** Management of different public transportation types (Jeepneys, Buses, Vans).
- **Search Logic:** Core engine for finding navigation routes between points.
- **Authentication & RBAC:** Secure access control for users and system administrators.

## User Roles
- **Super Admin:** Full system access.
- **Admin:** Restricted management access (typically view-only or limited scope).
- **Standard User:** General platform access for navigation and profile management.

## Core Workflows
1. **Route Search:** User inputs origin and destination → API processes route geometry and stop points → Returns optimal transport paths.
2. **Transportation Management:** Admin updates route paths or stop locations → System reflects changes in real-time navigation.
3. **Identity & Access:** Secure login via JWT → Permission-based access to administrative and user-specific features.
