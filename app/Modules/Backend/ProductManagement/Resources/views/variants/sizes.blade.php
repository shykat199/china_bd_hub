@extends('backend.layouts.app')
@section('title', 'Sizes - ')
@section('content')
    <div class="content-body">
        @include('productmanagement::includes.product_management')
        <!-- Tab Content Start -->
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="add-brand" role="tabpanel" Area-labelledby="add-brand-tab">
                <div class="container">
                    <div class="row bg-white d-flex justify-content-center gap-5">

                        <div class="col-lg-8 col-sm-12">
                            <div class="mb-2">
                                <h4 class="text-center">Size</h4>
                            </div>
                            <form action="{{ route('backend.variant.store') }}" class="d-flex gap-1" style="width: 100%" method="POST">
                                @csrf
                                <div style="width: 100%">
                                    <input type="text" class="form-control" name="size" placeholder="Size Name" value="{{ old('color') }}">
                                    <span class="text-danger">@error('color'){{ $message }}@enderror</span>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-success text-white">Add</button>
                                </div>
                            </form>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Sl</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sizes as $key => $size)
                                        <tr>
                                            <th scope="row">{{ $size->id }}</th>
                                            <td>{{ $size->name }}</td>
                                            <td>
                                                <!-- EDIT -->
                                                <button
                                                    class="btn btn-sm btn-primary edit-btn"
                                                    data-id="{{ $size->id }}"
                                                    data-name="{{ $size->name }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editColorModal">
                                                    Edit
                                                </button>

                                                <!-- DELETE -->
                                                <form action="{{ route('variant.size.delete', $size->id) }}"
                                                      method="POST"
                                                      class="d-inline">
                                                    @csrf
                                                    <button class="btn btn-sm btn-danger text-white"
                                                            onclick="return confirm('Are you sure?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end">
                                {{ $sizes->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editColorModal" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" id="editColorForm">
                    @csrf

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Size</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name"
                                       class="form-control"
                                       id="editName" required>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary text-white"
                                    data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button class="btn btn-success text-white">
                                Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('custom-script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function () {

                    const id   = this.dataset.id;
                    const name = this.dataset.name;

                    document.getElementById('editName').value = name;

                    // 🔥 THIS IS THE FIX
                    document.getElementById('editColorForm').action =
                        "{{ url('variants-size/update') }}/" + id;
                });
            });

        });
    </script>
@endpush

