<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    function __construct()
    {
        // $this->middleware(['permission:create role|update role|delete role|read role']);
    }

    public static function all_role() {
        return Role::pluck('name')->toArray();
    }

    public function permissions(Role $role)
    {
        // dd($role->permissions->pluck('id')->toArray());
        $permissions = Permission::all();
        $group_permissions = [];
        foreach ($permissions as $permission) {
            $split = explode(' ', $permission->name);
            $module = end($split);

            $group_permissions[$module][] = $permission;
        }

        return view('admin.role.permissions-checkbox', compact('role', 'group_permissions'));
    }

    public function assignPermissions(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'permissions' => 'required|array',
        ]);
        if ($validator->fails()) return ['status' => false, 'message' => $validator->errors()->first()];

        $role->syncPermissions($request->permissions);
        return ['status' => true, 'message' => 'Permissions Updated'];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $s = get_search_text();
        $roles = search_records(Role::query(), ['name'], $s)->paginate(request('limit', 10));
        $permissions = Permission::all();

        return view('admin.role.index', compact('roles', 'permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name',
        ]);
        if ($validator->fails()) return ['status' => false, 'message' => $validator->errors()->first()];

        Role::create($request->only(['name']));
        return ['status' => true, 'message' => 'Role Created'];
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $role->permissions;
        return ['status' => true, 'data' => ['role' => $role]];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:roles,name,' . $role->id,
        ]);
        if ($validator->fails()) return ['status' => false, 'message' => $validator->errors()->first()];

        $role->update($request->only(['name']));
        return ['status' => true, 'message' => 'Role Updated'];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return ['status' => true, 'message' => 'Role Deleted'];
    }
}
