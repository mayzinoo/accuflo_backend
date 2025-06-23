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
                    <h5>Create Class</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Class</a></li>
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
                            <h3 class="card-title">Create New Class</h3>
                        </div>
                        <form class="form-horizontal" action="{{ route('class.store') }}" method="POST" autocomplete="off">
                            @csrf
                            <div class="card-body">

                                <div class="form-group row">
                                    <label class="col-sm-1 col-form-label" for="name">Class</label>
                                    <div class="col-md-6">
                                        <input type="text" name="name" value="{{ old('name') }}"
                                            class="form-control" id="name" placeholder="Enter class" required>
                                        <x-input-error for="name"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-1 col-form-label" for="name">Type</label>
                                    <div class="col-md-6">
                                       <select name="type" id="type" style="width:100%;">
                                            @foreach($liquor_types as $liquor_type)
                                            <option value="{{$liquor_type}}">{{$liquor_type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                                <button type="button" class="btn btn-sm btn-default">
                                    <a href="{{ url('class') }}">Cancel</a></button>
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
    $(document).ready(function(){
        $("#type").select2();
    })
</script>
@endpush