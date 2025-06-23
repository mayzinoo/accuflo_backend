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
    </style>
</head>

<body>
    <h1></h1>
    <h1></h1>
    <h1></h1>
    <table class="styled-table">
        <tr>
            <th>Item Name</th>
            <th>Station</th>
            <th>Item Size</th>
            <th>On-Hand</th>
            <th>On-Hand UOM</th>
            <th>Category</th>
            <th>Class</th>
            <th>Weight Location</th>
            <th>Bar code</th>
            <th>Code</th>
            <th>Product Code</th>
        </tr>
        @if ($inventory_fullcounts != null)
            @foreach ($inventory_fullcounts as $index => $fullcount)
                <tr>
                    <td>{{ $fullcount->item->name }}</td>
                    <td>{{ $fullcount->station ? $fullcount->station->name : '' }}</td>
                    <td>{{ $fullcount->size }}</td>
                    <td>{{ $fullcount->period_count }}</td>
                    <td>{{ $fullcount->itemPackage->unit_to }}</td>
                    <td>{{ $fullcount->item->category ? $fullcount->item->category->name : '' }}</td>
                    <td>{{ $fullcount->item->class ? $fullcount->item->class->name : '' }}</td>
                    <td></td>
                    <td> {{ $fullcount->itemPackage->package_barcode }}</td>
                    <td></td>
                    <td></td>

                </tr>
            @endforeach
        @endif
        @if ($inventory_weights != null)
            @foreach ($inventory_weights as $index => $weight)
                <tr>
                    <td>{{ $weight->item->name }}</td>
                    <td>{{ $weight->station ? $weight->station->name : ''}}</td>
                    <td>{{ $weight->size }}</td>
                    <?php $on_hand='';
                        $item_size=\App\Models\ItemSize::where('item_id',$weight->item_id)->get()[0];
                        if($item_size->full_weight!=0) {
                            $current_weight=$weight->weight-$item_size->empty_weight;
                            $total_weight=$item_size->full_weight-$item_size->empty_weight;
                            $on_hand=number_format( $current_weight/$total_weight , 3, '.', ''); 
                        }?>
                    <td>{{ $on_hand }}</td>
                    <td>{{ $weight->package->unit_to }}</td>
                    <td>{{ $weight->item->category ? $weight->item->category->name : '' }}</td>
                    <td>{{ $weight->item->class ? $weight->item->class->name : '' }}</td>
                    <td>{{ $weight->section ? $weight->section->name : '' }} {{ optional($weight->shelf)->shelf_name }}</td>
                    <td> {{ $weight->package->package_barcode }}</td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
        @endif
    </table>
</body>

</html>
