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
                    <h5>Create Role</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Role</a></li>
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
                            <h3 class="card-title">Create New Role</h3>
                        </div>
                        <form class="form-horizontal" action="{{ route('roles.store') }}" method="POST" autocomplete="off">
                            @csrf
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-1 col-form-label" for="name">Name</label>
                                    <div class="col-md-6">
                                        <input type="text" name="name" value="{{ old('name') }}"
                                            class="form-control" id="name" placeholder="Enter Role" required>
                                        <x-input-error for="name"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="permissions" class="form-label">
                                        <input  type="checkbox" 
                                                name="all_permission"
                                                style="margin-right:5px"
                                        >
                                        Assign Permissions
                                    </label>
                                </div>
                                <div id="accordion">
                                    @foreach($main_permissions as $key => $main_permission)
                                    <div class="card">
                                        <div class="card-header" id="{{ 'heading'.$key }}" style="padding:5px">
                                        <h5 class="mb-0">
                                            <button class="btn permission_toggle" data-toggle="collapse" data-target="{{ '#collapse'.$key }}" aria-expanded="true" aria-controls="{{ '#collapse'.$key }}">
                                            {{ $main_permission->title }}
                                            </button>
                                        </h5>
                                        </div>

                                        <div id="{{ 'collapse'.$key }}" class="collapse show" aria-labelledby="{{ 'heading'.$key }}" >
                                        <div class="card-body">
                                            <div class="row">
                                            @foreach($main_permission->permissions as $permission)
                                                <div class="col-md-3">
                                                    <input  type="checkbox" 
                                                            name="permission[]"
                                                            value="{{ $permission->id }}"
                                                            class='permission'
                                                            style="margin-right:5px"
                                                    >
                                                    {{ $permission->name }}
                                                </div>
                                            @endforeach
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    <x-input-error for="permission"/>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-sm btn-primary">Create</button>
                                <button type="button" class="btn btn-sm btn-default">
                                    <a href="{{ url('roles') }}">Cancel</a></button>
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
<script type="text/javascript">
    $(document).ready(function() {
        $('[name="all_permission"]').on('click', function() {

            if($(this).is(':checked')) {
                $.each($('.permission'), function() {
                    $(this).prop('checked',true);
                });
            } else {
                $.each($('.permission'), function() {
                    $(this).prop('checked',false);
                });
            }

        });
    });
    $('.permission_toggle').click(function(e){
        e.preventDefault();
    })
</script>
@endpush
