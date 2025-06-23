<div class="modal fade" id="change-date-period-modal" tabindex="-1" role="dialog" aria-labelledby="vendorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="change_date_title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="" id="frm-change-date-period" method="POST" autocomplete="off">
                @csrf
                @method('PATCH')
                <input type="hidden" name="action_type" value="change_date"/>
                <div class="form-group row">
                    <label for="start_date" class="col-form-label col-md-4">New Start Date:</label>
                    <input type="text" name="start_date" id="start_date" class="col-md-6 form-control datepicker"/>
                </div>
                <div class="form-group row">
                    <label for="end_date" class="col-form-label col-md-4">New End Date:</label>
                    <input type="text" name="end_date" id="end_date" class="col-md-6 form-control datepicker"/>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-sm btn-primary btn-update-date">Save</button>
        </div>
        </div>
    </div>
</div>
@push('script')
<script type="text/javascript">
    $('.btn-update-date').click(function(){
        if(($('#start_date').val()) == '' || ($('#end_date').val() == '')){
            alert("Date need to be filled")
        }else if(new Date($('#end_date').val()) <= new Date($('#start_date').val())){
            alert("End Date must be larger than Start Date")
        }else{
            $("#frm-change-date-period").submit()
        }
    })
</script>
@endpush