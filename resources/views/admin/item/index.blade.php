@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Item</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Item</li>
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
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Item List</h3>
                            <div class="card-tools">
                                <div class="float-right">
                                    <a class="btn btn-sm btn-dark" data-toggle="collapse" href="#filter" role="button"
                                        aria-expanded="false" aria-controls="filter">
                                        Filter
                                    </a>
                                    @can('create item')
                                    <a href="{{ route('item.create') }}" class="btn btn-sm btn-primary">
                                        <i class="fa fa-plus"> </i> Create Item
                                    </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        <div class="collapse show" id="filter">
                            <div class="card-header">
                                <form action="" autocomplete="off">
                                    <div class="row search-section">
                                        <div class="col-md-3">
                                            <input class="form-control form-control-sm" type="text" name="name"
                                                value="{{ request('name') }}" placeholder="Search by item">
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit"
                                                class="btn btn-sm btn-primary search mb-2">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- /.card-header -->

                        <!-- /.card-body -->
                        <div class="card-body table-responsive p-0">
                            <!-- ****************************************-->
                            <table class="table table-hover text-nowrap" style="border-collapse:collapse;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Class</th>
                                        <th>Category</th>
                                        <th>Quality</th>
                                        <th>Density</th>
                                        <th width="100px">Action</th>
                                </thead>
                                <tbody>
                                    @foreach ($items as $index => $item)
                                        <tr data-toggle="collapse" data-target="#item{{ $index }}"
                                            class="accordion-toggle">
                                            <td><button class="btn btn-default btn-xs"><span
                                                        class="fa fa-plus"></span></button></td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ optional($item->class)->name }}</td>
                                            <td>
                                                @if ($item->category_id == '0')
                                                    UNKNOWN
                                                @else
                                                    {{ $item->category->name }}
                                                @endif
                                            </td>

                                            <td>
                                                @if ($item->quality_id == '0')
                                                    UNKNOWN
                                                @else
                                                    {{ $item->quality->name }}
                                                @endif
                                            </td>

                                            <td>
                                                <?php $count=1; ?>
                                                @foreach ($item->itemSize as $item_size)
                                                    @if ($item_size->density != null)
                                                        @if($count==1)
                                                        <i class="fas fa-exclamation-triangle"
                                                            style="color: orange;font-size: 0.7rem;margin-right: 15px"></i>
                                                        <span class='rounded'> {{ $item_size->density }}  </span>
                                                        {{ $item_size->density_m_unit }} / {{ $item_size->density_v_unit }}
                                                        <?php $count+=1; ?>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @can('edit item')
                                                <a href="{{ route('item.edit', $item->id) }}" class="btn btn-xs btn-info">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @endcan
                                                @can('delete item')
                                                <a href="#deleteModal" data-toggle="modal" data-id="{{ $item->id }}"
                                                    data-route="item" class="btn btn-xs btn-danger delete">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                @endcan
                                                @can('create item size')
                                                <a href="{{ route('create-size', $item->id) }}"
                                                    class="btn btn-xs btn-primary">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                                @endcan
                                            </td>
                                        </tr>
                                        @can('list item size')
                                        <tr>
                                            <td colspan="12" class="accordian-body collapse" id="item{{ $index }}">
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Size</th>
                                                                <th>Barcode</th>
                                                                <th>Code </th>
                                                                <th>Empty Wt.</th>
                                                                <th>Full Wt.</th>
                                                                <th>Packaging</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($item->itemSize as $index => $item_size)
                                                                <tr>
                                                                    <td>
                                                                        @if ($item_size->countable_unit == null)
                                                                            @if(isset($item_size->itemPackage[0]))
                                                                                {{$item_size->itemPackage[0]->qty.' '. optional($item_size->itemPackage[0])->unit_from }}
                                                                            @else
                                                                            -
                                                                            @endif
                                                                        @else
                                                                            {{ $item_size->countable_unit }}
                                                                            {{ $item_size->countable_size }} /
                                                                            @if (isset($item_size->itemPackage[0]))
                                                                                {{ optional($item_size->itemPackage[0])->unit_from }}
                                                                            @endif
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if(isset($item_size->itemPackage[0]))
                                                                            {{ optional($item_size->itemPackage[0])->package_barcode }}
                                                                        @endif
                                                                    </td>
                                                                    <td> - </td>
                                                                    <td>
                                                                        @if ($item_size->empty_weight == null)
                                                                            -
                                                                        @else
                                                                            {{ $item_size->empty_weight }}
                                                                            {{ $item_size->empty_weight_size }}
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if ($item_size->full_weight == null)
                                                                            -
                                                                        @else
                                                                            {{ $item_size->full_weight }}
                                                                            {{ $item_size->full_weight_size }}
                                                                        @endif
                                                                    </td>

                                                                    <td>
                                                                        @foreach ($item_size->itemPackage as $index => $item_package)
                                                                            @if ($index != 0)
                                                                                {{ $item_package->qty . ' ' . $item_package->unit_from . ' / ' . $item_package->unit_to }}
                                                                                <br />
                                                                            @endif
                                                                        @endforeach
                                                                    </td>
                                                                    <td>
                                                                        @can('edit item size')
                                                                            <a href="item/{{ $item->id }}/edit-size/{{ $item_size->id }}"
                                                                                class="btn btn-xs btn-info">
                                                                                <i class="fa fa-edit"></i>
                                                                            </a>
                                                                        @endcan
                                                                        @can('delete item size')
                                                                            @if(count($item->itemSize) > 1)
                                                                            <a href="#deleteModal" data-toggle="modal" data-id="{{ $item_size->id }}"
                                                                                data-route="{{ 'item/'.$item->id.'/delete-size' }}" class="btn btn-xs btn-danger delete">
                                                                                <i class="fa fa-trash"></i>
                                                                            </a>
                                                                            @endif
                                                                        @endcan
                                                                    </td>
                                                                </tr>
                                                            @endforeach                                                            
                                                        </tbody>
                                                    </table>
                                            </td>
                                        </tr>
                                        @endcan
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            {{ $items->withQueryString()->links() }}
                        </div>

                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @include('includes.delete-modal')
@endsection
@push('script')
    <script>
        $('.rounded').text(function(i, curr) {
            return parseFloat(curr).toFixed(2)
        })
    </script>
@endpush
