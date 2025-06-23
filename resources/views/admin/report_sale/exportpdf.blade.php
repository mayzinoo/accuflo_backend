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
    </style>
</head>

<body>
    <h3 style="color: #1c75bc; margin-left: 5px;">Sale Report</h3>
    <table class="styled-table">
        <tr>
            <th>PLU</th>
            <th>Item Name</th>
            <th>Price Level</th>
            <th>Price</th>
            <th>Sold</th>
            <th>Revenue</th>
        </tr>
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
    </table>
</body>

</html>
