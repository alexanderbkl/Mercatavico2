<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
            'email' => ['required','email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'password'=>['nullable','confirmed', Rules\Password::defaults()],
            'cp'=>['required','string','max:7'],
            'ciudad'=>['required','string','max:250'],
            'address'=>['required','string','max:250'],
        ];
    }
}
