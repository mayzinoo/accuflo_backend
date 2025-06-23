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
                    <h5>Create Item</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Item</a></li>
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
                <div class="col-md-12">
                    <form class="form-horizontal form-validation" action="{{ route('item.store') }}" method="post" autocomplete="off">
                        @csrf

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        <a class="d-block w-100" data-toggle="collapse" href="#collapseOne"
                                            aria-expanded="true" style="color:#000">
                                            Item
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="collapse show" style="">
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-sm-8 offset-sm-2">
                                                <div class="form-group">
                                                    <label for="">Name</label>
                                                    <input type="text" name="name" value="{{ old('name') }}"
                                                        class="form-control" id="name" placeholder="" required>
                                                    <x-input-error for="name" />
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
                                                                    <option value="{{ $class->id }}">{{ $class->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <x-input-error for="class_id" />
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="" for="name">Category</label>
                                                            <select class="form-control" name="category_id" id="category_id"
                                                                style="width:100%;">
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}">
                                                                        {{ $category->name }}</option>
                                                                @endforeach
                                                            </select>
                                                            <x-input-error for="category_id" />
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
                                                                @foreach ($qualities as $quality)
                                                                    <option value="{{ $quality->id }}">{{ $quality->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <x-input-error for="quality_id" />
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="" for="">Barcode</label>
                                                            <input type="text" name="barcode"
                                                                value="{{ old('barcode') }}" class="form-control"
                                                                id="barcode" placeholder="" required>
                                                            <x-input-error for="barcode" />
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
                                            aria-expanded="true" style="color: #000">
                                            Count / Weight
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseSix" class="collapse show" style="">
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
                                                                        checked />
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
                                                                        onclick="show6()" />
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

                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo"
                                            aria-expanded="true" style="color:#000">
                                            Unit Size
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="collapse show" style="">
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-sm-8 offset-sm-2">
                                                <div class="form-group">
                                                    <label class="" for="">Description of each countable
                                                        item/unit</label>
                                                    <select class="form-control" name="unit_from" id="item_unit_id"
                                                        style="width:100%;" required>
                                                        @foreach ($units as $key => $unit)
                                                            <option value="{{ $key }}">{{ $unit }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <x-input-error for="unit_from" />
                                                </div>
                                            </div>

                                            <div class="col-sm-8 offset-sm-2" id="div5" style="display: none;">
                                                <label class="" for="">Are portions of this item/unit
                                                    served individually?</label>
                                                <div class="row" style="width: 100% !important;">
                                                    <div class="col-sm-12"
                                                        style="border: 1px solid #ced4da;
                                                                border-radius: .25rem;
                                                                padding: .375rem .75rem;
                                                                margin-left: 7px;
                                                                margin-top: 3px">
                                                        <input type="radio" name="sizeoption"
                                                            id="item_unit_portion_no" value="no" onclick="show4()" />
                                                        No
                                                    </div>
                                                    <div class="col-sm-12"
                                                        style="border: 1px solid #ced4da;
                                                                border-radius: .25rem;
                                                                padding: .375rem .75rem;
                                                                margin-left: 7px;
                                                                margin-top: 15px">
                                                        <input type="radio" name="sizeoption"
                                                            id="item_unit_portion_yes" value="yes" onclick="show3()"
                                                            checked />
                                                        Yes
                                                    </div>
                                                    <x-input-error for="unit_portion" />
                                                </div>
                                            </div>

                                            <div class="col-sm-8 offset-sm-2" id="div4"
                                                style="display: none;margin-top: 15px;">
                                                <div class="form-group">
                                                    <label class="" for="">Size of each countable
                                                        unit</label>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <input type="number" name="countable_unit"
                                                                value="{{ old('countable_unit') }}" class="form-control"
                                                                id="countable_unit" placeholder="">

                                                        </div>
                                                        <div class="col-sm-6">
                                                            <select class="form-control" name="countable_size"
                                                                id="countable_unit_id" style="width:100%;">
                                                                @foreach ($COUNTABLE_UNIT_ID as $key => $countable_unit_id)
                                                                    <option value="{{ $key }}">
                                                                        {{ $countable_unit_id }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-8 offset-sm-2" id="div6" style="display: none;">
                                                <div class="form-group">
                                                    <label class="" for="">Specify the empty weight</label>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <input type="number" name="empty_weight"
                                                                value="{{ old('empty_weight') }}" class="form-control"
                                                                id="empty_weight" placeholder="">
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <select class="form-control" name="empty_weight_size"
                                                                id="empty_weight_id" style="width:100%;">
                                                                @foreach ($EMPTY_WEIGHT_ID as $key => $empty_weight_id)
                                                                    <option value="{{ $key }}">
                                                                        {{ $empty_weight_id }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="" for="">Specify the full weight</label>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <input type="number" name="full_weight"
                                                                value="{{ old('full_weight') }}" class="form-control"
                                                                id="full_weight" placeholder="">
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <select class="form-control" name="full_weight_size"
                                                                id="full_weight_id" style="width:100%;">
                                                                @foreach ($FULL_WEIGHT_ID as $key => $full_weight_id)
                                                                    <option value="{{ $key }}">
                                                                        {{ $full_weight_id }}
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

                            <div class="card" id="div8" style="display: none;">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        <a class="d-block w-100" data-toggle="collapse" href="#collapseTree"
                                            aria-expanded="true" style="color:#000">
                                            Density
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTree" class="collapse show" style="">
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
                                                                        value="{{ old('density') }}" class="form-control"
                                                                        id="density" placeholder="" readonly>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <select class="form-control" name="density_m_unit"
                                                                        id="density_weight_id" style="width:100%;">
                                                                        @foreach ($DENSITY_WEIGHT_ID as $key => $density_weight_id)
                                                                            <option value="{{ $key }}">
                                                                                {{ $density_weight_id }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <select class="form-control" name="density_v_unit"
                                                                        id="density_unit_id" style="width:100%;">
                                                                        @foreach ($DENSITY_UNIT_ID as $key => $density_unit_id)
                                                                            <option value="{{ $key }}">
                                                                                {{ $density_unit_id }}
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
                                        <a class="d-block w-100" data-toggle="collapse" href="#collapseFour"
                                            aria-expanded="true" style="color:#000">
                                            Packaging
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseFour" class="collapse show" style="">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-8 offset-sm-2">

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <label class="" for="">Does this item come in a
                                                                package (ie. case, box, etc.)</label>
                                                            <div class="row" style="width: 100% !important;">
                                                                <div class="col-sm-12"
                                                                    style="border: 1px solid #ced4da;
                                                                        border-radius: .25rem;
                                                                        padding: .375rem .75rem;
                                                                        margin-left: 7px;
                                                                        margin-top: 3px">
                                                                    <input type="radio" name="package_status"
                                                                        id="package_yes" value="yes"
                                                                        onclick="show2()" />
                                                                    Yes
                                                                </div>
                                                                <div class="col-sm-12"
                                                                    style="border: 1px solid #ced4da;
                                                                        border-radius: .25rem;
                                                                        padding: .375rem .75rem;
                                                                        margin-left: 7px;
                                                                        margin-top: 15px">
                                                                    <input type="radio" name="package_status"
                                                                        id="package_no" value="no" onclick="show1()"
                                                                        checked />
                                                                    No
                                                                </div>
                                                                <x-input-error for="package_status" />
                                                            </div>
                                                        </div>

                                                        <div class="form-group" id="div1"
                                                            style="display: none; margin-top: 20px">

                                                            <div class="card package">
                                                                <div class="card-header">
                                                                    <label class="" for=""
                                                                        style="margin-bottom: 0px;">
                                                                        Package Definition</label>
                                                                    <span onclick="removerpkg(event)"
                                                                        style="float: right; font-size: 20px; cursor: pointer;">
                                                                        x
                                                                    </span>
                                                                </div>

                                                                <div class="card-body">
                                                                    <div class="form-group">
                                                                        <label class="" for="">Name</label>
                                                                        <select class="form-control packagename"
                                                                            name="package_name[]" id="package_name_0"
                                                                            data-id=0 style="width:100%;">
                                                                            <option data-name="BAG" value="BAG">BAG
                                                                            </option>
                                                                            <option data-name="BLOCK" value="BLOCK">BLOCK
                                                                            </option>
                                                                            <option data-name="BOX" value="BOX">BOX
                                                                            </option>
                                                                            <option data-name="CARTON" value="CARTON">
                                                                                CARTON</option>
                                                                            <option data-name="CASE" value="CASE">CASE
                                                                            </option>
                                                                            <option data-name="CRATE" value="CRATE">CRATE
                                                                            </option>
                                                                            <option data-name="LOAF" value="LOAF">LOAF
                                                                            </option>
                                                                            <option data-name="PACKAGE" value="PACKAGE">
                                                                                PACKAGE</option>
                                                                            <option data-name="TRAY" value="TRAY">TRAY
                                                                            </option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label class="" for="">Size</label>
                                                                        <div class="row">
                                                                            <div class="col-sm-9">
                                                                                <input type="text"
                                                                                    name="package_size[]"
                                                                                    value="{{ old('package_size.0', '') }}"
                                                                                    class="form-control"
                                                                                    id="package_size_0" data-id=0
                                                                                    placeholder="">
                                                                            </div>
                                                                            <div class="col-sm-3">
                                                                                <span class="size_per_case"
                                                                                    id="size_per_case_0" type="text"
                                                                                    name="size_per_case[]"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label class="" for="">Barcode of
                                                                            the Case</label>
                                                                        <input type="text" name="package_barcode[]"
                                                                            value="{{ old('package_barcode.0', '') }}"
                                                                            class="form-control" id="package_barcode_0"
                                                                            data-id=0 placeholder="">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div id="addMore" style="display: none; margin-top: 20px">
                                                            <button type="submit" class="btn btn-primary"
                                                                onclick="pkgcloneform(event)">
                                                                + </button>
                                                            <span>Specify another packaging size</span>
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
                                <div id="collapseFive" class="collapse show" style="">
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
                                                                <label class="" for="">Barcode : </label>
                                                                <span class="" for=""
                                                                    id="summary_barcode"></span>
                                                            </div>
                                                            <div>
                                                                <label class="" for="">Size : </label>
                                                                <span class="" for=""
                                                                    id="summary_size"></span>
                                                            </div>
                                                            <div id="div9" style="display: none;">
                                                                <label class="" for="">Empty Container
                                                                    Weight : </label>
                                                                <span class="" for=""
                                                                    id="summary_empty_weight"></span>
                                                            </div>
                                                            <div>
                                                                <label class="" for="">Density : </label>
                                                                <span class="" for=""
                                                                    id="summary_density"></span>
                                                            </div>
                                                            <div class="form-group" style="margin-top: 10px">
                                                                <h4 class="d-block w-100" for=""> Item Packaging
                                                                </h4>
                                                            </div>
                                                            <div class="form-group" id="div2"
                                                                style="display: block">
                                                                <label class="" for=""> This item does not
                                                                    come in packages </label>
                                                            </div>
                                                            <div class="form-group" id="div3" style="display: none">
                                                                <div class="row">
                                                                    <div class="col-sm-6">
                                                                        <label class="" for="">Size :
                                                                        </label>
                                                                        <span class="" for=""
                                                                            id="summary_package_size_0"></span>
                                                                    </div>
                                                                    <div class="col-sm-6">
                                                                        <label class="" for="">Barcode :
                                                                        </label>
                                                                        <span class="" for=""
                                                                            id="summary_package_barcode_0"></span>
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script>
        $(function() {
            $('.packagename').change(function() {
                $(this).closest('.package').find('.sizepercase').val($('option:selected', this).data(
                    'name'));
            });


            $('.clientType').change(function() {
                $(this).closest('tr').find('.clientAmt').val($('option:selected', this).data('price'));
            });
        })


        $(".form-validation").validate({
            rules: {
                countable_unit: {
                    required: function(element) {
                        return true;
                    }
                },
                empty_weight: {
                    required: function(element) {
                        var class_value = $("#class_id").find(":selected").text();
                        if (class_value.includes("Liquor")) {
                            return true;
                        }
                    }
                },
                full_weight: {
                    required: function(element) {
                        var class_value = $("#class_id").find(":selected").text();
                        if (class_value.includes("Liquor")) {
                            return true;
                        }
                    }
                }
            }
        });

        $(document).ready(function() {
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
                        $("#div4").css("display", "block");
                        $("#div5").css("display", "block");
                        $("#div6").css("display", "none");
                        $("#div7").css("display", "none");
                        $("#div8").css("display", "none");
                        $("#div9").css("display", "none");
                        $("#summary_inventory_method").html("Count Only");
                    } else if (result.type == "Miscellaneous") {
                        $("#div4").css("display", "block");
                        $("#div5").css("display", "block");
                        $("#div6").css("display", "none");
                        $("#div7").css("display", "block");
                        $("#div8").css("display", "none");
                        $("#div9").css("display", "none");
                        $("#summary_inventory_method").html("Count Only");
                    } else {
                        $("#div4").css("display", "block");
                        $("#div5").css("display", "none");
                        $("#div6").css("display", "block");
                        $("#div7").css("display", "none");
                        $("#div8").css("display", "block");
                        $("#div9").css("display", "block");
                        $("#summary_inventory_method").html("Count & Weight");
                    }
                }
            });
        });

        $("#item_unit_id").on("change", function() {
            var item_unit_id = $("#item_unit_id").val();
            for (let i = 0; i < key; i++) {
                var package_name = $("#package_name_" + i).val();
                $("#size_per_case_" + i).html(
                    item_unit_id + " per " + package_name
                );
                var package_size = $("#package_size_" + i).val();
                $("#summary_package_size_" + i).html(
                    package_size + " " + item_unit_id + " / " + package_name
                );
            }
        });

        var key = 1;

        function pkgcloneform(event) {
            event.preventDefault();
            var clone = `<div class="card package">
            <div class="card-header">
                <label class="" for=""
                    style="margin-bottom: 0px;">
                    Package Definition</label>
                <span onclick="removerpkg(event)"
                    style="float: right; font-size: 20px; cursor: pointer;">
                    x
                </span>
            </div>

            <div class="card-body">
                <div class="form-group">
                    <label class="" for="">Name</label>
                    <select class="form-control packagename" name="package_name[]" id="package_name_${key}" data-id="${key}"
                        style="width:100%;">
                        <option data-name="BAG" value="BAG">BAG</option>
                        <option data-name="BLOCK" value="BLOCK">BLOCK</option>
                        <option data-name="BOX" value="BOX">BOX</option>
                        <option data-name="CARTON" value="CARTON">CARTON</option>
                        <option data-name="CASE" value="CASE">CASE</option>
                        <option data-name="CRATE" value="CRATE">CRATE</option>
                        <option data-name="LOAF" value="LOAF">LOAF</option>
                        <option data-name="PACKAGE" value="PACKAGE">PACKAGE</option>
                        <option data-name="TRAY" value="TRAY">TRAY</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="" for="">Size</label>
                    <div class="row">
                        <div class="col-sm-9">
                            <input type="text"
                                name="package_size[]"
                                class="form-control" id="package_size_${key}" data-id="${key}"
                                placeholder="" >
                        </div>
                        <div class="col-sm-3">
                        <span class="size_per_case" id="size_per_case_${key}" type="text" name="size_per_case[]"
                            ></span> 
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="" for="">Barcode of
                        the Case</label>
                    <input type="text" name="package_barcode[]"
                        class="form-control" id="package_barcode_${key}" data-id="${key}"
                        placeholder="" >
                </div>
            </div>
            </div>`;

            var summaryClone = `<div class="row">
            <div class="col-sm-6">
                <label class="" for="">Size :
                </label>
                <span class="" for="" 
                    id="summary_package_size_${key}"></span>
            </div>
            <div class="col-sm-6">
                <label class="" for="">Barcode :
                </label>
                <span class="" for=""
                    id="summary_package_barcode_${key}"></span>
            </div>
            </div>`;

            $("#div1").append(clone);
            $("#div3").append(summaryClone);

            key++;
        }

        function removerpkg(event) {
            var target = $(event.target);
            var cl = $(".package").length;
            if (cl == 1) {
                alert("You can not remove");
            } else {
                target.parent().parent().remove();
                key--;
            }
        }

        $(document).ready(function() {
            $(document).on("change", "select[name^='package_name[]']", function() {
                var id = $(this).data("id");
                var package_name = $(this).val();
                var item_unit_id = $("#item_unit_id").val();
                $("#size_per_case_" + id).html(item_unit_id + " per " + package_name);
                var package_size = $("#package_size_" + id).val();
                $("#summary_package_size_" + id).html(
                    package_size + " " + item_unit_id + " / " + package_name
                );
            });

            $("#summary_class").html($("#class_id").find(":selected").text());

            $("#class_id").on("change", function() {
                $("#summary_class").html($("#class_id").find(":selected").text());
                var class_type_id = $(this).val();
                checkClassType(class_type_id);
            });

            $("#summary_category").html($("#category_id").find(":selected").text());

            $("#category_id").on("change", function() {
                $("#summary_category").html($("#category_id").find(":selected").text());
            });

            $("#summary_quality").html($("#quality_id").find(":selected").text());

            $("#quality_id").on("change", function() {
                $("#summary_quality").html($("#quality_id").find(":selected").text());
            });

            $("#summary_title").html($("#name").val());

            $("#name").keyup(function() {
                var name = $(this).val();
                $("#summary_title").html(name);
            });

            $("#summary_barcode").html($("#barcode").val());

            $("#barcode").keyup(function() {
                var barcode = $(this).val();
                $("#summary_barcode").html(barcode);
            });

            $("#summary_size").html(
                $("#countable_unit").val() +
                " " +
                $("#countable_unit_id").val() +
                " / " +
                $("#item_unit_id").val()
            );

            $("#countable_unit").keyup(function() {
                var countable_unit = $(this).val();
                var countable_unit_id = $("#countable_unit_id").val();
                var item_unit_id = $("#item_unit_id").val();
                $("#summary_size").html(
                    countable_unit + " " + countable_unit_id + " / " + item_unit_id
                );
            });

            $("#countable_unit_id").on("change", function() {
                var countable_unit = $("#countable_unit").val();
                var countable_unit_id = $(this).val();
                var item_unit_id = $("#item_unit_id").val();
                $("#summary_size").html(
                    countable_unit + " " + countable_unit_id + " / " + item_unit_id
                );
            });

            $("#summary_empty_weight").html(
                $("#empty_weight").val() + " " + $("#empty_weight_id").val()
            );

            $("#empty_weight").keyup(function() {
                var empty_weight = $(this).val();
                var empty_weight_id = $("#empty_weight_id").val();
                $("#summary_empty_weight").html(empty_weight + " " + empty_weight_id);
            });

            $("#empty_weight_id").on("change", function() {
                var empty_weight = $("#empty_weight").val();
                var empty_weight_id = $(this).val();
                $("#summary_empty_weight").html(empty_weight + " " + empty_weight_id);
            });

            $("#summary_density").html(
                $("#density").val() +
                " " +
                $("#density_weight_id").val() +
                " / " +
                $("#density_unit_id").val()
            );

            $(document).on("keyup", "[name^='package_size[]']", function() {
                var id = $(this).data("id");
                var package_name = $("#package_name_" + id).val();
                var item_unit_id = $("#item_unit_id").val();
                var package_size = $("#package_size_" + id).val();
                $("#summary_package_size_" + id).html(
                    package_size + " " + item_unit_id + " / " + package_name
                );
            });

            $(document).on("keyup", "[name^='package_barcode[]']", function() {
                var id = $(this).data("id");
                var package_barcode = $("#package_barcode_" + id).val();
                $("#summary_package_barcode_" + id).html(package_barcode);
            });

            $("#full_weight_id").on("change", function() {
                $("#empty_weight_id").val(this.value).select2();
            });

            $("#empty_weight_id").on("change", function() {
                $("#full_weight_id").val(this.value).select2();
            });

            // Regardless of WHICH radio was clicked, is the
            //  showSelect radio active?
            if ($("#package").is(":checked")) {
                $("#div1").show();
                $("#addMore").show();
            } else {
                $("#div1").hide();
            }
        });

        function show1() {
            document.getElementById("div1").style.display = "none";
            document.getElementById("addMore").style.display = "none";
            document.getElementById("div2").style.display = "block";
            document.getElementById("div3").style.display = "none";
        }

        function show2() {
            document.getElementById("div1").style.display = "block";
            document.getElementById("addMore").style.display = "block";
            document.getElementById("div2").style.display = "none";
            document.getElementById("div3").style.display = "block";
        }

        function show3() {
            document.getElementById("div4").style.display = "block";
        }

        function show4() {
            document.getElementById("div4").style.display = "none";
        }

        function show5() {
            document.getElementById("div5").style.display = "block";
            document.getElementById("div6").style.display = "none";
            document.getElementById("div8").style.display = "none";
            document.getElementById("div9").style.display = "none";
            $("#summary_inventory_method").html("Count Only");
        }

        function show6() {
            document.getElementById("div4").style.display = "block";
            document.getElementById("div5").style.display = "none";
            document.getElementById("div6").style.display = "block";
            document.getElementById("div8").style.display = "block";
            document.getElementById("div9").style.display = "block";
            $("#summary_inventory_method").html("Count & Weight");
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
                            value: 1,
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
                            value: 1,
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
                    if (result.type == "Beer") {
                        $("#div4").css("display", "block");
                        $("#div5").css("display", "block");
                        $("#div6").css("display", "none");
                        $("#div7").css("display", "none");
                        $("#div8").css("display", "none");
                        $("#div9").css("display", "none");
                        $("#summary_inventory_method").html("Count Only");
                    } else if (result.type == "Miscellaneous") {
                        $("#div4").css("display", "block");
                        $("#div5").css("display", "block");
                        $("#div6").css("display", "none");
                        $("#div7").css("display", "block");
                        $("#div8").css("display", "none");
                        $("#div9").css("display", "none");
                        $("#summary_inventory_method").html("Count Only");
                    } else {
                        $("#div4").css("display", "block");
                        $("#div5").css("display", "none");
                        $("#div6").css("display", "block");
                        $("#div7").css("display", "none");
                        $("#div8").css("display", "block");
                        $("#div9").css("display", "block");
                        $("#summary_inventory_method").html("Count & Weight");
                    }
                }
            });
        }
    </script>
@endpush
