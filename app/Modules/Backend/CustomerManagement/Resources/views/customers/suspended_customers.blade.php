@extends('backend.layouts.app')
@section('title','Suspended Customer - ')
@push('css')
    @include('backend.includes.datatable_css')
@endpush
@section('content')
    <div class="content-body">
    @include('customermanagement::nav')
        <!-- Tab Content Start -->
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="suspended-customers" Area-labelledby="suspended-customers-tab">
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
                                <th scope="col">{{__('Name')}}</th>
                                <th scope="col">{{__('Email')}}</th>
                                <th scope="col">{{__('Phone Number')}}</th>
                                <th scope="col">{{__('Gender')}}</th>
                                <th scope="col">{{__('Suspended')}}</th>
                                <th scope="col">{{__('Action')}}</th>
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
        <!-- modal content -->
        <div class="modal fade" id="exampleModal" tabindex="-1" Area-labelledby="exampleModalLabel" Area-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{__('New message')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" Area-label="Close"></button>
                    </div>
                    <form id="messageForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="message-text" class="col-form-label">{{__('Subject')}}:</label>
                                <input name="subject" class="form-control" type="text" required>
                            </div>
                            <div class="mb-3">
                                <label for="message-text" class="col-form-label">{{__('Message')}}:</label>
                                <textarea name="message" rows="3" class="form-control" required id="message-text"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                            <button type="submit" class="btn btn-primary">{{__('Send message')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    @include('backend.includes.datatable_js')

    <script>

        $(function () {
            "use strict";

            $(document).ready(function(){
                const table = $('#mDataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{route('backend.suspended_customer.list')}}",

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
                        { data: 'last_name' },
                        { data: 'email'},
                        { data: 'mobile' },
                        { data: 'gender' },
                        { data: 'is_suspended' },
                        { data: 'action',searchable:false,sortable:false },
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
                        alert('Please select at least one user.');
                        return;
                    }

                    if (!confirm('Are you sure you want to delete selected user?')) {
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


            $(document).on('click','#mDataTable .suspend', function() {
                var status = $(this).prop('checked') == true ? 1 : 0;
                var id = $(this).data('id');

                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: public_path +'/admin/customer/changeStatus',
                    data: {'status': status, 'id': id,'field': 'is_suspended'},
                    success: function(data){
                        notification('success', data.message);
                    }
                });
            });

            var mailModal = document.getElementById('exampleModal')
            mailModal.addEventListener('show.bs.modal', function (event) {
                // Button that triggered the modal
                var button = event.relatedTarget
                //  initiate an AJAX request
                let form = document.getElementById('messageForm');
                // Extract info from data-bs-* attributes
                var recipient = button.getAttribute('data-bs-recipient')
                var recipient_name = button.getAttribute('data-bs-recipient_name')

                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    var message = form.querySelector('.modal-body textarea').value;
                    var subject = form.querySelector('.modal-body input').value;
                    $.ajax({
                        type: "get",
                        dataType: "json",
                        url: public_path + '/admin/customer/sendMail',
                        data: {'subject':subject,'name': recipient_name, 'email': recipient, 'message': message},
                        success: function (data) {
                            $('#exampleModal').modal('hide');
                            notification('success', data.message);
                        }
                    });
                });
            })
        });
    </script>
@endpush
