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

                    <h5 style="margin-left: 15px;margin-top: 15px"> Drink Mix Report </h5>
                    <form action="" id="generate">
                        <div style="margin-left: 30px;margin-top: 15px;display: flex">
                            <div>
                                <select class="form-control" name="station_id" id="station_id" style="height: 98%;"
                                    onchange="changeStation(this.value)">
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
                        <form action="{{ route('report-recipe-excel') }}" method="POST">
                            @csrf
                            <li style="margin-right: 5px;">
                                <button type="submit" class="btn btn-sm btn-default">
                                    <i class="far fa-file-excel"></i> Save to Excel
                                </button>
                                <input type="hidden" name="excel_station_id" id="excel_station_id">
                            </li>
                        </form>

                        <form action="{{ route('report-recipe-pdf') }}" method="POST">
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
                    @if ($recipes != null)
                        <table class="table table-responsive text-nowrap" style="line-height: 0.7 !important">
                            <thead style="color: #000000;
                            background-color: #f2f2f2;">
                                <tr>
                                    <th>PLU</th>
                                    <th>Item Name</th>
                                    <th>Cost</th>
                                    <th>Tax / Discount</th>
                                    <th>Regular</th>
                                    <th>PC</th>
                                    <th>Profit</th>
                                    @foreach ($price_levels as $price)
                                        @if ($price->type == 1)
                                            <th>{{ $price->level }}</th>
                                            <th>PC</th>
                                            <th>Profit</th>
                                        @endif
                                        @if ($price->type == 2)
                                            <th>{{ $price->level }}</th>
                                            <th>PC</th>
                                            <th>Profit</th>
                                        @endif
                                        @if ($price->type == 3)
                                            <th>{{ $price->level }}</th>
                                            <th>PC</th>
                                            <th>Profit</th>
                                        @endif
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recipes as $index => $recipe)
                                    <tr>
                                        <td>{{ $recipe->plu }}</td>
                                        <td>{{ $recipe->name }}</td>
                                        <td> SGD {{ number_format($recipe->cost, 2, '.', '') }}</td>
                                        <td> {{ number_format($recipe->tax, 2, '.', '') }} % </td>
                                        @if ($recipe->sales->count() > 1)
                                            @foreach ($recipe->sales as $sale)
                                                @php
                                                    $price_level = \App\Models\PriceLevel::where('id', $sale->price_level_id)->get();
                                                @endphp
                                                @if ($price_level[0]->type == 0)
                                                    <td> SGD {{ number_format($sale->price, 2, '.', '') }}</td>
                                                    <td>
                                                        @if ($sale->pure_cost > 0)
                                                            {{ $sale->pure_cost }} %
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td> SGD
                                                        {{ number_format($sale->price - $recipe->cost, 2, '.', '') }}
                                                    </td>
                                                @elseif($price_level[0]->type == 1)
                                                    <td> SGD {{ number_format($sale->price, 2, '.', '') }}</td>
                                                    <td>
                                                        @if ($sale->pure_cost > 0)
                                                            {{ $sale->pure_cost }} %
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td> SGD
                                                        {{ number_format($sale->price - $recipe->cost, 2, '.', '') }}
                                                    </td>
                                                @elseif($price_level[0]->type == 2)
                                                    <td> SGD {{ number_format($sale->price, 2, '.', '') }}</td>
                                                    <td>
                                                        @if ($sale->pure_cost > 0)
                                                            {{ $sale->pure_cost }} %
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td> SGD
                                                        {{ number_format($sale->price - $recipe->cost, 2, '.', '') }}
                                                    </td>
                                                @elseif($price_level[0]->type == 3)
                                                    <td> SGD {{ number_format($sale->price, 2, '.', '') }}</td>
                                                    <td>
                                                        @if ($sale->pure_cost > 0)
                                                            {{ $sale->pure_cost }} %
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td> SGD
                                                        {{ number_format($sale->price - $recipe->cost, 2, '.', '') }}
                                                    </td>
                                                @endif
                                            @endforeach
                                        @else
                                            @foreach ($recipe->sales as $sale)
                                                <td> SGD {{ number_format($sale->price, 2, '.', '') }}</td>
                                                <td>
                                                    @if ($sale->pure_cost > 0)
                                                        {{ $sale->pure_cost }} %
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td> SGD
                                                    {{ number_format($sale->price - $recipe->cost, 2, '.', '') }}
                                                </td>
                                                @foreach ($price_levels as $price)
                                                    @if ($price->type == 1)
                                                        <td> SGD 0.00 </td>
                                                        <td> - </td>
                                                        <td> SGD
                                                            {{ number_format(0 - $recipe->cost, 2, '.', '') }}</td>
                                                    @endif
                                                    @if ($price->type == 2)
                                                        <td> SGD 0.00 </td>
                                                        <td> - </td>
                                                        <td> SGD
                                                            {{ number_format(0 - $recipe->cost, 2, '.', '') }}</td>
                                                    @endif
                                                    @if ($price->type == 3)
                                                        <td> SGD 0.00 </td>
                                                        <td> - </td>
                                                        <td> SGD
                                                            {{ number_format(0 - $recipe->cost, 2, '.', '') }}</td>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endif
                                    </tr>
                                    @foreach ($recipe->ingredients as $index => $ingredient)
                                        <tr>
                                            <td>
                                                <span style="justify-content: center; display: flex;">
                                                    {{ $index == 0 ? 'Ingredient(s):' : '' }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $ingredient->item->name }} - {{ $ingredient->qty }}
                                                {{ $ingredient->uom_text }}
                                            </td>
                                            <td>SGD {{ $ingredient->cost }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            @foreach ($price_levels as $price)
                                                @if ($price->type != 0)
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    @endif
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

        var recipes = <?php echo json_encode($recipes); ?>;
    </script>
@endpush
