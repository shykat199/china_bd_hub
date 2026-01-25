@extends('backend.layouts.app')
@section('title','Email Subscriber - ')
@push('css')
    @include('backend.includes.datatable_css')
@endpush
@section('content')
    <div class="content-body">
    @include('customermanagement::nav')
        <!-- Tab Content Start -->
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="all-customers" Area-labelledby="all-customers-tab">
                <div class="container">

                    <div class="content-table mt-0">
                        <table id="mDataTable" class="table p-table">
                            <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll">
                                    <button type="button" id="bulkDeleteBtn" class="btn btn-sm text-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </th>
                                <th scope="col">{{__('Email')}}</th>
                                <th scope="col">{{__('Status')}}</th>
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
        "use strict";

        $(function () {

            $(document).ready(function(){
                const table = $('#mDataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "@auth('admin'){{route('backend.email_subscriber.list')}}@elseauth('seller'){{route('seller.email_subscriber.list')}}@endauth",

                    order: [[0, 'desc']],

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
                        { data: 'email'},
                        { data: 'opt_out' },
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
                        alert('Please select at least one Email Subscriber.');
                        return;
                    }

                    if (!confirm('Are you sure you want to delete selected Email Subscriber?')) {
                        return;
                    }

                    $.ajax({
                        url: "{{ route('backend.subscriberBulkDelete') }}",
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
                var id = $(this).data('id');

                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: public_path +@auth('admin')'/admin/email_subscriber/changeStatus'@elseauth('seller')'/seller/email_subscriber/changeStatus'@endauth,
                    data: {'status': status, 'id': id,'field': 'opt_out'},
                    success: function(data){
                        notification('success', data.message);
                    }
                });
            });
        });
    </script>
@endpush
