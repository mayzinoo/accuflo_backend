@if ($message = session('success'))
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-success alert-message mb-0 mt-3">
                    <button type="button" class="close" data-dismiss="alert">×</button>    
                    <strong>{{ $message }}</strong>
                </div>
            </div>
        </div>
    </div>
@endif

@if ($message = session('info'))
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info alert-message mb-0 mt-3">
                <button type="button" class="close" data-dismiss="alert">×</button>    
                <strong>{{ $message }}</strong>
            </div>
        </div>
    </div>
</div>
@endif

@if ($message = session('warning'))
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger alert-message mb-0 mt-3">
                <button type="button" class="close" data-dismiss="alert">×</button>    
                <strong>{{ $message }}</strong>
            </div>
        </div>
    </div>
</div>
@endif

@if ($errors->any())
<!-- to show validation message -->
    <div class="alert alert-danger alert-message">
        <button type="button" class="close" data-dismiss="alert">×</button>
        @foreach ($errors->all() as $message)
            <li>{{ $message }}</li>
        @endforeach
    </div>
@endif