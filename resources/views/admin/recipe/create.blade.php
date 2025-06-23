@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
@endpush
@section('content')
    <!-- Content Header (Page header) -->
    <form class="form-horizontal" action="{{ route('sales.store') }}" method="POST" autocomplete="off">
        @csrf
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-9" style="display: flex;">
                        <h5>Create Sales & Recipes</h5>
                        <div style="margin-left: 15px;margin-right: 40px;margin-top: -5px;">
                            <select name="station_id" class="form-control " id="station_id" data-id=0 style="width: 130%;" onchange="getPriceLevel(this)">
                            @foreach ($stations as $station)
                                <option value="{{ $station->id }}" {{ $station->id == $station_id ? 'selected' : '' }}>
                                    {{ $station->name }}
                                </option>
                            @endforeach
                            </select>
                        </div>
                        <div style="margin-top: -5px;">
                            <select name="price_level_id" class="form-control " id="price_level_id">
                                @foreach ($price_levels as $key => $price_level)
                                    <option value="{{ $price_level->id }}"
                                        {{ $price_level->id == $price_level_id ? 'selected' : '' }}>
                                        {{ $price_level->level }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Sales & Recipes</a></li>
                            <li class="breadcrumb-item active">Create New</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- /.row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title text-bold"> Drink Mix Details </h3>
                            </div>
                            <div class="card-body">

                                <div class="form-group row">
                                    <label class="col-sm-1 col-form-label" for="name">Name</label>
                                    <div class="col-md-5">
                                        <input type="text" name="name" value="{{ old('name') }}"
                                            class="form-control" id="name" placeholder="Enter Name">
                                        <x-input-error for="name" />
                                    </div>

                                    <label class="col-sm-1 col-form-label" for="name">PLU #</label>
                                    <div class="col-md-5">
                                        <input type="text" name="plu" value="{{ old('plu') }}"
                                            class="form-control" id="plu" placeholder="Enter PLU">
                                        <x-input-error for="plu" />
                                    </div>
                                </div>

                                @include('includes.form-divider', [
                                    'title' => 'Prices & Tax',
                                    'controlls' => '',
                                ])

                                <div class="form-group prices-container">
                                    @foreach($price_levels as $key => $price_level)
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ $price_level->level }}</label>
                                        <div class="col-md-3">
                                            <input type="number" name="prices[{{ $price_level->id }}]" class='form-control'/>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <div id="div1">
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="name">Sales Tax (%)</label>
                                    <div class="col-md-3">
                                        <input type="number" name="tax" value="0" class="form-control"
                                            id="tax" placeholder="Enter Tax %" required>
                                    </div>
                                </div>

                                @include('includes.form-divider', [
                                    'title' => 'Ingredients',
                                    'controlls' =>
                                        '<button type="button" class="btn btn-sm" id="add-ingredient-btn"><i class="fas fa-plus-circle text-primary"></i></button>',
                                ])

                                <div class="form-row ingredient-row align-items-end">
                                    <div class="col-md-4 mb-3">
                                        <label for="name">Name</label>
                                        <select name="item_name[]" class="form-control select2-d item_name" required>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="name">Quantity</label>
                                        <input type="number" name="qty[]" class="form-control qty" placeholder="Quantity" required>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="name">Unit</label>
                                        <select name="package[]" class="form-control package" style="width:100%;">
                                        </select>
                                        <input type="hidden" class="package_text" name="package_text[]"/>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <button type="button" class="btn btn-outline-dark remove-ingredient-btn">
                                            <i class="far fa-times-circle"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                                <button type="button" class="btn btn-sm btn-default">
                                    <a href="{{ url('sales') }}">Cancel</a></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

    </form>
    <!-- /.content -->
    <template id="ingredient_row">
        <div class="form-row ingredient-row align-items-end">
            <div class="col-md-4 mb-3">
                <label for="name">Name</label>
                <select name="item_name[]" class="form-control select2-d item_name" required>
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <label for="name">Quantity</label>
                <input type="number" name="qty[]" class="form-control qty" placeholder="Quantity" required>
            </div>
            <div class="col-md-2 mb-3">
                <label for="name">Unit</label>
                <select name="package[]" class="form-control package" style="width:100%;">
                </select>
                <input type="hidden" class="package_text" name="package_text[]"/>
            </div>
            <div class="col-md-2 mb-3">
                <button type="button" class="btn btn-outline-dark remove-ingredient-btn">
                    <i class="far fa-times-circle"></i>
                </button>
            </div>
        </div>
    </template>
@endsection

@push('script')
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script type="text/javascript">
    var uoms = <?php echo json_encode(GlobalConstants::WEIGHT_UOM); ?>;
    $(function() {
        $(".select2-d").select2({
            'placeholder': 'Search Item',
            ajax: {
                url: '/ajax/item/fetchall'
            }
        });

        $("#add-ingredient-btn").on("click", function() {
            let clone_data = $("#ingredient_row").html();
            $(".card-body").append(clone_data);
            $(".ingredient-row:last").find(".select2-d").select2({
                'placeholder': 'Search Item',
                ajax: {
                    url: '/ajax/item/fetchall'
                }
            });
        });

        $(".card-body").on("click", ".remove-ingredient-btn", function() {
            if ($(".card-body .form-row").length > 1) {
                $(this).parents(".form-row").remove();
            } else {
                alert("You can`t remove the last line.");
            }
        });
    });

    $(document).on('change','.item_name', function(){
        let $this = $(this)
        var itemid = $(this).val()
        $.ajax({
            url: "{{ route('item.getItemSizesPackages') }}",
            type: "get",
            dataType: "json",
            data: {
                itemid: itemid
            },
            success: function(response) {
                let package = $this.parents('.ingredient-row').find('.package')
                let package_text = $this.parents('.ingredient-row').find('.package_text')
                package.empty()
                $.each(response.item_size, function(index, item_size) {
                    let initial_value = item_size.countable_unit + item_size.countable_size;
                    $.each(item_size.item_package, function(idx, item_package) {
                        if (idx == 0) {
                            let text_option = initial_value + ' ' + item_package.unit_from +
                                '(' + item_package.package_barcode + ')'
                            package.append(`<option value="${item_package.id}">
                                               ${text_option}
                                          </option>`);
                        } else {
                            let text_value = item_package.qty + ' x ' + initial_value
                            let text_option = text_value + ' ' + item_package.unit_from +
                                '(' + item_package.package_barcode + ')'
                            package.append(`<option value="${item_package.id}">
                                               ${text_option}
                                          </option>`);
                        }
                    })
                })
                $.each(uoms, function(key, val) {
                    package.append($('<option>', {
                        value: key,
                        text: val
                    }));
                });
                package_text.val(package.find(":selected").text())
            }
        })
    })

    $(document).on('change','.package', function(){
        $(this).siblings('.package_text').val($(this).find(':selected').text().trim())
    })

    function getPriceLevel(ele){
        var station_id=ele.value;
        var url=`/ajax/${station_id}/get/price_level`;
        $.ajax({
            url:url,
            method: 'GET',
            success:function(result){
                if(result.price_levels.length){
                    option='';
                    divHtml='';
                    $(result.price_levels).each((i, price_level)=>{
                        option+=`<option value="${price_level.id}">${price_level.level}</option>`;
                        divHtml+=`<div class="form-group row">
                                <label class="col-sm-2 col-form-label">${price_level.level}</label>
                                    <div class="col-md-3">
                                        <input type="number" name="prices[${price_level.id}]" class="form-control" value="">
                                    </div>
                              </div>`;
                    })
                    $('#price_level_id').html(option);
                    $('.prices-container').html(divHtml);
                }
                
            }
        })
    }
</script>
@endpush
