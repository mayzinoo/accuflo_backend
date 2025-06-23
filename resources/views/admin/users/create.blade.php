
@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href=" {{ asset('assets/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
@endpush
@section('content')<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h5>Create User</h5>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Users</a></li>
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
                <h3 class="card-title">Create New User</h3>
            </div>
            <form class="form-horizontal form-validation" action="{{ route('users.store') }}" method="POST" autocomplete="off">
                @csrf                
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="name">Name</label>
                        <div class="col-md-6">
                            <input type="text" name="name" value="{{old('name')}}" class="form-control" id="name" placeholder="Enter name" required>
                            <x-input-error for="name"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="email">Email address</label>
                        <div class="col-md-6">
                            <input type="email" name="email" value="{{old('email')}}" class="form-control" id="email" placeholder="Enter email" required>
                            <x-input-error for="email"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="password">Password</label>
                        <div class="col-md-6">
                            <input type="password" name="password" class="form-control" id="password" placeholder="Password" required>
                            <x-input-error for="password"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="confirm_password">Confirm Password</label>
                        <div class="col-md-6">
                            <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirm Password" required>
                            <x-input-error for="confirm_password"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="phone">Phone Number</label>
                        <div class="col-md-6">
                            <input type="text" name="phone_no" value="{{old('phone_no')}}" class="form-control" id="phone" placeholder="Phone Number" required onkeypress="return isNumber(event)">
                            <x-input-error for="phone_no"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label" for="role">Role</label>
                        <div class="col-md-6">
                            @php
                            $user_types = \App\GlobalConstants::USER_TYPES;
                            if(Auth::user()->role == 'client'){
                              unset($user_types['super_admin']);
                            }
                            @endphp
                            <select name="role" id="role" style="width:100%;">
                               @foreach($user_types as $key => $user_type)
                               <option @if($key == old('role') ) {{ 'selected' }} @endif value="{{ $key }}">{{ $user_type }}</option>
                               @endforeach
                            </select>
                            <x-input-error for="role"/>
                        </div>
                    </div>
                    <div class="form-group row client-row" @if(Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin' ) style="display:none" @endif>
                        <label class="col-sm-2 col-form-label" for="company_id">Company</label>
                        <div class="col-md-6">
                            <select class="form-control" name="company_id">
                              @foreach($companies as $company)
                              <option @if(old('company_id') == $company->id) selected @endif value="{{ $company->id }}">{{ $company->name }}</option>
                              @endforeach
                            </select>
                            <x-input-error for="company_id"/>
                        </div>
                    </div>
                    <div class="form-group row client-row" @if(Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin' ) style="display:none" @endif>
                        <label class="col-sm-2 col-form-label" for="branch_id">Branch</label>
                        <div class="col-md-6">
                            <select class="form-control" name="branch_id">
                              @foreach($branches as $branch)
                              <option @if(old('branch_id') == $branch->id) selected @endif value="{{ $branch->id }}">{{ $branch->name }}</option>
                              @endforeach
                            </select>
                            <x-input-error for="branch_id"/>
                        </div>
                    </div>
                    <div class="form-group row client-row" @if(Auth::user()->role == 'super_admin' || Auth::user()->role == 'admin') style="display:none" @endif>
                        <label class="col-sm-2 col-form-label" for="permission_role">Permission Role</label>
                        <div class="col-md-6">
                            <select class="form-control" name="permission_role">
                              @foreach($permission_roles as $permission_role)
                              <option @if(old('permission_role') == $permission_role) selected @endif value="{{ $permission_role }}">{{ $permission_role }}</option>
                              @endforeach
                            </select>
                            <x-input-error for="permission_role"/>
                        </div>
                    </div>
                    
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                    <button type="button" class="btn btn-sm btn-default"><a href="{{ url('users') }}">Cancel</a></button>
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
<script src="../assets/plugins/select2/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var old_role = "{{ old('role') }}"
        if(old_role == 'client'){
          $('.client-row').show()
        }
        $("#role").select2();
        $('.form-validation').validate({
            rules: {
              password: {
                minlength: 8
              },
              phone:{
                minlength:8,
                number:true
              }
            },
            
        });

        $( ".date-picker").datepicker({
          dateFormat: "yy-mm-dd",
          minDate: 0
        });
    });

    $("#role").change(function(){
      if($(this).val() == 'super_admin' || $(this).val() == 'admin'){
        $('.client-row').hide()
      }else{
        $('.client-row').show()
      }
    })

    function isNumber(evt){
      var charCode=evt.which? evt.which: evt.keyCode;
      if(evt.which>=48 & evt.which<=57){
        return true;
      }
      return false;
    }
</script>
@endpush