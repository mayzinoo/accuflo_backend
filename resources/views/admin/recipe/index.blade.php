@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6" style="display: flex;">
                    <h5>Sales & Recipes</h5>
                    <div style="margin-left: 15px;margin-right: 40px;margin-top: -5px;">
                        <select name="station_id" class="form-control " id="station_id" data-id=0 style="width: 130%;">
                            @foreach ($stations as $station)
                                <option value="{{ $station->id }}" {{ $station->id == $station_id ? 'selected' : '' }}>
                                    {{ $station->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="margin-top: -5px;">
                        <select name="price_level_id" class="form-control " id="price_level_id">
                            @foreach ($price_levels as $key => $price_level)
                                <option value="{{ $price_level->id }}"
                                    {{ $price_level->id == $price_level_id ? 'selected' : '' }}>
                                    {{ $price_level->level }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Sales & Recipes</li>
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
                    <div class="row mb-3 justify-content-end">
                        <div class="col-auto d-flex">
                            <div class="text-center px-2 border-right">
                                <small class="text-bold text-muted">TOTAL QUANTITY</small><br />
                                <span class="text-dark h4">{{ $total_qty }} Item(s)</span>
                            </div>
                            <div class="text-center px-2">
                                <small class="text-bold text-muted">TOTAL REVENUE</small><br />
                                <span class="text-dark h4">SGD{{ number_format($total_revenue, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Sales & Recipes List</h3>
                            <div class="card-tools">
                                <div class="float-right">
                                    <a class="btn btn-sm btn-dark" data-toggle="collapse" href="#filter" role="button"
                                        aria-expanded="false" aria-controls="filter">
                                        Filter
                                    </a>
                                    @can('create sales')
                                    <a href="#" class="btn btn-sm btn-primary sales-create">
                                        <i class="fa fa-plus"> </i> Add New
                                    </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        <div class="collapse show" id="filter">
                            <div class="card-header">
                                <form action="">
                                    <div class="row search-section">
                                        <div class="col-md-3">
                                            <input class="form-control form-control-sm" type="text" name="name"
                                                value="{{ request('name') }}" placeholder="Search by name">
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
                                        <th width="50"></th>
                                        <th width="80"></th>
                                        <th>PLU</th>
                                        <th>Name</th>
                                        <th width="50">Price</th>
                                        <th width="50">Quantity</th>
                                        <th width="50">Revenue</th>
                                        <th width="60"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($sale_recipes as $index => $sale)
                                        <tr class="item-line" data-id="{{ $sale->id }}">
                                            <td>#</td>
                                            <td><small data-toggle="tooltip"
                                                    
                                                    class="text bg-red p-1">New</small>
                                            </td>
                                            <td>{{ optional($sale->recipe)->plu }}</td>
                                            <td>{{ optional($sale->recipe)->name }}</td>
                                            <td class="price_row">
                                                <input 
                                                    type="number" 
                                                    class="price" 
                                                    value="{{ $sale->price }}" 
                                                    @if(!auth()->user()->can('edit sales')) readonly @endif
                                                />
                                            </td>
                                            <td class="qty_row">
                                                <input 
                                                    type="number" 
                                                    class="qty" 
                                                    value="{{ $sale->qty }}"
                                                    @if(!auth()->user()->can('edit sales')) readonly @endif
                                                />
                                            </td>
                                            <td class="revenue_row">
                                                <input 
                                                    type="number" 
                                                    class="revenue" 
                                                    value="{{ $sale->revenue }}"
                                                    @if(!auth()->user()->can('edit sales')) readonly @endif
                                                 />
                                            </td>
                                            <td style="text-align: right">
                                                @can('edit sales')
                                                    <a href="#" data-id="{{ $sale->recipe->id }}"  class="btn btn-xs btn-info sales-edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('delete sales')
                                                    <a href="#deleteModal" data-toggle="modal" data-id="{{ $sale->recipe->id }}"
                                                        data-route="sales" class="btn btn-xs btn-danger delete">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">There is no data.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                        </div>

                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @include('includes.delete-modal')
@endsection
@push('script')
    <script type="text/javascript">
        $('.sales-create').on('click', function(){
            let station_id = "{{ request()->query('station-id') }}"
            let price_level_id = "{{ request()->query('price-level-id') }}"
            let url = "{{ route('sales.create') }}"
            if(price_level_id){
                url = url + "?station-id=" + station_id + "&price-level-id=" + price_level_id
            } else if (station_id){
                url = url + "?station-id=" + station_id 
            }
            window.location.href = url
        })
        $('.sales-edit').on('click', function(){
            let station_id = "{{ request()->query('station-id') }}"
            let price_level_id = "{{ request()->query('price-level-id') }}"
            let url = "{{ route('sales.edit', ':id' )}}"
            let id = $(this).data('id')
            url = url.replace(':id', id)
            if(price_level_id){
                url = url + "?station-id=" + station_id + "&price-level-id=" + price_level_id
            } else if (station_id){
                url = url + "?station-id=" + station_id 
            }
            window.location.href = url
        })
        $('.price').on('change',function(){
         let price = Number($(this).val());
         let qty = Number($(this).parents('.price_row').siblings('.qty_row').find('.qty').val());
         let revenue = price * qty
         $(this).parents('.price_row').siblings('.revenue_row').find('.revenue').val(revenue.toFixed(2))
         update_recipe_sales({
            "id": $(this).parents('.item-line').data('id'),
            "price": price,
            "qty": qty,
            "revenue": revenue
         })
        })
        $('.qty').on('change',function(){
         let price = Number($(this).parents('.qty_row').siblings('.price_row').find('.price').val());
         let qty = Number($(this).val());
         let revenue = price * qty
         $(this).parents('.qty_row').siblings('.revenue_row').find('.revenue').val(revenue.toFixed(2))
         update_recipe_sales({
            "id": $(this).parents('.item-line').data('id'),
            "price": price,
            "qty": qty,
            "revenue": revenue
         })
        })
        $('.revenue').on('change',function(){
         let revenue = $(this).val();
         let price = Number($(this).parents('.revenue_row').siblings('.price_row').find('.price').val());
         let qty = Number($(this).parents('.revenue_row').siblings('.qty_row').find('.qty').val());
         if(price || qty){
            if(qty){
                price = revenue / qty
                $(this).parents('.revenue_row').siblings('.price_row').find('.price').val(price)
            }else{
                qty = Math.floor(revenue / price)
                $(this).parents('.revenue_row').siblings('.qty_row').find('.qty').val(qty)
            }
            update_recipe_sales({
                "id": $(this).parents('.item-line').data('id'),
                "price": price,
                "qty": qty,
                "revenue": revenue
            })
         }else{
            $(this).val(0)
            alert("You cannot enter a revenue when both price and total qty are zero!")
         }
        })

        $("#price_level_id").change(function(){
            window.location.href = window.location.origin + window.location.pathname + "?station-id=" + $("#station_id").val() + "&price-level-id=" + $(this).val()
        })

        $("#station_id").change(function(){
            window.location.href = window.location.origin + window.location.pathname + "?station-id=" + $("#station_id").val()
        })

        function update_recipe_sales(update_data){
            $.ajax({
                url: "{{ route('recipe-sales.update-recipe-sales') }}",
                method: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": update_data.id,
                    "price": update_data.price,
                    "qty": update_data.qty,
                    "revenue": update_data.revenue
                },
                success: function(response){
                    console.log(response)
                }
            })
        }
    </script>
@endpush
