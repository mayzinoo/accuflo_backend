@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">

                <div class="col-sm-6" style="display: flex;">
                    <div>
                        <i class="fa fa-barcode fa-2x"
                            style="color: #1c75bc;
                        border: #1c75bc solid 4px;
                        border-radius: 10px;
                        padding: 10px;"></i>
                    </div>
                    <h5 style="margin-left: 15px;margin-top: 15px"> Inventory Report </h5>
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
                        <form action="{{ route('report-inventory-excel') }}" method="POST">
                            @csrf
                            <li style="margin-right: 5px;">
                                <button type="submit" class="btn btn-sm btn-default">
                                    <i class="far fa-file-excel"></i> Save to Excel
                                </button>
                            </li>
                        </form>
                        <form action="{{ route('report-inventory-pdf') }}" method="POST">
                            @csrf
                            <li>
                                <button type="submit" class="btn btn-sm btn-default">
                                    <i class="far fa-file-pdf"></i> Save to PDF
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
                    @if ($inventory_fullcounts != null || $inventory_weights != null)
                        <table class="table table-responsive text-nowrap" style="line-height: 0.7 !important">
                            <thead style="color: #000000;
                            background-color: #f2f2f2;">
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
                            </thead>
                            <tbody>
                                @if ($inventory_fullcounts != null)
                                    @foreach ($inventory_fullcounts as $index => $fullcount)
                                        <tr>
                                            <td>{{ $fullcount->item->name }}</td>
                                            <td>{{ $fullcount->station? $fullcount->station->name : '' }}</td>
                                            <td>{{ $fullcount->size }}</td>
                                            <td>{{ $fullcount->period_count }}</td>
                                            <td>{{ optional($fullcount->itemPackage)->unit_to }}</td>
                                            <td>{{ $fullcount->item->category ? $fullcount->item->category->name : '' }}</td>
                                            <td>{{ $fullcount->item->class ? $fullcount->item->class->name : '' }}</td>
                                            <td></td>
                                            <td> {{ optional($fullcount->itemPackage)->package_barcode }}</td>
                                            <td></td>
                                            <td></td>

                                        </tr>
                                    @endforeach
                                @endif
                                @if ($inventory_weights != null)
                                    @foreach ($inventory_weights as $index => $weight)
                                        <tr>
                                            <td>{{ $weight->item->name }}</td>
                                            <td>{{ $weight->station ? $weight->station->name : '' }}</td>
                                            <td>{{ $weight->size }}</td>
                                            <?php $on_hand='';
                                            $item_size=\App\Models\ItemSize::where('item_id',$weight->item_id)->get()[0];
                                            if($item_size->full_weight!=0) {
                                                $current_weight=$weight->weight-$item_size->empty_weight;
                                                $total_weight=$item_size->full_weight-$item_size->empty_weight;
                                                $on_hand=number_format( $current_weight/$total_weight , 3, '.', ''); 
                                            }?>
                                            <td>{{ $on_hand}}
                                            </td>
                                            <td>{{ optional($weight->package)->unit_to }}</td>
                                            <td>{{ $weight->item->category ? $weight->item->category->name : '' }}</td>
                                            <td>{{ $weight->item->class ? $weight->item->class->name : ''}}</td>
                                            <td>{{ $weight->section ? $weight->section->name : '' }} {{ optional($weight->shelf)->shelf_name }}
                                            </td>
                                            <td> {{ optional($weight->package)->package_barcode }}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                @endif
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
