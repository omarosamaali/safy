<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrackRequest extends FormRequest
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
            'user_level' => 'required|in:bg,md,pro',
            'training_type' => 'required|in:pullpushleg,prosplit,arnoldsplit,home',
            'program_id' => 'required|exists:programs,id', // Assuming programs table exists
        ];
    }
}
