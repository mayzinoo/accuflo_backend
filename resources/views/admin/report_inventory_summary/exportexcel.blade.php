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
            <th>Invoice Date</th>
            <th>Vendor</th>
            <th>Invoice Number</th>
            @foreach ($class_names as $key => $class_name)
            <th>{{$key}}</th>
            @endforeach
            <th>Total Order</th>
            <th>Total Cost (excluding tax)</th>
            <th>Total Tax</th>
            <th>Total Cost (including tax)</th>
        </tr>
        @foreach ($invoices as $key => $invoice)
        <tr>
            <td> {{$invoice->invoice_delivery_date }}</td>
            <td> {{$invoice->vendor->name }}</td>
            <td> {{$invoice->invoice_number }}</td>
            @foreach ($class_names as $key => $class_name)
            <td> SGD {{number_format($class_name, 2, '.','')}}</td>
            @endforeach 
            <td> {{$invoice->total_quantity }}</td>
            <td> SGD {{number_format($invoice->total_cost_excluding_taxes, 2, '.', '') }}</td>
            <td> SGD {{number_format($invoice->total_taxes, 2, '.', '') }}</td>
            <td> SGD {{number_format($invoice->total_cost, 2, '.', '') }}</td>
            
        </tr>
        @endforeach
    </table>
</body>

</html>
