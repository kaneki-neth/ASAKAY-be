# Module: Vehicles

## Overview
The Vehicles module manages the different types of public transportation supported by the ASAKAY platform (Jeepneys, Buses, and Vans). It allows administrators and authorized users to define vehicle metadata and status.

## Entity Schema
- `id`: Unique identifier.
- `name`: Display name of the vehicle.
- `code`: Unique alphanumeric code (e.g., Plate Number or Route Code).
- `type`: Category (`jeepney`, `bus`, `van`).
- `description`: Optional text details.
- `status`: Operation status (`active`, `inactive`).
- `created_by`: User who created the record.
- `updated_by`: User who last updated the record.

## Permissions
- `can_view_vehicles`: Allows viewing list and single vehicle details.
- `can_create_vehicles`: Allows creating new vehicle records.
- `can_edit_vehicles`: Allows editing *any* vehicle record.
- `can_delete_vehicles`: Allows deleting *any* vehicle record.

## Authorization Rules
- **View:** Requires `can_view_vehicles`.
- **Create:** Requires `can_create_vehicles`.
- **Update/Delete:**
  - Users with `can_edit_vehicles`/`can_delete_vehicles` can modify any record.
  - Standard users can modify records they created (`created_by === current_user_id`).

## API Endpoints
- `GET /api/vehicles`: List vehicles (Supports search, type/status filter, sorting).
- `POST /api/vehicles`: Create vehicle.
- `GET /api/vehicles/{vehicle}`: View single vehicle.
- `PUT /api/vehicles/{vehicle}`: Update vehicle.
- `DELETE /api/vehicles/{vehicle}`: Soft delete vehicle.
