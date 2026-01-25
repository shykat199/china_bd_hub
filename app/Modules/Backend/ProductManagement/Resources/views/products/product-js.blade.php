<script>
    let count = @json($count ?? 0);

    $('.another-vAreation').on('click', function() {
        count++
        var inputs = `<div class="input-group mb-4 d-flex align-items-center">
                            <button type="button" class="btn btn-danger input-group-text btn-sm text-light remove-row"><i class="fas fa-trash d-inline-block mt-1"></i></button>
                            <select name="colors_new[]" class="form-control color-select">
                                <option value="">-{{ __('Select Color') }}-</option>
                                @foreach ($colors as $key => $color)
                                    <option value="{{ $color->id }}">{{ $color->name }}</option>
                                @endforeach
                            </select>
                            <select name="sizes_new[]" class="form-control">
                                <option value="">-{{ __('Select Size') }}-</option>
                                @foreach ($sizes as $key => $sz)
                                    <option value="{{ $sz->id }}">{{ $sz->name }}</option>
                                @endforeach
                            </select>
                            <input type="number" class="form-control variant-qty" placeholder="Enter quantity" name="quantities_new[]">
                            <div>
                                <label for="variantImage${count}">
                                    <img src="{{ asset('dummy-image-square.jpg') }}" alt="Choose Image" width="80" height="160" style="border-radius: 4px; margin: 3px">
                                </label>
                                <input id="variantImage${count}" type="file" class="form-control d-none" name="variant_image_new[]" onchange="showImage(event)">
                            </div>
                        </div>`;

        $('.vAreants').append(inputs);
        initColorSelect2()
    })

    function initColorSelect2() {
        $('.color-select').select2({
            tags: true,
            placeholder: 'Select or add color',
            createTag: function (params) {
                const term = $.trim(params.term);
                if (term === '') return null;

                return {
                    id: term,
                    text: term,
                    newTag: true
                };
            }
        });
    }
    initColorSelect2();

    $(document).on('click', '.remove-row', function() {
        var productStockId = $(this).data("product_stock_id");
        var button = $(this)
        if (productStockId) {
            var url = `/admin/variants/delete-variant/${productStockId}`
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                    url: url,
                    type: "delete",
                    dataType: "json",
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(res) {
                        if (res.status) {
                            notification('success', res.message);
                            button.parent('.input-group').remove();
                        } else {
                            notification('error', res.message);
                        }
                    }
                });
                }
            })

        } else {
            button.parent('.input-group').remove();
        }
    })
</script>
