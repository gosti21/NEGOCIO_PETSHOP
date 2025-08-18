<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Http;
use App\Enums\TypeOfDocuments;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{

    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/', 'between:3,80'],
            'last_name' => ['required', 'string', 'regex:/^[A-Za-záéíóúÁÉÍÓÚñÑ\s]+$/', 'between:3,80'],
            'document_type' => ['required', 'string', new Enum(TypeOfDocuments::class)],
            'document_number' => ['required', 'string', 'max:20', 'unique:document_types,document_number'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'integer', 'digits:9', 'unique:phones,number'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        DB::beginTransaction();
        try{
            $user = User::create([
                'name' => $input['name'],
                'last_name' => $input['last_name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);

            $user->documents()->create([
                'document_type' => $input['document_type'],
                'document_number' => $input['document_number'],
            ]);

            $user->phones()->create([
                'number' => $input['phone']
            ]);

            DB::commit();
            return $user;

        } catch(\Exception $e)
        {
            DB::rollback();
            throw $e;
        }
    }
}


