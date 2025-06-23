
@extends('layouts.app')

@section('content')<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Station</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Station</li>
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
            <h3 class="card-title">Station List</h3>
            <div class="card-tools">
              <div class="float-right">
                <a class="btn btn-sm btn-dark" data-toggle="collapse" href="#filter" role="button" aria-expanded="false"
                  aria-controls="filter">
                  Filter
                </a>
                <a href="{{route('station.create')}}" class="btn btn-sm btn-primary">
                    <i class="fa fa-plus"> </i> Create Station
                </a>
              </div>
            </div>
          </div>
          <div class="collapse" id="filter">
            <div class="card-header">
              <form action="">
              <div class="row search-section">
                <div class="col-md-3">
                  <input class="form-control form-control-sm" type="text" name="name" value="{{ request('name') }}" placeholder="Search by station">
                </div>
                <div class="col-md-3">
                  <button type="submit" class="btn btn-sm btn-primary search mb-2">Search</button>
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
                  <th>Station</th>
                  <th>Created At</th>
                  <th width="100px">Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($stations as $index => $station)
                  <tr>
                    <td>{{ $stations->firstItem() + $index }}</td>
                    <td>{{ $station->name }}</td>
                    <td>{{ $station->created_at->toDateString() }}</td>
                    <td>
                        <a href="{{ route('station.edit', $station->id) }}" class="btn btn-xs btn-info">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="#deleteModal" data-toggle="modal" data-id="{{ $station->id }}"
                          data-route="station" class="btn btn-xs btn-danger delete">
                          <i class="fa fa-trash"></i>
                       </a>
                      </a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="text-center">There is no data.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <div class="card-footer">
            {{ $stations->withQueryString()->links() }}
          </div> 
        
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
@include('includes.delete-modal', ['action' => isset($station) ? route('station.destroy', $station->id) : ''])
@endsection