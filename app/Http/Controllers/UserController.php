<?php

namespace App\Http\Controllers;

use Auth;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Team;
use App\Mail\UserInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redirect;
use App\Actions\Jetstream\DeleteUser;
use App\Actions\Jetstream\DeleteTeam;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        // Authorize routes
        $addUserRoutes = ['create', 'store', 'revokeInvitation'];
        $this->middleware('authorize:add-user')->only($addUserRoutes);
        $this->middleware('authorize:manage-user')->except($addUserRoutes);
    }
    
    /**
     * Show a list of users (except the currently authenticated user)
     * with select menus to update user roles and buttons to delete users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $predefinedRoles = DB::table('roles')
                            ->distinct()
                            ->orderBy('name')
                            ->pluck('name')
                            ->toArray();
        
        return Inertia::render('User/Manage', [
            'users' => User::where([
                     ['id', '<>', Auth::id()],
                     ['can_login', '1']
                ])->orderBy('name', 'asc')->get(['id', 'name', 'email', 'role']),
            'roles' => array_merge(['admin', 'custom'], $predefinedRoles)
        ]);
    }

    /**
     * Show a form to invite new users.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('User/Invite', [
            'invitations' => User::join('user_invitations', function ($join) {
                    $join->on('users.id', '=', 'user_invitations.inviter_id')
                         ->where('status', 'SN');
                })
                ->select(['user_invitations.id as id', 'invitee', 'invited_at', 'name as inviter'])
                ->get()
        ]);
    }

    /**
     * Store user invitation information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /**
         * Validate
         * - must be email
         * - must not be in the user's table
         * - must not be in the invitation's table
         */
        
        Validator::make($request->all(), [
            'email' => [
                'required', 'string', 'email', 'max:100',
                function ($attribute, $value, $fail) {
                    $result = DB::table('user_invitations')->where('invitee', $value)->first(['status']);
                    if ($result && $result->status != 'RV') {
                        if ($result->status == 'RG') {
                            $fail('Registered users cannot be invited. Removed accounts should be restored to reinstate access.');
                        } else {
                            $fail('An invitation has been sent to this email address already! Existing invitation should be revoked to re-invite.');
                        }
                    }
                },
            ]
        ])->validate();
        
        // generate invitation code
        $invitation_code = str::upper(str::substr(md5(str::random(10)), 0, 16));
        
        // store invitation
        DB::table('user_invitations')->insert([
            'inviter_id' => Auth::id(),
            'invitee' => $request->email,
            'invitation_code' => $invitation_code
        ]);
        
        // Send email to the invitee
        Mail::to($request->email)->send(new UserInvitation(Auth::user(), $invitation_code));
        
        return redirect()->route('users.create');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // Only the role and permissions are updatable through this function
        
        $roles = DB::table('roles')
                    ->distinct()
                    ->orderBy('name')
                    ->pluck('name')
                    ->toArray();
        $roles = array_merge(['admin', 'custom'], $roles);
        
        if ($request->filled('role')) {
            $role = $request->input('role');
            if (in_array($role, $roles)) {
                $user->role = $role;
                if ($role === 'custom') {
                    $user->permissions = $request->input('permissions') ?? NULL;
                }
                $user->save();
                
                return response()->json([
                    'status' => 'success',
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unknown role: ' . $role
                ], 200);
            }
        } else {
            // return Redirect::back()->with('error', 'Only the user roles can be updated.');
            return response()->json([
                'status' => 'error',
                'message' => 'Only the user role and permissions can be updated.'
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        (new DeleteUser(new DeleteTeam()))->delete($user);
        return redirect()->route('users.index');
    }
    
    /**
     * Revoke user invitation
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function revokeInvitation($id)
    {
        DB::table('user_invitations')->where('id', $id)->update(['invitation_code' => NULL, 'status' => 'RV']);
        return redirect()->route('users.create');
    }
}
