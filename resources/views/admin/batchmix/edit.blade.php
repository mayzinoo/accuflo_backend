@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href=" {{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
@endpush
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update Batch Mix</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Batch Mix</a></li>
                        <li class="breadcrumb-item active">Update</li>
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
                            <h3 class="card-title text-bold">{{ strtoupper($batchmix->name) }}</h3>
                        </div>
                        <form class="form-horizontal" action="{{ route('batchmix.update', $batchmix->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">

                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label" for="name">Name</label>
                                    <div class="col-md-4">
                                        <input type="text" name="name" value="{{ $batchmix->name }}"
                                            class="form-control" id="name" placeholder="Enter Name" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label" for="barcode">Barcode</label>
                                    <div class="col-md-4">
                                        <input type="text" name="barcode" value="{{ $batchmix->barcode }}"
                                            class="form-control" id="barcode" placeholder="Enter barcode" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label" for="code">Code</label>
                                    <div class="col-md-4">
                                        <input type="text" name="code" value="{{ $batchmix->code }}"
                                            class="form-control" id="code" placeholder="Enter code" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label" for="unit_des">Unit
                                        Description</label>
                                    <div class="col-md-4">
                                        <select name="unit_des" id="unit_des" class="form-control uom" style="width:100%;">
                                            @foreach ($BATCHMIX_UD as $key => $quality)
                                                <option value="{{ $key }}"
                                                    {{ $batchmix->unit_des == $key ? 'selected' : '' }}>
                                                    {{ $quality }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label" for="inventory_status">
                                        How will you perform inventory on this batch mix?</label>
                                    <div class="col-sm-2">
                                        <input type="radio" name="inventory_status" id="count_only" value="no"
                                            onclick="hideInventory()" style="margin-top: 13px;"
                                            {{ $batchmix->inventory_status == 'no' ? 'checked' : '' }} />
                                        Count Only
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="radio" name="inventory_status" id="count_weight" value="yes"
                                            onclick="showInventory()" style="margin-top: 13px;"
                                            {{ $batchmix->inventory_status == 'yes' ? 'checked' : '' }} />
                                        Count and Weigh
                                    </div>
                                </div>

                                <div id="div1" style="display: none">
                                    <div class="form-group row">
                                        <label class="col-sm-5 col-form-label" for="total_weight">
                                            Total Weight:</label>
                                        <div class="col-sm-2">
                                            <input type="number" name="total_weight" value="{{ $batchmix->total_weight }}"
                                                class="form-control" id="total_weight">
                                        </div>
                                        <div class="col-sm-2">
                                            <select name="total_weight_id" id="total_weight_id" class="form-control">
                                                @foreach ($BATCHMIX_WEIGHT_UNIT as $key => $quality)
                                                    <option value="{{ $key }}"
                                                        {{ $batchmix->total_weight_id == $key ? 'selected' : '' }}>
                                                        {{ $quality }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-5 col-form-label" for="container_weight">
                                            Empty Container Weight:</label>
                                        <div class="col-sm-2">
                                            <input type="number" name="container_weight"
                                                value="{{ $batchmix->container_weight }}" class="form-control"
                                                id="container_weight">
                                        </div>
                                        <div class="col-sm-2">
                                            <select name="container_weight_id" id="container_weight_id"
                                                class="form-control">
                                                @foreach ($BATCHMIX_WEIGHT_UNIT as $key => $quality)
                                                    <option value="{{ $key }}"
                                                        {{ $batchmix->container_weight_id == $key ? 'selected' : '' }}>
                                                        {{ $quality }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label" for="liquid_status">
                                        Is the final product of this batch mix in liquid form?</label>
                                    <div class="col-sm-2">
                                        <input type="radio" name="liquid_status" id="liquid_no" value="no"
                                            onclick="hideLiquidForm()" style="margin-top: 13px;"
                                            {{ $batchmix->liquid_status == 'no' ? 'checked' : '' }} />
                                        No
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="radio" name="liquid_status" id="liquid_yes" value="yes"
                                            onclick="showLiquidForm()" style="margin-top: 13px;"
                                            {{ $batchmix->liquid_status == 'yes' ? 'checked' : '' }} />
                                        Yes
                                    </div>
                                </div>

                                <div id="div2" style="display: none">
                                    <div class="form-group row">
                                        <label class="col-sm-5 col-form-label" for="total_volume">
                                            Total Volume:</label>
                                        <div class="col-sm-2">
                                            <input type="number" name="total_volume"
                                                value="{{ $batchmix->total_volume }}" class="form-control"
                                                id="total_volume" required>
                                        </div>
                                        <div class="col-sm-2">
                                            <select name="total_volume_id" id="total_volume_id" class="form-control">
                                                @foreach ($BATCHMIX_VOLUME_UNIT as $key => $quality)
                                                    <option value="{{ $key }}"
                                                        {{ $batchmix->total_volume_id == $key ? 'selected' : '' }}>
                                                        {{ $quality }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row" style="display: none">
                                        <label class="col-sm-5 col-form-label" for="">
                                            Density:</label>
                                        <div class="col-sm-4">
                                            <input type="number" name="density" value="{{ $batchmix->density }}"
                                                class="form-control" id="density" placeholder="" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row" style="display: none;">
                                        <label class="col-sm-5 col-form-label" for=""> </label>
                                        <div class="col-md-4">
                                            <input type="number" name="user_id" class="form-control" id="user_id"
                                                value="{{ $batchmix->user_id }}">
                                        </div>
                                    </div>

                                    <div class="form-group row" style="display: none;">
                                        <label class="col-sm-5 col-form-label" for=""> </label>
                                        <div class="col-md-4">
                                            <input type="number" name="period_id" class="form-control" id="period_id"
                                                value="{{ $batchmix->period_id }}">
                                        </div>
                                    </div>
                                </div>

                                @include('includes.form-divider', [
                                    'title' => 'Ingredients',
                                    'controlls' =>
                                        '<button type="button" class="btn btn-sm" id="add-ingredient-btn"><i class="fas fa-plus-circle text-primary"></i></button>',
                                ])

                                @foreach ($ingredients as $key => $ingredient)
                                    <div class="form-row {{ $key == 0 ? 'default' : '' }} align-items-end">
                                        <div class="col-md-4 mb-3">
                                            <label for="name">Name</label>
                                            <input type="hidden" name="ingredients[{{ $key }}][id]"
                                                class="id" value="{{ $ingredient->id }}" required>
                                            <select name="ingredients[{{ $key }}][item_name]"
                                                data-id="{{ $key }}" onchange="searchmakemodel(this)"
                                                class="form-control select2-d name">
                                                <option value="{{ $ingredient->item_id }}" selected>
                                                    {{ $ingredient->item_name }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="name">Quantity</label>
                                            <input type="number" value="{{ $ingredient->qty }}"
                                                name="ingredients[{{ $key }}][qty]" class="form-control qty"
                                                placeholder="Quantity">
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <label for="name">Unit</label>
                                            <select name="ingredients[{{ $key }}][uom]"
                                                id="uom_{{ $key }}" class="form-control uom"
                                                style="width:100%;">
                                                @php
                                                    $item_package_format = package_format($ingredient->item_id);
                                                @endphp
                                                @foreach ($item_package_format as $key => $format)
                                                    <option value="{{ $format->text }}"
                                                        data-unit_to="{{ $format->unit_to }}"
                                                        {{ $ingredient->uom == $format->text ? 'selected' : '' }}>
                                                        {{ $format->text }}
                                                    </option>
                                                @endforeach
                                                @foreach ($BATCHMIX_UOM as $key => $quality)
                                                    <option value="{{ $quality }}"
                                                        {{ $ingredient->uom == $quality ? 'selected' : '' }}>
                                                        {{ $quality }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 mb-3">
                                            <button type="button" class="btn btn-outline-dark remove-ingredient-btn"
                                                style="width: 74px;">
                                                <i class="far fa-times-circle"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                                <button type="button" class="btn btn-sm btn-default">
                                    <a href="{{ url('batchmix') }}">Cancel</a></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@push('script')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            let default_line = $(".default")
                .clone()
                .removeClass("default");
            let line_index = 1;

            $("#add-ingredient-btn").on("click", function() {
                let _default_line = default_line.clone();
                _default_line.find(".id").val("");
                _default_line.find(".name").html("")
                $(".card-body").append(_default_line);

                fix_line_index();
            });

            $(".card-body").on("click", ".remove-ingredient-btn", function() {
                if ($(".card-body .form-row").length > 1) {
                    $(this).parents(".form-row").remove();
                    fix_line_index();
                } else {
                    alert("You can`t remove the last line.");
            }
        });

        function fix_line_index() {
            $(".card-body .form-row").each(function(i, el) {
                $(el).find(".id").attr("name", "ingredients[" + i + "][id]");
                $(el).find(".name").attr("name", "ingredients[" + i + "][item_name]");
                $(el).find(".qty").attr("name", "ingredients[" + i + "][qty]");
                $(el).find(".uom").attr("name", "ingredients[" + i + "][uom]");
                $(el).find(".name").attr("data-id", i);
                $(el).find(".uom").attr("id", "uom_" + i);

                $(el).find(".select2-d").select2({
                    'placeholder': 'Search Item',
                    ajax: {
                        url: '/ajax/item/fetchall'
                    }
                });
            });
        }

        fix_line_index();
    });

    var change_uom_id;
    var result = <?php echo json_encode(GlobalConstants::BATCHMIX_UOM); ?>;

    function searchmakemodel(item) {
        var itemid = item.value;
        change_uom_id = $(item).attr("data-id");
        $.ajax({
            url: "{{ route('item.getItemSizesPackages') }}",
            type: "get",
            dataType: "json",
            data: {
                itemid: itemid
            },
            success: function(response) {
                $('#uom_' + change_uom_id).empty();
                $.each(response.item_size, function(index, item_size) {
                    let initial_value = item_size.countable_unit + item_size.countable_size;
                    $.each(item_size.item_package, function(idx, item_package) {
                        if (idx == 0) {
                            let text_option = item_package.unit_from + '(' + initial_value +
                                ') - ' +
                                item_package.package_barcode;
                            $('#uom_' + change_uom_id).append(`<option value="${text_option}">
                                        ${text_option}
                                        </option>`);
                        } else {
                            let text_value = item_package.qty + ' x ' + initial_value;
                            let text_option;
                            if (item_package.package_barcode != null) {
                                text_option = item_package.unit_to + '(' + text_value +
                                    ') - ' +
                                    item_package.package_barcode;
                            } else {
                                text_option = item_package.unit_to + '(' + text_value + ')';
                            }

                            $('#uom_' + change_uom_id).append(`<option value="${text_option}">
                                        ${text_option}
                                        </option>`);
                            }
                        })
                    })
                    $.each(result, function(key, val) {
                        $('#uom_' + change_uom_id).append($('<option>', {
                            value: val,
                            text: val
                        }));
                    });
                }
            })
        }

        $(document).ready(function() {
            var inventory_status = '{{ $batchmix->inventory_status }}'
            if (inventory_status == 'yes') {
                document.getElementById("div1").style.display = "block";
            } else {
                document.getElementById("div1").style.display = "none";
            }

            var liquid_status = '{{ $batchmix->liquid_status }}'
            if (liquid_status == 'yes') {
                document.getElementById("div2").style.display = "block";
            } else {
                document.getElementById("div2").style.display = "none";
            }
        });

        function hideInventory() {
            document.getElementById("div1").style.display = "none";
        }

        function showInventory() {
            document.getElementById("div1").style.display = "block";
        }

        function hideLiquidForm() {
            document.getElementById("div2").style.display = "none";
        }

        function showLiquidForm() {
            document.getElementById("div2").style.display = "block";
        }

        $(document).ready(function() {

            $("#total_volume").blur(function() {

                var total_weight = $("#total_weight").val();
                var container_weight = $("#container_weight").val();
                var total_volume = $("#total_volume").val();

                if ($("#total_weight_id").val() === '0' &&
                    $("#container_weight_id").val() === '0') {
                    var change_total_weight = total_weight; // g
                    var change_container_weight = container_weight; // g
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '1' &&
                    $("#container_weight_id").val() === '0') {
                    var change_total_weight = total_weight // kg 
                    var change_container_weight = container_weight *
                        0.001; // g to kg
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '2' &&
                    $("#container_weight_id").val() === '0') {
                    var change_total_weight = total_weight; // dry oz 
                    var change_container_weight = container_weight *
                        0.035274; // g to dry oz 
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '3' &&
                    $("#container_weight_id").val() === '0') {
                    var change_total_weight = total_weight; // lb 
                    var change_container_weight = container_weight *
                        0.0022046226; // g to lb
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '4' &&
                    $("#container_weight_id").val() === '0') {
                    var change_total_weight = total_weight; // mg
                    var change_container_weight = container_weight *
                        1000; // g to mg
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '0' &&
                    $("#container_weight_id").val() === '1') {
                    var change_total_weight = total_weight // g 
                    var change_container_weight = container_weight *
                        1000; // kg to g
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '1' &&
                    $("#container_weight_id").val() === '1') {
                    var change_total_weight = total_weight // kg 
                    var change_container_weight = container_weight; // kg
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '2' &&
                    $("#container_weight_id").val() === '1') {
                    var change_total_weight = total_weight; // dry oz 
                    var change_container_weight = container_weight *
                        35.27396; // kg to dry oz
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '3' &&
                    $("#container_weight_id").val() === '1') {
                    var change_total_weight = total_weight; // lb 
                    var change_container_weight = container_weight *
                        2.2046226218; // kg to lb
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '4' &&
                    $("#container_weight_id").val() === '1') {
                    var change_total_weight = total_weight; // mg
                    var change_container_weight = container_weight *
                        1000000; // kg to mg
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '0' &&
                    $("#container_weight_id").val() === '2') {
                    var change_total_weight = total_weight // g 
                    var change_container_weight = container_weight *
                        28.34952; // dry oz to g
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '1' &&
                    $("#container_weight_id").val() === '2') {
                    var change_total_weight = total_weight // kg 
                    var change_container_weight = container_weight *
                        0.02835; // dry oz to kg 
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '2' &&
                    $("#container_weight_id").val() === '2') {
                    var change_total_weight = total_weight; // dry oz 
                    var change_container_weight = container_weight; // dry oz
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '3' &&
                    $("#container_weight_id").val() === '2') {
                    var change_total_weight = total_weight; // lb 
                    var change_container_weight = container_weight *
                        0.0625; // dry oz to lb
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '4' &&
                    $("#container_weight_id").val() === '2') {
                    var change_total_weight = total_weight; // mg
                    var change_container_weight = container_weight *
                        28349.523125; // dry oz to mg
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '0' &&
                    $("#container_weight_id").val() === '3') {
                    var change_total_weight = total_weight // g 
                    var change_container_weight = container_weight *
                        453.6; // lb to g
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '1' &&
                    $("#container_weight_id").val() === '3') {
                    var change_total_weight = total_weight // kg 
                    var change_container_weight = container_weight *
                        0.4536; // lb to kg 
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '2' &&
                    $("#container_weight_id").val() === '3') {
                    var change_total_weight = total_weight; // dry oz 
                    var change_container_weight = container_weight *
                        16; // lb to dry oz 
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '3' &&
                    $("#container_weight_id").val() === '3') {
                    var change_total_weight = total_weight; // lb 
                    var change_container_weight = container_weight; // lb
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '4' &&
                    $("#container_weight_id").val() === '3') {
                    var change_total_weight = total_weight; // mg
                    var change_container_weight = container_weight *
                        453592.37; // lb to mg
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '0' &&
                    $("#container_weight_id").val() === '4') {
                    var change_total_weight = total_weight // g 
                    var change_container_weight = container_weight *
                        0.001; // mg to g
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '1' &&
                    $("#container_weight_id").val() === '4') {
                    var change_total_weight = total_weight // kg 
                    var change_container_weight = container_weight *
                        0.000001; // mg to kg
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '2' &&
                    $("#container_weight_id").val() === '4') {
                    var change_total_weight = total_weight; // dry oz 
                    var change_container_weight = container_weight *
                        0.000035274; // mg to dry oz 
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '3' &&
                    $("#container_weight_id").val() === '4') {
                    var change_total_weight = total_weight; // lb 
                    var change_container_weight = container_weight *
                        0.0000022046; // mg to lb
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '4' &&
                    $("#container_weight_id").val() === '4') {
                    var change_total_weight = total_weight; // mg
                    var change_container_weight = container_weight *
                        453592.37; // mg
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                }
            });

            $("#total_weight").blur(function() {

                var total_weight = $("#total_weight").val();
                var container_weight = $("#container_weight").val();
                var total_volume = $("#total_volume").val();

                if ($("#total_weight_id").val() === '0' &&
                    $("#container_weight_id").val() === '0') {
                    var change_total_weight = total_weight; // g
                    var change_container_weight = container_weight; // g
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '1' &&
                    $("#container_weight_id").val() === '0') {
                    var change_total_weight = total_weight // kg 
                    var change_container_weight = container_weight *
                        0.001; // g to kg
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '2' &&
                    $("#container_weight_id").val() === '0') {
                    var change_total_weight = total_weight; // dry oz 
                    var change_container_weight = container_weight *
                        0.035274; // g to dry oz 
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '3' &&
                    $("#container_weight_id").val() === '0') {
                    var change_total_weight = total_weight; // lb 
                    var change_container_weight = container_weight *
                        0.0022046226; // g to lb
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '4' &&
                    $("#container_weight_id").val() === '0') {
                    var change_total_weight = total_weight; // mg
                    var change_container_weight = container_weight *
                        1000; // g to mg
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '0' &&
                    $("#container_weight_id").val() === '1') {
                    var change_total_weight = total_weight // g 
                    var change_container_weight = container_weight *
                        1000; // kg to g
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '1' &&
                    $("#container_weight_id").val() === '1') {
                    var change_total_weight = total_weight // kg 
                    var change_container_weight = container_weight; // kg
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '2' &&
                    $("#container_weight_id").val() === '1') {
                    var change_total_weight = total_weight; // dry oz 
                    var change_container_weight = container_weight *
                        35.27396; // kg to dry oz
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '3' &&
                    $("#container_weight_id").val() === '1') {
                    var change_total_weight = total_weight; // lb 
                    var change_container_weight = container_weight *
                        2.2046226218; // kg to lb
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '4' &&
                    $("#container_weight_id").val() === '1') {
                    var change_total_weight = total_weight; // mg
                    var change_container_weight = container_weight *
                        1000000; // kg to mg
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '0' &&
                    $("#container_weight_id").val() === '2') {
                    var change_total_weight = total_weight // g 
                    var change_container_weight = container_weight *
                        28.34952; // dry oz to g
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '1' &&
                    $("#container_weight_id").val() === '2') {
                    var change_total_weight = total_weight // kg 
                    var change_container_weight = container_weight *
                        0.02835; // dry oz to kg 
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '2' &&
                    $("#container_weight_id").val() === '2') {
                    var change_total_weight = total_weight; // dry oz 
                    var change_container_weight = container_weight; // dry oz
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '3' &&
                    $("#container_weight_id").val() === '2') {
                    var change_total_weight = total_weight; // lb 
                    var change_container_weight = container_weight *
                        0.0625; // dry oz to lb
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '4' &&
                    $("#container_weight_id").val() === '2') {
                    var change_total_weight = total_weight; // mg
                    var change_container_weight = container_weight *
                        28349.523125; // dry oz to mg
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '0' &&
                    $("#container_weight_id").val() === '3') {
                    var change_total_weight = total_weight // g 
                    var change_container_weight = container_weight *
                        453.6; // lb to g
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '1' &&
                    $("#container_weight_id").val() === '3') {
                    var change_total_weight = total_weight // kg 
                    var change_container_weight = container_weight *
                        0.4536; // lb to kg 
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '2' &&
                    $("#container_weight_id").val() === '3') {
                    var change_total_weight = total_weight; // dry oz 
                    var change_container_weight = container_weight *
                        16; // lb to dry oz 
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '3' &&
                    $("#container_weight_id").val() === '3') {
                    var change_total_weight = total_weight; // lb 
                    var change_container_weight = container_weight; // lb
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '4' &&
                    $("#container_weight_id").val() === '3') {
                    var change_total_weight = total_weight; // mg
                    var change_container_weight = container_weight *
                        453592.37; // lb to mg
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '0' &&
                    $("#container_weight_id").val() === '4') {
                    var change_total_weight = total_weight // g 
                    var change_container_weight = container_weight *
                        0.001; // mg to g
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '1' &&
                    $("#container_weight_id").val() === '4') {
                    var change_total_weight = total_weight // kg 
                    var change_container_weight = container_weight *
                        0.000001; // mg to kg
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '2' &&
                    $("#container_weight_id").val() === '4') {
                    var change_total_weight = total_weight; // dry oz 
                    var change_container_weight = container_weight *
                        0.000035274; // mg to dry oz 
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '3' &&
                    $("#container_weight_id").val() === '4') {
                    var change_total_weight = total_weight; // lb 
                    var change_container_weight = container_weight *
                        0.0000022046; // mg to lb
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '4' &&
                    $("#container_weight_id").val() === '4') {
                    var change_total_weight = total_weight; // mg
                    var change_container_weight = container_weight *
                        453592.37; // mg
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                }
            });

            $("#container_weight").blur(function() {

                var total_weight = $("#total_weight").val();
                var container_weight = $("#container_weight").val();
                var total_volume = $("#total_volume").val();

                if ($("#total_weight_id").val() === '0' &&
                    $("#container_weight_id").val() === '0') {
                    var change_total_weight = total_weight; // g
                    var change_container_weight = container_weight; // g
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '1' &&
                    $("#container_weight_id").val() === '0') {
                    var change_total_weight = total_weight // kg 
                    var change_container_weight = container_weight *
                        0.001; // g to kg
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '2' &&
                    $("#container_weight_id").val() === '0') {
                    var change_total_weight = total_weight; // dry oz 
                    var change_container_weight = container_weight *
                        0.035274; // g to dry oz 
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '3' &&
                    $("#container_weight_id").val() === '0') {
                    var change_total_weight = total_weight; // lb 
                    var change_container_weight = container_weight *
                        0.0022046226; // g to lb
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '4' &&
                    $("#container_weight_id").val() === '0') {
                    var change_total_weight = total_weight; // mg
                    var change_container_weight = container_weight *
                        1000; // g to mg
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '0' &&
                    $("#container_weight_id").val() === '1') {
                    var change_total_weight = total_weight // g 
                    var change_container_weight = container_weight *
                        1000; // kg to g
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '1' &&
                    $("#container_weight_id").val() === '1') {
                    var change_total_weight = total_weight // kg 
                    var change_container_weight = container_weight; // kg
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '2' &&
                    $("#container_weight_id").val() === '1') {
                    var change_total_weight = total_weight; // dry oz 
                    var change_container_weight = container_weight *
                        35.27396; // kg to dry oz
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '3' &&
                    $("#container_weight_id").val() === '1') {
                    var change_total_weight = total_weight; // lb 
                    var change_container_weight = container_weight *
                        2.2046226218; // kg to lb
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '4' &&
                    $("#container_weight_id").val() === '1') {
                    var change_total_weight = total_weight; // mg
                    var change_container_weight = container_weight *
                        1000000; // kg to mg
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '0' &&
                    $("#container_weight_id").val() === '2') {
                    var change_total_weight = total_weight // g 
                    var change_container_weight = container_weight *
                        28.34952; // dry oz to g
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '1' &&
                    $("#container_weight_id").val() === '2') {
                    var change_total_weight = total_weight // kg 
                    var change_container_weight = container_weight *
                        0.02835; // dry oz to kg 
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '2' &&
                    $("#container_weight_id").val() === '2') {
                    var change_total_weight = total_weight; // dry oz 
                    var change_container_weight = container_weight; // dry oz
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '3' &&
                    $("#container_weight_id").val() === '2') {
                    var change_total_weight = total_weight; // lb 
                    var change_container_weight = container_weight *
                        0.0625; // dry oz to lb
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '4' &&
                    $("#container_weight_id").val() === '2') {
                    var change_total_weight = total_weight; // mg
                    var change_container_weight = container_weight *
                        28349.523125; // dry oz to mg
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '0' &&
                    $("#container_weight_id").val() === '3') {
                    var change_total_weight = total_weight // g 
                    var change_container_weight = container_weight *
                        453.6; // lb to g
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '1' &&
                    $("#container_weight_id").val() === '3') {
                    var change_total_weight = total_weight // kg 
                    var change_container_weight = container_weight *
                        0.4536; // lb to kg 
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '2' &&
                    $("#container_weight_id").val() === '3') {
                    var change_total_weight = total_weight; // dry oz 
                    var change_container_weight = container_weight *
                        16; // lb to dry oz 
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '3' &&
                    $("#container_weight_id").val() === '3') {
                    var change_total_weight = total_weight; // lb 
                    var change_container_weight = container_weight; // lb
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '4' &&
                    $("#container_weight_id").val() === '3') {
                    var change_total_weight = total_weight; // mg
                    var change_container_weight = container_weight *
                        453592.37; // lb to mg
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '0' &&
                    $("#container_weight_id").val() === '4') {
                    var change_total_weight = total_weight // g 
                    var change_container_weight = container_weight *
                        0.001; // mg to g
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '1' &&
                    $("#container_weight_id").val() === '4') {
                    var change_total_weight = total_weight // kg 
                    var change_container_weight = container_weight *
                        0.000001; // mg to kg
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '2' &&
                    $("#container_weight_id").val() === '4') {
                    var change_total_weight = total_weight; // dry oz 
                    var change_container_weight = container_weight *
                        0.000035274; // mg to dry oz 
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '3' &&
                    $("#container_weight_id").val() === '4') {
                    var change_total_weight = total_weight; // lb 
                    var change_container_weight = container_weight *
                        0.0000022046; // mg to lb
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '4' &&
                    $("#container_weight_id").val() === '4') {
                    var change_total_weight = total_weight; // mg
                    var change_container_weight = container_weight *
                        453592.37; // mg
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                }
            });

            $("#total_weight_id").on("change", function() {
                var total_weight = $("#total_weight").val();
                var container_weight = $("#container_weight").val();
                var total_volume = $("#total_volume").val();

                if ($("#total_weight_id").val() === '0' &&
                    $("#container_weight_id").val() === '0') {
                    var change_total_weight = total_weight; // g
                    var change_container_weight = container_weight; // g
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '1' &&
                    $("#container_weight_id").val() === '0') {
                    var change_total_weight = total_weight // kg 
                    var change_container_weight = container_weight *
                        0.001; // g to kg
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '2' &&
                    $("#container_weight_id").val() === '0') {
                    var change_total_weight = total_weight; // dry oz 
                    var change_container_weight = container_weight *
                        0.035274; // g to dry oz 
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '3' &&
                    $("#container_weight_id").val() === '0') {
                    var change_total_weight = total_weight; // lb 
                    var change_container_weight = container_weight *
                        0.0022046226; // g to lb
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '4' &&
                    $("#container_weight_id").val() === '0') {
                    var change_total_weight = total_weight; // mg
                    var change_container_weight = container_weight *
                        1000; // g to mg
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '0' &&
                    $("#container_weight_id").val() === '1') {
                    var change_total_weight = total_weight // g 
                    var change_container_weight = container_weight *
                        1000; // kg to g
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '1' &&
                    $("#container_weight_id").val() === '1') {
                    var change_total_weight = total_weight // kg 
                    var change_container_weight = container_weight; // kg
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '2' &&
                    $("#container_weight_id").val() === '1') {
                    var change_total_weight = total_weight; // dry oz 
                    var change_container_weight = container_weight *
                        35.27396; // kg to dry oz
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '3' &&
                    $("#container_weight_id").val() === '1') {
                    var change_total_weight = total_weight; // lb 
                    var change_container_weight = container_weight *
                        2.2046226218; // kg to lb
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '4' &&
                    $("#container_weight_id").val() === '1') {
                    var change_total_weight = total_weight; // mg
                    var change_container_weight = container_weight *
                        1000000; // kg to mg
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '0' &&
                    $("#container_weight_id").val() === '2') {
                    var change_total_weight = total_weight // g 
                    var change_container_weight = container_weight *
                        28.34952; // dry oz to g
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '1' &&
                    $("#container_weight_id").val() === '2') {
                    var change_total_weight = total_weight // kg 
                    var change_container_weight = container_weight *
                        0.02835; // dry oz to kg 
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '2' &&
                    $("#container_weight_id").val() === '2') {
                    var change_total_weight = total_weight; // dry oz 
                    var change_container_weight = container_weight; // dry oz
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '3' &&
                    $("#container_weight_id").val() === '2') {
                    var change_total_weight = total_weight; // lb 
                    var change_container_weight = container_weight *
                        0.0625; // dry oz to lb
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '4' &&
                    $("#container_weight_id").val() === '2') {
                    var change_total_weight = total_weight; // mg
                    var change_container_weight = container_weight *
                        28349.523125; // dry oz to mg
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '0' &&
                    $("#container_weight_id").val() === '3') {
                    var change_total_weight = total_weight // g 
                    var change_container_weight = container_weight *
                        453.6; // lb to g
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '1' &&
                    $("#container_weight_id").val() === '3') {
                    var change_total_weight = total_weight // kg 
                    var change_container_weight = container_weight *
                        0.4536; // lb to kg 
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '2' &&
                    $("#container_weight_id").val() === '3') {
                    var change_total_weight = total_weight; // dry oz 
                    var change_container_weight = container_weight *
                        16; // lb to dry oz 
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '3' &&
                    $("#container_weight_id").val() === '3') {
                    var change_total_weight = total_weight; // lb 
                    var change_container_weight = container_weight; // lb
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '4' &&
                    $("#container_weight_id").val() === '3') {
                    var change_total_weight = total_weight; // mg
                    var change_container_weight = container_weight *
                        453592.37; // lb to mg
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '0' &&
                    $("#container_weight_id").val() === '4') {
                    var change_total_weight = total_weight // g 
                    var change_container_weight = container_weight *
                        0.001; // mg to g
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '1' &&
                    $("#container_weight_id").val() === '4') {
                    var change_total_weight = total_weight // kg 
                    var change_container_weight = container_weight *
                        0.000001; // mg to kg
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '2' &&
                    $("#container_weight_id").val() === '4') {
                    var change_total_weight = total_weight; // dry oz 
                    var change_container_weight = container_weight *
                        0.000035274; // mg to dry oz 
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '3' &&
                    $("#container_weight_id").val() === '4') {
                    var change_total_weight = total_weight; // lb 
                    var change_container_weight = container_weight *
                        0.0000022046; // mg to lb
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '4' &&
                    $("#container_weight_id").val() === '4') {
                    var change_total_weight = total_weight; // mg
                    var change_container_weight = container_weight *
                        453592.37; // mg
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                }
            });

            $("#container_weight_id").on("change", function() {

                var total_weight = $("#total_weight").val();
                var container_weight = $("#container_weight").val();
                var total_volume = $("#total_volume").val();

                if ($("#total_weight_id").val() === '0' &&
                    $("#container_weight_id").val() === '0') {
                    var change_total_weight = total_weight; // g
                    var change_container_weight = container_weight; // g
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '1' &&
                    $("#container_weight_id").val() === '0') {
                    var change_total_weight = total_weight // kg 
                    var change_container_weight = container_weight *
                        0.001; // g to kg
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '2' &&
                    $("#container_weight_id").val() === '0') {
                    var change_total_weight = total_weight; // dry oz 
                    var change_container_weight = container_weight *
                        0.035274; // g to dry oz 
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '3' &&
                    $("#container_weight_id").val() === '0') {
                    var change_total_weight = total_weight; // lb 
                    var change_container_weight = container_weight *
                        0.0022046226; // g to lb
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '4' &&
                    $("#container_weight_id").val() === '0') {
                    var change_total_weight = total_weight; // mg
                    var change_container_weight = container_weight *
                        1000; // g to mg
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '0' &&
                    $("#container_weight_id").val() === '1') {
                    var change_total_weight = total_weight // g 
                    var change_container_weight = container_weight *
                        1000; // kg to g
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '1' &&
                    $("#container_weight_id").val() === '1') {
                    var change_total_weight = total_weight // kg 
                    var change_container_weight = container_weight; // kg
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '2' &&
                    $("#container_weight_id").val() === '1') {
                    var change_total_weight = total_weight; // dry oz 
                    var change_container_weight = container_weight *
                        35.27396; // kg to dry oz
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '3' &&
                    $("#container_weight_id").val() === '1') {
                    var change_total_weight = total_weight; // lb 
                    var change_container_weight = container_weight *
                        2.2046226218; // kg to lb
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '4' &&
                    $("#container_weight_id").val() === '1') {
                    var change_total_weight = total_weight; // mg
                    var change_container_weight = container_weight *
                        1000000; // kg to mg
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '0' &&
                    $("#container_weight_id").val() === '2') {
                    var change_total_weight = total_weight // g 
                    var change_container_weight = container_weight *
                        28.34952; // dry oz to g
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '1' &&
                    $("#container_weight_id").val() === '2') {
                    var change_total_weight = total_weight // kg 
                    var change_container_weight = container_weight *
                        0.02835; // dry oz to kg 
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '2' &&
                    $("#container_weight_id").val() === '2') {
                    var change_total_weight = total_weight; // dry oz 
                    var change_container_weight = container_weight; // dry oz
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '3' &&
                    $("#container_weight_id").val() === '2') {
                    var change_total_weight = total_weight; // lb 
                    var change_container_weight = container_weight *
                        0.0625; // dry oz to lb
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '4' &&
                    $("#container_weight_id").val() === '2') {
                    var change_total_weight = total_weight; // mg
                    var change_container_weight = container_weight *
                        28349.523125; // dry oz to mg
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '0' &&
                    $("#container_weight_id").val() === '3') {
                    var change_total_weight = total_weight // g 
                    var change_container_weight = container_weight *
                        453.6; // lb to g
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '1' &&
                    $("#container_weight_id").val() === '3') {
                    var change_total_weight = total_weight // kg 
                    var change_container_weight = container_weight *
                        0.4536; // lb to kg 
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '2' &&
                    $("#container_weight_id").val() === '3') {
                    var change_total_weight = total_weight; // dry oz 
                    var change_container_weight = container_weight *
                        16; // lb to dry oz 
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '3' &&
                    $("#container_weight_id").val() === '3') {
                    var change_total_weight = total_weight; // lb 
                    var change_container_weight = container_weight; // lb
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '4' &&
                    $("#container_weight_id").val() === '3') {
                    var change_total_weight = total_weight; // mg
                    var change_container_weight = container_weight *
                        453592.37; // lb to mg
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                } else if ($("#total_weight_id").val() === '0' &&
                    $("#container_weight_id").val() === '4') {
                    var change_total_weight = total_weight // g 
                    var change_container_weight = container_weight *
                        0.001; // mg to g
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '1' &&
                    $("#container_weight_id").val() === '4') {
                    var change_total_weight = total_weight // kg 
                    var change_container_weight = container_weight *
                        0.000001; // mg to kg
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '2' &&
                    $("#container_weight_id").val() === '4') {
                    var change_total_weight = total_weight; // dry oz 
                    var change_container_weight = container_weight *
                        0.000035274; // mg to dry oz 
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '3' &&
                    $("#container_weight_id").val() === '4') {
                    var change_total_weight = total_weight; // lb 
                    var change_container_weight = container_weight *
                        0.0000022046; // mg to lb
                    var diff_weight =
                        parseInt(change_total_weight) - parseInt(
                            change_container_weight);
                    var final_weight =
                        parseInt(diff_weight) / parseInt(total_volume);
                    $("#density").val(final_weight.toFixed(2));
                } else if ($("#total_weight_id").val() === '4' &&
                    $("#container_weight_id").val() === '4') {
                    var change_total_weight = total_weight; // mg
                    var change_container_weight = container_weight *
                        453592.37; // mg
                    if (Number(change_total_weight) < Number(change_container_weight)) {
                        alert(
                            "Empty weight cannot be greater than or equal to total weight!"
                        );
                    } else {
                        var diff_weight =
                            parseInt(change_total_weight) - parseInt(
                                change_container_weight);
                        var final_weight =
                            parseInt(diff_weight) / parseInt(total_volume);
                        $("#density").val(final_weight.toFixed(2));
                    }
                }
            });

        });
    </script>
@endpush
