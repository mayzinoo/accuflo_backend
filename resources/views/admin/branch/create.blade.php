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
                    <h5>Create Branch</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Companies</a></li>
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
                            <h3 class="card-title">Create New Branch</h3>
                        </div>
                        <form class="form-horizontal" action="{{ route('branches.store') }}" method="POST" autocomplete="off">
                            @csrf
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-1 col-form-label" for="company_id">Company</label>
                                    <div class="col-md-6">
                                        <select class="form-control" name="company_id">
                                          @foreach($companies as $company)
                                          <option @if(old('company_id') == $company->id) selected @endif value="{{ $company->id }}">{{ $company->name }}</option>
                                          @endforeach
                                        </select>
                                        <x-input-error for="company_id"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-1 col-form-label" for="name">Name</label>
                                    <div class="col-md-6">
                                        <input type="text" name="name" value="{{ old('name') }}"
                                            class="form-control" id="name" placeholder="Enter Name" required>
                                        <x-input-error for="name"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-1 col-form-label" for="phone_no">Phone No</label>
                                    <div class="col-md-6">
                                        <input type="text" name="phone_no" value="{{ old('phone_no') }}"
                                            class="form-control" id="phone_no" placeholder="Enter Phone No" required>
                                        <x-input-error for="phone_no"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-1 col-form-label" for="address">Address</label>
                                    <div class="col-md-6">
                                        <textarea name="address" class="form-control" placeholder="Enter Address" required>{{ old('address') }}</textarea>
                                        <x-input-error for="address"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-1 col-form-label" for="phone">Period End Date</label>
                                    <div class="col-md-6">
                                        <input type="text" name="period_end_date" value="{{old('period_end_date')}}" class="form-control date-picker" id="period_end_date" placeholder="Period End Date" required>
                                        <x-input-error for="period_end_date"/>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                                <button type="button" class="btn btn-sm btn-default">
                                    <a href="{{ url('branches') }}">Cancel</a></button>
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

$( ".date-picker").datepicker({
  dateFormat: "yy-mm-dd",
  minDate: 0
});

</script>
@endpush
