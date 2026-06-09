<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:routes,code'],
            'vehicle_type_id' => ['required', 'exists:vehicle_types,id'],
            'color' => ['nullable', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'status' => ['nullable', 'in:active,inactive'],
            'polyline' => ['nullable', 'array'],
            'polyline.*.lat' => ['required_with:polyline', 'numeric'],
            'polyline.*.lng' => ['required_with:polyline', 'numeric'],
            'stops' => ['nullable', 'array'],
            'stops.*' => ['exists:stops,id'],
        ];
    }
}
