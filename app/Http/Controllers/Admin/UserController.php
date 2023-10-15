<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use SebastianBergmann\CodeCoverage\Report\Xml\Unit;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    function __construct()
    {
        // $this->middleware(['permission:create user|update user|delete user|read user']);
    }

    public function permissions(User $user)
    {
        // dd($role->permissions->pluck('id')->toArray());
        $permissions = Permission::all();
        $group_permissions = [];
        foreach ($permissions as $permission) {
            $split = explode(' ', $permission->name);
            $module = end($split);

            $group_permissions[$module][] = $permission;
        }

        $all_permissions = $user->getAllPermissions()->pluck('id')->toArray();

        // return $user;

        return view('admin.user.permissions-checkbox', compact('user', 'all_permissions', 'group_permissions'));
    }

    public function assignPermissions(Request $request, User $user)
    {
        $this->authorize('update user');
        $validator = Validator::make($request->all(), [
            'permissions' => 'required|array',
        ]);
        if ($validator->fails()) return ['status' => false, 'message' => $validator->errors()->first()];

        $user->syncPermissions($request->permissions);
        return ['status' => true, 'message' => 'Permissions Updated'];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $this->authorize('* user');

        $s = get_search_text();
        $users = search_records(User::query(), ['name'], $s);

        if(request()->has('trash') && request()->trash == 1) {
            $users = $users->onlyTrashed()->paginate(request('limit', 10));
        } else {
            $users = $users->paginate(request('limit', 10));
        }

        Session::flash('page.title', 'Users Management');
        return view('admin.user.index', compact('users'));
    }
    public function index_by_level($role_id)
    {
        $role = Role::where('id', $role_id)->firstOrFail();
        $users = User::role($role->id)->paginate(request('limit', 10));
        return view('admin.user.indexbyrole', compact('users', 'role'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create user');
        $roles = Role::all();
        return view('admin.user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $this->authorize('create user');
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'unitid' => 'required|exists:msunit,unitid',
            'email' => 'required|string|unique:users,email',
            'role' => 'required|exists:roles,name',
        ]);

        if($validator->fails()) return ['status' => false, 'message' => $validator->errors()->first()];

        $user_data = $request->only(['name', 'email', 'unitid']);
        $user_data['password'] = Hash::make("12345678");
        $user_data['is_default_password'] = true;

        $user = User::create($user_data);
        $user->syncRoles([$request->role]);

        return ['status' => true, 'message' => 'User Created'];
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
        $user->all_permissions = $user->getAllPermissions();
        return ['status' => true, 'data' => ['user' => $user]];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update user');
        if($request->has('role') && $request->filled('role')) {
            $user->syncRoles([$request->role]);
        }

        $user_data = $request->except(['role', '_token']);
        if($request->has('password') && $request->filled('password')) {
            $user_data['password'] = Hash::make($request->password);
            $user_data['is_default_password'] = false;
        }

        if(!empty($user_data)) $user->update($user_data);

        return ['status' => true, 'message' => 'User Updated'];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete user');
        $user->delete();
        return ['status' => true, 'message' => 'User Deleted'];
    }

    public function restore($user) {
        $this->authorize('delete user');
        $user = User::onlyTrashed()->find($user);
        $user->restore();
        return ['status' => true, 'message' => 'User Restored'];
    }
}
