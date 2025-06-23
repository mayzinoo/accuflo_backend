@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Users</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Users</li>
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
                            <h3 class="card-title">User List</h3>
                            <div class="card-tools">
                                <div class="float-right">
                                    <a class="btn btn-sm btn-dark" data-toggle="collapse" href="#filter" role="button"
                                        aria-expanded="false" aria-controls="filter">
                                        Filter
                                    </a>
                                    @can('create user')
                                    <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">
                                        <i class="fa fa-plus"> </i> Create User
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
                                            <input class="form-control form-control-sm" type="text" name="email"
                                                value="{{ request('email') }}" placeholder="Search by email">
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
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Company / Branch</th>
                                        <th>Role</th>
                                        <th>Phone No.</th>
                                        <th>Created At</th>
                                        <th width="100px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $index => $user)
                                        <tr>
                                            <td>{{ $users->firstItem() + $index }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ optional($user->company)->name}} {{ ($user->role == 'client') ? '/' : '' }}  {{ optional($user->branch)->name }}</td>
                                            <td>{{ ($user->role == 'client') ? (isset($user->roles[0]) ? $user->roles[0]->name : '') : 'Super Admin' }}</td>
                                            <td>{{ $user->phone_no }}</td>
                                            <td>{{ $user->created_at->toDateString() }}</td>
                                            <td>
                                                @can('edit user')
                                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-xs btn-info">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @endcan
                                                {{-- <a href="#deleteModal" data-toggle="modal" data-id="{{ $user->id }}" 
                              class="btn btn-xs btn-danger">
                            <i class="fa fa-trash"></i>
                        </a> --}}
                                                @can('delete user')
                                                <a href="#deleteModal" data-toggle="modal" data-id="{{ $user->id }}"
                                                    data-route="users" class="btn btn-xs btn-danger delete">
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
                            <!-- {{ $users->withQueryString()->links() }} -->
                        </div>
                        <div class="card-footer">
                            {{ $users->withQueryString()->links() }}
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    @include('includes.delete-modal')
@endsection
