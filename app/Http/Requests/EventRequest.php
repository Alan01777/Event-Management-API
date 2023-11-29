<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $method = $this->route()->getActionMethod();
        $rules = [];

        switch ($method) {
            case 'store':
                $rules = [
                    'name'          => 'required|string|max:255',
                    'description'   => 'nullable|string',
                    'start_time'    => 'required|date',
                    'end_time'      => 'required|date|after:start_date'
                ];
                break;

            case 'update':
                $rules = [
                    'name'          => 'sometimes|string|max:255',
                    'description'   => 'nullable|string',
                    'start_time'    => 'sometimes|date',
                    'end_time'      => 'sometimes|date|after:start_date'
                ];
                break;
        }
        return $rules;
    }
}
