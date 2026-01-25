@extends('backend.layouts.app')
@section('title','Tag Manager Manage')


@section('content')
    <div class="content-body">

    <!-- start page title -->

        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="questions-and-answer" role="tabpanel" Area-labelledby="questions-and-answer-tab">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-8 mb-2">
                            <div class="title">
                                <h4>{{__('Tag Manager Manage')}}</h4>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="float-md-end">
                                <a href="{{route('backend.tagmanagers.create')}}">
                                    <button class="btn btn-primary text-white"> + {{ __('Add New Tag') }}</button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="content-table">
                        <table id="mDataTable" class="table p-table">
                            <thead>
                            <tr>
                                <th scope="col">{{ __('SL') }}</th>
                                <th scope="col">{{ __('Tag Manager ID') }}</th>
                                <th scope="col">{{ __('Status') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $key=>$value)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$value->code}}</td>
                                    <td>@if($value->status==1)<span class="badge bg-soft-success text-success">Active</span> @else <span class="badge bg-soft-danger text-danger">Inactive</span> @endif</td>
                                    <td>
                                        <div class="button-list">
                                            @if($value->status == 1)
                                                <form method="post" action="{{route('backend.tagmanagers.inactive')}}" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" value="{{$value->id}}" name="hidden_id">
                                                    <button type="button" class="btn btn-xs  btn-secondary waves-effect waves-light change-confirm"><i class="text-white fa fa-thumbs-down"></i></button></form>
                                            @else
                                                <form method="post" action="{{route('backend.tagmanagers.active')}}" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" value="{{$value->id}}" name="hidden_id">
                                                    <button type="button" class="btn btn-xs  btn-success waves-effect waves-light change-confirm"><i class="text-white fa fa-thumbs-up"></i></button>
                                                </form>
                                            @endif

                                            <a href="{{route('backend.tagmanagers.edit',$value->id)}}" class="btn btn-xs btn-primary waves-effect waves-light"><i class="text-white fa fa-pencil-square"></i></a>


                                            <form method="post" action="{{route('backend.tagmanagers.destroy')}}" class="d-inline">
                                                @csrf
                                                <input type="hidden" value="{{$value->id}}" name="hidden_id">
                                                <button type="submit" class="btn btn-xs  btn-danger waves-effect waves-light"><i class="text-white fa fa-trash"></i></button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

</div>
@endsection
