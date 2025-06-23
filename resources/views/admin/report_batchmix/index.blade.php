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

                    <h5 style="margin-left: 15px;margin-top: 15px">Batch Mix Report</h5>
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
                    <ol class="breadcrumb float-sm-right" style="margin-top: 15px;">
                        <li style="margin-right: 5px;">
                            <a href="report_batchmix/exportExcel/" class="btn btn-sm btn-default">
                                <i class="far fa-file-excel"></i> Save to Excel
                            </a>
                        </li>
                        <li>
                            <a href="report_batchmix/exportPDF/" class="btn btn-sm btn-default">
                                <i class="far fa-file-pdf"></i> Save to PDF
                            </a>
                        </li>
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
                                <th>Item Name</th>
                                <th>Bar Code</th>
                                <th>Item Size</th>
                                <th>Description</th>
                                <th>Empty Weight</th>
                                <th>Cost</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($batchmixs as $index => $batchmix)
                                <tr data-target="#demo{{ $batchmix->id }}">
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
                                    <tr id="demo{{ $batchmix->id }}">
                                        <td><span style="justify-content: center; display: flex;">
                                                <i class="fas fa-caret-right"></i>
                                            </span>
                                        </td>
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
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>SGD 0.00</td>
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
    </script>
@endpush
