<?php

namespace App\Actions\Fortify;

use App\Models\Team;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        /**
         * a user was invited and registered. then the user was removed (self or by an admin doesn't matter)
         * if the user want to re-register, the user needs to contact an administrator. there will be a page
         * from where an admin can change can_login of the user to true. the user will use forget password
         * to reset password (password will be set to null on account removal)
         */
        
        Validator::make($input, [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users', 'exists:user_invitations,invitee'],
            'invitation_code' => ['required', 'string', 'size:16', Rule::exists('user_invitations', 'invitation_code')->where('invitee', $input['email'])],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ], [
            'email.unique' => 'You are a registered user. Please log in. If you were registered and you deleted your account or your account has been deleted, please contact an administrator to restore your account.',
            'email.exists' => 'Registration is by invitation only! Please contact an administrator.',
            'invitation_code.exists' => 'The invitation code does not match!',
        ])->stopOnFirstFailure(true)->validate();

        return DB::transaction(function () use ($input) {
            return tap(User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'role' => 'custom',
                'password' => Hash::make($input['password'])
            ]), function (User $user) {
                $user->email_verified_at = $user->created_at;
                $user->save();
                
                $this->createTeam($user);
                DB::table('user_invitations')
                    ->where('invitee', $user->email)
                    ->update([
                        'invitation_code' => NULL,
                        'status' => 'RG',
                        'user_id' => $user->id
                    ]);
            });
        });
    }

    /**
     * Create a personal team for the user.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    protected function createTeam(User $user)
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
    }
}
