@extends('backend.layouts.app')
@section('title', 'Colors - ')
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
                                <h4 class="text-center">Color</h4>
                            </div>
                            <form action="{{ route('backend.variant.store') }}" class="d-flex gap-1" style="width: 100%" method="POST">
                                @csrf
                                <div style="width: 80%; height: 20%">
                                    <input type="text" class="form-control" name="color" placeholder="Color Name" value="{{ old('color') }}" required>
                                    <span class="text-danger">@error('color'){{ $message }}@enderror</span>
                                </div>
                                <div style="width: 20%">
                                    <input type="color" class="form-control" name="hex" placeholder="Hex Code" value="{{ old('hex') }}" required>
                                    <span class="text-danger">@error('hex'){{ $message }}@enderror</span>
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
                                        <th scope="col">Hex Code</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($colors as $key => $color)
                                        <tr>
                                            <th scope="row">{{ $color->id }}</th>
                                            <td>{{ $color->name }}</td>
                                            <td>{{ $color->hex }}</td>
                                            <td>
                                                <!-- EDIT -->
                                                <button
                                                    class="btn btn-sm btn-primary edit-btn"
                                                    data-id="{{ $color->id }}"
                                                    data-name="{{ $color->name }}"
                                                    data-hex="{{ $color->hex }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editColorModal">
                                                    Edit
                                                </button>

                                                <!-- DELETE -->
                                                <form action="{{ route('variant.delete', $color->id) }}"
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
                                {{ $colors->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- EDIT COLOR MODAL -->
        <div class="modal fade" id="editColorModal" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" id="editColorForm">
                    @csrf

                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Color</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label>Name</label>
                                <input type="text" name="name"
                                       class="form-control"
                                       id="editName" required>
                            </div>

                            <div class="mb-3">
                                <label>Hex Code</label>
                                <input type="color" name="hex"
                                       class="form-control form-control-color"
                                       id="editHex" required>
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
                    const hex  = this.dataset.hex;

                    document.getElementById('editName').value = name;
                    document.getElementById('editHex').value  = hex;

                    // 🔥 THIS IS THE FIX
                    document.getElementById('editColorForm').action =
                        "{{ url('variants/update') }}/" + id;
                });
            });

        });
    </script>
@endpush
