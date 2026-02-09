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
                            <div class="col-xxl-3 col-lg-3 col-md-6 mb-2 ms-auto text-end">
                                <form action="{{ route('backend.variant.units') }}" method="GET" id="limitForm">

                                    <select name="limit"
                                            class="form-select"
                                            onchange="this.form.submit()">
                                        @foreach([10, 25, 50, 100] as $limit)
                                            <option value="{{ $limit }}"
                                                {{ request('limit', 10) == $limit ? 'selected' : '' }}>
                                                {{ $limit }} per page
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                            <form action="{{ route('backend.variant.units.store') }}" class="d-flex gap-1" style="width: 100%" method="POST">
                                @csrf
                                <div style="width: 100%">
                                    <input type="text" class="form-control" name="size" placeholder="Unit Name" value="{{ old('color') }}">
                                    <span class="text-danger">@error('color'){{ $message }}@enderror</span>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-success text-white">Add</button>
                                </div>
                            </form>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAll">
                                            <button type="button" id="bulkDeleteBtn" class="btn btn-sm text-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </th>
                                        <th scope="col">Sl</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($unites as $key => $size)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="ids[]" value="{{ $size->id }}"
                                                       data-bulk="true"
                                                       class="wholesaleCheckbox rowCheckbox">
                                            </td>
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
                                                <form action="{{ route('variant.unites.delete', $size->id) }}"
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
                                {{ $unites->links() }}
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
                            <h5 class="modal-title">Edit Unit</h5>
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
                        "{{ url('variants-unites/update') }}/" + id;
                });
            });

        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const selectAll = document.getElementById('selectAll');
            const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

            /* =====================================================
               1. LOCK STATUS CHECKBOXES (IMMUTABLE FOR BULK)
            ===================================================== */
            function lockStatusCheckboxes() {
                document.querySelectorAll('input[data-bulk="false"]').forEach(cb => {

                    // store original state once
                    if (!cb.dataset.locked) {
                        cb.dataset.locked = cb.checked ? '1' : '0';
                    }

                    // stop all bubbling / row triggers
                    ['click', 'change', 'mousedown'].forEach(evt => {
                        cb.addEventListener(evt, e => e.stopPropagation());
                    });

                    // force state back if anything toggles it
                    cb.checked = cb.dataset.locked === '1';
                });
            }

            lockStatusCheckboxes();

            /* =====================================================
               2. SELECT ALL (ONLY ROW CHECKBOXES)
            ===================================================== */
            if (selectAll) {
                selectAll.addEventListener('change', function () {

                    document.querySelectorAll('input[data-bulk="true"]').forEach(cb => {
                        cb.checked = this.checked;
                    });

                    // re-assert status states
                    lockStatusCheckboxes();
                });
            }

            /* =====================================================
               3. ROW CHECKBOX CHANGE → SYNC SELECT ALL
            ===================================================== */
            function bindRowCheckboxEvents() {
                document.querySelectorAll('input[data-bulk="true"]').forEach(cb => {
                    if (cb.dataset.bound) return;

                    cb.dataset.bound = '1';

                    cb.addEventListener('change', function () {

                        const total = document.querySelectorAll('input[data-bulk="true"]').length;
                        const checked = document.querySelectorAll('input[data-bulk="true"]:checked').length;

                        if (selectAll) {
                            selectAll.checked = total === checked;
                        }

                        lockStatusCheckboxes();
                    });
                });
            }

            bindRowCheckboxEvents();

            /* =====================================================
               4. BULK DELETE AJAX
            ===================================================== */
            if (bulkDeleteBtn) {
                bulkDeleteBtn.addEventListener('click', function () {

                    const checked = document.querySelectorAll('input[data-bulk="true"]:checked');

                    if (!checked.length) {
                        alert('Please select at least one item');
                        return;
                    }

                    if (!confirm('Are you sure you want to delete selected items?')) {
                        return;
                    }

                    const ids = Array.from(checked).map(cb => cb.value);

                    fetch("{{ route('unit.bulkDelete') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ ids })
                    })
                        .then(res => res.json())
                        .then(res => {
                            if (res.success) {
                                location.reload();
                            } else {
                                alert('Delete failed');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Something went wrong');
                        });
                });
            }

        });
    </script>
@endpush

