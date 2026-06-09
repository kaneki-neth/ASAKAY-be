<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('route')->id;

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'code' => ['sometimes', 'required', 'string', 'max:50', 'unique:routes,code,' . $id],
            'vehicle_type_id' => ['sometimes', 'required', 'exists:vehicle_types,id'],
            'color' => ['sometimes', 'required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'status' => ['sometimes', 'required', 'in:active,inactive'],
            'polyline' => ['nullable', 'array'],
            'stops' => ['nullable', 'array'],
        ];
    }
}
