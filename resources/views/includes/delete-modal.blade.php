<div class="modal fade" id="deleteModal" role="dialog" aria-hidden="true">
    <form id="deleteForm" action="" method="POST">
        @csrf
        @method('DELETE')

        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title w-100 text-center">
                        Delete
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    Are you sure you want to proceed?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal"> Cancle </button>
                    <button type="submit" class="btn btn-sm btn-danger"> Delete</button>
                </div>
            </div>
        </div>
    </form>
</div>
@push('script')
<script type="text/javascript">
$(document).on('click','.delete',function(e){
  
   id=$(this).data('id');
   route=$(this).data('route');
   $("#deleteForm").attr('action',`/${route}/${id}`);
   
})
</script>
@endpush