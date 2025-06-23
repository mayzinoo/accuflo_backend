
@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href=" {{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
@endpush
@section('content')<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h5>Create Invoice</h5>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Invoices</a></li>
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
                <h3 class="card-title">Create New Invoice</h3>
            </div>
            <form class="form-horizontal form-validation" action="{{ route('invoices.store') }}" method="POST" autocomplete="off">
                @csrf                
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="vendor_id">Vendor</label>
                        <div class="col-md-6">
                            <select name="vendor_id" id="vendor_id" style="width:100%;">
                            </select>
                            <x-input-error for="vendor_id"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="invoice_number">Invoice Number</label>
                        <div class="col-md-6">
                            <input type="text" name="invoice_number" value="{{old('invoice_number')}}" class="form-control" id="invoice_number" placeholder="Enter invoice number" required>
                            <x-input-error for="invoice_number"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="invoice_delivery_date">Invoice / Delivery Date</label>
                        <div class="col-md-6">
                            <input type="text" name="invoice_delivery_date" value="{{old('invoice_delivery_date')}}" class="form-control" id="invoice_delivery_date" placeholder="Invoice Delivery Date" required>
                            <x-input-error for="invoice_delivery_date"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="invoice_due_date">Invoice Due Date</label>
                        <div class="col-md-6">
                            <input type="text" name="invoice_due_date" value="{{old('invoice_due_date')}}" class="form-control" id="invoice_due_date" placeholder="Invoice Due Date" required >
                            <x-input-error for="invoice_due_date"/>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                    <button type="button" class="btn btn-sm btn-default"><a href="{{ url('invoices') }}">Cancel</a></button>
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
<script src="../assets/plugins/select2/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#vendor_id").select2();
        $( "#invoice_delivery_date").datepicker({
          dateFormat: "yy-mm-dd"
        });
        $( "#invoice_due_date").datepicker({
          dateFormat: "yy-mm-dd"
        });
    })
</script>
@include('includes.select2-ajax', [
    'id' => '#vendor_id',
    'placeholder' => 'Vendor',
    'url' => route('vendors.searchbyname')
])
@if(old('vendor_id'))
  @include('includes.old-select2-ajax', [
    'id' => '#vendor_id',
    'url' => route('vendors.getbyid'),
    'old_id' => old('vendor_id')
  ])
@endif
@endpush