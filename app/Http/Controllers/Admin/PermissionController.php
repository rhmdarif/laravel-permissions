<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    function __construct()
    {
        // $this->middleware(['permission:create permission|update permission|delete permission|read permission']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $s = get_search_text();
        $permissions = search_records(Permission::query(), ['name'], $s)->paginate(request('limit', 10));

        return view('admin.permission.index', compact('permissions'));
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
        //
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name',
        ]);
        if ($validator->fails()) return ['status' => false, 'message' => $validator->errors()->first()];

        Permission::create($request->only(['name']));
        return ['status' => true, 'message' => 'Permission Created'];
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Permission $permission)
    {
        return ['status' => true, 'data' => $permission];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
        ]);
        if ($validator->fails()) return ['status' => false, 'message' => $validator->errors()->first()];

        $permission->update($request->only(['name']));
        return ['status' => true, 'message' => 'Permission Updated'];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return ['status' => true, 'message' => 'Permission Deleted'];
    }
}
