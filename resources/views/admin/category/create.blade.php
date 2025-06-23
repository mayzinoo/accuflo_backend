@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href=" {{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
@endpush
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Create Category</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Category</a></li>
                        <li class="breadcrumb-item active">Create New</li>
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
                            <h3 class="card-title">Create New Category</h3>
                        </div>
                        <form class="form-horizontal" action="{{ route('category.store') }}" method="POST" autocomplete="off">
                            @csrf
                            <div class="card-body">

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="name">Category</label>
                                    <div class="col-md-6">
                                        <input type="text" name="name" value="{{ old('name') }}"
                                            class="form-control" id="name" placeholder="Enter category" required>
                                        <x-input-error for="name"/>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="class_id">Class</label>
                                    <div class="col-md-6"> 
                                        <select name="class_id" id="class_id" style="width:100%;" required>
                                            @forelse($classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @empty
                                                <option value="">Select a class</option>
                                            @endforelse
                                        </select>
                                        <x-input-error for="class_id"/>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                                <button type="submit" class="btn btn-sm btn-default"><a
                                        href="{{ url('category') }}">Cancel</a></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@push('script')
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#class_id").select2();
        });
    </script>
@endpush
