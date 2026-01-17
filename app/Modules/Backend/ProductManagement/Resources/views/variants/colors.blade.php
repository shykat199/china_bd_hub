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
                                <div style="width: 50%">
                                    <input type="text" class="form-control" name="color" placeholder="Color Name" value="{{ old('color') }}">
                                    <span class="text-danger">@error('color'){{ $message }}@enderror</span>
                                </div>
                                <div style="width: 50%">
                                    <input type="text" class="form-control" name="hex" placeholder="Hex Code" value="{{ old('hex') }}">
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($colors as $key => $color)
                                        <tr>
                                            <th scope="row">{{ $color->id }}</th>
                                            <td>{{ $color->name }}</td>
                                            <td>{{ $color->hex }}</td>
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
        <!-- Tab Content End -->
    </div>
@endsection
