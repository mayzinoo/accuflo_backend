@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Vendor</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Vendor</li>
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
                            <h3 class="card-title">Vendor List</h3>
                            <div class="card-tools">
                                <div class="float-right">
                                    <a class="btn btn-sm btn-dark" data-toggle="collapse" href="#filter" role="button"
                                        aria-expanded="false" aria-controls="filter">
                                        Filter
                                    </a>
                                    @can('create vendor')
                                    <a id="add" href="{{ route('vendor.create') }}" class="btn btn-sm btn-primary">
                                        <i class="fa fa-plus"> </i> New Vendor
                                    </a>
                                    @endcan
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
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Address</th>
                                        <th>Phone</th>
                                        <th>Cell</th>
                                        <th>Fax</th>
                                        <th>Email</th>
                                        <th>Created At</th>
                                        <th width="100px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($vendors as $index => $vendor)
                                        <tr>
                                            <td>{{ $vendors->firstItem() + $index }}</td>
                                            <td>{{ $vendor->name }}</td>
                                            <td>{{ $vendor->code }}
                                            <td>
                                                @if ($vendor->postal_code != 0)
                                                    {{ $vendor->address_line_1 }}
                                                    @if ($vendor->address_line_1 != '')
                                                        <br />
                                                    @endif
                                                    {{ $vendor->address_line_2 }}
                                                    @if ($vendor->address_line_2 != '')
                                                        <br />
                                                    @endif
                                                    {{ $vendor->city }}
                                                    @if ($vendor->city != '')
                                                        <br />
                                                    @endif
                                                    {{ $vendor->state }}
                                                    @if ($vendor->state != '')
                                                        <br />
                                                    @endif
                                                    {{ $vendor->postal_code }},
                                                    @foreach ($COUNTRY as $key => $country)
                                                        @if ($vendor->country_code == $key)
                                                            {{ $country }}
                                                        @endif
                                                    @endforeach
                                                @else
                                                    {{ $vendor->address_line_1 }}
                                                    @if ($vendor->address_line_1 != '')
                                                        <br />
                                                    @endif
                                                    {{ $vendor->address_line_2 }}
                                                    @if ($vendor->address_line_2 != '')
                                                        <br />
                                                    @endif
                                                    {{ $vendor->city }}
                                                    @if ($vendor->city != '')
                                                        <br />
                                                    @endif
                                                    {{ $vendor->state }}
                                                    @foreach ($COUNTRY as $key => $country)
                                                        @if ($vendor->country_code == $key)
                                                            {{ $country }}
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>{{ $vendor->phone }}</td>
                                            <td>{{ $vendor->cell }}</td>
                                            <td>{{ $vendor->fax }}</td>
                                            <td>{{ $vendor->email }}</td>
                                            <td>{{ $vendor->created_at->toDateString() }}</td>
                                            <td>
                                            @can('edit vendor')
                                                <a href="{{ route('vendor.edit', $vendor->id) }}"
                                                    class="btn btn-xs btn-info">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            @endcan
                                            @can('delete vendor')
                                                <a href="#deleteModal" data-toggle="modal" data-id="{{ $vendor->id }}"
                                                    data-route="vendor" class="btn btn-xs btn-danger delete">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">There is no data.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <!-- {{ $vendors->withQueryString()->links() }} -->
                        </div>
                        <div class="card-footer">
                            {{ $vendors->withQueryString()->links() }}
                        </div>

                    </div>
                    <!-- /.card  -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @include('includes.delete-modal')
@endsection
