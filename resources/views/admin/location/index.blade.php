@extends('layouts.app')


@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Location Setup</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Location</li>
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
                            <h3 class="card-title">Location Setup</h3>
                            <div class="card-tools">
                                <div class="float-right">
                                    @can('create station')
                                    <a href="#" class="btn btn-sm btn-primary create_station">
                                        <i class="fa fa-plus"> </i> Create Station
                                    </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->

                        <!-- /.card-body -->
                        <div class="card-body table-responsive p-0">
                            <div class="container">
                                    @foreach($stations as $station)
                                    <div class="row mt-2 mb-t p-2">
                                        <div class="col-md-9">
                                            <div style="border:1px solid #ddd;height:35px;line-height:35px;width:auto;padding:0px 15px;">
                                                <span class="pr-3 expand text-center" data-target="{{ $station->id }}" style="cursor: pointer"><i class="fa fa-plus"></i></span>
                                                <span> {{ $station->name }} </span>
                                                <div style="float:right">
                                                    @can('edit station')
                                                        <button type="button" data-station_id="{{ $station->id }}" data-station_name="{{ $station->name }}" class="btn btn-xs btn-info station-edit">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                    @endcan
                                                    @can('delete station')
                                                        <a href="#deleteModal" data-toggle="modal" data-id="{{ $station->id }}"
                                                            data-route="station" class="btn btn-xs btn-danger delete">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    @endcan
                                                </div>
                                            </div>
                                            <div id="{{ $station->id }}" class="collapse">
                                                @can('create section')
                                                <div class="mt-2 add_section text-center" data-station_id="{{ $station->id }}" style="border:1px solid #ddd;height:35px;line-height:35px;width:auto;margin-left:20px;padding:0px 15px;cursor:pointer;">
                                                    Add Section
                                                </div>
                                                @endcan
                                                @foreach($station->sections as $section)
                                                    <div class="mt-2" style="border:1px solid #ddd;height:35px;line-height:35px;width:auto;margin-left:20px;padding:0px 15px;">
                                                        <span class="pr-3 expand text-center" data-target="{{ 'section_'.$section->id }}" style="cursor: pointer"><i class="fa fa-plus"></i></span>
                                                        <span> {{ $section->name }} </span>
                                                        <div style="float:right">
                                                            @can('edit section')
                                                            <button type="button" data-station_id="{{ $station->id }}" data-section_id="{{ $section->id }}" data-section_name="{{ $section->name }}" class="btn btn-xs btn-info section-edit">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            @endcan
                                                            @can('delete section')
                                                            <a href="#deleteModal" data-toggle="modal" data-id="{{ $section->id }}"
                                                                data-route="section" class="btn btn-xs btn-danger delete">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                            @endcan
                                                        </div>
                                                    </div>
                                                    <div class="mt-2 collapse" id="{{ 'section_'.$section->id }}" style="border:1px solid #ddd;height:35px;line-height:35px;width:auto;margin-left:40px;padding:0px 15px;">
                                                    {{ $section->shelves_count }} Shelves Used in Current Period
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
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
    @include('includes.delete-modal')
    <div class="modal fade" id="create-station-modal" tabindex="-1" role="dialog" aria-labelledby="createStationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Create Station</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="createStationForm" action="{{ route('station.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="form-group">
                    <label for="new-station" class="col-form-label">New Station:</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}"/>
                    <x-input-error for="name"/>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-primary btn-create-station">Create</button>
            </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit-station-modal" tabindex="-1" role="dialog" aria-labelledby="editStationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStationModalLabel">Update Station</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editStationForm" action="" method="POST" autocomplete="off">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <input type="hidden" name="station_id" class="station_id"/>
                    <label for="station" class="col-form-label">Station:</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}"/>
                    <x-input-error for="name"/>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-primary btn-update-station">Update</button>
            </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="create-section-modal" tabindex="-1" role="dialog" aria-labelledby="createSectionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sectionModalLabel">Create Section</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="createSectionForm" action="{{ route('section.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="form-group">
                    <input type="hidden" class="station_id" name="station_id" value=""/>
                    <label for="new-section" class="col-form-label">New Section:</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}"/>
                    <x-input-error for="name"/>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-primary btn-create-section">Create</button>
            </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="edit-section-modal" tabindex="-1" role="dialog" aria-labelledby="editSectionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sectionModalLabel">Edit Section</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editSectionForm" action="" method="POST" autocomplete="off">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <input type="hidden" class="section_id" name="section_id" value=""/>
                    <input type="hidden" class="station_id" name="station_id" value=""/>
                    <label for="section" class="col-form-label">Section:</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}"/>
                    <x-input-error for="name"/>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-primary btn-update-section">Update</button>
            </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
<style type="text/css">
    .collapse {
        display: none;
    }
    .collapse.in{
        display: block;
    }
</style>
@endpush
@push('script')
<script type="text/javascript">
    $(document).ready(function(){
        const validation_error = "{{ session('validation_error') }}";
        if(validation_error === 'create_station'){
            $('#create-station-modal').modal('show')
        }else if(validation_error === 'edit_station'){
            let station_id = "{{ session('station_id') }}";
            let route_url = "{{ route('station.update',':id') }}";
            let route = route_url.replace(':id', station_id)
            $("#editStationForm").attr('action',route)
            $("#edit-station-modal").find(".station_id").val(station_id)
            $("#edit-station-modal").modal('show')
        }else if(validation_error === 'create_section'){
            $('#create-section-modal').modal('show')
        }else if(validation_error === 'edit_section'){ 
            let section_id = "{{ session('section_id') }}";
            let station_id = "{{ session('station_id') }}";
            let route_url = "{{ route('section.update',':id') }}";
            let route = route_url.replace(':id', section_id)
            $("#editSectionForm").attr('action',route)
            $("#edit-section-modal").find(".section_id").val(section_id)
            $("#edit-section-modal").find(".station_id").val(station_id)
            $("#edit-section-modal").modal('show')
        }
    })
    $('.expand').on('click', function(){    
        let target_selector = $(this).data('target');
        if($("#"+target_selector).hasClass('in')){
            $("#"+target_selector).removeClass('in')
            $(this).html("<i class='fa fa-plus'></i>")
        }else{
            $("#"+target_selector).addClass('in')
            $(this).html("<i class='fa fa-minus'></i>")
        }
    })
    $('.create_station').on('click', function(){
        $("#create-station-modal").find("input[name='name']").val('')
        $(".invalid-feedback").css("display", "none")
        $('#create-station-modal').modal('show')
    })
    $('.btn-create-station').on('click',function(){
        $("#createStationForm").submit()
    })
    $('.station-edit').on('click', function(){
        let station_id = $(this).data('station_id')
        let station_name = $(this).data('station_name')
        let route_url = "{{ route('station.update',':id') }}";
        let route = route_url.replace(':id', station_id)
        $("#editStationForm").attr('action',route)
        $("#edit-station-modal").find(".station_id").val(station_id)
        $("#edit-station-modal").find("input[name='name']").val(station_name)
        $(".invalid-feedback").css("display", "none")
        $("#edit-station-modal").modal('show')
    })    
    $('.btn-update-station').click(function(){
        $("#editStationForm").submit()
    })
    $('.add_section').on('click', function(){
        $('#createSectionForm').find('.station_id').val($(this).data('station_id'))
        $("#createSectionForm").find("input[name='name']").val('')
        $(".invalid-feedback").css("display", "none")
        $('#create-section-modal').modal('show')
    })
    $('.btn-create-section').on('click', function(){
        $("#createSectionForm").submit()
    })
    $('.section-edit').on('click', function(){
        let section_id = $(this).data('section_id')
        let station_id = $(this).data('station_id')
        let section_name = $(this).data('section_name')
        let route_url = "{{ route('section.update',':id') }}";
        let route = route_url.replace(':id', section_id)
        $("#editSectionForm").attr('action',route)
        $("#edit-section-modal").find(".section_id").val(section_id)
        $("#edit-section-modal").find(".station_id").val(station_id)
        $("#edit-section-modal").find("input[name='name']").val(section_name)
        $(".invalid-feedback").css("display", "none")
        $("#edit-section-modal").modal('show')
    })
    $('.btn-update-section').on('click', function(){
        $("#editSectionForm").submit()
    })
</script>
@endpush
