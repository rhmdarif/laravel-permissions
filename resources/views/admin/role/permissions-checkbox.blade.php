<div class="row d-flex">
    @foreach ($group_permissions as $module => $permissions)
        <div class="col-md-4 mb-4 align-self-stretch ">
            <div class="d-flex justify-content-start mb-1">
                <input class="form-check-input me-2" type="checkbox" value="{{ $module }}" id="m-{{ $module }}" onclick="checkAllPermission(this)">
                <h5> Module {{ $module }}</h5>
            </div>
            <div class="d-flex justify-content-start">
            @foreach ($permissions as $permission)
                <div class="form-check mx-2">
                    <input class="form-check-input m-{{ $module }}" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission-{{ $permission->id }}" @checked($role->name == "Super Admin" || in_array($permission->id, $role->permissions->pluck('id')->toArray()))>
                    <label class="form-check-label" for="permission-{{ $permission->id }}">
                        {{ trim(str_replace($module, '', $permission->name)) }}
                    </label>
                </div>
            @endforeach
            </div>
        </div>
    @endforeach
</div>
