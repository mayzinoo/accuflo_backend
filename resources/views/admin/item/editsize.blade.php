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
                    <h5>Update Item Size and Packaging</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Size</a></li>
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
                    <form class="form-horizontal form-validation" action="{{ route('update-size', $item_size->id) }}"
                        method="POST" autocomplete="off">
                        @csrf
                        @method('POST')
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
                                                        class="form-control" id="name" placeholder="" readonly
                                                        required>
                                                </div>
                                            </div>

                                            <div class="col-sm-8 offset-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="" for="">Class</label>
                                                            <select class="form-control" name="class_id" id="class_id"
                                                                style="width:100%;" disabled="disabled" required>
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
                                                                style="width:100%;" disabled="disabled">
                                                                <option value="0">Default</option>
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
                                                                style="width:100%;" disabled="disabled">
                                                                <option value="0">Default</option>
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
                                                                value="{{ old('barcode') ? old('barcode') : $item_packages[0]->package_barcode }}"
                                                                class="form-control" id="barcode" placeholder="" readonly
                                                                required>
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
                                <div id="collapseTwo" class="collapse show"  style="">
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-sm-8 offset-sm-2">
                                                <div class="form-group">
                                                    <label class="" for="">Description of each countable
                                                        item/unit</label>
                                                    <select class="form-control" name="unit_from" id="item_unit_id"
                                                        style="width:100%;" required>
                                                        @foreach ($units as $key => $unit)
                                                            <option value="{{ $key }}"
                                                                {{ $item_packages[0]->unit_from == $key ? 'selected' : '' }}>
                                                                {{ $unit }}
                                                            </option>
                                                        @endforeach
                                                    </select>
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
                                                            id="item_unit_portion_yes" value="yes" onclick="show3()"/>
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
                                                                value="{{ old('countable_unit') ? old('countable_unit') : $item_size->countable_unit }}"
                                                                class="form-control" id="countable_unit" placeholder="">
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <select class="form-control" class="form-control"
                                                                name="countable_size" id="countable_unit_id"
                                                                style="width:100%;">
                                                                @foreach ($COUNTABLE_UNIT_ID as $key => $countable_unit_id)
                                                                    <option value="{{ $key }}"
                                                                        {{ $item_size->countable_size == $key ? 'selected' : '' }}>
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
                                                                value="{{ old('empty_weight') ? old('empty_weight') : $item_size->empty_weight }}"
                                                                class="form-control" id="empty_weight" placeholder="">
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <select class="form-control" name="empty_weight_size"
                                                                id="empty_weight_id" style="width:100%;">
                                                                @foreach ($EMPTY_WEIGHT_ID as $key => $empty_weight_id)
                                                                    <option value="{{ $key }}"
                                                                        {{ $item_size->empty_weight_size == $key ? 'selected' : '' }}>
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
                                                                value="{{ old('full_weight') ? old('full_weight') : $item_size->full_weight }}"
                                                                class="form-control" id="full_weight" placeholder="">
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <select class="form-control" name="full_weight_size"
                                                                id="full_weight_id" style="width:100%;">
                                                                @foreach ($FULL_WEIGHT_ID as $key => $full_weight_id)
                                                                    <option value="{{ $key }}"
                                                                        {{ $item_size->full_weight_size == $key ? 'selected' : '' }}>
                                                                        {{ $full_weight_id }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                         {{--    <div class="col-sm-8 offset-sm-2">

                                                <div class="form-group">
                                                    <label class="" for="">Size of each countable
                                                        unit</label>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <input type="number" name="countable_unit"
                                                                value="{{ old('countable_unit') ? old('countable_unit') : $item_size->countable_unit }}"
                                                                class="form-control" id="countable_unit" placeholder="">
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <select class="form-control" class="form-control"
                                                                name="countable_size" id="countable_unit_id"
                                                                style="width:100%;">
                                                                @foreach ($COUNTABLE_UNIT_ID as $key => $countable_unit_id)
                                                                    <option value="{{ $key }}"
                                                                        {{ $item_size->countable_size == $key ? 'selected' : '' }}>
                                                                        {{ $countable_unit_id }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="" for="">Specify the empty weight</label>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <input type="number" name="empty_weight"
                                                                value="{{ old('empty_weight') ? old('empty_weight') : $item_size->empty_weight }}"
                                                                class="form-control" id="empty_weight" placeholder="">
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <select class="form-control" name="empty_weight_size"
                                                                id="empty_weight_id" style="width:100%;">
                                                                @foreach ($EMPTY_WEIGHT_ID as $key => $empty_weight_id)
                                                                    <option value="{{ $key }}"
                                                                        {{ $item_size->empty_weight_size == $key ? 'selected' : '' }}>
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
                                                                value="{{ old('full_weight') ? old('full_weight') : $item_size->full_weight }}"
                                                                class="form-control" id="full_weight" placeholder="">
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <select class="form-control" name="full_weight_size"
                                                                id="full_weight_id" style="width:100%;">
                                                                @foreach ($FULL_WEIGHT_ID as $key => $full_weight_id)
                                                                    <option value="{{ $key }}"
                                                                        {{ $item_size->full_weight_size == $key ? 'selected' : '' }}>
                                                                        {{ $full_weight_id }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> --}}

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
                                <div id="collapseFour" class="collapse show"  style="">
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
                                                                        id="package_no" value="no"
                                                                        onclick="show1()" />
                                                                    No
                                                                </div>
                                                                <x-input-error for="package_status" />
                                                            </div>
                                                        </div>

                                                        <div class="form-group" id="div1"
                                                            style="display: none; margin-top: 20px">

                                                            @foreach ($item_packages as $key => $item_package)
                                                                @if($key != 0)
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
                                                                                <label class=""
                                                                                    for="">Name</label>
                                                                                <select class="form-control packagename"
                                                                                    name="package_name[]"
                                                                                    id="package_name_{{ $key }}"
                                                                                    data-id={{ $key }}
                                                                                    style="width:100%;">
                                                                                    @foreach ($pkgnamelist as $pkgname)
                                                                                        @if ($item_package->unit_to == $pkgname)
                                                                                            <option
                                                                                                value="{{ $pkgname }}"
                                                                                                selected="">
                                                                                                {{ $pkgname }}
                                                                                            </option>
                                                                                        @else
                                                                                            <option
                                                                                                value="{{ $pkgname }}">
                                                                                                {{ $pkgname }}</option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>

                                                                            </div>

                                                                            <div class="form-group">
                                                                                <label class=""
                                                                                    for="">Size</label>
                                                                                <div class="row">
                                                                                    <div class="col-sm-9">
                                                                                        <input type="text"
                                                                                            name="package_size[]"
                                                                                            value="{{ $item_package->qty }}"
                                                                                            class="form-control"
                                                                                            id="package_size_{{ $key }}"
                                                                                            data-id={{ $key }}
                                                                                            placeholder="">
                                                                                    </div>
                                                                                    <div class="col-sm-3">
                                                                                        <span class="size_per_case"
                                                                                            id="size_per_case_{{ $key }}"
                                                                                            type="text"
                                                                                            name="size_per_case[]"></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="form-group">
                                                                                <label class="" for="">Barcode
                                                                                    of the Case</label>
                                                                                <input type="text" name="package_barcode[]"
                                                                                    value="{{ $item_package->package_barcode }}"
                                                                                    class="form-control"
                                                                                    id="package_barcode_{{ $key }}"
                                                                                    data-id=0 placeholder="">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach

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
                                <div id="collapseFive" class="collapse show"  style="">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-8 offset-sm-2">

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group">
                                                            <h4 class="d-block w-100" for=""
                                                                id="create_size_summary_title"> </h4>
                                                        </div>
                                                        <div class="form-group">
                                                            <div>
                                                                <label class="" for="">Size : </label>
                                                                <span class="" for=""
                                                                    id="create_size_summary_size"></span>
                                                            </div>
                                                            <div>
                                                                <label class="" for="">Empty Container
                                                                    Weight : </label>
                                                                <span class="" for=""
                                                                    id="create_size_summary_empty_weight"></span>
                                                            </div>
                                                            <div>
                                                                <label class="" for="">Barcode : </label>
                                                                <span class="" for=""
                                                                    id="create_size_summary_barcode"></span>
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
                                                                @foreach ($item_packages as $key => $item_package)
                                                                    @if($key != 0)
                                                                        <div class="row">
                                                                            <div class="col-sm-6">
                                                                                <label class="" for="">Size :
                                                                                </label>
                                                                                <span class="" for=""
                                                                                    id="create_size_summary_package_size_{{ $key }}"></span>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <label class="" for="">Barcode :
                                                                                </label>
                                                                                <span class="" for=""
                                                                                    id="create_size_summary_package_barcode_{{ $key }}"></span>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
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
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script type="text/javascript">
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
                        $("#div5").css("display", "block");
                        if (item_size.sizeoption == 'yes') {
                            $("#div4").css("display", "block");
                        } else {
                            $("#div4").css("display", "none");
                        }
                        $("#div6").css("display", "none");
                    } else {
                        $("#div5").css("display", "none");
                        $("#div4").css("display", "block");
                        $("#div6").css("display", "block");
                    }
                }
            });
        });

        $("#create_size_summary_title").html("Add a Size for " + $("#name").val() + ":");

        $("#create_size_summary_size").html(
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
            $("#create_size_summary_size").html(
                countable_unit + " " + countable_unit_id + " / " + item_unit_id
            );
        });

        $("#countable_unit_id").on("change", function() {
            var countable_unit = $("#countable_unit").val();
            var countable_unit_id = $(this).val();
            var item_unit_id = $("#item_unit_id").val();
            $("#create_size_summary_size").html(
                countable_unit + " " + countable_unit_id + " / " + item_unit_id
            );
        });

        $("#create_size_summary_empty_weight").html(
            $("#empty_weight").val() + " " + $("#empty_weight_id").val()
        );

        $("#empty_weight").keyup(function() {
            var empty_weight = $(this).val();
            var empty_weight_id = $("#empty_weight_id").val();
            $("#create_size_summary_empty_weight").html(empty_weight + " " + empty_weight_id);
        });

        $("#empty_weight_id").on("change", function() {
            var empty_weight = $("#empty_weight").val();
            var empty_weight_id = $(this).val();
            $("#create_size_summary_empty_weight").html(empty_weight + " " + empty_weight_id);
        });

        $("#create_size_summary_barcode").html($("#barcode").val());

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

        var size_option = "{{ $item_size->sizeoption }}";
        if (size_option == 'yes') {
            $("#item_unit_portion_yes").prop("checked", true);
        } else {
            $("#item_unit_portion_no").prop("checked", true);
        }

        var item_packages = <?php echo $item_packages; ?>; 
        var pkg_status = "{{ $item_size->package_status }}";
        if (pkg_status == 'yes') {
            $("#package_yes").prop("checked", true);
            $("#div1").css("display", "block");
            $("#div2").css("display", "none");
            $("#div3").css("display", "block");
            $("#addMore").css("display", "block");
        } else {
            $("#package_no").prop("checked", true);
            $("#div1").css("display", "none");
            $("#div2").css("display", "block");
            $("#div3").css("display", "none");
            $("#addMore").css("display", "none");
        }

        var item_package = <?php echo $item_packages; ?>;
        var pkgcount = item_package.length;
        for (let i = 0; i < item_package.length; i++) {
                var package_size = $("#package_size_" + i).val();
                var item_unit_id = $("#item_unit_id").val();
                var package_name = $("#package_name_" + i).val();

                $("#size_per_case_" + i).html(
                    item_unit_id + " per " + package_name
                );

                $("#create_size_summary_package_size_" + i).html(
                    package_size + " " + item_unit_id + " / " + package_name
                );

                var package_barcode = $("#package_barcode_" + i).val();
                $("#create_size_summary_package_barcode_" + i).html(package_barcode);
        }

        $("#item_unit_id").on("change", function() {
            var item_unit_id = $("#item_unit_id").val();
            for (let i = 0; i < key; i++) {
                var package_name = $("#package_name_" + i).val();
                $("#size_per_case_" + i).html(
                    item_unit_id + " per " + package_name
                );
                var package_size = $("#package_size_" + i).val();
                $("#create_size_summary_package_size_" + i).html(
                    package_size + " " + item_unit_id + " / " + package_name
                );
            }
        });

        $(document).on("keyup", "[name^='package_size[]']", function() {
            var id = $(this).data("id");
            var package_name = $("#package_name_" + id).val();
            var item_unit_id = $("#item_unit_id").val();
            var package_size = $("#package_size_" + id).val();
            $("#create_size_summary_package_size_" + id).html(
                package_size + " " + item_unit_id + " / " + package_name
            );
        });

        $(document).on("keyup", "[name^='package_barcode[]']", function() {
            var id = $(this).data("id");
            var package_barcode = $("#package_barcode_" + id).val();
            $("#create_size_summary_package_barcode_" + id).html(package_barcode);
        });

        $(document).on("change", "select[name^='package_name[]']", function() {
            var id = $(this).data("id");
            var package_name = $(this).val();
            var item_unit_id = $("#item_unit_id").val();
            $("#size_per_case_" + id).html(item_unit_id + " per " + package_name);
            var package_size = $("#package_size_" + id).val();
            $("#create_size_summary_package_size_" + id).html(
                package_size + " " + item_unit_id + " / " + package_name
            );
        });

        var key = pkgcount;

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
                    id="create_size_summary_package_size_${key}"></span>
            </div>
            <div class="col-sm-6">
                <label class="" for="">Barcode :
                </label>
                <span class="" for=""
                    id="create_size_summary_package_barcode_${key}"></span>
            </div>
            </div>`;

            $("#div1").append(clone);
            $("#div3").append(summaryClone);

            key++;
        }

        function removerpkg(event) {
            var target = $(event.target);
            var cl = $(".package").length;
            console.log(cl);
            if (cl == 1) {
                alert("You can not remove");
            } else {
                target.parent().parent().remove();
                key--;
            }
        }
    </script>
@endpush
