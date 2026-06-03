<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by Policy
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $merge = [];
        if ($this->has('type')) $merge['type'] = strtolower($this->type);
        if ($this->has('status')) $merge['status'] = strtolower($this->status);

        if (!empty($merge)) {
            $this->merge($merge);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $vehicle = $this->route('vehicle');
        $id = is_object($vehicle) ? $vehicle->id : $vehicle;

        return [
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|nullable|string|unique:vehicles,code,' . $id . '|max:50',
            'type' => 'sometimes|string|in:jeepney,bus,van',
            'description' => 'sometimes|nullable|string',
            'status' => 'sometimes|string|in:active,inactive,maintenance',
        ];
    }
}
