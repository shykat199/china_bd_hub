@extends('backend.layouts.app')
@section('title','Category - ')
@push('css')
    @include('backend.includes.datatable_css')
@endpush
@section('content')
    <div class="content-body">
    @include('productmanagement::includes.product_management')
        <!-- Tab Content Start -->
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="category" role="tabpanel" Area-labelledby="category-tab">
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
                                <th scope="col">{{ __('Id') }}</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Image') }}</th>
                                <th scope="col">{{ __('Parent') }}</th>
                                <th scope="col">{{ __('Display') }}</th>
                                <th scope="col">{{ __('Sort') }}</th>
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
            $(document).on('click','#mDataTable .status', function() {
                var status = $(this).prop('checked') == true ? 1 : 0;
                var cat_id = $(this).data('id');

                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: public_path +@auth('admin')'/admin/category/changeStatus'@elseauth('seller')'/seller/category/changeStatus'@endauth,
                    data: {'status': status, 'cat_id': cat_id,'field': 'is_active'},
                    success: function(data){
                        notification('success', data.message);
                    }
                });
            });
            $(document).on('click','#mDataTable .display_out_website', function() {
                var status = $(this).prop('checked') == true ? 1 : 0;
                var cat_id = $(this).data('id');

                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: public_path +@auth('admin')'/admin/category/changeStatus'@elseauth('seller')'/seller/category/changeStatus'@endauth,
                    data: {'status': status, 'cat_id': cat_id,'field': 'show_in_home'},
                    success: function(data){
                        notification('success', data.message);
                    }
                });
            });

            $(document).ready(function(){
                const table = $('#mDataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('backend.category.list') }}",

                    order: [[0, 'desc']], // sort by id

                    columns: [
                        {
                            data: 'id',
                            orderable: false,
                            searchable: false,
                            render: function (data, type, row) {
                                return `
                                <input type="checkbox"
                                       class="rowCheckbox"
                                       value="${data}">
                            `;
                                        }
                        },
                        { data: 'id' },
                        { data: 'name' },
                        { data: 'image', orderable: false, searchable: false },
                        { data: 'category_id' },
                        { data: 'show_in_home', orderable: false },
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
                        url: "{{ route('backend.categories.bulk-delete') }}",
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
        });
    </script>
@endpush
