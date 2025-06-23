@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
@endpush
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Edit Invoice</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('invoices') }}">Invoices</a></li>
                        <li class="breadcrumb-item active">Edit</li>
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
                            <h3 class="card-title text-bold">Edit Invoice</h3>
                        </div>
                        <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label class="d-block gray-text">{{ optional($invoice->vendor)->name }}</label>
                                        <label class="gray-text" for="invoice" style="width:80%;border-bottom:1px dotted #000">
                                            INVOICE# <span class="black-text invoice_number" style="color:red;font-size:20px;cursor:pointer">{{ $invoice->invoice_number }}</span>
                                        </label>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="d-block gray-text">INVOICE/DELIVERY DATE</label>
                                        <label class="black-text invoice_delivery_date" style="width:80%;border-bottom:1px dotted #000;cursor:pointer">{{ $invoice->invoice_delivery_date }}</label>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="d-block gray-text">INVOICE DUE DATE</label>
                                        <label class="black-text invoice_due_date" style="width:80%;border-bottom:1px dotted #000;cursor:pointer">{{ $invoice->invoice_due_date }}</label>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="d-block gray-text">TOTAL QUANTITY</label>
                                        <label>
                                            <input type="text" readonly name="total_quantity" class="total_quantity black-text" value="{{ $invoice->total_quantity? $invoice->total_quantity:'' }}"/>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-2">
                                        <label class="d-block gray-text">TOTAL TAXES</label>
                                        <label class="black-text">SGD {{ number_format($invoice->total_taxes, 2) }}</label>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="d-block gray-text">TOTAL DEPOSITS</label>
                                        <label class="black-text">SGD {{ number_format($invoice->total_deposits, 2) }}</label>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="d-block gray-text">TOTAL DELIVERY</label>
                                        <label class="black-text">SGD {{ number_format($invoice->total_delivery, 2) }}</label>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="d-block gray-text">TOTAL NON-INVENTORY</label>
                                        <label class="black-text">SGD {{ number_format($invoice->total_non_inventory, 2) }}</label>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="d-block gray-text">TOTAL MISC</label>
                                        <label class="black-text">SGD {{ number_format($invoice->total_misc, 2) }}</label>
                                    </div>

                                    <div class="col-md-2">
                                        <label class="d-block gray-text">TOTAL COST</label>
                                        <label class="black-text">SGD 
                                            <input type="text" style="width:100px" name="total_cost" class="total_cost" value="{{ number_format($invoice->total_cost, 2) }}"/>
                                        </label>
                                    </div>
                                </div>
                                @if($period_status == 1)
                                <div class="row border-top border-bottom mt-0 mb-1 pt-3 pb-3">
                                    <div class="col-md-12 mb-2">
                                       <h3 class="card-title text-center text-bold">Add an item to the invoice by searching:</h3>
                                    </div>
                                    <div class="col-md-2">
                                       <select id="item_name" class="form-control">
                                       </select>
                                    </div>
                                    <div class="col-md-1 text-center"><label>OR</label></div>
                                    <div class="col-md-2">
                                        <select id="barcode" class="form-control">
                                        </select>
                                    </div>
                                </div>
                                @endif
                                 <div class="row pt-3 pb-3">
                              <div class="col-md-12">
                                 <table class="table table-bordered tbl-invoice-details">
                                    <thead>
                                       <tr>
                                          <th scope="col" class="text-center" style="width:3%">#</th>
                                          <th scope="col" class="text-center" style="width:20%">Description</th>
                                          <th colspan="2" scope="col" class="text-center" style="width:25%">Quantity</th>
                                          <th scope="col" class="text-center" style="width:20%">Unit Price</th>
                                          <th scope="col" class="text-center" style="width:17%">Ext Price</th>
                                          <th scope="col" class="text-center" style="width:5%">Action</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                        @if($invoice->invoiceDetails)
                                            @foreach($invoice->invoiceDetails as $index => $invoice_details)
                                            <tr>
                                                <td class="index text-center">{{ $index + 1 }}</td>
                                                <td class="text-center">
                                                    {{ optional($invoice_details->item)->name }}
                                                    <input type="hidden" name="item_id[]" class="item_id" value="{{ $invoice_details->item_id }}"/>
                                                </td>
                                                <td style="width:100px">
                                                    <input type="text" name="quantity[]" class="form-control quantity" value="{{ $invoice_details->purchased_quantity }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" />
                                                </td>
                                                <td class="unit">
                                                    <select name="unit[]" class="form-control package_id">
                                                        @php
                                                            $item_package_format = package_format($invoice_details->item_id);
                                                        @endphp
                                                        @foreach($item_package_format as $key => $format)
                                                        <option value="{{ $key }}" data-unit_to="{{ $format->unit_to }}" 
                                                            @if($key == $invoice_details->purchase_package) selected @endif>
                                                            {{ $format->text }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="unit_price[]" class="form-control unit_price" value="{{ $invoice_details->unit_price }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" />
                                                </td>
                                                <td>
                                                    <input type="text" name="extended_price[]" class="form-control extended_price" value="{{ $invoice_details->extended_price }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" />
                                                </td>
                                                <td class="text-center">
                                                    <i class="fa fa-trash remove" aria-hidden="true" style="font-size: 17px;line-height: 30px;color: red;cursor: pointer;"></i>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                 </table>
                              </div>
                            </div>
                            </div>
                            <div class="card-footer text-right">
                                @if($period_status == 1)
                                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                                <a href="{{ url('invoices') }}" type="button" class="btn btn-sm btn-default">Cancel</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    @include('includes.invoice-edit-models')
@endsection
@include('templates.tr-invoice-details')
@push('script')
    <script type="text/javascript">
        const units = "{{ json_encode($units) }}";
        const PERIOD_STATUS = "{{ $period_status }}"
        const js_units = JSON.parse(units.replace(/&quot;/g,'"'));
        $( ".modal-date-picker").datepicker({
          dateFormat: "yy-mm-dd"
        });
        $('.invoice_number').on('click',function(){
            if(PERIOD_STATUS == 1){
                $('.modal-invoice_number').val($('.invoice_number').text())
                $('#invoice-number-modal').modal('show')
            }
        })
        $('.btn-update-invoice-number').on('click',function(){
            $.ajax({
                url: "{{ route('invoices.upadatefieldbyone') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "field_name": "invoice_number",
                    "id": "{{ $invoice->id }}",
                    "invoice_number": $('.modal-invoice_number').val()
                },
                success: function(response){
                    if(response.status == 'success'){
                        $('.invoice_number').html($('.modal-invoice_number').val())
                    }
                    $('#invoice-number-modal').modal('hide')
                }
            })
        })

        $('.invoice_delivery_date').on('click',function(){
            if(PERIOD_STATUS == 1){
                $('.modal-invoice_delivery_date').val($('.invoice_delivery_date').text())
                $('#invoice-delivery-date-modal').modal('show')
            }
        })

        $('.btn-update-invoice-delivery-date').on('click',function(){
            $.ajax({
                url: "{{ route('invoices.upadatefieldbyone') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "field_name": "invoice_delivery_date",
                    "id": "{{ $invoice->id }}",
                    "invoice_delivery_date": $('.modal-invoice_delivery_date').val()
                },
                success: function(response){
                    if(response.status == 'success'){
                        $('.invoice_delivery_date').html($('.modal-invoice_delivery_date').val())
                    }
                    $('#invoice-delivery-date-modal').modal('hide')
                }
            })
        })

        $('.invoice_due_date').on('click',function(){
            if(PERIOD_STATUS == 1){
                $('.modal-invoice_due_date').val($('.invoice_due_date').text())
                $('#invoice-due-date-modal').modal('show')
            }
        })

        $('.btn-update-invoice-due-date').on('click',function(){
            $.ajax({
                url: "{{ route('invoices.upadatefieldbyone') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "field_name": "invoice_due_date",
                    "id": "{{ $invoice->id }}",
                    "invoice_due_date": $('.modal-invoice_due_date').val()
                },
                success: function(response){
                    if(response.status == 'success'){
                        $('.invoice_due_date').html($('.modal-invoice_due_date').val())
                    }
                    $('#invoice-due-date-modal').modal('hide')
                }
            })
        })

        $("#item_name").change(function(){
            if(PERIOD_STATUS == 1){
                let invoice_id = "{{ $invoice->id }}";
                let vendor_id = "{{ $invoice->vendor_id }}";
                addTableRow({ id: $(this).val() , invoice_id: invoice_id, vendor_id: vendor_id }, "select2-item_name-container", "Item")
                $(this).val('')
            }
        })

        $("#barcode").change(function(){
            if(PERIOD_STATUS == 1){
                let invoice_id = "{{ $invoice->id }}";
                let vendor_id = "{{ $invoice->vendor_id }}";
                addTableRow({ id: $(this).val() , invoice_id: invoice_id, vendor_id: vendor_id }, "select2-barcode-container", "UPC")
                $(this).val('')
            }
        })

        $(document).on('click','.remove', function(){
            if(PERIOD_STATUS == 1){
                $(this).parents('tr').remove()
                fixIndexNo()
                calcTotalQuantity()
                calcTotalCost()
            }
        })

        $(document).on('keyup','.quantity', function(){
           if(PERIOD_STATUS == 1){
            let quantity = $(this).val() ? $(this).val() : 0
            let unit_price = $(this).parents('tr').find('.unit_price').val() ? $(this).parents('tr').find('.unit_price').val() : 0
            calcExtendedPrice(quantity, unit_price, $(this).parents('tr'))
            calcTotalQuantity()
            calcTotalCost()
           }
        })

        $(document).on('keyup','.unit_price', function(){
           if(PERIOD_STATUS == 1){
            let quantity = $(this).parents('tr').find('.quantity').val() ? $(this).parents('tr').find('.quantity').val() : 0
            let unit_price = $(this).val() ? $(this).val() : 0
            calcExtendedPrice(quantity, unit_price, $(this).parents('tr'))
            calcTotalCost()
           }
        })

        $(document).on('keyup','.extended_price', function(){
            if(PERIOD_STATUS == 1){
                let quantity = $(this).parents('tr').find('.quantity').val() ? $(this).parents('tr').find('.quantity').val() : 0
                let extended_price = $(this).val() ? parseFloat($(this).val()) : 0
                let unit_price = Number(extended_price) / Number(quantity)
                $(this).parents('tr').find('.unit_price').val(unit_price.toFixed(2))
                calcTotalCost()
            }
        })

        $(document).on('change','.package_id', function(){
            calcTotalQuantity()
            calcTotalCost()
        })

        function addTableRow(data, select_2_container, placeholder){
            $.ajax({
                url: '/ajax/items/getbyid',
                dataType: 'json',
                data: data,
                success: function(response){
                    if(response.status == 'success'){
                        let option_text = ''
                        $.each(response.item_packages, function(index,val){
                            console.log("val>> ", val);
                            option_text += `<option value="${val.id}" data-unit_to="${val.unit_to}">
                                    ${getOptionText(val, response.item_sizes)}
                                </option>`
                        })
                        let data_clone = $("#tr-invoice-details").html()
                                         .replace("${index}",$('.tbl-invoice-details tbody tr').length + 1)
                                         .replace("${description}", response.data.name)
                                         .replace(/\${unit}/g, option_text)
                                         .replace("${item_id}", response.data.id)
                                         .replace("${unit_price}", response.data.unit_price)
                                         .replace("${extended_price}", response.data.unit_price)

                        $('.tbl-invoice-details tbody').append(data_clone)
                        $("#" + select_2_container).html(`<span class="select2-selection__placeholder">${placeholder}</span>`)
                        calcTotalQuantity()
                        calcTotalCost()
                    }
                }
            })
        }

        function getOptionText(val,item_sizes){
            let text = ''
            let countable_unit = ''
            let countable_size = ''

            $.each(item_sizes, function(index,size){
                if(size.id == val.item_size_id){
                    countable_unit = size.countable_unit
                    countable_size = size.countable_size
                }
            })

            if(val.unit_from == val.unit_to){
                text = `${val.unit_to}(${countable_unit} ${countable_size}) - ${val.package_barcode}`
            }else{
                text = `${val.unit_to}(${val.qty} x${countable_unit} ${countable_size}) - ${val.package_barcode}`
            }
            return text
        }
        function fixIndexNo(){
            $('.tbl-invoice-details tbody td.index').each(function(index,ele){
                $('.tbl-invoice-details tbody td.index:eq('+ index +')').html(index + 1)
            })
        }
        function calcExtendedPrice(quantity, unit_price, row_selector){
            let extended_price = Number(quantity) * Number(unit_price)
            row_selector.find('.extended_price').val(extended_price.toFixed(2))
        }
        function calcTotalQuantity(){
            let obj_total_qty = {}
            let string_total_qty = null
            $('.tbl-invoice-details tbody td.unit').each(function(index,ele){
                let unit = $('.tbl-invoice-details tbody td.unit:eq('+ index +')').find(':selected').data('unit_to').trim()
                if(obj_total_qty[unit] === undefined){
                    obj_total_qty[unit] = $('.tbl-invoice-details tbody td .quantity:eq('+ index +')').val()
                }else{
                    obj_total_qty[unit] = Number($('.tbl-invoice-details tbody td .quantity:eq('+ index +')').val()) + Number(obj_total_qty[unit])
                }
            })
            for (const property in obj_total_qty) {
                if(string_total_qty === null){
                    string_total_qty = obj_total_qty[property] + ' ' + property.trim()
                }else{
                    string_total_qty += ', ' + obj_total_qty[property] + ' ' + property.trim()
                }
            }
            $('.total_quantity').val(string_total_qty)
        }
        function calcTotalCost(){
            let total_cost = 0
            $('.extended_price').each(function(index,ele){
                total_cost += Number($(this).val())
            })
            $('.total_cost').val(total_cost.toFixed(2))
        }
    </script>
    @include('includes.select2-ajax', [
        'id' => '#item_name',
        'placeholder' => 'Item',
        'url' => route('items.searchbyname')
    ])

    @include('includes.select2-ajax', [
        'id' => '#barcode',
        'placeholder' => 'UPC',
        'url' => route('items.searchbybarcode')
    ])
@endpush

@push('styles')
<style type="text/css">
    .table td, .table th {
        padding: 0.6rem;
        vertical-align: middle;
    }
    .gray-text{
        font-size: 14px;
        color: gray;
    }
    .black-text{
        font-size: 20px;
        color: #000;
    }
    .select2-selection__rendered {
        line-height: 23px !important;
    }
    .select2-container .select2-selection--single {
        height: 35px !important;
    }
    .select2-selection__arrow {
        height: 23px !important;
    }
    .total_quantity, .total_quantity:active, .total_cost, .total_cost:active {
        font-size: 20px;
        font-weight: 700;
        border: 0px;
        color: #000;
        padding: 0;
        margin: 0;
        outline: 0;
    }
</style>
@endpush
