<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Use Policies for specific post ownership/checks
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => 'required|string|max:1000',
            'image' => 'nullable|image|max:10240', // 10MB Max
            'video' => 'nullable|mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4|max:51200', // 50MB Max
        ];
    }
}
