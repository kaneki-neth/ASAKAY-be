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
**ASAKAY** is a route-mapping platform designed to help users navigate unfamiliar destinations using fixed-route public transportation, including jeepneys, buses, and vans. 

This backend provides a RESTful API built with **Laravel 12** and **PHP 8.2+**. It handles:
- **Identity & Access Management:** Secure authentication and granular RBAC.
- **Vehicle Management:** Managing transport types (jeepneys, buses, etc.).
- **Route Logic:** Storage for route geometry, stop points, and search logic.
- **Standards:** PSR-12, standardized JSON responses via `ApiResponse` trait.

## 3. Operational Mandate
- **Plan First:** Always enter Plan Mode for tasks involving 3+ steps or architectural changes.
- **Respect Patterns:** Follow the established patterns documented in `agent/project/patterns/`.
- **Update Knowledge:** Every significant change or lesson learned MUST be recorded in the relevant file within the `agent/` directory (e.g., `changelog.md`, `lessons-learned.md`, `modules/*.md`).

*This file acts as the foundational directive for all AI agents. Do not ignore or override these instructions.*
