@extends('backend.layouts.app')
@section('title','Brands - ')
@push('css')
    @include('backend.includes.datatable_css')
@endpush
@section('content')
    <div class="content-body">
    @include('productmanagement::includes.product_management')
        <!-- Tab Content Start -->
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="brand" role="tabpanel" Area-labelledby="brand-tab">
                <div class="container">
                    <div class="content-table mt-0">
                        <table id="mDataTable" class="table p-table">
                            <thead>
                            <tr>
                                <th scope="col">
                                    <input type="checkbox" id="selectAll">
                                    <button type="button" id="bulkDeleteBtn" class="btn btn-sm text-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </th>
                                <th scope="col">{{ __('ID') }}</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Logo') }}</th>
                                <th scope="col">{{ __('Slug') }}</th>
                                <th scope="col">{{ __('Order') }}</th>
                                <th scope="col">{{ __('Status') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tab Content End -->
    </div>
@endsection

@push('js')
    @include('backend.includes.datatable_js')
    <script>
        $(function() {
            "use strict";

            $(document).ready(function(){
                // DataTable
                const table = $('#mDataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "@auth('admin'){{ route('backend.brand.list') }}@elseauth('seller'){{ route('seller.brand.list') }}@endauth",

                    // 🔥 never sort by checkbox column
                    order: [[1, 'desc']],

                    columns: [
                        {
                            data: 'id',
                            orderable: false,
                            searchable: false,
                            render: function (data) {
                                return `<input type="checkbox" class="rowCheckbox" value="${data}">`;
                            }
                        },
                        { data: 'id' },
                        { data: 'name' },
                        { data: 'logo', orderable: false, searchable: false },
                        { data: 'slug' },
                        { data: 'order' },
                        { data: 'is_active', orderable: false },
                        { data: 'action', orderable: false, searchable: false }
                    ]
                });

                $(document).on('change', '#selectAll', function () {
                    $('.rowCheckbox').prop('checked', this.checked);
                });

                $(document).on('change', '.rowCheckbox', function () {
                    if (!this.checked) {
                        $('#selectAll').prop('checked', false);
                    }
                });

                $('#bulkDeleteBtn').on('click', function () {
                    let ids = [];

                    $('.rowCheckbox:checked').each(function () {
                        ids.push($(this).val());
                    });

                    if (ids.length === 0) {
                        alert('Please select at least one category.');
                        return;
                    }

                    if (!confirm('Are you sure you want to delete selected categories?')) {
                        return;
                    }

                    $.ajax({
                        url: "{{ route('backend.brands.bulk-delete') }}",
                        type: "POST",
                        data: {
                            ids: ids,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function () {
                            $('#selectAll').prop('checked', false);
                            table.ajax.reload(null, false); // reload without page reset
                        },
                        error: function () {
                            alert('Something went wrong.');
                        }
                    });
                });

            });

            $(document).on('click','#mDataTable .status', function() {
                var status = $(this).prop('checked') == true ? 1 : 0;
                var brand_id = $(this).data('id');

                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: public_path +@auth('admin')'/admin/brand/changeStatus'@elseauth('seller')'/seller/brand/changeStatus'@endauth,
                    data: {'status': status, 'brand_id': brand_id,'field': 'is_active'},
                    success: function(data){
                        notification('success', data.message);
                    }
                });
            });
        });
    </script>
@endpush
