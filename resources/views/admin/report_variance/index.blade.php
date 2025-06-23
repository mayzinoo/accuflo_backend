@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">

                <div class="col-sm-8" style="display: flex;">

                    <h5 style="margin-left: 15px;margin-top: 15px"> Variance Report</h5>
                    <form action="" id="generate">
                        <div style="margin-left: 30px;margin-top: 15px;display: flex">
                            <div>
                                <select class="form-control" name="category_quality_id" id="category_quality_id"
                                    style="height: 98%;" onchange="changeCategoryQuality(this.value)">
                                    @foreach ($report_types as $report_type)
                                        <option value="{{ $report_type }}"
                                            {{ $report_type == $category_quality_status ? 'selected' : '' }}>
                                            {{ $report_type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="margin-left: 5px;">
                                <select class="form-control" name="detail_summary_id" id="detail_summary_id"
                                    style="height: 98%;" onchange="changeDetailSummary(this.value)">
                                    @foreach ($report_types_1 as $report_type_1)
                                        <option value="{{ $report_type_1 }}"
                                            {{ $report_type_1 == $detail_summary_status ? 'selected' : '' }}>
                                            {{ $report_type_1 }}
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

                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right" style="margin-top:15px;">
                        <form action="{{ route('report-variance-excel') }}" method="POST">
                            @csrf
                            <li style="margin-right: 5px;">
                                <button type="submit" class="btn btn-sm btn-default">
                                    <i class="far fa-file-excel"></i> Save to Excel
                                </button>
                                <input type="hidden" name="excel_category_quality_id" id="excel_category_quality_id">
                                <input type="hidden" name="excel_detail_summary_id" id="excel_detail_summary_id">
                            </li>
                        </form>

                       <form action="{{ route('report-variance-pdf') }}" method="POST">
                            @csrf
                            <li>
                                <button type="submit" class="btn btn-sm btn-default">
                                    <i class="far fa-file-pdf"></i> Save to PDF
                                </button>
                                <input type="hidden" name="pdf_category_quality_id" id="pdf_category_quality_id">
                                <input type="hidden" name="pdf_detail_summary_id" id="pdf_detail_summary_id">
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
                    @if (
                        ($category_quality_status == 'Category' && $detail_summary_status == 'Detailed') ||
                            ($category_quality_status == 'Quality' && $detail_summary_status == 'Detailed'))
                        <table class="table table-borderless table-responsive text-nowrap"
                            style="line-height: 0.7 !important">
                            <thead style="color: #000000;background-color: #f2f2f2;">
                                <tr style="border-bottom: 2px solid #dee2e6;border-top: 1px solid #dee2e6;">
                                    <th>Item Name</th>
                                    <th>Sold</th>
                                    <th>Sold (cost)</th>
                                    <th>Used</th>
                                    <th>Used (cost)</th>
                                    <th>Missing</th>
                                    <th>% Missing</th>
                                    <th>Missing (cost)</th>
                                    <th>Previous On-Hand</th>
                                    <th>Purchases</th>
                                    <th>Purchases (cost)</th>
                                    <th>On-Hand</th>
                                    <th>On-Hand (cost)</th>
                                    <th>On-Hand (unit cost)</th>
                                    <th>Pour Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($classes as $key => $class)
                                    @php
                                        if ($category_quality_status == 'Category') {
                                            $categories_qualities_data = \App\Models\Category::whereIn('id', $categories)
                                                ->where('class_id', $class->class_id)
                                                ->get();
                                        } else {
                                            $categories_qualities_data = \App\Models\Quality::whereIn('id', $qualities)
                                                ->where('class_id', $class->class_id)
                                                ->get();
                                        }
                                    @endphp
                                    @foreach ($categories_qualities_data as $index => $category_quality)
                                        <tr>
                                            <td style="font-size: 110%;">{{ $category_quality->name }}</td>
                                        </tr>
                                        @php
                                            if ($category_quality_status == 'Category') {
                                                $fullcount_data = get_full_count_with_category($items, $category_quality->id);
                                            } else {
                                                $fullcount_data = get_full_count_with_quality($items, $category_quality->id);
                                            }
                                            $total_sold = 0;
                                            $total_sold_cost = 0;
                                            $total_used = 0;
                                            $total_used_cost = 0;
                                            $total_missing = 0;
                                            $total_missing_cost = 0;
                                            $total_percent_missing = 0;
                                            $total_purchase = 0;
                                            $total_purchase_cost = 0;
                                            $total_on_hand = 0;
                                            $total_on_hand_cost = 0;
                                            $total_pour_cost = 0;
                                        @endphp
                                        @if ($fullcount_data->count())
                                            @foreach ($fullcount_data as $index => $fullcount)
                                                <tr>
                                                    @php
                                                        $one_btl_price = $fullcount->unit_price / $fullcount->package_qty / $fullcount->countable_unit;
                                                        [$sold_qty, $revenue, $sale_price, $sale_package_id, $sale_uom_text, $sale_ingredients_qty] = get_total_sales_data($fullcount->item_id);
                                                        if ($sale_package_id == null || $sale_uom_text == null || $sold_qty == null || $sale_price == null || $sale_ingredients_qty == null) {
                                                            $sold_data = 0;
                                                            $sold_cost_data = 0;
                                                            $ideal_pour_cost = 0;
                                                        } else {
                                                            [$sold_data, $sold_cost_data, $ideal_pour_cost] = get_sold_cost_data($fullcount->unit_price, $fullcount->invoice_purchase_package, $fullcount->countable_unit, $fullcount->countable_size, $sale_package_id, $sale_uom_text, $sold_qty, $sale_price, $sale_ingredients_qty);
                                                        }
                                                        $used_purchase_data = get_total_purchase_data($fullcount->item_id);
                                                        $used_data = (float) $used_purchase_data - $fullcount->used_fullcount;
                                                        if ($fullcount->countable_size == 'ml') {
                                                            $sold = $sold_data;
                                                            $used_purchase = $used_purchase_data;
                                                            $used = $used_data;
                                                            $on_hand = $fullcount->used_fullcount;
                                                        } elseif ($fullcount->countable_size == 'L') {
                                                            $sold = (int) $sold_data * 1000;
                                                            $used_purchase = (int) $used_purchase_data * 1000;
                                                            $used = (int) $used_data * 1000;
                                                            $on_hand = (int) $fullcount->used_fullcount * 1000;
                                                        } elseif ($fullcount->countable_size == 'oz') {
                                                            $sold = (int) $sold_data * 29.5735296;
                                                            $used_purchase = (int) $used_purchase_data * 29.5735296;
                                                            $used = (int) $used_data * 29.5735296;
                                                            $on_hand = (int) $fullcount->used_fullcount * 29.5735296;
                                                        } elseif ($fullcount->countable_size == 'cL') {
                                                            $sold = (int) $sold_data * 10;
                                                            $used_purchase = (int) $used_purchase_data * 10;
                                                            $used = (int) $used_data * 10;
                                                            $on_hand = (int) $fullcount->used_fullcount * 10;
                                                        } elseif ($fullcount->countable_size == '100-mL') {
                                                            $sold = (int) $sold_data * 100;
                                                            $used_purchase = (int) $used_purchase_data * 100;
                                                            $used = (int) $used_data * 100;
                                                            $on_hand = (int) $fullcount->used_fullcount * 100;
                                                        } elseif ($fullcount->countable_size == 'hL') {
                                                            $sold = (int) $sold_data * 100000;
                                                            $used_purchase = (int) $used_purchase_data * 100000;
                                                            $used = (int) $used_data * 100000;
                                                            $on_hand = (int) $fullcount->used_fullcount * 100000;
                                                        } elseif ($fullcount->countable_size == '30-mL') {
                                                            $sold = (int) $sold_data * 30;
                                                            $used_purchase = (int) $used_purchase_data * 30;
                                                            $used = (int) $used_data * 30;
                                                            $on_hand = (int) $fullcount->used_fullcount * 30;
                                                        } elseif ($fullcount->countable_size == '25-mL') {
                                                            $sold = (int) $sold_data * 25;
                                                            $used_purchase = (int) $used_purchase_data * 25;
                                                            $used = (int) $used_data * 25;
                                                            $on_hand = (int) $fullcount->used_fullcount * 25;
                                                        } elseif ($fullcount->countable_size == '45-mL') {
                                                            $sold = (int) $sold_data * 45;
                                                            $used_purchase = (int) $used_purchase_data * 45;
                                                            $used = (int) $used_data * 45;
                                                            $on_hand = (int) $fullcount->used_fullcount * 45;
                                                        }
                                                        
                                                        $sold_cost = $sold_cost_data * (float) $sold_data;
                                                        $used_cost = $one_btl_price * $used_data;
                                                        $missing = $used - (float) $sold;
                                                        $percent_missing = ($missing / $used) * 100;
                                                        $missing_cost = $used_cost - $sold_cost;
                                                        $purchase_cost = $fullcount->extended_price;
                                                        $on_hand_cost = $one_btl_price * $fullcount->used_fullcount;
                                                        $on_hand_unit_cost = $on_hand_cost / $fullcount->used_fullcount;
                                                        
                                                        if ($sold_cost == 0) {
                                                            $pour_cost = 0;
                                                        } else {
                                                            $pour_cost = ($used_cost / $sold_cost) * 100;
                                                        }
                                                        
                                                        $total_sold += (int) $sold;
                                                        $total_sold_cost += $sold_cost;
                                                        $total_used += $used;
                                                        $total_used_cost += $used_cost;
                                                        $total_missing += $missing;
                                                        $total_missing_cost += $missing_cost;
                                                        $total_percent_missing += $percent_missing;
                                                        $total_purchase += (int) $used_purchase;
                                                        $total_purchase_cost += $purchase_cost;
                                                        $total_on_hand += $on_hand;
                                                        $total_on_hand_cost += $on_hand_cost;
                                                        if ($total_sold_cost == 0) {
                                                            $total_pour_cost = 0;
                                                        } else {
                                                            $total_pour_cost = ($total_used_cost / $total_sold_cost) * 100;
                                                        }
                                                    @endphp
                                                    <td>
                                                        <span style="margin-left: 20px;">{{ $fullcount->item_name }}</span>
                                                    </td>

                                                    <td>
                                                        @if ($sold != 0)
                                                            {{ round($sold) }}ml
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if ($sold_cost != 0)
                                                            SGD{{ number_format($sold_cost, 2, '.', ',') }}
                                                        @endif
                                                    </td>

                                                    @if (str_starts_with($used, '-'))
                                                        <td style="color: red;">
                                                            {{ $used }}ml
                                                        </td>
                                                    @else
                                                        <td>
                                                            {{ $used }}ml
                                                        </td>
                                                    @endif

                                                    @if (str_starts_with($used_cost, '-'))
                                                        <td style="color: red;">
                                                            @if ($used_cost != 0)
                                                                -SGD{{ number_format(abs($used_cost), 2, '.', ',') }}
                                                            @endif
                                                        </td>
                                                    @else
                                                        <td>
                                                            @if ($used_cost != 0)
                                                                +SGD{{ number_format(abs($used_cost), 2, '.', ',') }}
                                                            @endif

                                                        </td>
                                                    @endif

                                                    @if (str_starts_with($missing, '-'))
                                                        <td>
                                                            +{{ abs($missing) }}ml
                                                        </td>
                                                    @else
                                                        <td>
                                                            -{{ abs($missing) }}ml
                                                        </td>
                                                    @endif

                                                    @if (str_starts_with($percent_missing, '-'))
                                                        <td>
                                                            -{{ number_format(abs($percent_missing), 1, '.', '') }} %
                                                        </td>
                                                    @else
                                                        <td>
                                                            +{{ number_format(abs($percent_missing), 1, '.', '') }} %
                                                        </td>
                                                    @endif

                                                    @if (str_starts_with($missing_cost, '-'))
                                                        <td>
                                                            @if ($missing_cost != 0)
                                                                +SGD{{ number_format(abs($missing_cost), 2, '.', ',') }}
                                                            @endif
                                                        </td>
                                                    @else
                                                        <td>
                                                            @if ($missing_cost != 0)
                                                                -SGD{{ number_format(abs($missing_cost), 2, '.', ',') }}
                                                            @endif
                                                        </td>
                                                    @endif

                                                    <td></td>

                                                    <td>
                                                        @if ($used_purchase != 0 && $used_purchase != '')
                                                            {{ $used_purchase }}ml
                                                        @endif
                                                    </td>


                                                    <td>
                                                        @if ($purchase_cost != '')
                                                            SGD{{ number_format($purchase_cost, 2, '.', ',') }}
                                                        @endif
                                                    </td>

                                                    <td>{{ $on_hand }} ml</td>

                                                    <td>
                                                        @if ($on_hand_cost != 0)
                                                            SGD{{ number_format($on_hand_cost, 2, '.', ',') }}
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if ($on_hand_unit_cost != 0)
                                                            {{ number_format($on_hand_unit_cost, 3, '.', ' ') }}
                                                        @endif
                                                    </td>

                                                    @if (str_starts_with($pour_cost, '-'))
                                                        <td>
                                                            @if ($pour_cost != 0)
                                                                -{{ number_format(abs($pour_cost), 1, '.', '') }} %
                                                            @endif
                                                        </td>
                                                    @else
                                                        <td>
                                                            @if ($pour_cost != 0)
                                                                +{{ number_format(abs($pour_cost), 1, '.', '') }} %
                                                            @endif
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                            <tr
                                                style="border-bottom: thin solid black !important;
                                                            border-top-color: black !important;padding-top: 8px;
                                                            background-color: #e7e7e7 !important;">
                                                <td style="font-size: 110%;font-weight: bold";>
                                                    Total {{ $category_quality->name }}:
                                                </td>

                                                <td>
                                                    @if ($total_sold != 0)
                                                        <span style="font-size: 110%;font-weight: bold">
                                                            {{ $total_sold }}</span>ml
                                                    @endif
                                                </td>

                                                <td style="font-size: 110%;font-weight: bold">
                                                    @if ($total_sold_cost != 0)
                                                        SGD{{ number_format($total_sold_cost, 2, '.', ',') }}
                                                    @endif

                                                </td>

                                                @if (str_starts_with($total_used, '-'))
                                                    <td style="color:red;">
                                                        <span style="font-size: 110%;font-weight: bold">
                                                            -{{ abs($total_used) }} </span>ml
                                                    </td>
                                                @else
                                                    <td>
                                                        <span style="font-size: 110%;font-weight: bold">
                                                            +{{ abs($total_used) }} </span>ml
                                                    </td>
                                                @endif

                                                @if (str_starts_with($total_used_cost, '-'))
                                                    <td style="font-size: 110%;font-weight: bold; color:red;">
                                                        @if ($total_used_cost != 0)
                                                            -SGD{{ number_format(abs($total_used_cost), 2, '.', ',') }}
                                                        @endif
                                                    </td>
                                                @else
                                                    <td style="font-size: 110%;font-weight: bold;">
                                                        @if ($total_used_cost != 0)
                                                            +SGD{{ number_format(abs($total_used_cost), 2, '.', ',') }}
                                                        @endif
                                                    </td>
                                                @endif

                                                @if (str_starts_with($total_missing, '-'))
                                                    <td>
                                                        <span style="font-size: 110%;font-weight: bold">
                                                            +{{ abs($total_missing) }}
                                                        </span>ml
                                                    </td>
                                                @else
                                                    <td>
                                                        <span style="font-size: 110%;font-weight: bold">
                                                            -{{ abs($total_missing) }}
                                                        </span>ml
                                                    </td>
                                                @endif

                                                @if (str_starts_with($total_percent_missing, '-'))
                                                    <td> -{{ number_format($total_percent_missing, 1, '.', ',') }} %</td>
                                                @else
                                                    <td> +{{ number_format($total_percent_missing, 1, '.', ',') }} %</td>
                                                @endif

                                                @if (str_starts_with($total_missing_cost, '-'))
                                                    <td style="font-size: 110%;font-weight: bold">
                                                        @if ($total_missing_cost != 0)
                                                            +SGD{{ number_format(abs($total_missing_cost), 2, '.', ',') }}
                                                        @endif
                                                    </td>
                                                @else
                                                    <td style="font-size: 110%;font-weight: bold">
                                                        @if ($total_missing_cost != 0)
                                                            -SGD{{ number_format(abs($total_missing_cost), 2, '.', ',') }}
                                                        @endif
                                                    </td>
                                                @endif

                                                <td></td>

                                                <td>
                                                    @if ($total_purchase != 0)
                                                        <span
                                                            style="font-size: 110%;font-weight: bold">{{ $total_purchase }}</span>ml
                                                    @endif
                                                </td>

                                                <td style="font-size: 110%;font-weight: bold">
                                                    @if ($total_purchase_cost != 0)
                                                        SGD{{ number_format($total_purchase_cost, 2, '.', ',') }}
                                                    @endif
                                                </td>

                                                <td>
                                                    <span
                                                        style="font-size: 110%;font-weight: bold">{{ $total_on_hand }}</span>ml
                                                </td>

                                                <td style="font-size: 110%;font-weight: bold">
                                                    @if ($total_on_hand_cost != 0)
                                                        SGD{{ number_format($total_on_hand_cost, 2, '.', ',') }}
                                                    @endif
                                                </td>
                                                <td></td>

                                                @if (str_starts_with($total_pour_cost, '-'))
                                                    <td style="font-size: 110%;font-weight: bold">
                                                        @if ($total_pour_cost != 0)
                                                            -{{ number_format(abs($total_pour_cost), 1, '.', '') }} %
                                                        @endif
                                                    </td>
                                                @else
                                                    <td style="font-size: 110%;font-weight: bold">
                                                        @if ($total_pour_cost != 0)
                                                            +{{ number_format(abs($total_pour_cost), 1, '.', '') }} %
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                    @if (
                        ($category_quality_status == 'Category' && $detail_summary_status == 'Summary') ||
                            ($category_quality_status == 'Quality' && $detail_summary_status == 'Summary'))
                        <table class="table table-borderless table-responsive text-nowrap"
                            style="line-height: 0.7 !important">
                            <thead style="color: #000000;background-color: #f2f2f2;">
                                <tr style="border-bottom: 2px solid #dee2e6;border-top: 1px solid #dee2e6;">
                                    <th>Item Name</th>
                                    <th>Used</th>
                                    <th>Sold</th>
                                    <th>Missing</th>
                                    <th>% Missing</th>
                                    <th>Missing (cost)</th>
                                    <th>Used (cost)</th>
                                    <th>On-Hand (cost)</th>
                                    <th>Revenue</th>
                                    <th>Pour Cost</th>
                                    <th>Ideal Pour Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $grand_total_sold_cost = 0;
                                    $grand_total_used_cost = 0;
                                    $grand_total_missing_cost = 0;
                                    $grand_total_on_hand_cost = 0;
                                    $grand_total_revenue = 0;
                                    $grand_total_pour_cost = 0;
                                    $grand_total_ideal_pour_cost = 0;
                                @endphp
                                @foreach ($classes as $key => $class)
                                    <tr>
                                        @php
                                            $class_name = \App\Models\Classes::where('id', $class->class_id)->get()[0];
                                        @endphp
                                        <td style="font-size: 110%;">{{ $class_name->name }} </td>
                                    </tr>
                                    @php
                                        if ($category_quality_status == 'Category') {
                                            $categories_qualities_data = \App\Models\Category::whereIn('id', $categories)
                                                ->where('class_id', $class->class_id)
                                                ->get();
                                        } else {
                                            $categories_qualities_data = \App\Models\Quality::whereIn('id', $qualities)
                                                ->where('class_id', $class->class_id)
                                                ->get();
                                        }
                                        $main_total_sold = 0;
                                        $main_total_sold_cost = 0;
                                        $main_total_used = 0;
                                        $main_total_used_cost = 0;
                                        $main_total_missing = 0;
                                        $main_total_percent_missing = 0;
                                        $main_total_missing_cost = 0;
                                        $main_total_on_hand_cost = 0;
                                        $main_total_revenue = 0;
                                        $main_total_pour_cost = 0;
                                        $main_total_ideal_pour_cost = 0;
                                    @endphp
                                    @foreach ($categories_qualities_data as $index => $category_quality)
                                        @php
                                            if ($category_quality_status == 'Category') {
                                                $fullcount_data = get_full_count_with_category($items, $category_quality->id);
                                            } else {
                                                $fullcount_data = get_full_count_with_quality($items, $category_quality->id);
                                            }
                                            $total_sold = 0;
                                            $total_sold_cost = 0;
                                            $total_used = 0;
                                            $total_used_cost = 0;
                                            $total_missing = 0;
                                            $total_percent_missing = 0;
                                            $total_missing_cost = 0;
                                            $total_on_hand_cost = 0;
                                            $total_revenue = 0;
                                            $total_pour_cost = 0;
                                            $total_ideal_pour_cost = 0;
                                        @endphp
                                        @if ($fullcount_data->count())
                                            @foreach ($fullcount_data as $index => $fullcount)
                                                @php
                                                    $one_btl_price = $fullcount->unit_price / $fullcount->package_qty / $fullcount->countable_unit;
                                                    [$sold_qty, $revenue, $sale_price, $sale_package_id, $sale_uom_text, $sale_ingredients_qty] = get_total_sales_data($fullcount->item_id);
                                                    if ($sale_package_id == null || $sale_uom_text == null || $sold_qty == null || $sale_price == null || $sale_ingredients_qty == null) {
                                                        $sold_data = 0;
                                                        $sold_cost_data = 0;
                                                        $ideal_pour_cost = 0;
                                                    } else {
                                                        [$sold_data, $sold_cost_data, $ideal_pour_cost] = get_sold_cost_data($fullcount->unit_price, $fullcount->invoice_purchase_package, $fullcount->countable_unit, $fullcount->countable_size, $sale_package_id, $sale_uom_text, $sold_qty, $sale_price, $sale_ingredients_qty);
                                                    }
                                                    $used_purchase_data = get_total_purchase_data($fullcount->item_id);
                                                    $used_data = (float) $used_purchase_data - $fullcount->used_fullcount;
                                                    if ($fullcount->countable_size == 'ml') {
                                                        $sold = $sold_data;
                                                        $used_purchase = $used_purchase_data;
                                                        $used = $used_data;
                                                        $on_hand = $fullcount->used_fullcount;
                                                    } elseif ($fullcount->countable_size == 'L') {
                                                        $sold = (int) $sold_data * 1000;
                                                        $used_purchase = (int) $used_purchase_data * 1000;
                                                        $used = (int) $used_data * 1000;
                                                        $on_hand = (int) $fullcount->used_fullcount * 1000;
                                                    } elseif ($fullcount->countable_size == 'oz') {
                                                        $sold = (int) $sold_data * 29.5735296;
                                                        $used_purchase = (int) $used_purchase_data * 29.5735296;
                                                        $used = (int) $used_data * 29.5735296;
                                                        $on_hand = (int) $fullcount->used_fullcount * 29.5735296;
                                                    } elseif ($fullcount->countable_size == 'cL') {
                                                        $sold = (int) $sold_data * 10;
                                                        $used_purchase = (int) $used_purchase_data * 10;
                                                        $used = (int) $used_data * 10;
                                                        $on_hand = (int) $fullcount->used_fullcount * 10;
                                                    } elseif ($fullcount->countable_size == '100-mL') {
                                                        $sold = (int) $sold_data * 100;
                                                        $used_purchase = (int) $used_purchase_data * 100;
                                                        $used = (int) $used_data * 100;
                                                        $on_hand = (int) $fullcount->used_fullcount * 100;
                                                    } elseif ($fullcount->countable_size == 'hL') {
                                                        $sold = (int) $sold_data * 100000;
                                                        $used_purchase = (int) $used_purchase_data * 100000;
                                                        $used = (int) $used_data * 100000;
                                                        $on_hand = (int) $fullcount->used_fullcount * 100000;
                                                    } elseif ($fullcount->countable_size == '30-mL') {
                                                        $sold = (int) $sold_data * 30;
                                                        $used_purchase = (int) $used_purchase_data * 30;
                                                        $used = (int) $used_data * 30;
                                                        $on_hand = (int) $fullcount->used_fullcount * 30;
                                                    } elseif ($fullcount->countable_size == '25-mL') {
                                                        $sold = (int) $sold_data * 25;
                                                        $used_purchase = (int) $used_purchase_data * 25;
                                                        $used = (int) $used_data * 25;
                                                        $on_hand = (int) $fullcount->used_fullcount * 25;
                                                    } elseif ($fullcount->countable_size == '45-mL') {
                                                        $sold = (int) $sold_data * 45;
                                                        $used_purchase = (int) $used_purchase_data * 45;
                                                        $used = (int) $used_data * 45;
                                                        $on_hand = (int) $fullcount->used_fullcount * 45;
                                                    }
                                                    
                                                    $sold_cost = $sold_cost_data * (float) $sold_data;
                                                    $used_cost = $one_btl_price * $used_data;
                                                    $missing_cost = $used_cost - $sold_cost;
                                                    $missing = $used - (float) $sold;
                                                    $percent_missing = ($missing / $used) * 100;
                                                    $purchase_cost = $fullcount->extended_price;
                                                    $on_hand_cost = $one_btl_price * $fullcount->used_fullcount;
                                                    $on_hand_unit_cost = $on_hand_cost / $fullcount->used_fullcount;
                                                    if ($sold_cost == 0) {
                                                        $pour_cost = 0;
                                                    } else {
                                                        $pour_cost = ($used_cost / $sold_cost) * 100;
                                                    }
                                                    
                                                    $total_sold += (int) $sold;
                                                    $total_sold_cost += $sold_cost;
                                                    $total_used += $used;
                                                    $total_used_cost += $used_cost;
                                                    $total_missing += $missing;
                                                    $total_percent_missing += $percent_missing;
                                                    $total_missing_cost += $missing_cost;
                                                    $total_on_hand_cost += $on_hand_cost;
                                                    $total_revenue += (int) $revenue;
                                                    if ($total_sold_cost == 0) {
                                                        $total_pour_cost = 0;
                                                    } else {
                                                        $total_pour_cost = ($total_used_cost / $total_sold_cost) * 100;
                                                    }
                                                    $total_ideal_pour_cost += $ideal_pour_cost;
                                                @endphp
                                            @endforeach
                                            <tr>
                                                <td>
                                                    <span style="margin-left: 20px;">
                                                        {{ $category_quality->name }}</span>
                                                </td>

                                                @if (str_starts_with($total_used, '-'))
                                                    <td style="color: red;">
                                                        -{{ abs($total_used) }}ml
                                                    </td>
                                                @else
                                                    <td>
                                                        +{{ abs($total_used) }}ml
                                                    </td>
                                                @endif

                                                <td>
                                                    @if ($total_sold != 0)
                                                        {{ $total_sold }}ml
                                                    @endif
                                                </td>

                                                @if (str_starts_with($total_missing, '-'))
                                                    <td style="color: red;">
                                                        -{{ abs($total_missing) }}ml
                                                    </td>
                                                @else
                                                    <td>
                                                        +{{ abs($total_missing) }}ml
                                                    </td>
                                                @endif

                                                @if (str_starts_with($total_percent_missing, '-'))
                                                    <td style="color: red;">
                                                        -{{ number_format($total_percent_missing, 1, '.', ',') }} %
                                                    </td>
                                                @else
                                                    <td>
                                                        +{{ number_format($total_percent_missing, 1, '.', ',') }} %
                                                    </td>
                                                @endif

                                                @if (str_starts_with($total_missing_cost, '-'))
                                                    <td style="color: red;">
                                                        @if ($total_missing_cost != 0)
                                                            -SGD{{ number_format(abs($total_missing_cost), 2, '.', ',') }}
                                                        @endif
                                                    </td>
                                                @else
                                                    <td>
                                                        @if ($total_missing_cost != 0)
                                                            +SGD{{ number_format(abs($total_missing_cost), 2, '.', ',') }}
                                                        @endif
                                                    </td>
                                                @endif

                                                @if (str_starts_with($total_used_cost, '-'))
                                                    <td style="color:red;">
                                                        @if ($total_used_cost != 0)
                                                            -SGD{{ number_format(abs($total_used_cost), 2, '.', ',') }}
                                                        @endif
                                                    </td>
                                                @else
                                                    <td>
                                                        @if ($total_used_cost != 0)
                                                            +SGD{{ number_format(abs($total_used_cost), 2, '.', ',') }}
                                                        @endif
                                                    </td>
                                                @endif

                                                <td>
                                                    @if ($total_on_hand_cost != 0)
                                                        SGD{{ number_format($total_on_hand_cost, 2, '.', ',') }}
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($total_revenue != 0)
                                                        SGD{{ number_format($total_revenue, 2, '.', ',') }}
                                                    @endif
                                                </td>

                                                @if (str_starts_with($total_pour_cost, '-'))
                                                    <td>
                                                        @if ($total_pour_cost != 0)
                                                            -{{ number_format(abs($total_pour_cost), 1, '.', '') }} %
                                                        @endif
                                                    </td>
                                                @else
                                                    <td>
                                                        @if ($total_pour_cost != 0)
                                                            +{{ number_format(abs($total_pour_cost), 1, '.', '') }} %
                                                        @endif
                                                    </td>
                                                @endif

                                                <td>
                                                    {{ number_format($total_ideal_pour_cost, 1, '.', '') }} %
                                                </td>
                                            </tr>
                                            @php
                                                $main_total_sold += $total_sold;
                                                $main_total_sold_cost += $total_sold_cost;
                                                $main_total_used += $total_used;
                                                $main_total_used_cost += $total_used_cost;
                                                $main_total_missing += $total_missing;
                                                $main_total_percent_missing += $total_percent_missing;
                                                $main_total_missing_cost += $total_missing_cost;
                                                $main_total_on_hand_cost += $total_on_hand_cost;
                                                $main_total_revenue += $total_revenue;
                                                if ($main_total_sold_cost == 0) {
                                                    $main_total_pour_cost = 0;
                                                } else {
                                                    $main_total_pour_cost = ($main_total_used_cost / $main_total_sold_cost) * 100;
                                                }
                                                $main_total_ideal_pour_cost += $total_ideal_pour_cost;
                                            @endphp
                                        @endif
                                    @endforeach
                                    <tr
                                        style="border-bottom: thin solid black !important;
                                                    border-top-color: black !important;padding-top: 8px;
                                                    background-color: #e7e7e7 !important;">
                                        <td style="font-size: 110%;font-weight: bold";>
                                            Total {{ $class_name->name }}:
                                        </td>
                                        @if (str_starts_with($main_total_used, '-'))
                                            <td style="color: red;">
                                                -<span
                                                    style="font-weight:bold;font-size: 110%;">{{ abs($main_total_used) }}</span>ml
                                            </td>
                                        @else
                                            <td style="color: red;">
                                                +<span
                                                    style="font-weight:bold;font-size: 110%;">{{ abs($main_total_used) }}</span>ml
                                            </td>
                                        @endif

                                        <td>
                                            @if ($main_total_sold != 0)
                                                <span style="font-weight:bold;font-size: 110%;">
                                                    {{ $main_total_sold }}</span>ml
                                            @endif
                                        </td>

                                        @if (str_starts_with($main_total_missing, '-'))
                                            <td style="color: red;">
                                                <span style="font-weight:bold;font-size: 110%;">
                                                    -{{ abs($main_total_missing) }}</span>ml
                                            </td>
                                        @else
                                            <td>
                                                <span style="font-weight:bold;font-size: 110%;">
                                                    +{{ abs($main_total_missing) }}</span>ml
                                            </td>
                                        @endif

                                        @if (str_starts_with($main_total_percent_missing, '-'))
                                            <td style="font-weight:bold;font-size: 110%;color: red;">
                                                -{{ number_format($main_total_percent_missing, 1, '.', ',') }} %
                                            </td>
                                        @else
                                            <td style="font-weight:bold;font-size: 110%;">
                                                +{{ number_format($main_total_percent_missing, 1, '.', ',') }} %
                                            </td>
                                        @endif

                                        @if (str_starts_with($main_total_missing_cost, '-'))
                                            <td style="font-weight:bold;font-size: 110%;color: red;">
                                                @if ($main_total_missing_cost != 0)
                                                    -SGD{{ number_format(abs($main_total_missing_cost), 2, '.', ',') }}
                                                @endif
                                            </td>
                                        @else
                                            <td style="font-weight:bold;font-size: 110%;">
                                                @if ($main_total_missing_cost != 0)
                                                    +SGD{{ number_format(abs($main_total_missing_cost), 2, '.', ',') }}
                                                @endif
                                            </td>
                                        @endif

                                        @if (str_starts_with($main_total_used_cost, '-'))
                                            <td style="color:red;font-weight:bold;font-size: 110%;">
                                                @if ($main_total_used_cost != 0)
                                                    -SGD{{ number_format(abs($main_total_used_cost), 2, '.', ',') }}
                                                @endif
                                            </td>
                                        @else
                                            <td style="font-weight:bold;font-size: 110%;">
                                                @if ($main_total_used_cost != 0)
                                                    +SGD{{ number_format(abs($main_total_used_cost), 2, '.', ',') }}
                                                @endif
                                            </td>
                                        @endif

                                        <td style="font-weight:bold;font-size: 110%;">
                                            @if ($main_total_on_hand_cost != 0)
                                                SGD{{ number_format($main_total_on_hand_cost, 2, '.', ',') }}
                                            @endif
                                        </td>

                                        <td style="font-weight:bold;font-size: 110%;">
                                            @if ($main_total_revenue != 0)
                                                SGD{{ number_format($main_total_revenue, 2, '.', ',') }}
                                            @endif
                                        </td>

                                        @if (str_starts_with($main_total_pour_cost, '-'))
                                            <td style="font-size: 110%;font-weight: bold;">
                                                @if ($main_total_pour_cost != 0)
                                                    -{{ number_format(abs($main_total_pour_cost), 1, '.', '') }} %
                                                @endif
                                            </td>
                                        @else
                                            <td style="font-size: 110%;font-weight: bold;">
                                                @if ($main_total_pour_cost != 0)
                                                    +{{ number_format(abs($main_total_pour_cost), 1, '.', '') }} %
                                                @endif
                                            </td>
                                        @endif

                                        <td style="font-weight:bold;font-size: 110%;">
                                            {{ number_format($main_total_ideal_pour_cost, 1, '.', '') }} %
                                        </td>
                                    </tr>
                                    @php
                                        $grand_total_sold_cost += $main_total_sold_cost;
                                        $grand_total_used_cost += $main_total_used_cost;
                                        $grand_total_missing_cost += $main_total_missing_cost;
                                        $grand_total_on_hand_cost += $main_total_on_hand_cost;
                                        $grand_total_revenue += $main_total_revenue;
                                        if ($grand_total_sold_cost == 0) {
                                            $grand_total_pour_cost = 0;
                                        } else {
                                            $grand_total_pour_cost = ($grand_total_used_cost / $grand_total_sold_cost) * 100;
                                        }
                                        $grand_total_ideal_pour_cost += $main_total_ideal_pour_cost;
                                    @endphp
                                @endforeach
                                <tr
                                    style="border-top-color: black !important;padding-top: 8px;
                                                    background-color: #b8cf8b; !important;">
                                    <td style="font-size: 110%;font-weight: bold";>
                                        GRANT TOTAL:
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>

                                    @if (str_starts_with($grand_total_missing_cost, '-'))
                                        <td style="font-size: 110%;font-weight: bold;color:red;">
                                            @if ($grand_total_missing_cost != 0)
                                                -SGD{{ number_format(abs($grand_total_missing_cost), 2, '.', ',') }}
                                            @endif
                                        </td>
                                    @else
                                        <td style="font-size: 110%;font-weight: bold;">
                                            @if ($grand_total_missing_cost != 0)
                                                +SGD{{ number_format(abs($grand_total_missing_cost), 2, '.', ',') }}
                                            @endif
                                        </td>
                                    @endif

                                    @if (str_starts_with($grand_total_used_cost, '-'))
                                        <td style="font-size: 110%;font-weight: bold;color:red;">
                                            @if ($grand_total_used_cost != 0)
                                                -SGD{{ number_format(abs($grand_total_used_cost), 2, '.', ',') }}
                                            @endif
                                        </td>
                                    @else
                                        <td style="font-size: 110%;font-weight: bold;">
                                            @if ($grand_total_used_cost != 0)
                                                +SGD{{ number_format(abs($grand_total_used_cost), 2, '.', ',') }}
                                            @endif
                                        </td>
                                    @endif

                                    <td style="font-size: 110%;font-weight: bold;">
                                        @if ($grand_total_on_hand_cost != 0)
                                            SGD{{ number_format($grand_total_on_hand_cost, 2, '.', ',') }}
                                        @endif
                                    </td>

                                    <td style="font-size: 110%;font-weight: bold;">
                                        @if ($grand_total_revenue != 0)
                                            SGD{{ number_format($grand_total_revenue, 2, '.', ',') }}
                                        @endif
                                    </td>

                                    @if (str_starts_with($grand_total_pour_cost, '-'))
                                        <td style="font-size: 110%;font-weight: bold;">
                                            @if ($grand_total_pour_cost != 0)
                                                -{{ number_format(abs($grand_total_pour_cost), 1, '.', '') }} %
                                            @endif
                                        </td>
                                    @else
                                        <td style="font-size: 110%;font-weight: bold;">
                                            @if ($grand_total_pour_cost != 0)
                                                +{{ number_format(abs($grand_total_pour_cost), 1, '.', '') }} %
                                            @endif
                                        </td>
                                    @endif

                                    <td style="font-size: 110%;font-weight: bold;">
                                        {{ number_format($grand_total_ideal_pour_cost, 1, '.', '') }} %
                                    </td>
                                </tr>
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
            var excel_category_quality_id = $("#category_quality_id").val();
            var excel_detail_summary_id = $("#detail_summary_id").val();
            $("#excel_category_quality_id").val(excel_category_quality_id);
            $("#excel_detail_summary_id").val(excel_detail_summary_id);
            var pdf_category_quality_id = $("#category_quality_id").val();
            var pdf_detail_summary_id = $("#detail_summary_id").val();
            $("#pdf_category_quality_id").val(pdf_category_quality_id);
            $("#pdf_detail_summary_id").val(pdf_detail_summary_id);
        });

        function changeCategoryQuality(id) {
            var category_quality_id = id;
            $("#excel_category_quality_id").val(category_quality_id);
            var excel_detail_summary_id = $("#detail_summary_id").val();
            $("#excel_detail_summary_id").val(excel_detail_summary_id);
            $("#pdf_category_quality_id").val(category_quality_id);
            var pdf_detail_summary_id = $("#detail_summary_id").val();
            $("#pdf_detail_summary_id").val(pdf_detail_summary_id);
        }

        function changeDetailSummary(id) {
            var detail_summary_id = id;
            var excel_category_quality_id = $("#category_quality_id").val();
            $("#excel_category_quality_id").val(excel_category_quality_id);
            $("#excel_detail_summary_id").val(detail_summary_id);
            var pdf_category_quality_id = $("#category_quality_id").val();
            $("#pdf_category_quality_id").val(pdf_category_quality_id);
            $("#pdf_detail_summary_id").val(detail_summary_id);
        }


        @php
            function get_sold_cost_data($unit_price, $invoice_purchase_package, $countable_unit, $countable_size, $sale_package_id, $sale_uom_text, $sold_qty, $sale_price, $sale_ingredients_qty)
            {
                $sold_data = 0;
                $sold_cost_data = 0;
                $ideal_pour_cost = 0;
                $invoice_item_packages = \App\Models\ItemPackage::where('id', $invoice_purchase_package)->get();
                if ($invoice_item_packages->isEmpty()) {
                    $sold_data = $countable_unit * $sold_qty;
                    $sold_cost_data = 0;
                } else {
                    foreach ($invoice_item_packages as $invoice_item_package) {
                        $invoice_item_package_unit_to = $invoice_item_package->unit_to;
                        $invoice_item_package_qty = $invoice_item_package->qty;
                    }
                    if ($sale_package_id > 0) {
                        $sale_item_packages = \App\Models\ItemPackage::where('id', $sale_package_id)->get();
                        foreach ($sale_item_packages as $sale_item_package) {
                            $sale_item_package_unit_to = $sale_item_package->unit_to;
                            $sale_item_package_qty = $sale_item_package->qty;
                        }
                        if (($invoice_item_package_unit_to == 'BOTTLE' || $invoice_item_package_unit_to == 'PVC BTL' || $invoice_item_package_unit_to == 'CAN' || $invoice_item_package_unit_to == 'KEG' || $invoice_item_package_unit_to == 'BARREL') && ($sale_item_package_unit_to == 'BOTTLE' || $sale_item_package_unit_to == 'PVC BTL' || $sale_item_package_unit_to == 'CAN' || $sale_item_package_unit_to == 'KEG' || $sale_item_package_unit_to == 'BARREL')) {
                            if ($unit_price == 0) {
                                $sold_data = 0;
                                $sold_cost_data = 0;
                            } else {
                                $sold_data = $countable_unit * $sold_qty;
                                $sold_cost_data = $unit_price / $countable_unit;
                            }
                            if ($sale_price == 0) {
                                $ideal_pour_cost = 0;
                            } else {
                                $ideal_pour_cost = ($unit_price / $sale_price) * 100 * $sale_ingredients_qty;
                            }
                        } elseif (($invoice_item_package_unit_to == 'BOTTLE' || $invoice_item_package_unit_to == 'PVC BTL' || $invoice_item_package_unit_to == 'CAN' || $invoice_item_package_unit_to == 'KEG' || $invoice_item_package_unit_to == 'BARREL') && ($sale_item_package_unit_to == 'BAG' || $sale_item_package_unit_to == 'BLOCK' || $sale_item_package_unit_to == 'BOX' || $sale_item_package_unit_to == 'CARTON' || $sale_item_package_unit_to == 'CASE' || $sale_item_package_unit_to == 'CRATE' || $sale_item_package_unit_to == 'LOAF' || $sale_item_package_unit_to == 'PACKAGE' || $sale_item_package_unit_to == 'TRAY')) {
                            if ($unit_price == 0) {
                                $sold_data = 0;
                                $sold_cost_data = 0;
                            } else {
                                $sold_data = $countable_unit * $sold_qty * $sale_item_package_qty;
                                $sold_cost_data = $unit_price / $countable_unit;
                            }
                            if ($sale_price == 0) {
                                $ideal_pour_cost = 0;
                            } else {
                                $ideal_pour_cost = (($unit_price * $sale_item_package_qty) / $sale_price) * 100 * $sale_ingredients_qty;
                            }
                        } elseif (($invoice_item_package_unit_to == 'BAG' || $invoice_item_package_unit_to == 'BLOCK' || $invoice_item_package_unit_to == 'BOX' || $invoice_item_package_unit_to == 'CARTON' || $invoice_item_package_unit_to == 'CASE' || $invoice_item_package_unit_to == 'CRATE' || $invoice_item_package_unit_to == 'LOAF' || $invoice_item_package_unit_to == 'PACKAGE' || $invoice_item_package_unit_to == 'TRAY') && ($sale_item_package_unit_to == 'BOTTLE' || $sale_item_package_unit_to == 'PVC BTL' || $sale_item_package_unit_to == 'CAN' || $sale_item_package_unit_to == 'KEG' || $sale_item_package_unit_to == 'BARREL')) {
                            if ($unit_price == 0) {
                                $sold_data = 0;
                                $sold_cost_data = 0;
                            } else {
                                $sold_data = $countable_unit * $sold_qty;
                                $sold_cost_data = $unit_price / $invoice_item_package_qty / $countable_unit;
                            }
                            if ($sale_price == 0) {
                                $ideal_pour_cost = 0;
                            } else {
                                $ideal_pour_cost = ($unit_price / $invoice_item_package_qty / $sale_price) * 100 * $sale_ingredients_qty;
                            }
                        } elseif (($invoice_item_package_unit_to == 'BAG' || $invoice_item_package_unit_to == 'BLOCK' || $invoice_item_package_unit_to == 'BOX' || $invoice_item_package_unit_to == 'CARTON' || $invoice_item_package_unit_to == 'CASE' || $invoice_item_package_unit_to == 'CRATE' || $invoice_item_package_unit_to == 'LOAF' || $invoice_item_package_unit_to == 'PACKAGE' || $invoice_item_package_unit_to == 'TRAY') && ($sale_item_package_unit_to == 'BAG' || $sale_item_package_unit_to == 'BLOCK' || $sale_item_package_unit_to == 'BOX' || $sale_item_package_unit_to == 'CARTON' || $sale_item_package_unit_to == 'CASE' || $sale_item_package_unit_to == 'CRATE' || $sale_item_package_unit_to == 'LOAF' || $sale_item_package_unit_to == 'PACKAGE' || $sale_item_package_unit_to == 'TRAY')) {
                            if ($unit_price == 0) {
                                $sold_data = 0;
                                $sold_cost_data = 0;
                            } else {
                                $sold_data = $countable_unit * $sold_qty * $invoice_item_package_qty;
                                $sold_cost_data = $unit_price / $invoice_item_package_qty / $countable_unit;
                            }
                            if ($sale_price == 0) {
                                $ideal_pour_cost = 0;
                            } else {
                                $ideal_pour_cost = ($unit_price / $sale_price) * 100;
                            }
                        }
                    } else {
                        if ($unit_price == 0) {
                            $sold_data = 0;
                            $sold_cost_data = 0;
                        } else {
                            [$sold_data_unit_size, $ideal_pour_cost_unit_size] = ChangeCountableSize($countable_size, $countable_unit, $sale_uom_text, $unit_price, $sold_qty);
                            dd($sold_data_unit_size);
                            if ($invoice_item_package_unit_to == 'BOTTLE' || $invoice_item_package_unit_to == 'PVC BTL' || $invoice_item_package_unit_to == 'CAN' || $invoice_item_package_unit_to == 'KEG' || $invoice_item_package_unit_to == 'BARREL') {
                                $sold_data = $sold_data_unit_size;
                                $sold_cost_data = $unit_price / $countable_unit;
                                $ideal_pour_cost = ($ideal_pour_cost_unit_size / $sale_price) * 100 * $sale_ingredients_qty;
                            } else {
                                $sold_data = $sold_data_unit_size;
                                $sold_cost_data = $unit_price / $invoice_item_package_qty / $countable_unit;
                                $ideal_pour_cost = ($ideal_pour_cost_unit_size / $invoice_item_package_qty / $sale_price) * 100 * $sale_ingredients_qty;
                            }
                        }
                    }
                }
                return [$sold_data, $sold_cost_data, $ideal_pour_cost];
            }
            
            function ChangeCountableSize($countable_size, $countable_unit, $item_ingredient_uom, $invoice_detail_unit_price, $sold_qty)
            {
                if ($countable_size == 'ml' && $item_ingredient_uom == 'ml') {
                    $sold_data_unit_size = $sold_qty;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit;
                } elseif ($countable_size == 'ml' && $item_ingredient_uom == 'L') {
                    $sold_data_unit_size = $sold_qty * 1000;
                    $ideal_pour_cost_unit_size = ($invoice_detail_unit_price / $countable_unit) * 1000;
                } elseif ($countable_size == 'ml' && $item_ingredient_uom == 'oz') {
                    $sold_data_unit_size = $sold_qty * 29.5735295625;
                    $ideal_pour_cost_unit_size = ($invoice_detail_unit_price / $countable_unit) * 29.5735295625;
                } elseif ($countable_size == 'ml' && $item_ingredient_uom == 'cL') {
                    $sold_data_unit_size = $sold_qty * 10;
                    $ideal_pour_cost_unit_size = ($invoice_detail_unit_price / $countable_unit) * 10;
                } elseif ($countable_size == 'ml' && $item_ingredient_uom == '100-mL') {
                    $sold_data_unit_size = $sold_qty * 100;
                    $ideal_pour_cost_unit_size = ($invoice_detail_unit_price / $countable_unit) * 100;
                } elseif ($countable_size == 'ml' && $item_ingredient_uom == 'hL') {
                    $sold_data_unit_size = $sold_qty * 100000;
                    $ideal_pour_cost_unit_size = ($invoice_detail_unit_price / $countable_unit) * 100000;
                } elseif ($countable_size == 'ml' && $item_ingredient_uom == '30-mL') {
                    $sold_data_unit_size = $sold_qty * 30;
                    $ideal_pour_cost_unit_size = ($invoice_detail_unit_price / $countable_unit) * 30;
                } elseif ($countable_size == 'ml' && $item_ingredient_uom == '25-mL') {
                    $sold_data_unit_size = $sold_qty * 25;
                    $ideal_pour_cost_unit_size = ($invoice_detail_unit_price / $countable_unit) * 25;
                } elseif ($countable_size == 'ml' && $item_ingredient_uom == '45-mL') {
                    $sold_data_unit_size = $sold_qty * 45;
                    $ideal_pour_cost_unit_size = ($invoice_detail_unit_price / $countable_unit) * 45;
                }
            
                if ($countable_size == 'L' && $item_ingredient_uom == 'ml') {
                    $sold_data_unit_size = $sold_qty / 1000;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit / 1000;
                } elseif ($countable_size == 'L' && $item_ingredient_uom == 'L') {
                    $sold_data_unit_size = $sold_qty;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit;
                } elseif ($countable_size == 'L' && $item_ingredient_uom == 'oz') {
                    $sold_data_unit_size = $sold_qty / 33.814022558919;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit / 33.814022558919;
                } elseif ($countable_size == 'L' && $item_ingredient_uom == 'cL') {
                    $sold_data_unit_size = $sold_qty / 100;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit / 100;
                } elseif ($countable_size == 'L' && $item_ingredient_uom == '100-mL') {
                    $sold_data_unit_size = $sold_qty * 0.1;
                    $ideal_pour_cost_unit_size = ($invoice_detail_unit_price / $countable_unit) * 0.1;
                } elseif ($countable_size == 'L' && $item_ingredient_uom == 'hL') {
                    $sold_data_unit_size = $sold_qty * 100;
                    $ideal_pour_cost_unit_size = ($invoice_detail_unit_price / $countable_unit) * 100;
                } elseif ($countable_size == 'L' && $item_ingredient_uom == '30-mL') {
                    $sold_data_unit_size = $sold_qty * 0.03;
                    $ideal_pour_cost_unit_size = ($invoice_detail_unit_price / $countable_unit) * 0.03;
                } elseif ($countable_size == 'L' && $item_ingredient_uom == '25-mL') {
                    $sold_data_unit_size = $sold_qty * 0.025;
                    $ideal_pour_cost_unit_size = ($invoice_detail_unit_price / $countable_unit) * 0.025;
                } elseif ($countable_size == 'L' && $item_ingredient_uom == '45-mL') {
                    $sold_data_unit_size = $sold_qty * 0.045;
                    $ideal_pour_cost_unit_size = ($invoice_detail_unit_price / $countable_unit) * 0.045;
                }
            
                if ($countable_size == 'oz' && $item_ingredient_uom == 'ml') {
                    $sold_data_unit_size = $sold_qty / 29.5735296;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit / 29.5735296;
                } elseif ($countable_size == 'oz' && $item_ingredient_uom == 'L') {
                    $sold_data_unit_size = $sold_qty / 0.0295735296875;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit / 0.0295735296875;
                } elseif ($countable_size == 'oz' && $item_ingredient_uom == 'oz') {
                    $sold_data_unit_size = $sold_qty;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit;
                } elseif ($countable_size == 'oz' && $item_ingredient_uom == 'cL') {
                    $sold_data_unit_size = $sold_qty / 2.95735296;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit / 2.95735296;
                } elseif ($countable_size == 'oz' && $item_ingredient_uom == '100-mL') {
                    $sold_data_unit_size = ($sold_qty / 29.5735296) * 100;
                    $ideal_pour_cost_unit_size = ($invoice_detail_unit_price / $countable_unit / 29.5735296) * 100;
                } elseif ($countable_size == 'oz' && $item_ingredient_uom == 'hL') {
                    $sold_data_unit_size = $sold_qty / 0.00029574;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit / 0.00029574;
                } elseif ($countable_size == 'oz' && $item_ingredient_uom == '30-mL') {
                    $sold_data_unit_size = ($sold_qty / 29.5735296) * 30;
                    $ideal_pour_cost_unit_size = ($invoice_detail_unit_price / $countable_unit / 29.5735296) * 30;
                } elseif ($countable_size == 'oz' && $item_ingredient_uom == '25-mL') {
                    $sold_data_unit_size = ($sold_qty / 29.5735296) * 25;
                    $ideal_pour_cost_unit_size = ($invoice_detail_unit_price / $countable_unit / 29.5735296) * 25;
                } elseif ($countable_size == 'oz' && $item_ingredient_uom == '45-mL') {
                    $sold_data_unit_size = ($sold_qty / 29.5735296) * 45;
                    $ideal_pour_cost_unit_size = ($invoice_detail_unit_price / $countable_unit / 29.5735296) * 45;
                }
            
                if ($countable_size == 'cL' && $item_ingredient_uom == 'ml') {
                    $sold_data_unit_size = $sold_qty / 10;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit / 10;
                } elseif ($countable_size == 'cL' && $item_ingredient_uom == 'L') {
                    $sold_data_unit_size = $sold_qty / 0.01;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit / 0.01;
                } elseif ($countable_size == 'cL' && $item_ingredient_uom == 'oz') {
                    $sold_data_unit_size = $sold_qty / 0.33814;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit / 0.33814;
                } elseif ($countable_size == 'cL' && $item_ingredient_uom == 'cL') {
                    $sold_data_unit_size = $sold_qty;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit;
                } elseif ($countable_size == 'cL' && $item_ingredient_uom == '100-mL') {
                    $sold_data_unit_size = ($sold_qty / 10) * 100;
                    $ideal_pour_cost_unit_size = ($invoice_detail_unit_price / $countable_unit / 10) * 100;
                } elseif ($countable_size == 'cL' && $item_ingredient_uom == 'hL') {
                    $sold_data_unit_size = $sold_qty / 0.0001;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit / 0.0001;
                } elseif ($countable_size == 'cL' && $item_ingredient_uom == '30-mL') {
                    $sold_data_unit_size = ($sold_qty / 10) * 30;
                    $ideal_pour_cost_unit_size = ($invoice_detail_unit_price / $countable_unit / 10) * 30;
                } elseif ($countable_size == 'cL' && $item_ingredient_uom == '25-mL') {
                    $sold_data_unit_size = ($sold_qty / 10) * 25;
                    $ideal_pour_cost_unit_size = ($invoice_detail_unit_price / $countable_unit / 10) * 25;
                } elseif ($countable_size == 'cL' && $item_ingredient_uom == '45-mL') {
                    $sold_data_unit_size = ($sold_qty / 10) * 45;
                    $ideal_pour_cost_unit_size = ($invoice_detail_unit_price / $countable_unit / 10) * 45;
                }
            
                if ($countable_size == '100-mL' && $item_ingredient_uom == 'ml') {
                    $sold_data_unit_size = $sold_qty / 100;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit / 100;
                } elseif ($countable_size == '100-mL' && $item_ingredient_uom == 'L') {
                    $sold_data_unit_size = ($sold_qty * 1000) / 100;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 1000) / 100;
                } elseif ($countable_size == '100-mL' && $item_ingredient_uom == 'oz') {
                    $sold_data_unit_size = ($sold_qty * 29.5735295625) / 100;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 29.5735295625) / 100;
                } elseif ($countable_size == '100-mL' && $item_ingredient_uom == 'cL') {
                    $sold_data_unit_size = ($sold_qty * 10) / 100;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 10) / 100;
                } elseif ($countable_size == '100-mL' && $item_ingredient_uom == '100-mL') {
                    $sold_data_unit_size = $sold_qty;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit;
                } elseif ($countable_size == '100-mL' && $item_ingredient_uom == 'hL') {
                    $sold_data_unit_size = ($sold_qty * 100000) / 100;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 100000) / 100;
                } elseif ($countable_size == '100-mL' && $item_ingredient_uom == '30-mL') {
                    $sold_data_unit_size = ($sold_qty * 30) / 100;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 30) / 100;
                } elseif ($countable_size == '100-mL' && $item_ingredient_uom == '25-mL') {
                    $sold_data_unit_size = ($sold_qty * 25) / 100;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 25) / 100;
                } elseif ($countable_size == '100-mL' && $item_ingredient_uom == '45-mL') {
                    $sold_data_unit_size = ($sold_qty * 45) / 100;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 45) / 100;
                }
            
                if ($countable_size == 'hL' && $item_ingredient_uom == 'ml') {
                    $sold_data_unit_size = $sold_qty / 100000;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit / 100000;
                } elseif ($countable_size == 'hL' && $item_ingredient_uom == 'L') {
                    $sold_data_unit_size = $sold_qty / 100;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit / 100;
                } elseif ($countable_size == 'hL' && $item_ingredient_uom == 'oz') {
                    $sold_data_unit_size = $sold_qty / 3381.4022701843;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit / 3381.4022701843;
                } elseif ($countable_size == 'hL' && $item_ingredient_uom == 'cL') {
                    $sold_data_unit_size = $sold_qty / 10000;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit / 10000;
                } elseif ($countable_size == 'hL' && $item_ingredient_uom == '100-mL') {
                    $sold_data_unit_size = ($sold_qty / 100000) * 100;
                    $ideal_pour_cost_unit_size = ($invoice_detail_unit_price / $countable_unit / 100000) * 100;
                } elseif ($countable_size == 'hL' && $item_ingredient_uom == 'hL') {
                    $sold_data_unit_size = $sold_qty;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit;
                } elseif ($countable_size == 'hL' && $item_ingredient_uom == '30-mL') {
                    $sold_data_unit_size = ($sold_qty / 100000) * 30;
                    $ideal_pour_cost_unit_size = ($invoice_detail_unit_price / $countable_unit / 100000) * 30;
                } elseif ($countable_size == 'hL' && $item_ingredient_uom == '25-mL') {
                    $sold_data_unit_size = ($sold_qty / 100000) * 25;
                    $ideal_pour_cost_unit_size = ($invoice_detail_unit_price / $countable_unit / 100000) * 25;
                } elseif ($countable_size == 'hL' && $item_ingredient_uom == '45-mL') {
                    $sold_data_unit_size = ($sold_qty / 100000) * 45;
                    $ideal_pour_cost_unit_size = ($invoice_detail_unit_price / $countable_unit / 100000) * 45;
                }
            
                if ($countable_size == '30-mL' && $item_ingredient_uom == 'ml') {
                    $sold_data_unit_size = $sold_qty / 30;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit / 30;
                } elseif ($countable_size == '30-mL' && $item_ingredient_uom == 'L') {
                    $sold_data_unit_size = ($sold_qty * 1000) / 30;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 1000) / 30;
                } elseif ($countable_size == '30-mL' && $item_ingredient_uom == 'oz') {
                    $sold_data_unit_size = ($sold_qty * 29.5735295625) / 30;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 29.5735295625) / 30;
                } elseif ($countable_size == '30-mL' && $item_ingredient_uom == 'cL') {
                    $sold_data_unit_size = ($sold_qty * 10) / 30;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 10) / 30;
                } elseif ($countable_size == '30-mL' && $item_ingredient_uom == '100-mL') {
                    $sold_data_unit_size = ($sold_qty * 100) / 30;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 100) / 30;
                } elseif ($countable_size == '30-mL' && $item_ingredient_uom == 'hL') {
                    $sold_data_unit_size = ($sold_qty * 100000) / 30;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 100000) / 30;
                } elseif ($countable_size == '30-mL' && $item_ingredient_uom == '30-mL') {
                    $sold_data_unit_size = $sold_qty;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit;
                } elseif ($countable_size == '30-mL' && $item_ingredient_uom == '25-mL') {
                    $sold_data_unit_size = ($sold_qty * 25) / 30;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 25) / 30;
                } elseif ($countable_size == '30-mL' && $item_ingredient_uom == '45-mL') {
                    $sold_data_unit_size = ($sold_qty * 45) / 30;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 45) / 30;
                }
            
                if ($countable_size == '25-mL' && $item_ingredient_uom == 'ml') {
                    $sold_data_unit_size = $sold_qty / 25;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit / 25;
                } elseif ($countable_size == '25-mL' && $item_ingredient_uom == 'L') {
                    $sold_data_unit_size = ($sold_qty * 1000) / 25;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 1000) / 25;
                } elseif ($countable_size == '25-mL' && $item_ingredient_uom == 'oz') {
                    $sold_data_unit_size = ($sold_qty * 29.5735295625) / 25;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 29.5735295625) / 25;
                } elseif ($countable_size == '25-mL' && $item_ingredient_uom == 'cL') {
                    $sold_data_unit_size = ($sold_qty * 10) / 25;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 10) / 25;
                } elseif ($countable_size == '25-mL' && $item_ingredient_uom == '100-mL') {
                    $sold_data_unit_size = ($sold_qty * 100) / 25;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 100) / 25;
                } elseif ($countable_size == '25-mL' && $item_ingredient_uom == 'hL') {
                    $sold_data_unit_size = ($sold_qty * 100000) / 25;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 100000) / 25;
                } elseif ($countable_size == '25-mL' && $item_ingredient_uom == '30-mL') {
                    $sold_data_unit_size = ($sold_qty * 30) / 25;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 30) / 25;
                } elseif ($countable_size == '25-mL' && $item_ingredient_uom == '25-mL') {
                    $sold_data_unit_size = $sold_qty;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit;
                } elseif ($countable_size == '25-mL' && $item_ingredient_uom == '45-mL') {
                    $sold_data_unit_size = ($sold_qty * 45) / 25;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 45) / 25;
                }
            
                if ($countable_size == '45-mL' && $item_ingredient_uom == 'ml') {
                    $sold_data_unit_size = $sold_qty / 45;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit / 45;
                } elseif ($countable_size == '45-mL' && $item_ingredient_uom == 'L') {
                    $sold_data_unit_size = ($sold_qty * 1000) / 45;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 1000) / 45;
                } elseif ($countable_size == '45-mL' && $item_ingredient_uom == 'oz') {
                    $sold_data_unit_size = ($sold_qty * 29.5735295625) / 45;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 29.5735295625) / 45;
                } elseif ($countable_size == '45-mL' && $item_ingredient_uom == 'cL') {
                    $sold_data_unit_size = ($sold_qty * 10) / 45;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 10) / 45;
                } elseif ($countable_size == '45-mL' && $item_ingredient_uom == '100-mL') {
                    $sold_data_unit_size = ($sold_qty * 100) / 45;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 100) / 45;
                } elseif ($countable_size == '45-mL' && $item_ingredient_uom == 'hL') {
                    $sold_data_unit_size = ($sold_qty * 100000) / 45;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 100000) / 45;
                } elseif ($countable_size == '45-mL' && $item_ingredient_uom == '30-mL') {
                    $sold_data_unit_size = ($sold_qty * 30) / 45;
                    $ideal_pour_cost_unit_size = (($invoice_detail_unit_price / $countable_unit) * 30) / 45;
                } elseif ($countable_size == '45-mL' && $item_ingredient_uom == '25-mL') {
                    $sold_data_unit_size = $sold_qty / 45;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit / 45;
                } elseif ($countable_size == '45-mL' && $item_ingredient_uom == '45-mL') {
                    $sold_data_unit_size = $sold_qty;
                    $ideal_pour_cost_unit_size = $invoice_detail_unit_price / $countable_unit;
                }
            
                return [$sold_data_unit_size, $ideal_pour_cost_unit_size];
            }
        @endphp
    </script>
@endpush
