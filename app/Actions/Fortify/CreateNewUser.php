<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Tenant;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ];

        $isCustomer = false;
        try {
            $isCustomer = $input["isCustomer"] == "on" ?  true : false;
        } catch (\Throwable $th) {
            $isCustomer = false;
        }
        if (!$isCustomer) $rules["company_name"] = ['required', 'string', 'max:255'];

        Validator::make($input, $rules)->validate();

        $data = [
            "isCustomer" => $isCustomer,
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ];
        if (!$isCustomer) {
            $tenant = Tenant::create([
                "name" => $input["company_name"]
            ]);

            $data["tenant_id"] = $tenant->id;
            $data["tenant_role"] = "admin";
        }

        return User::create($data);
    }
}
