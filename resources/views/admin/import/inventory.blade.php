@extends('layouts.app')
@section('content')
 <!-- Content Header (Page header) -->
 <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5><i class="fas fa-upload"></i> Inventory Upload</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Inventory Upload</li>
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
                            <h3 class="card-title">Upload File</h3>
                            <div class="card-tools">
                                <div class="float-right">
                                    
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->

                        <!-- /.card-body -->
                        <div class="card-body table-responsive">
                            <div class="container">
                                <form action="{{route('inventory-upload')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                <input type="file" name="inventory_file" id="" class="form-control" required>
                                </div>
                                 
                                 <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- collapse contain -->
                    <!-- collapse contain end -->
                    <!-- /.card -->
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
@endsection