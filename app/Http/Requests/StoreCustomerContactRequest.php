<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\CustomerContact;

class StoreCustomerContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return false;
    }

    public function rules(): array
    {
        return [
            'first_name'         => ['required', 'string', 'max:100'],
            'last_name'          => ['required', 'string', 'max:100'],
            'email_address'      => ['required', 'email', 'max:225'],
            'subject_select'     => ['required', 'in:'.implode(',', [
                CustomerContact::SUBJECT_GENERAL,
                CustomerContact::SUBJECT_SUPPORT,
                CustomerContact::SUBJECT_FEEDBACK,
            ])],
            'message_description'=> ['required', 'string'],
            'product_search'     => ['nullable', 'string', 'max:225'],
            'order_id'           => ['nullable', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required'     => 'Please enter your first name.',
            'last_name.required'      => 'Please enter your last name.',
            'email_address.required'  => 'Please enter your e-mail address.',
            'email_address.email'     => 'Please enter a valid e-mail address.',
            'subject_select.required' => 'Please select a subject.',
            'message_description.required' => 'Please enter your message.',
        ];
    }
}
