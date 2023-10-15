
<div class="btn-group m-2">
    @role("Super Admin")
        <a href="{{ route('admin.user.index_by_level', 1) }}" class="btn btn-outline-primary @if(request()->route()->parameter('role_id') == 1) active @endif" aria-current="page">Super Admin</a>
    @endrole
    @canany("update user|read user")
        <a href="{{ route('admin.user.index_by_level', 2) }}" class="btn btn-outline-primary @if(request()->route()->parameter('role_id') == 2) active @endif">Admin</a>
        <a href="{{ route('admin.user.index_by_level', 3) }}" class="btn btn-outline-primary @if(request()->route()->parameter('role_id') == 3) active @endif">Petugas Loket</a>
    @endcan
</div>
