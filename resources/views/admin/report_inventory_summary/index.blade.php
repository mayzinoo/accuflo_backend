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
                    <h5 style="margin-left: 15px;margin-top: 15px"> Invoice Summary </h5>
                    <form action="" id="generate">
                        <div style="margin-left: 30px;margin-top: 15px;">
                            <a class="btn btn-sm btn-default" onclick="generate()">
                                Generate
                            </a>
                            <input type="hidden" name="generate" value='1'>
                        </div>
                    </form>
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right" style="margin-top:15px;">
                        <form action="{{ route('report-inventory-summary-excel') }}" method="POST">
                            @csrf
                            <li style="margin-right: 5px;">
                                <button type="submit" class="btn btn-sm btn-default">
                                    <i class="far fa-file-excel"></i> Save to Excel
                                </button>
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
                    @if ($invoices != null)
                        <table class="table table-responsive table-sm table-borderless text-nowrap">
                            <thead
                                style="color: #000000;
                            background-color: #f2f2f2;border-bottom: 2px solid #ddd !important;
                            border-top: 1px solid #ddd !important;">
                                <tr>
                                    <th>Invoice Date</th>
                                    <th>Vendor</th>
                                    <th>Invoice Number</th>
                                    @foreach ($class_names as $key => $class_name)
                                        <th>{{ $key }}</th>
                                    @endforeach
                                    <th>Total Order</th>
                                    <th>Total Cost (excluding tax)</th>
                                    <th>Total Tax</th>
                                    <th>Total Cost (including tax)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $key => $invoice)
                                    <tr>
                                        <td> {{ $invoice->invoice_delivery_date }}</td>
                                        <td> {{ $invoice->vendor->name }}</td>
                                        <td> {{ $invoice->invoice_number }}</td>
                                        @foreach ($class_names as $key => $class_name)
                                            <td> SGD {{ number_format($class_name, 2, '.', '') }}</td>
                                        @endforeach
                                        <td> {{ $invoice->total_quantity }}</td>
                                        <td> SGD {{ number_format($invoice->total_cost_excluding_taxes, 2, '.', '') }}</td>
                                        <td> SGD {{ number_format($invoice->total_taxes, 2, '.', '') }}</td>
                                        <td> SGD {{ number_format($invoice->total_cost, 2, '.', '') }}</td>

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
    </script>
@endpush
