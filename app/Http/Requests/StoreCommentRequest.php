<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
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
            'body' => 'required|string|max:1000',
            'commentable_id' => 'required|integer',
            'commentable_type' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id',
            'user_id' => 'required|exists:users,id', // Added user_id validation since it's used in the controller
        ];
    }
}
