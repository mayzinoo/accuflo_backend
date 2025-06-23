<!DOCTYPE html>
<html>

<head>
    <style>
        .styled-table {
            border-collapse: collapse;
            min-width: 100%;
            padding: 7px;
            border: 1px solid #dddddd;
        }

        .styled-table tr th {
            color: #000000;
            font-weight: bold;
            text-align: left;
            border-bottom: 2px solid #dddddd;
        }

        .styled-table th,
        .styled-table td {
            padding: 7px;
            font-size: 11px;
        }

        .styled-table tr {
            border-bottom: 1px solid #dddddd;
        }

        .span-css {
            justify-content: center;
            display: flex;
            text-align: center;
        }

        .styled-table .main_title {
            font-size: 110%
        }
    </style>
</head>

<body>
    <h1></h1>
    <h1></h1>
    <h1></h1>
    <table class="styled-table">
        <tr>
            <th>Item Name</th>
            <th>Bar Code</th>
            <th>Item Size</th>
            <th>Unit Cost</th>
            <th>Purchase</th>
            <th>Purchases Volume</th>
            <th>Purchases Cost</th>
        </tr>
        @foreach ($classes as $index => $class)
            <tr>
                @php
                    $class_name = \App\Models\Classes::where('id', $class->class_id)->get()[0];
                @endphp
                <td class="main_title">{{ $class_name->name }}</td>
            </tr>
            @php
                $categories_data = \App\Models\Category::whereIn('id', $categories)
                    ->where('class_id', $class->class_id)
                    ->get();
                $main_total_purchase_volume = 0;
                $main_total_purchase_cost = 0;
            @endphp
            @foreach ($categories_data as $index => $category)
                <tr>
                    <td>{{ $category->name }}</td>
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
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->itemPackage->first()->package_barcode }}</td>
                        <td>{{ $countable_unit }} {{ $item->itemSize->first()->countable_size }}</td>
                        <td>SGD {{ number_format($item->invoiceDetails->first()->unit_price, 2, '.', ',') }}
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
                <tr>
                    <td>
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
            <tr>
                <td>
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
    </table>
</body>

</html>
