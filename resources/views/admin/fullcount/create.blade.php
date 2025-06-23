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
                    <h5>Add Count</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Full Counts</a></li>
                        <li class="breadcrumb-item active">Add New</li>
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
                            <h3 class="card-title">Add New Count</h3>
                        </div>
                        <form class="form-horizontal" action="{{ route('fullcount.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <input type="hidden" name="package_id" id="package_id"/>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="name">Item Name</label>
                                    <div class="col-md-6">
                                        <select name="item_id" id="item_id" class="form-control select2-d name"
                                            onchange="searchmakemodel(this.value)"></select>
                                        <x-input-error for="item_id"/>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="barcode">Bar Code</label>
                                    <div class="col-md-6">
                                        <input type="text" name="barcode" value="{{ old('barcode') }}"
                                            class="form-control" id="barcode" readonly required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="size_id">Size</label>
                                    <div class="col-md-6">
                                        <select class="form-control size" name="size" required>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="period_count">Count</label>
                                    <div class="col-md-6">
                                        <input type="number" name="period_count"
                                            value="{{ old('period_count') }}" class="form-control"
                                            id="period_count" required>
                                        <x-input-error for="period_count"/>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="station_id">Station</label>
                                    <div class="col-md-6">
                                        <select name="station_id" id="station_id" style="width:100%;" required>
                                            @forelse($stations as $station)
                                                <option value="{{ $station->id }}">{{ $station->name }}</option>
                                            @empty
                                                <option value="">Select a station</option>
                                            @endforelse
                                        </select>
                                        <x-input-error for="station_id"/>
                                    </div>
                                </div>

                                <div class="form-group row" style="display: none;">
                                    <label class="col-sm-2 col-form-label" for=""> </label>
                                    <div class="col-md-6">
                                        <input type="number" name="period_id" class="form-control" id="period_id"
                                        value="">
                                    </div>
                                </div>

                            </div>


                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                                <button type="submit" class="btn btn-sm btn-default"><a
                                        href="{{ url('fullcount') }}">Cancel</a></button>
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
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#station_id").select2();
            let item_id = "{{ old('item_id') }}"
            if(item_id){
                searchmakemodel(item_id)
            }
        });

        $(document).ready(function() {
            var fullcount_period_id = <?php echo $period_id; ?>;
            $("input[name='period_id']" ).val(fullcount_period_id);
        });

        $(".select2-d").select2({
            'placeholder': 'Search Item',
            ajax: {
                url: '/ajax/item/fetchall'
            }
        });

        function searchmakemodel(itemid) {
            $.ajax({
                url : "{{ route('item.getItemSizesPackages') }}",
                type: "get",
                dataType: "json",
                data: {
                    itemid: itemid
                },
                success: function(response){
                    $('.size').empty();
                    $.each(response.item_size, function(index,item_size){
                        let initial_value = item_size.countable_unit + item_size.countable_size
                        $.each(item_size.item_package, function(idx, item_package){
                            if(idx == 0){
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
        }

        $(".size").change(function(){
            let package_barcode = $(this).find(':selected').data('package_barcode') 
            let package_id = $(this).find(':selected').data('package_id')

            $("#barcode").val(package_barcode)
            $("#package_id").val(package_id)
        })

    </script>
@endpush
