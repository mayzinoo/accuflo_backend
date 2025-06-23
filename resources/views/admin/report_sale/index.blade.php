@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">

                <div class="col-sm-6" style="display: flex;">
                    <div>
                        <i class="fa fa-th-list fa-2x"
                            style="color: #1c75bc;
                        border: #1c75bc solid 4px;
                        border-radius: 10px;
                        padding: 10px;"></i>
                    </div>

                    <h5 style="margin-left: 15px;margin-top: 15px"> Sale Report</h5>
                    <form action="" id="generate">
                        <div style="margin-left: 30px;margin-top: 15px;display: flex">
                            <div>
                                <select class="form-control " name="station_id" id="station_id"
                                    style="height: 98%;" onchange="changeStation(this.value)">
                                    @foreach ($stations as $station)
                                        <option value="{{ $station->id }}"
                                            {{ $station->id == $station_id ? 'selected' : '' }}>
                                            {{ $station->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="margin-left: 5px;">
                                <a class="btn btn-sm btn-default" onclick="generate()">
                                    Generate
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right" style="margin-top:15px;">
                        <form action="{{ route('report-sale-excel') }}" method="POST">
                            @csrf
                            <li style="margin-right: 5px;">
                                <button type="submit" class="btn btn-sm btn-default">
                                    <i class="far fa-file-excel"></i> Save to Excel
                                </button>
                                <input type="hidden" name="excel_station_id" id="excel_station_id">
                            </li>
                        </form>

                        <form action="{{ route('report-sale-pdf') }}" method="POST">
                            @csrf
                            <li>
                                <button type="submit" class="btn btn-sm btn-default">
                                    <i class="far fa-file-pdf"></i> Save to PDF
                                </button>
                                <input type="hidden" name="pdf_station_id" id="pdf_station_id">
                            </li>
                        </form>

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
                    <table class="table text-nowrap" style="line-height: 0.7 !important">
                        <thead style="color: #000000;
                          background-color: #f2f2f2;">
                            <tr>
                                <th>PLU</th>
                                <th>Item Name</th>
                                <th>Price Level</th>
                                <th>Price</th>
                                <th>Sold</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recipes as $recipe)
                                @foreach ($recipe->sales as $index => $sale)
                                    <tr>
                                        <td>{{ $index == 0 ? $recipe->plu : ' ' }}</td>
                                        <td>{{ $index == 0 ? $recipe->name : ' ' }}</td>
                                        <td>{{ $sale->pricelevel->first()->level }}</td>
                                        <td>SGD {{ number_format($sale->price, 2, '.', '') }} </td>
                                        <td>{{ number_format($sale->qty, 2, '.', '') }}</td>
                                        <td>SGD {{ number_format($sale->revenue, 2, '.', '') }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
@push('script')
    <script type="text/javascript">
        function generate() {
            console.log("generate");
            $("#generate").submit();
        }

        $(document).ready(function() {
            var station_id = $("#station_id").val();
            $("#excel_station_id").val(station_id);
            $("#pdf_station_id").val(station_id);
        });

        function changeStation(id) {
            var station_id = id;
            $("#excel_station_id").val(station_id);
            $("#pdf_station_id").val(station_id);
        }
    </script>
@endpush
