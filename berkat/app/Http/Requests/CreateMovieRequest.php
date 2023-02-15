<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateMovieRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'overview' => 'nullable|string|max:255',
            'poster_path' => 'nullable|string|max:255',
            'release_date' => 'nullable|date',
            'popularity' => 'nullable|numeric',
            'vote_average' => 'nullable|numeric',
            'category_id' => 'nullable|exists:categories,id',
        ];
    }
}
