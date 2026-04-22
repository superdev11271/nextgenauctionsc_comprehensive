<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class SellerProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $newPasswordRule        = 'sometimes';
        $confirmPasswordRule    = 'sometimes';
        if ($this->request->get('new_password') != null && $this->request->get('confirm_password') != null) {
            $newPasswordRule       = ['min:8'];
            $newPasswordRule       = ['min:8'];
        }
        return [
            'name'              => ['required', 'max:191'],
            'new_password'      => $newPasswordRule,
            'confirm_password'  => $confirmPasswordRule,
            'phone' => ['required', 'phone:AU,IN',Rule::unique('users', 'phone')->ignore($this->id)],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'         => translate('Name is required'),
            'new_password.min'      => translate('Minimum 6 characters'),
            'confirm_password.min'  => translate('Minimum 6 characters'),
            'phone.phone' => 'The phone number format is invalid. Please provide a valid phone number from India or Australia.',
            'phone.phone:IN' => 'Please provide a valid phone number from India, starting with +91 or a valid local format.',
            'phone.phone:AU' => 'Please provide a valid phone number from Australia, starting with +61 or a valid local format.',

        ];
    }
}
