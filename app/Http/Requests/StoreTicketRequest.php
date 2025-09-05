<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255|min:10',
            'description' => 'required|string|min:20|max:2000',
            'priority' => 'nullable|in:low,medium,high,urgent',
            'category_id' => 'nullable|exists:ticket_categories,id',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240', // 10MB
        ];
    }

    public function messages()
    {
        return [
            'title.min' => 'El título debe tener al menos 10 caracteres',
            'description.min' => 'La descripción debe tener al menos 20 caracteres',
            'attachments.*.max' => 'Cada archivo no puede superar los 10MB',
        ];
    }
}
