Implement a complete Vehicle Management module.

Requirements:

1. Create a Vehicle model and migration.

Table: vehicles

Columns:
- id
- name (string, required)
- code (string, unique, nullable)
- type (string, required, enum: jeepney, bus, van)
- description (text, nullable)
- status (enum: active, inactive)
- created_by (foreign key to users table)
- updated_by (foreign key to users table)
- timestamps
- soft deletes

2. Create VehicleController with RESTful endpoints.

Endpoints:
- GET /api/vehicles
- POST /api/vehicles
- GET /api/vehicles/{id}
- PUT /api/vehicles/{id}
- DELETE /api/vehicles/{id}

Requirements:
- Pagination
- Search by vehicle name and code
- Filter by vehicle type
- Filter by status
- Sort by created_at and name

3. Validation

Store:
- name required
- type required
- type must be jeepney, bus, or van

Update:
- same validation rules

4. Resource Classes

Create VehicleResource and VehicleCollection.

5. Permissions

Create permissions:
- can_view_vehicles
- can_create_vehicle
- can_update_vehicle
- can_delete_vehicle

Protect endpoints:

GET:
- vehicle.view

POST:
- vehicle.create

PUT:
- vehicle.update

DELETE:
- vehicle.delete

6. Seeder

Create:
- VehiclePermissionSeeder

Seed all permissions.

7. Policies

Create VehiclePolicy.

Users may:
- view vehicles if they have permission
- create vehicles if they have permission
- update vehicles if they have permission
- delete vehicles if they have permission

8. API Response Format

Success:

{
  "success": true,
  "message": "Vehicle created successfully.",
  "data": {}
}

Validation Errors:

{
  "success": false,
  "message": "Validation failed.",
  "errors": {}
}

9. Architecture

Use:
- Form Requests
- API Resources
- Service Layer
- Policy Authorization

Generate complete code including:
- Migration
- Model
- Controller
- Form Requests
- Resources
- Policy
- Seeder
- Routes
- Service Class