@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h5>Category</h5>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Category</li>
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
                            <h3 class="card-title">Category List</h3>
                            <div class="card-tools">
                                <div class="float-right">
                                    @can('create category')
                                        <a href="{{ route('category.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-plus"> </i> Create Category
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->

                        <!-- /.card-body -->
                        <div class="card-body table-responsive p-0">
                            <div class="container">
                                    @foreach($classes as $class)
                                    <div class="row mt-2 mb-2">
                                        <div class="col-md-9">
                                            <div style="border:1px solid #ddd;height:35px;line-height:35px;width:auto;padding:0px 15px;">
                                                <span class="pr-3 expand text-center" data-target="{{ $class->id }}" style="cursor: pointer">
                                                    <i class="fa fa-plus"></i></span>
                                                <span> {{ $class->name }} </span>
                                            </div>
                                            <div id="{{ $class->id }}" class="collapse">
                                                @foreach($class->categories as $category)
                                                    <div class="mt-2" style="border:1px solid #ddd;height:35px;line-height:35px;width:auto;margin-left:30px;padding:0px 15px;">
                                                        <span> {{ $category->name }} </span>
                                                        <div style="float:right">
                                                            @can('edit category')
                                                            <button type="button" data-class_id="{{ $class->id }}" data-class_name="{{ $class->name }}" data-id="{{ $category->id }}" data-text="{{ $category->name }}" class="btn btn-xs btn-info category-edit">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            @endcan
                                                            @can('delete category')
                                                            <a href="#deleteModal" data-toggle="modal" data-id="{{ $category->id }}"
                                                                data-route="category" class="btn btn-xs btn-danger delete">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                            @endcan
                                                        </div>
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
    <div class="modal fade" id="category-modal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Edit Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="categoryEditForm" action="" method="POST" autocomplete="off">
                @csrf
                @method('PATCH')
                <input type="hidden" class="class_id"/>
                <input type="hidden" class="class_name"/>
                <div class="form-group">
                    <label for="message-text" class="col-form-label">Class:</label>
                    <select id="class_id" class="form-control" name="class_id">
                    </select>
                </div>
                <div class="form-group">
                    <label for="message-text" class="col-form-label">Category:</label>
                    <input type="text" class="form-control category_name" name="name"/>
                </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-primary btn-update-category">Update</button>
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
<script src="../assets/plugins/select2/js/select2.min.js"></script>
<script type="text/javascript">
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
    $('.category-edit').on('click', function(){
        let route_url = "{{ route('category.update',':id') }}";
        let route = route_url.replace(':id', $(this).data('id'))
        $("#categoryEditForm").attr('action',route);
        $('.category_name').val($(this).data('text'))
        $('.class_id').val($(this).data('class_id'))
        $('.class_name').val($(this).data('class_name'))
        $('#category-modal').modal('show')
    })
    $('#category-modal').on('shown.bs.modal', function () {
        $("#class_id").select2({
            dropdownParent: '#category-modal',
	    	minimumInputLength: 2,
	    	placeholder: "Search Class",
			language: {
				noResults: function() {
					return 'No Result';
				},
				searching: function() {
					return "Searching...";
				}
			},
	      
	      	ajax: {
                url: "{{ route('classes.searchbyname') }}",
	        	dataType: 'json',
	        	delay: 250, 
	        	data: function (params) {
	            	return {
	                	q: params.term
	            	};
	        	},
	        	processResults: function (data) {
	          		return {
	            		results: $.map(data, function (item) {
		              		return {
		                		id: item.id,
		                		text: item.text,
		              		}
		            	})
	          		};
                    
	        	},
	        	cache: true
	      	}
	    });

        var option = new Option($('.class_name').val(), $('.class_id').val(), true, true);
        $("#class_id").append(option).trigger('change');
        $("#class_id").trigger({
            type: 'select2:select',
            params: {
                data: { id : $('.class_id').val() , text : $('.class_name').val()}
            }
        });
    })
    $('.btn-update-category').click(function(){
        $("#categoryEditForm").submit()
    })
</script>
@endpush
