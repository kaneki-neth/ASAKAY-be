# Backend Patterns

## Standard API Response
All controllers must use the `ApiResponse` trait to ensure a consistent JSON structure.

### Success Response
```json
{
  "status": "success",
  "message": "Resource retrieved successfully",
  "data": { ... }
}
```

### Error Response
```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": { ... }
}
```

## Controller Pattern
Controllers generally follow this structure:
1. **Try-Catch Block:** Wrap logic in `try-catch` to handle exceptions gracefully.
2. **Validation:** Use `$request->validate()` or `Validator::make()`.
3. **Trait Methods:** Return `$this->success()` or `$this->error()`.

### Example
```php
public function store(Request $request) {
    try {
        $data = $request->validate([...]);
        $item = Model::create($data);
        return $this->success($item, 'Created successfully', 201);
    } catch (\Exception $e) {
        return $this->error('Error', 500, $e->getMessage());
    }
}
```

## Routing Pattern
- Routes are split into modular files under `routes/api/`.
- Included in the main `routes/api.php` via `include __DIR__ . '/api/...'`.
- Grouped by resource and often protected by `auth:api` middleware.

## Soft Deletes
- (Observation: To be confirmed if implemented project-wide, but recommended for IAM systems).
