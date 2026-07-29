<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateItemRequest extends FormRequest
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
        $itemId = $this->route('item') ? $this->route('item')->id : null;

        return [
            'item_code' => ['sometimes', 'required', 'string', 'max:50', Rule::unique('items', 'item_code')->ignore($itemId)],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'category' => ['sometimes', 'required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'required', Rule::in(['Tersedia', 'Dipinjam', 'Perbaikan'])],
            'photo_path' => ['nullable', 'string', 'max:255'],
        ];
    }
}
