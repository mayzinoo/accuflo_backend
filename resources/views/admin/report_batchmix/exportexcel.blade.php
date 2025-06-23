<!DOCTYPE html>
<html>

<head>
    <style>
        .styled-table {
            border-collapse: collapse;
            min-width: 100%;
            padding: 7px;
        }

        .styled-table tr th {
            color: #000000;
            font-weight: bold;
            text-align: left;
        }

        .styled-table th,
        .styled-table td {
            padding: 7px;
            font-size: 11px;
            border: 2px solid #000000;
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
            <th>Description</th>
            <th>Empty Weight</th>
            <th>Cost</th>
        </tr>
        @foreach ($batchmixs as $index => $batchmix)
            <tr>
                <td>{{ $batchmix->name }}</td>
                <td>{{ $batchmix->barcode }}</td>
                <td>
                    @if ($batchmix->liquid_status == 'no')
                        -
                    @else
                        {{ $batchmix->total_volume }}
                        @foreach ($BATCHMIX_VOLUME_UNIT as $key => $volume_unit)
                            @if ($batchmix->total_volume_id == $key)
                                {{ $volume_unit }}
                            @endif
                        @endforeach
                    @endif
                </td>
                <td>
                    @foreach ($BATCHMIX_UD as $key => $unit_des)
                        @if ($batchmix->unit_des == $key)
                            {{ $unit_des }}
                        @endif
                    @endforeach
                </td>
                <td>
                    @if ($batchmix->inventory_status == 'no')
                        -
                    @else
                        {{ $batchmix->container_weight }}
                        @foreach ($BATCHMIX_WEIGHT_UNIT as $key => $weight_unit)
                            @if ($batchmix->container_weight_id == $key)
                                {{ $weight_unit }}
                            @endif
                        @endforeach
                    @endif
                </td>
                <td>SGD 0.00</td>
            </tr>
            @foreach ($batchmix->ingredients as $index => $ingredient)
                <tr>
                    <td>
                        {{ $ingredient->item_name }} - {{ $ingredient->qty }}
                        @foreach ($BATCHMIX_UOM as $key => $uom_unit)
                            @if ($ingredient->uom == $key)
                                {{ $uom_unit }} from
                            @endif
                        @endforeach
                        {{ $ingredient->item->itemPackage->first()->unit_from }}
                        @if ($ingredient->item->itemSize->first()->countable_unit != null)
                            ({{ $ingredient->item->itemSize->first()->countable_unit }}
                            {{ $ingredient->item->itemSize->first()->countable_size }})
                        @endif
                    </td>
                    <td>
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>SGD 0.00</td>
                </tr>
            @endforeach
        @endforeach
    </table>
</body>

</html>
