<?php
 namespace App\Http\Requests\Api\Profile;
  use Illuminate\Foundation\Http\FormRequest;
  use Illuminate\Validation\Rule;
   class UpdateProfileRequest extends FormRequest {
    public function authorize(): bool { 
        return true; 
    } 
    public function rules(): array {
        $userId = $this->user()->id; 
        info('My Request Data:', );
        return [ 
            'first_name' => [ 'required', 'string', 'max:50', ], 
            'last_name' => [ 'required', 'string', 'max:50', ], 
            'phone_number' => [ 'nullable', 'string', 
            Rule::unique('users', 'phone_number')->ignore($userId), ], 
            'whatsapp' => [ 'nullable', 'string', 
            Rule::unique('users', 'whatsapp')->ignore($userId), ], 
            'profile_picture' => [ 'nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048', ], 
        ]; 
    } 
}