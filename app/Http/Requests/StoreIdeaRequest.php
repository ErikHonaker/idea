<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\IdeaStatus;

class StoreIdeaRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string','max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable',Rule::enum(IdeaStatus::class)],
            'links' =>  ['nullable', 'array'],
            'links.*' => ['url', 'max:255'],
        ];
    }
}
