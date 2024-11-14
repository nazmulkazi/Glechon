<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'email', 'password', 'role', 'permissions', 'can_login'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];
    
    /**
     * Retrieve the permissions attribute for the user. Permissions are sourced from
     * the roles table for predefined roles, while the user-specific permissions from
     * the users table are returned for the "custom" role.
     * 
     * User permissions are directly dependent on the user's role. There are two
     * permission columns: one in the users table and another in the roles table.
     * Users with the "admin" role can perform any actions, and the permissions
     * column should be null for them. The roles table contains predefined roles
     * with specific sets of permissions for each role. For users with predefined
     * roles from the roles table, the permissions column in the users table remains
     * null, and the permissions associated with their role are utilized. For cases
     * where user-specific permissions do not align with predefined roles, users
     * can be assigned the "custom" role. This allows users to have unique permissions
     * that don't correspond to any predefined roles, eliminating the need to define
     * new roles for individual users. When a user's role is set to "custom," the
     * permissions stored in the user table apply to them. Please note that the roles
     * table cannot contain entries for "admin" or "custom" roles.
     * 
     * @param  string|null  $permissions
     * @return object|null
     */
    public function getPermissionsAttribute($permissions)
    {
        // Fetch permissions from the roles table for predefined roles
        if ($this->role !== 'admin' && $this->role !== 'custom') {
            $permissions = DB::table('roles')
                ->where('name', $this->role)
                ->value('permissions');
        }
        return json_decode($permissions) ?? Array();
    }
}
