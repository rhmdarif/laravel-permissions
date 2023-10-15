<x-templating.admin.layout>

    <div class="row">
        @can('create permission')
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Create a Permission</h4>
                    </div>
                    <div class="card-body">
                        <form id="form-create" method="post">
                            <div class="form-group mb-2">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>

                            <div class="row">
                                <div class="col-md-5">
                                    <button type="reset" class="btn btn-secondary w-100">Reset</button>
                                </div>
                                <div class="col-md-7">
                                    <button type="submit" id="btn-create" class="btn btn-primary w-100">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan
        <div class="{{ auth()->user()->can('create permission')? 'col-md-9': 'col-md-12' }}">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">All Permission</h4>
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

                    <table class="table">
                        <thead>
                            <tr>
                                <th width="10%">#</th>
                                <th>Name</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $page = (request('page', 1) - 1) * 10;
                            @endphp
                            @foreach ($permissions as $permission)
                                <tr>
                                    <td>{{ $page + $loop->iteration }}</td>
                                    <td>{{ $permission->name }}</td>
                                    <td>
                                        @can('update permission')
                                            <a href="javascript:edit('{{ $permission->id }}')"
                                                class="btn btn-primary btn-sm">Edit</a>
                                        @endcan

                                        @can('delete permission')
                                            <a href="javascript:remove('{{ $permission->id }}')"
                                                class="btn btn-danger btn-sm">Del</a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-2"></div>
                    {{ $permissions->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <x-slot name="modal">
        <!-- Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="form-edit" method="post">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="editModalLabel">Edit Permission | Id : <span
                                    id="model-id"></span></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-2">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name">
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
    </x-slot>

    <x-slot name="js">
        <script>
            $(document).ready(() => {
                $('#form-create').on('submit', (e) => {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('admin.permission.store') }}",
                        method: "POST",
                        data: $('#form-create').serialize(),
                        success: (res) => {
                            $('#form-create')[0].reset();

                            if (res.status == true) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: res.message
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: res.message
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
                    })
                });
                $('#form-edit').on('submit', (e) => {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('admin.permission.index') }}/" + $('#model-id').text(),
                        method: "PUT",
                        data: $('#form-edit').serialize(),
                        success: (res) => {

                            if (res.status == true) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: res.message
                                }).then(() => {
                                    window.location.reload();
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
                        error: (xhr, status, error) => {
                            Swal.fire({
                                icon: 'error',
                                title: error,
                                text: xhr.responseJSON.message
                            });
                        },
                    })
                });
            });

            function edit(id) {
                $.ajax({
                    url: "{{ route('admin.permission.index') }}/" + id + "/edit",
                    method: "GET",
                    success: (res) => {
                        $('#form-edit #model-id').text(res.data.id);
                        $('#form-edit #name').val(res.data.name);
                        $('#editModal').modal('show');
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

            function remove(id) {
                $.ajax({
                    url: "{{ route('admin.permission.index') }}/" + id + "/edit",
                    method: "GET",
                    success: (res) => {
                        Swal.fire({
                            title: 'Do you want to remove record of ' + res.data.name + '?',
                            showDenyButton: true,
                            confirmButtonText: 'Remove',
                            denyButtonText: `Cancel`,
                        }).then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {

                                $.ajax({
                                    url: "{{ route('admin.permission.index') }}/" + id,
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
        </script>
    </x-slot>
</x-templating.admin.layout>
