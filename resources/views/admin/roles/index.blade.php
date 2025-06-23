@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Role</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Role</li>
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
                            <h3 class="card-title">Role List</h3>
                            <div class="card-tools">
                                <div class="float-right">
                                    <a class="btn btn-sm btn-dark" data-toggle="collapse" href="#filter" role="button"
                                        aria-expanded="false" aria-controls="filter">
                                        Filter
                                    </a>
                                    @can('create role')
                                    <a href="{{ route('roles.create') }}" class="btn btn-sm btn-primary">
                                        <i class="fa fa-plus"> </i> Create Role
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
                                                value="{{ request('name') }}" placeholder="Search by role">
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
                                        <th width="100px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($roles as $index => $role)
                                        <tr>
                                            <td>{{ $roles->firstItem() + $index }}</td>
                                            <td>{{ $role->name }}</td>
                                            <td>
                                            @can('edit role')
                                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-xs btn-info">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            @endcan
                                            @can('delete role')
                                                <a href="#deleteModal" data-toggle="modal" data-id="{{ $role->id }}"
                                                    data-route="roles" class="btn btn-xs btn-danger delete">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">There is no data.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            {{ $roles->withQueryString()->links() }}
                        </div>

                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @include('includes.delete-modal')
@endsection
