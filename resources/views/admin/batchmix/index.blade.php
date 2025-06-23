@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Batch Mix</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Batch Mix</li>
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
                            <h3 class="card-title">Batch Mix List</h3>
                            <div class="card-tools">
                                <div class="float-right">
                                    <a class="btn btn-sm btn-dark" data-toggle="collapse" href="#filter" role="button"
                                        aria-expanded="false" aria-controls="filter">
                                        Filter
                                    </a>
                                    @if ($period_status == 1)
                                    @can('create batch mix')
                                        <a id="add" href="{{ route('batchmix.create') }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fa fa-plus"> </i> New Batch Mix
                                        </a>
                                    @endcan
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="collapse show" id="filter">
                            <div class="card-header">
                                <form action="">
                                    <div class="row search-section">
                                        <div class="col-md-3">
                                            <input class="form-control form-control-sm" type="text" name="name"
                                                value="{{ request('name') }}" placeholder="Search by name">
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

                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Barcode</th>
                                        <th>Code</th>
                                        <th>Method</th>
                                        <th>Description</th>
                                        <th>Size</th>
                                        <th>Empty Weight</th>
                                        <th>Density</th>
                                        <th width="100px">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($batchmixs as $index => $batchmix)
                                        <tr data-toggle="collapse" data-target="#demo{{ $batchmix->id }}"
                                            class="accordion-toggle">
                                            <td class="expand-button">
                                            </td>
                                            <td>{{ $batchmix->name }}</td>
                                            <td>{{ $batchmix->barcode }} </td>
                                            <td>{{ $batchmix->code }}</td>
                                            <td>
                                                <i class="fas fa-sort-numeric-down"></i>
                                                @if ($batchmix->inventory_status == 'yes')
                                                    <i class="fas fa-cubes"></i>
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
                                            <td>
                                                @if ($batchmix->liquid_status == 'no')
                                                    -
                                                @else
                                                    {{ $batchmix->density }}

                                                    @foreach ($BATCHMIX_WEIGHT_UNIT as $key => $weight_unit)
                                                        @if ($batchmix->total_weight_id == $key)
                                                            {{ $weight_unit }}
                                                        @endif
                                                    @endforeach

                                                    @foreach ($BATCHMIX_VOLUME_UNIT as $key => $volume_unit)
                                                        @if ($batchmix->total_volume_id == $key)
                                                            {{ $volume_unit }}
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                            @can('edit batch mix')
                                                <a href="{{ route('batchmix.edit', $batchmix->id) }}"
                                                    class="btn btn-xs btn-info">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            @endcan
                                            @can('delete batch mix')
                                                <a href="#deleteModal" data-toggle="modal" data-id="{{ $batchmix->id }}"
                                                    data-route="batchmix" class="btn btn-xs btn-danger delete">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            @endcan
                                            </td>
                                        </tr>
                                        @foreach ($batchmix->ingredients as $index => $ingredient)
                                            <tr id="demo{{ $batchmix->id }}" class="accordian-body collapse">
                                                <td></td>
                                                <td style="display: flex; justify-content: end;">{{ $index + 1 }}</td>
                                                <td>
                                                    {{ $ingredient->item_name }}
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td> {{ $ingredient->qty }}
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
                                            </tr>
                                        @endforeach
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">There is no data.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                           {{--  <!-- {{ $batchmixs->withQueryString()->links() }} --> --}}
                        </div>
                        <div class="card-footer">
                          {{--   {{ $batchmixs->withQueryString()->links() }} --}}
                        </div>

                    </div>

                    <!-- /.card  -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @include('includes.delete-modal')
@endsection
<script type="text/javascript"></script>
