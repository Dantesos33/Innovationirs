<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {return true;}

    public function rules(): array
    {
        return [
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'email'            => 'required|email|max:255',
            'phone'            => 'required|string|max:30',
            'company'          => 'nullable|string|max:200',
            'make'             => 'nullable|string|max:150',
            'model'            => 'nullable|string|max:150',
            'serial_number'    => 'nullable|string|max:150',
            'part_number'      => 'nullable|string|max:150',
            'part_description' => 'required|string|min:5',
            'quantity'         => 'nullable|integer|min:1|max:9999',
            'notes'            => 'nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'part_description.required' => 'Please describe the part you need.',
            'part_description.min'      => 'Please provide more detail about the part.',
        ];
    }
}
