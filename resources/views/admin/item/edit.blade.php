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
                    <h5>Update Item</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Item</a></li>
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
                <div class="col-md-12">
                    <form class="form-horizontal form-validation" action="{{ route('item.update', $item->id) }}"
                        method="POST" autocomplete="off">
                        @csrf
                        @method('PUT')
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        <a class="d-block w-100" data-toggle="collapse" href="#collapseOne"
                                            aria-expanded="true" style="color:#000">
                                            Item
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="collapse show"  style="">
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-sm-8 offset-sm-2">
                                                <div class="form-group">
                                                    <label for="">Name</label>
                                                    <input type="text" name="name"
                                                        value="{{ old('name') ? old('name') : $item->name }}"
                                                        class="form-control" id="name" placeholder="" required>
                                                </div>
                                            </div>

                                            <div class="col-sm-8 offset-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="" for="">Class</label>
                                                            <select class="form-control" name="class_id" id="class_id"
                                                                style="width:100%;" onchange="changeClasses(this.value)"
                                                                required>
                                                                @foreach ($classes as $class)
                                                                    <option value="{{ $class->id }}"
                                                                        {{ $class->id == old('class_id') || $class->id == $item->class_id ? 'selected' : '' }}>
                                                                        {{ $class->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="" for="name">Category</label>
                                                            <select class="form-control" name="category_id" id="category_id"
                                                                style="width:100%;">
                                                                @if($categories->count()==0)<option value="0">Default</option>@endif
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}"
                                                                        {{ $category->id == old('category_id') || $category->id == $item->category_id ? 'selected' : '' }}>
                                                                        {{ $category->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-8 offset-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="" for="">Quality</label>
                                                            <select class="form-control" name="quality_id" id="quality_id"
                                                                style="width:100%;">
                                                                @if($qualities->count()==0)<option value="0">Default</option>@endif
                                                                @foreach ($qualities as $quality)
                                                                    <option value="{{ $quality->id }}"
                                                                        {{ $quality->id == old('quality_id') || $quality->id == $item->quality_id ? 'selected' : '' }}>
                                                                        {{ $quality->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="" for="">Barcode</label>
                                                            <input type="text" name="barcode"
                                                                value="{{ old('barcode') ? old('barcode') : $item_package->package_barcode }}"
                                                                class="form-control" id="barcode" placeholder="" required
                                                                readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card" id="div7" style="display: none;">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        <a class="d-block w-100" data-toggle="collapse" href="#collapseSix"
                                            aria-expanded="true" style="color:#000">
                                            Count / Weight
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseSix" class="collapse show"  style="">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-8 offset-sm-2">

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label class="" for="">When performing inventory
                                                                on this item, I will:</label>
                                                            <div class="row" style="width: 100% !important;">
                                                                <div class="col-sm-12"
                                                                    style="border: 1px solid #ced4da;
                                                                        border-radius: .25rem;
                                                                        padding: .375rem .75rem;
                                                                        margin-left: 7px;
                                                                        margin-top: 3px">
                                                                    <input type="radio" name="quantification"
                                                                        id="count_only" value="no" onclick="show5()"
                                                                        {{ ($item_size->count()&&$item_size->quantification == 'no') ? 'checked' : '' }} />
                                                                    Count Only
                                                                </div>
                                                                <div class="col-sm-12"
                                                                    style="border: 1px solid #ced4da;
                                                                        border-radius: .25rem;
                                                                        padding: .375rem .75rem;
                                                                        margin-left: 7px;
                                                                        margin-top: 15px">
                                                                    <input type="radio" name="quantification"
                                                                        id="count_weight" value="yes"
                                                                        onclick="show6()"
                                                                        {{ ($item_size->count() && $item_size->quantification == 'yes') ? 'checked' : '' }} />
                                                                    Count and Weight
                                                                </div>
                                                                <x-input-error for="quantification" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="card" id="div8" style="display: none;">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        <a class="d-block w-100" data-toggle="collapse" href="#collapseTree"
                                            aria-expanded="true" style="color:#000">
                                            Density
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTree" class="collapse show"  style="">
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-sm-8 offset-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label class="" for="">Density
                                                                calculated</label>
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <input type="number" step="any" name="density"
                                                                        value="{{ old('density') ? old('density') : ($item_size->count() ? $item_size->density : '') }}"
                                                                        class="form-control" id="density"
                                                                        placeholder="">
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <select class="form-control" name="density_m_unit"
                                                                        id="density_weight_id" style="width:100%;"d>
                                                                        @foreach ($DENSITY_WEIGHT_ID as $key => $density_m_unit)
                                                                            <option value="{{ $key }}"
                                                                                {{ ($item_size->count() && $item_size->density_m_unit == $key) ? 'selected' : '' }}>
                                                                                {{ $density_m_unit }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <select class="form-control" name="density_v_unit"
                                                                        id="density_unit_id" style="width:100%;">
                                                                        @foreach ($DENSITY_UNIT_ID as $key => $density_v_unit)
                                                                            <option value="{{ $key }}"
                                                                                {{ ($item_size->count() && $item_size->density_v_unit == $key) ? 'selected' : '' }}>
                                                                                {{ $density_v_unit }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        <a class="d-block w-100" data-toggle="collapse" href="#collapseFive"
                                            aria-expanded="true" style="color:#000">
                                            Summary
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseFive" class="collapse show"  style="">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-8 offset-sm-2">

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <h4 class="d-block w-100" for="" id="summary_title">
                                                            </h4>
                                                        </div>
                                                        <div class="form-group">
                                                            <div>
                                                                <label class="" for="">Class : </label>
                                                                <span class="" for=""
                                                                    id="summary_class"></span>
                                                            </div>
                                                            <div>
                                                                <label class="" for="">Category : </label>
                                                                <span class="" for=""
                                                                    id="summary_category"></span>
                                                            </div>
                                                            <div>
                                                                <label class="" for="">Quality : </label>
                                                                <span class="" for=""
                                                                    id="summary_quality"></span>
                                                            </div>
                                                            <div>
                                                                <label class="" for="">Inventory Method :
                                                                </label>
                                                                <span class="" for=""
                                                                    id="summary_inventory_method"></span>
                                                            </div>

                                                            <div>
                                                                <label class="" for="">Density : </label>
                                                                <span class="" for=""
                                                                    id="summary_density"></span>
                                                            </div>

                                                        </div>
                                                        <div class="form-group">
                                                            <h4 class="d-block w-100" for=""
                                                                id="update_summary_title">
                                                            </h4>
                                                        </div>
                                                        <div class="form-group">
                                                            <div>
                                                                <label class="" for="">Class : </label>
                                                                <span class="" for=""
                                                                    id="update_summary_class"></span>
                                                            </div>
                                                            <div>
                                                                <label class="" for="">Category : </label>
                                                                <span class="" for=""
                                                                    id="update_summary_category"></span>
                                                            </div>
                                                            <div>
                                                                <label class="" for="">Quality : </label>
                                                                <span class="" for=""
                                                                    id="update_summary_quality"></span>
                                                            </div>
                                                            <div>
                                                                <label class="" for="">Inventory Method :
                                                                </label>
                                                                <span class="" for=""
                                                                    id="update_summary_inventory_method"></span>
                                                            </div>

                                                            <div>
                                                                <label class="" for="">Density : </label>
                                                                <span class="" for=""
                                                                    id="update_summary_density"></span>
                                                            </div>

                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group row" style="display: none;">
                                <label class="col-sm-2 col-form-label" for=""> </label>
                                <div class="col-md-6">
                                    <input type="number" name="user_id" class="form-control" id="user_id"
                                        value="{{ old('user_id') ? old('user_id') : $item->user_id }}">
                                </div>
                            </div>

                            <div class="form-group row" style="display: none;">
                                <label class="col-sm-2 col-form-label" for=""> </label>
                                <div class="col-md-6">
                                    <input type="number" name="period_id" class="form-control" id="period_id"
                                        value="{{ old('period_id') ? old('period_id') : $item->period_id }}">
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                                <button type="button" class="btn btn-sm btn-default"><a
                                        href="{{ url('item') }}">Cancel</a></button>
                            </div>
                    </form>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
@push('script')
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            $('.packagename').change(function() {
                $(this).closest('.package').find('.sizepercase').val($('option:selected', this).data(
                    'name'));
            });


            $('.clientType').change(function() {
                $(this).closest('tr').find('.clientAmt').val($('option:selected', this).data('price'));
            });
        })

        $(document).ready(function() {
            var item_size = <?php echo $item_size; ?>;

            var class_type_id = $("#class_id").val();
            var url = "<?php echo route('check-type'); ?>";
            var token = $('input[name="_token"]').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': token
                }
            });
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    id: class_type_id
                },
                success: function(result) {
                    if (result.type == "Beer") {
                        $("#div7").css("display", "none");
                        $("#div8").css("display", "none");
                        $("#summary_inventory_method").html("Count Only");
                        $("#update_summary_inventory_method").html("Count Only");
                    } else if (result.type == "Miscellaneous") {
                        $("#div7").css("display", "block");
                        if (item_size.quantification == 'yes') {
                            $("#div8").css("display", "block");
                            $("#summary_inventory_method").html("Count & Weight");
                            $("#update_summary_inventory_method").html("Count & Weight");
                        } else {
                            $("#div8").css("display", "none");
                            $("#summary_inventory_method").html("Count Only");
                            $("#update_summary_inventory_method").html("Count Only");
                        }
                    } else {
                        $("#div7").css("display", "none");
                        $("#div8").css("display", "block");
                        $("#summary_inventory_method").html("Count & Weight");
                        $("#update_summary_inventory_method").html("Count & Weight");
                    }
                }
            });


            $("#summary_title").html("UPDATE " + $("#name").val());
            $("#summary_class").html($("#class_id").find(":selected").text());
            $("#summary_category").html($("#category_id").find(":selected").text());
            $("#summary_quality").html($("#quality_id").find(":selected").text());


            $("#update_summary_title").html("TO " + $("#name").val());
            $("#name").keyup(function() {
                var name = $(this).val();
                $("#update_summary_title").html("TO " + name);
            });

            $("#update_summary_class").html($("#class_id").find(":selected").text());
            $("#class_id").on("change", function() {
                $("#update_summary_class").html($("#class_id").find(":selected").text());
                var class_type_id = $(this).val();
                checkClassType(class_type_id);
            });

            $("#update_summary_category").html($("#category_id").find(":selected").text());

            $("#category_id").on("change", function() {
                $("#update_summary_category").html($("#category_id").find(":selected").text());
            });

            $("#update_summary_quality").html($("#quality_id").find(":selected").text());

            $("#quality_id").on("change", function() {
                $("#update_summary_quality").html($("#quality_id").find(":selected").text());
            });

            var class_value = $("#class_id").find(":selected").text();

            if (class_value.includes("Beer") || class_value.includes("Coolers")) {
                $("#update_summary_density").html("None");
                $("#summary_density").html("None");
            } else if (class_value.includes("Miscellaneous")) {
                if (item_size.quantification == 'yes') {
                    $("#update_summary_density").html(
                        $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                    );
                    $("#summary_density").html(
                        $("#density").val() +
                        " " +
                        $("#density_weight_id").val() +
                        " / " +
                        $("#density_unit_id").val()
                    );
                } else {
                    $("#update_summary_density").html("None");
                    $("#summary_density").html("None");
                }
            } else {
                $("#update_summary_density").html(
                    $("#density").val() +
                    " " +
                    $("#density_weight_id").val() +
                    " / " +
                    $("#density_unit_id").val()
                );
                $("#summary_density").html(
                    $("#density").val() +
                    " " +
                    $("#density_weight_id").val() +
                    " / " +
                    $("#density_unit_id").val()
                );
            }

            $("#density").keyup(function() {
                console.log("$(this).val()>> ", $(this).val())
                $("#update_summary_density").html(
                    $(this).val() +
                    " " +
                    $("#density_weight_id").val() +
                    " / " +
                    $("#density_unit_id").val()
                );
            });

            $("#density_weight_id").on("change", function() {
                $("#update_summary_density").html(
                    $("#density").val() +
                    " " +
                    $(this).val() +
                    " / " +
                    $("#density_unit_id").val()
                );
            });

            $("#density_unit_id").on("change", function() {
                $("#update_summary_density").html(
                    $("#density").val() +
                    " " +
                    $("#density_weight_id").val() +
                    " / " +
                    $(this).val()
                );
            });

        });

        function show5() {
            $("#summary_density").html("None");
            $("#update_summary_density").html("None");
            document.getElementById("div8").style.display = "none";
        }

        function show6() {
            $("#summary_density").html(
                $("#density").val() +
                " " +
                $("#density_weight_id").val() +
                " / " +
                $("#density_unit_id").val()
            );
            $("#update_summary_density").html(
                $("#density").val() +
                " " +
                $("#density_weight_id").val() +
                " / " +
                $("#density_unit_id").val()
            );
            document.getElementById("div8").style.display = "block";
        }

        function changeClasses(id) {
            var url = "<?php echo route('check-category'); ?>";
            var url1 = "<?php echo route('check-quality'); ?>";
            var token = $('input[name="_token"]').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': token
                }
            });
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    id: id
                },
                success: function(result) {
                    if (result.length > 0) {
                        $('#category_id').empty();
                        
                        $.each(result, function(key, val) {
                            $("#category_id").append($('<option>', {
                                value: val.id,
                                text: val.name
                            }));
                        });

                    } else {
                        $('#category_id').empty();
                        $("#category_id").append($('<option>', {
                            value: 0,
                            text: "Default"
                        }));
                    }

                }
            })
            $.ajax({
                type: "POST",
                url: url1,
                data: {
                    id: id
                },
                success: function(result) {
                    if (result.length > 0) {
                        $('#quality_id').empty();
                       
                        $.each(result, function(key, val) {
                            $("#quality_id").append($('<option>', {
                                value: val.id,
                                text: val.name
                            }));
                        });

                    } else {
                        $('#quality_id').empty();
                        $("#quality_id").append($('<option>', {
                            value: 0,
                            text: "Default"
                        }));
                    }

                }
            })
        }

        function checkClassType(id) {
            var url = "<?php echo route('check-type'); ?>";
            var token = $('input[name="_token"]').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': token
                }
            });
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    id: id
                },
                success: function(result) {
                    console.log("checkClassType result>> ", result);
                    if (result.type == "Beer") {
                        $("#div7").css("display", "none");
                        $("#div8").css("display", "none");
                        $("#summary_inventory_method").html("Count Only");
                    } else if (result.type == "Miscellaneous") {
                        $("#div7").css("display", "block");
                        if (item_size.quantification == 'yes') {
                            $("#div8").css("display", "block");
                            $("#summary_inventory_method").html("Count & Weight");
                            $("#update_summary_inventory_method").html("Count & Weight");
                        } else {
                            $("#div8").css("display", "none");
                            $("#summary_inventory_method").html("Count Only");
                            $("#update_summary_inventory_method").html("Count Only");
                        }
                    } else {
                        $("#div7").css("display", "none");
                        $("#div8").css("display", "block");
                        $("#summary_inventory_method").html("Count & Weight");
                    }
                }
            });
        }
    </script>
@endpush
