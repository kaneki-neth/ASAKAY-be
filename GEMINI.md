# Gemini Instructions: Asakay Backend

Welcome to the **Asakay Backend** project. To ensure consistency, safety, and project continuity, you MUST follow the instructions below.

## 1. Mandatory Session Initialization
At the start of **every** new conversation or session, your first priority is to load and internalize the contents of the `agent/` directory. This directory contains your persona, operating framework, and deep project knowledge.

### Required Reading Order:
1.  **`agent/agent.md`**: Understand your identity and mission.
2.  **`agent/operating-system.md`**: Internalize your thinking, planning, and verification rules.
3.  **`agent/project/project-overview.md`**: Get a high-level view of the system.
4.  **`agent/project/architecture.md`**: Understand the technical foundation.
5.  **`agent/memory.md`**: Check for any user-specific preferences or active working notes.

## 2. Project Overview
**Asakay Backend** is a specialized RESTful API built with **Laravel 12** and **PHP 8.2+**. Its primary purpose is to provide a secure and scalable Identity and Access Management (IAM) foundation for enterprise applications.

### Core Technology Stack:
- **Framework:** Laravel 12 (latest stable).
- **Authentication:** Stateless JWT via `tymon/jwt-auth`.
- **Authorization:** Granular RBAC (Roles/Permissions).
- **Standards:** PSR-12, standardized JSON responses via `ApiResponse` trait.

## 3. Operational Mandate
- **Plan First:** Always enter Plan Mode for tasks involving 3+ steps or architectural changes.
- **Respect Patterns:** Follow the established patterns documented in `agent/project/patterns/`.
- **Update Knowledge:** Every significant change or lesson learned MUST be recorded in the relevant file within the `agent/` directory (e.g., `changelog.md`, `lessons-learned.md`, `modules/*.md`).

*This file acts as the foundational directive for all AI agents. Do not ignore or override these instructions.*
