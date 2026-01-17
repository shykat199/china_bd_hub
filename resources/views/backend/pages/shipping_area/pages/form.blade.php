<div class="row">

    <div class="col-lg-3">
        <p>{{__('Name of Area')}}</p>
    </div>
    <div class="col-lg-7">
        <div class="input-group">
            <input name="name" type="text" required
                   class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                   value="@if($shipping_area->name){{$shipping_area->name}}@else{{ old('name') }}@endif"
                   placeholder="Color Name">
            @error('name')
            <div class="error invalid-feedback" id="name-error" for="name">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-3">
        <p>{{__('Shipping Charge')}}</p>
    </div>
    <div class="col-lg-7">
        <div class="input-group">
            <input type="number" name="charge" class="form-control" value="@if($shipping_area->charge){{$shipping_area->charge}}@else{{ old('charge') }}@endif">
        </div>
    </div>
    <div class="col-lg-3">
        <p>{{__('Status')}}</p>
    </div>
    <div class="col-lg-7">
        <div class="input-group">
            <select name="status"
                    class="form-select form-control{{ $errors->has('status') ? ' is-invalid' : '' }}">
                <option value="1" @if($shipping_area->status==1) selected @endif>{{__('Active') }}</option>
                <option value="0" @if($shipping_area->status==0) selected @endif >{{__('Inactive')}}</option>
            </select>
    </div>
</div>

@push('js')
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {

                // validate form on keyup and submit
                $("#pageForm").validate({
                    ignore: ".note-editor *"
                });

                $('#editor').summernote({
                    tabsize: 2,
                    height: 120,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'underline', 'clear']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['codeview', 'help']]
                    ]
                })
            });
        })(jQuery);

    </script>
@endpush
