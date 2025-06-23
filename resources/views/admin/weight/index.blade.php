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
                    <h5>Weights</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Weights</li>
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
                            <h3 class="card-title">Weights List</h3>
                            <div class="card-tools">
                                <div class="float-left mr-1">
                                    <select class="form-control stations" name="stations" style="height:35px;min-width:130px;">
                                        <option value="">All Stations</option>
                                        @foreach($stations as $key => $station)
                                        <option @if(request()->station_id == $station->id) selected @endif value="{{ $station->id }}">{{ $station->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="float-right">
                                    <a class="btn btn-sm btn-dark" data-toggle="collapse" href="#filter" role="button"
                                        aria-expanded="false" aria-controls="filter">
                                        Filter
                                    </a>
                                    @if ($period_status == 1)
                                        @can('create weight')
                                            <a id="add" href="javascript:void(0)"
                                                class="btn btn-sm btn-primary add_weight">
                                                <i class="fa fa-plus"> </i> Add Weight
                                            </a>
                                        @endcan
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="collapse show" id="filter">
                            <div class="card-header">
                                <form action="" autocomplete="off">
                                    <div class="row search-section">
                                        <div class="col-md-3">
                                        <input class="form-control form-control-sm" type="text" name="item_name"
                                                value="{{ request('item_name') }}" placeholder="Search by item name">
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
                                        <th>
                                            Weight <br>
                                            Date
                                        </th>
                                        <th>Station</th>
                                        <th>Section</th>
                                        <th>Shelf</th>
                                        <th>Barcode</th>
                                        <th>Class</th>
                                        <th>Category</th>
                                        <th>
                                            Item <br>
                                            Name
                                        </th>
                                        <th>Size</th>
                                        <th style="width:100px">
                                            Last <br>
                                            Period <br>
                                            Weight
                                        </th>
                                        <th style="width:100px">
                                            Current <br>
                                            Period <br>
                                            Weight
                                        </th>
                                        <th>
                                            Volume <br>
                                            Different
                                        </th>
                                        <th width="100px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($weights as $index => $weight)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $weight->created_at->toDateString() }}</td>
                                            <td>{{ optional($weight->station)->name }}</td>
                                            <td>{{ optional($weight->section)->name  }}</td>
                                            <td>{{ optional($weight->shelf)->shelf_name }}</td>
                                            <td>{{ optional($weight->package)->package_barcode }}
                                            <td>{{ optional($weight->item->class)->name }}</td>
                                            <td>{{ optional($weight->item->category)->name }}</td>
                                            <td>{{ optional($weight->item)->name }}</td>
                                            <td>{{ $weight->size }}</td>
                                            <td class="last_weight_row">
                                                @if($last_period_status)
                                                <input type="text" style="width:60px" data-id="{{ $weight->last_weight_id }}" data-item_id="{{ $weight->item_id }}" data-station_id="{{ $weight->station_id }}" data-section_id="{{ $weight->section_id }}" data-shelf_id="{{ $weight->shelf_id }}" data-package_id="{{ $weight->package_id }}" data-unit_id="{{ $weight->unit_id }}" data-size="{{ $weight->size }}" class="form-control last_weight" value="{{ $weight->last_weight }}" name="last_weight" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                                @if(!auth()->user()->can('edit weight')) readonly @endif
                                                />
                                                <small> {{ $weight->unit_id }} </small>
                                                @else
                                                {{ $weight->last_weight ? $weight->last_weight : '' }}
                                                @endif
                                            </td>
                                            <td class="current_weight_row">
                                                @if($period_status)
                                                <input type="text" style="width:60px" @if(isset($weight->current_weight_id)) data-id="{{ $weight->current_weight_id }}" @endif data-item_id="{{ $weight->item_id }}" data-station_id="{{ $weight->station_id }}" data-section_id="{{ $weight->section_id }}" data-shelf_id="{{ $weight->shelf_id }}" data-package_id="{{ $weight->package_id }}" data-unit_id="{{ $weight->unit_id }}" data-size="{{ $weight->size }}" class="form-control current_weight" value="{{ $weight->current_weight }}" name="current_weight" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                                @if(!auth()->user()->can('edit weight')) readonly @endif
                                                />
                                                <small> {{ $weight->unit_id }} </small>
                                                @else
                                                {{ $weight->current_weight }}
                                                @endif
                                            </td>
                                            <td class="volume_difference">
                                                @if($weight->already_updated)
                                                    @if($weight->up_or_down == 'up')
                                                        <i class="fa fa-arrow-up">&nbsp;</i>
                                                    @else
                                                        <i class="fa fa-arrow-down">&nbsp;</i>
                                                    @endif
                                                    <span>{{ $weight->volume_difference }} <small> ml </small></span>
                                                @else
                                                    @if($weight->up_or_down == 'up')
                                                        <i class="fa fa-arrow-up" style="color:orange">&nbsp;</i>
                                                        <span style="color:orange">{{ $weight->volume_difference }} <small> ml </small> </span>
                                                    @else
                                                        <i class="fa fa-arrow-down" style="color:red">&nbsp;</i>
                                                        <span style="color:red">{{ $weight->volume_difference }} <small> ml </small></span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="delete_row">
                                                @if ($period_status == 1)
                                                @can('delete weight')
                                                    <a href="#deleteModal" @if($weight->current_weight_id == '') style="pointer-events: none" @endif data-toggle="modal" data-id="{{ $weight->current_weight_id }}"
                                                        data-route="weight" class="btn btn-xs btn-danger delete">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                @endcan
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="14" class="text-center">There is no data.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>

                    <!-- /.card  -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="modal fade" id="create-weight-modal" tabindex="-1" role="dialog" aria-labelledby="createWeightModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-weight: 650px">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createWeightModalLabel">Create Weight</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="createWeightForm" action="{{ route('weight.store') }}" method="POST" autocomplete="off">
                @csrf
                <input type="hidden" name="period_id" class="form-control" id="period_id" value="{{ $period_id }}">
                <input type="hidden" name="package_id" class="form-control" id="package_id" value="">
                <div class="form-group row">
                    <div class="col-md-6">
                            <label for="item_id" class="col-form-label">Item Name:</label>
                            <select id="item_name" class="form-control" name="item_id">
                            </select>
                            <x-input-error for="item_id"/>
                    </div>
                    <div class="col-md-6">
                        <label for="barcode" class="col-form-label">Barcode:</label>
                        <input type="text" id="barcode" class="form-control" name="barcode" value="" disabled/>
                        <x-input-error for="barcode"/>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="weight" class="col-form-label">Weight:</label>
                        <input type="text" class="form-control" name="weight" value="{{ old('weight') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" />
                        <x-input-error for="weight"/>
                    </div>
                    <div class="col-md-6">
                        <label for="unit_id" class="col-form-label">Unit:</label>
                        <select name="unit_id" class="form-control">
                            <option value="g">g</option>
                            <option value="kg">kg</option>
                        </select>
                        <x-input-error for="unit_id"/>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="size" class="col-form-label">Size:</label>
                        <select class="form-control size" name="size">
                        </select>
                        <x-input-error for="size"/>
                    </div>
                    <div class="col-md-6">
                        <label for="station_id" class="col-form-label">Station:</label>
                        <select id="station_id" class="form-control" name="station_id">
                            @foreach($stations as $station)
                            <option @if($station->id == old('station_id')) selected @endif value="{{ $station->id }}"/>{{ $station->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="station_id"/>
                    </div>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-primary btn-create-weight">Create</button>
            </div>
            </div>
        </div>
    </div>
    @include('includes.delete-modal')
@endsection
@push('script')
<script type="text/javascript">
    $(document).ready(function(){
        const validation_error = "{{ session('validation_error') }}";
        if(validation_error === 'create_weight'){
            $('#create-weight-modal').modal('show')
        }
    })
    $('.add_weight').on('click', function(){
        $('#create-weight-modal').modal('show')
    })
    $('#create-weight-modal').on('shown.bs.modal', function () {
        $("#item_name").select2({
            dropdownParent: '#create-weight-modal',
	    	minimumInputLength: 2,
	    	placeholder: "Search Item",
			language: {
				noResults: function() {
					return 'No Result';
				},
				searching: function() {
					return "Searching...";
				}
			},
	      
	      	ajax: {
                url: "{{ route('items.searchbyname') }}",
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
    $('.btn-create-weight').on('click', function(){
        $("#createWeightForm").submit()
    })
    $(document).on('change','.last_weight', function(){
        let $this = $(this);
        let last_weight = $(this).val() ? $(this).val() : 0
        let weight_id = $(this).data('id')
        if(weight_id){
            $.ajax({
                url : "{{ route('weight.updateWeight') }}",
                method : "POST",
                data: {
                        "_token": "{{ csrf_token() }}",
                        "weight": last_weight,
                        "id": weight_id,
                },
                success: function(){
                    let current_weight = $this.parent('td').siblings('.current_weight_row').find('.current_weight').val()
                    current_weight = current_weight ? current_weight : 0
                    if(parseInt(last_weight) < parseInt(current_weight)){
                        let volume_difference = parseInt(current_weight) - parseInt(last_weight)
                        let volumeDifferenceHtml =  "<i class='fa fa-arrow-up'>&nbsp;</i>"
                        volumeDifferenceHtml += `<span>${volume_difference}</span>`
                        $this.parent('td').siblings('.volume_difference').html(volumeDifferenceHtml)
                    }else{
                        let volume_difference = parseInt(last_weight) - parseInt(current_weight)
                        let volumeDifferenceHtml =  "<i class='fa fa-arrow-down'>&nbsp;</i>"
                        volumeDifferenceHtml += `<span>${volume_difference}</span>`
                        $this.parent('td').siblings('.volume_difference').html(volumeDifferenceHtml)
                    }
                    alert('Last Weight Updated Successfully')
                }
            })
        }else{
            let last_period_id = "{{ $last_period_id }}"
            $.ajax({
                url : "{{ route('weight.storeWeight') }}",
                method : "post",
                data : {
                    "_token": "{{ csrf_token() }}",
                    item_id : $(this).data('item_id'),
                    station_id : $(this).data('station_id'),
                    section_id : $(this).data('section_id'),
                    shelf_id : $(this).data('shelf_id'),
                    period_id : last_period_id,
                    unit_id : $(this).data('unit_id'),
                    size : $(this).data('size'),
                    weight : $(this).val(),
                    package_id : $(this).data('package_id')
                },
                success : function(){
                    let current_weight = $this.parent('td').siblings('.current_weight_row').find('.current_weight').val()
                    current_weight = current_weight ? current_weight : 0
                    if(parseInt(last_weight) < parseInt(current_weight)){
                        let volume_difference = parseInt(current_weight) - parseInt(last_weight)
                        let volumeDifferenceHtml =  "<i class='fa fa-arrow-up'>&nbsp;</i>"
                        volumeDifferenceHtml += `<span>${volume_difference}</span>`
                        $this.parent('td').siblings('.volume_difference').html(volumeDifferenceHtml)
                    }else{
                        let volume_difference = parseInt(last_weight) - parseInt(current_weight)
                        let volumeDifferenceHtml =  "<i class='fa fa-arrow-down'>&nbsp;</i>"
                        volumeDifferenceHtml += `<span>${volume_difference}</span>`
                        $this.parent('td').siblings('.volume_difference').html(volumeDifferenceHtml)
                    }
                    alert('Last Weight Updated Successfully')
                }
            })
        }
        
    })

    $(document).on('change','.current_weight', function(){
        let $this = $(this);
        let current_weight = $(this).val() ? $(this).val() : 0
        let weight_id = $(this).data('id')
        if(weight_id){
            $.ajax({
                url : "{{ route('weight.updateWeight') }}",
                method : "POST",
                data: {
                        "_token": "{{ csrf_token() }}",
                        "weight": current_weight,
                        "id": weight_id,
                },
                success: function(){
                    let last_weight = $this.parent('td').siblings('.last_weight_row').find('.last_weight').val()
                    last_weight = last_weight ? last_weight : 0
                    if(parseInt(last_weight) < parseInt(current_weight)){
                        let volume_difference = parseInt(current_weight) - parseInt(last_weight)
                        let volumeDifferenceHtml =  "<i class='fa fa-arrow-up'>&nbsp;</i>"
                        volumeDifferenceHtml += `<span>${volume_difference}</span>`
                        $this.parent('td').siblings('.volume_difference').html(volumeDifferenceHtml)
                    }else{
                        let volume_difference = parseInt(last_weight) - parseInt(current_weight)
                        let volumeDifferenceHtml =  "<i class='fa fa-arrow-down'>&nbsp;</i>"
                        volumeDifferenceHtml += `<span>${volume_difference}</span>`
                        $this.parent('td').siblings('.volume_difference').html(volumeDifferenceHtml)
                    }
                    alert('Current Weight Updated Successfully')
                }
            })
        }else{
            let period_id = "{{ $period_id }}"
            $.ajax({
                url : "{{ route('weight.storeWeight') }}",
                method : "post",
                data : {
                    "_token": "{{ csrf_token() }}",
                    item_id : $(this).data('item_id'),
                    station_id : $(this).data('station_id'),
                    section_id : $(this).data('section_id'),
                    shelf_id : $(this).data('shelf_id'),
                    period_id : period_id,
                    unit_id : $(this).data('unit_id'),
                    size : $(this).data('size'),
                    package_id : $(this).data('package_id'),
                    weight : $(this).val(),
                    package_id : $(this).data('package_id')
                },
                success : function(response){
                    let last_weight = $this.parent('td').siblings('.last_weight_row').find('.last_weight').val()
                    last_weight = last_weight ? last_weight : 0
                    if(parseInt(last_weight) < parseInt(current_weight)){
                        let volume_difference = parseInt(current_weight) - parseInt(last_weight)
                        let volumeDifferenceHtml =  "<i class='fa fa-arrow-up'>&nbsp;</i>"
                        volumeDifferenceHtml += `<span>${volume_difference}</span>`
                        $this.parent('td').siblings('.volume_difference').html(volumeDifferenceHtml)
                    }else{
                        let volume_difference = parseInt(last_weight) - parseInt(current_weight)
                        let volumeDifferenceHtml =  "<i class='fa fa-arrow-down'>&nbsp;</i>"
                        volumeDifferenceHtml += `<span>${volume_difference}</span>`
                        $this.parent('td').siblings('.volume_difference').html(volumeDifferenceHtml)
                    }
                    if((current_weight == 0) || (current_weight == '')){
                        $this.parent('td').siblings('.delete_row').find('.delete').css("pointer-events","none")
                    }else{
                        $this.parent('td').siblings('.delete_row').find('.delete').css("pointer-events","auto")
                        $this.parent('td').siblings('.delete_row').find('.delete').attr("data-id", response.id)
                    }
                    alert('Current Weight Updated Successfully')
                }
            })
        }
    })
    $('.stations').change(function(){
        if($(this).val()){
            window.location.href = window.location.origin + window.location.pathname + "?station_id=" + $(this).val()
        }else{
            window.location.href = window.location.origin + window.location.pathname
        }
    })
    $("#item_name").change(function(){
        $.ajax({
            url : "{{ route('item.getItemSizesPackages') }}",
            type: "get",
            dataType: "json",
            data: {
                itemid: $(this).val()
            },
            success: function(response){
                $("#barcode").val(response.item_size[0].barcode);
                $.each(response.item_size, function(index,item_size){
                    let initial_value = item_size.countable_unit + item_size.countable_size
                    $.each(item_size.item_package, function(idx, item_package){
                        if(idx == 0){
                            $("#package_id").val(item_package.id)
                            let text_option= initial_value + ' ' + item_package.unit_from + '(' + item_package.package_barcode + ')'
                            $('.size').append(`<option value="${initial_value}" data-package_id="${item_package.id}" data-package_barcode="${item_package.package_barcode}">
                                    ${text_option}
                                </option>`);
                        }else{
                            let text_value = item_package.qty + ' x ' + initial_value 
                            let text_option= text_value + ' ' + item_package.unit_from + '(' + item_package.package_barcode + ')'
                            $('.size').append(`<option value="${text_value}" data-package_id="${item_package.id}" data-package_barcode="${item_package.package_barcode}">
                                    ${text_option}
                                </option>`);
                        }
                    })
                })
            },
            complete: function(){
                let package_id = $('.size').find(':selected').data('package_id')
                let package_barcode = $('.size').find(':selected').data('package_barcode')

                $("#barcode").val(package_barcode);
                $("#package_id").val(package_id);
            }
        })
    })
    $(".size").change(function(){
        let package_barcode = $(this).find(':selected').data('package_barcode') 
        let package_id = $(this).find(':selected').data('package_id')

        $("#barcode").val(package_barcode)
        $("#package_id").val(package_id)
    })
</script>
@endpush
