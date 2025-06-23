@push('styles')
    <link rel="stylesheet" href=" {{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
@endpush

@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Edit Permission</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Permission</a></li>
                        <li class="breadcrumb-item active">Edit</li>
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
                            <h3 class="card-title">Edit Permission</h3>
                        </div>
                        <form method="POST" action="{{ route('permissions.update', $permission->id) }}" autocomplete="off">
                           @method('patch')
                           @csrf
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-1 col-form-label" for="name">Name</label>
                                    <div class="col-md-6">
                                        <input type="text" name="name" value="{{ $permission->name }}" 
                                            class="form-control" id="name" placeholder="Enter Permission" required>
                                        <x-input-error for="name"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-1 col-form-label" for="title">Title</label>
                                    <div class="col-md-6">
                                        <input type="text" name="title" value="{{ $permission->title }}"
                                            class="form-control" id="title" placeholder="Enter Permission Title" required>
                                        <x-input-error for="title"/>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                                <button type="button" class="btn btn-sm btn-default">
                                    <a href="{{ url('permissions') }}">Cancel</a></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
