@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">

                <div class="col-sm-9" style="display: flex;">
                    <div>
                        <i class="fas fa-exchange-alt fa-2x"
                            style="color: #1c75bc;
                        border: #1c75bc solid 4px;
                        border-radius: 10px;
                        padding: 10px;"></i>
                    </div>
                    <h5 style="margin-left: 15px;margin-top: 15px"> Purchase Report </h5>
                    <form action="" id="generate">
                        <div style="margin-left: 30px;margin-top: 10px;display: flex">
                            <div style="width: 25%; height: 100%;">
                                <input type="text" class="form-control date-picker fromdate" name="from_date"
                                    id="from_date" placeholder="From" required>
                            </div>
                            <div style="width: 25%; height: 100%;margin-left: 7px">
                                <input type="text" class="form-control date-picker todate" name="to_date" id="to_date"
                                    placeholder="To" required>
                            </div>
                            <div style="margin-left: 7px;">
                                <a class="btn btn-md btn-default" onclick="generate()">
                                    Generate
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-sm-3">
                    <ol class="breadcrumb float-sm-right" style="margin-top:15px;">
                        <form action="{{ route('report-purchase-summary-excel') }}" method="POST">
                            @csrf
                            <li style="margin-right: 5px;">
                                <button type="submit" class="btn btn-sm btn-default">
                                    <i class="far fa-file-excel"></i> Save to Excel
                                </button>
                                <input type="hidden" name="excel_form_date" id="excel_form_date">
                                <input type="hidden" name="excel_to_date" id="excel_to_date">
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
                    @if ($classes != null)
                        <table class="table table-sm table-borderless text-nowrap">
                            <thead
                                style="color: #000000;
                            background-color: #f2f2f2;border-bottom: 2px solid #ddd !important;
                            border-top: 1px solid #ddd !important;">
                                <tr>
                                    <th>Item Name</th>
                                    <th>Bar Code</th>
                                    <th>Item Size</th>
                                    <th>Unit Cost</th>
                                    <th>Purchase</th>
                                    <th>Purchases Volume</th>
                                    <th>Purchases Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($classes as $key => $class)
                                    <tr>
                                        @php
                                            $class_name = \App\Models\Classes::where('id', $class->class_id)->get()[0];
                                        @endphp
                                        <td style="font-size: 110%;">{{ $class_name->name }} </span></td>
                                    <tr>
                                        @php
                                            $categories_data = \App\Models\Category::whereIn('id', $categories)
                                                ->where('class_id', $class->class_id)
                                                ->get();
                                            $main_total_purchase_volume = 0;
                                            $main_total_purchase_cost = 0;
                                        @endphp
                                        @foreach ($categories_data as $index => $category)
                                    <tr>
                                        <td style="font-size: 110%;">{{ $category->name }}</td>
                                    </tr>
                                    @php
                                        $items_data = \App\Models\Item::whereIn('id', $items)
                                            ->where('category_id', $category->id)
                                            ->select('id', 'name')
                                            ->get();
                                        $total_purchase_volume = 0;
                                        $total_purchase_cost = 0;
                                    @endphp
                                    @foreach ($items_data as $index => $item)
                                        @php
                                            $countable_unit = $item->itemSize->first()->countable_unit;
                                            if ($item->itemSize->first()->countable_size == 'ml') {
                                                $item->itemSize->first()->countable_unit = $item->itemSize->first()->countable_unit;
                                            } elseif ($item->itemSize->first()->countable_size == 'L') {
                                                $item->itemSize->first()->countable_unit = $item->itemSize->first()->countable_unit * 1000;
                                            } elseif ($item->itemSize->first()->countable_size == 'L') {
                                                $item->itemSize->first()->countable_unit = $item->itemSize->first()->countable_unit * 1000;
                                            } elseif ($item->itemSize->first()->countable_size == 'oz') {
                                                $item->itemSize->first()->countable_unit = $item->itemSize->first()->countable_unit * 29.5735;
                                            } elseif ($item->itemSize->first()->countable_size == 'cL') {
                                                $item->itemSize->first()->countable_unit = $item->itemSize->first()->countable_unit * 10;
                                            } elseif ($item->itemSize->first()->countable_size == '100-mL') {
                                                $item->itemSize->first()->countable_unit = $item->itemSize->first()->countable_unit * 100;
                                            } elseif ($item->itemSize->first()->countable_size == 'hL') {
                                                $item->itemSize->first()->countable_unit = $item->itemSize->first()->countable_unit * 100000;
                                            } elseif ($item->itemSize->first()->countable_size == '30-mL') {
                                                $item->itemSize->first()->countable_unit = $item->itemSize->first()->countable_unit * 30;
                                            } elseif ($item->itemSize->first()->countable_size == '25-mL') {
                                                $item->itemSize->first()->countable_unit = $item->itemSize->first()->countable_unit * 25;
                                            } elseif ($item->itemSize->first()->countable_size == '45-mL') {
                                                $item->itemSize->first()->countable_unit = $item->itemSize->first()->countable_unit * 45;
                                            }
                                            $total_purchase_volume += optional($item->itemPackage->first())->qty * $item->itemSize->first()->countable_unit * $item->invoiceDetails->first()->purchased_quantity;
                                            $total_purchase_unit = 'ml';
                                            $total_purchase_cost += $item->invoiceDetails->first()->unit_price * $item->invoiceDetails->first()->purchased_quantity;
                                        @endphp
                                        <tr>
                                            <td><span style="margin-left: 20px;">{{ $item->name }}</span></td>
                                            <td>{{ $item->itemPackage->first()->package_barcode }}</td>
                                            <td>{{ $countable_unit }} {{ $item->itemSize->first()->countable_size }}</td>
                                            <td>SGD
                                                {{ number_format($item->invoiceDetails->first()->unit_price, 2, '.', ',') }}
                                            </td>
                                            <td>{{ number_format($item->invoiceDetails->first()->purchased_quantity, 1, '.', '') }}
                                                {{ optional($item->itemPackage->first())->unit_to }}</td>
                                            <td>{{ optional($item->itemPackage->first())->qty * $item->itemSize->first()->countable_unit * $item->invoiceDetails->first()->purchased_quantity }}
                                                {{ $total_purchase_unit }} </td>
                                            <td>SGD
                                                {{ number_format($item->invoiceDetails->first()->unit_price * $item->invoiceDetails->first()->purchased_quantity, 2, '.', ',') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    @php
                                        $main_total_purchase_volume += $total_purchase_volume;
                                        $main_total_purchase_unit = 'ml';
                                        $main_total_purchase_cost += $total_purchase_cost;
                                    @endphp

                                    <tr
                                        style="border-bottom: thin solid black !important;
                                            border-top-color: black !important;padding-top: 8px;
                                            background-color: #e7e7e7 !important;">
                                        <td style="font-size: 110%;font-weight: bold";>
                                            Total {{ $category->name }}:
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{ $total_purchase_volume }} {{ $total_purchase_unit }}</td>
                                        <td> SGD {{ number_format($total_purchase_cost, 2, '.', ',') }}</td>
                                    </tr>
                                @endforeach
                                <tr
                                    style="border-bottom: thin solid black !important;
                                            border-top-color: black !important;padding-top: 8px;
                                            background-color: #e7e7e7 !important;">
                                    <td style="font-size: 110%;font-weight: bold";>
                                        Total {{ $class_name->name }}:
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ $main_total_purchase_volume }} {{ $main_total_purchase_unit }}</td>
                                    <td>SGD {{ number_format($main_total_purchase_cost, 2, '.', ',') }}</td>
                                </tr>
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
            $(".date-picker").datepicker({
                dateFormat: "yy-mm-dd",
            });
        });

        $(".fromdate")
            .datepicker({
                dateFormat: "yy-mm-dd",
                onSelect: function(dateText) {

                    $(this).change();
                    $("#excel_form_date").val(dateText);
                    console.log("Got change>> ", $("#excel_form_date").val());
                }
            })
            .on("change", function() {
                console.log("Got change event from field");
            });

        $(".todate")
            .datepicker({
                dateFormat: "yy-mm-dd",
                onSelect: function(dateText) {
                    $(this).change();
                    $("#excel_to_date").val(dateText);
                    console.log("Got change>> ", $("#excel_to_date").val());
                }
            })
            .on("change", function() {
                console.log("Got change event from field");
            });
    </script>
@endpush
