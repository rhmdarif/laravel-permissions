<x-templating.admin.layout>

    <div class="card">
        <div class="card-header">
            <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">Daftar Pengguna</a>
        </div>
        <div class="card-body">
            <form id="form-create" method="post">
                <div class="form-group mb-2">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name">
                </div>
                <div class="form-group mb-2">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email">
                </div>
                <div class="form-group mb-2">
                    <label for="role">Role as</label>
                    <select name="role" id="role" class="form-select">
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <button type="reset" class="btn btn-secondary w-100">Reset</button>
                    </div>
                    <div class="col-md-8">
                        <button type="submit" id="btn-create" class="btn btn-primary w-100">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <x-slot name="js">
        <script>
            $(document).ready(function() {

                $('#form-create').on('submit', (e) => {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('admin.user.store') }}",
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
                                    window.location.href =
                                        "{{ route('admin.user.index') }}";
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
            })
        </script>
    </x-slot>
</x-templating.admin.layout>
