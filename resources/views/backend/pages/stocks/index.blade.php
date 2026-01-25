@extends('backend.layouts.app')
@section('title', 'Stock List')

@section('content')
    <div class="content-body">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <h5>{{ __('Manage Stock') }}</h5>
                    <div class="row align-items-center mb-3">

                        <div class="col-xxl-3 col-lg-3 col-md-6 mb-2 ms-auto text-end">
                            <form action="{{ route('backend.products.wholesale') }}" method="GET" id="limitForm">
                                {{-- keep search query --}}
                                <input type="hidden" name="search" value="{{ request('search') }}">

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
                    </div>
                    <table class="table p-0 p-table table-bordered table-striped table-hover">
                        <thead class="bg-secondary text-light">
                            <tr>
                                <th scope="col">
                                    <input type="checkbox" id="selectAll">
                                    <button type="button" id="bulkDeleteBtn" class="btn btn-sm text-danger">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </th>
                                <th>{{__('ID')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Price')}}</th>
                                <th>{{__('Stock')}}</th>
                                <th>{{__('Sold')}}</th>
                                <th>{{__('Viewed')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                            <tr class="borderd">
                                <td><input type="checkbox"
                                           class="rowCheckbox"
                                           value="{{ $product->id }}"></td>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ currency($product->unit_price, 2) }}</td>
                                <td>
                                    <div class="badge bg-success">
                                        {{ $product->quantity }}
                                    </div>
                                </td>
                                <td>{{ $product->orders_sum_qty }}</td>
                                <td>{{ $product->total_viewed }}</td>
                                <td>
                                    <a href="{{ route('backend.stocks.show', $product->id) }}" class="text-warning">
                                        <i class="fa fa-eye" Area-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row float-end pt-3">
                        <div class="col-12 text-center">
                            {{ $products->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
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
                alert('Please select at least one stock.');
                return;
            }

            if (!confirm('Are you sure you want to delete selected stock?')) {
                return;
            }

            $.ajax({
                url: "{{ route('backend.stockBulkDelete') }}",
                type: "POST",
                data: {
                    ids: ids,
                    _token: "{{ csrf_token() }}"
                },
                success: function () {
                    $('#selectAll').prop('checked', false);
                    window.location.reload()
                },
                error: function () {
                    alert('Something went wrong.');
                }
            });
        });
    </script>
@endpush
