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
                    <h5>Edit Company</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Companies</a></li>
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
                            <h3 class="card-title">Edit Company</h3>
                        </div>
                        <form class="form-horizontal form-validation" autocomplete="off" action="{{ route('companies.update', $company->id) }}" method="POST">
                           @csrf
                           @method('PUT')
                            <div class="card-body">

                                <div class="form-group row">
                                    <label class="col-sm-1 col-form-label" for="name">Name</label>
                                    <div class="col-md-6">
                                        <input type="text" name="name" value="{{ old('name') ? old('name') : $company->name }}"
                                            class="form-control" id="name" placeholder="Enter Name" required>
                                        <x-input-error for="name"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-1 col-form-label" for="phone_no">Phone No</label>
                                    <div class="col-md-6">
                                        <input type="text" name="phone_no" value="{{ old('phone_no') ? old('phone_no') : $company->phone_no }}"
                                            class="form-control" id="phone_no" placeholder="Enter Phone No" required>
                                        <x-input-error for="phone_no"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-1 col-form-label" for="address">Address</label>
                                    <div class="col-md-6">
                                        <textarea name="address" class="form-control" placeholder="Enter Address" required>{{ old('address') ? old('address') : $company->address }}</textarea>
                                        <x-input-error for="address"/>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                                <button type="button" class="btn btn-sm btn-default">
                                    <a href="{{ url('companies') }}">Cancel</a></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
