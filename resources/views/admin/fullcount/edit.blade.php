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
                    <h1>Update Full Counts</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Full Counts</a></li>
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Update Full Counts</h3>
                        </div>
                        <form class="form-horizontal form-validation"
                            action="{{ route('fullcount.update', $fullcount->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="item_id">Item Name</label>
                                    <div class="col-md-6">
                                        <select name="item_id" id="item_id" class="form-control select2-d name">
                                            @foreach ($items as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $item->id == old('item_id') || $item->id == $fullcount->item_id ? 'selected' : '' }}>
                                                    {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="barcode">Bar Code</label>
                                    <div class="col-md-6">
                                        <input type="text" name="barcode"
                                            value="{{ old('barcode') ? old('barcode') : $fullcount->item->barcode }}"
                                            class="form-control" id="barcode" readonly required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="last_peroid_count">
                                        Last Period Count</label>
                                    <div class="col-md-6">
                                        <input type="number" name="last_peroid_count"
                                            value="{{ old('last_peroid_count') ? old('last_peroid_count') : $fullcount->last_peroid_count }}"
                                            class="form-control" id="last_peroid_count" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="current_peroid_count">Current Period
                                        Count</label>
                                    <div class="col-md-6">
                                        <input type="number" name="current_peroid_count"
                                            value="{{ old('current_peroid_count') ? old('current_peroid_count') : $fullcount->current_peroid_count }}"
                                            class="form-control" id="current_peroid_count" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="invertory_level"> Invertory Level</label>
                                    <div class="col-md-6">
                                        <input type="number" name="invertory_level"
                                            value="{{ old('invertory_level') ? old('invertory_level') : $fullcount->invertory_level }}"
                                            class="form-control" id="invertory_level" readonly required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="size_id">Size</label>
                                    <div class="col-md-6">
                                        <input type="text" name="size"
                                            value="{{ old('size') ? old('size') : $fullcount->item->countable_unit }} {{ $fullcount->item->countable_unit_id }}"
                                            class="form-control" id="size" readonly required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="station_id">Station</label>
                                    <div class="col-md-6">
                                        <select name="station_id" id="station_id" style="width:100%;" required>
                                            @forelse($stations as $station)
                                                <option value="{{ $station->id }}"
                                                    {{ $station->id == old('station_id') || $station->id == $fullcount->station_id ? 'selected' : '' }}>
                                                    {{ $station->name }}</option>
                                            @empty
                                                <option value="">Select a station</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row" style="display: none;">
                                    <label class="col-sm-2 col-form-label" for=""> </label>
                                    <div class="col-md-6">
                                        <input type="number" name="period_id" class="form-control" id="period_id"
                                        value="{{ old('period_id') ? old('period_id') : $fullcount->period_id }}">
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
        });

        $(document).ready(function() {
            $('#item_id').prop('disabled', true);
            $('#station_id').prop('disabled', true);
        });

        $("#last_peroid_count").keyup(function() {
            var last_count = $("#last_peroid_count").val();
            var current_count = $("#current_peroid_count").val();
            if (Number(last_count) <= Number(current_count)) {
                var invertoryLevel = current_count - last_count;
                $("#invertory_level").val(invertoryLevel);
            } else {
                var invertoryLevel = last_count - current_count;
                $("#invertory_level").val(invertoryLevel);
            }
        });

        $("#current_peroid_count").keyup(function() {
            var current_count = $("#current_peroid_count").val();
            var last_count = $("#last_peroid_count").val();
            if (Number(last_count) <= Number(current_count)) {
                var invertoryLevel = current_count - last_count;
                $("#invertory_level").val(invertoryLevel);
            } else {
                var invertoryLevel = last_count - current_count;
                $("#invertory_level").val(invertoryLevel);
            }
        });
    </script>
@endpush
