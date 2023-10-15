<x-templating.admin.layout>


    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title">All User</h4>
                    @if (request()->has('trash') && request()->trash == 1)
                        <a href="?trash=0" class="btn btn-icon btn-sm btn-outline-primary">Current</a>
                    @else
                        <a href="?trash=1" class="btn btn-icon btn-sm btn-outline-danger">Trash</a>
                    @endif
                </div>
                <div class="card-body">
                    <form method="get" id="form-search">
                        <div class="d-flex justify-content-between mb-3">
                            <select name="limit" id="limit" class="form-select" style="width: 65px;" onchange="$('#form-search').submit()">
                                @foreach ([10, 20, 50] as $l)
                                    <option value="{{ $l }}" @selected(request('limit', 10) == $l)>{{ $l }}</option>
                                @endforeach
                            </select>

                            <div class="input-group" style="width: 250px;">
                                <input type="text" class="form-control" id="search" name="q" value="{{ request('q', '') }}"
                                    placeholder="Search...">
                                <button type="submit" class="btn btn-outline-primary">Find</button>
                            </div>
                        </div>
                    </form>

                    @include('admin.user.button-roles')

                    <table class="table">
                        <thead>
                            <tr>
                                <th width="10%">#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $page = (request('page', 1)-1)*10;
                            @endphp
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $page+$loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ implode(',', $user->roles->pluck('name')->toArray() ?? []) }}</td>
                                    <td>
                                        @if ($user->hasRole('Petugas Loket'))
                                            <a href="http://www.google.com" class="btn btn-outline-primary btn-sm m-1">Assign Loket</a>
                                        @endif
                                        @if (request()->has('trash') && request()->trash == 1)
                                            @can('update user')
                                                <a href="javascript:restore('{{ $user->id }}')"
                                                    class="btn btn-primary btn-sm m-1">Restore</a>
                                            @endcan
                                        @else
                                            @can('read user')
                                                <a href="javascript:listPermissions('{{ $user->id }}')"
                                                    class="btn btn-info btn-sm m-1">Permission</a>
                                            @endcan
                                            @can('update user')
                                                <a href="javascript:edit('{{ $user->id }}')"
                                                    class="btn btn-primary btn-sm m-1">Edit</a>
                                                <a href="javascript:set_password('{{ $user->id }}')"
                                                    class="btn btn-outline-primary btn-sm m-1">Set Pass</a>
                                            @endcan

                                            @can('delete user')
                                                <a href="javascript:remove('{{ $user->id }}')"
                                                    class="btn btn-danger btn-sm m-1">Del</a>
                                            @endcan
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-2"></div>
                    {{ $users->appends(request()->query())->links("pagination::bootstrap-5") }}
                </div>
            </div>
        </div>
    </div>

    <x-slot name="modal">
        <!-- Modal Edit -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="form-edit" method="post">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="editModalLabel">Edit User | Id : <span
                                    id="model-id"></span></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-2">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="form-group mb-2">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal Edit Password -->
        <div class="modal fade" id="editPasswordModal" tabindex="-1" aria-labelledby="editPasswordModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="form-edit-password" method="post">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="editPasswordModalLabel">Edit User | Id : <span
                                    id="model-id"></span></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-2">
                                <label for="password">Password</label>
                                <input type="text" class="form-control" id="password" name="password">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal List Permission -->
        <div class="modal fade" id="listPermissionsModal" tabindex="-1" aria-labelledby="listPermissionsModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editModalLabel">List Permission of User | Id : <span
                                id="model-id"></span></h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <button type="button" class="btn btn-primary btn-sm float-end mb-2"
                            id="btn-add-permission">Add</button>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">Permission</th>
                                </tr>
                            </thead>
                            <tbody id="list-permission">
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Assign Permission -->
        <div class="modal fade" id="assignPermissionModal" tabindex="-1"
            aria-labelledby="assignPermissionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <form id="form-assign-permission" method="post">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="assignPermissionModalLabel">Assign Permission to User |
                                Id : <span id="model-id"></span></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-slot>

    <x-slot name="js">
        <script>
            function listPermissions(id) {
                $.ajax({
                    url: "{{ route('admin.user.index') }}/" + id + "/edit",
                    method: "GET",
                    success: (res) => {
                        $('#listPermissionsModal #model-id').text(id);

                        let row = "";
                        res.data.user.all_permissions.forEach(permission => {
                            row += `<tr>
                                        <td>${permission.name}</td>
                                    </tr>`;
                        });

                        $('#listPermissionsModal tbody#list-permission').html(row);
                        $('#listPermissionsModal button#btn-add-permission').attr("onclick",
                            `assignPermission('${id}')`);
                        $('#listPermissionsModal').modal('show');
                    }
                });
            }

            function assignPermission(id) {
                $.ajax({
                    url: "{{ route('admin.user.index') }}/" + id + "/permissions-checkbox",
                    method: "GET",
                    success: (res) => {
                        $('#assignPermissionModal #model-id').text(id);
                        $('#assignPermissionModal .modal-body').html(res);
                        $('#assignPermissionModal').modal('show');
                    }
                });
            }

            function checkAllPermission(el_module) {
                let value = $(el_module).attr('value');

                if (el_module.checked) {
                    $('.m-' + value).each(function() {
                        this.checked = true;
                    });
                } else {
                    $('.m-' + value).each(function() {
                        this.checked = false;
                    });
                }
            }

            $(document).ready(() => {

                $('#form-assign-permission').on('submit', (e) => {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('admin.user.index') }}/" + $('#assignPermissionModal #model-id')
                            .text() + "/assign-permissions",
                        method: "POST",
                        data: $('#form-assign-permission').serialize(),
                        success: (res) => {
                            $('#form-assign-permission')[0].reset();

                            if (res.status == true) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: res.message
                                }).then(() => {
                                    $('#assignPermissionModal').modal('hide');
                                    $('#listPermissionsModal').modal('hide');
                                    listPermissions($('#assignPermissionModal #model-id')
                                        .text());
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: res.message
                                });
                            }
                        },
                        error: (err) => {
                            console.log(err);
                        },
                    })
                });
            })
        </script>
        <script>
            $(document).ready(() => {
                $('#form-edit').on('submit', (e) => {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('admin.user.index') }}/" + $('#form-edit #model-id').text(),
                        method: "PUT",
                        data: $('#form-edit').serialize(),
                        success: (res) => {

                            if (res.status == true) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: res.message
                                }).then(() => {
                                    window.location.href =
                                        "{{ route('admin.user.index') }}";
                                });
                            } else {
                                $('#form-edit')[0].reset();

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: res.message
                                });
                            }
                        },
                        error: (err) => {
                            console.log(err);
                        },
                    })
                });
                $('#form-edit-password').on('submit', (e) => {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('admin.user.index') }}/" + $('#form-edit-password #model-id')
                            .text(),
                        method: "PUT",
                        data: $('#form-edit-password').serialize(),
                        success: (res) => {

                            if (res.status == true) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: res.message
                                }).then(() => {
                                    window.location.href =
                                        "{{ route('admin.user.index') }}";
                                });
                            } else {
                                $('#form-edit')[0].reset();

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: res.message
                                });
                            }
                        },
                        error: (err) => {
                            console.log(err);
                        },
                    })
                });
            });

            function edit(id) {
                $.ajax({
                    url: "{{ route('admin.user.index') }}/" + id + "/edit",
                    method: "GET",
                    success: (res) => {
                        $('#form-edit #model-id').text(res.data.user.id);
                        $('#form-edit #name').val(res.data.user.name);
                        $('#editModal').modal('show');
                    }
                });
            }

            function set_password(id) {
                $('#form-edit-password #model-id').text(id);
                $('#editPasswordModal').modal('show');
            }

            function remove(id) {
                $.ajax({
                    url: "{{ route('admin.user.index') }}/" + id + "/edit",
                    method: "GET",
                    success: (res) => {
                        Swal.fire({
                            title: 'Do you want to remove record of ' + res.data.user.name + '?',
                            showDenyButton: true,
                            confirmButtonText: 'Remove',
                            denyButtonText: `Cancel`,
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {

                                $.ajax({
                                    url: "{{ route('admin.user.index') }}/" + id,
                                    method: "DELETE",
                                    success: (res_delete) => {
                                        if (res_delete.status) {
                                            Swal.fire({
                                                icon: 'success',
                                                title: 'Success',
                                                text: res_delete.message
                                            }).then(() => {
                                                window.location.reload();
                                            });
                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Error',
                                                text: res_delete.message
                                            });
                                        }
                                    },
                                    error: (xhr, status, error) => {
                                        Swal.fire({
                                            icon: 'error',
                                            title: error,
                                            text: xhr.responseJSON.message
                                        });
                                    },
                                });

                            } else if (result.isDenied) {
                                Swal.fire('Changes are not saved', '', 'info')
                            }
                        })
                    },
                    error: (xhr, status, error) => {
                        Swal.fire({
                            icon: 'error',
                            title: error,
                            text: xhr.responseJSON.message
                        });
                    },
                });
            }

            function restore(id) {
                Swal.fire({
                    title: 'Do you want to restore record of id :' + id + '?',
                    showDenyButton: true,
                    confirmButtonText: 'Remove',
                    denyButtonText: `Cancel`,
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {

                        $.ajax({
                            url: "{{ route('admin.user.index') }}/" + id + "/restore",
                            method: "POST",
                            success: (res_delete) => {
                                if (res_delete.status) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: res_delete.message
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: res_delete.message
                                    });
                                }
                            },
                            error: (xhr, status, error) => {
                                Swal.fire({
                                    icon: 'error',
                                    title: error,
                                    text: xhr.responseJSON.message
                                });
                            },
                        });

                    } else if (result.isDenied) {
                        Swal.fire('Changes are not saved', '', 'info')
                    }
                })
            }
        </script>
    </x-slot>
</x-templating.admin.layout>
