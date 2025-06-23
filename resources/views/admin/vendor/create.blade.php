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
                    <h1>New Vendor</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Vendor</a></li>
                        <li class="breadcrumb-item active">New Vendor</li>
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
                    <form class="form-horizontal form-validation" action="{{ route('vendor.store') }}" method="post">
                        @csrf
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title w-100">
                                        <a class="d-block w-100" data-toggle="collapse" href="#collapseOne"
                                            aria-expanded="true" style="color:#000">
                                            Vendor
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
                                                    <x-input-error for="name"/>
                                                </div>
                                            </div>

                                            <div class="col-sm-8 offset-sm-2">
                                                <div class="form-group">
                                                    <label for="">Vendor Code</label>
                                                    <input type="text" name="code" value="{{ old('code') }}"
                                                        class="form-control" id="code" placeholder="" required>
                                                    <x-input-error for="code"/>
                                                </div>
                                            </div>

                                            <div class="col-sm-8 offset-sm-2">
                                                <div class="form-group">
                                                    <label for="">Default Invoice Due Date</label>
                                                    <div id="div1" style="display: none; margin-bottom: 10px;">
                                                        <input type="number" name="invoice_due_date"
                                                            value="{{ old('invoice_due_date') }}" class="form-control"
                                                            id="invoice_due_date" placeholder="">
                                                    </div>
                                                    <select name="invoice_due_date_unit" id="invoice_due_date_unit"
                                                        class="form-control" onchange="changeDueDate(this.value)" required>
                                                        @foreach ($INVOICE_DUE_DATE as $key => $due_date)
                                                            <option value="{{ $key }}">{{ $due_date }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <x-input-error for="invoice_due_date_unit"/>
                                                </div>
                                            </div>

                                            <div class="col-sm-8 offset-sm-2">
                                                <div class="row" style="width: 100% !important;">
                                                    <div class="col-sm-12"
                                                        style="border: 1px solid #ced4da;
                                                            border-radius: .25rem;
                                                            padding: .375rem .75rem;
                                                            margin-left: 7px;
                                                            margin-top: 3px">
                                                        <input type="checkbox" name="status" id="status"
                                                            onchange="checkNotRealVendor(this)" />
                                                        This is not a real vendor
                                                        <x-input-error for="status"/>
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
                                            Address
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="collapse show" style="">
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-sm-8 offset-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="">Address Line 1</label>
                                                            <input type="text" name="address_line_1"
                                                                value="{{ old('address_line_1') }}" class="form-control"
                                                                id="address_line_1" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="">Address Line 2</label>
                                                            <input type="text" name="address_line_2"
                                                                value="{{ old('address_line_2') }}" class="form-control"
                                                                id="address_line_2" placeholder="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-8 offset-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="">City</label>
                                                            <input type="text" name="city"
                                                                value="{{ old('city') }}" class="form-control"
                                                                id="city" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="">State</label>
                                                            <input type="text" name="state"
                                                                value="{{ old('state') }}" class="form-control"
                                                                id="state" placeholder="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-sm-8 offset-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="">Country</label>
                                                            <select name="country_code" id="country_code"
                                                                class="form-control" required>
                                                                @foreach ($COUNTRY as $key => $country)
                                                                    <option value="{{ $key }}">
                                                                        {{ $country }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="">Postal Code</label>
                                                            <input type="number" name="postal_code"
                                                                value="{{ old('postal_code') }}" class="form-control"
                                                                id="postal_code" placeholder="">
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
                                        <a class="d-block w-100" data-toggle="collapse" href="#collapseTree"
                                            aria-expanded="true" style="color:#000">
                                            Contacts
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTree" class="collapse show" style="">
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-sm-8 offset-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="">Phone #</label>
                                                            <input type="text" name="phone"
                                                                value="{{ old('phone') }}" class="form-control"
                                                                id="phone" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="">Cell #</label>
                                                            <input type="text" name="cell"
                                                                value="{{ old('cell') }}" class="form-control"
                                                                id="cell" placeholder="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-8 offset-sm-2">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="">Fax #</label>
                                                            <input type="text" name="fax"
                                                                value="{{ old('fax') }}" class="form-control"
                                                                id="fax" placeholder="">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label for="">Email #</label>
                                                            <input type="email" name="email"
                                                                value="{{ old('email') }}" class="form-control"
                                                                id="email" placeholder="">
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
                                            Notes
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseFour" class="collapse show" style="">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-8 offset-sm-2">
                                                <div class="form-group">
                                                    <label for="">Notes</label>
                                                    <textarea class="form-control" name="notes" id="notes"></textarea>
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
                                                            <div>
                                                                <label class="" for="">Name : </label>
                                                                <span class="" for=""
                                                                    id="summary_name"></span>
                                                            </div>
                                                            <div>
                                                                <label class="" for="">Code : </label>
                                                                <span class="" for=""
                                                                    id="summary_code"></span>
                                                            </div>
                                                            <div>
                                                                <label class="" for="">Address : </label>
                                                                <span class="" for=""
                                                                    id="summary_address"></span>
                                                            </div>
                                                            <div>
                                                                <label class="" for="">Phone :
                                                                </label>
                                                                <span class="" for=""
                                                                    id="summary_phone"></span>
                                                            </div>
                                                            <div>
                                                                <label class="" for="">Cell : </label>
                                                                <span class="" for=""
                                                                    id="summary_cell"></span>
                                                            </div>
                                                            <div>
                                                                <label class="" for="">Fax : </label>
                                                                <span class="" for=""
                                                                    id="summary_fax"></span>
                                                            </div>
                                                            <div>
                                                                <label class="" for=""> Email: </label>
                                                                <span class="" for=""
                                                                    id="summary_email"></span>
                                                            </div>
                                                            <div>
                                                                <label class="" for="">Notes : </label>
                                                                <span class="" for=""
                                                                    id="summary_notes"></span>
                                                            </div>
                                                            <div>
                                                                <label class="" for="">Invoice Due Date :
                                                                </label>
                                                                <span class="" for=""
                                                                    id="summary_invoice_due_date"></span>
                                                            </div>
                                                            <div>
                                                                <span class="" for=""
                                                                    id="summary_status"></span>
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
                                <button type="submit" class="btn btn-sm btn-default"><a
                                        href="{{ url('vendor') }}">Cancel</a></button>
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
    <script>
        function changeDueDate(id) {
            if (id == 0) {
                document.getElementById("div1").style.display = "none";
            } else {
                document.getElementById("div1").style.display = "block";
            }

        }

        function checkNotRealVendor(checkbox) {
            if (checkbox.checked) {
                $("#status").val(checkbox.checked);
                var status = "*** This is NOT a real vendor ***";
                $("#summary_status").html(status);
            } else {
                $("#status").val(checkbox.checked);
                var status = " ";
                $("#summary_status").html(status);
            }
        }


        $("#name").keyup(function() {
            $("#summary_name").html($(this).val());
        });

        $("#code").keyup(function() {
            $("#summary_code").html($(this).val());
        });

        $("#address_line_1").keyup(function() {
            get_summary_address($(this).val(), $("#address_line_2").val(), $("#city").val(), $("#state").val(), $(
                "#country_code option:selected").text(), $("#postal_code").val())

        });

        $("#address_line_2").keyup(function() {
            get_summary_address($("#address_line_1").val(), $(this).val(), $("#city").val(), $("#state").val(), $(
                "#country_code option:selected").text(), $("#postal_code").val())
        });

        $("#city").keyup(function() {
            get_summary_address($("#address_line_1").val(), $("#address_line_2").val(), $(this).val(), $("#state")
                .val(), $("#country_code option:selected").text(), $("#postal_code").val())
        });

        $("#state").keyup(function() {
            get_summary_address($("#address_line_1").val(), $("#address_line_2").val(), $("#city").val(), $(this)
                .val(), $("#country_code option:selected").text(), $("#postal_code").val())
        });


        $("#country_code").on("change", function() {
            get_summary_address($("#address_line_1").val(), $("#address_line_2").val(), $("#city").val(),
                $("#state").val(), $(this).find('option:selected').text(), $("#postal_code").val())
        });

        $("#postal_code").keyup(function() {
            get_summary_address($("#address_line_1").val(), $("#address_line_2").val(), $("#city").val(), $(
                "#state").val(), $("#country_code option:selected").text(), $(this).val())
        });

        function get_summary_address(address_line_1, address_line_2, city, state, country_code, postal_code) {

            let summary_address = address_line_1;

            if ((summary_address) && (address_line_2)) {
                summary_address = summary_address + ',' + address_line_2
            } else if (address_line_2) {
                summary_address = address_line_2
            }

            if ((summary_address) && (city)) {
                summary_address = summary_address + ',' + city
            } else if (city) {
                summary_address = city
            }

            if ((summary_address) && (state)) {
                summary_address = summary_address + ',' + state
            } else if (state) {
                summary_address = state
            }


            if ((summary_address) && (postal_code)) {
                summary_address = summary_address + ',' + postal_code
            } else if (postal_code) {
                summary_address = postal_code
            }

            if ((summary_address) && (country_code)) {
                summary_address = summary_address + ',' + country_code
            } else if (country_code) {
                summary_address = country_code
            }

            $("#summary_address").html(summary_address);
        }

        $("#phone").keyup(function() {
            $("#summary_phone").html($(this).val());
        });

        $("#cell").keyup(function() {
            $("#summary_cell").html($(this).val());
        });

        $("#fax").keyup(function() {
            $("#summary_fax").html($(this).val());
        });

        $("#email").keyup(function() {
            $("#summary_email").html($(this).val());
        });

        $("#notes").keyup(function() {
            $("#summary_notes").html($(this).val());
        });

        $("#invoice_due_date_unit").on("change", function() {
            $("#summary_invoice_due_date").html($("#invoice_due_date_unit").find(":selected").text());
        });
    </script>
@endpush
