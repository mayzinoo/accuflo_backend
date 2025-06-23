@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Invoices</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Invoices</li>
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
                            <h3 class="card-title">Invoices List</h3>
                            <div class="card-tools">
                                <div class="float-right">
                                    <a class="btn btn-sm btn-dark" data-toggle="collapse" href="#filter" role="button"
                                        aria-expanded="false" aria-controls="filter">
                                        Filter
                                    </a>
                                    @can('create invoice')
                                        @if($period_status == 1)
                                        <a id="add" href="{{ route('invoices.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-plus"> </i> New Invoice
                                        </a>
                                        @endif
                                    @endcan
                                </div>
                            </div>
                        </div>
                        <div class="collapse show" id="filter">
                            <div class="card-header">
                                <form action="" autocomplete="off">
                                    <div class="row search-section">
                                        <div class="col-md-3">
                                            <input class="form-control form-control-sm" type="text" name="invoice_number"
                                                value="{{ request('invoice_number') }}" placeholder="Search By Invoice Number">
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit"
                                                class="btn btn-sm btn-primary search mb-2">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /.card-header -->

                        <!-- /.card-body -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Vendor Name</th>
                                        <th>
                                            Date of Invoice <br/>
                                            Delivery
                                        </th>
                                        <th>Invoice</th>
                                        <th>
                                            Total <br/>
                                            Quantity
                                        </th>
                                        <th>
                                            Total Cost <br/>
                                            Excluding Taxes <br/>
                                            (SGD)
                                        </th>
                                        <th>
                                            Total Taxes <br/>
                                            (SGD)
                                        </th>
                                        <th>
                                            Total Cost <br/>
                                            Including Taxes </br>
                                            (SGD)
                                        </th>
                                        <th width="100px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($invoices as $index => $invoice)
                                        <tr>
                                            <td>{{ $invoices->firstItem() + $index }}</td>
                                            <td class="vendor" style="cursor:pointer" data-id="{{ $invoice->id }}">
                                                {{ optional($invoice->vendor)->name }}
                                            </td>
                                            <td>{{ $invoice->invoice_delivery_date }}</td>
                                            <td>{{ $invoice->invoice_number }}</td>
                                            <td>{{ $invoice->total_quantity }}</td>
                                            <td>{{ number_format($invoice->total_cost_excluding_taxes, 2) }}</td>
                                            <td>{{ number_format($invoice->total_taxes, 2) }}</td>
                                            <td>{{ number_format($invoice->total_cost, 2) }}</td> 
                                            <td>
                                                @if($period_status == 1)
                                                    @can('edit invoice')
                                                        <a href="{{ route('invoices.edit', $invoice->id) }}"
                                                            class="btn btn-xs btn-info">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                    @can('delete invoice')
                                                    <a href="#deleteModal" data-toggle="modal" data-id="{{ $invoice->id }}"
                                                        data-route="invoices" class="btn btn-xs btn-danger delete">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                    @endcan
                                                @else
                                                    <a href="{{ route('invoices.edit', $invoice->id) }}"
                                                        class="btn btn-xs btn-info">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">There is no data.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            {{ $invoices->withQueryString()->links() }}
                        </div>

                    </div>
                    <!-- /.card  -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @include('includes.delete-modal')
    <div class="modal fade" id="vendor-modal" tabindex="-1" role="dialog" aria-labelledby="vendorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="vendorModalLabel">Edit Vendor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                <div class="form-group">
                    <input type="hidden" name="invoice_id" class="invoice_id"/>
                    <label for="message-text" class="col-form-label">Vendor:</label>
                    <select id="vendor_id" class="form-control">
                    </select>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-primary btn-update-vendor">Update</button>
            </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
<style type="text/css">
    .table thead th, .table tr td {
        text-align: center;
        vertical-align: middle;
    }
</style>
@endpush
@push('script')
<script src="../assets/plugins/select2/js/select2.min.js"></script>
<script>
    $('.vendor').on('click',function(){ 
        $("#vendor-modal .invoice_id").val($(this).data('id'))
        $('#vendor-modal').modal('show')
    })
    $('#vendor-modal').on('shown.bs.modal', function () {
        $("#vendor_id").select2({
            dropdownParent: '#vendor-modal',
	    	minimumInputLength: 2,
	    	placeholder: "Search Vendor",
			language: {
				noResults: function() {
					return 'No Result';
				},
				searching: function() {
					return "Searching...";
				}
			},
	      
	      	ajax: {
                url: "{{ route('vendors.searchbyname') }}",
	        	dataType: 'json',
	        	delay: 250, 
	        	data: function (params) {
	            	return {
	                	q: params.term
	            	};
	        	},
	        	processResults: function (data) {
	          		return {
	            		results: $.map(data, function (item) {
		              		return {
		                		id: item.id,
		                		text: item.text,
		              		}
		            	})
	          		};
	        	},
	        	cache: true
	      	}
	    });
    })
    $('.btn-update-vendor').on('click',function(){
            $.ajax({
                url: "{{ route('invoices.upadatefieldbyone') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "field_name": "vendor_id",
                    "id": $("#vendor-modal .invoice_id").val(),
                    "vendor_id": $('#vendor_id').val()
                },
                success: function(response){
                    if(response.status == 'success'){
                        $('.vendor').html($('#vendor_id').text().trim())
                    }
                    $('#vendor-modal').modal('hide')
                }
            })
        })
</script>
@endpush
