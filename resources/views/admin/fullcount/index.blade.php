@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Full Counts</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Full Counts</li>
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
                            <h3 class="card-title">Full Counts List</h3>
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
                                    @can('create full count')
                                        @if ($period_status == 1)
                                            <a id="add" href="{{ route('fullcount.create') }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="fa fa-plus"> </i> Add Count
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
                                        <th style="width:3%">#</th>
                                        <th style="width:7%">
                                            Station <br/> Name
                                        </th>
                                        <th style="width:10%">Barcode</th>
                                        <th style="width:10%">Class</th>
                                        <th style="width:10%">Category</th>
                                        <th style="width:10%">Name</th>
                                        <th style="width:10%">Size</th>
                                        <th style="width:10%">
                                            Last <br/> Period <br/> Count
                                        </th>
                                        <th style="width:10%">
                                            Current <br/> Period <br/> Count
                                        </th>
                                        <th style="width:10%">
                                            Invertory <br/> Level
                                        </th>
                                        <th style="width:10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($full_counts as $index => $full_count)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ optional($full_count->station)->name }}</td>
                                            <td>{{ optional($full_count->itemPackage)->package_barcode }} </td>
                                            <td>{{ optional($full_count->item->class)->name }}</td>
                                            <td>{{ optional($full_count->item->category)->name }}</td>
                                            <td>{{ optional($full_count->item)->name }}</td>
                                            <td>{{ $full_count->size }}</td>
                                            <td class="last_period_count_row">
                                                @if($last_period_status)
                                                <input type="text" data-id="{{ $full_count->last_full_count_id }}" data-item_id="{{ $full_count->item_id }}" data-size="{{ $full_count->size }}" data-package_id="{{ $full_count->package_id }}"  data-station_id="{{ $full_count->station_id }}" class="form-control last_period_count" value="{{ $full_count->last_period_count }}" name="last_period_count" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                                @if(!auth()->user()->can('edit full count')) readonly @endif
                                                />
                                                <small> {{ optional($full_count->itemPackage)->unit_to }} </small>
                                                @else
                                                {{ $full_count->last_period_count ? $full_count->last_period_count : '' }}
                                                @endif
                                            </td>
                                            <td class="current_period_count_row">
                                                @if($period_status)
                                                <input type="text" @if(isset($full_count->current_full_count_id)) data-id="{{ $full_count->current_full_count_id }}" @endif data-item_id="{{ $full_count->item_id }}" data-size="{{ $full_count->size }}" data-package_id="{{ $full_count->package_id }}"  data-station_id="{{ $full_count->station_id }}" class="form-control current_period_count" value="{{ $full_count->current_period_count }}" name="current_period_count" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                                @if(!auth()->user()->can('edit full count')) readonly @endif
                                                />
                                                <small> {{ optional($full_count->itemPackage)->unit_to }} </small>
                                                @else
                                                {{ $full_count->current_period_count }}
                                                @endif
                                            </td>
                                            <td class="inventory_level">
                                                @if($full_count->already_updated)
                                                    @if($full_count->up_or_down == 'up')
                                                        <i class="fa fa-arrow-up">&nbsp;</i>
                                                    @else
                                                        <i class="fa fa-arrow-down">&nbsp;</i>
                                                    @endif
                                                    <span>{{ $full_count->inventory_level }}</span>
                                                @else
                                                    @if($full_count->up_or_down == 'up')
                                                        <i class="fa fa-arrow-up" style="color:orange">&nbsp;</i>
                                                        <span style="color:orange">{{ $full_count->inventory_level }}</span>
                                                    @else
                                                        <i class="fa fa-arrow-down" style="color:red">&nbsp;</i>
                                                        <span style="color:red">{{ $full_count->inventory_level }}</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="delete_row">
                                                @can('delete full count')
                                                    @if ($period_status == 1)
                                                        <a href="#deleteModal" @if($full_count->current_full_count_id == '') style="pointer-events: none" @endif data-toggle="modal" data-id="{{ $full_count->current_full_count_id }}"
                                                            data-route="fullcount" class="btn btn-xs btn-danger delete">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    @endif
                                                @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center">There is no data.</td>
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
    @include('includes.delete-modal')
@endsection
@push('script')
<script type="text/javascript">
    $(document).on('change','.last_period_count', function(){
        let $this = $(this);
        let last_period_count = $(this).val() ? $(this).val() : 0
        let full_count_id = $(this).data('id')
        if(full_count_id){
            $.ajax({
                url : "{{ route('fullcounts.updatePeriodCount') }}",
                method : "POST",
                data: {
                        "_token": "{{ csrf_token() }}",
                        "period_count": last_period_count,
                        "id": full_count_id,
                },
                success: function(){
                    let current_period_count = $this.parent('td').siblings('.current_period_count_row').find('.current_period_count').val()
                    current_period_count = current_period_count ? current_period_count : 0
                    if(parseInt(last_period_count) < parseInt(current_period_count)){
                        let inventory_level = parseInt(current_period_count) - parseInt(last_period_count)
                        let inventoryLevelHtml =  "<i class='fa fa-arrow-up'>&nbsp;</i>"
                        inventoryLevelHtml += `<span>${inventory_level}</span>`
                        $this.parent('td').siblings('.inventory_level').html(inventoryLevelHtml)
                    }else{
                        let inventory_level = parseInt(last_period_count) - parseInt(current_period_count)
                        let inventoryLevelHtml =  "<i class='fa fa-arrow-down'>&nbsp;</i>"
                        inventoryLevelHtml += `<span>${inventory_level}</span>`
                        $this.parent('td').siblings('.inventory_level').html(inventoryLevelHtml)
                    }
                    alert('Last Period Count Updated Successfully')
                }
            })
        }else{
            let last_period_id = "{{ $last_period_id }}"
            $.ajax({
                url : "{{ route('fullcounts.storePeriodCount') }}",
                method : "post",
                data : {
                    "_token": "{{ csrf_token() }}",
                    item_id : $(this).data('item_id'),
                    size : $(this).data('size'),
                    station_id : $(this).data('station_id'),
                    period_count : last_period_count,
                    period_id : last_period_id,
                    package_id : $(this).data('package_id'),
                },
                success : function(){
                    let current_period_count = $this.parent('td').siblings('.current_period_count_row').find('.current_period_count').val()
                    current_period_count = current_period_count ? current_period_count : 0
                    if(parseInt(last_period_count) < parseInt(current_period_count)){
                        let inventory_level = parseInt(current_period_count) - parseInt(last_period_count)
                        let inventoryLevelHtml =  "<i class='fa fa-arrow-up'>&nbsp;</i>"
                        inventoryLevelHtml += `<span>${inventory_level}</span>`
                        $this.parent('td').siblings('.inventory_level').html(inventoryLevelHtml)
                    }else{
                        let inventory_level = parseInt(last_period_count) - parseInt(current_period_count)
                        let inventoryLevelHtml =  "<i class='fa fa-arrow-down'>&nbsp;</i>"
                        inventoryLevelHtml += `<span>${inventory_level}</span>`
                        $this.parent('td').siblings('.inventory_level').html(inventoryLevelHtml)
                    }
                    alert('Last Period Count Updated Successfully')
                }
            })
        }
        
    })

    $(document).on('change','.current_period_count', function(){
        let $this = $(this);
        let current_period_count = $(this).val() ? $(this).val() : 0
        let full_count_id = $(this).data('id')
        if(full_count_id){
            $.ajax({
                url : "{{ route('fullcounts.updatePeriodCount') }}",
                method : "POST",
                data: {
                        "_token": "{{ csrf_token() }}",
                        "period_count": current_period_count,
                        "id": full_count_id,
                },
                success: function(){
                    let last_period_count = $this.parent('td').siblings('.last_period_count_row').find('.last_period_count').val()
                    last_period_count = last_period_count ? last_period_count : 0
                    if(parseInt(last_period_count) < parseInt(current_period_count)){
                        let inventory_level = parseInt(current_period_count) - parseInt(last_period_count)
                        let inventoryLevelHtml =  "<i class='fa fa-arrow-up'>&nbsp;</i>"
                        inventoryLevelHtml += `<span>${inventory_level}</span>`
                        $this.parent('td').siblings('.inventory_level').html(inventoryLevelHtml)
                    }else{
                        let inventory_level = parseInt(last_period_count) - parseInt(current_period_count)
                        let inventoryLevelHtml =  "<i class='fa fa-arrow-down'>&nbsp;</i>"
                        inventoryLevelHtml += `<span>${inventory_level}</span>`
                        $this.parent('td').siblings('.inventory_level').html(inventoryLevelHtml)
                    }
                    alert('Current Period Count Updated Successfully')
                }
            })
        }else{
            let period_id = "{{ $period_id }}"
            $.ajax({
                url : "{{ route('fullcounts.storePeriodCount') }}",
                method : "post",
                data : {
                    "_token": "{{ csrf_token() }}",
                    item_id : $(this).data('item_id'),
                    size : $(this).data('size'),
                    station_id : $(this).data('station_id'),
                    period_count : current_period_count,
                    period_id : period_id,
                    package_id : $(this).data('package_id'),
                },
                success : function(response){
                    let last_period_count = $this.parent('td').siblings('.last_period_count_row').find('.last_period_count').val()
                    last_period_count = last_period_count ? last_period_count : 0
                    if(parseInt(last_period_count) < parseInt(current_period_count)){
                        let inventory_level = parseInt(current_period_count) - parseInt(last_period_count)
                        let inventoryLevelHtml =  "<i class='fa fa-arrow-up'>&nbsp;</i>"
                        inventoryLevelHtml += `<span>${inventory_level}</span>`
                        $this.parent('td').siblings('.inventory_level').html(inventoryLevelHtml)
                    }else{
                        let inventory_level = parseInt(last_period_count) - parseInt(current_period_count)
                        let inventoryLevelHtml =  "<i class='fa fa-arrow-down'>&nbsp;</i>"
                        inventoryLevelHtml += `<span>${inventory_level}</span>`
                        $this.parent('td').siblings('.inventory_level').html(inventoryLevelHtml)
                    }
                    if((current_period_count == 0) || (current_period_count == '')){
                        $this.parent('td').siblings('.delete_row').find('.delete').css("pointer-events","none")
                    }else{
                        $this.parent('td').siblings('.delete_row').find('.delete').css("pointer-events","auto")
                        $this.parent('td').siblings('.delete_row').find('.delete').attr("data-id", response.id)
                    }
                    alert('Current Period Count Updated Successfully')
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
</script>
@endpush
