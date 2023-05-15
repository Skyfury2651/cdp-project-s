<?php

namespace App\Http\Requests;

use App\Models\Lake;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ImportRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:xlsx'],
            'fields' => ['required'],
            'fields.*.name' => ['required', Rule::in(app(Lake::class)->getFillable())],
            'fields.*.header' => ['required', 'string']
        ];
    }
}
