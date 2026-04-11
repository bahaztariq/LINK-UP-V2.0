<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ToggleReactionRequest extends FormRequest
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
        return [
            'reactable_id' => 'required|integer',
            'reactable_type' => 'required|string',
            'type' => 'nullable|string|in:like', // default to like
            'user_id' => 'required|exists:users,id', // Added user_id validation since it's used in notification logic
        ];
    }
}
